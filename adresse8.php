<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géocodage avec Mapbox GL JS</title>
    <meta name='mapbox-token' content='pk.eyJ1IjoiZnJhbmNrdzMxIiwiYSI6ImNsbmJqemU5cjA0MDYya3RkczNrMHdqb2wifQ.6NLEMz-lShL80j9QuGW9cA' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css' rel='stylesheet' />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
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
            center: [1.43, 43.65], // Longitude, Latitude
            zoom: 9.6
        });

        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            container: 'geocoder'
        });

        map.addControl(geocoder);

        let marker;
        let circle;
        const historyList = document.getElementById('history-list');

        // Écouter les résultats du géocodeur
        geocoder.on('result', async function(e) {
            const coordinates = e.result.center;
            const address = e.result.place_name;
            document.getElementById('coordinates').innerText = `Coordonnées : ${coordinates[1]}, ${coordinates[0]}`;

            // Ajouter ou déplacer le marqueur
            if (marker) {
                marker.setLngLat(coordinates);
            } else {
                marker = new mapboxgl.Marker({ draggable: true })
                    .setLngLat(coordinates)
                    .addTo(map);

                // Écouter les événements de drag du marqueur
                marker.on('dragend', onDragEnd);
            }

            // Ajouter ou déplacer le cercle
            if (circle) {
                map.removeLayer('circle-fill');
                map.removeSource('circle');
            }

            map.addSource('circle', {
                'type': 'geojson',
                'data': {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': coordinates
                    }
                }
            });

            map.addLayer({
                'id': 'circle-fill',
                'type': 'circle',
                'source': 'circle',
                'paint': {
                    'circle-radius': 100, // Rayon en mètres
                    'circle-color': '#007cbf',
                    'circle-opacity': 0.5
                }
            });

            // Ajouter à l'historique
            const listItem = document.createElement('li');
            listItem.textContent = `Adresse : ${address} (Coordonnées : ${coordinates[1]}, ${coordinates[0]})`;
            historyList.appendChild(listItem);

            // Sauvegarder dans la base de données
            await saveToDatabase(address, coordinates[1], coordinates[0]);

            updateCircleRadius();
        });

        async function onDragEnd() {
            const lngLat = marker.getLngLat();
            document.getElementById('coordinates').innerText = `Nouvelles coordonnées : ${lngLat.lat}, ${lngLat.lng}`;

            // Mettre à jour le cercle
            map.getSource('circle').setData({
                'type': 'Feature',
                'geometry': {
                    'type': 'Point',
                    'coordinates': [lngLat.lng, lngLat.lat]
                }
            });

            // Géocodage inverse pour obtenir l'adresse
            const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`);
            const data = await response.json();
            const newAddress = data.features[0].place_name;

            // Ajouter à l'historique
            const listItem = document.createElement('li');
            listItem.textContent = `Nouvelle adresse : ${newAddress} (Coordonnées : ${lngLat.lat}, ${lngLat.lng})`;
            historyList.appendChild(listItem);

            // Sauvegarder dans la base de données
            await saveToDatabase(newAddress, lngLat.lat, lngLat.lng);

            updateCircleRadius();
        }

        async function saveToDatabase(address, latitude, longitude) {
            const formData = new FormData();
            formData.append('address', address);
            formData.append('latitude', latitude);
            formData.append('longitude', longitude);

            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text();
            console.log(result); // Afficher la réponse du serveur
        }

        function updateCircleRadius() {
            const zoomLevel = map.getZoom();
            const radius = 100 * Math.pow(1.5, zoomLevel - 9.6); // Ajuste le rayon en fonction du zoom
            if (map.getLayer('circle-fill')) {
                map.setPaintProperty('circle-fill', 'circle-radius', radius);
            }
        }

        // Mettre à jour le rayon du cercle lorsque le niveau de zoom change
        map.on('zoom', updateCircleRadius);
    </script>

    <?php
    // Activer l'affichage des erreurs
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root"; // Remplace par ton nom d'utilisateur MySQL
    $password = "Kookies7*"; // Remplace par ton mot de passe MySQL
    $dbname = "dbs9616600"; // Remplace par le nom de ta base de données

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $address = $_POST['address'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Débogage : Afficher les valeurs reçues
        echo "+".$address."+";

        $sql = "INSERT INTO geocoding_results ('id_geo', 'longitude', 'latitude', 'addresse') VALUES ( '10', '1', '2', 'add')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdd", $address, $latitude, $longitude);

        if ($stmt->execute()) {
            echo "Nouveau résultat créé avec succès";
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
