<?php

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

$sql = "INSERT INTO parcelle (jardin_id, parcelle_nom, isAccepted, parcelle_superficie) VALUES (:id, :name, false, :superficie)";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_POST['id']);
$query->bindParam(':name', $_POST['name']);
$query->bindParam(':superficie', $_POST['superficie']);
$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
