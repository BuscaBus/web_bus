<?php
include("../connection.php");

// Pega todos os pontos cadastrados com coordenadas e dados de rota/viagem
$sql = "SELECT 
            s.stop_code,
            s.stop_name,
            s.stop_lat AS latitude,
            s.stop_lon AS longitude,
            GROUP_CONCAT(
                CONCAT(r.route_short_name, ' - ', t.trip_short_name, ' - ', t.trip_headsign)
                SEPARATOR '<br>'
            ) AS rotas_viagens
        FROM stops s
        LEFT JOIN stop_routes sr ON sr.stop_code = s.stop_code
        LEFT JOIN trips t ON sr.trip_id = t.trip_id
        LEFT JOIN routes r ON t.route_id = r.route_id
        WHERE s.stop_lat IS NOT NULL AND s.stop_lon IS NOT NULL
        GROUP BY s.stop_code
        ORDER BY s.stop_name ASC";

$result = mysqli_query($conexao, $sql);

$marcadores = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['latitude'] = floatval($row['latitude']);
    $row['longitude'] = floatval($row['longitude']);
    $marcadores[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de pontos</title>
    <link rel="stylesheet" href="../css/shape.css?v=1.0">

    <!-- CSS do Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    <!-- JS do Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
</head>

<body>
    <section>
        <!-- FILTROS -->
        <form id="form-filtro">
            <label>Operadora: </label>
            <select name="operadora" id="selc-op" class="selc-op">
                <option value="">Selecione a operadora</option>
                <?php
                $sql_select = "SELECT agency_id, agency_name FROM agency ORDER BY agency_name ASC";
                $result_selec = mysqli_query($conexao, $sql_select);
                while ($dados = mysqli_fetch_array($result_selec)) {
                    $id = $dados['agency_id'];
                    $nome = $dados['agency_name'];
                    echo "<option value=\"$id\">$nome</option>";
                }
                ?>
            </select>

            <label>Linha: </label>
            <select name="linha" id="selc-linh" class="selc-linh">
                <option value="">Selecione a linha</option>
            </select>

            <label>Viagem: </label>
            <select name="viagem" id="selc-viag" class="selc-viag">
                <option value="">Selecione a viagem</option>
            </select>

            <button type="button" class="btn-selec">SELECIONAR</button>
        </form>

        <!-- MAPA -->
        <div id="div-map"></div>

        <script>
            // =================== SELECTS DINÂMICOS ===================
            document.getElementById('selc-op').addEventListener('change', function() {
                let agency_id = this.value;
                let linhaSelect = document.getElementById('selc-linh');
                let viagemSelect = document.getElementById('selc-viag');

                linhaSelect.innerHTML = "<option value=''>Carregando...</option>";
                viagemSelect.innerHTML = "<option value=''>Selecione a viagem</option>";

                if (agency_id) {
                    fetch('get_linhas.php?agency_id=' + agency_id)
                        .then(response => response.json())
                        .then(data => {
                            linhaSelect.innerHTML = "<option value=''>Selecione a linha</option>";
                            data.forEach(linha => {
                                linhaSelect.innerHTML += `<option value="${linha.route_id}">${linha.linha_nome}</option>`;
                            });
                        });
                } else {
                    linhaSelect.innerHTML = "<option value=''>Selecione a linha</option>";
                }
            });

            document.getElementById('selc-linh').addEventListener('change', function() {
                let route_id = this.value;
                let viagemSelect = document.getElementById('selc-viag');

                viagemSelect.innerHTML = "<option value=''>Carregando...</option>";

                if (route_id) {
                    fetch('get_viagens.php?route_id=' + route_id)
                        .then(response => response.json())
                        .then(data => {
                            viagemSelect.innerHTML = "<option value=''>Selecione a viagem</option>";
                            data.forEach(viagem => {
                                viagemSelect.innerHTML += `<option value="${viagem.trip_id}">${viagem.viagem_nome}</option>`;
                            });
                        });
                } else {
                    viagemSelect.innerHTML = "<option value=''>Selecione a viagem</option>";
                }
            });

            // =================== BASEMAPS ===================
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            });

            var satelite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri'
            });

            var map = L.map('div-map', {
                center: [-27.595740, -48.568228],
                zoom: 13,
                maxZoom: 18, 
                layers: [osm]
            });

            var baseMaps = {
                "OpenStreetMap": osm,
                "Satélite": satelite,
            };
            L.control.layers(baseMaps).addTo(map);

            // =================== MARCADORES EXISTENTES ===================
            var meuIcone = L.icon({
                iconUrl: '../img/icon-bus2.png',
                iconSize: [15, 15],
                iconAnchor: [16, 5],
                popupAnchor: [0, -32]
            });

            var marcadoresBanco = L.layerGroup().addTo(map);
            var marcadoresExistentes = <?php echo json_encode($marcadores); ?>;
            var zoomMinimo = 17;

            function atualizarMarcadores() {
                marcadoresBanco.clearLayers();
                if (map.getZoom() >= zoomMinimo) {
                    var bounds = map.getBounds();
                    marcadoresExistentes.forEach(function(ponto) {
                        if (ponto.latitude && ponto.longitude) {
                            var latlng = L.latLng(ponto.latitude, ponto.longitude);
                            if (bounds.contains(latlng)) {
                                L.marker([ponto.latitude, ponto.longitude], {
                                    icon: meuIcone
                                })
                                .bindPopup("<b>Ponto:</b> " + ponto.stop_code + " <br>" + ponto.stop_name +
                                (ponto.rotas_viagens ? "<br><br> Linhas/Viagens: <br>" + ponto.rotas_viagens : "")
                                )

                                .addTo(marcadoresBanco);
                            }
                        }
                    });
                }
            }

            map.on('zoomend', atualizarMarcadores);
            map.on('moveend', atualizarMarcadores);
            atualizarMarcadores();

            // =================== SHAPES ===================
            let shapeLayer;

            document.querySelector(".btn-selec").addEventListener("click", function(e) {
                e.preventDefault();

                let trip_id = document.getElementById("selc-viag").value;
                if (!trip_id) {
                    alert("Selecione uma viagem!");
                    return;
                }

                fetch("get_shape.php?trip_id=" + trip_id)
                    .then(response => response.json())
                    .then(pontos => {
                        if (shapeLayer) {
                            map.removeLayer(shapeLayer);
                        }
                        if (pontos.length > 0) {
                            shapeLayer = L.polyline(pontos, {
                                color: "blue",
                                weight: 4
                            }).addTo(map);
                            map.fitBounds(shapeLayer.getBounds());
                        } else {
                            alert("Nenhuma rota traçada para esta viagem.");
                        }
                    });
            });
        </script>

        <p>
            <button class="btn-reg-cad">
                <a href="../index.html" class="a-btn-canc">VOLTAR</a>
            </button>
        </p>
    </section>
</body>

</html>
