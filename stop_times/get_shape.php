<?php
include("../connection.php");

$trip_id = (int)$_GET["trip_id"];

// Buscar shape_id vinculado à trip
$sql = "SELECT shape_id FROM trips WHERE trip_id = $trip_id";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$shape_id = $row['shape_id'] ?? null;

$coords = [];

if ($shape_id) {
    $sql_shape = "SELECT shape_pt_lat, shape_pt_lon
                  FROM shapes
                  WHERE shape_id = $shape_id
                  ORDER BY shape_pt_sequence ASC";
    $result = mysqli_query($conexao, $sql_shape);

    while ($r = mysqli_fetch_assoc($result)) {
        // Leaflet espera [lat, lon]
        $coords[] = [(float)$r["shape_pt_lat"], (float)$r["shape_pt_lon"]];
    }
}

header("Content-Type: application/json");
echo json_encode($coords);
