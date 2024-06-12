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
}else {
    http_response_code(403);
    echo json_encode(['message' => 'Please login!']);
    die();
}

$sql = "SELECT Jardin.jardin_id, Jardin.user_id, parcelle.user_id AS parcelle_request FROM Jardin INNER JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id WHERE Jardin.jardin_id = :jardinID";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':jardinID', $_POST['jardin']);
$query->execute();
$jardin = $query->fetch();



if($userID != $jardin['user_id']){
    echo json_encode(["message" => "Vous n'êtes pas le propriétaire du jardin"]);
    http_response_code(403);
    die();
} 

$status = $_POST['status'];



$sql = "UPDATE parcelle SET plantation_id = :type, isAccepted = :isAccepted, parcelle_nom = :name, user_id = :userID WHERE parcelle_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':type', $_POST['type']);
$query->bindParam(':name', $_POST['name']);
$query->bindParam(':id', $_POST['id']);


if ($status == "waiting") {
    $query->bindValue(':isAccepted', 0);
    $query->bindValue(':userID', $jardin['parcelle_request']);
} else if ($status == "accepted") {
    $query->bindValue(':isAccepted', 1);
    $query->bindValue(':userID', $jardin['parcelle_request']);
} else {
    $query->bindValue(':isAccepted', 0);
    $query->bindValue(':userID', null);
}

$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
