<?php
include("../connection.php");

if (isset($_GET['trip_id'])) {
    $trip_id = intval($_GET['trip_id']);

    // pega o shape_id da viagem
    $sql = "SELECT shape_id FROM trips WHERE trip_id = $trip_id";
    $res = mysqli_query($conexao, $sql);
    $row = mysqli_fetch_assoc($res);

    if ($row && $row['shape_id']) {
        $shape_id = $row['shape_id'];

        // pega os pontos do shape
        $sql2 = "SELECT shape_pt_lat, shape_pt_lon 
                 FROM shapes 
                 WHERE shape_id = $shape_id 
                 ORDER BY shape_pt_sequence ASC";
        $res2 = mysqli_query($conexao, $sql2);

        $pontos = [];
        while ($r = mysqli_fetch_assoc($res2)) {
            $pontos[] = [$r['shape_pt_lat'], $r['shape_pt_lon']];
        }

        echo json_encode($pontos);
    } else {
        echo json_encode([]);
    }
}
