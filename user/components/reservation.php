<?php

require('../../conf/function.inc.php');
session_start();

$sql = "SELECT 
parcelle.parcelle_id,
parcelle.jardin_id,
parcelle.user_id,
parcelle.isAccepted,
parcelle.parcelle_nom,
users.user_nom,
Jardin.jardin_nom,
Jardin.jardin_position,
Jardin.jardin_image,
plantations.plantation_nom,
plantations.plantation_id
FROM parcelle 
INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
LEFT JOIN users ON parcelle.user_id = users.user_id
LEFT JOIN plantations ON parcelle.plantation_id = plantations.plantation_id
WHERE Jardin.user_id = :userID";


$db = getConnection();

$query = $db->prepare($sql);

$query->bindParam(':userID', $_SESSION['id']);

$query->execute();


$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);


$jardins = findAll("Jardin");

$users = findAll("users");

$plantations = findAll("plantations");


?>

<div class="flex flex-wrap gap-8" id="listing">
    
    <?php

    if(count($parcelles) <= 0){ ?>
        <p class="mt-8">Vous n'avez aucune réservation...</p>
<?php
    }

    foreach ($parcelles as $parcelle) { 
        $isAvailable = $parcelle['isAccepted'] == 1 ? false : true;  
        $isWaiting = ($isAvailable && $parcelle['user_nom'] != null) ? true : false;
        ?>
        <div class="border p-4 rounded-sm w-48">
            <h2><?= $parcelle['jardin_nom'] ?></h2>
            <p>Type: <span class="font-bold"><?= $parcelle['plantation_nom'] ?></span></p>
            <div class="mt-4">
                <h3 class="font-bold">Status</h3>
                <?php
                    if($isWaiting && $isAvailable){ ?>
                    <p>Demande en cours</p>
                    <div class="flex flex-row mt-2 gap-2">
                        <a  class="bg-lime-800 text-white py-2 px-4 rounded-sm" onclick="acceptRequest('accepted', <?= $parcelle['parcelle_id'] ?>)">Accepter</a>
                        <a href="../process/plot/denied.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm" onclick="acceptRequest('denied', <?= $parcelle['parcelle_id'] ?>)">Refuser</a>
                    </div>
<?php
                    }else if(!$isWaiting && $isAvailable){ ?>
                        <p>Disponible</p>
                    <?php
                    }else if(!$isWaiting && !$isAvailable) { ?>
                        <p>Détenu par <span class="font-bold"><?= $parcelle['user_nom']?></span></p>
                    <?php
                    }
                ?>
            </div>
            <a href="../../garden/single.php?id=<?= $parcelle['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
            <a href="../process/deletePlot.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(<?= json_encode($parcelle) ?>)'>Modifier</button>
        </div>
        <?php
    }
    ?>

</div>


