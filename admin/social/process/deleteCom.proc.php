<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../conf/function.inc.php');

extract($_GET);



$res = delete("commentary", "commentary_id", $_GET['id']);

if($res){
    $_SESSION['success']['message'] = "Les changements ont bien été pris en compte";
}else {
    $_SESSION['error']['message'] = "Une erreur est survenue lors des changements";
}

header('Location: ../listSocial.php');







