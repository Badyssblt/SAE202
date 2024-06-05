<?php
// BACKEND
require('../conf/header.inc.php');
require('../conf/function.inc.php');

if(isset($_SESSION['id'])){
    $userID = $_SESSION['id'];
}else {
    $userID = 0;
}

$db = getConnection();

$sql_jardin = "SELECT 
                Jardin.jardin_id, 
                Jardin.jardin_nom, 
                Jardin.jardin_position, 
                Jardin.jardin_image, 
                users.user_nom AS jardin_user,
                users.user_id AS jardin_user_id
            FROM 
                Jardin 
            INNER JOIN 
                users ON Jardin.user_id = users.user_id 
            WHERE 
                Jardin.jardin_id = :id";

$query_jardin = $db->prepare($sql_jardin);
$query_jardin->bindParam(':id', $_GET['id']);
$query_jardin->execute();
$jardin = $query_jardin->fetch(PDO::FETCH_ASSOC);

// Vérifie si l'utilisateur connecté est le propriétaire du jardin
$owner = false;
if(isset($_SESSION['id']) && $jardin['jardin_user_id'] == $_SESSION['id']){
    $owner = true;
}








$sql_parcelles = "SELECT 
                    parcelle.parcelle_id, 
                    parcelle.parcelle_type,
                    parcelle.parcelle_nom,
                    users.user_nom AS parcelle_user,
                    parcelle.isAccepted
                FROM 
                    parcelle 
                LEFT JOIN 
                    users ON parcelle.user_id = users.user_id
                WHERE 
                    parcelle.jardin_id = :id";

$query_parcelles = $db->prepare($sql_parcelles);
$query_parcelles->bindParam(':id', $_GET['id']);
$query_parcelles->execute();
$parcelles = $query_parcelles->fetchAll(PDO::FETCH_ASSOC);


if ($jardin) {
    function parsePosition($position)
    {
        return explode(",", $position);
    }

    $position = parsePosition($jardin['jardin_position']);

    $latitude = $position[0];
    $longitude = $position[1];
}

// FIN BACKEND
?>


<div class="w-full flex flex-row px-8 gap-4">
    <div class="w-1/2">
        <img src="../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full">
    </div>
    <div class="w-1/2">
        <h2 class="font-bold text-2xl text-center"><?= $jardin['jardin_nom'] ?></h2>
        <div class="p-8">
            <h3 class="font-bold text-xl">Parcelles :</h3>
            <div class="grid grid-cols-3 gap-4 mt-4" id="listing">
                <?php
                    foreach($parcelles as $parcelle){ 
                        $isAvailable = $parcelle['isAccepted'] == 1 ? false : true;  
                        $isWaiting = ($isAvailable && $parcelle['parcelle_user'] != null) ? true : false;
                        ?>
                        <div class="w-56 relative">
                        <?php
                        if($owner){ ?>
                        <button onclick="deletePlot(<?= $parcelle['parcelle_id'] ?>)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <?php
                        }
                    
                            if($isAvailable && !$isWaiting){  ?>
                            <p class="font-bold"><?= $parcelle['parcelle_nom'] ?></p>                      
                            <button onclick="updatePlot(<?= $parcelle['parcelle_id'] ?>)" class="bg-black text-white py-2 px-4 rounded-sm mt-2 inline-block">Réserver</button>
                            <?php
                                if($parcelle['parcelle_type'] !== null){ ?>
                                <p>Type : <span class="font-bold"><?= $parcelle['parcelle_type'] ?></span></p>
                            <?php
                                }
                            ?>
                            <?php
                            }elseif($isWaiting){ ?>
                            <p class="font-bold"><?= $parcelle['parcelle_nom'] ?></p> 
                            <button class="bg-black text-white py-2 px-4 rounded-sm mt-2 inline-block">Réservation en cours</button>
                            <?php
                                if($parcelle['parcelle_type'] !== null){ ?>
                                <p>Type : <span class="font-bold"><?= $parcelle['parcelle_type'] ?></span></p>
                                <?php

                                }
                            }else { ?>
                            <p>Propriétaire : <span class="font-bold"><?= $parcelle['parcelle_user'] ?></span></p>
                            <?php
                                if($parcelle['parcelle_type'] !== null){ ?>
                                <p>Type : <span class="font-bold"><?= $parcelle['parcelle_type'] ?></span></p>
                                <?php
                                }
                            }
                        ?>
                        </div>
                    <?php
                    }
                ?>
            </div>
        </div>

        <?php
            // Alerte de la réservation
        ?>
        <div role="alert" class="rounded-xl border border-gray-100 bg-white p-4 hidden fixed bottom-0 right-0 z-50" id="alert">
            <div class="flex items-start gap-4">
                <span class="text-green-600">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-6 w-6"
                >
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                </span>

                <div class="flex-1">
                <strong class="block font-medium text-gray-900"> Votre réservation a été prise en compte </strong>

                <p class="mt-1 text-sm text-gray-700">Votre demande sera examinée auprès d'un administrateur</p>
                </div>

                <button class="text-gray-500 transition hover:text-gray-600">
                <span class="sr-only">Dismiss popup</span>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-6 w-6"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                </button>
            </div>
        </div>

        <?php
            // Affichage de la carte avec OpenStreetMap
        ?>
        <div id="map" class="mt-4">

        </div>
    </div>
