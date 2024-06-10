<?php
require('../../../conf/function.inc.php');

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postContent = $_POST['postContent'];
    $userId = $_SESSION['id'];

    if (empty($postContent)) {
        echo json_encode(['success' => false, 'message' => 'Le contenu de la publication ne peut pas être vide.']);
        exit;
    }
    $imageName = date("Y_m_d_H_i_s") . "---" . $_FILES["image"]["name"];
    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], "../../../assets/images/uploads/posts/" . $imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>' . "\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>' . "\n";
        die();
    }
    $db = getConnection();
    $sql = "INSERT INTO post (post_content, user_id, created_at, post_image) VALUES (?, ?, NOW(), ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$postContent, $userId, $imageName]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la publication.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
?>
