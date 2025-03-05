<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Mapbox Geocoder Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css" />
    <style>
        body { margin: 0; padding: 0; }
        #map { position: absolute; top: 0; bottom: 50%; width: 100%; }
        #history { position: absolute; top: 50%; bottom: 0; width: 100%; overflow-y: auto; }
        .history-item { padding: 10px; border-bottom: 1px solid #ccc; }
    </style>
</head>
<body>

<div id="map"></div>
<div id="history"></div>

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiZnJhbmNrdzMxIiwiYSI6ImNsbmJqemU5cjA0MDYya3RkczNrMHdqb2wifQ.6NLEMz-lShL80j9QuGW9cA';
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [1.43, 43.65],
        zoom: 9.6
    });

    const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
    });

    map.addControl(geocoder);

    let marker;

    geocoder.on('result', function(e) {
        const coordinates = e.result.geometry.coordinates;
        const address = e.result.place_name;

        // Remove existing marker if any
        if (marker) {
            marker.remove();
        }

        // Add new marker
        marker = new mapboxgl.Marker({ draggable: true })
            .setLngLat(coordinates)
            .addTo(map);

        // Display the address and coordinates on the map
        const popup = new mapboxgl.Popup()
            .setLngLat(coordinates)
            .setHTML(`<strong>${address}</strong><br>Latitude: ${coordinates[1]}<br>Longitude: ${coordinates[0]}`)
            .addTo(map);

        // Send the address and coordinates to the server to store in the database
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `address=${encodeURIComponent(address)}&latitude=${coordinates[1]}&longitude=${coordinates[0]}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            addToHistory(address, coordinates);
        })
        .catch((error) => {
            console.error('Error:', error);
        });

        // Update coordinates on marker drag
        marker.on('dragend', function() {
            const lngLat = marker.getLngLat();
            popup.setLngLat(lngLat)
                .setHTML(`<strong>${address}</strong><br>Latitude: ${lngLat.lat}<br>Longitude: ${lngLat.lng}`);

            // Send updated coordinates to the server
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `address=${encodeURIComponent(address)}&latitude=${lngLat.lat}&longitude=${lngLat.lng}`
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                addToHistory(address, [lngLat.lng, lngLat.lat]);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    });

    function addToHistory(address, coordinates) {
        const historyItem = document.createElement('div');
        historyItem.classList.add('history-item');
        historyItem.innerHTML = `<strong>${address}</strong><br>Latitude: ${coordinates[1]}<br>Longitude: ${coordinates[0]}`;
        document.getElementById('history').appendChild(historyItem);
    }
</script>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "Kookies7*";
    $dbname = "dbs9616600";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupérer les données POST
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

    if ($address && $latitude && $longitude) {
        // Insérer les données dans la base de données
        $sql = "INSERT INTO adresse (address, latitude, longitude) VALUES ('$address', '$latitude', '$longitude')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "New record created successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Invalid data received"]);
    }

    $conn->close();
}
?>
