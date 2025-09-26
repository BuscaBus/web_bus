<?php
include("../connection.php");

// Pega todos os pontos cadastrados que tenham coordenadas
$sql = "SELECT stop_lat AS latitude, stop_lon AS longitude, stop_code FROM stops WHERE stop_lat <> '' AND stop_lon <> ''";
$result = mysqli_query($conexao, $sql);

$marcadores = [];
while ($row = mysqli_fetch_assoc($result)) {
    $marcadores[] = $row;
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de pontos</title>
    <link rel="stylesheet" href="../css/stops.css?v=1.2">

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

                <button class="btn-reg-canc">
                    <a href="list.php" class="a-btn-canc">CANCELAR</a>
                </button>
            </form>
        </div>

        <script>
            // =================== BASEMAPS ===================
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            });

            var satelite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri'
            });

            // Cria o mapa já com o OSM
            var map = L.map('div-map', {
                center: [-27.595740, -48.568228],
                zoom: 13,
                layers: [osm] // camada inicial
            });

            // Controle para trocar camadas
            var baseMaps = {
                "OpenStreetMap": osm,
                "Satélite": satelite,                             
            };

            L.control.layers(baseMaps).addTo(map);

            // =================== DESENHO ===================
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            var drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems
                },
                draw: {
                    marker: true,
                    polyline: false,
                    polygon: false,
                    rectangle: false,
                    circle: false
                }
            });
            map.addControl(drawControl);

            // =================== ÍCONE ===================
            var meuIcone = L.icon({
                iconUrl: '../img/icon-bus2.png',
                iconSize: [15, 15],
                iconAnchor: [16, 5],
                popupAnchor: [0, -32]
            });

            // =================== MARCADORES EXISTENTES ===================
            var marcadoresBanco = L.layerGroup().addTo(map);

            var marcadoresExistentes = <?php echo json_encode($marcadores); ?>;

            marcadoresExistentes.forEach(function(ponto) {
                if (ponto.latitude && ponto.longitude) {
                    L.marker([ponto.latitude, ponto.longitude], {
                            icon: meuIcone
                        })
                        .bindPopup("<b>Ponto:</b> " + ponto.stop_code)
                        .addTo(marcadoresBanco);
                }
            });

            // =================== NOVO MARCADOR ===================
            map.on(L.Draw.Event.CREATED, function(event) {
                var layer = event.layer;

                if (event.layerType === 'marker') {
                    var coords = layer.getLatLng();

                    // Preenche inputs (se existirem)
                    if (document.getElementById('lat')) {
                        document.getElementById('lat').value = coords.lat;
                    }
                    if (document.getElementById('lng')) {
                        document.getElementById('lng').value = coords.lng;
                    }

                    var marcador = L.marker([coords.lat, coords.lng], {
                            icon: meuIcone
                        })
                        .bindPopup("Lat: " + coords.lat + "<br>Lng: " + coords.lng)
                        .openPopup();

                    drawnItems.clearLayers();
                    drawnItems.addLayer(marcador);
                }
            });
        </script>        
    </section>
</body>

</html>