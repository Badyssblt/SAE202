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


$sql = "UPDATE parcelle SET jardin_id = :jardin_id, user_id = :userID, parcelle_type = :type, isAccepted = :isAccepted, parcelle_nom = :nom WHERE parcelle_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':jardin_id', $_POST['jardinID']);
$query->bindParam(':userID', $_POST['userID']);
$query->bindParam(':type', $_POST['type']);
$query->bindParam(':isAccepted', $_POST['isAccepted']);
$query->bindParam(':nom', $_POST['nom']);

$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
