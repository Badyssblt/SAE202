<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

require('../../conf/function.inc.php');


if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else {
    $res = ['message' => 'Veuillez vous connecter'];
    http_response_code(401);
}

$sql = "SELECT 
COUNT(DISTINCT likes.like_id) AS like_count
FROM 
post 
LEFT JOIN 
likes ON likes.post_id = post.post_id
WHERE 
post.post_id = :id";

$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_GET['id']);
$query->execute();
$likes = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($likes);
