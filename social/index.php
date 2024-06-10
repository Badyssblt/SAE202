<?php

require('../conf/header.inc.php');

?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />


<?php

require('../conf/function.inc.php');

$sql = "SELECT 
    post.post_id, 
    post.post_content, 
    post.post_image,
    post.created_at,
    users.user_nom AS post_author, 
    users.user_picture,
    COUNT(DISTINCT likes.like_id) AS like_count,
    GROUP_CONCAT(DISTINCT likes.user_id) AS liked_user_ids,
    COUNT(DISTINCT commentary.commentary_id) AS commentary_count
FROM 
    post 
INNER JOIN 
    users ON post.user_id = users.user_id 
LEFT JOIN 
    likes ON likes.post_id = post.post_id
LEFT JOIN 
    commentary ON post.post_id = commentary.post_id
GROUP BY 
    post.post_id, 
    post.post_content, 
    post.created_at,
    users.user_nom,
    users.user_picture";


$posts = sql($sql);

if (isset($_SESSION) && isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
}




function formatTimeAgo($timestamp)
{
    $difference = time() - strtotime($timestamp);

    if ($difference < 60) {
        return 'Il y a quelques secondes';
    } elseif ($difference < 3600) {
        return 'Il y a ' . floor($difference / 60) . ' minutes';
    } elseif ($difference < 86400) {
        return 'Il y a ' . floor($difference / 3600) . ' heures';
    } elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days == 1 ? 'Il y a 1 jour' : 'Il y a ' . $days . ' jours';
    } else {
        return date('d/m/Y', strtotime($timestamp));
    }
}


?>

<div>
    <button onclick="displayCreateForm()">Créer une publication</button>
</div>
<div class="flex flex-col items-center gap-16">
    <?php

    foreach ($posts as $post) {
        if (isset($_SESSION) && isset($_SESSION['id'])) {
            $likedArray = explode(',', $post['liked_user_ids']);
            $likedArray = array_filter($likedArray);
            $isLiked = false;
            for ($i = 0; $i < count($likedArray); $i++) {
                if ($likedArray[$i] == $_SESSION['id']) {
                    $isLiked = true;
                }
            }
        }

    ?>
        <div class="flex flex-col w-1/2">
            <div class="flex flex-row gap-2">
                <img src="/assets/images/uploads/users/<?= $post['user_picture'] ?>" alt="" class="w-12 rounded-full">
                <div>
                    <h3 class="font-bold"><?= $post['post_author'] ?></h3>
                    <p><?= formatTimeAgo($post['created_at']) ?></p>
                </div>
            </div>
            <div class="mt-4">
                <img src="../assets/images/uploads/posts/<?= $post['post_image'] ?>" alt="">
            </div>
            <div class="mt-4">
                <?= $post['post_content'] ?>
            </div>
            <div class="mt-4 flex flex-row justify-between w-64">
                <div class="flex flex-row">
                    <?php
                    // Icon coeur du like
                    ?>
                    <?php
                    if ($isLiked) { ?>
                        <svg onclick="toggleLike(<?= $post['post_id'] ?>, this)" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    <?php
                    } else { ?>
                        <svg onclick="toggleLike(<?= $post['post_id'] ?>, this)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    <?php
                    }
                    ?>

                    <h4 class="like-count" data-post-like-id="<?= $post['post_id'] ?>"><?= $post['like_count'] ?></h4>
                </div>
                <div class="flex flex-row">
                    <?php
                    // Icon du commentaire
                    ?>
                    <svg onclick="displayCommentaries(<?= $post['post_id'] ?>)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>

                    <h4><?= $post['commentary_count'] ?></h4>
                </div>
                <div></div>
            </div>
        </div>
    <?php
    }
    ?>
</div>


