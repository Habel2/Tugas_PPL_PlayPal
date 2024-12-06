<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];

    // Ekstrak gambar dari komentar
    preg_match_all('/<img src="data:image\/(.*?);base64,(.*?)"/', $comment_text, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $extension = $match[1];
        $base64_data = $match[2];
        $allowed_extensions = ['png', 'jpeg', 'jpg', 'gif'];

        if (!in_array($extension, $allowed_extensions)) {
            continue;
        }

        $image_name = "comment_" . time() . "_" . uniqid() . "." . $extension;
        $image_path = __DIR__ . "/img/comments/" . $image_name;
        file_put_contents($image_path, base64_decode($base64_data));
        $comment_text = str_replace($match[0], '<img src="img/comments/' . $image_name . '"', $comment_text);
    }

    // Simpan komentar
    $query = "INSERT INTO comments (post_id, user_id, comment_text, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("iis", $post_id, $user_id, $comment_text);

    if ($stmt->execute()) {
        header("Location: postingan.php?post_id=" . $post_id);
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
