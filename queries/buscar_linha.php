<?php
include("../connection.php");

if (isset($_GET['operadora'])) {
    $operadora = mysqli_real_escape_string($conexao, $_GET['operadora']);

    $sql = "SELECT a.agency_id, a.agency_name, r.agency_id, r.route_long_name 
            FROM routes r 
            INNER JOIN agency a ON r.agency_id = a.agency_id
            WHERE a.agency_name = '$operadora' 
            ORDER BY r.route_long_name ASC";
    $result = mysqli_query($conexao, $sql);

    $linha = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $linha[] = [
            'nome' => $row['route_long_name']
        ];
    }

    echo json_encode($linha);
}
?>