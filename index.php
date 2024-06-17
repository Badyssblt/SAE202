<?php
$title = "Planta";
require('conf/header.inc.php');

?>
<main class="md:min-h-screen">
    <div class="bg-no-repeat bg-cover md:h-full md:h-72">
        <div class="flex items-center justify-center h-full px-10 flex-col  md:flex-row">
            <div>
                <img src="/assets/images/cojardinage.png" alt="" >
            </div>
            <div class="text-center w-4/6 py-2 px-4">
                <h2 class="font-bold text-center text-7xl" style="color: #93A267">Planta</h2>
                <p class="text-lg mt-4 text-center text-black">Découvrez une plateforme de co-jardinage vous permettant de partagez<br> vos jardins ou cultivez dans des jardins d’autres utilisateurs </p>
                <div class="flex justify-center gap-4">
                <a href="/user/signup.php" class="bg-lime-800	 px-2 py-2 rounded-lg font-bold flex justify-center w-28 mt-4 text-white">S'inscrire</a>
                    <a href="/garden/index.php" style="border-color: #93A267;" class="border border-2 px-2 py-2 rounded-lg font-bold flex justify-center w-28 mt-4 text-black">Découvrir</a>
                </div>
            </div>
        </div>
    </div>
</main>



<?php
// Section actualité
?>
<section class=" mt-12 pb-4">
    <h3 class="font-bold text-2xl text-center py-4">Actualités</h3>
    <div class="flex flex-col px-10 gap-4 md:justify-center md:flex-row">
        <div class="border h-fit px-2 pb-6 pt-2 rounded-lg">
            <div>
                <img src="assets/images/image_fond.png" alt="">
            </div>
            <p class="font-bold text-lg">Comment planter des betteraves</p>
            <p class="w-52 md:w-72">Quelle saison ? Quelle espace ? Durée de la pousse?</p>
            <div class="flex justify-center mt-4">
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-40 text-white">En savoir plus</a>
            </div>
        </div>
        <div class="border h-fit px-2 pb-6 pt-2 rounded-lg">
            <div>
                <img src="assets/images/image_fond.png" alt="">
            </div>
            <p class="font-bold text-lg">Comment planter des betteraves</p>
            <p class="w-52 md:w-72">Quelle saison ? Quelle espace ? Durée de la pousse?</p>
            <div class="flex justify-center mt-4">
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-40 text-white">En savoir plus</a>
            </div>
        </div>

        <div class="border h-fit px-2 pb-6 pt-2 rounded-lg">
            <div>
                <img src="assets/images/image_fond.png" alt="">
            </div>
            <p class="font-bold text-lg">Comment planter des betteraves</p>
            <p class="w-52 md:w-72">Quelle saison ? Quelle espace ? Durée de la pousse?</p>
            <div class="flex justify-center mt-4">
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-40 text-white">En savoir plus</a>
            </div>
        </div>

    </div>
</section>

<section class="px-10 flex flex-col mt-12">
    <img class="w-full" src="/assets/images/jardin.png" alt="">
    <div>
        <h4 class="text-2xl font-bold py-4 text-center md:text-left">Nos jardins</h4>
        <p class="text-justify md:w-full">Planta contribue au développement des jardins familiaux tout en veillant au maintien des acquis des jardiniers. Aussi bien débutants que jardiniers aguerrit, grâce à Planta, vous pouvez faire différentes rencontres et créer de vrais liens d'amitié autour de votre passion commune.</p>
        <div class="flex justify-center my-6 md:justify-end">
            <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-28 text-white">Découvrir</a>
        </div>
    </div>

</section>

<?php
require("conf/footer.inc.php");
?>