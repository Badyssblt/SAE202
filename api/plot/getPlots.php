<?php

// Ce fichier est utilisé uniquement dans reservation.php pour le fetchPlot

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}
session_start();


require('../../conf/function.inc.php');
$userID = $_SESSION['id'];
$sql = "SELECT 
parcelle.parcelle_id,
parcelle.jardin_id,
parcelle.user_id,
parcelle.isAccepted,
parcelle.parcelle_nom,
users.user_nom,
Jardin.jardin_nom,
Jardin.jardin_position,
Jardin.jardin_image,
plantations.plantation_nom,
plantations.plantation_id
FROM parcelle 
INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
LEFT JOIN users ON parcelle.user_id = users.user_id
LEFT JOIN plantations ON parcelle.plantation_id = plantations.plantation_id
WHERE Jardin.user_id = :userID";

$db = getConnection();
$query = $db->prepare($sql);
$query->bindValue(':userID', $userID);
$query->execute();
$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($parcelles);
