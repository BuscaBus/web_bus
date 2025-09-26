<?php
include("../connection.php");

if (isset($_GET['route_id'])) {
    $route_id = intval($_GET['route_id']);

    $sql = "SELECT trip_id, 
                   CONCAT(trip_short_name, ' - ', trip_headsign) AS viagem_nome
            FROM trips 
            WHERE route_id = $route_id 
            ORDER BY trip_short_name, trip_headsign";
    $result = mysqli_query($conexao, $sql);

    $viagens = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $viagens[] = $row;
    }

    echo json_encode($viagens);
}

