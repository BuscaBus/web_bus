<?php
include("../connection.php");

$filtro_sql = "";
$operadora = "";
$linha = "";
$servico = "";
$result = null;
$nome_saida_ida = "";
$nome_saida_volta = "";
$result_ida = null;
$result_volta = null;

// Só executa se tiver filtro aplicado
if (
    (!empty($_GET['operadora'])) ||
    (!empty($_GET['linha'])) ||
    (!empty($_GET['servico']) && $_GET['servico'] !== "selecionar")
) {
    if (!empty($_GET['operadora'])) {
        $operadora = mysqli_real_escape_string($conexao, $_GET['operadora']);
        $filtro_sql .= " AND a.agency_id = '$operadora'";
    }

    if (!empty($_GET['linha'])) {
        $linha = (int) $_GET['linha'];
        $filtro_sql .= " AND r.route_id = $linha";
    }

    if (!empty($_GET['servico']) && $_GET['servico'] !== "selecionar") {
        $servico = mysqli_real_escape_string($conexao, $_GET['servico']);
        $filtro_sql .= " AND t.service_id = '$servico'";
    }

    // --- consulta para os horários IDA ---
    $sql_ida = "SELECT 
                TIME_FORMAT(st.arrival_time, '%H:%i') AS arrival_time,
                st.stop_headsign,
                t.departure_location
                FROM stop_times st
                INNER JOIN trips t ON st.trip_id = t.trip_id
                INNER JOIN routes r ON t.route_id = r.route_id
                INNER JOIN agency a ON r.agency_id = a.agency_id
                WHERE 1=1 $filtro_sql AND t.direction_id = 'Ida'
                ORDER BY st.arrival_time ASC";

    $result_ida = mysqli_query($conexao, $sql_ida);

    if (!$result_ida) {
        die("Erro na consulta IDA: " . mysqli_error($conexao));
    }

    // --- consulta para os horários VOLTA ---
    $sql_volta = "SELECT 
                TIME_FORMAT(st.arrival_time, '%H:%i') AS arrival_time,
                st.stop_headsign,
                t.departure_location
                FROM stop_times st
                INNER JOIN trips t ON st.trip_id = t.trip_id
                INNER JOIN routes r ON t.route_id = r.route_id
                INNER JOIN agency a ON r.agency_id = a.agency_id
                WHERE 1=1 $filtro_sql AND t.direction_id = 'Volta'
                ORDER BY st.arrival_time ASC";

    $result_volta = mysqli_query($conexao, $sql_volta);

    if (!$result_volta) {
        die("Erro na consulta VOLTA: " . mysqli_error($conexao));
    }

    // --- consulta só para pegar a saída IDA (um único registro) ---
    $sql_saida_ida = "SELECT DISTINCT t.departure_location
                      FROM trips t
                      INNER JOIN routes r ON t.route_id = r.route_id
                      INNER JOIN agency a ON r.agency_id = a.agency_id
                      WHERE 1=1 $filtro_sql AND t.direction_id = 'Ida'
                      LIMIT 1";

    $res_saida_ida = mysqli_query($conexao, $sql_saida_ida);
    if ($res_saida_ida && mysqli_num_rows($res_saida_ida) > 0) {
        $row_s1 = mysqli_fetch_assoc($res_saida_ida);
        $nome_saida_ida = $row_s1['departure_location'];
    }

    // --- consulta só para pegar a saída VOLTA (um único registro) ---
    $sql_saida_volta = "SELECT DISTINCT t.departure_location
                      FROM trips t
                      INNER JOIN routes r ON t.route_id = r.route_id
                      INNER JOIN agency a ON r.agency_id = a.agency_id
                      WHERE 1=1 $filtro_sql AND t.direction_id = 'Volta'
                      LIMIT 1";

    $res_saida_volta = mysqli_query($conexao, $sql_saida_volta);
    if ($res_saida_volta && mysqli_num_rows($res_saida_volta) > 0) {
        $row_s2 = mysqli_fetch_assoc($res_saida_volta);
        $nome_saida_volta = $row_s2['departure_location'];
    }
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
    <link rel="stylesheet" href="../css/queries.css?v=1.3">
</head>

<body>
    <div>
        <header>
            <h1>Tabela de horários</h1>
        </header>
        <main>
            <section class="scroll-area">
                <form method="GET" action="">
                    <label for="id-serv">Dia da Semana</label>
                    <select name="servico" class="selc-serv" id="id-serv" onchange="this.form.submit()">
                        <option value="Segunda a Sexta" <?php if ($servico == "Segunda a Sexta") echo "selected"; ?>>Segunda a Sexta</option>
                        <option value="Sábado" <?php if ($servico == "Sábado") echo "selected"; ?>>Sábado</option>
                        <option value="Domingo e Feriado" <?php if ($servico == "Domingo e Feriado") echo "selected"; ?>>Domingo e Feriado</option>
                    </select>
                    <br><br>
                    <Label>Operadora: </Label>
                    <select name="operadora" id="selc-op" class="selc-op">
                        <option value="">Selecione a operadora</option>
                        <?php
                        $sql_select = "SELECT agency_id, agency_name FROM agency ORDER BY agency_name ASC";
                        $result_selec = mysqli_query($conexao, $sql_select);
                        while ($dados = mysqli_fetch_array($result_selec)) {
                            $id = $dados['agency_id'];
                            $nome = $dados['agency_name'];
                            $selected = (isset($_GET['operadora']) && $_GET['operadora'] == $id) ? 'selected' : '';
                            echo "<option value=\"$id\" $selected>$nome</option>";
                        }
                        ?>
                    </select>
                    <Label>Linha: </Label>
                    <select name="linha" id="selc-linh" class="selc-linh">
                        <option value="">Selecione a linha</option>
                    </select>
                    <button type="submit" class="btn-selec">SELECIONAR</button>
                </form>
                <table>
                    <caption>Saída: <?php echo htmlspecialchars($nome_saida_ida); ?></caption>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-dest">Viagem</th>
                    </thead>
                    <tbody>
                        <?php if ($result_ida): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_ida)): ?>
                                <tr>
                                    <td class="td-hor"><?php echo $row['arrival_time'] ?></td>
                                    <td class="td-dest"><?php echo $row['stop_headsign'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <br>
                <table>
                    <caption>Saída: <?php echo htmlspecialchars($nome_saida_volta); ?></caption>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-dest">Viagem</th>
                    </thead>
                    <tbody>
                        <?php if ($result_volta): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_volta)): ?>
                                <tr>
                                    <td class="td-hor"><?php echo $row['arrival_time'] ?></td>
                                    <td class="td-dest"><?php echo $row['stop_headsign'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
        <footer>
            <p><a href="queries.php">
                    < Voltar</a>
            </p>
        </footer>
    </div>
    <!-- JS para tratar a linha com base na operadora escolhida-->
    <script>
        document.getElementById("selc-op").addEventListener("change", function() {
            let operadoraSelecionada = this.value;
            let selectLinha = document.getElementById("selc-linh");

            if (!operadoraSelecionada) {
                selectLinha.innerHTML = '<option value="">Selecione a linha</option>';
                return;
            }

            fetch("buscar_linha.php?operadora=" + encodeURIComponent(operadoraSelecionada))
                .then(response => response.json())
                .then(data => {
                    selectLinha.innerHTML = '<option value="">Selecione uma linha</option>';
                    data.forEach(function(linha) {
                        let option = document.createElement("option");
                        option.value = linha.id; // agora value é o id
                        option.textContent = linha.nome;
                        selectLinha.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Erro ao buscar Linha:", error);
                });
        });
    </script>
</body>

</html>