<?php
$activePage = 'contact';

require("../conf/header.inc.php");

?>

<h2 class="font-bold text-2xl text-center underline md:py-2 md:text-3xl md:rounded-xl md:text-center md:no-underline" style="color: #3E572D">Contact</h2>

<p class="font-bold text-xl text-center py-8">N'hésitez pas à nous envoyer un message</p>
<div class="flex justify-center mb-8">
<form action="../process/mail.proc.php" method="POST" style="border: 2px solid #475C37;" class="w-11/12 px-8 py-6 rounded-lg md:w-2/5">
    <div class="flex flex-col">
        <label for="subject" class="font-bold">Sujet <span class="text-red-600">*</span></label>
        <input type="text" name="subject" id="subject" placeholder="Email" class="border rounded-md pl-4 py-2">
    </div>
    <div class="flex flex-col mt-4">
        <label for="email" class="font-bold">Email<span class="text-red-600">*</span></label>
        <input type="email" name="email" id="email" placeholder="Email" class="border rounded-md pl-4 py-2">
    </div>
    <div class="flex flex-col mt-4">
        <label for="content" class="font-bold">Entrer votre message</label>
        <textarea name="message" id="content" placeholder="Contenu du mail" class="border rounded-md pl-4 py-2"></textarea>
    </div>
    <div class="flex justify-center">
        <button type="submit" class="bg-main px-4 py-2 rounded-full font-bold text-white mt-4">Envoyer un mail</button>
    </div>
</form>
</div>

<?php
require('../conf/footer.inc.php');

?>