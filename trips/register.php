<?php
include("../connection.php");

// Declaração da variavel para receber o ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erro: ID não informado ou inválido.");
}

$id = (int) $_GET['id'];

// Consulta o ID no banco de dados
$sql = "SELECT * FROM routes WHERE route_id = $id";
$result = mysqli_query($conexao, $sql);

// Variavel que recebe o ID do banco de dados    
$result_id = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema WebBus</title>
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css?v=1.1">
    <link rel="stylesheet" href="../css/table.css?v=1.0">
    <link rel="stylesheet" href="../css/trips.css?v=1.6">
</head>

<body>
    <div>
        <header>
            <h1>Viagens</h1>
        </header>
        <main class="main-cont">
            <section class="sect-reg-viag">
                <h1 class="h1-cad-vig">Cadastrar viagens</h1>
                <form action="result_register.php" method="POST" autocomplete="off">
                    <input type="hidden" name="id" class="inpt1" id="id-nome" value="<?= $result_id['route_id'] ?>">
                    <p class="p-estilo">
                        <label for="id-cod" class="lb-reg-cod">Código:</label>
                        <input type="text" name="codigo" class="inpt-reg-cod" id="id-cod" value="<?= $result_id['route_short_name'] ?>">
                    </p>
                    <p class="p-estilo">
                        <label for="id-linha" class="lb-reg-linha">Linha:</label>
                        <input type="text" name="linha" class="inpt-reg-linha" id="id-linha" value="<?= $result_id['route_long_name'] ?>">
                    </p>
                    <p class="p-estilo">
                        <label for="id-serv" class="lb-reg-serv">Serviço:</label>
                        <select name="servico" class="selc-reg-serv" id="id-serv">
                            <?php
                            $sql_select = "SELECT service_id FROM calendar ORDER BY service_id DESC";
                            $result_selec = mysqli_query($conexao, $sql_select);

                            while ($dados = mysqli_fetch_array($result_selec)) {
                                $servicos = $dados['service_id'];
                                $selected = ($servicos == $result_id['service_id']) ? 'selected' : '';
                                echo "<option value='$servicos' $selected>$servicos</option>";
                            }
                            ?>
                        </select>
                    </p>
                    <p class="p-estilo">
                        <label for="id-orig" class="lb-reg-orig">Origem:</label>
                        <input type="text" name="origem" class="inpt-reg-orig" id="id-org">
                    </p>
                    <p class="p-estilo">
                        <label for="id-dest" class="lb-reg-dest">Destino:</label>
                        <input type="text" name="destino" class="inpt-reg-dest" id="id-dest">
                    </p>
                    <p class="p-estilo">
                        <label for="id-sent" class="lb-reg-sent">Sentido:</label>
                        <select name="sentido" class="selc-reg-sent" id="id-sent">
                            <option value="Ida">Ida</option>
                            <option value="Volta">Volta</option>
                        </select>
                    </p>
                    <p class="p-estilo">
                        <label for="id-part" class="lb-reg-part">Local de Partida:</label>
                        <input type="text" name="partida" class="inpt-reg-part" id="id-part">
                    </p>
                    <br>
                    <nav class="nav-reg-btn">
                        <p>
                            <button class="btn-reg-cad">CADASTRAR</button>
                        </p>
                        <p>
                            <button class="btn-reg-canc">
                                <a href="../route/list.php" class="a-btn-canc">CANCELAR</a>
                            </button>
                        </p>
                    </nav>                    
                </form>
            </section>
            <section class="sect-list-viag">
                <br>
                <table>
                    <caption class="cap-list-vig">Relação de viagens</caption>
                    <thead>
                        <th class="th-viag">Viagem</th>
                        <th class="th-sent">Sentido</th>
                        <th class="th-part">Partida</th>
                        <th class="th-acoes">Ações</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <form action="delete.php" method="POST">
                                    <!--<input type="hidden" name="id" value="">-->
                                    <button class="btn-editar" id="btn-edit">
                                        <!--<a href="edit.php?id=" class="link">EDITAR</a>-->
                                    </button>
                                    <button class="btn-excluir" onclick="return deletar()">EXCLUIR</button>
                                </form>
                            </td>
                        </tr>
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