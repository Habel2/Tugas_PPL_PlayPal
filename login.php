<?php
session_start();
$message = null;
$type = null;

// Periksa apakah ada pesan di session
if (isset($_SESSION['error'])) {
    $message = $_SESSION['error'];
    $type = 'danger'; // Pesan error (merah)
    unset($_SESSION['error']);
} elseif (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    $type = 'success'; // Pesan sukses (hijau)
    unset($_SESSION['success']);
}

// Sertakan feedback.php
include "feedback.php";
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="./loginnregister.css">
    <link rel="stylesheet" href="./background.css">

    <title>Login</title>
  </head>

  <body>
    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="./bg/background.webm" type="video/webm">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="halaman">
      <!-- Navigasi -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
        <a class="navbar-brand" href="#">
            <img src="./bg/PlayPal_Logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            PlayPal
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>
    
      <!-- Form -->
      <div class="container rounded">
        <form method="POST" action="login_aksi.php" class="form">
          <div class="form-group">
            <label>Username atau Email</label>
            <input name="username_or_email" type="text" class="form-control" placeholder="Masukkan username atau email di sini" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" class="form-control" placeholder="Masukkan password disini" required>
          </div>
          <p style="color: white;">Belum punya akun? <a href="register.php" style="color: white; text-decoration: none;"> Klik disini!</a></p>

            <button type="submit" class="btn btn-danger">Submit</button>
        </form>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
