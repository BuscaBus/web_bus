<?php
include("../connection.php");

$filtro_sql = "";
$operadora = "";
$linha = "";
$servico = "";
$result = null;

// Só executa se tiver filtro aplicado
if (
    (isset($_GET['operadora']) && !empty($_GET['operadora'])) ||
    (isset($_GET['linha']) && !empty($_GET['linha'])) ||
    (isset($_GET['servico']) && !empty($_GET['servico']) && $_GET['servico'] !== "selecionar")
) {
    // Filtro por operadora
    if (!empty($_GET['operadora'])) {
        $operadora = mysqli_real_escape_string($conexao, $_GET['operadora']);
        $filtro_sql .= " AND a.agency_name = '$operadora'";
    }

    // Filtro por linha
    if (!empty($_GET['linha'])) {
        $linha = mysqli_real_escape_string($conexao, $_GET['linha']);
        $filtro_sql .= " AND r.route_short_name = '$linha'";
    }

    // Filtro por serviço
    if (!empty($_GET['servico']) && $_GET['servico'] !== "selecionar") {
        $servico = mysqli_real_escape_string($conexao, $_GET['servico']);
        $filtro_sql .= " AND t.service_id = '$servico'";
    }

    // Consulta principal
    $sql = "SELECT 
                t.departure_location,
                st.arrival_time,
                st.stop_headsign,
                r.route_long_name
            FROM stop_times st
            INNER JOIN trips t ON st.trip_id = t.trip_id
            INNER JOIN routes r ON t.route_id = r.route_id
            INNER JOIN agency a ON r.agency_id = a.agency_id
            WHERE 1=1 $filtro_sql
            ORDER BY st.arrival_time ASC";

    $result = mysqli_query($conexao, $sql);
}

$nome_linha = "";

if ($result && mysqli_num_rows($result) > 0) {
    $row_first = mysqli_fetch_assoc($result);
    $nome_linha = $row_first['route_long_name'];
    // reposiciona o ponteiro para poder usar o while depois
    mysqli_data_seek($result, 0);
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
    <link rel="stylesheet" href="../css/queries.css?v=1.1">
</head>

<body>
    <div>
        <header>
            <h1>Tabela de horários</h1>
        </header>
        <main>
            <section class="scroll-area">
                <form action="GET">
                    <Label>Operadora: </Label>
                    <select name="operadora" id="selc-op" class="selc-op">
                        <option value="">Selecione a operadora</option>
                        <?php
                        $sql_select = "SELECT agency_id, agency_name FROM agency ORDER BY agency_name ASC";
                        $result_selec = mysqli_query($conexao, $sql_select);
                        while ($dados = mysqli_fetch_array($result_selec)) {
                            $id = $dados['agency_id'];
                            $operadora = $dados['agency_name'];
                            echo "<option value=\"$operadora\">$operadora</option>";
                        }
                        ?>
                    </select>
                    <Label>Linha: </Label>
                    <select name="linha" id="selc-linh" class="selc-linh">
                        <option value="">Selecione a linha</option>
                    </select>
                    <button type="submit" class="btn-selec">SELECIONAR</button>
                </form>
                <label for="id-serv">Dia da Semana</label>
                <br>
                <select name="servico" class="selc-serv" id="id-serv" onchange="this.form.submit()">
                        <option value="Segunda a Sexta" <?php if ($servico == "Segunda a Sexta") echo "selected"; ?>>Segunda a Sexta</option>
                        <option value="Sábado" <?php if ($servico == "Sábado") echo "selected"; ?>>Sábado</option>
                        <option value="Domingo e Feriado" <?php if ($servico == "Domingo e Feriado") echo "selected"; ?>>Domingo e Feriado</option>
                    </select>
                <br><br>
                <table>
                    <caption>Linha: <?php echo htmlspecialchars($nome_linha); ?></caption>
                    <caption>Saída: 1</caption>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-dest">Destino</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-hor"></td>
                            <td class="td-dest"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table>
                    <caption>Saída: 2</caption>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-dest">Destino</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-hor"></td>
                            <td class="td-dest"></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
        <footer>
            <p><a href="queries.php">< Voltar</a>
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
                        option.value = linha.nome; // <-- salva o nome da linha
                        option.textContent = linha.nome;
                        selectLinha.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Erro ao buscar linha:", error);
                });
        });
    </script>
</body>

</html>