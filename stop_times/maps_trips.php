<?php
include("../connection.php");

// Declaração da variavel para receber o ID
$id = $_GET['id'];

// Consulta no banco de dados para exibir no nome da viagem 
$sql_viag = "SELECT route_id, trip_headsign, trip_short_name FROM trips WHERE trip_id = $id";
$result_viag = mysqli_query($conexao, $sql_viag);

// Laço de repetição para trazer no nome da viagem do banco
while ($sql_result_viag = mysqli_fetch_array($result_viag)) {
    $route_id = $sql_result_viag['route_id'];
    $destino = $sql_result_viag['trip_headsign'];
    $origem = $sql_result_viag['trip_short_name'];
}

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
    <link rel="stylesheet" href="../css/stop_times.css?v=1.5">

    <!-- CSS do Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- CSS do Leaflet.draw -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    <!-- JS do Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- JS do Leaflet.draw -->
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

</head>

<body>
    <div>
        <header>
            <h1>Mapa</h1>
        </header>
        <main>
            <section>
                <h3>Viagem: <?php echo $origem; ?> - <?php echo $destino; ?> </h3>
                <br>
                <hr>
                <!-- Mapa -->
                <div id="div-map"></div>
                <script>
                    // Cria o mapa
                    var map = L.map('div-map').setView([-27.595740, -48.568228], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(map);

                    // Grupo de layers desenhados
                    var drawnItems = new L.FeatureGroup();
                    map.addLayer(drawnItems);

                    // Controle de desenho com editar/excluir habilitado
                    var drawControl = new L.Control.Draw({
                        edit: {
                            featureGroup: drawnItems,
                            remove: true // habilita botão de excluir
                        },
                        draw: {
                            polyline: {
                                shapeOptions: {
                                    color: '#0000ff',
                                    weight: 5,
                                    opacity: 0.8
                                }
                            },
                            polygon: false,
                            marker: false,
                            rectangle: false,
                            circle: false,
                            circlemarker: false
                        }
                    });
                    map.addControl(drawControl);

                    // Função para salvar linha no servidor
                    function salvarShape(layer) {
                        var geojson = layer.toGeoJSON();
                        if (geojson.geometry.type === "LineString") {
                            var coords = geojson.geometry.coordinates; // [lon, lat]

                            fetch("salvar_shape.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        trip_id: <?= $id ?>,
                                        coords: coords
                                    })
                                })
                                .then(res => res.text())
                                .then(data => {
                                    alert(data);
                                });
                        }
                    }

                    // Quando criar novo traçado
                    map.on(L.Draw.Event.CREATED, function(event) {
                        drawnItems.clearLayers(); // só permite um traçado por trip
                        var layer = event.layer;
                        drawnItems.addLayer(layer);
                        salvarShape(layer);
                    });

                    // Quando editar traçado
                    map.on(L.Draw.Event.EDITED, function(event) {
                        event.layers.eachLayer(function(layer) {
                            salvarShape(layer);
                        });
                    });

                    // Quando excluir traçado
                    map.on(L.Draw.Event.DELETED, function(event) {
                        fetch("salvar_shape.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    trip_id: <?= $id ?>,
                                    coords: [] // vazio = remover shape
                                })
                            })
                            .then(res => res.text())
                            .then(data => {
                                alert(data);
                            });
                    });

                    // Carregar traçado salvo
                    var tripId = <?= $id ?>;
                    fetch("get_shape.php?trip_id=" + tripId)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                var polyline = L.polyline(data, {
                                    color: "#0000ff",
                                    weight: 5,
                                    opacity: 0.8
                                }).addTo(drawnItems);
                                map.fitBounds(polyline.getBounds());
                            }
                        });
                </script>

            </section>
        </main>
        <footer>
            <p><a href="../trips/register.php?id=<?= $route_id ?>">
                    < Voltar</a>
            </p>
        </footer>
    </div>
</body>

</html>