<?php

require('../conf/function.inc.php');








if ($_FILES['image']['name'] != "") {
    $imageName = date("Y_m_d_H_i_s") . "---" . $_FILES["image"]["name"];
    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], "../assets/images/uploads/garden/" . $imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>' . "\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>' . "\n";
        die();
    }
    $sql = "UPDATE Jardin SET jardin_nom = :nom, jardin_position = :position, jardin_image = :image WHERE jardin_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(":nom", $_POST['nameEdit']);
    $query->bindParam(":position", $_POST['coor']);
    $query->bindParam(":image", $imageName);
    $query->bindParam(":id", $_POST['id']);
    $res = $query->execute();
} else {
    $sql = "UPDATE Jardin SET jardin_nom = :nom, jardin_position = :position WHERE jardin_id = :id";
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->bindParam(":nom", $_POST['nameEdit']);
    $query->bindParam(":position", $_POST['coor']);
    $query->bindParam(":id", $_POST['id']);
    $res = $query->execute();
}


header('Location: ../user/index.php');
