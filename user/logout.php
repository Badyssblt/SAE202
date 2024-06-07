<?php

if(isset($_SESSION)){
    session_destroy();
}else {
    session_start();
    session_destroy();
}

header('Location: ./signin.php');