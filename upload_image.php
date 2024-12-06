<?php
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_ext, $allowed_extensions)) {
        echo json_encode(['success' => false, 'message' => 'Format file tidak diizinkan.']);
        exit();
    }

    if ($_FILES['image']['size'] > 10 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar.']);
        exit();
    }

    $new_file_name = uniqid() . '.' . $file_ext;
    $upload_dir = __DIR__ . '/img/';
    $file_path = $upload_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $file_path)) {
        $url = 'img/' . $new_file_name;
        echo json_encode(['success' => true, 'url' => $url]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengunggah gambar.']);
    }
    exit();
}
echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diunggah.']);
?>
