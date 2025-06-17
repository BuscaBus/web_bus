<?php
    include("../connection.php");

    $sql = "SELECT agency_name, agency_city, agency_url FROM agency";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result)){
        
    }


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema WebBus</title>
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/agency.css">    
</head>

<body>
    <div>
        <hearder>
            <h1>Operadoras</h1>
        </hearder>
        <main>
            <section>
                <button class="btn-cadastrar" id="btn-cad">
                    <a href="register.html" class="link" target="frame">+ CADASTRAR</a>
                </button>
        
                <table>
                    <caption>Relação de operadoras</caption>
                    <thead>
                        <th class="th1">Operadora</th>
                        <th class="th2">Cidade</th>
                        <th class="th3">Site</th>
                        <th class="th4">Ações</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Consórcio Fênix</td>
                            <td>Florianópolis</td>
                            <td>https://www.consorciofenix.com.br/</td>
                            <td>
                                <button class="btn-editar" id="btn-edit">
                                    <a href="edit.html" class="link" target="frame">EDITAR</a>
                                </button>
                                <Button class="btn-excluir">EXCLUIR</Button>
                            </td>
                        </tr>
                        <tr>
                            <td>Biguaçu</td>
                            <td>Biguaçu</td>
                            <td>https://www.tcbiguacu.com.br/</td>
                            <td>
                                <button class="btn-editar" id="btn-edit">
                                    <a href="edit.html" class="link" target="frame">EDITAR</a>
                                </button>
                                <Button class="btn-excluir">EXCLUIR</Button>
                            </td>
                        </tr>
                        <tr>
                            <td>Estrela</td>
                            <td>Florianópolis</td>
                            <td>https://insulartc.com.br/est/wp/</td>
                            <td>
                                <button class="btn-editar" id="btn-edit">
                                    <a href="edit.html" class="link" target="frame">EDITAR</a>
                                </button>
                                <Button class="btn-excluir">EXCLUIR</Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <p>Total de operadoras cadastradas: 00</p>
                <br>
                <dialog>
                    <iframe src="register.html" name="frame"></iframe>
                    <br>
                    <button class="btn-fechar">FECHAR</button>
                </dialog>
            </section>
        </main>
        <footer>
            <p><a href="../index.html">< Voltar</a></p>
        </footer>
    </div>
</body>
<script src="../js/modal-agency.js"></script>
</html>