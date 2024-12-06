<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id === 0) {
    echo "ID postingan tidak valid.";
    exit;
}

$query_check = "SELECT * FROM posts WHERE post_id = $post_id AND user_id = $user_id";
$result_check = $koneksi->query($query_check);

if ($result_check->num_rows > 0) {
    $row = $result_check->fetch_assoc();
    $text_content = $row['text_content'];
    
    preg_match_all('/<img src="([^"]+)"/', $text_content, $matches);
    
    if (isset($matches[1])) {
        foreach ($matches[1] as $img_url) {
            $image_name = basename($img_url);
            $image_path = 'img/' . $image_name;
            
            if (file_exists($image_path)) {
                if (!unlink($image_path)) {
                    echo "Gagal menghapus gambar: " . $image_path;
                    exit;
                }
            }
        }
    }

    $query_delete_comments = "DELETE FROM comments WHERE post_id = $post_id";
    if (!$koneksi->query($query_delete_comments)) {
        echo "Gagal menghapus komentar: " . $koneksi->error;
        exit;
    }

    $query_delete_likes_dislikes = "DELETE FROM likes_dislikes WHERE post_id = $post_id";
    if (!$koneksi->query($query_delete_likes_dislikes)) {
        echo "Gagal menghapus likes/dislikes: " . $koneksi->error;
        exit;
    }

    $query_delete_posts = "DELETE FROM posts WHERE post_id = $post_id";
    if (!$koneksi->query($query_delete_posts)) {
        echo "Gagal menghapus postingan: " . $koneksi->error;
        exit;
    }

    echo "Post berhasil dihapus.";
    header("Location: index.php");
} else {
    echo "Anda tidak berhak menghapus postingan ini.";
}
?>