<?php
    include("../connection.php");
   
    // Código para filtrar após pesquisar no Select
    $filtro_sql = "";
    if($_POST != NULL){
        $filtro = $_POST["pesquisar"];
        $filtro_sql = "WHERE agency_name ='$filtro'";
    }  
    
    // Código para buscar pelo imput 
    if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
        $pesquisa = mysqli_real_escape_string($conexao, $_GET['buscar']);
        $filtro_sql .= " AND routes.route_long_name LIKE '%$pesquisa%'";
    }

   
    // Consulta no banco de dados para exibir na tabela
    $sql = "SELECT 
                agency.agency_name,
                fare_attributes.price,
                FORMAT(fare_attributes.price, 2) AS price_format,
                fare_attributes.route_group,
                routes.route_id,
                routes.route_short_name,
                routes.route_long_name,
                routes.route_desc,                       
                routes.route_status,
                CASE 
                    WHEN routes.route_status = 'A' THEN 'Ativa'
                    WHEN routes.route_status = 'I' THEN 'Inativa'                    
                END AS status_format,
                routes.update_date,
                DATE_FORMAT(routes.update_date, '%d/%m/%Y') AS data_format
            FROM 
                routes
            JOIN 
                agency ON agency.agency_id = routes.agency_id
            JOIN
                fare_attributes ON fare_attributes.fare_id = routes.route_group   
            $filtro_sql ORDER BY routes.route_id ASC";
            $result = mysqli_query($conexao, $sql);        
    
?>

<!--Script para confirmar a exclusão-->
<script>
    function deletar() {
        if(confirm("Deseja exluir essa linha"))
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
    <link rel="stylesheet" href="../css/style.css?v=1.2">
    <link rel="stylesheet" href="../css/route.css?v=1.9">
    <link rel="stylesheet" href="../css/table.css?v=1.0">
</head>

<body>
    <div>
        <header>
            <h1>Linhas</h1>
        </header>
        <main>
            <section class="scroll-area">
                <button class="btn-cadastrar" id="btn-cad">
                    <a href="register.php" class="link">+ CADASTRAR</a>
                </button>
                <br>                  
                <nav class="nav-estilo">
                    <!-- Select no banco de dados para filtrar uma operadora-->
                    <form method="POST" action="list.php">
                        <select name="pesquisar" class="selc-pesq">
                            <option>Operadora</option>;
                            <?php
                                $sql_select = "SELECT * FROM agency ORDER BY agency_name ASC";
                                $result_selec = mysqli_query($conexao, $sql_select);
                                while($dados = mysqli_fetch_array($result_selec)){
                                    $id = $dados['agency_id'];
                                    $operadoras = $dados['agency_name'];
                                    echo "<option value='$operadoras'>$operadoras</option>";
                                }
                            ?>
                        </select>
                        <button type="submit" class="btn-pesq">PESQUISAR</button>
                    </form>
                    <!-- Imput para buscar dados e filtrar por linha -->
                    <form method="GET">
                       <input name="buscar"  class="impt-buscar"  value="<?php if(isset($_GET['buscar'])) echo $_GET['buscar'];?>" placeholder="Nome da linha" type="text">
                       <button type="submit" class="btn-buscar">PESQUISAR</button>
                    </form>
                </nav> 
                <hr> 
                <br>    
                <table>
                    <caption>Relação de linhas</caption>
                    <thead>
                        <th class="th-op">Operadora</th>
                        <th class="th-cod">Código</th>
                        <th class="th-linha">Linha</th>
                        <th class="th-grup">Grupo</th>
                        <th class="th-tarifa">Tarifa</th>
                        <th class="th-status">Status</th>
                        <th class="th-atual">Atualização</th>
                        <th class="th-acoes">Ações</th>
                    </thead>
                    <?php
                        // Laço de repetição para trazer dados do banco
                        while($sql_result = mysqli_fetch_array($result)){
                            $id = $sql_result['route_id'];
                            $id_op = $sql_result['agency_name'];
                            $codigo = $sql_result['route_short_name'];
                            $linha = $sql_result['route_long_name']; 
                            $desc = $sql_result['route_desc']; 
                            $id_tipo = $sql_result['route_group']; 
                            $id_tarifa = $sql_result['price_format']; 
                            $status = $sql_result['status_format'];  
                            $data = $sql_result['data_format'];                              
                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $id_op ?></td>
                            <td><?php echo $codigo ?></td>
                            <td><?php echo $linha ?></td>
                            <td><?php echo $id_tipo ?></td>
                            <td>R$ <?php echo $id_tarifa ?></td>
                            <td><?php echo $status ?></td>
                            <td><?php echo $data ?></td>
                            <td>                                
                                <form action="delete.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                    <a href="../trips/register.php?id=<?=$sql_result['route_id']?>" class="a-viagem" id="a-viag">VIAGENS</a>
                                    <a href="edit.php?id=<?=$sql_result['route_id']?>" class="a-editar" id="a-edit">EDITAR</a>                                    
                                    <button class="btn-excluir" onclick="return deletar()">EXCLUIR</button>
                                </form>
                            </td>
                        </tr> 
                    <?php }; ?>                           
                    </tbody>
                </table>
                <br>                
                <br>
                <!--Consulta no banco de dados a quantidade de registros-->
                <?php
                    $sql = "SELECT COUNT(*) AS total FROM routes";
                    $result = mysqli_query($conexao, $sql);

                     $row = mysqli_fetch_assoc($result);
                     $total_registros = $row['total'];                    
                ?>
                <!-- Mostra a quantidade de registros-->
                <p>Total de linhas cadastradas: <?php echo $total_registros;?></p>                
                <br>                
            </section>
        </main>
        <footer>
            <p><a href="../index.html">< Voltar</a></p>
        </footer>
    </div>
</body>
</html>