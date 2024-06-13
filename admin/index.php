<?php
require('../conf/header.inc.php');
require('../conf/function.inc.php');

$sql = "SELECT COUNT(*) AS jardin_numbers FROM Jardin";
$jardins = sql($sql);


$sql = "SELECT COUNT(*) AS parcelle_numbers FROM parcelle";
$parcelles = sql($sql);

$sql = "SELECT COUNT(*) AS users_numbers FROM users";
$users = sql($sql);

$sql = "SELECT COUNT(*) AS plantes_numbers FROM plantations";
$plantes = sql($sql);


?>
<div class="flex flex-col md:flex-row md:flex-wrap px-10 gap-6 justify-center">
    <div class="border md:w-1/3 rounded-md w-full">
        <div class="relative overflow-hidden rounded-t-md h-48">
            <img src="../assets/images/jardin_admin.jpg" class="w-full h-full object-cover" alt="">
        </div>
        <div class="px-6  py-4">
            <h2 class="font-bold text-3xl mb-2">Gestions des jardins</h2>
            <p class="mb-4 text-slate-600">Créer, modifier, et supprimer les jardins disponibles sur le site</p>
            <a href="./garden/listGarden.php" class="bg-main rounded-full w-full px-8 py-2">Voir</a>
        </div>
        
    </div>
    <div class="border  rounded-md w-full md:w-1/3">
        <div class="relative overflow-hidden rounded-t-md h-48">
            <img src="../assets/images/parcelle_admin.jpg" class="w-full h-full object-cover" alt="">
        </div>
        <div class="px-6  py-4">
            <h2 class="font-bold text-3xl mb-2">Gestions des parcelles</h2>
            <p class="mb-4 text-slate-600">Créer, modifier, supprimer et définissez les disponibilités des parcelles</p>
            <a href="./plots/listPlot.php" class="bg-main rounded-full w-full px-8 py-2">Voir</a>
        </div>
        
    </div>
    <div class="border md:w-1/3 rounded-md w-full">
        <div class="relative overflow-hidden rounded-t-md h-48 bg-blue-400">
            <img src="../assets/images/parcelle_admin.jpg" class="w-full h-full object-cover" alt="">
        </div>
        <div class="px-6  py-4">
            <h2 class="font-bold text-3xl mb-2">Gestions des utilisateurs</h2>
            <p class="mb-4 text-slate-600">Créer, modifier, et supprimer les utilisateurs sur le site</p>
            <a href="./users/listUsers.php" class="bg-main rounded-full w-full px-8 py-2">Voir</a>
        </div>
        
    </div>
    <div class="border md:w-1/3 rounded-md w-full">
        <div class="relative overflow-hidden rounded-t-md h-48 bg-blue-400">
            <img src="../assets/images/plantes_admin.jpg" class="w-full h-full object-cover" alt="">
        </div>
        <div class="px-6  py-4">
            <h2 class="font-bold text-3xl mb-2">Gestions des plantes</h2>
            <p class="mb-4 text-slate-600">Créer, modifier, et supprimer les plantes disponibles sur le site</p>
            <a href="./plantations/listPlantations.php" class="bg-main rounded-full w-full px-8 py-2">Voir</a>
        </div>
        
    </div>
    
</div>

<div class="my-12">
    <h2 class="font-bold text-3xl text-center my-12">Statistiques du site web</h2>
    <div class="flex flex-row gap-12 justify-center">
        <div class="flex flex-col">
            <h2 class="font-bold text-xl">Jardins</h2>
            <p class="font-bold text-2xl text-center"><?= $jardins[0]['jardin_numbers'] ?></p>
        </div>
        <div class="flex flex-col">
            <h2 class="font-bold text-xl">Parcelles</h2>
            <p class="font-bold text-2xl text-center"><?= $parcelles[0]['parcelle_numbers'] ?></p>
        </div>
        <div class="flex flex-col">
            <h2 class="font-bold text-xl">Utilisateurs</h2>
            <p class="font-bold text-2xl text-center"><?= $users[0]['users_numbers'] ?></p>
        </div>
        <div class="flex flex-col">
            <h2 class="font-bold text-xl">Plantes</h2>
            <p class="font-bold text-2xl text-center"><?= $plantes[0]['plantes_numbers'] ?></p>
        </div>
    </div>
</div>

<?php
require('../conf/footer.inc.php');
?>