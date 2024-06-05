

<?php

require("../../conf/header.inc.php");
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
                parcelle.parcelle_type,
                parcelle.isAccepted,
                users.user_nom,
                Jardin.jardin_nom,
                Jardin.jardin_position,
                Jardin.jardin_image
            FROM parcelle 
            INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
            LEFT JOIN users ON parcelle.user_id = users.user_id";

$db = getConnection();
$query = $db->prepare($sql);
$query->execute();
$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);

$jardins = findAll("Jardin");

$users = findAll("users");

?>
<div class="px-8">


<div class="flex flex-row gap-8">
    <a href="../garden/listGarden.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des jardins</a>
    <a href="./listPlot.php" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Liste des parcelles</a>
    <a href="../users/listUsers.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des utilisateurs</a>
</div>

<h2 class="font-bold text-xl mt-12 mb-8">Liste des parcelles</h2>
<button class="bg-black text-white py-2 px-4 rounded-sm flex justify-center my-2" onclick="displayForm()">Créer une parcelle</button>
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
            <p>Type: <?= $parcelle['parcelle_type'] ?></p>
            <div class="mt-4">
                <h3 class="font-bold">Status</h3>
                <?php
                    if($isWaiting && $isAvailable){ ?>
                    <p>Demande en cours</p>
                    <div class="flex flex-row mt-2 gap-2">
                        <a  class="bg-lime-800 text-white py-2 px-4 rounded-sm">Accepter</a>
                        <a href="../process/plot/denied.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm">Refuser</a>
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
            <a href="../process/plot/delete.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>

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
            <button onclick="closeForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex flex-col mt-2">
                <label for="parcelle_nom" class="font-bold">Entrer le nom</label>
                <input type="text" name="parcelle_nom" id="parcelle_nom" class="border pl-4 py-2" placeholder="Nom de la parcelle">
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
                <label for="parcelle_type" class="font-bold">Entrer le type</label>
                <input type="text" name="plantation_type" id="parcelle_type" class="border pl-4 py-2" placeholder="Nom de la plantation">
            </div>
            <div>
                
            </div>
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Définir la plantation</button>
        </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>


<script>

        const plotForm = document.getElementById("plotForm");
        plotForm.addEventListener('submit', (event) => {
            event.preventDefault();
            createPlot(event)
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
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../../api/plot/create/admin.php",
                data: {
                    name: name.value,
                    jardin: jardin.value,
                    user: user.value,
                    type: type.value
                },
                dataType: "JSON",
                success: function (response) {
                    fetchPlot();
                }
            });
        } catch (error) {
            
        }
    }

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

    function displayPlot(data)
    {
        const wrapper = $("#listing");
        wrapper.empty();
        data.forEach(element => {
        let isAvailable = element.isAccepted == 1 ? false : true;
        let isWaiting = (isAvailable && element.parcelle_user != null) ? true : false;

        let plotDetails;

        let type;

        let closeButton;

        if(element['parcelle_type'] !== null){
            type = `<p>Type : <span class="font-bold">${element['parcelle_type']}</span></p>`;
        }else {
            type = ``;
        }

        if (isAvailable && !isWaiting) {
            plotDetails = `
                <p>Disponible</p>
            `;
        } else if(isAvailable && isWaiting){
            plotDetails = `
                    <p>Demande en cours</p>
                    <div class="flex flex-row mt-2 gap-2">
                        <a class="bg-lime-800 text-white py-2 px-4 rounded-sm">Accepter</a>
                        <a href="../process/plot/denied.proc.php?id=${element['parcelle_id']}" class="bg-red-800 text-white py-2 px-4 rounded-sm">Refuser</a>
                    </div>
            `;
        }else if(!isAvailable && !isWaiting){
            plotDetails = `
                <p>Détenu par <span class="font-bold"><?= $parcelle['user_nom']?></span></p>
            `;
        }

        const div = `
            <div class="border p-4 rounded-sm w-48">
                <h2>${element['jardin_nom']}</h2>
                <p>Type: <?= $parcelle['parcelle_type'] ?></p>
                <div class="mt-4">
                    <h3 class="font-bold">Status</h3>
                ${plotDetails}
                </div>
                <a href="../../garden/single.php?id=${element['jardin_id']}" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                <a href="../process/plot/delete.proc.php?id=${element['parcelle_id']}" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            </div>
        `;

        wrapper.append(div);
        })
    }


</script>
