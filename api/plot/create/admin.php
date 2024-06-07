<?php

// Ce fichier permet aux administrateurs du site d'ajouter une parcelle avec toutes les colonnes

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../conf/function.inc.php');

if(!isset($_SESSION)){
    session_start();
}

if(isset($_SESSION['id'])){
    $userID = $_SESSION['id'];
}

$sql = "INSERT INTO parcelle (jardin_id, user_id, plantation_id, parcelle_nom, isAccepted) VALUES (:jardin_id, :userID, :type, :name, true)";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':jardin_id', $_POST['jardin']);
$query->bindParam(':userID', $_POST['user']);
$query->bindParam(':type', $_POST['type']);
$query->bindParam(':name', $_POST['name']);

$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
