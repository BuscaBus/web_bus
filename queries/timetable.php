<?php
include("../connection.php");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema WebBus</title>
    <link rel="shortcut icon" href="../img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css?v=1.2">
    <link rel="stylesheet" href="../css/table.css?v=1.0">
    <link rel="stylesheet" href="../css/queries.css?v=1.0">
</head>

<body>
    <div>
        <header>
            <h1>Tabela de horários</h1>
        </header>
        <main>
            <section class="scroll-area">
                <form action="GET">                    
                    <Label>Operadora: </Label>
                    <select name="terminal" id="selc-term" class="selc-term">
                        <option value="">Selecione a operadora</option>                        
                    </select>
                    <Label>Linha: </Label>
                    <select name="box" id="selc-box" class="selc-box">
                        <option value="">Selecione a linha</option>
                    </select>
                    <button type="submit" class="btn-selec">SELECIONAR</button>
                </form>
                <label for="id-serv">Dia da Semana</label>
                <br>
                <select name="servico" class="selc-serv" id="id-serv">
                    <option value="Segunda a Sexta">Segunda a Sexta</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo e Feriado">Domingo e Feriado</option>
                </select>
                <br><br>
                <table>
                    <caption>Saída: 1</caption>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-dest">Destino</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-hor"></td>
                            <td class="td-dest"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table>
                    <caption>Saída: 2</caption>
                    <thead>
                        <th class="th-hor">Horário</th>
                        <th class="th-dest">Destino</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-hor"></td>
                            <td class="td-dest"></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
        <footer>
            <p><a href="queries.php">
                    < Voltar</a>
            </p>
        </footer>
    </div>

</body>

</html>