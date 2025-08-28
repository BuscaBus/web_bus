<?php
   include("../connection.php");

    // Recebe as variaveis
    $trip_id = $_POST['id'];
    $hr_inicio = $_POST['hora_inicio'];
    $hr_fim = $_POST['hora_fim'];
    $ponto = $_POST['ponto'];
    $sequencia = $_POST['sequencia'];
    $destino = $_POST['destino'];
    $tipo_hora = $_POST['tipo_hora'];
    
    
    // Altera no banco de dados
    $sql = "INSERT INTO stop_times (
                trip_id, 
                arrival_time, 
                departure_time,
                stop_code,
                stop_sequence,
                stop_headsign,
                timepoint                
            ) 
            VALUES (
                '$trip_id', 
                '$hr_inicio', 
                '$hr_fim',
                '$ponto',
                '$sequencia',
                '$destino',
                '$tipo_hora'                
            )";
    $query = mysqli_query($conexao, $sql);

    //if(mysqli_query($conexao, $sql)){
      //echo "Operadora editada com sucesso";        
    //}
    //else{
      // echo "Erro ao editar".mysqli_connect_error($conexao);
    //}

    // Redireciona para a página anterior passando o id
    header("Location: register.php?id=$trip_id");
    exit;

?>
