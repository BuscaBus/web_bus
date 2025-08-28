<?php
include("../connection.php");

$filtro_sql = "";
$pesquisa = "";

// Código para buscar pelo input 
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $pesquisa = mysqli_real_escape_string($conexao, $_GET['buscar']);
    $filtro_sql .= " WHERE stop_times.stop_code LIKE '%$pesquisa%'";
}

// Consulta no banco de dados para exibir os Horários na tabela
$sql = "SELECT 
            r.route_short_name, st.stop_headsign, TIME_FORMAT(st.arrival_time, '%H:%i') AS arrival_time          
        FROM stop_times st
        INNER JOIN trips t 
        ON st.trip_id = t.trip_id
        INNER JOIN routes r 
        ON t.route_id = r.route_id
        WHERE stop_code = '$pesquisa'";
$result = mysqli_query($conexao, $sql);

// Consulta no banco de dados para exibir o nome do ponto
$sql_pt = "SELECT tp.stop_name 
           FROM stop_times st
           INNER JOIN stops tp
           ON tp.stop_code = st.stop_code
           WHERE st.stop_code = '$pesquisa'";
$result_pt = mysqli_query($conexao, $sql_pt);

    // Buscando o nome do ponto no banco de dados
    if ($row_pt = mysqli_fetch_assoc($result_pt)) {
        $nome_pt = $row_pt['stop_name'];
    } else {
        $nome_pt = "Ponto não encontrado";
    }
          

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema WebBus</title>
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css?v=1.2">
    <link rel="stylesheet" href="../css/table.css?v=1.0">
    <link rel="stylesheet" href="../css/queries.css?v=1.4">
</head>

<body>
    <div>
        <header>
            <h1>Consultas</h1>
        </header>
        <main>
            <section class="scroll-area">                
                <br>
                <!-- Imput para buscar dados e filtrar -->
                <form method="GET">
                    <input name="buscar" class="impt-buscar" value="<?php if (isset($_GET['buscar'])) echo $_GET['buscar']; ?>" placeholder="Pesquise por código" type="text">
                    <button type="submit" class="btn-buscar">PESQUISAR</button>
                </form>
                <table>
                    <caption><?php echo $nome_pt ?></caption>
                    <thead>
                        <th class="th-linh">Linha</th>
                        <th class="th-dest">Destino</th>
                        <th class="th-hor">Horário</th>                        
                    </thead>
                    <?php
                    // Laço de repetição para trazer dados do banco
                    while ($sql_result = mysqli_fetch_array($result)) {
                        $linha = $sql_result['route_short_name'];
                        $destino = $sql_result['stop_headsign'];
                        $horario = $sql_result['arrival_time'];                        
                    ?>
                        <tbody>
                            <tr>
                                <td class="td-linh"><?php echo $linha ?></td>
                                <td class="td-dest"><?php echo $destino ?></td>
                                <td class="td-hor"><?php echo $horario ?></td>                                
                            </tr>
                        <?php }; ?>
                        </tbody>
                </table>
                <br>                                
            </section>
        </main>
        <footer>
            <p><a href="../index.html">< Voltar</a>
            </p>
        </footer>
    </div>
</body>
</html>