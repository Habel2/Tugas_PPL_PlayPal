<?php

$message = null;
$type = null;

if (isset($_SESSION['error'])) {
    $message = $_SESSION['error'];
    $type = 'danger';
    unset($_SESSION['error']);
} elseif (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    $type = 'success';
    unset($_SESSION['success']);
}
include "background.php";
include "koneksi.php";
include "cek_login.php";
include "feedback.php";

$post_id = $_GET['post_id'];

$query_post = "SELECT posts.*, users.username, games.game_name 
               FROM posts
               JOIN users ON posts.user_id = users.user_id
               JOIN games ON posts.game_id = games.game_id
               WHERE posts.post_id = ?";
$stmt = $koneksi->prepare($query_post);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result_post = $stmt->get_result();

if ($result_post->num_rows == 0) {
    echo "Postingan tidak ditemukan.";
    exit();
}

$post = $result_post->fetch_assoc();

$query_comments = "SELECT comments.*, users.username 
                   FROM comments
                   JOIN users ON comments.user_id = users.user_id
                   WHERE comments.post_id = ?
                   ORDER BY comments.created_at ASC";
$stmt_comments = $koneksi->prepare($query_comments);
$stmt_comments->bind_param("i", $post_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="transparent_bg.css">
    <link rel="stylesheet" href="select_comment.css">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <title>Postingan - <?= htmlspecialchars($post['title']) ?></title>
</head>
<style>
    .card-text img {
        max-width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
    }
</style>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="./icon/PlayPal_Logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            PlayPal
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="posting.php">Post</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="postingan.php">Comment <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a>
            </form>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Detail Postingan -->
        <div class="card mb-4 transparent-bg rounded">
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($post['title']) ?></h3>
                <h6 class="card-subtitle mb-2 text-muted">
                    by <?= htmlspecialchars($post['username']) ?> in <?= htmlspecialchars($post['game_name']) ?>
                </h6>
                <div class="card-text" style="max-width: 100%; height:auto; display:block;">
                    <?= nl2br($post['text_content']) ?>
                </div>
                <p class="text-muted">Posted on <?= htmlspecialchars($post['created_at']) ?></p>
            </div>
        </div>

        <!-- Form untuk Menambahkan Komentar -->
        <div class="mb-4">
            <div class="p-4 transparent-bg rounded">
                <h4>Leave a Comment</h4>
                <form action="submit_comment.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <div id="editor" style="height: 200px;"></div>
                    <input type="hidden" name="comment_text" id="comment_text">
                    <button type="submit" class="btn btn-primary mt-3">Submit Comment</button>
                </form>
            </div>
        </div>

    <!-- Daftar Komentar -->
    <div>
        <div class="p-4 transparent-bg rounded">
            <h4>Comments</h4>
            <?php if ($result_comments->num_rows > 0): ?>
                <?php while ($comment = $result_comments->fetch_assoc()):
                    $username = htmlspecialchars($comment['username']);
                    $created_at = htmlspecialchars($comment['created_at']);
                    $like = (int)($comment['like'] ?? 0); 
                    $dislike = (int)($comment['dislike'] ?? 0);
                    $is_owner = $comment['user_id'] == $post['user_id'];
                    $is_comment_owner = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id'];
                ?>
                    <div class="media mb-3 comment-item <?= $is_comment_owner ? 'owner-comment' : ''; ?>" 
                        data-comment-id="<?= $comment['comment_id'] ?>">
                        <img 
                        src="<?= !empty($comment['user_icon']) && file_exists('./bg/' . $comment['user_icon']) 
                        ? './bg/' . htmlspecialchars($comment['user_icon']) 
                        : './icon/geek_emoji.png'; ?>"
                                alt="User Icon" 
                                class="mr-3 rounded-circle" 
                                style="width: 50px; height: 50px; object-fit: cover;"
                            >
                        <div class="media-body">
                            <h5 class="mt-0">
                                <?= $username ?>
                                <?php if ($is_owner): ?>
                                    <span style="color: yellow; font-weight: bold;">&lt;Owner&gt;</span>
                                <?php endif; ?>
                            </h5>
                            <p><?= nl2br($comment['comment_text']) ?></p>
                            <small class="text-muted">Posted on <?= $created_at ?></small>

                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary like-btn" 
                                    data-comment-id="<?= $comment['comment_id'] ?>" 
                                    data-type="like">
                                    👍 <span class="like-count"><?= $like ?></span>
                                </button>
                                <button class="btn btn-sm btn-outline-danger dislike-btn" 
                                    data-comment-id="<?= $comment['comment_id'] ?>" 
                                    data-type="dislike">
                                    👎 <span class="dislike-count"><?= $dislike ?></span>
                                </button>
                            </div>

                        </div>
                        <?php if ($is_comment_owner): ?>
                            <div class="trash-icon" style="display: none;">
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteComment(<?= $comment['comment_id'] ?>)">
                                    <img 
                                        src="./icon/trashcan.png" 
                                        alt="Hapus" 
                                        style="width: auto; height: 40px;"
                                    >
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Gaada yang komen awokawokawok 🫵</p>
            <?php endif; ?>
        </div>
    </div>    
        <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link']
                ]
            }
        });

        function validateForm() {
            var editorContent = quill.root.innerHTML.trim();
            if (editorContent === "<p><br></p>") {
                alert("Komentar tidak boleh kosong!");
                return false;
            }
            document.querySelector('#comment_text').value = editorContent;
            return true;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const handleLikeDislike = (button) => {
                const commentId = button.dataset.commentId;
                const type = button.dataset.type;

                fetch('comment_like_dislike.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ comment_id: commentId, type: type })
                })
                .then(response => response.text())
                .then(data => {
                    const [likeCount, dislikeCount] = data.split('|');

                    // Update counts in the DOM
                    const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                    if (commentElement) {
                        commentElement.querySelector('.like-count').textContent = likeCount;
                        commentElement.querySelector('.dislike-count').textContent = dislikeCount;
                    }
                })
                .catch(error => console.error("Error:", error));
            };

            document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
                button.addEventListener('click', function () {
                    handleLikeDislike(this);
                });
            });
        });


    document.addEventListener("DOMContentLoaded", function () {
        let selectedCommentId = null;

        document.querySelectorAll(".comment-item.owner-comment").forEach(commentItem => {
            commentItem.addEventListener("click", function () {
                const commentId = commentItem.dataset.commentId;

                if (selectedCommentId === commentId) {
                    commentItem.classList.remove("selected");
                    commentItem.querySelector(".trash-icon").style.display = "none";
                    selectedCommentId = null;
                    return;
                }

                if (selectedCommentId) {
                    const prevSelected = document.querySelector(`[data-comment-id="${selectedCommentId}"]`);
                    if (prevSelected) {
                        prevSelected.classList.remove("selected");
                        prevSelected.querySelector(".trash-icon").style.display = "none";
                    }
                }

                commentItem.classList.add("selected");
                commentItem.querySelector(".trash-icon").style.display = "block";
                selectedCommentId = commentId;
            });
        });
    });
    function deleteComment(commentId) {
        if (confirm("Apakah Anda yakin ingin menghapus komentar ini?")) {
            fetch('hapus_komentar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ comment_id: commentId })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    window.location.href = "feedback.php?type=success&message=Komentar berhasil dihapus.";
                    const commentElement = document.querySelector(`.comment-item[data-comment-id="${commentId}"]`);
                    if (commentElement) commentElement.remove();
                } else {
                    window.location.href = `feedback.php?type=danger&message=${encodeURIComponent(data.error || "Terjadi kesalahan saat menghapus komentar.")}`;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                window.location.href = "feedback.php?type=danger&message=" + encodeURIComponent("Gagal menghapus komentar. Silakan coba lagi.");
            });
        }
    }

    </script>   
</body>
</html>
    