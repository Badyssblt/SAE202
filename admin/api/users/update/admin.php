<?php

// Ce fichier permet aux administrateurs du site de modifier un user avec toutes les colonnes

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    $response = ["message" => "Erreur lors de la requête"];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

require('../../../../conf/function.inc.php');

$password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);


if($_FILES != null && isset($_FILES["user_picture"])){
    $imageName = date("Y_m_d_H_i_s") . "---" . $_FILES["user_picture"]["name"];
    if(is_uploaded_file($_FILES["user_picture"]["tmp_name"])) {
        if(!move_uploaded_file($_FILES["user_picture"]["tmp_name"], "../../../../assets/images/uploads/users/".$imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>'."\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>'."\n";
        die();
    }




$sql = "UPDATE users 
SET 
    user_nom = :user_nom, 
    user_email = :user_email, 
    user_password = :user_password, 
    user_picture = :user_picture 
WHERE 
    user_id = :user_id;
";
$db = getConnection();
$query = $db->prepare($sql);
$query->bindParam(':user_nom', $_POST['user_nom']);
$query->bindParam(':user_email', $_POST['user_email']);
$query->bindParam(':user_password', $password);
$query->bindParam(':user_picture', $imageName);
$query->bindParam(':user_id', $_POST['id']);
}else {
    $sql = "UPDATE users 
    SET 
        user_nom = :user_nom, 
        user_email = :user_email, 
        user_password = :user_password    WHERE 
        user_id = :user_id;
    ";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(':user_nom', $_POST['user_nom']);
    $query->bindParam(':user_email', $_POST['user_email']);
    $query->bindParam(':user_password', $password);
    $query->bindParam(':user_id', $_POST['id']);
}



$res = $query->execute();

header('Content-Type: application/json');

echo json_encode($res);
