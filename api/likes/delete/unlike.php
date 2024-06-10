<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
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

$postId = $_POST['postId'];

$sql = "DELETE FROM likes WHERE post_id = :postId AND user_id = :userId";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':postId', $postId);
$query->bindParam(':userId', $userID);
$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
