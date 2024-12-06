<?php
include "koneksi.php";

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    $query = "SELECT game_name FROM games LIMIT 10";
    $stmt = $koneksi->prepare($query);
} else {
    $query = "SELECT game_name FROM games WHERE game_name LIKE ? LIMIT 10";
    $stmt = $koneksi->prepare($query);
    $searchTerm = '%' . $q . '%';
    $stmt->bind_param("s", $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

$games = [];
while ($row = $result->fetch_assoc()) {
    $games[] = $row;
}

header('Content-Type: application/json');
echo json_encode($games);
?>
