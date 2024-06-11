<?php
$activePage = 'admin';

require('../../conf/header.inc.php');
require('../../conf/function.inc.php');


$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id GROUP BY Jardin.jardin_id, users.user_nom";
$jardins = sql($sql);

$users = findAll("users");
?>

<div class="px-8">
    
<div class="flex flex-row gap-8">
    <a href="./listGarden.php" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Liste des jardins</a>
    <a href="../plots/listPlot.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des parcelles</a>
    <a href="../users/listUsers.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des utilisateurs</a>
    <a href="../plantations/listPlantations.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des plantations</a>
</div>

<h2 class="font-bold text-xl mt-12 mb-8">Liste des jardins</h2>
<button class="bg-black text-white py-2 px-4 rounded-sm flex justify-center my-2" onclick="displayAddGardenForm()">Créer un jardin</button>
<div class="flex flex-wrap gap-2" id="listing">
    <?php
        foreach($jardins as $jardin){ ?>
            <div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full">
                <h3><?= $jardin['jardin_nom'] ?></h3>
                <p>Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>
                <h4>Propriétaire: <span class="font-bold"><?= $jardin['user_nom'] ?></span></h4>
                <div class="flex flex-row gap-4">
                    <button class="border text-black py-2 px-4 rounded-sm flex justify-center mt-4" onclick='displayEditGardenForm(<?= json_encode($jardin) ?>)'>Modifier</button>
                    <button onclick="deleteGarden(<?= $jardin['jardin_id'] ?>)" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</button>
                </div>
                <a href="/garden/single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
            </div>
        <?php
        }

    ?>
    
</div>
</div>



