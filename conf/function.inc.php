<?php

require("conf.inc.php");

function getConnection()
{
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    return $db;
}

function findAll($tableName)
{
    $db = getConnection();
    $sql = "SELECT * FROM " . $tableName;
    $query = $db->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function delete($tableName, $column, $id){
    $db = getConnection();
    $sql = "DELETE FROM $tableName WHERE $column = :id";
    $query = $db->prepare($sql);
    $query->bindParam(':id', $id);
    $res = $query->execute();
    return $res;
}

function findOne($tableName, $id)
{
    if(!is_int($id)) return false;
    $db = getConnection();
    $sql = "SELECT * FROM " . $tableName . "WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindParam('id', $id);
    $query->execute();
    return $query->fetch();
}

function sql($sql){
    $db = getConnection();
    $query = $db->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}



/**
 * Inscription des utilisateurs et vérification de l'unicité de l'email
 *
 * @param Array $data
 * @return bool
 */
function register($data)
{
    extract($data);
    $db = getConnection();
    $sql = "SELECT * FROM users WHERE user_email = :email";
    $query = $db->prepare($sql);
    $query->bindParam(':email', $email);
    $query->execute();
    $user = $query->fetch();
    if($user) {
        setErrorMessage("Un utilisateur avec cette adresse email existe déjà.", "../user/signup.php");
    }
    $sql = "INSERT INTO users (user_nom, user_email, user_password, user_picture) VALUES (:name, :email, :password, 'test')";
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = $db->prepare($sql);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return ['message' => 'Veuillez entrer un message valide'];
        die();
    }
    $query->bindParam(':name', $name);
    $query->bindParam(':email', $email);
    $query->bindParam(':password', $password);
    $res = $query->execute();
    if($res){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['success']['message'] = "Inscription réussit, veuillez vous connecter";
        $_SESSION['error'] = [];
        header('Location: ../user/signin.php');
    }
}

function login($data)
{
    extract($data);
    $db = getConnection();
    $sql = "SELECT * FROM users WHERE user_email = :email";
    $query = $db->prepare($sql);
    $query->bindParam(':email', $email);
    $query->execute();
    $user = $query->fetch();
    if(!$user) setErrorMessage("L'utilisateur est introuvable", '../user/signin.php');
    
    if(password_verify($password, $user['user_password'])){
        session_start();
        $_SESSION['error'] = [];
        $_SESSION['name'] = $user['user_nom'];
        $_SESSION['email'] = $user['user_email'];
        $_SESSION['id'] = $user['user_id'];
        $_SESSION['error']['message'] = '';
        $_SESSION['success']['message'] = '';
        header('Location: /');
    }else {
        setErrorMessage("Le mot de passe est incorrect", '../user/signin.php');
    }
}

function setErrorMessage($msg, $redirection){
    if(!isset($_SESSION)){
        session_start();
        $_SESSION['error']['message'] = $msg;
        header("Location: $redirection");
        die();
    }else {
        $_SESSION['error']['message'] = $msg;
        header("Location: $redirection");
        die();
    }
}

function setSuccessMessage($msg, $redirection)
{
    if(!isset($_SESSION)){
        session_start();
        $_SESSION['success']['message'] = $msg;
        header("Location: $redirection");
        die();
    }else {
        $_SESSION['success']['message'] = $msg;
        header("Location: $redirection");
        die();
    }
}

function addGarden($data, $file)
{
    extract($data);
    $db = getConnection();
    $sql = "INSERT INTO Jardin (jardin_nom, jardin_position, jardin_image, user_id, is_public) VALUES (:name, :position, :image, :userID, false)";
    $imageName = date("Y_m_d_H_i_s") . "---" . $file["image"]["name"];
    if(is_uploaded_file($file["image"]["tmp_name"])) {
        if(!move_uploaded_file($file["image"]["tmp_name"], "../assets/images/uploads/garden/".$imageName)) {
            echo '<p>Problème avec la sauvegarde de l\'image, désolé...</p>'."\n";
            die();
        }
    } else {
        echo '<p>Problème : image non chargée...</p>'."\n";
        die();
    }

    if(!isset($_SESSION)) session_start();

    if(!isset($_SESSION['id'])){
        header('Location: ../user/signin.php');
        die();
    }
    $query = $db->prepare($sql);
    $query->bindParam(':name', $name);
    $query->bindParam(':position', $position);
    $query->bindParam(':image', $imageName);
    $query->bindParam(':userID', $_SESSION['id']);
    $res = $query->execute();
    if($res){
        setSuccessMessage("Votre jardin a bien été ajoutée", "../user/index.php");
    }
}



