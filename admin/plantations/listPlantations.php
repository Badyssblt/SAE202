<?php
$activePage = 'admin';

require("../../conf/header.inc.php");
require('../../conf/function.inc.php');


$plantations = findAll("plantations");


?>
<div class="px-8">


    <div class="flex flex-row gap-8">
        <a href="../garden/listGarden.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des jardins</a>
        <a href="../plots/listPlot.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des parcelles</a>
        <a href="../users/listUsers.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des utilisateurs</a>
        <a href="./listPlantations.php" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Liste des plantations</a>
    </div>

    <h2 class="font-bold text-xl mt-12 mb-8">Liste des plantations</h2>
    <button class="bg-black text-white py-2 px-4 rounded-sm flex justify-center my-2" onclick="displayForm()">Créer une parcelle</button>
    <div class="flex flex-wrap gap-8" id="listing">

        <?php

        if (count($plantations) <= 0) { ?>
            <p class="mt-8">Il n'y a aucune plantation dans la base de donnée...</p>
        <?php
        }

        foreach ($plantations as $plantation) { ?>
            <div class="border p-4 rounded-sm w-48">
                <div>
                    <h2>Nom: <?= $plantation['plantation_nom'] ?></h2>
                </div>
                <a href="../process/plantations/delete.proc.php?id=<?= $plantation['plantation_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
                <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(<?= json_encode($plantation) ?>)'>Modifier</button>
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
        <p class="font-bold text-xl">Créer une plantation</p>
        <button type="button" onclick="closeForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col mt-2">
            <label for="parcelle_nom" class="font-bold">Entrer le nom</label>
            <input type="text" name="parcelle_nom" id="parcelle_nom" class="border pl-4 py-2" placeholder="Nom de la parcelle">
        </div>
        <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Créer la plantation</button>
    </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>



<?php
// Formulaire d'édition de parcelle
?>
<div id="editPlotForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <form onsubmit="editPlot(event);" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editForm" action="#" method="POST">
        <p class="font-bold text-xl">Modifier une plantation</p>
        <button type="button" onclick="closeEditForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col mt-2">
            <label for="edit_parcelle_nom" class="font-bold">Entrer le nom</label>
            <input type="text" name="edit_parcelle_nom" id="edit_parcelle_nom" class="border pl-4 py-2" placeholder="Nom de la parcelle">
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



    function displayForm() {
        const form = document.getElementById("plotForm");
        form.classList.remove("hidden");
    }

    function closeForm() {
        const form = document.getElementById("plotForm");
        form.classList.add("hidden");
    }

    async function createPlot(event) {
        event.preventDefault();
        let name = document.getElementById("parcelle_nom");
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/plantations/create/admin.php",
                data: {
                    name: name.value
                },
                dataType: "JSON",
                success: function(response) {
                    fetchPlantations();
                }
            });
        } catch (error) {

        }
    }

    function displayEditForm(parcelle) {
        const form = document.getElementById("editPlotForm");
        form.classList.remove("hidden");

        document.getElementById("edit_parcelle_nom").value = parcelle.plantation_nom || '';
        form.dataset.parcelleId = parcelle.plantation_id;
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

    function closeEditForm(event) {
        const form = document.getElementById("editPlotForm");
        form.classList.add("hidden");
    }

    async function editPlot(event) {
        event.preventDefault();
        let name = document.getElementById("edit_parcelle_nom");
        let jardinID = document.getElementById("editPlotForm").dataset.parcelleId;

        $.ajax({
            type: "POST",
            url: "../api/plantations/update/admin.php",
            data: {
                name: name.value,
                id: jardinID
            },
            dataType: "JSON",
            success: function(response) {
                fetchPlantations();
                closeEditForm();
            }
        });
    }



    // Récupérer les parcelles et les afficher dynamiquement

    async function fetchPlantations() {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/plantations/admin.php",
                dataType: "JSON",
                success: function(response) {
                    displayPlantations(response);
                    closeForm();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }


    async function acceptRequest(status, id) {
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
                success: function(response) {
                    fetchPlot();
                }
            });
        } catch (error) {

        }
    }

    function displayPlantations(data) {
        const wrapper = $("#listing");
        wrapper.empty();

        data.forEach(element => {
            const div =
                `
        <div class="border p-4 rounded-sm w-48">
            <div>  
                <h2>Nom:  ${element['plantation_nom']}</h2>
            </div>
            <a href="../process/plantations/delete.proc.php?id=${element['plantation_id']}" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(${JSON.stringify(element)})'>Modifier</button>
        </div>
        `
            wrapper.append(div);
        });
    }
</script>