<?php
$activePage = "plantes";
require('../conf/header.inc.php');
require("../conf/function.inc.php");

$plantes = findAll("plantations");

?>
<h2 class="font-bold text-2xl text-center mt-8 underline md:bg-[#93A267]/70 md:py-2 md:w-1/2 md:rounded-xl md:pl-8 md:text-left md:no-underline" style="color: #3E572D">Nos plantes</h2>

<div class="flex flex-wrap gap-8 justify-center mt-12" id="listing">
        <?php
        foreach ($plantes as $plante) { ?>
            <div class="flex flex-col gap-4 w-96 border rounded-lg py-4">
                <h3 class="font-bold text-center text-xl"><?= $plante['plantation_nom'] ?></h3>
            </div>

        <?php
        }

        ?>

    </div>
