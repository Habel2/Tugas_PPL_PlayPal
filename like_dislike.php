<?php
session_start();
include "koneksi.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Validasi input
if (isset($_POST['post_id']) && isset($_POST['type'])) {
    $post_id = intval($_POST['post_id']);
    $type = $_POST['type'];

    if ($type !== 'like' && $type !== 'dislike') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid type'
        ]);
        exit();
    }

    // Cek apakah post_id valid
    $query = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Comment not found'
        ]);
        exit();
    }

    // Cek apakah user sudah memberikan like/dislike sebelumnya untuk komentar ini
    $check_query = "SELECT * FROM likes_dislikes WHERE post_id = ? AND user_id = ?";
    $stmt = $koneksi->prepare($check_query);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User sudah memberikan feedback sebelumnya
        $existing = $check_result->fetch_assoc();

        if ($existing['type'] === $type) {
            // Jika user menekan tombol yang sama, cabut feedback
            $delete_query = "DELETE FROM likes_dislikes WHERE post_id = ? AND user_id = ?";
            $stmt = $koneksi->prepare($delete_query);
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();

            // Kurangi jumlah like atau dislike pada komentar
            if ($type === 'like') {
                $update_comment_query = "UPDATE posts SET likes = likes - 1 WHERE post_id = ?";
            } else {
                $update_comment_query = "UPDATE posts SET dislikes = dislikes - 1 WHERE post_id = ?";
            }
            $stmt = $koneksi->prepare($update_comment_query);
            $stmt->bind_param("i", $post_id);
            $stmt->execute();

            echo json_encode([
                'status' => 'success',
                'message' => ucfirst($type) . ' removed',
            ]);
        } else {
            // User mengganti dari like ke dislike atau sebaliknya
            $update_query = "UPDATE likes_dislikes SET type = ?, created_at = NOW() WHERE post_id = ? AND user_id = ?";
            $stmt = $koneksi->prepare($update_query);
            $stmt->bind_param("sii", $type, $post_id, $user_id);
            $stmt->execute();

            // Perbarui jumlah like dan dislike pada komentar
            if ($type === 'like') {
                $update_comment_query = "UPDATE posts SET likes = likes + 1, dislikes = dislikes - 1 WHERE post_id = ?";
            } else {
                $update_comment_query = "UPDATE posts SET dislikes = dislikes + 1, likes = likes - 1 WHERE post_id = ?";
            }
            $stmt = $koneksi->prepare($update_comment_query);
            $stmt->bind_param("i", $post_id);
            $stmt->execute();

            echo json_encode([
                'status' => 'success',
                'message' => ucfirst($type) . ' updated',
            ]);
        }
    } else {
        // Tambahkan like/dislike baru
        $insert_query = "INSERT INTO likes_dislikes (post_id, user_id, type, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $koneksi->prepare($insert_query);
        $stmt->bind_param("iis", $post_id, $user_id, $type);
        $stmt->execute();

        // Perbarui jumlah like/dislike
        if ($type === 'like') {
            $update_comment_query = "UPDATE posts SET likes = likes + 1 WHERE post_id = ?";
        } else {
            $update_comment_query = "UPDATE posts SET dislikes = dislikes + 1 WHERE post_id = ?";
        }
        $stmt = $koneksi->prepare($update_comment_query);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        echo json_encode([
            'status' => 'success',
            'message' => ucfirst($type) . ' added',
        ]);
    }

    // Ambil data terbaru untuk likes dan dislikes
    $refresh_query = "SELECT likes, dislikes FROM posts WHERE post_id = ?";
    $stmt = $koneksi->prepare($refresh_query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $refresh_result = $stmt->get_result();
    $data = $refresh_result->fetch_assoc();

    echo json_encode([
        'status' => 'success',
        'likes' => $data['likes'],
        'dislikes' => $data['dislikes']
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}
?>
