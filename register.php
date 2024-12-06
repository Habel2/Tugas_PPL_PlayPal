<?php
session_start();
if (isset($_SESSION['error'])) {
    $message = $_SESSION['error'];
    $type = 'danger'; // Error message
    unset($_SESSION['error']);
} elseif (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    $type = 'success'; // Success message
    unset($_SESSION['success']);
} else {
    $message = '';
    $type = '';
}
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

    <title>Register</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="register.php">Register <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
    
      <!-- Form -->
      <div class="container rounded">
        <form method="POST" action="register_aksi.php" class="form">
          <div class="form-group">
            <label>Username</label>
            <input name="username" type="text" class="form-control" placeholder="Masukkan username disini" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input name="email" type="email" class="form-control" placeholder="Masukkan email disini" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" class="form-control" placeholder="Masukkan password disini" required>
          </div>
          <p style="color: white;">Sudah punya akun? <a href="login.php" style="color: white; text-decoration: none;"> Klik disini!</a></p>
            <button type="submit" class="btn btn-danger">Submit</button>
        </form>
      </div>
    </div>

    <!-- Modal untuk pesan -->
    <?php if ($message): ?>
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-<?php echo $type; ?> text-white">
            <h5 class="modal-title" id="messageModalLabel"><?php echo $type === 'success' ? 'Sukses' : 'Kesalahan'; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <?php echo $message; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      <?php if ($message): ?>
      $(document).ready(function() {
        $('#messageModal').modal('show');
      });
      <?php endif; ?>
    </script>
  </body>
</html>
