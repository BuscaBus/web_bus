<?php
    include("../connection.php");
   
    // Código para filtrar após pesquisar
    $filtro_sql = "";
    if($_POST != NULL){
        $filtro = $_POST["pesquisar"];
        $filtro_sql = "WHERE route_group ='$filtro'";
    }    

    // Paginação
    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1; //Verificar se está passando na URL a página

    $sql_itens = "SELECT * FROM fare_attributes";
    $result_itens = mysqli_query($conexao, $sql_itens);
    $total_itens = mysqli_num_rows($result_itens); // Contar total de operadoras
    $quant_paginas = 10; // Setar quantidade de itens por página
    $num_pagina = ceil($total_itens/$quant_paginas); // Calcula o numero de páginas necessárias
    $inicio = ($quant_paginas*$pagina)-$quant_paginas; // Calcula o inicio da visualização

    // Consulta no banco de dados para exibir na tabela
    $sql = "SELECT *, FORMAT(price, 2) AS price_format, DATE_FORMAT(update_date, '%d/%m/%Y') AS data_format FROM fare_attributes $filtro_sql LIMIT $inicio, $quant_paginas";
    $result = mysqli_query($conexao, $sql);
    
    $total_itens = mysqli_num_rows($result_itens);   
    
    ?>

<!--Script para confirmar a exclusão-->
<script>
    function deletar() {
        if(confirm("Deseja exluir esse item?"))
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/fare_attributes.css?v=1.2">    
</head>

<body>
    <div>
        <hearder>
            <h1>Tarifas</h1>
        </hearder>
        <main>
            <section>
                <button class="btn-cadastrar" id="btn-cad">
                    <a href="register.html" class="link">+ CADASTRAR</a>
                </button>
                <br> 
                <!-- Select no banco de dados para filtrar uma operadora--> 
                <form method="POST" action="list.php">
                    <select name="pesquisar" class="selc2">
                        <option>Selecione um tipo de tarifa</option>;
                        <?php
                            $sql_select = "SELECT * FROM fare_attributes";
                            $result_selec = mysqli_query($conexao, $sql_select);

                            while($dados = mysqli_fetch_array($result_selec)){
                                $id = $dados['route_group']; 
                                $tipo = $dados['route_group'];                            
                                echo "<option value='$tipo'>$tipo</option>";
                            }
                        ?>                          
                    </select> 
                    <button type="submit" class="btn-pesq">PESQUISAR</button> 
                </form>                 
                <table>
                    <caption>Relação de tarifas vigentes</caption>
                    <thead>
                        <th class="th1">Código</th>
                        <th class="th2">Tarifa</th>
                        <th class="th3">Tipo</th>
                        <th class="th4">Atualização</th>
                        <th class="th5">Ações</th>
                    </thead>
                    <?php
                        // Laço de repetição para trazer dados do banco
                        while($sql_result = mysqli_fetch_array($result)){
                            $id = $sql_result['fare_id'];
                            $preco = $sql_result['price_format'];
                            $grupo = $sql_result['route_group'];
                            $data = $sql_result['data_format'];                         
                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $id ?></td>
                            <td><?php echo $preco ?></td>
                            <td><?php echo $grupo ?></td>
                            <td><?php echo $data ?></td>
                            <td>
                                <form action="delete.php" method ="POST">
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                    <button class="btn-editar" id="btn-edit">
                                        <a href="edit.php?id=<?=$sql_result['fare_id']?>" class="link">EDITAR</a>
                                    </button>                                
                                    <Button class="btn-excluir" onclick="return deletar()">EXCLUIR</Button>
                                </form>
                            </td>
                        </tr> 
                    <?php }; ?>                           
                    </tbody>
                </table>
                <br>                
                <?php
                    // Verificar pagina anterior e posterior
                    $pagina_ant = $pagina - 1;
                    $pagina_post = $pagina + 1;
                ?>
                 <!-- Navegação da páginação-->
                <nav class="nav-pag" aria-label="Page navigation example">
                    <ul class="paginacao">                       
                        <?php
                            if($pagina_ant != 0){ ?>
                                <a class="nav-pag" href="list.php?pagina=<?php echo $pagina_ant; ?>"> << </a>
                        <?php } else{?>
                            <span> << </span>
                        <?php } ?>  
                        <?php
                            for($i = 1; $i < $num_pagina + 1; $i++){ ?>                               
                                <a class="nav-pag" href="list.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>  
                        <?php
                            if($pagina_post <= $num_pagina){ ?>
                                <a class="nav-pag" href="list.php?pagina=<?php echo $pagina_post; ?>"> >> </a>
                        <?php } else{?>
                            <span> >> </span>
                        <?php } ?>                                        
                    </ul>
                </nav>                 
                <br>
                <!--Consulta no banco de dados a quantidade de registros-->
                <?php
                    $sql = "SELECT COUNT(*) AS total FROM fare_attributes";
                    $result = mysqli_query($conexao, $sql);

                     $row = mysqli_fetch_assoc($result);
                     $total_registros = $row['total'];                    
                ?>
                <!-- Mostra a quantidade de registros-->
                <p>Total de tarifas cadastradas: <?php echo $total_registros;?></p>
                <br>                            
            </section>
        </main>
        <footer>
            <p><a href="../index.html">< Voltar</a></p>
        </footer>
    </div>
</body>
</html>