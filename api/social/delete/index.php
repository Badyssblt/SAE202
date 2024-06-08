<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

require('../../../conf/function.inc.php');

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else {
    $res = ['message' => 'Veuillez vous connecter'];
    http_response_code(401);
}

$sql = "SELECT user_id FROM commentary WHERE commentary_id = :commentary_id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':commentary_id', $_GET['id']);
$query->execute();
$user = $query->fetch();


// Vérifier que l'utilisateur qui fait la requête est bien auteur du commentaire
if ($user['user_id'] != $userID) {
    echo json_encode(['message' => "Vous n'êtes pas l'auteur de ce commentaire.", 'http_code' => 403]);
    http_response_code(403);
    die();
}


$commentaryId = $_GET['id'];

$sql = "DELETE FROM commentary WHERE commentary_id = :id";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $commentaryId);
$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