</div>

<script>

                    
    let isOwner = <?= json_encode($owner) ?>;

    // Gestion des parcelles
    async function deletePlot(id)
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/plot/delete/index.php",
                data: {
                    id
                },
                dataType: "JSON",
                success: function (response) {
                 fetchPlot();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    async function fetchPlot()
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/plot/index.php",
                data: {
                    id: <?= $jardin['jardin_id'] ?>
                },
                dataType: "JSON",
                success: function (response) {
                    displayPlot(response);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    function displayPlot(data, userID)
    {
        const wrapper = $("#listing");
        wrapper.empty();
        data.forEach(element => {
        let isAvailable = element.isAccepted == 1 ? false : true;
        let isWaiting = (isAvailable && element.parcelle_user != null) ? true : false;

        let plotDetails;

        let type;

        let closeButton;

        if(isOwner){
            closeButton = 
            `
            <button onclick="deletePlot(${element['parcelle_id']})" class="absolute top-0 right-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
        }else {
            closeButton = ``;
        }

        if(element['parcelle_type'] !== null){
            type = `<p>Type : <span class="font-bold">${element['parcelle_type']}</span></p>`;
        }else {
            type = ``;
        }

        if (isAvailable && !isWaiting) {
            plotDetails = `
                <p class="font-bold">${element['parcelle_nom']}</p>
                <button onclick="updatePlot(${element['parcelle_id']})" class="bg-black text-white py-2 px-4 rounded-sm mt-2 inline-block">Réserver</button>
                ${type}
            `;
        } else if(isAvailable && isWaiting){
            plotDetails = `
                <p class="font-bold">${element['parcelle_nom']}</p>
                <a href="#" class="bg-black text-white py-2 px-4 rounded-sm mt-2 inline-block">Réservation en cours</a>
                ${type}
            `;
        }else if(!isAvailable && !isWaiting){
            plotDetails = `
                <p>Propriétaire : <span class="font-bold">${element['parcelle_user']}</span></p>
                
                ${type}
            `;
        }

        const div = `
            <div class="w-56 relative">
                ${closeButton}
                ${plotDetails}
            </div>
        `;

        wrapper.append(div);
        })
    }


    function setAlert()
    {
        const alert = document.getElementById("alert"); 
        alert.classList.remove("hidden");
        setTimeout(() => {
            alert.classList.add("hidden");
        }, 4000);
    }

    async function updatePlot(id)
    {
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/plot/update/index.php",
                data: {
                    userID: <?=  $userID ?>,
                    id: id
                },
                dataType: "JSON",
                success: function (response) {
                    fetchPlot();
                    setAlert();
                },
                error: function(jqXHR){
                    // L'utilisateur n'est pas connecté
                    if(jqXHR.status === 403){
                        window.location.href = '/user/signin.php';
                    }
                }
            });
        } catch (error) {
            console.log(error);
        }
    }



    // Gestion de la carte NE PAS TOUCHER
    var latitude = <?= $latitude ?>;
    var longitude = <?= $longitude ?>;

    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([longitude, latitude]),
            zoom: 13
        })
    });

    var marker = new ol.Feature({
        geometry: new ol.geom.Point(
            ol.proj.fromLonLat([longitude, latitude])
        )
    });

    var markerLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: [marker]
        })
    });

    map.addLayer(markerLayer);
</script>
