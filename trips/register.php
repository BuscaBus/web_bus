<?php
    include("../connection.php");

   // Declaração da variavel para receber o ID
    $id = $_GET['id'];     
   

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
    <link rel="stylesheet" href="../css/trips.css?v=1.1">    
</head>

<body>
    <div>
        <hearder>
            <h1>Viagens</h1>
        </hearder>
        <main>
            <section class="sect1">       
                <?php
                    echo $id;
                ?>
            </section>
            <section class="sect2">
                 <?php
                    echo $id;
                ?>            
            </section>
                     
        </main>
        <footer>
            <p><a href="../route/list.php">< Voltar</a></p>
        </footer>
    </div>
</body>
<script src="../js/modal-calendar.js"></script>
</html>