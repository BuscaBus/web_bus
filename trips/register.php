<?php
//include("../connection.php");

// Declaração da variavel para receber o ID
//$id = $_GET['id'];

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
    <link rel="stylesheet" href="../css/trips.css?v=1.4">
</head>

<body>
    <div>
        <header>
            <h1>Viagens</h1>
        </header>
        <main class="main-cont">
            <section class="sect-reg-viag">
                <h1>Cadastrar viagens</h1>
                <form action="register.php" method="POST" autocomplete="off">                    
                    <p class="p-estilo">
                        <label for="id-nome" class="lb-reg-op">Nome:</label>
                        <input type="text" name="nome" class="inpt-reg-op" id="id-nome" placeholder="insira o nome da operadora..." required>
                    </p>
                    <p class="p-estilo">
                        <label for="id-cid" class="lb-reg-cid">Cidade:</label>
                        <select name="cidade" class="selc-reg-cid" id="id-cid">
                            <option value="select">Selecione uma cidade..</option>
                            <option value="Águas Mornas">Águas Mornas</option>
                            <option value="Antônio Carlos">Antônio Carlos</option>
                            <option value="Biguaçu">Biguaçu</option>
                            <option value="Florianópolis">Florianópolis</option>
                            <option value="Governador Celso Ramos">Governador Celso Ramos</option>
                            <option value="Palhoça">Palhoça</option>
                            <option value="Santo Amaro da Imperatriz">Santo Amaro da Imperatriz</option>
                            <option value="São José">São José</option>
                            <option value="São Pedro de Alcântara">São Pedro de Alcântara</option>
                        </select>
                    </p>
                    <p class="p-estilo">
                        <label for="id-url" class="lb-reg-site">Site:</label>
                        <input type="text" name="url" class="inpt-reg-site" id="id-url" placeholder="insira o site da operadora..." required>
                    </p>
                    <br>
                    <nav class="nav-reg-btn">
                        <p>
                            <Button class="btn-reg-cad">CADASTRAR</Button>
                        </p>
                        <p>
                            <Button class="btn-reg-canc">
                                <a href="list.php" class="a-btn-canc">CANCELAR</a>
                            </Button>
                        </p>
                    </nav>

                </form>
            </section>
            <section class="sect-list-viag">                           
                <table>
                    <caption>Relação de viagens</caption>
                    <thead>
                        <th class="th-op">Operadora</th>
                        <th class="th-cid">Cidade</th>
                        <th class="th-site">Site</th>
                        <th class="th-acoes">Ações</th>
                    </thead>                    
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <form action="delete.php" method ="POST">
                                    <input type="hidden" name="id" value="">
                                    <button class="btn-editar" id="btn-edit">
                                        <a href="edit.php?id=" class="link">EDITAR</a>
                                    </button>                                
                                    <Button class="btn-excluir" onclick="return deletar()">EXCLUIR</Button>
                                </form>
                            </td>
                        </tr>                                            
                    </tbody>
                </table>
            </section>

        </main>
        <footer>
            <p><a href="../route/list.php">< Voltar</a>
            </p>
        </footer>
    </div>
</body>

</html>