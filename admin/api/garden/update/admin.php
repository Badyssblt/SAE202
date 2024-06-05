<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');


if($_FILES != null){
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


    $sql = "UPDATE Jardin SET jardin_nom = :jardin_nom, jardin_position = :jardin_position, jardin_image = :jardin_image, user_id = :user_id, is_public = :is_public WHERE jardin_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':jardin_nom', $_POST['jardin_nom']);
    $query->bindParam(':jardin_position', $_POST['jardin_position']);
    $query->bindParam(':jardin_image', $imageName);
    $query->bindParam(':user_id', $_POST['user_id']);
    $query->bindParam(':is_public', $_POST['is_public']);
    $query->bindParam(':id', $_POST['jardin_id']);
}else {
    $sql = "UPDATE Jardin SET jardin_nom = :jardin_nom, jardin_position = :jardin_position, user_id = :user_id, is_public = :is_public WHERE jardin_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':jardin_nom', $_POST['jardin_nom']);
    $query->bindParam(':jardin_position', $_POST['jardin_position']);
    $query->bindParam(':user_id', $_POST['user_id']);
    $query->bindParam(':is_public', $_POST['is_public']);
    $query->bindParam(':id', $_POST['jardin_id']);
}




$res = $query->execute();


header('Content-Type: application/json');

echo json_encode($res);
