<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../conf/function.inc.php');

if(!isset($_SESSION)){
    session_start();
}

if(isset($_SESSION['id'])){
    $userID = $_SESSION['id'];
}



$sql = "SELECT 
Jardin.jardin_id, 
Jardin.jardin_nom, 
Jardin.jardin_position, 
Jardin.jardin_image, 
users.user_nom AS jardin_user,
users.user_id AS jardin_user_id
FROM 
Jardin 
INNER JOIN 
users ON Jardin.user_id = users.user_id 
WHERE Jardin.jardin_id = :id AND Jardin.user_id = :userId";

$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_GET['id']);
$query->bindParam(':userId', $userID);
$query->execute();
$jardin = $query->fetch();


header('Content-Type: application/json');

echo json_encode($jardin);
