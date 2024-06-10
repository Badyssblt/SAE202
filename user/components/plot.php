<?php

require('../../conf/function.inc.php');

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id'])){
    header('Location: ./signin.php');
}
$userID = $_SESSION['id'];
$sql = "SELECT 
                parcelle.parcelle_id,
                parcelle.jardin_id,
                parcelle.user_id,
                parcelle.parcelle_nom,
                users.user_nom,
                Jardin.jardin_nom,
                Jardin.jardin_position,
                Jardin.jardin_image,
                plantations.plantation_nom
            FROM parcelle 
            INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
            LEFT JOIN users ON parcelle.user_id = users.user_id
            LEFT JOIN plantations ON parcelle.plantation_id = plantations.plantation_id
            WHERE parcelle.user_id = :userID AND parcelle.isAccepted = true";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindValue(':userID', $userID);
$query->execute();
$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);
$plantations = findAll("plantations");
?>


<div class="flex flex-wrap gap-2" id="listing_plot">
    <?php
    if(count($parcelles) <= 0){ ?>
        <p>Vous n'avez aucune parcelle</p>
<?php
    }

    foreach ($parcelles as $parcelle) { 
?>
        <div class="border p-4 rounded-sm">
            <h2 class="font-bold"><?= $parcelle['jardin_nom'] ?></h2>
            <h3 class="font-semibold"><?= $parcelle['parcelle_nom'] ?></h3>
            <p>Type: <?= $parcelle['plantation_nom'] ?></p>
            <div class="flex flex-wrap gap-4">
                <a href="../../garden/single.php?id=<?= $parcelle['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                <button onclick="displayFormPlot('<?= $parcelle['parcelle_nom'] ?>', <?=$parcelle['parcelle_id'] ?>)" class="bg-lime-800 py-2 px-4 text-white rounded-sm flex justify-center mt-2">Définir un type de plantation</button>
                <button onclick="deletePlot(<?=$parcelle['parcelle_id'] ?>)" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</button>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<?php
// Formulaire d'édition de parcelle
?>
<div id="plantation_form" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form onsubmit="updateTypePlot(event)" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="typePlotForm" action="#" method="POST">
            <p id="plantation_text"></p>
            <button onclick="closeFormPlot(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div>
            <div class="flex flex-col mt-2">
                <label for="parcelle_type" class="font-bold">Sélectionner le jardin</label>
                <select name="parcelle_type" id="parcelle_type" class="border pl-4 py-2">
                    <?php
                    foreach ($plantations as $plantation) { ?>
                        <option value="<?= $plantation['plantation_id'] ?>"><?= $plantation['plantation_nom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            </div>
            <input type="hidden" name="parcelle_id" id="parcelle_id">
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Définir la plantation</button>
        </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>


<script>



    function displayFormPlot(name, id){
        const form = document.getElementById("plantation_form");
        const inputHidden = document.getElementById("parcelle_id");
        console.log(inputHidden);
        inputHidden.value = id;
        const plantationText = document.getElementById("plantation_text");
        const nameSpan = document.createElement("span");
        nameSpan.classList.add("font-bold");
        nameSpan.textContent = name;
        plantationText.textContent = "Définir une plantation pour ";
        plantationText.append(nameSpan);
        form.classList.remove("hidden");
    }

    function closeFormPlot()
    {
        event.preventDefault();
        const form = document.getElementById("plantation_form");
        const type = document.getElementById("parcelle_type");
        type.value = "";
        form.classList.add("hidden");
    }

    async function updateTypePlot(event)
    {
        
        event.preventDefault();
        const parcelleID = (document.getElementById("parcelle_id")).value;
        const type = document.getElementById("parcelle_type");
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/plot/update/type.php",
                data: {
                    id: parcelleID,
                    type: type.value
                },
                dataType: "JSON",
                success: function (response) {
                    fetchPlot();
                    closeFormPlot();
                },
                error: function(jqXHR){
                    console.log(jqXHR);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    async function deletePlot(id)
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/plot/delete/index.php?id=" + id,
                dataType: "JSON",
                success: function (response) {
                    fetchPlot();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    function displayPlot(data)
    {
        const wrapper = $("#listing_plot");
        wrapper.empty();
        console.log(data);
        if(data.length <= 0){
            const div = `<p>Vous n'avez aucune parcelles</p>`;
            wrapper.append(div);
            return;
        }
        
        data.forEach(element => {
            const div = 
            `
            <div class="border p-4 rounded-sm">
            <h2 class="font-bold">${element['jardin_nom']}</h2>
            <h3 class="font-semibold">${element['parcelle_nom']}</h3>
            <p>Type: ${element['plantation_nom']}</p>
            <div class="flex flex-wrap gap-4">
                <a href="../../garden/single.php?id=${element['jardin_id']}" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                <button onclick="displayFormPlot('${element['parcelle_nom']}', ${element['parcelle_id']})" class="bg-lime-800 py-2 px-4 text-white rounded-sm flex justify-center mt-2">Définir un type de plantation</button>
                <button onclick="deletePlot(${element['parcelle_id']})" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</button>
            </div>
        </div>
            `;
            wrapper.append(div);
        }); 

        
    }


    async function fetchPlot()
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/plot/getUserPlot.php",
                dataType: "JSON",
                success: function (response) {
                    displayPlot(response);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }
</script>

