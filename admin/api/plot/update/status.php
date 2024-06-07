<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');

$request = $_POST['status'];

if($request == "accepted"){
    $sql = "UPDATE parcelle SET isAccepted = :isAccepted WHERE parcelle_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':id', $_POST['id']);
    $query->bindValue(':isAccepted', true);
}else {
    $sql = "UPDATE parcelle SET isAccepted = :isAccepted, user_id = :user_id WHERE parcelle_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':id', $_POST['id']);
    $query->bindParam(':isAccepted', false);
    $query->bindValue(':user_id', null);
}


$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
