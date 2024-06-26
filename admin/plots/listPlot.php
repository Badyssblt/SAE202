

<?php
$activePage = 'admin';
$title = "Parcelles";

require("../../conf/header.inc.php");
require('../../conf/function.inc.php');


$sql = "SELECT 
parcelle.parcelle_id,
parcelle.jardin_id,
parcelle.user_id,
parcelle.parcelle_superficie,
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
LEFT JOIN plantations ON parcelle.plantation_id = plantations.plantation_id;
";

$db = getConnection();
$query = $db->prepare($sql);
$query->execute();
$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);


$jardins = findAll("Jardin");

$users = findAll("users");

$plantations = findAll("plantations");

?>
<div class="px-8">


<div class="flex flex-col gap-2 md:flex-row">
    <a href="../garden/listGarden.php" class="border text-black py-2 px-4 rounded-full flex justify-center mt-2">Liste des jardins</a>
    <a href="./listPlot.php" class="bg-main text-white py-2 px-4 rounded-full flex justify-center mt-2">Liste des parcelles</a>
    <a href="../users/listUsers.php" class="border text-black py-2 px-4 rounded-full flex justify-center mt-2">Liste des utilisateurs</a>
    <a href="../plantations/listPlantations.php" class="border text-black py-2 px-4 rounded-full flex justify-center mt-2">Liste des plantations</a>
</div>

<h2 class="font-bold text-xl mt-12 mb-8">Liste des parcelles</h2>
<button class="bg-main text-white py-2 px-4 rounded-sm flex justify-center my-2" onclick="displayForm()">Créer une parcelle</button>
<div class="flex flex-wrap gap-8" id="listing">
    
    <?php

    if(count($parcelles) <= 0){ ?>
        <p class="mt-8">Il n'y a aucune parcelle dans la base de donnée...</p>
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
            <a href="../../garden/single.php?id=<?= $parcelle['jardin_id'] ?>" class="bg-main text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
            <a href="../process/plot/delete.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(<?= json_encode($parcelle) ?>)'>Modifier</button>
        </div>
        <?php
    }
    ?>

</div>
    
</div>

<?php
// Formulaire d'ajout de parcelle
?>
<div id="plotForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="typePlotForm" action="#" method="POST">
            <p class="font-bold text-xl">Créer une parcelle</p>
            <button type="button" onclick="closeForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex flex-col mt-2">
                <label for="parcelle_nom" class="font-bold">Entrer le nom</label>
                <input type="text" name="parcelle_nom" id="parcelle_nom" class="border pl-4 py-2" placeholder="Nom de la parcelle">
            </div>
            <div class="flex flex-col mt-2">
                <label for="parcelle_superficie" class="font-bold">Entrer la superficie</label>
                <input type="text" name="parcelle_superficie" id="parcelle_superficie" class="border pl-4 py-2" placeholder="Nom de la parcelle">
            </div>
            <div class="flex flex-col mt-2">
                <label for="jardin" class="font-bold">Sélectionner le jardin</label>
                <select name="jardin" id="jardin" class="border pl-4 py-2">
                    <?php
                    foreach ($jardins as $jardin) { ?>
                        <option value="<?= $jardin['jardin_id'] ?>"><?= $jardin['jardin_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="flex flex-col mt-2">
                <label for="users" class="font-bold">Sélectionner un utilisateur</label>
                <select name="users" id="users" class="border pl-4 py-2">
                    <?php
                    foreach ($users as $user) { ?>
                        <option value="<?= $user['user_id'] ?>"><?= $user['user_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="flex flex-col mt-2">
                <label for="users" class="font-bold">Sélectionner un type de plantation</label>
                <select name="parcelle_type" id="parcelle_type" class="border pl-4 py-2">
                    <?php
                    foreach ($plantations as $plantation) { ?>
                        <option value="<?= $plantation['plantation_id'] ?>"><?= $plantation['plantation_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div>
                
            </div>
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Créer la parcelle</button>
        </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>

<?php
require('../../conf/footer.inc.php');
?>

<?php
// Formulaire d'édition de parcelle
?>
<div id="editPlotForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editForm" action="#" method="POST">
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
                <label for="parcelle_superficie" class="font-bold">Entrer la superficie</label>
                <input type="text" name="edit_superficie" id="edit_superficie" class="border pl-4 py-2" placeholder="Nom de la parcelle">
            </div>
            <div class="flex flex-col mt-2">
                <label for="edit_jardin" class="font-bold">Sélectionner le jardin</label>
                <select name="edit_jardin" id="edit_jardin" class="border pl-4 py-2">
                    <?php
                    foreach ($jardins as $jardin) { ?>
                        <option value="<?= $jardin['jardin_id'] ?>"><?= $jardin['jardin_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="flex flex-col mt-2">
                <label for="edit_users" class="font-bold">Sélectionner un utilisateur</label>
                <select name="edit_users" id="edit_users" class="border pl-4 py-2">
                    <?php
                    foreach ($users as $user) { ?>
                        <option value="<?= $user['user_id'] ?>"><?= $user['user_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
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

        const plotForm = document.getElementById("plotForm");
        plotForm.addEventListener('submit', (event) => {
            event.preventDefault();
            createPlot(event)
        });

        document.getElementById("editForm").addEventListener("submit", (event) => {
            editPlot(event);
        });


    function displayForm(){
        const form = document.getElementById("plotForm");
        form.classList.remove("hidden");
    }

    function closeForm()
    {
        const form = document.getElementById("plotForm");
        form.classList.add("hidden");
    }

    async function createPlot(event){
        event.preventDefault();
        let name = document.getElementById("parcelle_nom");
        let jardin = (document.getElementById("jardin"));
        let user = (document.getElementById("users"));
        let type = (document.getElementById("parcelle_type"));
        let superficie = (document.getElementById("parcelle_superficie"))
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/plot/create/admin.php",
                data: {
                    name: name.value,
                    jardin: jardin.value,
                    user: user.value,
                    type: type.value,
                    superficie: superficie.value
                },
                dataType: "JSON",
                success: function (response) {
                    fetchPlot();
                }
            });
        } catch (error) {
            
        }
    }

    function displayEditForm(parcelle) {
        const form = document.getElementById("editPlotForm");
        form.classList.remove("hidden");

        document.getElementById("edit_parcelle_nom").value = parcelle.parcelle_nom || '';
        document.getElementById("edit_jardin").value = parcelle.jardin_id || '';
        document.getElementById("edit_superficie").value = parcelle.parcelle_superficie || '';
        setSelectOption('edit_users', parcelle.user_id);
        setSelectOption('edit_parcelle_type', parcelle.plantation_id);
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
        let jardin = (document.getElementById("edit_jardin"));
        let user = (document.getElementById("edit_users"));
        let type = (document.getElementById("edit_parcelle_type"));
        let status = document.getElementById("edit_status");
        let superficie = document.getElementById("edit_superficie");
        let jardinID = document.getElementById("editPlotForm").dataset.parcelleId;

        $.ajax({
            type: "POST",
            url: "../api/plot/update/admin.php",
            data: {
                name: name.value,
                jardin: jardin.value,
                user: user.value,
                type: type.value,
                status: status.value,
                superficie: superficie.value,
                id: jardinID
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
                url: "../api/plot/admin.php",
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
                <a href="../../garden/single.php?id=${element.jardin_id}" class="bg-main text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                <a href="../process/plot/delete.proc.php?id=${element.parcelle_id}" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
                <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(${JSON.stringify(element)})'>Modifier</button>
            </div>
        `;

        wrapper.append(div);
    });
}




</script>
