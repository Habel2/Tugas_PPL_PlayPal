<?php
session_start();
$user_id = $_SESSION['user_id'];
$user_folder = "./uploads/backgrounds/user_$user_id/";
$active_background_file = $user_folder . "active_background.txt";

if (isset($_GET['file'])) {
    $file = $_GET['file'];
    if (strpos(realpath($file), realpath($user_folder)) === 0 && file_exists($file)) {
        unlink($file);
        if ($file === file_get_contents($active_background_file)) {
            file_put_contents($active_background_file, '');
        }
        header("Location: personal_background.php");
        exit();
    }
} else {
    echo "File tidak ditemukan.";
}
