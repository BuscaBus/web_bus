<?php
include("../connection.php");

// Lê dados enviados via fetch (JSON)
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['trip_id']) || !isset($data['coords'])) {
    die("Dados inválidos.");
}

$trip_id = intval($data['trip_id']);
$coords = $data['coords'];

// 1) Gerar um novo shape_id (por exemplo, último + 1)
$result = mysqli_query($conexao, "SELECT IFNULL(MAX(shape_id),0)+1 AS next_id FROM shapes");
$row = mysqli_fetch_assoc($result);
$shape_id = intval($row['next_id']);

// 2) Atualizar a viagem para apontar para esse shape_id
$sql = "UPDATE trips SET shape_id = $shape_id WHERE trip_id = $trip_id";
if (!mysqli_query($conexao, $sql)) {
    die("Erro ao atualizar trip: " . mysqli_error($conexao));
}

// 3) Inserir todos os pontos da linha
$sequence = 1;
$dist = 0.0;

foreach ($coords as $coord) {
    $lon = floatval($coord[0]);
    $lat = floatval($coord[1]);

    $sql = "INSERT INTO shapes (shape_id, shape_pt_lat, shape_pt_lon, shape_pt_sequence, shape_dist_traveled) 
            VALUES ($shape_id, $lat, $lon, $sequence, $dist)";
    if (!mysqli_query($conexao, $sql)) {
        die("Erro ao inserir ponto: " . mysqli_error($conexao));
    }

    $sequence++;
    $dist += 0.1; // incremento fictício (ideal: calcular distância real)
}

echo "Traçado $shape_id salvo com sucesso e vinculado à trip $trip_id";
