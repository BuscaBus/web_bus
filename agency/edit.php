<?php
    include("../connection.php");

    // Declaração da variavel para receber o ID
    $id = $_GET['id'];
    
    // Consulta o ID no banco de dados
    $sql = "SELECT * FROM agency WHERE agency_id = $id";
    $result = mysqli_query($conexao, $sql);

    // Variavel que recebe o ID do banco de dados    
    $result_id = mysqli_fetch_assoc($result);
  
    mysqli_close($conexao);
    
?>
   
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de operadoras</title>
    <link rel="stylesheet" href="../css/agency.css"> 
</head>

<body>
    <section id="section-iframe">
        <h1>Editar operadora</h1>
        <hr>
        <form action="edit_result.php" method="POST" autocomplete="off">
            <input type="hidden" name="id" class="inpt1" id="id-nome" value="<?=$result_id['agency_id']?>">
            <p class="p1">
                <label for="id-nome" class="lb1">Nome:</label>
                <input type="text" name="nome" class="inpt1" id="id-nome" value="<?=$result_id['agency_name']?>">
            </p>
            <p class="p1">
                <label for="id-cid" class="lb2">Cidade:</label>
                <input type="text" name="cidade" class="inpt1" id="id-cid" value="<?=$result_id['agency_city']?>">                     
                </select>                
            </p>    
            <p class="p1">
                <label for="id-url" class="lb3">Site:</label>
                <input type="text" name="url" class="inpt2" id="id-url" value="<?=$result_id['agency_url']?>">
            </p>
            <hr>
            <p>
                <input type="submit" value="EDITAR" class="inpt_btn_edt">                        
            </p>
        </form>
    </section>

</body>

</html>