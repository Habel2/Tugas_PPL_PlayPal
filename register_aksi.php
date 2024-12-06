<?php
session_start();
include('koneksi.php');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$username = mysqli_real_escape_string($koneksi, $username);
$email = mysqli_real_escape_string($koneksi, $email);

$query_check = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");
if (!$query_check) {
    die('Query Failed: ' . mysqli_error($koneksi));
}
$cek = mysqli_num_rows($query_check);

if ($cek > 0) {
    $registration_error_messages = [
        "Email atau username ini sudah digunakan orang lain. Coba yang lain, ya! 😕",
        "Kayaknya akun ini sudah terdaftar. Punya email atau username lain? 🤷‍♀️",
        "Oops, email/username ini sudah diambil. Gak bisa dipakai dua kali. 😬",
        "Akun ini sudah ada. Mau coba daftar dengan data yang berbeda? 😊",
        "Email/username ini sudah dipakai. Sudah terdaftar di database kami. 📋",
        "Gagal mendaftar. Email/username sudah ada di sistem. 😞",
        "Email atau username ini sudah eksis. Mau coba kombinasi lain? 🤔",
        "Hmm, ada nama yang sama di database. Gak bisa duplikat, bos! 😅",
        "Aduh, ini udah dipakai orang. Cari email atau username lain yuk! 🛠️",
        "Data ini gak bisa dipakai. Email/username sudah ada sebelumnya. 🚫"
    ];
    
    $random_error_message = $registration_error_messages[array_rand($registration_error_messages)];
    $_SESSION['error'] = $random_error_message;
    header("location:register.php");
    exit();
} else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query_insert = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    $result = mysqli_query($koneksi, $query_insert);

    if ($result) {
        $_SESSION['success'] = "Pendaftaran berhasil! Selamat datang, $username! 🎉 Silakan login.";
        header("location:login.php");
        exit();
    } else {
        $_SESSION['error'] = "Pendaftaran gagal. Silakan coba lagi. 😞";
        header("location:register.php");
        exit();
    }
}
?>
