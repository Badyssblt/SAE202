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
}



$sql = "SELECT 
commentary.commentary_id, 
commentary.commentary_content, 
commentary.created_at, 
users.user_nom AS commentary_author, 
users.user_picture AS commentary_author_picture
FROM 
commentary
INNER JOIN 
users ON commentary.user_id = users.user_id
WHERE 
commentary.post_id = :post_id
ORDER BY 
commentary.created_at";

$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':post_id', $_GET['id']);
$query->execute();
$commentary = $query->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');

echo json_encode($commentary);
