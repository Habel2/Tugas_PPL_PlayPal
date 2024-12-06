<?php
include "background.php";
include "koneksi.php";
include "cek_login.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./background.css">
    <link rel="stylesheet" href="posting.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="transparent_bg.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <title>Posting</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="./bg/PlayPal_Logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            PlayPal
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="posting.php">Post <span class="sr-only">(current)</span></a>
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

<div class="container">
    <form action="posting_proses.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="postTitle">Judul</label>
            <input type="text" class="form-control transparent-bg" id="postTitle" name="title" placeholder="Masukkan judul" required>
        </div>

        <div class="form-group">
            <label for="gameName">Nama Game</label>
            <input type="text" class="form-control transparent-bg" id="gameName" name="game_name" placeholder="Masukkan nama game" autocomplete="off" required>
            <ul id="suggestions" class="list-group" style="position: absolute; z-index: 1000; width: 100%;"></ul>
        </div>



        <div class="form-group">
            <label for="editor">Konten</label>
            <div id="editor" class="transparent-bg" style="height: 300px;"></div>
            <input type="hidden" name="content" id="content">
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>

</div>

<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link', 'image']
            ]
        }
    });

    quill.getModule('toolbar').addHandler('image', function () {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async function () {
            const file = input.files[0];

            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran gambar terlalu besar. Maksimum 10MB.');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            const response = await fetch('upload_image.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                const range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', data.url);
            } else {
                alert('Gagal mengunggah gambar.');
            }
        };
    });

    const gameNameInput = document.getElementById('gameName');
    const suggestionsList = document.getElementById('suggestions');

    gameNameInput.addEventListener('focus', () => fetchSuggestions(''));
    gameNameInput.addEventListener('input', () => fetchSuggestions(gameNameInput.value));
    gameNameInput.addEventListener('blur', () => {
        setTimeout(() => {
            suggestionsList.innerHTML = '';
        }, 200);
    });

    function fetchSuggestions(query = '') {
        fetch('search_games.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsList.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(game => {
                        const listItem = document.createElement('li');
                        listItem.textContent = game.game_name;
                        listItem.className = 'list-group-item list-group-item-action';
                        listItem.onclick = () => {
                            gameNameInput.value = game.game_name;
                            suggestionsList.innerHTML = '';
                        };
                        suggestionsList.appendChild(listItem);
                    });
                } else {
                    const noResultItem = document.createElement('li');
                    noResultItem.textContent = 'Tidak ada hasil';
                    noResultItem.className = 'list-group-item disabled';
                    suggestionsList.appendChild(noResultItem);
                }
            })
            .catch(error => console.error('Error fetching game suggestions:', error));
    }


</script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>