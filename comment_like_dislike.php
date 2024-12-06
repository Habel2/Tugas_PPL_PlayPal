<?php
include "koneksi.php";

$comment_id = $_POST['comment_id'];
$type = $_POST['type'];
$user_id = 1;
$conn = $koneksi;
$query = "SELECT * FROM likes_dislikes_comments WHERE comment_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $comment_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $existing_vote = $result->fetch_assoc();
    
    if ($existing_vote['type'] == $type) {
        $delete_query = "DELETE FROM likes_dislikes_comments WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $existing_vote['id']);
        $stmt->execute();
    } else {
        $update_query = "UPDATE likes_dislikes_comments SET type = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $type, $existing_vote['id']);
        $stmt->execute();
    }
} else {
    $insert_query = "INSERT INTO likes_dislikes_comments (comment_id, user_id, type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iis", $comment_id, $user_id, $type);
    $stmt->execute();
}

$like_count_query = "SELECT COUNT(*) AS like_count FROM likes_dislikes_comments WHERE comment_id = ? AND type = 'like'";
$stmt = $conn->prepare($like_count_query);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$like_result = $stmt->get_result();
$like_count = $like_result->fetch_assoc()['like_count'];

$dislike_count_query = "SELECT COUNT(*) AS dislike_count FROM likes_dislikes_comments WHERE comment_id = ? AND type = 'dislike'";
$stmt = $conn->prepare($dislike_count_query);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$dislike_result = $stmt->get_result();
$dislike_count = $dislike_result->fetch_assoc()['dislike_count'];

echo $like_count . "|" . $dislike_count;
?>
