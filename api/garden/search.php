<?php

require('../../conf/function.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

extract($_GET);

if($category == "name"){
    $sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id  WHERE Jardin.is_public = true GROUP BY Jardin.jardin_id, users.user_nom ORDER BY Jardin.jardin_nom ASC";
}else if($category == "created")
{
    $sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id  WHERE Jardin.is_public = true GROUP BY Jardin.jardin_id, users.user_nom ORDER BY Jardin.created_at DESC";
}else {
    $sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id  WHERE Jardin.is_public = true GROUP BY Jardin.jardin_id, users.user_nom";
}

$db = getConnection();
$query = $db->prepare($sql);

$query->execute();

$jardins = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jardins);

