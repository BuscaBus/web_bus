<?php
include("../connection.php");

if (!isset($_GET['trip_id'])) {
    die("trip_id não informado.");
}

$trip_id = intval($_GET['trip_id']);

// Buscar shape_id da trip
$sql = "SELECT shape_id FROM trips WHERE trip_id = $trip_id LIMIT 1";
$result = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row || !$row['shape_id']) {
    echo json_encode([]);
    exit;
}

$shape_id = intval($row['shape_id']);

// Buscar todos os pontos do shape
$sql = "SELECT shape_pt_lat, shape_pt_lon FROM shapes 
        WHERE shape_id = $shape_id 
        ORDER BY shape_pt_sequence ASC";
$result = mysqli_query($conexao, $sql);

$coords = [];
while ($ponto = mysqli_fetch_assoc($result)) {
    $coords[] = [(float)$ponto['shape_pt_lat'], (float)$ponto['shape_pt_lon']];
}

echo json_encode($coords, JSON_UNESCAPED_UNICODE);
