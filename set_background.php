<?php
session_start();
$user_id = $_SESSION['user_id'];
$user_folder = "./uploads/backgrounds/user_$user_id/";
$current_folder = $user_folder . "current_background/";
$uploaded_folder = $user_folder . "uploaded_background/";
$default_folder = "./bg/";
$active_background_file = $current_folder . "active_background.txt";

if (!is_dir($current_folder)) {
    mkdir($current_folder, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    $file = $_POST['file'];
    $file_path = realpath($file);

    if (strpos($file_path, realpath($uploaded_folder)) === 0 && file_exists($file_path)) {
        $source_folder = 'uploaded_background';
    } elseif (strpos($file_path, realpath($default_folder)) === 0 && file_exists($file_path)) {
        $source_folder = 'default_background';
    } else {
        echo "File tidak valid atau tidak berada dalam folder yang diizinkan.";
        exit;
    }

    $existing_backgrounds = glob($current_folder . "*");
    foreach ($existing_backgrounds as $existing_file) {
        unlink($existing_file);
    }

    $target_file_path = $current_folder . basename($file);
    if (copy($file_path, $target_file_path)) {
        file_put_contents($active_background_file, basename($file));

        echo "Background berhasil diatur!";
    } else {
        echo "Gagal menyalin background baru.";
    }
} else {
    echo "Tidak ada file yang dipilih.";
}
?>
