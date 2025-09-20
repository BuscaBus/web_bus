<?php
include("../connection.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de pontos</title>
    <link rel="stylesheet" href="../css/stops.css?v=1.1">

    <!-- CSS do Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- CSS do Leaflet.draw -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    <!-- JS do Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- JS do Leaflet.draw -->
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

    <style>
        .form-coords {
            margin-top: 15px;
        }

        .form-coords label {
            display: inline-block;
            width: 80px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <section>
        <h2>Cadastrar ponto no mapa</h2>

        <!-- MAPA -->
        <div id="div-map"></div>

        <!-- Inputs para coordenadas -->
        <div class="form-coords">
            <form action="register.php" method="GET">
                <label>Latitude:</label>
                <input type="text" id="lat" name="latitude" readonly>

                <label>Longitude:</label>
                <input type="text" id="lng" name="longitude" readonly>

                <button type="submit" class="btn-reg-cad">SALVAR</button>
            </form>
        </div>

        <script>
            // Cria o mapa
            var map = L.map('div-map').setView([-27.595740, -48.568228], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(map);

            // Grupo de layers desenhados
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            // Controle de desenho (somente marcador)
            var drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems
                },
                draw: {
                    marker: true, // habilita marcador
                    polyline: false,
                    polygon: false,
                    rectangle: false,
                    circle: false
                }
            });
            map.addControl(drawControl);

            // Evento ao criar um desenho
            map.on(L.Draw.Event.CREATED, function(event) {
                var layer = event.layer;

                if (event.layerType === 'marker') {
                    var coords = layer.getLatLng();

                    // Preenche os inputs com as coordenadas
                    document.getElementById('lat').value = coords.lat;
                    document.getElementById('lng').value = coords.lng;

                    // Mostra popup no marcador
                    layer.bindPopup("Lat: " + coords.lat + "<br>Lng: " + coords.lng).openPopup();
                }

                drawnItems.clearLayers(); // remove marcador anterior
                drawnItems.addLayer(layer); // adiciona novo marcador
            });
        </script>

        <p>
            <button class="btn-reg-cad">
                <a href="list.php" class="a-btn-canc">VOLTAR</a>
            </button>
        </p>
    </section>
</body>

</html>