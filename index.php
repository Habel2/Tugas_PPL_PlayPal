<?php
include "koneksi.php";
include "background.php";
include "cek_login.php";

$user_id = $_SESSION['user_id'];
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="./show_img.css">
    <link rel="stylesheet" href="transparent_bg.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="hide_content.css">

    <title>PlayPal</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="./icon/PlayPal_Logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            PlayPal
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="posting.php">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="personal_background.php">Background</a>
                </li>
                <li class="nav-item dropdown">
                    <?php
                    $selected_game_id = isset($_GET['game_id']) ? $_GET['game_id'] : null;
                    $selected_game_name = 'Game';
                    if ($selected_game_id) {
                        $query = "SELECT game_name FROM games WHERE game_id = ?";
                        $stmt = $koneksi->prepare($query);
                        $stmt->bind_param("i", $selected_game_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $selected_game_name = htmlspecialchars($row['game_name']);
                        }
                    } else {
                        $selected_game_name = 'Game';
                    }
                    ?>
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        <?php echo $selected_game_name; ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="index.php">Game</a>

                        <?php
                        $query = "SELECT game_id, game_name FROM games";
                        $result = $koneksi->query($query);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $game_id = htmlspecialchars($row['game_id']);
                                $game_name = htmlspecialchars($row['game_name']);
                                echo "<a class='dropdown-item' href='index.php?game_id={$game_id}'>{$game_name}</a>";
                            }
                        } else {
                            echo "<a class='dropdown-item' href='#'>No games available</a>";
                        }
                        ?>
                    </div>
                </li>



            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1 class="my-4" style="color:aliceblue">Recent Posts</h1>
        <div class="row">
        <?php
            $game_id_filter = isset($_GET['game_id']) ? (int)$_GET['game_id'] : null;

            $query = "
                SELECT posts.*, users.username, games.game_name
                FROM posts
                JOIN users ON posts.user_id = users.user_id
                JOIN games ON posts.game_id = games.game_id
            ";
            if ($game_id_filter) {
                $query .= " WHERE posts.game_id = {$game_id_filter}";
            }
            $query .= " ORDER BY posts.created_at DESC";

            $result = $koneksi->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post_id = htmlspecialchars($row['post_id']);
                    $title = htmlspecialchars($row['title']);
                    $username = htmlspecialchars($row['username']);
                    $game_name = htmlspecialchars($row['game_name']);
                    $text_content = $row['text_content'];
                    $created_at = htmlspecialchars($row['created_at']);
                    $likes = (int)($row['likes'] ?? 0);
                    $dislikes = (int)($row['dislikes'] ?? 0);
                    $comment_count = $row['comment_count'] ?? 0
            ?>
                    <div class="my-3" style="margin-right:20px;">
                        <div class="card transparent-bg">
                            <div class="card-body position-relative">
                                <div class="dropdown position-absolute" style="top: 10px; right: 10px;">
                                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton<?= $post_id ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $post_id ?>">
                                        <?php if ($_SESSION['user_id'] == $row['user_id']) { ?>
                                            <a class="dropdown-item" href="edit.php?id=<?= $post_id ?>">Edit</a>
                                            <a class="dropdown-item" href="hapus.php?id=<?= $post_id ?>">Hapus</a>
                                        <?php } ?>
                                        <a class="dropdown-item" href="#">Report</a>
                                    </div>
                                </div>
    
                                <h5 class="card-title"><?= $title ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">by <?= $username ?> in <?= $game_name ?></h6>
    
                                <div class="hide-content-wrapper">
                                    <input type="checkbox" id="toggle-<?= $post_id ?>" class="toggle-checkbox">
                                    <div class="hide-content">
                                        <p class="card-text"><?= nl2br($text_content) ?></p>
                                    </div>
                                    <label for="toggle-<?= $post_id ?>" class="toggle-label">
                                        <span class="arrow">▼</span> <span class="text">Read More</span>
                                    </label>
                                </div>
    
                                <p class="text-muted">Posted on <?= $created_at ?></p>
                                <div class="d-flex justify-content-between">
                                    <div class="likedislikebtn">
                                        <button class="btn btn-sm btn-outline-primary like-btn" data-post-id="<?= $post_id ?>">
                                            👍 <span class="like-count"><?= $likes ?></span>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger dislike-btn" data-post-id="<?= $post_id ?>">
                                            👎 <span class="dislike-count"><?= $dislikes ?></span>
                                        </button>
                                    </div>
                                    <button class="btn btn-sm btn-outline-info" onclick="window.location.href='postingan.php?post_id=<?= $post_id ?>'">
                                        💬 <span class="comment-count"><?= $comment_count ?></span>
                                    </button>
                                </div>
    
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "
                <div style='display: flex; justify-content: center; align-items: center; height: 70vh; width: 100%;'>
                    <p style='font-size: 2rem; color: white;'>Belum ada yang ngeposting 😔.</p>
                </div>
                ";
            }
                      
            ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.like-btn').click(function() {
                var post_id = $(this).data('post-id');
                $.post('like_dislike.php', { post_id: post_id, type: 'like' }, function(response) {
                    console.log(response);
                    location.reload();
                }).fail(function() {
                    alert('Failed to process like.');
                });
            });

            $('.dislike-btn').click(function() {
                var post_id = $(this).data('post-id');
                $.post('like_dislike.php', { post_id: post_id, type: 'dislike' }, function(response) {
                    console.log(response);
                    location.reload();
                }).fail(function() {
                    alert('Failed to process dislike.');
                });
            });
        });

    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>
