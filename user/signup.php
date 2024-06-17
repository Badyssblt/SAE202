<?php
$title = "Planta | Inscrivez vous sur Planta ! ";
require('../conf/header.inc.php');
?>
<div class="flex items-center justify-center">

    <form action="../process/register.proc.php" method="POST" class="flex flex-col gap-8 m-0" id="register-form">
        <h3 class="font-bold text-2xl">Inscrivez vous !</h3>
        <div class="flex flex-col">
            <label for="name" class="font-bold">Nom<span class="text-red-600 font-bold">*</span></label>
            <input type="text" name="name" id="name" class="border pl-4 py-2 px-4 rounded-md">
        </div>
        <div class="flex flex-col">
            <label for="email" class="font-bold">Email<span class="text-red-600 font-bold">*</span></label>
            <input type="email" name="email" id="email" class="border pl-4 py-2 px-4 rounded-md">
        </div>
        <div class="flex flex-col relative">
            <label for="password" class="font-bold">Mot de passe<span class="text-red-600 font-bold">*</span></label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" class="border pl-4 py-2 px-4 rounded-md">
            <span id="toggle-password" class="absolute right-4   cursor-pointer" style="bottom: 12px">
                <img src="/assets/images/oeil.png" alt="">
            </span>
        </div>
        <div class="flex flex-col relative">
            <label for="passwordConfirm" class="font-bold">Confirmation du mot de passe<span class="text-red-600 font-bold">*</span></label>
            <input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Mot de passe" class="border pl-4 py-2 px-4 rounded-md">
            <span id="toggle-passwordConfirm" class="absolute right-4   cursor-pointer" style="bottom: 12px">
                <img src="/assets/images/oeil.png" alt="">
            </span>
        </div>
        <div>
            <p id="error-message" class="text-red-600 font-bold"></p>
        </div>
        <div class="flex flex-col gap-4">
            <button type="submit" class="bg-main font-bold text-xl text-white rounded-full py-2">S'inscrire</button>
            <p class="font-bold text-xl text-center">Déjà un compte ?</p>
            <a href="./signin.php" class="border text-black rounded-md py-2 text-center">Se connecter</a>
        </div>
    </form>
</div>



<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

    });

    document.getElementById('toggle-passwordConfirm').addEventListener('click', function() {
        const passwordField = document.getElementById('passwordConfirm');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

    });

    document.getElementById('register-form').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('passwordConfirm').value;
        const errorMessage = document.getElementById('error-message');

        if (password !== passwordConfirm) {
            event.preventDefault();
            errorMessage.textContent = "Les mots de passe ne correspondent pas.";
        } else {
            errorMessage.textContent = "";
        }
    });
</script>