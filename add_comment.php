<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

if (isset($_POST['post_id']) && isset($_POST['comment_text'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $comment_text = htmlspecialchars($_POST['comment_text']);

    $query = "INSERT INTO comments (post_id, user_id, comment_text, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("iis", $post_id, $user_id, $comment_text);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Comment added successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add comment'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}
?>
