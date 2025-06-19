<?php
    include("../connection.php");

   // Consulta no banco de dados
    $sql = "SELECT * FROM agency ORDER BY agency_name ASC";
    $result = mysqli_query($conexao, $sql);
  
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
                    <?php
                        // Laço de repetição para trazer dados do banco
                        while($sql_result = mysqli_fetch_array($result)){
                            $id = $sql_result['agency_id'];
                            $nome = $sql_result['agency_name'];
                            $cidade = $sql_result['agency_city'];
                            $url = $sql_result['agency_url'];                         
                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $cidade ?></td>
                            <td><?php echo $url ?></td>
                            <td>
                                <form action="delete.php" method ="POST">
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                    <button class="btn-editar" id="btn-edit">
                                        <a href="edit.php?id=<?=$sql_result['agency_id']?>" class="link" target="frame">EDITAR</a>
                                    </button>                                
                                    <Button class="btn-excluir">EXCLUIR</Button>
                                </form>
                            </td>
                        </tr> 
                    <?php }; ?>                           
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