<?php
session_start();
$user_id = $_SESSION['user_id'];

$user_folder = "./uploads/backgrounds/user_$user_id/current_background/";
$active_background_file = $user_folder . "active_background.txt"; 
$default_background = "./bg/background.webm";

$active_background = file_exists($active_background_file) ? file_get_contents($active_background_file) : $default_background;

$full_path = $user_folder . basename($active_background);

if (!file_exists($full_path)) {
    $active_background = $default_background;
    $full_path = $default_background;
}

?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="background.css">
  </head>
  <body>
      <div class="video-background">
          <?php if (preg_match('/\.(mp4|webm)$/i', $full_path)): ?>
              <video autoplay loop muted playsinline>
                  <source src="<?= $full_path ?>" type="video/<?= pathinfo($full_path, PATHINFO_EXTENSION) ?>">
                  Your browser does not support the video tag.
              </video>
          <?php else: ?>
              <img src="<?= $full_path ?>" alt="Background">
          <?php endif; ?>
      </div>
  </body>
</html>
