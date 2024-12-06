<?php
session_start();
$user_id = $_SESSION['user_id'];
$user_folder = "./uploads/backgrounds/user_$user_id/";
$uploaded_folder = $user_folder . "uploaded_background/";
$current_folder = $user_folder . "current_background/";

if (!is_dir($uploaded_folder)) mkdir($uploaded_folder, 0755, true);
if (!is_dir($current_folder)) mkdir($current_folder, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['background'])) {
    $file = $_FILES['background'];
    $file_type = mime_content_type($file['tmp_name']);
    $valid_image_types = ['image/jpeg', 'image/png', 'image/gif'];
    $valid_video_types = ['video/mp4', 'video/webm', 'video/ogg'];

    $ext = '';
    if (in_array($file_type, $valid_image_types)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    } elseif (in_array($file_type, $valid_video_types)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    } else {
        echo "Invalid file type.";
        exit;
    }

    $target_file = $uploaded_folder . uniqid() . '.' . $ext;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        echo "Upload successful!";
    } else {
        echo "Failed to upload.";
    }
}
?>
