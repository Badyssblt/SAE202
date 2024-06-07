<header class="h-20 flex items-center px-8">
    <menu class="flex flex-row gap-10 ">
        <li><a href="/">Accueil</a></li>
        <li><a href="/garden">Jardins</a></li>
        <?php
            if(isset($_SESSION['id'])){ ?>
            <li><a href="/user">Mon compte</a></li>
        <?php
            }else { ?>
            <li><a href="/user/signin.php">Se connecter</a></li>
            <?php
            }
        ?>
        <li><a href="/admin/garden/listGarden.php">Administrateur</a></li>
    </menu>
</header>
<body>