<?php
session_start(); // Memulai sesi

include "koneksi.php"; // Pastikan koneksi ke database sudah benar

// Cek apakah request POST mengandung comment_id
if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

    // Query untuk menghapus komentar
    $query = "DELETE FROM comments WHERE comment_id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        // Jika berhasil, set session untuk sukses dan arahkan ke feedback.php
        $_SESSION['success'] = "Komentar berhasil dihapus.";
        header("Location: feedback.php"); // Arahkan ke feedback.php untuk menampilkan pesan
        exit();
    } else {
        // Jika gagal, set session untuk error dan arahkan ke feedback.php
        $_SESSION['error'] = "Gagal menghapus komentar. Silakan coba lagi.";
        header("Location: feedback.php"); // Arahkan ke feedback.php untuk menampilkan pesan
        exit();
    }
} else {
    // Jika tidak ada comment_id, set session error
    $_SESSION['error'] = "comment_id tidak ditemukan.";
    header("Location: feedback.php"); // Arahkan ke feedback.php untuk menampilkan pesan
    exit();
}
?>
