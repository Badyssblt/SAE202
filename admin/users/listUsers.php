

<?php

require("../../conf/header.inc.php");
require('../../conf/function.inc.php');

$jardins = findAll("Jardin");

$users = findAll("users");

?>
<div class="px-8">


<div class="flex flex-row gap-8">
    <a href="../garden/listGarden.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des jardins</a>
    <a href="./listPlot.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des parcelles</a>
    <a href="../users/listUsers.php" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Liste des utilisateurs</a>
    <a href="../plantations/listPlantations.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des plantations</a>
</div>

<h2 class="font-bold text-xl mt-12 mb-8">Liste des parcelles</h2>
<button class="bg-black text-white py-2 px-4 rounded-sm flex justify-center my-2" onclick="displayForm()">Créer un utilisateur</button>
<div class="flex flex-wrap gap-8" id="listing">
    
    <?php

    if(count($users) <= 0){ ?>
        <p class="mt-8">Il n'y a aucun utilisateur dans la base de donnée...</p>
<?php
    }

    foreach ($users as $user) { 
        ?>
        <div class="border p-4 rounded-sm w-48">
            <div>  
                <h2>Nom:  <?= $user['user_nom'] ?></h2>
                <img src="../../assets/images/uploads/users/<?= $user['user_picture'] ?>" alt="">
            </div>
            <p>Email: <span class="font-bold"><?= $user['user_email'] ?></span></p>
            <a href="../process/users/delete.proc.php?id=<?= $user['user_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(<?= json_encode($user) ?>)'>Modifier</button>
        </div>
        <?php
    }
    ?>

</div>
    
</div>

<?php
// Formulaire d'ajout d'un utilisateur
?>
<div id="addUser" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="addUserForm" action="#" method="POST" enctype="multipart/form-data">
            <p class="font-bold text-xl">Créer un utilisateur</p>
            <button type="button" onclick="closeForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex flex-col mt-2">
                <label for="user_nom" class="font-bold">Entrer le nom</label>
                <input type="text" name="user_nom" id="user_nom" class="border pl-4 py-2" placeholder="Nom de l'utilisateur">
            </div>
            <div class="flex flex-col mt-2">
                <label for="user_email" class="font-bold">Entrer l'email</label>
                <input type="text" name="user_email" id="user_email" class="border pl-4 py-2" placeholder="Email de l'utilisateur">
            </div>
            
            <div class="flex flex-col mt-2">
                <label for="user_password" class="font-bold">Entrer le mot de passe</label>
                <input type="text" name="user_password" id="user_password" class="border pl-4 py-2" placeholder="Mot de passe de l'utilisateur">
            </div>
            

            <div class="flex flex-col mt-2">
                <label for="user_image" class="font-bold">Entrer l'image de profil</label>
                <input type="file" name="user_image" id="user_image" class="border pl-4 py-2" placeholder="Image">
            </div>
            <div>
                
            </div>
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Créer un utilisateur</button>
        </form>
</div>
<?php
// Fin Formulaire d'ajout de parcelle
?>



<?php
// Formulaire d'édition de parcelle
?>
<div id="editUser" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
        <form class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editUserForm" action="#" method="POST" enctype="multipart/form-data">
            <p class="font-bold text-xl">Modifier un utilisateur</p>
            <button type="button" onclick="closeEditForm(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex flex-col mt-2">
                <label for="edit_user_nom" class="font-bold">Entrer le nom</label>
                <input type="text" name="user_nom" id="edit_user_nom" class="border pl-4 py-2" placeholder="Nom de l'utilisateur">
            </div>
            <div class="flex flex-col mt-2">
                <label for="edit_user_email" class="font-bold">Entrer l'email</label>
                <input type="text" name="user_email" id="edit_user_email" class="border pl-4 py-2" placeholder="Email de l'utilisateur">
            </div>
            
            <div class="flex flex-col mt-2">
                <label for="edit_user_password" class="font-bold">Entrer le mot de passe</label>
                <input type="text" name="user_password" id="edit_user_password" class="border pl-4 py-2" placeholder="Mot de passe de l'utilisateur">
            </div>
            

            <div class="flex flex-col mt-2">
                <label for="edit_user_image" class="font-bold">Entrer l'image de profil</label>
                <input type="file" name="user_image" id="edit_user_image" class="border pl-4 py-2" placeholder="Image">
            </div>
            <div>
                
            </div>
            <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Créer un utilisateur</button>
        </form>
</div>
<?php
// Fin Formulaire d'édition de parcelle
?>


<script>


        document.getElementById('addUserForm').addEventListener('submit', (event) => {
            createUser(event);
        })

        document.getElementById("editUserForm").addEventListener("submit", (event) => {
            editUser(event);
        });


    function displayForm(){
        const form = document.getElementById("addUser");
        form.classList.remove("hidden");
    }

    function closeForm()
    {
        const form = document.getElementById("addUser");
        form.classList.add("hidden");
    }

    async function createUser(event){
        event.preventDefault();
        let name = document.getElementById("user_nom");
        let email = (document.getElementById("user_email"));
        let password = (document.getElementById("user_password"));
        let image = document.getElementById("user_image").files[0];

        let formData = new FormData();
        formData.append("user_nom", name.value);
        formData.append("user_email", email.value);
        formData.append("user_password", password.value);
        formData.append("user_picture", image);


        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/users/create/admin.php",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function (response) {
                    fetchUser();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    function displayEditForm(user) {
        const form = document.getElementById("editUser");
        form.classList.remove("hidden");

        document.getElementById("edit_user_nom").value = user.user_nom || '';
        document.getElementById("edit_user_email").value = user.user_email || '';
        document.getElementById("edit_user_password").value = '';
       
        form.dataset.userId = user.user_id;
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
        const form = document.getElementById("editUser");
        form.classList.add("hidden");
    }

    async function editUser(event)
    {
        event.preventDefault();
        let name = document.getElementById("edit_user_nom");
        let email = (document.getElementById("edit_user_email"));
        let password = (document.getElementById("edit_user_password"));
        let image = document.getElementById("edit_user_image").files[0];
        let userID = document.getElementById("editUser").dataset.userId;

        let formData = new FormData();
        formData.append("user_nom", name.value);
        formData.append("user_email", email.value);
        formData.append("user_password", password.value);
        formData.append("user_picture", image);
        formData.append("id", userID);


        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/users/update/admin.php",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function (response) {
                    fetchUsers();
                    closeEditForm();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }



    // Récupérer les parcelles et les afficher dynamiquement
    async function fetchUsers()
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/users/admin.php",
                dataType: "JSON",
                success: function (response) {
                    displayUsers(response);
                    closeForm();
                }
            });
        } catch (error) {
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

    function displayUsers(data) {
    const wrapper = $("#listing");
    wrapper.empty();

    data.forEach(element => {
        const div = `
        <div class="border p-4 rounded-sm w-48">
            <div>  
                <h2>${element['user_nom']}</h2>
                <img src="../../assets/images/uploads/users/${element['user_picture']}" alt="">
            </div>
            <p>Email: <span class="font-bold">${element['user_email']}</span></p>
            <a href="../process/users/delete.proc.php?id=${element['user_id']}" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(${JSON.stringify(element)})'>Modifier</button>
        </div>
        `;

        wrapper.append(div);
    });
}

fetchUsers();


</script>
