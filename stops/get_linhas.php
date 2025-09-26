<?php
include("../connection.php");

if (isset($_GET['agency_id'])) {
    $agency_id = intval($_GET['agency_id']);

    $sql = "SELECT route_id, route_long_name 
            FROM routes 
            WHERE agency_id = $agency_id 
            ORDER BY route_long_name ASC";
    $result = mysqli_query($conexao, $sql);

    $linhas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $linhas[] = $row;
    }

    echo json_encode($linhas);
}
