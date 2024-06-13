<?php

// Récupère les parcelles d'un jardin

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../conf/function.inc.php');

$sql = "SELECT 
                    parcelle.parcelle_id, 
                    parcelle.parcelle_nom,
                    users.user_nom AS parcelle_user,
                    parcelle.isAccepted,
                    parcelle.parcelle_superficie,
                    plantations.plantation_nom
                FROM 
                    parcelle 
                LEFT JOIN 
                    users ON parcelle.user_id = users.user_id
                LEFT JOIN plantations ON parcelle.plantation_id = plantations.plantation_id
                WHERE 
                    parcelle.jardin_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_GET['id']);
$query->execute();
$jardins = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($jardins);
