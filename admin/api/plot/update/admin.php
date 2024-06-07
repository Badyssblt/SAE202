<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');

$status = $_POST['status'];


$sql = "UPDATE parcelle SET jardin_id = :jardin_id, user_id = :userID, plantation_id = :type, isAccepted = :isAccepted, parcelle_nom = :nom WHERE parcelle_id = :id";
$db = getConnection();
$query = $db->prepare($sql);

$query->bindParam(':jardin_id', $_POST['jardin']);
$query->bindParam(':userID', $_POST['user']);
$query->bindParam(':type', $_POST['type']);
$query->bindParam(':nom', $_POST['name']);
$query->bindParam(':id', $_POST['id']); 

if ($status == "waiting") {
    $query->bindValue(':isAccepted', 0);
} else if ($status == "accepted") {
    $query->bindValue(':isAccepted', 1);
} else {
    $query->bindValue(':isAccepted', 0);
    $query->bindValue(':userID', null);
}


$res = $query->execute();

header('Content-Type: application/json');
echo json_encode($res);
