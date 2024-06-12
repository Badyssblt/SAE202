<?php

require('../../conf/function.inc.php');

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header('Location: ./signin.php');
}
$userID = $_SESSION['id'];
$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id WHERE Jardin.user_id = $userID GROUP BY Jardin.jardin_id, users.user_nom ";
$jardins = sql($sql);
?>


<div>
    <ul class="flex flex-row gap-4">
        <button onclick="showMenu('listing')" class="border text-black rounded-full py-2 px-4">Mes jardins</button>
        <button onclick="showMenu('adding')" class="border text-black rounded-full py-2 px-4">Ajouter un jardin</button>
    </ul>
</div>




<?php
// Liste des jardins
?>

<div id="garden" class="flex flex-row">
    <div class="flex flex-wrap items-center justify-start mt-4" id="listing">
        <?php
        if(count($jardins) <= 0){ ?>
            <p>Vous n'avez aucun jardin</p>
            <button onclick="showMenu('adding')" class="border px-4 py-2 ml-2">Créez-en un maintenant</button>
        <?php
        }
        foreach ($jardins as $jardin) {
        ?>
            <div class="border rounded-lg flex flex-col gap-4 w-96 p-4 shadow-md">
                <img src="../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full rounded-lg">
                <h3 class="text-center font-bold underline"><?= $jardin['jardin_nom'] ?></h3>
                <div class="flex flex-col p-4 gap-2">
                    <p class="text-center">Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>
                    <h4 class="text-center">Propriétaire: <span class="font-bold"><?= $jardin['user_nom'] ?></span></h4>
                    <div class="flex flex-wrap gap-4">
                        <button class="border text-black py-2 px-4 rounded-sm" onclick='displayEditForm(<?= json_encode($jardin); ?>)'>Modifier</button>
                        <button onclick="displayVerificationDelete(<?= $jardin['jardin_id'] ?>)" class="bg-red-800 text-white py-2 px-4 rounded-sm">Supprimer</button>
                        <button onclick="displayForm('<?= $jardin['jardin_nom'] ?>', <?= $jardin['jardin_id'] ?>)" class="bg-lime-800 text-white py-2 px-4 rounded-sm">Ajouter une parcelle</button>
                        <a href="../../garden/single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm">Voir plus</a>
                    </div>
                </div>

            </div>
        <?php
        }

        ?>
    </div>
</div>







<?php
// Ajouter un jardin
?>
<div class="flex items-center justify-center hidden" id="adding">
    <form action="/process/addGarden.proc.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
        <div class="flex flex-col">
            <label for="name">Entrer le nom du jardin</label>
            <input type="text" name="name" id="name" placeholder="Nom du jardin" class="border pl-4 py-2">
        </div>
        <div class="flex flex-col">
            <label for="autcomplete">Entrer la position du jardin</label>
            <input type="text" name="position" id="autocomplete" class="border pl-4 py-2" placeholder="Position du jardin">
            <div id="suggestions"></div>
        </div>

        <input type="hidden" name="coor" id="coor">


        <input type="file" name="image" id="image">
        <button type="submit" class="bg-black text-white rounded-md py-2">Créer</button>
        <?php
        if (isset($_SESSION['error']['message'])) { ?>
            <p class="text-red-800"><?= $_SESSION['error']['message'] ?></p>
        <?php
        } elseif (isset($_SESSION['success']['message'])) { ?>
            <p class="text-red-800"><?= $_SESSION['success']['message'] ?></p>
        <?php
        }
        ?>
    </form>
</div>



<?php
// Formulaire d'édition d'un jardin
?>
<div id="editing" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <form method="POST" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editGardenForm" action="../process/editGarden.proc.php" enctype="multipart/form-data">
        <p id="edit_text"></p>
        <button type="button" onclick="closeEditForm()" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col">
            <label for="name">Entrer le nom du jardin</label>
            <input type="text" name="nameEdit" id="nameEdit" placeholder="Nom du jardin" class="border pl-4 py-2">
        </div>
        <div class="flex flex-col">
            <label for="autcompleteEdit">Entrer la position du jardin</label>
            <input type="text" name="positionEdit" id="autocompleteEdit" class="border pl-4 py-2" placeholder="Entrer à nouveau la position du jardin">
            <div id="suggestionsEdit"></div>
        </div>

        <input type="hidden" name="coor" id="coorEdit">
        <input type="hidden" name="id" id="idEditing">


        <input type="file" name="image" id="image">
        <button type="submit" class="bg-black text-white rounded-md py-2">Modifier un jardin</button>
    </form>
