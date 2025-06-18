<?php
    include("../connection.php");

    // Declaração das variaveis
    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];
    $url = $_POST['url'];

    // Consulta no banco de dados
    $sql = "INSERT INTO agency(agency_name, agency_city, agency_url) VALUES ('$nome', '$cidade', '$url')";

    if(mysqli_query($conexao, $sql)){
       echo "Operadora cadastrada com sucesso";        
    }
    else{
       echo "Erro ao cadastrar".mysqli_connect_error($conexao);
    }
    
   mysqli_close($conexao);
    
?>
    

    