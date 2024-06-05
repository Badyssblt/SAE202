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

$sql = "UPDATE parcelle SET user_id = :userID WHERE parcelle_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_POST['id']);
$query->bindParam(':userID', $_POST['userID']);
$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
