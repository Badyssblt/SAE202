<?php

require('../../conf/header.inc.php');
require('../../conf/function.inc.php');


$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id  WHERE Jardin.is_public = true GROUP BY Jardin.jardin_id, users.user_nom";

$jardins = sql($sql);

$sql = "SELECT COUNT(*) AS user_count FROM users";

$users = sql($sql);
?>

<div>
    <p>Nombre d'utilisateur: <?= $users[0]["user_count"] ?></p>
</div>

<div class="flex flex-wrap gap-8">
        <?php
        foreach ($jardins as $jardin) { ?>
            <div class="flex flex-col gap-4 w-96">
                <div class="w-full h-64 rounded-xl overflow-hidden">
                    <img src="../../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-center text-xl"><?= $jardin['jardin_nom'] ?></h3>
                <p class="font-bold text-center">Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>
                <h4 class="font-bold text-center">Propriétaire: <span class="font-bold"><?= $jardin['user_nom'] ?></span></h4>
                <div>
                    <a href="./single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-black text-white py-2 px-4 mx-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                </div>
            </div>

        <?php
        }

        ?>

    </div>

