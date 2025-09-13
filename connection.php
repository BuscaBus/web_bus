<?php

    $server = "localhost";
    $user = "root";
    $password = "";
    $dbname = "web_bus_bd_v2"; 
    
    // criar conexão 
    $conexao = mysqli_connect($server, $user, $password, $dbname);

    // verificar conexão
    if ($conexao->connect_errno) {
       echo "Conexão falhou: (" . $conexao->connect_errno . ")" . $conexao->connect_errno;
    }
    
?>