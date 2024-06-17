<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<?php

$title = "Modifier un post";
require('../../../conf/header.inc.php');
require('../../../conf/function.inc.php');

$sql = "SELECT post.post_content, post.post_id, post.created_at, users.user_id, users.user_id FROM post INNER JOIN users ON post.user_id = users.user_id WHERE post_id = :id";

$db = getConnection();

$query = $db->prepare($sql);
$query->bindParam(":id", $_GET['id']);
$query->execute();

$post = $query->fetch();

$users = findAll("users");

?>

<form action="./edit.valid.php" enctype="multipart/form-data" method="POST" class="px-10 py-14" onsubmit="editPost(event)">
        <?php
            if(isset($post['post_image'])){ ?>
            <div class="flex flex-col gap-2">
                <img src="../../../assets/images/uploads/posts/<?= $post['post_image'] ?>" alt="">
                <input type="file" name="image" id="image">
            </div>
               
            <?php
            }else { ?>
                <input type="file" name="image" id="image">
            <?php
            }
        ?>
        <input type="hidden" name="postContent" id="postContent">
        <div id="editor">
            <?= $post['post_content'] ?>
        </div>
        <div class="flex flex-col mt-4">
        <label for="user_id" class="font-bold">Entrer l'utilisateur</label>
        <select name="user_id" id="user_id" class="border px-4 py-2">
            <?php foreach($users as $user): 
                if($user['user_id'] == $post['user_id']){ ?>
                    <option value="<?= $user['user_id'] ?>" selected><?= $user['user_nom'] ?></option>
                <?php }  ?>
                <option value="<?= $user['user_id'] ?>"><?= $user['user_nom'] ?></option>
            <?php endforeach; ?>
        </select>
        </div>
        
        <div class="bg-main text-white px-4 py-2 rounded-lg flex justify-center mt-4">
            <button type="submit">Modifier le post</button>
        </div>
</form>



<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline']
                                ],
            }
        });


        async function editPost(event) {
        event.preventDefault();

        const quillContent = quill.root.innerHTML;
        document.getElementById('postContent').value = quillContent;

        const userID = document.getElementById("user_id").value;

        const formData = new FormData();
        formData.append('content', quillContent);
        formData.append("userID", userID);
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        const url = new URLSearchParams(window.location.search);
        const id = url.get('id');
        formData.append('id', id);

        try {
            const res = await $.ajax({
                type: "POST",
                url: "./edit.valid.php",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.href = "/gestion/social/listSocial.php";
                }, error: function(jqXHR){
                    console.log(jqXHR);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

</script>