<?php

require('../conf/functions.inc.php');

$sql = "SELECT 
parcelle.parcelle_id,
parcelle.jardin_id,
parcelle.user_id,
parcelle.isAccepted,
parcelle.parcelle_nom,
users.user_nom,
Jardin.jardin_nom,
Jardin.jardin_position,
Jardin.jardin_image,
plantations.plantation_nom,
plantations.plantation_id
FROM parcelle 
INNER JOIN Jardin ON Jardin.jardin_id = parcelle.jardin_id
LEFT JOIN users ON parcelle.user_id = users.user_id
LEFT JOIN plantations ON parcelle.plantation_id = plantations.plantation_id;
WHERE Jardin.user_id = :userID";

$db = getConnection();

$query = $db->prepare($sql);

$query->bindParam(':userID', $_SESSION['id']);

$query->execute();


$parcelles = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="flex flex-wrap gap-8" id="listing">
    
    <?php

    if(count($parcelles) <= 0){ ?>
        <p class="mt-8">Il n'y a aucune parcelle dans la base de donnée...</p>
<?php
    }

    foreach ($parcelles as $parcelle) { 
        $isAvailable = $parcelle['isAccepted'] == 1 ? false : true;  
        $isWaiting = ($isAvailable && $parcelle['user_nom'] != null) ? true : false;
        ?>
        <div class="border p-4 rounded-sm w-48">
            <h2><?= $parcelle['jardin_nom'] ?></h2>
            <p>Type: <span class="font-bold"><?= $parcelle['plantation_nom'] ?></span></p>
            <div class="mt-4">
                <h3 class="font-bold">Status</h3>
                <?php
                    if($isWaiting && $isAvailable){ ?>
                    <p>Demande en cours</p>
                    <div class="flex flex-row mt-2 gap-2">
                        <a  class="bg-lime-800 text-white py-2 px-4 rounded-sm" onclick="acceptRequest('accepted', <?= $parcelle['parcelle_id'] ?>)">Accepter</a>
                        <a href="../process/plot/denied.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm" onclick="acceptRequest('denied', <?= $parcelle['parcelle_id'] ?>)">Refuser</a>
                    </div>
<?php
                    }else if(!$isWaiting && $isAvailable){ ?>
                        <p>Disponible</p>
                    <?php
                    }else if(!$isWaiting && !$isAvailable) { ?>
                        <p>Détenu par <span class="font-bold"><?= $parcelle['user_nom']?></span></p>
                    <?php
                    }
                ?>
            </div>
            <a href="../../garden/single.php?id=<?= $parcelle['jardin_id'] ?>" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Voir plus</a>
            <a href="../process/plot/delete.proc.php?id=<?= $parcelle['parcelle_id'] ?>" class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            <button class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2" onclick='displayEditForm(<?= json_encode($parcelle) ?>)'>Modifier</button>
        </div>
        <?php
    }
    ?>

</div>