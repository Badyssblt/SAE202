<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

require("../../../conf/function.inc.php");

extract($_GET);


$users = findAll("users");

echo json_encode($users);
