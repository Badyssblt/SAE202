<?php

require("../conf/header.inc.php");

?>


<form action="../process/mail.proc.php" method="POST">
    <div class="flex flex-col">
        <label for="subject">Entrer votre sujet</label>
        <input type="text" name="subject" id="subject" placeholder="Email">
    </div>
    <div class="flex flex-col">
        <label for="email">Entrer votre email</label>
        <input type="email" name="email" id="email" placeholder="Email">
    </div>
    <div class="flex flex-col mt-6">
        <label for="content">Entrer votre message</label>
        <textarea name="content" id="content" placeholder="Contenu du mail"></textarea>
    </div>
    <button type="submit">Envoyer un mail</button>
</form>