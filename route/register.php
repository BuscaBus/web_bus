<?php
    include("../connection.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de linhas</title>
    <link rel="stylesheet" href="../css/route.css?v=1.2">
    <style>
        .p1 {
            text-align: center;
        }        
    </style>
</head>

<body>
    <section id="section-iframe">
        <h1>Cadastrar linha</h1>
        <form action="result_register.php" method="POST" autocomplete="off">
            <hr>
            <p  class="p1">
                <label for="id-grp" class="lb-op">Operadora:</label>
                <select name="operadora" id="id-grp" class="selc">
                    <option>Selecione uma operadora</option>;
                    <?php
                        $sql_select = "SELECT agency_id, agency_name FROM agency ORDER BY agency_name ASC";
                        $result_selec = mysqli_query($conexao, $sql_select);

                        while($dados = mysqli_fetch_array($result_selec)){
                            $id = $dados['agency_id']; 
                            $operadoras = $dados['agency_name'];                            
                            echo "<option value='$id'>$operadoras</option>";
                            }
                    ?>       
                </select>
            </p>      
            <p class="p1">
                <label for="id-cod" class="lb-cod">Código:</label>
                <input type="text" name="codigo" class="inpt-cod" id="id-cod" placeholder="insira o código da linha..." required>
            </p>
            <p class="p1">
                <label for="id-linha" class="lb-linha">Linha:</label>
                <input type="text" name="linha" class="inpt-linha" id="id-linha" placeholder="insira o nome da linha..." required>
            </p>
            <p class="p1">
                <label for="id-desc" class="lb-desc">Descrição:</label>
                <textarea name="descricao" id="id-desc" class="tx_area1" placeholder="insira uma descrição..."></textarea>
            </p>
            <p class="p1">
                <label for="id-grp" class="lb-grupo">Grupo:</label>
                <select name="tipo" id="id-grp" class="selc">
                    <option>Selecione um grupo de linha</option>;
                    <?php
                        $sql_select = "SELECT fare_id, route_group FROM fare_attributes ORDER BY route_group ASC";
                        $result_selec = mysqli_query($conexao, $sql_select);

                        while($dados = mysqli_fetch_array($result_selec)){
                            $id = $dados['fare_id']; 
                            $tipo = $dados['route_group'];                            
                            echo "<option value='$id'>$tipo</option>";
                            }
                    ?>       
                </select>                
            </p>            
            <p class="p1">
                <label for="id-tarifa" class="lb-tarifa">Tarifa:</label>
                <select name="tarifa" id="id-tarifa" class="selc">
                    <option>Selecione uma tarifa</option>;
                    <?php
                        $sql_select = "SELECT fare_id, price, FORMAT(fare_attributes.price, 2) AS price_format FROM fare_attributes ORDER BY price ASC";
                        $result_selec = mysqli_query($conexao, $sql_select);

                        while($dados = mysqli_fetch_array($result_selec)){
                            $id = $dados['fare_id']; 
                            $tarifa = $dados['price_format'];                            
                            echo "<option value='$tarifa'>R$ $tarifa</option>";
                            }
                        ?>       
                </select>
            </p>
            <hr>
            <p>
                <Button class="inpt_btn_reg">CADASTRAR</Button>  
            </p>

        </form>
    </section>

</body>

</html>