</div>
<?php
// Fin Formulaire d'édition d'un jardin
?>



<?php
// Formulaire Vérification suppression jardin
?>
<div id="deleteVerif" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <form method="POST" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editGardenForm" action="../process/editGarden.proc.php" enctype="multipart/form-data">
        <h3 class="font-bold py-2">Voulez-vous supprimer ce jardin ?</h3>
        <button type="button" onclick="displayVerificationDelete()" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <input type="hidden" name="jardinID" id="jardinIdDelete">
        <div>
            <button onclick="deleteGarden(event)" class="bg-red-800 text-white py-2 px-4 rounded-sm">Supprimer</button>
            <button class="border px-4 py-2">Annuler</button>
        </div>
    </form>
</div>
<?php
// Fin Vérification suppression jardin
?>


<?php
// Formulaire d'ajout de parcelle
?>
<div id="plantation_form" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <form class="flex py-24 flex-col bg-white py-8 px-10 rounded-sm relative w-full h-screen md:w-fit md:h-fit" id="addPlotForm" onsubmit="addPlot(event)">
        <p id="plantation_text"></p>
        <button onclick="closeForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col mt-2">
            <label for="plantation" class="font-bold">Entrer le nom</label>
            <input type="text" name="plantation_type" id="plantation" class="border pl-4 py-2" placeholder="Nom de la parcelle">
        </div>
        <div>

        </div>
        <input type="hidden" name="jardin_id" id="jardin_id">
        <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Créer une parcelle</button>
    </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>