<?php
// Lister les commentaires
?>
<div id="listing_com" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">

    <div class="flex justify-center relative w-full">

        <div class="bg-white w-1/4 relative">
            <button onclick="closeCommentary(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <form class="mx-4 mt-4" onsubmit="publishComment(event)">
                <input type="hidden" name="postId" id="postIdInput">
                <input type="text" name="message" id="messageCommentary" placeholder="Entrer votre commentaire" class="w-full py-4 pl-4 bg-slate-200 rounded-md">
            </form>

            <div class="flex flex-col gap-8 bg-white p-8 rounded-md w-full overflow-auto h-96" id="listing-com__wrapper">
                <div class="flex flex-col relative">
                    <button onclick="displayDeleteVerification()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                        </svg>

                    </button>
                    <div class="flex flex-row gap-2">
                        <img src="/assets/images/uploads/users/" alt="" class="w-12 rounded-full">
                        <div>
                            <h3 class="font-bold"><?= $post['post_author'] ?></h3>
                            <p><?= formatTimeAgo($post['created_at']) ?></p>
                        </div>
                    </div>
                    <div class="w-full mt-2">
                        <p></p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?php
// Fin Lister les commentaires
?>

<?php
// Supprimer un commentaire
?>

<div id="delete_com" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <div class="flex justify-center flex-col p-6 rounded-md relative bg-white w-fit">
        <button onclick="closeDeleteCommentary(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <h4 class="font-bold">Voulez-vous supprimer ce commentaire ?</h4>
        <div class="flex flex-row justify-center gap-4 mt-4">
            <button class="bg-red-800 text-white py-2 px-4 rounded-sm" onclick="deleteComment()">Supprimer</button>
            <button class="border text-black py-2 px-4 rounded-sm" onclick="closeDeleteCommentary()">Annuler</button>
        </div>
    </div>
</div>


<?php
// Fin supprimer un commentaire
?>


<?php
// Créer une publication
?>
<div id="createPost" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-600 w-screen h-full flex justify-center items-center" style="background-color: rgba(0, 0, 0, 0.3);">
    <form onsubmit="createPost(event);" class="flex justify-center flex-col bg-white py-8 px-10 rounded-sm relative" id="editForm" action="#" method="POST" enctype="multipart/form-data">
        <p class="font-bold text-xl">Créer une publication</p>
        <input type="file" name="image" id="imagePost">
        <input type="hidden" name="postContent" id="postContent">
        <button type="button" onclick="displayCreateForm()" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col mt-2">
        <div id="editor">
            <p>Hello World!</p>
            <p>Some initial <strong>bold</strong> text</p>
            <p><br /></p>
        </div>

        </div>
        <button type="submit" class="bg-lime-800 text-white py-2 px-4 rounded-sm mt-4">Définir la plantation</button>
    </form>
