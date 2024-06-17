<?php
$title = "Nos jardins";
$activePage = "garden";
require('../conf/header.inc.php');
require('../conf/function.inc.php');

$sql = "SELECT jardin_id, jardin_image
FROM Jardin WHERE is_public = true
ORDER BY RAND()
LIMIT 3";
$randomJardin = sql($sql);

$sql = "SELECT Jardin.*, users.user_nom, COUNT(parcelle.parcelle_id) AS parcelle_count FROM Jardin INNER JOIN users ON Jardin.user_id = users.user_id LEFT JOIN parcelle ON Jardin.jardin_id = parcelle.jardin_id  WHERE Jardin.is_public = true GROUP BY Jardin.jardin_id, users.user_nom";
$jardins = sql($sql);
?>

<div class="mt-10">
    <h2 class="font-bold text-4xl mb-4 text-left ml-10" style="color: #3E582A;">Nos jardins</h2>
    <div>
        <h3 class="font-bold text-xl p-10">Nos jardins les plus fréquentés</h3>
        <section id="image-carousel" class="splide w-full h-48 md:h-[400px] overflow-hidden" aria-label="Beautiful Images">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php
                    foreach ($randomJardin as $jardin) { ?>
                        <li class="splide__slide">
                            <a href="/garden/single.php?id=<?= $jardin['jardin_id'] ?>"><img src="/assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt=""></a>
                        </li>
                    <?php

                    }
                    ?>
                </ul>
            </div>
        </section>
    </div>
</div>

<div class="px-8 mb-12">
    <div class="ml-4 my-4 md:flex md:flex-row md:items-center">
        <div class="flex flex-row justify-between">
            <p class="font-bold text-lg">Trier par :</p>
            <div class="md:hidden">
                <button onclick="collapseSorted()"><img src="../assets/images/burger.png" alt=""></button>
            </div>
        </div>
        
        <div class="p-4 rounded-lg flex-col flex gap-4 hidden items-start md:flex-row md:flex" id="sorted">
            <button onclick="sortBy('name')" class="bg-slate-200 px-4 py-2 rounded-full">Nom</button>
            <button onclick="sortBy('created')" class="bg-slate-200 px-4 py-2 rounded-full">Date de création</button>
        </div>
    </div>
    <div class="flex flex-wrap gap-8" id="listing">
        <?php
        foreach ($jardins as $jardin) { ?>
            <div class="flex flex-col gap-4 w-96 border py-4 px-2 shadow-md">
                <div class="w-full h-64 rounded-xl overflow-hidden">
                    <img src="../assets/images/uploads/garden/<?= $jardin['jardin_image'] ?>" alt="" class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-center text-xl underline"><?= $jardin['jardin_nom'] ?></h3>
                <h4 class="font-bold text-center flex items-center justify-center"><img class="w-8 mr-4" src="../assets/images/uploads/users/user.png" alt="">Propriétaire: <span class="font-bold"><?=  $jardin['user_nom'] ?></span></h4>
                <p class="font-bold text-center ">Nombre de parcelle: <span class="font-bold"><?= $jardin['parcelle_count'] ?></span></p>

                <div>
                    <a href="./single.php?id=<?= $jardin['jardin_id'] ?>" class="bg-main font-bold text-lg text-white py-2 px-4 mx-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                </div>
            </div>

        <?php
        }

        ?>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Splide('#image-carousel', {
            type: 'loop',
            autoplay: true,
            interval: 3000,
            pagination: true,
            arrows: true,
        }).mount();
    });

    function collapseSorted()
    {
        const div = document.getElementById("sorted");
        div.classList.toggle("hidden");
    }

    async function sortBy(sorted)
    {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/garden/search.php?category=" + sorted,
                dataType: "JSON",
                success: function (response) {
                    displayGarden(response);
                }
            });
        } catch (error) {
            
        }
    }

    async function displayGarden(data)
    {
        const wrapper = document.getElementById("listing");
        wrapper.innerHTML = "";

        if(data.length <= 0){
            const div = `<p>Aucun résultat !</p>`;
            wrapper.append(div);
            return;
        }

        
        data.forEach(element => {
            const div = 
            `
            <div class="flex flex-col gap-4 w-96 border py-4 px-2 shadow-md">
                <div class="w-full h-64 rounded-xl overflow-hidden">
                    <img src="../assets/images/uploads/garden/${element['jardin_image']}" alt="" class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-center text-xl underline">${element['jardin_nom']}</h3>
                <h4 class="font-bold text-center flex items-center justify-center"><img class="w-8 mr-4" src="../assets/images/uploads/users/user.png" alt="">Propriétaire: <span class="font-bold">${element['user_nom']}</span></h4>
                <p class="font-bold text-center ">Nombre de parcelle: <span class="font-bold">${element['parcelle_count']}</span></p>

                <div>
                    <a href="./single.php?id=${element['jardin_id']}" class="bg-main font-bold text-lg text-white py-2 px-4 mx-4 rounded-sm flex justify-center mt-2">Voir plus</a>
                </div>
            </div>
            `;

            wrapper.innerHTML += div;
        })
    }
</script>


<style>
    .splide__slide img {
        width: 100%;
        height: 100%;
    }
</style>

<?php

require("../conf/footer.inc.php")
?>