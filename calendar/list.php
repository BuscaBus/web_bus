<?php
    include("../connection.php");

     // Consulta no banco de dados para exibir na tabela
    $sql = "SELECT *, DATE_FORMAT(start_date, '%d/%m/%Y') AS data_for_start, DATE_FORMAT(end_date, '%d/%m/%Y') AS data_for_end  FROM calendar";
    $result = mysqli_query($conexao, $sql);
    
    // $total_itens = mysqli_num_rows($result_itens);   
    
    ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema WebBus</title>
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/table.css?v=1.0">
    <link rel="stylesheet" href="../css/calendar.css?v=1.0">    
</head>

<body>
    <div>
        <hearder>
            <h1>Calendários</h1>
        </hearder>
        <main>
            <section>
                <button class="btn-cadastrar" id="btn-cad">
                    <a href="register.html" class="link" target="frame">+ CADASTRAR</a>
                </button>
        
                <table>
                    <caption>Relação de calendários</caption>
                    <thead>
                        <th class="th1">Serviço</th>
                        <th class="th4">Seg</th>
                        <th class="th5">Ter</th>
                        <th class="th6">Qua</th>
                        <th class="th7">Qui</th>
                        <th class="th8">Sex</th>
                        <th class="th9">Sab</th>
                        <th class="th10">Dom</th>
                        <th class="th2">Inicio</th>
                        <th class="th3">Término</th>
                        <th class="th11">Ações</th>
                    </thead>
                    <?php
                        // Laço de repetição para trazer dados do banco
                        while($sql_result = mysqli_fetch_array($result)){
                            $id = $sql_result['service_id'];
                            $seg = $sql_result['monday'];
                            $ter = $sql_result['tuesday'];
                            $qua = $sql_result['wednesday'];
                            $qui = $sql_result['thursday']; 
                            $sex = $sql_result['friday'];                          
                            $sab = $sql_result['saturday'];  
                            $dom = $sql_result['sunday'];
                            $data_inicio = $sql_result['data_for_start'];  
                            $data_fim = $sql_result['data_for_end'];    
                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $id ?></td>
                            <td><?php echo $seg ?></td>
                            <td><?php echo $ter ?></td>
                            <td><?php echo $qua ?></td>
                            <td><?php echo $qui ?></td>
                            <td><?php echo $sex ?> </td>
                            <td><?php echo $sab ?></td>
                            <td><?php echo $dom ?></td>
                            <td><?php echo $data_inicio ?></td>
                            <td><?php echo $data_fim ?></td>
                            <td>
                                <button class="btn-editar" id="btn-edit">
                                    <a href="edit.html" class="link" target="frame">EDITAR</a>
                                </button>
                                <Button class="btn-excluir">EXCLUIR</Button>
                            </td>
                        </tr> 
                        <?php }; ?>                       
                    </tbody>
                </table>
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
<script src="../js/modal-calendar.js"></script>
</html>