<header class="px-10 mb-4">
    <div class="flex flex-row justify-between mt-8">
        <h1><a href="/"><img src="/assets/images/icon.svg" alt=""></a></h1>
        <div class="flex items-center flex-col md:hidden">
            <?php
                if(isset($_SESSION['id'])){ ?>
            <ul class="list-none">
                <li class="<?php echo ($activePage === 'Account') ? 'underline' : ''; ?>"><a href="/user" class="bg-main rounded-full px-6 py-2 text-white">Mon profil</a></li>
                <li class="mt-6"><a class="font-bold shadow-md px-4 py-2 rounded-lg" href="/social/index.php">Planta.net</a></li>
            </ul>
            <?php
                } else { ?>
            <div class="border-r-2 pr-2 flex flex-col gap-2">
                <ul class="list-none text-center">
                    <li><a href="/user/signup.php" class="font-bold <?php echo ($activePage === 'register') ? 'underline' : ''; ?>">S'inscrire</a></li>
                    <li><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full flex">Se connecter</a></li>
                </ul>
            </div>
            <?php
                }
            ?>
        </div>
        <div class="hidden md:flex flex-row gap-8">
            <?php
            if (isset($_SESSION['id'])) { ?>
            <div class="border-r-2 pr-2 flex flex-row gap-4">
                <ul class="list-none">
                    <li><a class="font-bold" href="/user/logout.php">Se déconnecter</a></li>
                    <li class="<?php echo ($activePage === 'Account') ? 'underline' : ''; ?>"><a href="/user" class="bg-main rounded-full px-6 py-2 text-white">Mon profil</a></li>
                </ul>
            </div>
            <?php
            } else { ?>
            <div class="border-r-2 pr-2 flex flex-row gap-2">
                <ul class="list-none">
                    <li class="md:text-center"><a href="/user/signup.php" class="font-bold ">S'inscrire</a></li>
                    <li><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full flex <?php echo ($activePage === 'login') ? 'underline' : ''; ?>">Se connecter</a></li>
                </ul>
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
    <nav class="fixed bottom-0 bg-white w-full py-4 border-t-2 left-0 px-10 z-50 md:hidden">
            <ul class="flex flex-row gap-2 justify-between">
                <li><a href="/garden" class="<?php echo ($activePage === 'garden') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/plants.svg" alt=""></a></li>
                <li><a href="/gestion" class="<?php echo ($activePage === 'admin') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/admin.png" alt=""></a></li>
                <li><a href="/contact/index.php" class="<?php echo ($activePage === 'contact') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/contact.png" alt=""></a></li>
                <li><a href="/plantes/index.php" class="<?php echo ($activePage === 'plantes') ? 'underline' : ''; ?> whitespace-nowrap"><img src="/assets/images/plante.svg" alt=""></a></li>
            </ul>
    </nav>

    <div id="menu" class="mt-6 mb-4 hidden md:flex flex-col md:flex-row items-center justify-center gap-20">
        <div class="flex flex-col md:flex-row gap-10">

            <ul class="flex flex-row gap-4">
                <li>
            <a href="/garden" class="<?php echo ($activePage === 'garden') ? 'underline underline-offset-4' : ''; ?> whitespace-nowrap relative group">
                Nos jardins
                <span class="absolute left-0 bottom-0 w-full h-0.5 bg-current transform scale-x-0 transition-transform duration-300 ease-in-out origin-left group-hover:scale-x-100"></span>
            </a>
        </li>
                <li><a href="/plantes/index.php" class="<?php echo ($activePage === 'plantes') ? 'underline underline-offset-4' : ''; ?> whitespace-nowrap relative group">
                    Nos plantes
                    <span class="absolute left-0 bottom-0 w-full h-0.5 bg-current transform scale-x-0 transition-transform duration-300 ease-in-out origin-left group-hover:scale-x-100"></span>
                </a></li>
                <li><a href="/social/index.php" class="<?php echo ($activePage === 'social') ? 'underline underline-offset-4' : ''; ?> whitespace-nowrap relative group">
                    Réseau social
                    <span class="absolute left-0 bottom-0 w-full h-0.5 bg-current transform scale-x-0 transition-transform duration-300 ease-in-out origin-left group-hover:scale-x-100"></span>
                </a></li>
                <li><a href="/contact/index.php" class="<?php echo ($activePage === 'contact') ? 'underline underline-offset-4' : ''; ?> whitespace-nowrap relative group">
                    Contact
                    <span class="absolute left-0 bottom-0 w-full h-0.5 bg-current transform scale-x-0 transition-transform duration-300 ease-in-out origin-left group-hover:scale-x-100"></span>
                </a></li>
                <li><a href="/gestion" class="<?php echo ($activePage === 'admin') ? 'underline underline-offset-4' : ''; ?> whitespace-nowrap relative group">
                    Administrateur
                    <span class="absolute left-0 bottom-0 w-full h-0.5 bg-current transform scale-x-0 transition-transform duration-300 ease-in-out origin-left group-hover:scale-x-100"></span>
                </a></li>
            </ul>
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
                    <ul class="list-none">
                        <li><a href="/user" class="<?php echo ($activePage === 'Account') ? 'underline' : ''; ?>">Mon compte</a></li>
                    </ul>
                </div>
                <?php
                } else { ?>
                <div class="border-r-2 pr-2 flex md:flex-row gap-2 flex-col">
                    <ul class="list-none">
                        <li><a href="/user/signup.php" class="font-bold <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>">S'inscrire</a></li>
                        <li><a href="/user/signin.php" class="bg-main px-6 text-white py-2 rounded-full <?php echo ($activePage === 'Account') ? 'underline' : ''; ?>">Se connecter</a></li>
                    </ul>
                </div>
                <?php
                }
                ?>
                <div>
                    <h2>Langues</h2>
                </div>
            </div>
        </div>
        </div>
</header>
