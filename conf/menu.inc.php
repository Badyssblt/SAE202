<header class="px-10">
    <div class="flex flex-row justify-between mt-8">
        <h1><a href="/"><img src="/assets/images/icon.png" alt=""></a></h1>
        <div class="flex items-center md:hidden">
            <button id="menu-button" class="focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <div class="hidden md:flex flex-row gap-8">
            <?php
            if (isset($_SESSION['id'])) { ?>
                <div class="border-r-2 pr-2">
                    <li class="list-none"><a href="/user">Mon compte</a></li>
                </div>
            <?php
            } else { ?>
                <div class="border-r-2 pr-2 flex flex-row gap-2">
                    <li class="list-none"><a href="/user/signup.php" class="font-bold">S'inscrire</a></li>
                    <li class="list-none"><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full">Se connecter</a></li>
                </div>
            <?php
            }
            ?>
            <div>
                <h2>Langues</h2>
            </div>
        </div>
    </div>
    <menu id="menu" class="mt-6 mb-4 hidden md:flex flex-col md:flex-row items-center justify-center gap-20">
        <div class="flex flex-col md:flex-row gap-10">
            <li class="flex flex-row gap-2"><img src="/assets/images/herbe.png" alt=""><a href="/">Accueil</a></li>
            <li class="flex flex-row gap-2"><img src="/assets/images/plants.png" alt=""><a href="/garden">Nos jardins</a></li>
            <li><a href="/social">Réseau-social</a></li>
            <li class="flex flex-row gap-2"><img src="/assets/images/admin.png" alt=""><a href="/admin/garden/listGarden.php">Administrateur</a></li>
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
                        <li class="list-none"><a href="/user">Mon compte</a></li>
                    </div>
                <?php
                } else { ?>
                    <div class="border-r-2 pr-2 flex md:flex-row gap-2 flex-col">
                        <li class="list-none"><a href="/user/signup.php" class="font-bold">S'inscrire</a></li>
                        <li class="list-none"><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full">Se connecter</a></li>
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