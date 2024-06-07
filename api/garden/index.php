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

$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count
        FROM Jardin 
        INNER JOIN users ON Jardin.user_id = users.user_id 
        LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id 
        WHERE Jardin.user_id = $userID GROUP BY Jardin.jardin_id, users.user_nom";

$jardins = sql($sql);

header('Content-Type: application/json');

echo json_encode($jardins);
