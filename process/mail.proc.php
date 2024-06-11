<?php

require('../conf/function.inc.php');

extract($_POST);


$headers = 'From: sender@example.com' . "\r\n" .
           'Reply-To: sender@example.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($email, $subject, $message, $headers)) {
    echo 'Email envoyé avec succès';
} else {
    echo 'L\'envoi de l\'email a échoué';
}