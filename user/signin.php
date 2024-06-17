<?php
$title = "Planta | Connectez vous !";
require('../conf/header.inc.php');

?>
<div class="flex mt-20 justify-center h-screen">
    <form action="../process/login.proc.php" method="POST" class="flex flex-col gap-8 m-0">
        <h3 class="font-bold text-2xl">Connectez-vous !</h3>
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
        <div class="flex flex-col gap-4">
            <button type="submit" class="bg-main font-bold text-xl text-white rounded-full py-2">Se connecter</button>
            <a href="./signup.php" class="border text-black rounded-md py-2 text-center">S'inscrire</a>
        </div>
        <?php
        if (isset($_SESSION['error']['message'])) { ?>
            <p class="text-red-800"><?= $_SESSION['error']['message'] ?></p>
        <?php
        } else if (isset($_SESSION['success']['message'])) { ?>
            <p class="text-lime-500"><?= $_SESSION['success']['message'] ?></p>
        <?php
        }
        ?>
    </form>
</div>


<?php
require('../conf/footer.inc.php');
?>

<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

    });
</script>