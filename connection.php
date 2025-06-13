<?php

    $server = "localhost";
    $user = "root";
    $password = "";
    $dbname = "web_bus_bd"; 
    
    // criar conexão 
    $conexao = new mysqli($server, $user, $password, $dbname);

    // verificar conexão
    if ($conexao->connect_errno) {
       echo "Conexão falhou: (" . $conexao->connect_errno . ")" . $conexao->connect_errno;
    }
    else
       echo "Conexão bem-sucedida!";
     
    // fechar conexão
    $conexao->close();

?>