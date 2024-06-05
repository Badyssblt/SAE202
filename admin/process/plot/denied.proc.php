<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../conf/function.inc.php');

extract($_GET);


$sql = "UPDATE parcelle SET user_id = null WHERE parcelle_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_GET['id']);
$res = $query->execute();


header('Location: ../../plots/listPlot.php');



