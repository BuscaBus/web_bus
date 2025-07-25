<?php
include("../connection.php");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar pontos</title>
    <link rel="stylesheet" href="../css/stops.css?v=1.1">
</head>

<body>
    <section>
        <h1>Editar pontos</h1>
        <form action="result_register.php" method="POST" autocomplete="off">
            <hr>
            <p class="p-estilo">
                <label for="id-cod" class="lb-edt-cod">Código:</label>
                <input type="text" name="codigo" class="inpt-edt-cod" id="id-cod">
            </p>
            <p class="p-estilo">
                <label for="id-pont" class="lb-edt-pont">Ponto:</label>
                <input type="text" name="ponto" class="inpt-edt-pont" id="id-pont">
            </p>
            <p class="p-estilo">
                <label for="id-cid" class="lb-edt-cid">Cidade:</label>
                <input type="text" name="cidade" class="inpt-edt-cid" id="id-cid">
            </p>
            <p class="p-estilo">
                <label for="id-bair" class="lb-edt-bair">Bairro:</label>
                <input type="text" name="bairro" class="inpt-edt-bair" id="id-bair">
            </p>   
            <p class="p-estilo">
                <label for="id-loc" class="lb-edt-loc">Tipo de Local:</label>
                <input type="text" name="local" class="inpt-edt-loc" id="id-loc">
            </p>
            <p class="p-estilo">
                <label for="id-term" class="lb-edt-term">Terminal:</label>
                <input type="text" name="terminal" class="inpt-edt-term" id="id-term">
            </p>            
            <p class="p-estilo">
                <label for="id-box" class="lb-reg-box">Box:</label>
                <input type="text" name="box" class="inpt-reg-box" id="id-box">
            </p>
            <hr>
            <nav class="nav-reg-btn">
                <p>
                    <Button class="btn-reg-cad">CADASTRAR</Button>
                </p>
                <p>
                    <Button class="btn-reg-canc">
                        <a href="list.php" class="a-btn-canc">CANCELAR</a>
                    </Button>
                </p>
            </nav>

        </form>
    </section>
    <!-- JS para tratar o bairro com base na cidade escolhida-->
    <script>
        document.getElementById("selc-cid").addEventListener("change", function() {
            let cidadeSelecionada = this.value;
            let selectBairro = document.getElementById("selc-bair");

            if (!cidadeSelecionada) {
                selectBairro.innerHTML = '<option value="">Selecione um bairro</option>';
                return;
            }

            fetch("buscar_bairros.php?cidade=" + encodeURIComponent(cidadeSelecionada))
                .then(response => response.json())
                .then(data => {
                    selectBairro.innerHTML = '<option value="">Selecione um bairro</option>';
                    data.forEach(function(bairro) {
                        let option = document.createElement("option");
                        option.value = bairro.nome; // <-- salva o nome do bairro
                        option.textContent = bairro.nome;
                        selectBairro.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Erro ao buscar bairros:", error);
                });
        });
    </script>

</body>

</html>