<script>
    function showMenu(edit) {
        const div = document.getElementById(edit);
        if (edit === "listing") {
            div.classList.remove("hidden");
            (document.getElementById("adding")).classList.add("hidden");
        } else if (edit === "adding") {
            div.classList.remove("hidden");
            (document.getElementById("listing")).classList.add("hidden");
        } else if (edit === "editing") {
            div.classList.remove("hidden");
            (document.getElementById("adding")).classList.add("hidden");
        }
    }

    function closeForm() {
        event.preventDefault();
        const form = document.getElementById("plantation_form");
        form.classList.add("hidden");
    }

    function displayForm(name, id) {
        const form = document.getElementById("plantation_form");
        const inputHiddens = document.getElementById("jardin_id");
        inputHiddens.value = id;
        const plantationText = document.getElementById("plantation_text");
        const nameSpan = document.createElement("span");
        nameSpan.classList.add("font-bold");
        nameSpan.textContent = name;
        plantationText.textContent = "Ajouter une parcelle à ";
        plantationText.append(nameSpan);
        form.classList.remove("hidden");
    }
    

    function displayEditForm(jardin) {
        const form = document.getElementById("editing");
        const inputHidden = document.getElementById("idEditing");
        inputHidden.value = jardin['jardin_id'];
        document.getElementById("nameEdit").value = jardin['jardin_nom'];

        form.classList.remove("hidden");
    }

    function displayVerificationDelete(id)
    {
        const form = document.getElementById("deleteVerif");

        if(!id){
            form.classList.toggle("hidden");
        }else {
            document.getElementById("jardinIdDelete").value = id;
            form.classList.toggle("hidden");
        }
        
    }

    function closeEditForm()
    {
        const form = document.getElementById("editing");
        form.classList.add("hidden");
    }

    async function deleteGarden(event) {
        event.preventDefault();
        const id = document.getElementById("jardinIdDelete").value;
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../../api/garden/delete",
                data: {
                    id
                },
                dataType: "JSON",
                success: function(response) {
                    fetchGardens();
                    displayVerificationDelete();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    async function addPlot(event) {
        event.preventDefault();
        const typeDiv = document.getElementById("plantation");
        const jardinID = (document.getElementById("jardin_id")).value;

        console.log(typeDiv);
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../../api/plot/create/index.php",
                data: {
                    id: jardinID,
                    name: typeDiv.value
                },
                dataType: "JSON",
                success: function(response) {
                    fetchGardens();
                    closeForm();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }



    async function fetchGardens() {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../../api/garden",
                dataType: "JSON",
                success: function(response) {
                    displayGarden(response);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }


    function displayGarden(data) {
        const wrapper = $("#listing");
        wrapper.empty();
        if(data.length <= 0){
            const div = 
            `
            <p>Vous n'avez aucun jardin</p>
            <button onclick="showMenu('adding')" class="border px-4 py-2 ml-2">Créez-en un maintenant</button>
            `;
            wrapper.append(div);
            return;
        }

        data.forEach(element => {
            const div = `<div class="border rounded-lg flex flex-col gap-4 w-96 p-4 shadow-md">
                <img src="../assets/images/uploads/garden/${element['jardin_image']}" alt="" class="w-full rounded-lg">
                <h3 class='text-center font-bold underline'>${element['jardin_nom']}</h3>
                <div class='flex flex-col p-4 gap-2'>
                <p class="text-center">Nombre de parcelle: <span class="font-bold">${element['parcelle_count']}</span></p>
                <h4 class="text-center">Propriétaire: <span class="font-bold">${element['user_nom']}</span></h4>
                <div class="flex flex-wrap gap-4">
                    <button class="border text-black py-2 px-4 rounded-sm" onclick="displayEditForm(${JSON.stringify(element)})">Modifier</button>
                    <button onclick="deleteGarden(${element['jardin_id']})" class="bg-red-800 text-white py-2 px-4 rounded-sm">Supprimer</button>
                    <button onclick="displayForm('${element['jardin_nom']}', ${element['jardin_id']})" class="bg-lime-800 text-white py-2 px-4 rounded-sm">Ajouter une parcelle</button>
                    <a href="../../garden/single.php?id=${element['jardin_id']}" class="bg-black text-white py-2 px-4 rounded-sm">Voir plus</a>
                </div>
                </div>
                
            </div>`;
            wrapper.append(div);
        });
    }

    const apiKey = "6a1c8957d6f94b9c85b3ccd98b09287f";

    function fetchAutocompleteSuggestions(query) {
        if (query.length >= 3) {
            $.ajax({
                url: `https://api.geoapify.com/v1/geocode/autocomplete`,
                data: {
                    apiKey: apiKey,
                    text: query
                },
                success: function(data) {
                    $('#suggestions').empty();
                    data.features.forEach(feature => {
                        const {
                            lon,
                            lat,
                            formatted
                        } = feature.properties;
                        $('#suggestions').append(`<li data-lon="${lon}" data-lat="${lat}" data-formatted="${formatted}">${formatted}</li>`);
                    });
                }
            });
        } else {
            $('#suggestions').empty();
        }
    }

    function displaySelectedCoordinates(lon, lat) {
        $('#selected-coordinates').text(`Longitude: ${lon}, Latitude: ${lat}`);
    }


    function fetchAutocompleteSuggestionsEdit(query) {
        if (query.length >= 3) {
            $.ajax({
                url: `https://api.geoapify.com/v1/geocode/autocomplete`,
                data: {
                    apiKey: apiKey,
                    text: query
                },
                success: function(data) {
                    $('#suggestionsEdit').empty();
                    data.features.forEach(feature => {
                        const {
                            lon,
                            lat,
                            formatted
                        } = feature.properties;
                        $('#suggestionsEdit').append(`<li data-lon="${lon}" data-lat="${lat}" data-formatted="${formatted}">${formatted}</li>`);
                    });
                }
            });
        } else {
            $('#suggestionsEdit').empty();
        }
    }

    function displaySelectedCoordinatesEdit(lon, lat) {
        $('#selected-coordinatesEdit').text(`Longitude: ${lon}, Latitude: ${lat}`);
    }

    $(document).ready(function() {

        // AJOUTER JARDIN

        $('#autocomplete').on('input', function() {
            const query = $(this).val();
            fetchAutocompleteSuggestions(query);
        });

        $('#suggestions').on('click', 'li', function() {
            const lon = $(this).data('lon');
            const lat = $(this).data('lat');
            const formatted = $(this).data('formatted');

            $('#autocomplete').val(formatted);

            document.getElementById("coor").value = lat + "," + lon;
            displaySelectedCoordinates(lon, lat);
            $('#suggestions').empty();
        });

        // MODIFIER JARDIN

        $('#autocompleteEdit').on('input', function() {
            const query = $(this).val();
            fetchAutocompleteSuggestionsEdit(query);
        });

        $('#suggestionsEdit').on('click', 'li', function() {
            const lon = $(this).data('lon');
            const lat = $(this).data('lat');
            const formatted = $(this).data('formatted');

            $('#autocompleteEdit').val(formatted);

            document.getElementById("coorEdit").value = lat + "," + lon;
            displaySelectedCoordinatesEdit(lon, lat);
            $('#suggestionsEdit').empty();
        });
    });
</script>