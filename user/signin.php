<?php
require('../conf/header.inc.php');

?>
<div class="flex items-center justify-center">
    <form action="../process/login.proc.php" method="POST" class="flex flex-col gap-8 m-0">
        <div class="flex flex-col">
            <label for="email">Entrer votre email</label>
            <input type="email" name="email" id="email" placeholder="Email" class="border pl-4 py-2">
        </div>
        <div class="flex flex-col">
            <label for="password">Entrer votre mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" class="border pl-4 py-2">
        </div>
        <div class="flex flex-col gap-4">
            <button type="submit" class="bg-black text-white rounded-md py-2">Se connecter</button>
            <a href="./signup.php" class="border text-black rounded-md py-2 text-center">S'inscrire</a>
        </div>
        <?php
        if(isset($_SESSION['error']['message'])){ ?>
           <p class="text-red-800"><?= $_SESSION['error']['message'] ?></p>
           <?php
        }else if(isset($_SESSION['success']['message'])){ ?>
            <p class="text-lime-500"><?= $_SESSION['success']['message'] ?></p>
        <?php
        }
        ?>
    </form>
</div>


<?php  
require('../conf/footer.inc.php');
?>