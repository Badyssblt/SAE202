<?php
$title = "Réseau social";
require('../../conf/header.inc.php');
require("../../conf/function.inc.php");

$posts = findAll("post");
$commentaries = findAll("commentary");



?>

<?php

if(isset($_SESSION['success']['message'])){ ?>
<div role="alert" class="rounded-xl border border-gray-100 bg-white p-4 mx-10" id="alertBox">
  <div class="flex items-start gap-4">
    <span class="text-green-600" >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="h-6 w-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
    </span>

    <div class="flex-1">
      <strong class="block font-medium text-gray-900"> Opération réussi</strong>

      <p class="mt-1 text-sm text-gray-700"><?= $_SESSION['success']['message'] ?></p>
    </div>

    <button class="text-gray-500 transition hover:text-gray-600" onclick="closeAlert()">
      <span class="sr-only">Dismiss popup</span>

      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="h-6 w-6"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</div>

<?php
unset($_SESSION['success']['message']);
}
?>

<div class="flex flex-col px-10">
<h2 class="font-bold text-xl">Posts publiés</h2>
<div class="flex flex-col gap-4 md:flex-wrap md:flex-row">
    <?php foreach($posts as $post) : ?>
        <div class="flex flex-col px-10 ">
            <div class="w-full h-64 rounded-xl overflow-hidden">
                <img src="../../assets/images/uploads/posts/<?= $post['post_image'] ?>" alt="" class="w-full h-full object-cover">
            </div>
            <p><?= $post['post_content'] ?></p>
            <div class="flex flex-row">
                <a href="./process/delete.proc.php?id=<?= $post['post_id'] ?>"  class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
                <a href="./process/edit.form.php?id=<?= $post['post_id'] ?>" class="border text-black w-full py-2 px-4 rounded-sm flex justify-center mt-2">Modifier</a>
            </div>
        </div>

    <?php endforeach; ?>
</div>

</div>


<div class="flex flex-col px-10 mt-8">
<h2 class="font-bold text-xl">Commentaires publiés</h2>
<div class="flex flex-col gap-4 md:flex-wrap md:flex-row">
    <?php 
    if(count($commentaries) == 0){
      echo "<p>Aucun commentaire...</p>";
    }else {
      foreach($commentaries as $commentary) : ?>
        <div class="flex flex-col px-10 ">
            <p><?= $commentary['commentary_content'] ?></p>
            <div class="flex flex-row">
                <a href="./process/deleteCom.proc.php?id=<?= $commentary['commentary_id'] ?>"  class="bg-red-800 text-white py-2 px-4 rounded-sm flex justify-center mt-4">Supprimer</a>
            </div>
        </div>
    <?php endforeach; ?>
   <?php } ?>
    
</div>

</div>

<script>
    function closeAlert() {
        const alertBox = document.getElementById("alertBox");
        alertBox.classList.add("hidden");
    }
</script>