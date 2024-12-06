<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$title = $_POST['title'];
$game_name = trim($_POST['game_name']);
$text_content = $_POST['content'];
$user_id = $_SESSION['user_id'];

// if (empty($game_name)) {
//     die("Nama game tidak boleh kosong.");
// }

$query = "SELECT game_id FROM games WHERE game_name = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $game_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $game_id = $result->fetch_assoc()['game_id'];
} else {
    $insert_game_query = "INSERT INTO games (game_name) VALUES (?)";
    $stmt = $koneksi->prepare($insert_game_query);
    $stmt->bind_param("s", $game_name);
    if ($stmt->execute()) {
        $game_id = $stmt->insert_id;
    } else {
        echo "Error menambahkan game: " . $stmt->error;
        exit();
    }
}

$query = "INSERT INTO posts (user_id, title, game_id, text_content, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("isis", $user_id, $title, $game_id, $text_content);

if ($stmt->execute()) {
    header("location: index.php");
} else {
    echo "Error menambahkan postingan: " . $stmt->error;
}
?>
