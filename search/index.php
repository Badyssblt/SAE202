<?php

require("../conf/header.inc.php");
require("../conf/function.inc.php");

$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id  WHERE Jardin.is_public = true AND Jardin.jardin_nom LIKE :query GROUP BY Jardin.jardin_id, users.user_nom";

$db = getConnection();
$query = $db->prepare($sql);
$query->bindValue(":query", "%" . $_GET['query'] . "%");
$query->execute();
$jardins = $query->fetchAll(PDO::FETCH_ASSOC);

?>




<div class="flex flex-wrap gap-8" id="listing">
        <?php
        if(count($jardins) <= 0){ ?>
            <p>Aucun résultat</p>
        <?php
        }

        foreach ($jardins as $jardin) { ?>
            <div class="flex flex-col gap-4 w-96">
                <div class="w-full h-64 rounded-xl overflow-hidden">
                    <img src="../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-center text-xl underline"><?= $jardin['jardin_nom'] ?></h3>
                <h4 class="font-bold text-center flex items-center justify-center"><img class="w-8 mr-4" src="../assets/images/uploads/users/user.png" alt="">Propriétaire: <span class="font-bold"><?=  $jardin['user_nom'] ?></span></h4>
                <p class="font-bold text-center ">Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>

                <div>
                    <a href="./single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-black text-white py-2 px-4 mx-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                </div>
            </div>

        <?php
        }

        ?>

    </div>

