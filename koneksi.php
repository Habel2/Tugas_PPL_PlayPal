<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $name   = "playpal_ppl";
    $koneksi = mysqli_connect($host, $user, $pass, $name) or die ("koneksi ke database gagal");
    mysqli_select_db($koneksi, $name);
?>