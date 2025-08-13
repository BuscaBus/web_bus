<?php
include("../connection.php");

// Declaração da variavel para receber o ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erro: ID não informado ou inválido.");
}

$id = (int) $_GET['id'];


// Consulta o ID no banco de dados
$sql = "SELECT trip_id, service_id, trip_headsign, trip_short_name FROM trips WHERE trip_id = $id";
$result = mysqli_query($conexao, $sql);

// Variavel que recebe o ID do banco de dados    
$result_id = mysqli_fetch_assoc($result);

?>

<!--Script para confirmar a exclusão-->
<script>
    function deletar() {
        if (confirm("Deseja exluir esse item?"))
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
    <link rel="stylesheet" href="../css/stop_times.css?v=1.2">
</head>

<body>
    <div>
        <header>
            <h1>Horários</h1>
        </header>
        <main class="main-cont">
            <!-- Section para cadastrar horários -->
            <section class="sect-reg-hor">
                <h1 class="h1-cad-hor">Cadastrar Horários</h1>
                <br>
                <form action="result_register.php" method="POST" autocomplete="off" class="form-cad-vig">
                    <input type="hidden" name="id" class="inpt1" id="id-nome" value="<?= $result_id['trip_id'] ?>">
                    <p class="p-estilo">
                        <label for="id-serv" class="lb-reg-serv">Serviço:</label>
                        <input type="text" name="servico" class="inpt-reg-serv" id="id-serv" value="<?= $result_id['service_id'] ?>" disabled>
                    </p>
                    <p class="p-estilo">
                        <label for="id-viag" class="lb-reg-viag">Viagem:</label>
                        <input type="text" name="viagem" class="inpt-reg-viag" id="id-viag" value="<?= $result_id['trip_short_name'] ?> - <?= $result_id['trip_headsign'] ?>" disabled>
                    </p>
                    <p class="p-estilo">
                        <label for="id-pont" class="lb-reg-pont">Ponto:</label>
                        <input type="text" name="ponto" class="inpt-reg-pont" id="id-pont">
                    </p>
                    <p class="p-estilo">
                        <label for="id-sequ" class="lb-reg-sequ">Sequencia:</label>
                        <input type="text" name="sequencia" class="inpt-reg-sequ" id="id-sequ">
                    </p>
                    <p class="p-estilo">
                        <label for="id-dest" class="lb-reg-dest">Destino:</label>
                        <input type="text" name="destino" class="inpt-reg-dest" id="id-dest">
                    </p>
                    <p class="p-estilo">
                        <label for="id-hrInc" class="lb-reg-hrInc">Hora Inicio:</label>
                        <input type="time" name="hora_inicio" class="inpt-reg-hrInc" id="id-hrInc">
                    </p>
                    <p class="p-estilo">
                        <label for="id-hrFim" class="lb-reg-hrFim">Hora Fim:</label>
                        <input type="time" name="hora_fim" class="inpt-reg-hrFim" id="id-hrFim">
                    </p>
                    <p class="p-estilo">
                        <label for="id-tpHr" class="lb-reg-tpHr">Tipo de Horário:</label>
                        <select name="tipo_hora" class="selc-reg-tpHr" id="id-tpHr">
                            <option value="">Selecione um tipo</option>
                            <option value="2">Meia Viagem</option>
                            <option value="0">Previsto</option>
                            <option value="1">Fixo</option>
                            <option value="3">Recolhe</option>
                        </select>
                    </p>

                    <br>
                    <nav class="nav-reg-btn">
                        <p>
                            <button class="btn-reg-cad">CADASTRAR</button>
                        </p>
                        <p>
                            <button class="btn-reg-canc">
                                <a href="../trips/register.php?id=" class="a-btn-canc">CANCELAR</a>
                            </button>
                        </p>
                    </nav>
                </form>
            </section>

            <!-- Section para listar os horários -->
            <section class="sect-list-hor">
                <br>
                <table>
                    <label> <?= $result_id['trip_short_name'] ?> - <?= $result_id['trip_headsign'] ?> </label>
                    <br><br>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-destino">Destino</th>
                        <th class="th-tipo">Tipo</th>
                        <th class="th-acoes">Ações</th>
                    </thead>
                    <?php
                    // Consulta no banco de dados para exibir na tabela de viagens 
                    $sql = "SELECT time_id, trip_id, TIME_FORMAT(arrival_time, '%H:%i') AS arrival_time, stop_headsign, timepoint,
                            CASE 
                               WHEN stop_times.timepoint = '0' THEN 'P'
                               WHEN stop_times.timepoint = '1' THEN '' 
                               WHEN stop_times.timepoint = '2' THEN 'M' 
                               WHEN stop_times.timepoint = '3' THEN 'R'                    
                            END AS format_timepoint 
                            FROM stop_times WHERE trip_id = $id ORDER BY arrival_time ASC";
                    $result = mysqli_query($conexao, $sql);

                    while ($sql_result = mysqli_fetch_array($result)) {
                        $id = $sql_result['time_id'];
                        $trip_id = $sql_result['trip_id'];
                        $hr_inicio = $sql_result['arrival_time'];
                        $destino = $sql_result['stop_headsign'];
                        $tipo = $sql_result['format_timepoint'];                        
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo $hr_inicio ?></td>
                                <td><?php echo $destino ?></td>
                                <td><?php echo $tipo ?></td>                                
                                <td>
                                    <form action="delete.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $id ?>">  
                                        <input type="hidden" name="trip-id" value="<?php echo $trip_id ?>">                                       
                                        <button class="btn-excluir" onclick="return deletar()">EXCLUIR</button>
                                    </form>
                                </td>
                            </tr>
                        <?php }; ?>
                        </tbody>
                </table>
            </section>

        </main>
        <footer>
            <p><a href="../route/list.php">
                    < Voltar</a>
            </p>
        </footer>
    </div>
</body>

</html>