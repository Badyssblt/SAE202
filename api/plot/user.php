<?php



if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

if(!isset($_SESSION)){
    session_start();
}

if(isset($_SESSION['id'])){
    $userID = $_SESSION['id'];
}else {
    http_response_code(403);
    echo json_encode(['message' => 'Please login!']);
    die();
}

require('../../conf/function.inc.php');

$sql = "SELECT 
parcelle.parcelle_id, 
parcelle.parcelle_type,
users.user_nom AS parcelle_user,
parcelle.isAccepted
FROM 
parcelle 
LEFT JOIN 
users ON parcelle.user_id = users.user_id
WHERE 
parcelle.jardin_id = :id AND parcelle.user_id = :userID AND parcelle.isAccepted = true";

$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':id', $_GET['id']);
$query->bindParam(':userID', $userID);
$query->execute();
$jardins = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($jardins);
