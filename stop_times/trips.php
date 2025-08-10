<?php
    include("../connection.php");

    // Declaração da variavel para receber o ID
    $id = $_GET['id'];

    // Consulta no banco de dados para exibir no nome da viagem 
    $sql_viag = "SELECT trip_headsign, trip_short_name FROM trips WHERE trip_id = $id";
    $result_viag = mysqli_query($conexao, $sql_viag);

    // Laço de repetição para trazer no nome da viagem do banco
    while($sql_result_viag = mysqli_fetch_array($result_viag)){
        $destino = $sql_result_viag['trip_headsign'];
        $origem = $sql_result_viag['trip_short_name'];                              
    }
    // Consulta no banco de dados para exibir na tabela
    $sql = "SELECT stop_sequence, stop_id, stop_headsign  FROM stop_times WHERE trip_id = $id ORDER BY stop_sequence ASC";
    $result = mysqli_query($conexao, $sql);
    
?>

<!--Script para confirmar a exclusão-->
<script>
    function deletar() {
        if(confirm("Deseja exluir esse item?"))
            document.forms[0].submit();
        else
            return false
    }
</script>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema WebBus</title>
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css?v=1.2">
    <link rel="stylesheet" href="../css/table.css?v=1.0">
    <link rel="stylesheet" href="../css/stop_times.css?v=1.1">    
</head>

<body>
    <div>
        <header>
            <h1>Trajeto</h1>
        </header>
        <main>
            <section>
                <label>Viagem: <?php echo $origem; ?> - <?php echo $destino; ?> </label> 
                <br><br>                        
                <table>
                    <caption>Relação de pontos do trajeto</caption>
                    <thead>
                        <th class="th-seq">Sequencia</th>
                        <th class="th-pont">Ponto</th>
                        <th class="th-dest">destino</th>                        
                    </thead>
                    <?php
                        // Laço de repetição para trazer dados do banco
                        while($sql_result = mysqli_fetch_array($result)){
                            $id = $sql_result['service_id'];                            
                            $data_inicio = $sql_result['data_for_start'];  
                            $data_fim = $sql_result['data_for_end'];    
                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $id ?></td>
                            <td><?php echo $data_inicio ?></td>
                            <td><?php echo $data_fim ?></td>                            
                        </tr> 
                        <?php }; ?>                       
                    </tbody>
                </table>
                <br>                
                 <!--Consulta no banco de dados a quantidade de registros-->
                <?php
                    $sql = "SELECT COUNT(*) AS total FROM calendar";
                    $result = mysqli_query($conexao, $sql);

                     $row = mysqli_fetch_assoc($result);
                     $total_registros = $row['total'];                    
                ?>
                <!-- Mostra a quantidade de registros-->
                <p>Total de calendários cadastrados: <?php echo $total_registros;?></p>
                <br>           
            </section>
        </main>
        <footer>
            <p><a href="../index.html">< Voltar</a></p>
        </footer>
    </div>
</body>
<script src="../js/modal-calendar.js"></script>
</html>