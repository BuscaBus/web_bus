<?php
include("../connection.php");

$data = json_decode(file_get_contents("php://input"), true);
$trip_id = (int)$data["trip_id"];
$coords = $data["coords"] ?? [];

// Buscar shape_id da trip
$sql = "SELECT shape_id FROM trips WHERE trip_id = $trip_id";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$shape_id = $row['shape_id'];

if (!$shape_id) {
    if (empty($coords)) {
        echo "Nenhum traçado para remover.";
        exit;
    }
    // Criar novo shape_id
    $shape_id = time();
    mysqli_query($conexao, "UPDATE trips SET shape_id = $shape_id WHERE trip_id = $trip_id");
}

// Se coords vazio → remover shape
if (empty($coords)) {
    mysqli_query($conexao, "DELETE FROM shapes WHERE shape_id = $shape_id");
    mysqli_query($conexao, "UPDATE trips SET shape_id = NULL WHERE trip_id = $trip_id");
    echo "Traçado removido.";
    exit;
}

// Salvar novo traçado
mysqli_query($conexao, "DELETE FROM shapes WHERE shape_id = $shape_id");

$seq = 1;
$stmt = $conexao->prepare("INSERT INTO shapes (shape_id, shape_pt_lat, shape_pt_lon, shape_pt_sequence) VALUES (?, ?, ?, ?)");

foreach ($coords as $c) {
    $lon = $c[0];
    $lat = $c[1];
    $stmt->bind_param("iddi", $shape_id, $lat, $lon, $seq);
    $stmt->execute();
    $seq++;
}

echo "Traçado salvo com sucesso!";
