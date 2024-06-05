<?php

require('../conf/header.inc.php');
require('../conf/function.inc.php');


$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id GROUP BY Jardin.jardin_id, users.user_nom";
$jardins = sql($sql);
?>
<div class="px-8">
<h2 class="font-bold text-xl mb-4">Listes des jardins à Troyes</h2>
<div class="ml-4 my-4">
    <p class="font-bold text-lg">Trier par</p>
    <div class="flex flex-row gap-4">
        <button onclick="sortBy('name')">Nom</button>
        <button onclick="sortBy('name')">Parcelles</button>
        <button onclick="sortBy('name')">Date de création</button>
    </div>
</div>
<div class="flex flex-wrap gap-8">
    <?php
        foreach($jardins as $jardin){ ?>
            <div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full">
                <h3><?= $jardin['jardin_nom'] ?></h3>
                <p>Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>
                <h4>Propriétaire: <span class="font-bold"><?= $jardin['user_nom'] ?></span></h4>
                <div>
                    <a href="./single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-black text-white py-2 px-4 mx-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                </div>
            </div>
        <?php
        }

    ?>
    
</div>

</div>


