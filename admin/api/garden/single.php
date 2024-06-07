<?php

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../conf/function.inc.php');



$sql = "SELECT 
Jardin.jardin_id, 
Jardin.jardin_nom, 
Jardin.jardin_position, 
Jardin.jardin_image, 
users.user_nom AS jardin_user,
users.user_id AS jardin_user_id
FROM 
Jardin 
INNER JOIN 
users ON Jardin.user_id = users.user_id 
WHERE 
Jardin.jardin_id = :id AND Jardin.is_public = true";

$db = getConnection();
$query = $db->prepare($sql);
$query->execute();
$query->bindParam(':id', $_GET['id']);
$jardin = $query->fetch();


header('Content-Type: application/json');

echo json_encode($jardin);
