<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../conf/function.inc.php');

extract($_GET);



$res = delete("parcelle", "parcelle_id", $_GET['id']);

header('Location: ../user/index.php');







