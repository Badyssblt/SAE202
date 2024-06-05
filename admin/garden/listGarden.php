<?php

require('../../conf/header.inc.php');
require('../../conf/function.inc.php');


$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id GROUP BY Jardin.jardin_id, users.user_nom";
$jardins = sql($sql);
?>

<div class="px-8">
    
<div class="flex flex-row gap-8">
    <a href="./listGarden.php" class="bg-black text-white py-2 px-4 rounded-sm flex justify-center mt-2">Liste des jardins</a>
    <a href="../plots/listPlot.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des parcelles</a>
    <a href="../users/listUsers.php" class="border text-black py-2 px-4 rounded-sm flex justify-center mt-2">Liste des utilisateurs</a>
</div>

<h2 class="font-bold text-xl mt-12 mb-8">Liste des jardins</h2>
<div class="flex flex-wrap gap-2" id="listing">
    <?php
        foreach($jardins as $jardin){ ?>
            <div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full">
                <h3><?= $jardin['jardin_nom'] ?></h3>
                <p>Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>
                <h4>Propriétaire: <span class="font-bold"><?= $jardin['user_nom'] ?></span></h4>
                <div class="flex flex-row">
                    <button>Modifier</button>
                    <button onclick="deleteGarden(<?= $jardin['jardin_id'] ?>)">Supprimer</button>
                </div>
            </div>
        <?php
        }

    ?>
    
</div>
</div>



<script>
    async function deleteGarden(id){
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../../api/garden/delete",
                data: {
                    id
                },
                dataType: "JSON",
                success: function (response) {
                    fetchGardens();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }


    async function fetchGardens(){
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../../api/garden",
                dataType: "JSON",
                success: function (response) {
                    displayGarden(response);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    function displayGarden(data){
        const wrapper = $("#listing");
        wrapper.empty();
        data.forEach(element => {
            const div = `<div class="border rounded-sm flex flex-col gap-4 w-96">
                <img src="../../assets/images/uploads/garden/${element['jardin_image']}" alt="" class="w-full">
                <h3>${element['jardin_nom']}</h3>
                <p>Nombre de parcelle: <span class="font-bold">${element['parcelle_count']}</span></p>
                <h4>Propriétaire: <span class="font-bold">${element['user_nom']}</span></h4>
                <div>
                    <button>Modifier</button>
                    <button onclick="deleteGarden(${element['jardin_id']})">Supprimer</button>
                </div>
            </div>`;
            wrapper.append(div);
        });
    }
</script>


