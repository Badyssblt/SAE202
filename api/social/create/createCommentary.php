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
    echo json_encode($res);
    http_response_code(401);
    die();
}

$postId = $_POST['postId'];


require_once('../../../vendors/profanity/src/mofodojodino/ProfanityFilter/Check.php');


$check = new Check();
$hasProfanity = $check->hasProfanity($_POST['commentary']);
$commentary = $check->obfuscateIfProfane($_POST['commentary']);

if ($hasProfanity) {
    echo json_encode(null);
    die();
}



$sql = "INSERT INTO commentary (commentary_content, user_id, post_id, created_at) VALUES (:commentary, :userID, :postId, :createdAt)";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':commentary', $commentary);
$query->bindParam(':userID', $userID);
$query->bindParam(':postId', $postId);
$createdAt = (new DateTime())->format('Y-m-d H:i:s');
$query->bindParam(':createdAt', $createdAt);
$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
