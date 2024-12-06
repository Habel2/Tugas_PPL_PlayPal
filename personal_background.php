<?php
include "background.php";
include "cek_login.php";
$user_id = $_SESSION['user_id'];
$user_folder = "./uploads/backgrounds/user_$user_id/uploaded_background/";

// Membuat folder pengguna jika belum ada
if (!file_exists($user_folder)) {
    mkdir($user_folder, 0777, true);
}

// File default
$default_bg_folder = "./bg/";
$default_bg_files = glob($default_bg_folder . "*");

// File pengguna
$files = glob($user_folder . "*");

// Membaca background aktif
$active_background_file = $user_folder . "active_background.txt";
$active_background = file_exists($active_background_file) ? file_get_contents($active_background_file) : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="transparent_bg.css">
    <title>Background Management</title>
    <style>
        .background-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .background-item {
            position: relative;
            width: 200px;
            height: 200px;
            overflow: hidden;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .background-item img, .background-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .active-background {
            border: 3px solid green;
        }
        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0);
            color: white;
            border: none;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="./bg/PlayPal_Logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            PlayPal
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="posting.php">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="personal_background.php">Background</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a>
            </form>
        </div>
    </nav>
<div class="container mt-5">
    <h2 class="text-center">Manage Your Background</h2>

    <!-- Form Upload -->
    <form id="upload-form" enctype="multipart/form-data">
        <input type="file" class="form-control transparent-bg" id="background" accept="image/*,video/*" required>
        <div id="image-container" class="mt-3" style="display: none;">
            <img id="preview-image" src="" style="max-width: 100%;">
        </div>
        <button type="button" id="crop-btn" class="btn btn-success btn-block mt-3" style="display: none;">Crop and Upload</button>
        <button type="button" id="upload-video-btn" class="btn btn-success btn-block mt-3" style="display: none;">Upload Video</button>
    </form>

    <!-- Default Backgrounds -->
    <h4 class="mt-5">Default Backgrounds</h4>
    <div class="background-container">
        <?php foreach ($default_bg_files as $file): ?>
            <div class="background-item <?= $file === $active_background ? 'active-background' : '' ?>" onclick="selectBackground('<?= $file ?>')">
                <?php
                    $file_extension = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= $file ?>" alt="Uploaded Background">
                    <?php elseif (in_array($file_extension, ['mp4', 'webm', 'ogg'])): ?>
                        <video src="<?= $file ?>" autoplay muted loop></video>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>
    </div>

    <!-- Uploaded Backgrounds -->
    <h4 class="mt-5">Personal Backgrounds</h4>
    <div class="background-container">
        <?php foreach ($files as $file): ?>
            <div class="background-item <?= $file === $active_background ? 'active-background' : '' ?>" onclick="selectBackground('<?= $file ?>')">
                <?php
                $file_extension = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <img src="<?= $file ?>" alt="Uploaded Background">
                <?php elseif (in_array($file_extension, ['mp4', 'webm', 'ogg'])): ?>
                    <video src="<?= $file ?>" autoplay muted loop></video>
                <?php endif; ?>
                <button class="delete-button" onclick="confirmDelete('<?= $file ?>')">
                    <img src="./icon/trashcan.png" alt="Hapus" style="width: auto; height: 20px;">
                </button>

            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
const backgroundInput = document.getElementById('background');
const previewImage = document.getElementById('preview-image');
const cropBtn = document.getElementById('crop-btn');
const uploadVideoBtn = document.getElementById('upload-video-btn');
let cropper;

backgroundInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const fileType = file.type.split('/')[0];

    if (fileType === 'image') {
        const reader = new FileReader();
        reader.onload = function (event) {
            previewImage.src = event.target.result;
            document.getElementById('image-container').style.display = 'block';
            cropBtn.style.display = 'block';
            uploadVideoBtn.style.display = 'none';

            if (cropper) cropper.destroy();
            cropper = new Cropper(previewImage, { aspectRatio: 16 / 9 });
        };
        reader.readAsDataURL(file);
    } else if (fileType === 'video') {
        document.getElementById('image-container').style.display = 'none';
        cropBtn.style.display = 'none';
        uploadVideoBtn.style.display = 'block';
    }
});

cropBtn.addEventListener('click', function () {
    cropper.getCroppedCanvas().toBlob(function (blob) {
        const formData = new FormData();
        formData.append('background', blob, 'cropped-image.png');

        fetch('personal_background_proses.php', {
            method: 'POST',
            body: formData,
        })
        .then(() => location.reload())
        .catch(() => alert('Upload failed!'));
    });
});

uploadVideoBtn.addEventListener('click', function () {
    const formData = new FormData();
    formData.append('background', backgroundInput.files[0]);

    fetch('personal_background_proses.php', {
        method: 'POST',
        body: formData,
    })
    .then(() => location.reload())
    .catch(() => alert('Upload failed!'));
});

    function selectBackground(file) {
        fetch('set_background.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'file=' + encodeURIComponent(file)
        }).then(() => location.reload());
    }

    function confirmDelete(file) {
        if (confirm("Are you sure you want to delete this background?")) {
            window.location.href = "personal_background_delete.php?file=" + encodeURIComponent(file);
        }
    }
</script>
</body>
</html>