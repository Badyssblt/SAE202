<?php

require('../conf/function.inc.php');

extract($_POST);

var_dump($_POST);

$email_dest = "mmi23g02@mmi-troyes.fr";

$headers['From'] = $email;
$headers['Reply-to'] = $email;
$headers['X-Mailer'] = 'PHP/' . phpversion();
$headers['MIME-Version'] = '1.0';   
$headers['Content-type'] = 'text/html; charset=utf-8';


if (mail($email_dest, $subject, $message, $headers)) {
    echo 'Email envoyé avec succès';
} else {
    echo 'L\'envoi de l\'email a échoué';
}
