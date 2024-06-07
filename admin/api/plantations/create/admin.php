<?php

// Ce fichier permet aux administrateurs du site d'ajouter une parcelle avec toutes les colonnes

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');


$sql = "INSERT INTO plantations (plantation_nom) VALUES (:name)";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':name', $_POST['name']);

$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
