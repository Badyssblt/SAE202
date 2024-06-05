<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../conf/function.inc.php');

$sql = "SELECT 
parcelle.parcelle_id,
parcelle.jardin_id,
parcelle.user_id,
parcelle.parcelle_type,
parcelle.isAccepted,
parcelle.parcelle_nom,
users.user_nom,
Jardin.jardin_nom,
Jardin.jardin_position,
Jardin.jardin_image
FROM parcelle 
INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
LEFT JOIN users ON parcelle.user_id = users.user_id";


$db = getConnection();
$query = $db->prepare($sql);
$query->execute();
$jardins = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($jardins);
