

<?php

require('../../conf/function.inc.php');

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id'])){
    header('Location: ./signin.php');
}
$userID = $_SESSION['id'];
$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id WHERE Jardin.user_id = $userID GROUP BY Jardin.jardin_id, users.user_nom ";
$jardins = sql($sql);
?>


<div>
    <ul class="flex flex-row gap-4">
        <button onclick="showMenu('listing')" class="bg-black text-white rounded-sm py-2 px-4">Mes jardins</button>
        <button onclick="showMenu('adding')" class="bg-black text-white rounded-sm py-2 px-4">Ajouter un jardin</button>
        <button onclick="showMenu('editing')" class="bg-black text-white rounded-sm py-2 px-4">Modifier mon jardin</button>
    </ul>
</div>

<?php
// Liste des jardins
?>
<div class="flex flex-wrap items-center justify-center mt-4" id="listing">
<?php
        foreach($jardins as $jardin){ 
            ?>
            <div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full">
                <h3 class="text-center font-bold"><?= $jardin['jardin_nom'] ?></h3>
                <div class="flex flex-col p-4 gap-2">
                <p>Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>
                <h4>Propriétaire: <span class="font-bold"><?= $jardin['user_nom'] ?></span></h4>
                <div class="flex flex-wrap gap-4">
                    <button class="border text-black py-2 px-4 rounded-sm">Modifier</button>
                    <button onclick="deleteGarden(<?= $jardin['jardin_id'] ?>)" class="bg-red-800 text-white py-2 px-4 rounded-sm">Supprimer</button>
                    <button onclick="displayForm('<?= $jardin['jardin_nom'] ?>', <?= $jardin['jardin_id'] ?>)" class="bg-lime-800 text-white py-2 px-4 rounded-sm">Ajouter une parcelle</button>
                    <a href="../../garden/single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm">Voir plus</a>
                </div>
                </div>
                
            </div>
        <?php
        }

    ?>
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
            <label for="positon">Entrer la position du jardin</label>
            <input type="text" name="position" id="position" class="border pl-4 py-2" placeholder="Position du jardin">
        </div>
        
        <input type="file" name="image" id="image">
        <button type="submit" class="bg-black text-white rounded-md py-2">Créer</button>
        <?php
            if(isset($_SESSION['error']['message'])){ ?>
            <p class="text-red-800"><?= $_SESSION['error']['message'] ?></p>
            <?php
            }elseif(isset($_SESSION['success']['message'])){ ?>
            <p class="text-red-800"><?= $_SESSION['success']['message'] ?></p>
            <?php
            }
        ?>
    </form>
</div>


<?php
// Formulaire d'ajout de parcelle
?>
<div id="plantation_form" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="addPlotForm">
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
        const addPlotForm = document.getElementById("addPlotForm");
        addPlotForm.addEventListener('submit', (event) => {
            const jardinID = (document.getElementById("jardin_id")).value;
            event.preventDefault();
            addPlot(jardinID);
        });



    function showMenu(edit){
        const div = document.getElementById(edit);
        if(edit === "listing"){
            div.classList.remove("hidden");
            (document.getElementById("adding")).classList.add("hidden");
            (document.getElementById("editing")).classList.add("hidden");
        }else if(edit === "adding"){
            div.classList.remove("hidden");
            (document.getElementById("listing")).classList.add("hidden");
            (document.getElementById("editing")).classList.add("hidden");
        }else if(edit === "editing"){
            div.classList.remove("hidden");
            (document.getElementById("adding")).classList.add("hidden");
            (document.getElementById("editing")).classList.add("hidden");
        }
    }

    function closeForm()
    {
        event.preventDefault();
        const form = document.getElementById("plantation_form");
        form.classList.add("hidden");
    }

    function displayForm(name, id){
        const form = document.getElementById("plantation_form");
        const inputHidden = document.getElementById("jardin_id");
        inputHidden.value = id;
        const plantationText = document.getElementById("plantation_text");
        const nameSpan = document.createElement("span");
        nameSpan.classList.add("font-bold");
        nameSpan.textContent = name;
        plantationText.textContent = "Ajouter une parcelle à ";
        plantationText.append(nameSpan);
        form.classList.remove("hidden");
    }

    async function deleteGarden(id){
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../../api/garden/delete",
                data: {
                    id
                },
                dataType: "JSON",
                success: function (response) {
                    fetchGardens();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    async function addPlot(id, type)
    {
        const typeDiv = document.getElementById("plantation");

        try {
            const res = await $.ajax({
                type: "POST",
                url: "../../api/plot/create/index.php",
                data: {
                    id: id,
                    name: typeDiv.value
                },
                dataType: "JSON",
                success: function (response) {
                    fetchGardens();
                    closeForm();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }



    async function fetchGardens(){
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../../api/garden",
                dataType: "JSON",
                success: function (response) {
                    displayGarden(response);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }


    function displayGarden(data){
        const wrapper = $("#listing");
        wrapper.empty();
        data.forEach(element => {
            const div = `<div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../assets/images/uploads/garden/${element['jardin_image']}" alt="" class="w-full">
                <h3 class='text-center font-bold'>${element['jardin_nom']}</h3>
                <div class='flex flex-col p-4 gap-2'>
                <p>Nombre de parcelle: <span class="font-bold">${element['parcelle_count']}</span></p>
                <h4>Propriétaire: <span class="font-bold">${element['user_nom']}</span></h4>
                <div class="flex flex-wrap gap-4">
                    <button class="border text-black py-2 px-4 rounded-sm">Modifier</button>
                    <button onclick="deleteGarden(${element['jardin_id']})" class="bg-red-800 text-white py-2 px-4 rounded-sm">Supprimer</button>
                    <button onclick="displayForm('${element['jardin_nom']}', ${element['jardin_id']})" class="bg-lime-800 text-white py-2 px-4 rounded-sm">Ajouter une parcelle</button>
                    <a href="../../garden/single.php?id=${element['jardin_id']}" class="bg-black text-white py-2 px-4 rounded-sm">Voir plus</a>
                </div>
                </div>
                
            </div>`;
            wrapper.append(div);
        });
    }
</script>