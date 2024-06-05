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
                parcelle.parcelle_type,
                parcelle.parcelle_nom,
                users.user_nom,
                Jardin.jardin_nom,
                Jardin.jardin_position,
                Jardin.jardin_image
            FROM parcelle 
            INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
            LEFT JOIN users ON parcelle.user_id = users.user_id
            WHERE parcelle.user_id = :userID AND parcelle.isAccepted = true";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':userID', $_SESSION['id']);
$query->execute();
$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="flex flex-wrap gap-2">
    <?php
    foreach ($parcelles as $parcelle) { 
?>
        <div class="border p-4 rounded-sm">
            <h2 class="font-bold"><?= $parcelle['jardin_nom'] ?></h2>
            <h3 class="font-semibold"><?= $parcelle['parcelle_nom'] ?></h3>
            <p>Type: <?= $parcelle['parcelle_type'] ?></p>
            <div class="flex flex-wrap gap-4">
                <a href="../../garden/single.php?id=<?= $parcelle['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                <button onclick="displayForm('<?= $parcelle['parcelle_nom'] ?>', <?=$parcelle['parcelle_id'] ?>)" class="bg-lime-800 py-2 px-4 text-white rounded-sm flex justify-center mt-2">Définir un type de plantation</button>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<?php
// Formulaire d'ajout de parcelle
?>
<div id="plantation_form" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="typePlotForm" action="#" method="POST">
            <p id="plantation_text"></p>
            <button onclick="closeForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex flex-col mt-2">
                <label for="plantation" class="font-bold">Entrer le type</label>
                <input type="text" name="plantation_type" id="parcelle_type" class="border pl-4 py-2" placeholder="Nom de la plantation">
            </div>
            <div>
                
            </div>
            <input type="hidden" name="parcelle_id" id="parcelle_id">
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Définir la plantation</button>
        </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>


<script>

        const typePlotForm = document.getElementById("typePlotForm");
        typePlotForm.addEventListener('submit', (event) => {
            const parcelleID = (document.getElementById("parcelle_id")).value;
            event.preventDefault();
            updateTypePlot(event, parcelleID)
        });


    function displayForm(name, id){
        const form = document.getElementById("plantation_form");
        const inputHidden = document.getElementById("parcelle_id");
        inputHidden.value = id;
        const plantationText = document.getElementById("plantation_text");
        const nameSpan = document.createElement("span");
        nameSpan.classList.add("font-bold");
        nameSpan.textContent = name;
        plantationText.textContent = "Définir une plantation pour ";
        plantationText.append(nameSpan);
        form.classList.remove("hidden");
    }

    function closeForm()
    {
        event.preventDefault();
        const form = document.getElementById("plantation_form");
        const type = document.getElementById("parcelle_type");
        type.value = "";
        form.classList.add("hidden");
    }

    async function updateTypePlot(event, id)
    {
        console.log('test');
        event.preventDefault();
        const type = document.getElementById("parcelle_type");
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/plot/update/type.php",
                data: {
                    id: id,
                    type: type.value
                },
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                },
                error: function(jqXHR){
                    console.log(jqXHR);
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

