<?php

require('../../conf/function.inc.php');

session_start();

$userID = $_SESSION['id'];

$sql = "SELECT * FROM users WHERE user_id = :userID";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(":userID", $userID);;
$query->execute();
$user = $query->fetch();

if($user['user_id'] != $userID){
    header('Location: /');
    die();
}

?>


<div class="flex justify-center">
    <form action="../process/editInfo.proc.php" method="POST">
        <h2 class="font-bold text-xl my-6">Changer vos informations</h2>
        <div class="flex flex-col mb-4">
            <label for="name">Entrer votre nom</label>
            <input type="text" name="nom" id="name" placeholder="Nom" class="border pl-4 py-2" value="<?= $user['user_nom'] ?>">
        </div>
        <div class="flex flex-col mb-4">
            <label for="email">Entrer votre email</label>
            <input type="text" name="email" id="email" placeholder="Email" class="border pl-4 py-2" value="<?= $user['user_email'] ?>">
        </div>
        <div class="flex flex-col mb-4">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" class="border pl-4 py-2">
        </div>
        <button type="submit" class="bg-second px-4 py-2 rounded-full w-full transition duration-300 ease-in-out hover:bg-transparent hover:border hover:border-second hover:text-second">Modifier les informations</button>
    </form>
</div>