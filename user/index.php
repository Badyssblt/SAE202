<?php
$title = "Mon compte";


require('../conf/header.inc.php');
require('../conf/function.inc.php');



?>

<main class="flex flex-col md:flex-row">

    <?php
    require('./components/sidebar.php');
    ?>

    <div id="dashboard" class="ml-8 w-full">

    </div>

</main>

<script src="../assets/js/dashboard.js"></script>

<script>
    switchMenu("parameters", document.getElementById("parametersButton"));
</script>