<?php

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'web_bus_db';  

    $conectar = new mysqli($host, $user, $pass, $db); 
   
    If($conectar->error){
        die("Falha ao conectar ao banco de dados: ". $conectar->error);
    }

?>