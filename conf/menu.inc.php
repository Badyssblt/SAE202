<?php

?>

<header class="px-10">
    <div class="flex flex-row justify-between mt-8">
        <h1><a href="/"><img src="/assets/images/icon.png" alt=""></a></h1>
        <div class="flex items-center md:hidden">
        <li class="list-none <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>"><a href="/user" class="bg-main rounded-full px-6 py-2 text-white">Mon profil</a></li>
        </div>
        <div class="hidden md:flex flex-row gap-8">
            <?php
            if (isset($_SESSION['id'])) { ?>
                <div class="border-r-2 pr-2 flex flex-row gap-4">
                <li class="list-none"><a class="font-bold shadow-md px-4 py-2 rounded-lg mr-8" href="/social/index.php">Planta.net</a></li>
                <li class="list-none"><a class="font-bold" href="/user/logout.php">Se déconnecter</a></li>
                    <li class="list-none <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>"><a href="/user" class="bg-main rounded-full px-6 py-2 text-white">Mon profil</a></li>
                </div>
            <?php
            } else { ?>
                <div class="border-r-2 pr-2 flex flex-row gap-2">
                    <li class="list-none"><a href="/user/signup.php" class="font-bold <?php echo ($activePage === 'register') ? 'underline' : ''; ?>">S'inscrire</a></li>
                    <li class="list-none"><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full <?php echo ($activePage === 'login') ? 'underline' : ''; ?>">Se connecter</a></li>
                </div>
            <?php
            }
            ?>
            <div>
                <h2>Langues</h2>
            </div>
        </div>
    </div>

    <?php
        // Menu téléphone bottom
    ?>
    <menu class="fixed bottom-0 bg-white w-full py-4 border-t-2 left-0 px-10 z-50 md:hidden">
        <div class="flex flex-row justify-between bg-white">
        <li class="flex flex-row gap-2">
                <a href="/garden" class="<?php echo ($activePage === 'garden') ? 'underline' : ''; ?> whitespace-nowrap">           <img src="/assets/images/plants.png" alt=""></a>
            </li>
            <li class="flex flex-row gap-2">
                <a href="/admin/garden/listGarden.php" class="<?php echo ($activePage === 'admin') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/admin.png" alt=""></a>
            </li>
            <li class="flex flex-row gap-2">
                <a href="/contact/index.php" class="<?php echo ($activePage === 'contact') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/contact.png" alt=""></a>
            </li>
            <li class="flex flex-row gap-2">
                <a href="/plantes/index.php" class="<?php echo ($activePage === 'plantes') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/plante.png" alt=""></a>
            </li>
        </div>
    </menu>

    <menu id="menu" class="mt-6 mb-4 hidden md:flex flex-col md:flex-row items-center justify-center gap-20">
            <div class="flex flex-col md:flex-row gap-10">
            <li class="flex flex-row gap-2">
                <img src="/assets/images/plants.png" alt="">
                <a href="/garden" class="<?php echo ($activePage === 'garden') ? 'underline' : ''; ?> whitespace-nowrap">Nos jardins</a>
            </li>
            <li class="flex flex-row gap-2">
                <img src="/assets/images/admin.png" alt="">
                <a href="/admin/garden/listGarden.php" class="<?php echo ($activePage === 'admin') ? 'underline' : ''; ?> whitespace-nowrap">Administrateur</a>
            </li>
            <li class="flex flex-row gap-2">
                <img src="/assets/images/admin.png" alt="">
                <a href="/contact/index.php" class="<?php echo ($activePage === 'contact') ? 'underline' : ''; ?> whitespace-nowrap">Contact</a>
            </li>
            <li class="flex flex-row gap-2">
                <img src="/assets/images/plante.png" alt="">
                <a href="/plantes/index.php" class="<?php echo ($activePage === 'plantes') ? 'underline' : ''; ?> whitespace-nowrap">Nos plantes</a>
            </li>
        </div>

        <div class="mt-4 md:mt-0">
            <form action="/search/index.php" method="GET">
                <input placeholder="Rechercher" type="search" name="query" id="query" class="bg-no-repeat pr-6 pl-4 py-2 border focus:outline-none" style="background-image: url('/assets/images/search.png'); background-position: right 6px center;">
            </form>
        </div>
        <div id="connect" class="hidden">
            <div class="md:flex md:flex-col flex-row gap-8">
                <?php
                if (isset($_SESSION['id'])) { ?>
                    <div class="border-r-2 pr-2">
                        <li class="list-none"><a href="/user <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>">Mon compte</a></li>
                    </div>
                <?php
                } else { ?>
                    <div class="border-r-2 pr-2 flex md:flex-row gap-2 flex-col">
                        <li class="list-none"><a href="/user/signup.php" class="font-bold <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>">S'inscrire</a></li>
                        <li class="list-none"><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>">Se connecter</a></li>
                    </div>
                <?php
                }
                ?>
                <div>
                    <h2>Langues</h2>
                </div>
            </div>
        </div>
    </menu>
</header>

<body>
    <script>
        document.getElementById('menu-button').addEventListener('click', function() {
            var menu = document.getElementById('menu');
            const connect = document.getElementById('connect');
            menu.classList.toggle('hidden');
            connect.classList.toggle('hidden');
        });
    </script>