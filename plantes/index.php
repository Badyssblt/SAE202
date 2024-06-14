<?php
$activePage = "plantes";
$title = "Nos plantes";
require('../conf/header.inc.php');
require("../conf/function.inc.php");

$plantes = findAll("plantations");

?>
<h2 class="font-bold text-2xl text-center mt-8 underline md:py-2 md:rounded-xl md:pl-8 md:no-underline" style="color: #3E572D">Nos plantes</h2>

<div class="flex flex-wrap gap-8 justify-center mt-12" id="listing">
        <?php
        foreach ($plantes as $plante) { ?>
            <div class="border shadow-lg flex flex-col items-center gap-4 w-96 border rounded-lg py-4">
                <div class="overflow-hidden h-48 w-72 flex"> <!-- Ajustez la hauteur et la largeur selon vos besoins -->
                    <img src="../assets/images/uploads/plants/<?= $plante['plantation_image'] ?>" alt="" class="object-cover h-full w-full">
                </div>
                <h3 class="font-bold text-center text-xl"><?= $plante['plantation_nom'] ?></h3>
            </div>

        <?php
        }

        ?>

    </div>
    <?php
require('../conf/footer.inc.php');
?>
