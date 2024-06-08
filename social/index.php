<?php

require('../conf/header.inc.php');
require('../conf/function.inc.php');

$sql = "SELECT 
    post.post_id, 
    post.post_nom, 
    post.post_content, 
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
    post.post_nom, 
    post.post_content, 
    post.created_at,
    users.user_nom,
    users.user_picture";


$posts = sql($sql);




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
                <div class="flex flex-row">
                    <?php
                    // Icon du partage
                    ?>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                    </svg>


                    <button>Partager</button>
                </div>
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
    <div class="flex justify-center relative w-1/4">
        <button onclick="closeCommentary(event)" class="absolute top-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col gap-8 bg-white p-8 rounded-md w-full" id="listing-com__wrapper">
            <div class="flex flex-col">
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
<?php
// Fin Lister les commentaires
?>


<script>
    //#region Commentaires

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

    function displayCommentaries(id) {
        const commentaryForm = document.getElementById("listing_com");
        commentaryForm.classList.remove("hidden");
        fetchCommentary(id);
    }

    function closeCommentary() {
        const commentaryForm = document.getElementById("listing_com");
        commentaryForm.classList.add("hidden");
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
            const div =
                `
            <div class="flex flex-col">
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
</script>