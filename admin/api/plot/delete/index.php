<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

require("../../../conf/function.inc.php");

extract($_GET);


$res = delete("parcelle", "parcelle_id", $id);

if ($res) {
    http_response_code(201);
    
    echo json_encode(null);
} else {
    http_response_code(500);
    
    echo json_encode(["message" => "Erreur lors de la suppression du jardin"]);
}
