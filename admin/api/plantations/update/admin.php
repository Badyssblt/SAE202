<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

require('../../../../conf/function.inc.php');


$sql = "UPDATE plantations SET plantation_nom = :nom WHERE plantation_id = :id";
$db = getConnection();
$query = $db->prepare($sql);

$query->bindParam(':nom', $_POST['name']);
$query->bindParam(':id', $_POST['id']);


$res = $query->execute();

header('Content-Type: application/json');
echo json_encode($res);