<?php
// Formulaire d'édition de parcelle
?>
<div id="editPlotForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editForm" action="#" method="POST" onsubmit="editPlot(event)">
            <p class="font-bold text-xl">Modifier une parcelle</p>
            <button type="button" onclick="closeEditForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex flex-col mt-2">
                <label for="edit_parcelle_nom" class="font-bold">Entrer le nom</label>
                <input type="text" name="edit_parcelle_nom" id="edit_parcelle_nom" class="border pl-4 py-2" placeholder="Nom de la parcelle">
            </div>
            <div class="flex flex-col mt-2">
                <label for="edit_jardin" class="font-bold">Sélectionner le jardin</label>
                <input type="text" name="edit_jardin" id="edit_jardin" class="border pl-4 py-2" disabled>
                <input type="hidden" name="edit_jardin_id" id="edit_jardin_id">
            </div>

            <div class="flex flex-col mt-2">
                <label for="edit_parcelle_type" class="font-bold">Sélectionner la plantation</label>
                <select name="edit_parcelle_type" id="edit_parcelle_type" class="border pl-4 py-2">
                    <?php
                    foreach ($plantations as $plantation) { 
                        ?>
                        <option value="<?= $plantation['plantation_id'] ?>"><?= $plantation['plantation_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div>
            <div class="flex flex-col mt-2">
                <label for="edit_parcelle_type" class="font-bold">Status</label>
                <select name="edit_users" id="edit_status" class="border pl-4 py-2">
                    <option value="waiting">En attente</option>
                    <option value="accepted">Accepter</option>
                    <option value="denied">Refuser</option>
                </select>
            </div>
            <div>
                
            </div>
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Définir la plantation</button>
        </form>
</div>
<?php
// Fin Formulaire d'édition de parcelle
?>








<script>

    function displayForm(){
        const form = document.getElementById("plotForm");
        form.classList.remove("hidden");
    }

    function closeForm()
    {
        const form = document.getElementById("plotForm");
        form.classList.add("hidden");
    }


    function displayEditForm(parcelle) {
        const form = document.getElementById("editPlotForm");
        form.classList.remove("hidden");

        document.getElementById("edit_parcelle_nom").value = parcelle.parcelle_nom || '';
        document.getElementById("edit_jardin").value = parcelle.jardin_nom || '';
        document.getElementById("edit_jardin_id").value = parcelle.jardin_id || '';
        form.dataset.parcelleId = parcelle.parcelle_id;
    }

    function setSelectOption(selectId, value) {
        const selectElement = document.getElementById(selectId);
        for (let i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value == value) {
                selectElement.selectedIndex = i;
                break;
            }
        }
    }

    function closeEditForm(event)
    {
        const form = document.getElementById("editPlotForm");
        form.classList.add("hidden");
    }

    async function editPlot(event)
    {
        event.preventDefault();
        let name = document.getElementById("edit_parcelle_nom");
        let jardin = (document.getElementById("edit_jardin_id"));
        let type = (document.getElementById("edit_parcelle_type"));
        let status = document.getElementById("edit_status");
        let plotID  = document.getElementById("editPlotForm").dataset.parcelleId;

        $.ajax({
            type: "POST",
            url: "../api/plot/update/owner.php",
            data: {
                name: name.value,
                jardin: jardin.value,
                type: type.value,
                status: status.value,
                id: plotID
            },
            dataType: "JSON",
            success: function (response) {
                fetchPlot();
                closeEditForm();
            }
        });
    }



    // Récupérer les parcelles et les afficher dynamiquement

    async function fetchPlot()
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/plot/getPlots.php",
                dataType: "JSON",
                success: function (response) {
                    displayPlot(response);
                    closeForm();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }


    async function acceptRequest(status, id)
    {
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/plot/update/status.php",
                data: {
                    isAccepted: status,
                    id: id,
                    status: status
                },
                dataType: "JSON",
                success: function (response) {
                    fetchPlot();
                }
            });
        } catch (error) {
            
        }
    }

    function displayPlot(data) {
    const wrapper = $("#listing");
    wrapper.empty();

    data.forEach(element => {
        let isAvailable = element.isAccepted == 1 ? false : true;
        let isWaiting = (isAvailable && element.user_nom != null) ? true : false;

        let plotDetails;

        if (isAvailable && !isWaiting) {
            plotDetails = `<p>Disponible</p>`;
        } else if (isAvailable && isWaiting) {
            plotDetails = `
                <p>Demande en cours</p>
                <div class="flex flex-row mt-2 gap-2">
                    <a class="bg-lime-800 text-white py-2 px-4 rounded-sm" onclick="acceptRequest('accepted', ${element.parcelle_id})">Accepter</a>
                    <a href="../process/plot/denied.proc.php?id=${element.parcelle_id}" class="bg-red-800 text-white py-2 px-4 rounded-sm" onclick="acceptRequest('denied', ${element.parcelle_id})">Refuser</a>
                </div>
            `;
        } else if (!isAvailable && !isWaiting) {
            plotDetails = `<p>Détenu par <span class="font-bold">${element.user_nom}</span></p>`;
        }

        let type = element.plantation_nom !== null ? `<p>Type: <span class="font-bold">${element.plantation_nom}</span></p>` : '';

        const div = `
            <div class="border p-4 rounded-sm w-48">
                <h2>${element.jardin_nom}</h2>
                ${type}
                <div class="mt-4">
                    <h3 class="font-bold">Status</h3>
                    ${plotDetails}
                </div>
                <a href="../../garden/single.php?id=${element.jardin_id}" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                <a href="../process/plot/delete.proc.php?id=${element.parcelle_id}" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
                <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(${JSON.stringify(element)})'>Modifier</button>
            </div>
        `;

        wrapper.append(div);
    });
}




</script>