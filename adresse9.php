<?php
// Traitement PHP en haut du fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "root";
    $password = "Kookies7*";
    $dbname = "dbs9616600";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validation des entrées
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
        $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);

        if (!$address || !$latitude || !$longitude) {
            throw new Exception("Données invalides");
        }

        $sql = "INSERT INTO geocoding_results (addresse, latitude, longitude) 
                VALUES (:address, :latitude, :longitude)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':address' => $address,
            ':latitude' => $latitude,
            ':longitude' => $longitude
        ]);

        echo "Nouveau résultat créé avec succès";
        
    } catch(PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    } catch(Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
    
    if(isset($conn)) {
        $conn = null;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géocodage avec Mapbox GL JS</title>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css' rel='stylesheet' />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css">
    <style>
        body { margin: 0; padding: 0; }
        #map { position: absolute; top: 0; bottom: 0; width: 100%; }
        #result {
            position: absolute;
            top: 10px;
            left: 10px;
            background: white;
            padding: 10px;
            z-index: 10;
            font-weight: bold;
        }
        #coordinates {
            margin-top: 10px;
            font-weight: bold;
        }
        #history {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: white;
            padding: 10px;
            z-index: 10;
            max-height: 150px;
            overflow-y: auto;
        }
        .mapboxgl-ctrl-geocoder { width: 300px; }
    </style>
</head>
<body>
    <div id="map"></div>
    <div id="result">Cliquez sur un résultat pour placer le marqueur.</div>
    <div id="geocoder-container">
        <div id="geocoder"></div>
        <div id="coordinates"></div>
    </div>
    <div id="history">
        <strong>Historique des résultats :</strong>
        <ul id="history-list"></ul>
    </div>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiZnJhbmNrdzMxIiwiYSI6ImNsbmJqemU5cjA0MDYya3RkczNrMHdqb2wifQ.6NLEMz-lShL80j9QuGW9cA';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/franckw31/clnd1m23b03o501qu3x5ab4xk',
            center: [1.43, 43.65],
            zoom: 9.6
        });

        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            container: 'geocoder',
            placeholder: 'Rechercher une adresse'
        });

        map.addControl(geocoder);

        let marker = null;
        let circle = null;
        const historyList = document.getElementById('history-list');

        geocoder.on('result', async (e) => {
            const coordinates = e.result.center;
            const address = e.result.place_name;
            
            updateCoordinatesDisplay(coordinates, address);
            updateMarker(coordinates);
            updateCircleLayer(coordinates);
            addToHistory(address, coordinates);
            await saveToDatabase(address, coordinates[1], coordinates[0]);
        });

        function updateCoordinatesDisplay(coords, address) {
            document.getElementById('coordinates').innerHTML = `
                Adresse : ${address}<br>
                Latitude : ${coords[1].toFixed(5)}<br>
                Longitude : ${coords[0].toFixed(5)}
            `;
        }

        function updateMarker(coords) {
            if (marker) {
                marker.setLngLat(coords);
            } else {
                marker = new mapboxgl.Marker({ draggable: true })
                    .setLngLat(coords)
                    .addTo(map)
                    .on('dragend', onDragEnd);
            }
        }

        function updateCircleLayer(coords) {
            if (map.getLayer('circle-fill')) {
                map.removeLayer('circle-fill');
                map.removeSource('circle');
            }

            map.addSource('circle', {
                type: 'geojson',
                data: {
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: coords
                    }
                }
            });

            map.addLayer({
                id: 'circle-fill',
                type: 'circle',
                source: 'circle',
                paint: {
                    'circle-radius': 100,
                    'circle-color': '#007cbf',
                    'circle-opacity': 0.3,
                    'circle-stroke-width': 2,
                    'circle-stroke-color': '#007cbf'
                }
            });
        }

        function addToHistory(address, coords) {
            const li = document.createElement('li');
            li.innerHTML = `
                ${address}<br>
                <small>Lat: ${coords[1].toFixed(5)}, Lng: ${coords[0].toFixed(5)}</small>
            `;
            historyList.appendChild(li);
        }

        async function onDragEnd() {
            const lngLat = marker.getLngLat();
            const response = await fetch(
                `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`
            );
            const data = await response.json();
            const newAddress = data.features[0].place_name;

            updateCoordinatesDisplay([lngLat.lng, lngLat.lat], newAddress);
            updateCircleLayer([lngLat.lng, lngLat.lat]);
            addToHistory(newAddress, [lngLat.lng, lngLat.lat]);
            await saveToDatabase(newAddress, lngLat.lat, lngLat.lng);
        }

        async function saveToDatabase(address, lat, lng) {
            try {
                const response = await fetch('index.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        address: address,
                        latitude: lat,
                        longitude: lng
                    })
                });

                if (!response.ok) throw new Error('Erreur HTTP');
                console.log(await response.text());
            } catch (error) {
                console.error('Erreur de sauvegarde:', error);
            }
        }

        map.on('zoom', () => {
            if (map.getLayer('circle-fill')) {
                map.setPaintProperty(
                    'circle-fill',
                    'circle-radius',
                    100 * Math.pow(1.5, map.getZoom() - 9.6)
                );
            }
        });
    </script>
</body>
</html>