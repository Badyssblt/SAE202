<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

session_start();

require('../../../conf/function.inc.php');


if($_FILES != null){
    $imageName = date("Y_m_d_H_i_s") . "---" . $_FILES["image"]["name"];
    if(is_uploaded_file($_FILES["image"]["tmp_name"])) {
        if(!move_uploaded_file($_FILES["image"]["tmp_name"], "../../assets/images/uploads/garden/".$imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>'."\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>'."\n";
        die();
    }


    $sql = "UPDATE post SET post_content = :content, user_id = :userID, post_image = :image WHERE post_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':content', $_POST['post_content']);
    $query->bindParam(':userID', $_POST['userID']);
    $query->bindParam(':image', $imageName);
    $query->bindParam(':id', $_POST['id']);
}else {
    $sql = "UPDATE post SET post_content = :content, user_id = :userID WHERE post_id = :id";    
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':content', $_POST['content']);
    $query->bindParam(':userID', $_POST['userID']);
    $query->bindParam(':id', $_POST['id']);
}



$res = $query->execute();

if($res){
    $_SESSION['success']['message'] = "Les changements ont bien été pris en compte";
}else {
    $_SESSION['error']['message'] = "Une erreur est survenue lors des changements";
}


header('Content-Type: application/json');

echo json_encode($res);

