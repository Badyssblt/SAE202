<?php

require('../conf/function.inc.php');

session_start();

$userID = $_SESSION['id'];

if(!$userID){
    header('Location: /');
    die();
}

$sql = "SELECT * FROM users WHERE user_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $userID);
$query->execute();
$user = $query->fetch();

$sql = "UPDATE users SET user_nom = :nom, user_email = :email, user_password = :password WHERE user_id = :id";

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':nom', $_POST['nom']);
$query->bindParam(':email', $_POST['email']);
$query->bindParam(':id', $userID);


if($_POST['password'] == "" || isset($_POST['passowrd'])){
    $query->bindParam(':password', $user['user_password']);
}else {
    $query->bindParam(':password', $password);

}

$res = $query->execute();

if($res){
    session_destroy();
    header('Location: /');
    die();
}