</div>
<?php
// Fin créer une publication
?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    //#region Commentaires

    let currentPost = null;

    let userID = <?= json_encode($userID); ?>;


    function displayCreateForm()
    {
        const form = document.getElementById("createPost");
        form.classList.toggle("hidden");
    }

    async function createPost(event) {
        event.preventDefault();

        const quillContent = quill.root.innerHTML;
        document.getElementById('postContent').value = quillContent;

        
        const formData = new FormData();
        formData.append('postContent', quillContent);

        const imageFile = document.getElementById('imagePost').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/social/create/createPost.php",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.reload();
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    

      function getImagesFromEditor() {
    const editorContent = quill.root.innerHTML;
    const parser = new DOMParser();
    const doc = parser.parseFromString(editorContent, 'text/html');
    const images = doc.querySelectorAll('img');
    const imageUrls = Array.from(images).map(img => img.src);
    return imageUrls;
  }


    function displayCommentaries(id) {
        const commentaryForm = document.getElementById("listing_com");
        const postIdHidden = document.getElementById("postIdInput").value = id;
        commentaryForm.classList.remove("hidden");
        currentPost = id;
        fetchCommentary(id);
    }

    function closeCommentary() {
        const commentaryForm = document.getElementById("listing_com");
        commentaryForm.classList.add("hidden");
    }

    function displayDeleteVerification(element) {
        let commentaryId = (element.parentNode).getAttribute("data-commentaryId");
        const commentaryForm = document.getElementById("delete_com");
        commentaryForm.dataset.currentCommentary = commentaryId;
        commentaryForm.classList.remove("hidden");
    }

    function closeDeleteCommentary() {
        const commentaryForm = document.getElementById("delete_com");
        commentaryForm.classList.add("hidden");
    }


    async function fetchCommentary(id) {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/social/single.php?id=" + id,
                dataType: "JSON",
                success: function(response) {
                    hydrateCommentaryList(response);
                }
            });
        } catch (error) {

        }
    }

    function hydrateCommentaryList(data) {
        const wrapper = $("#listing-com__wrapper");

        wrapper.empty();

        if (data.length <= 0) {
            const div = `
            <p>Aucun commentaire</p>
            `;
            wrapper.append(div);
            return;
        }

        data.forEach((element) => {
            const isCurrentUser = userID == element['commentary_author_id'];
            const deleteButton = isCurrentUser ? `<button onclick="displayDeleteVerification(this)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
</svg>
</button>` : '';
            console.log(element['commentary_author']);
            const div =
                `
            <div class="flex flex-col relative" data-commentaryId="${element['commentary_id']}">
            ${deleteButton}
            </button>
            <div class="flex flex-row gap-2">
                <img src="/assets/images/uploads/users/${element['commentary_author_picture']}" alt="" class="w-12 rounded-full">
                <div>
                    <h3 class="font-bold">${element['commentary_author']}</h3>
                    <p>${formatTimeAgo(element['created_at'])}</p>
                </div>
            </div>
            <div class="w-full mt-2">
                <p>${element['commentary_content']}</p>
            </div>
        </div>
            `;
            wrapper.append(div);
        })


    }

    async function publishComment(event) {

        event.preventDefault();
        const postId = document.getElementById("postIdInput").value;
        const commentary = document.getElementById("messageCommentary").value;
        try {
            const res = await $.ajax({
                type: "POST",
                url: "../api/social/create/createCommentary.php",
                data: {
                    commentary,
                    postId
                },
                dataType: "JSON",
                success: function(response) {
                    fetchCommentary(postId);
                    document.getElementById("messageCommentary").value = "";
                }
            });
        } catch (error) {

        }
    }

    async function deleteComment() {
        const commentaryId = document.getElementById("delete_com").dataset.currentCommentary;
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/social/delete/index.php?id=" + commentaryId,
                dataType: "JSON",
                success: function(response) {
                    fetchCommentary(currentPost);
                    closeDeleteCommentary();
                }
            });
        } catch (error) {

        }
    }


    //#endregion

    function formatTimeAgo(timestamp) {
        const difference = Date.now() - new Date(timestamp).getTime();

        const seconds = Math.floor(difference / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (seconds < 60) {
            return 'Il y a quelques secondes';
        } else if (minutes < 60) {
            return 'Il y a ' + minutes + ' minutes';
        } else if (hours < 24) {
            return 'Il y a ' + hours + ' heures';
        } else if (days < 7) {
            return days === 1 ? 'Il y a 1 jour' : 'Il y a ' + days + ' jours';
        } else {
            const date = new Date(timestamp);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
            const year = date.getFullYear();
            return day + '/' + month + '/' + year;
        }
    }



    //#region Likes
    async function toggleLike(postId, element) {
        try {
            const isLiked = element.getAttribute('fill') === 'red';

            if (isLiked) {
                await like(postId, element, 'unlike');
            } else {
                await like(postId, element, 'like');
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function like(postId, element, type) {
        try {
            if (type === "like") {
                const res = await $.ajax({
                    type: "POST",
                    url: "../api/likes/create/like.php",
                    data: {
                        postId,
                        type
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response === true) {
                            element.setAttribute('fill', 'red');
                            element.setAttribute('stroke', 'red');
                            fetchLikes(postId);
                        }
                    }
                });
            } else if (type === "unlike") {
                const res = await $.ajax({
                    type: "POST",
                    url: "../api/likes/delete/unlike.php",
                    data: {
                        postId
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response === true) {
                            element.setAttribute('fill', 'none');
                            element.setAttribute('stroke', 'currentColor');
                            fetchLikes(postId);
                        }
                    }
                })

            }
        } catch (error) {
            console.log(error);
        }
    }

    async function fetchLikes(postId) {
        try {
            const res = await $.ajax({
                type: "GET",
                url: "../api/likes/index.php?id=" + postId,
                dataType: "JSON",
                success: function(response) {
                    displayLikes(response, postId);
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    function displayLikes(data, postId) {
        const likeCountElements = document.querySelectorAll('[data-post-like-id="' + postId + '"]');
        likeCountElements.forEach(function(element) {
            element.innerText = data[0].like_count;
        });
    }

    //#endregion


    const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline']
                                ],
            }
        });

</script>