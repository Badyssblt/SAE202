<?php
$title = "Planta";
require('conf/header.inc.php');

?>
<main class="md:h-96">
    <div class="bg-no-repeat bg-cover h-48 image-mobile md:h-full">
        <div class="flex items-center justify-end h-full">
            <div class="text-center w-4/6 mr-12 py-2 px-4">
                <h2 class="text-2xl font-bold text-left">Bienvenue</h2>
                <p class="text-sm mt-4 text-left">Planta conserve l'humanisme qui la caractérise depuis sa création et est la référence française des jardins familiaux</p>
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-28 mt-4">Découvrir</a>
            </div>
        </div>
    </div>
</main>



<?php
// Section actualité
?>
<section class="bg-second mt-12 pb-4">
    <h3 class="font-bold text-xl text-center py-4">Actualités</h3>
    <div class="flex flex-col px-10 gap-4 md:justify-center md:flex-row">
        <div>
            <div>
                <img src="assets/images/image_fond.png" alt="">
            </div>
            <p class="font-bold">Comment planter des betteraves</p>
            <p class="w-52 md:w-72">Quelle saison ? Quelle espace ? Durée de la pousse?</p>
            <div class="flex justify-end">
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-40 text-white">En savoir plus</a>
            </div>
        </div>
        <div>
            <div>
                <img src="assets/images/image_fond.png" alt="">
            </div>
            <p class="font-bold">Comment planter des betteraves</p>
            <p class="w-52 md:w-72">Quelle saison ? Quelle espace ? Durée de la pousse?</p>
            <div class="flex justify-end">
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-40 text-white">En savoir plus</a>
            </div>
        </div>

        <div>
            <div>
                <img src="assets/images/image_fond.png" alt="">
            </div>
            <p class="font-bold">Comment planter des betteraves</p>
            <p class="w-52 md:w-72">Quelle saison ? Quelle espace ? Durée de la pousse?</p>
            <div class="flex justify-end">
                <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-40 text-white">En savoir plus</a>
            </div>
        </div>


    </div>
</section>

<section class="px-10 flex flex-col mt-12 md:flex-row">
    <img class="w-96" src="/assets/images/jardin.png" alt="">
    <div class="md:ml-24">
        <h4 class="text-2xl font-bold py-4 text-center md:text-left">Nos jardins</h4>
        <p class="text-justify md:w-1/2">a FNJFC contribue au développement des jardins familiaux tout en veillant au maintien des acquis des jardiniers. La Fédération crée, réhabilite, aménage, développe, gère, anime et défend ses groupes de jardins adhérents développe, gère, anime et défend ses groupes de jardins adhérents..</p>
        <div class="flex justify-start my-6">
            <a href="#" class="bg-main px-2 py-2 rounded-lg font-bold flex justify-center w-28">Découvrir</a>
        </div>
    </div>

</section>

<?php
require("conf/footer.inc.php");
?>