<?php
// Formulaire d'ajout d'un jardin
?>
<div id="addGardenForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <form action="#" method="POST" enctype="multipart/form-data" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative">
    <button type="button" onclick="closeAddGardenForm()" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        <div class="flex flex-col">
            <label for="jardin_nom">Entrer le nom du jardin</label>
            <input type="text" name="name" id="jardin_nom" placeholder="Nom du jardin" class="border pl-4 py-2">
        </div>
        <div class="flex flex-col">
            <label for="jardin_position">Entrer la position du jardin</label>
            <input type="text" name="position" id="jardin_position" class="border pl-4 py-2" placeholder="Position du jardin">
            <div id="suggestions" class="suggestions"></div>
        </div>
        <div class="flex flex-col">
            <label for="user">Entrer la position du jardin</label>
            <select name="user" id="user" class="border pl-4 py-2">
                <?php
                foreach ($users as $user) { ?>
                    <option value="<?= $user['user_id'] ?>"><?= $user['user_nom'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="flex flex-col">
            <label for="jardin_nom">Visibilité</label>
            <div class="flex flex-row gap-6">
                <div>
                    <label for="public">Public</label>
                    <input type="radio" name="visibility" id="public" value="1" required>
                </div>
                <div>
                    <label for="private">Privée</label>
                    <input type="radio" name="visibility" id="private" value="0" required>
                </div>
            </div>
            
        </div>
        
        <input type="file" name="image" id="jardin_image">
        <button type="submit" class="bg-black text-white rounded-md py-2" onclick="createGarden(event)">Créer</button>
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
// Fin du formulaire d'ajout d'un jardin
?>


<?php
// Formulaire d'édition d'un jardin
?>
<div id="editGardenForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
<form action="#" method="POST" enctype="multipart/form-data" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative">
    <button type="button" onclick="closeEditGardenForm()" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        <div class="flex flex-col">
            <label for="edit_jardin_nom">Entrer le nom du jardin</label>
            <input type="text" name="name" id="edit_jardin_nom" placeholder="Nom du jardin" class="border pl-4 py-2">
        </div>
        <div class="flex flex-col">
            <label for="edit_jardin_position">Entrer la position du jardin</label>
            <input type="text" name="position" id="edit_jardin_position" class="border pl-4 py-2" placeholder="Position du jardin">
            <div id="suggestions" class="suggestions"></div>
        </div>
        <div class="flex flex-col">
            <label for="edit_users">Entrer la position du jardin</label>
            <select name="user" id="edit_users" class="border pl-4 py-2">
                <?php
                foreach ($users as $user) { ?>
                    <option value="<?= $user['user_id'] ?>"><?= $user['user_nom'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="flex flex-col">
            <label for="edit_jardin_nom">Visibilité</label>
            <div class="flex flex-row gap-6">
                <div>
                    <label for="public">Public</label>
                    <input type="radio" name="visibilityEdit" id="edit_public" value="1" required>
                </div>
                <div>
                    <label for="private">Privée</label>
                    <input type="radio" name="visibilityEdit" id="edit_private" value="0" required>
                </div>
            </div>
            
        </div>
        
        <input type="file" name="image" id="edit_jardin_image">
        <button type="submit" class="bg-black text-white rounded-md py-2">Modifier le jardin</button>
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
// Fin Formulaire d'edition d'un jardin
?>


<script>


    // Début du CRUD des jardins
    async function createGarden(event){
        event.preventDefault();
        let name = document.getElementById("jardin_nom");
        let position = document.getElementById("jardin_position");
        let image = document.getElementById("jardin_image").files[0];
        let user_id = document.getElementById("user");
        let is_public = document.querySelector('input[name="visibility"]:checked').value;

        
        let formData = new FormData();
        formData.append("jardin_nom", name.value);
        formData.append("jardin_position", position.value);
        formData.append("jardin_image", image);
        formData.append("user_id", user_id.value);
        formData.append("is_public", is_public);


        try {
            const res = await $.ajax({
            type: "POST",
            url: "../api/garden/create/admin.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function (response) {
                fetchGardens();
                closeAddGardenForm();
            }
        });
        } catch (error) {
            
        }
    }

    async function fetchGardens(){
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/garden/index.php",
                dataType: "JSON",
                success: function (response) {
                    displayGarden(response);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    async function updateGarden(event) {
    event.preventDefault();

    let jardinId = document.getElementById("editGardenForm").dataset.jardinId;
    let name = document.getElementById("edit_jardin_nom").value;
    let position = document.getElementById("edit_jardin_position").value;
    let image = document.getElementById("edit_jardin_image").files[0];
    let user_id = document.getElementById("edit_users").value;
    let is_public = document.querySelector('input[name="visibilityEdit"]:checked').value;

    let formData = new FormData();
    formData.append("jardin_id", jardinId);
    formData.append("jardin_nom", name);
    formData.append("jardin_position", position);
    if (image) {
        formData.append("jardin_image", image);
    }
    formData.append("user_id", user_id);
    formData.append("is_public", is_public);

    try {
        const res = await $.ajax({
            type: "POST",
            url: "../api/garden/update/admin.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function (response) {
                fetchGardens();
                closeEditGardenForm();
            }
        });
    } catch (error) {
        console.error(error);
    }
}

    async function deleteGarden(id){
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/garden/delete/index.php",
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

    // FIN du CRUD des jardins



    function displayGarden(data){
        const wrapper = $("#listing");
        wrapper.empty();
        data.forEach(element => {
            const jardinData = JSON.stringify(element);
            const div = `<div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../../assets/images/uploads/garden/${element['jardin_image']}" alt="" class="w-full">
                <h3>${element['jardin_nom']}</h3>
                <p>Nombre de parcelle: <span class="font-bold">${element['parcelle_count']}</span></p>
                <h4>Propriétaire: <span class="font-bold">${element['user_nom']}</span></h4>
                <div class="flex flex-row gap-4">
                <button class="border text-black py-2 px-4 rounded-sm flex justify-center mt-4" onclick='displayEditGardenForm(${jardinData})'>Modifier</button>
                    <button onclick="deleteGarden(${element['jardin_id']})" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</button>
                </div>
                <a href="/garden/single.php?id=${element['jardin_id']}" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
            </div>`;
            wrapper.append(div);
        });
    }


    function displayAddGardenForm()
    {
        const form = document.getElementById("addGardenForm");
        form.classList.remove("hidden");
    }

    function closeAddGardenForm()
    {
        const form = document.getElementById("addGardenForm");
        form.classList.add("hidden");
    }


    function displayEditGardenForm(jardin) {
    const form = document.getElementById("editGardenForm");
    form.classList.remove("hidden");

    document.getElementById("edit_jardin_nom").value = jardin.jardin_nom || '';
    document.getElementById("edit_jardin_position").value = jardin.jardin_position || '';
    setSelectOption('edit_users', jardin.user_id);

    if (jardin.is_public == 1) {
        document.getElementById('edit_public').checked = true;
    } else {
        document.getElementById('edit_private').checked = true;
    }

    form.dataset.jardinId = jardin.jardin_id;
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

    function closeEditGardenForm()
    {
        const form = document.getElementById("editGardenForm");
        form.classList.add("hidden");
    }




document.getElementById("editGardenForm").addEventListener("submit", (event) => {
    updateGarden(event);
});



</script>


