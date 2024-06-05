<?php

require('../conf/header.inc.php');
?>
<div class="flex items-center justify-center">

<form action="../process/register.proc.php" method="POST"  class="flex flex-col gap-8 m-0">
    <div class="flex flex-col">
        <label for="name">Entrer votre nom</label>
        <input type="text" name="name" id="name" placeholder="Nom" class="border pl-4 py-2">
    </div>
    <div class="flex flex-col">
        <label for="email">Entrer votre email</label>
        <input type="email" name="email" id="email" placeholder="Email" class="border pl-4 py-2">
    </div>
    <div class="flex flex-col">
        <label for="password">Entrer votre mot de passe</label>
        <input type="password" name="password" id="password" class="border pl-4 py-2" placeholder="Mot de passe">
    </div>
    <div class="flex flex-col gap-4">
            <button type="submit" class="bg-black text-white rounded-md py-2">S'inscrire</button>
            <a href="./signin.php" class="border text-black rounded-md py-2 text-center">Se connecter</a>
        </div>
</form>
</div>