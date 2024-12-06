<?php
session_start();
session_unset();
session_destroy();

$logout_messages = [
    "Yah, kamu pergi lagi... 😢 Kami bakal nunggu kamu balik, ya.",
    "Dunia ini jadi lebih sepi tanpa kamu. Cepat kembali, ya... 😔",
    "Kehadiranmu itu berarti banget buat kami. Jangan lama-lama ya... 🥺",
    "Aduh, hati ini rasanya kosong pas kamu logout... 😟",
    "Selamat jalan sementara... Tapi jangan lupa pulang, ya? 😥",
    "Tanpa kamu di sini, semuanya jadi terasa hampa. Sampai jumpa lagi... 😢",
    "Hmm... rasanya sedih banget setiap kamu pergi. Kapan balik lagi? 😞",
    "Logout selesai. Dunia kami jadi lebih sunyi tanpamu... 🌧️",
    "Kami akan selalu di sini menunggumu. Tapi kok rasanya lama banget? 😩",
    "Kepergianmu meninggalkan luka di hati kami. Sampai jumpa lagi... 💔"
];

$random_logout_message = $logout_messages[array_rand($logout_messages)];

session_start();
$_SESSION['success'] = $random_logout_message;

header("location:login.php");
exit();
?>
