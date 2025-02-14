<?php

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'web_bus_bd'; 

    $conexao = mysqli_connect($host, $user, $pass, $db); 
   
    If($conexao->error){
        die("Falha ao conectar ao banco de dados: ".mysqli_connect_errno());
    }

?>