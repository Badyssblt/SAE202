<?php

// Ce fichier permet aux administrateurs du site d'ajouter un jardin avec toutes les colonnes

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');


extract($_POST);




$imageName = date("Y_m_d_H_i_s") . "---" . $_FILES["jardin_image"]["name"];
    if(is_uploaded_file($_FILES["jardin_image"]["tmp_name"])) {
        if(!move_uploaded_file($_FILES["jardin_image"]["tmp_name"], "../../../../assets/images/uploads/garden/".$imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>'."\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>'."\n";
        die();
    }



$sql = "INSERT INTO Jardin (jardin_nom, jardin_position, jardin_image, user_id, is_public) VALUES (:jardin_nom, :jardin_position, :jardin_image, :user_id, :is_public)";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':jardin_nom', $jardin_nom);
$query->bindParam(':jardin_position', $jardin_position);
$query->bindParam(':jardin_image', $imageName);
$query->bindParam(':user_id', $user_id);
$query->bindParam(':is_public', $is_public);

$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
