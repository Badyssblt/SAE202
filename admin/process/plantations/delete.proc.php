<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../conf/function.inc.php');

extract($_GET);



$res = delete("plantations", "plantation_id", $_GET['id']);

header('Location: ../../plantations/listPlantations.php');







