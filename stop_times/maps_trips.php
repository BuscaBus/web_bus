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
    <link rel="stylesheet" href="../css/stop_times.css?v=1.4">

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

                    // Controle de desenho
                    var drawControl = new L.Control.Draw({
                        edit: {
                            featureGroup: drawnItems
                        },
                        draw: {
                            polyline: {
                                shapeOptions: {
                                    color: '#000000', // cor da linha
                                    weight: 5, // espessura (px)
                                    opacity: 0.8
                                }
                            },
                        }
                    });
                    map.addControl(drawControl);

                    map.on(L.Draw.Event.CREATED, function(event) {
                        var layer = event.layer;
                        drawnItems.addLayer(layer);

                        var geojson = layer.toGeoJSON();

                        if (geojson.geometry.type === "LineString") {
                            var coords = geojson.geometry.coordinates; // [lon, lat]

                            fetch("salvar_shape.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        trip_id: <?= $id ?>, // ID da viagem atual em PHP
                                        coords: coords
                                    })
                                })
                                .then(res => res.text())
                                .then(data => {
                                    alert(data);
                                });
                        }
                    });

                    // ID da trip vindo do PHP
                    var tripId = <?= $id ?>;

                    // Buscar shape salvo no banco
                    fetch("get_shape.php?trip_id=" + tripId)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                // data = [[lat, lon], [lat, lon], ...]
                                var polyline = L.polyline(data, {
                                    color: "#0000ff",
                                    weight: 3,
                                    opacity: 0.8
                                }).addTo(map);

                                // Centralizar mapa no shape
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