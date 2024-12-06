<?php
session_start();
include('koneksi.php');

$username_or_email = $_POST['username_or_email'];
$password = $_POST['password'];

$username_or_email = mysqli_real_escape_string($koneksi, $username_or_email);
$password = mysqli_real_escape_string($koneksi, $password);

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username_or_email' OR email = '$username_or_email'");

if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['success'] = 'Login berhasil! Selamat datang, ' . $user['username'];
        header("location:index.php");
    } else {
        $login_error_messages = [
            "Email atau passwordnya salah. Coba cek lagi, ya! 😅",
            "Oops, kayaknya ada yang keliru. Cek email atau passwordmu. 🙃",
            "Hmm, kita gak kenal kamu. Mungkin salah ketik? 🤔 Coba lagi!",
            "Salah password? Atau salah email? Coba pikir-pikir lagi! 🧐",
            "Yakin sudah benar? Soalnya sistem gak nemuin kamu. 🤷‍♂️",
            "Email/password salah. Mau kita bantu ingetin password? 🤷",
            "Cek lagi deh, mungkin ada huruf besar/kecil yang kelewat. 🤓",
            "Waduh, akunmu gak cocok dengan data kami. Coba lagi ya. 🤦",
            "Kombinasi email dan password ini kurang pas. Cek lagi yuk! 🔍",
            "Mungkin jarimu salah pencet? Coba ketik ulang dengan tenang. 🖊️"
        ];

        $random_error_message = $login_error_messages[array_rand($login_error_messages)];
        $_SESSION['error'] = $random_error_message;
        header("location:login.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'Username atau email tidak ditemukan.';
    header("location:login.php");
}
