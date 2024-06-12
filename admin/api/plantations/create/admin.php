<?php

// Ce fichier permet aux administrateurs du site d'ajouter une parcelle avec toutes les colonnes

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');



$imageName = date("Y_m_d_H_i_s") . "---" . $_FILES["image"]["name"];
    if(is_uploaded_file($_FILES["image"]["tmp_name"])) {
        if(!move_uploaded_file($_FILES["image"]["tmp_name"], "../../../../assets/images/uploads/plants/".$imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>'."\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>'."\n";
        die();
    }

$sql = "INSERT INTO plantations (plantation_nom, plantation_image) VALUES (:name, :image)";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':name', $_POST['name']);
$query->bindParam(':image', $imageName);

$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
