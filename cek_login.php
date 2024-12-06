<?php
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    $error_messages = [
        "Siapa nih? Login dulu bang 😑",
        "Yah, lupa login ya? Balik dulu ke login.",
        "Waduh, siapa nih? Kok gak login dulu? 🤨",
        "Login dulu bos! Baru bisa akses sini.",
        "Hmm, siapa nih? Kok belum login? 🧐",
        "Yakin kamu sudah login? Soalnya aku gak lihat datamu. 🤔",
        "Kamu ngintip-ngintip tapi gak login dulu ya? Curiga nih... 😏",
        "Belum login kok sudah sampai sini? Ada apa ini? 😶",
        "Heh, belum login ya? Jangan-jangan mau nyusup? 🤨",
        "Ada yang aneh... Kamu belum login, kan? Ngaku deh! 😬",
        "Waduh, kok langsung masuk sini? Login dulu dong. Kami curiga. 😑",
        "Hmm, gak kenal nih. Kamu siapa? Login dulu ya sebelum masuk. 👀",
        "Eits, jangan sembarangan akses! Login dulu dong biar resmi. 🛑",
        "Yakin mau ke sini tanpa login? Kayaknya mencurigakan... 🚨"
    ];
    $random_error_message = $error_messages[array_rand($error_messages)];
    $_SESSION['error'] = $random_error_message;
    header("location:login.php");
    exit();
}
?>