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
        #map { position: absolute; top: 0; bottom: 0; width: 100%; }
        #identifier-input { position: absolute; top: 10px; left: 10px; z-index: 10; background: white; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>

<div id="map"></div>
<div id="identifier-input">
    <label for="identifier">Identifier: </label>
    <input type="text" id="identifier" name="identifier" placeholder="Enter identifier" required>
    <button id="search-button">Search Address</button>
</div>

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
    const history = [];

    document.getElementById('search-button').addEventListener('click', function() {
        const identifier = document.getElementById('identifier').value.trim();
        if (!identifier) {
            alert('Please enter an identifier.');
            return;
        }

        geocoder.query(identifier);
    });

    geocoder.on('result', function(e) {
        const coordinates = e.result.geometry.coordinates;
        const address = e.result.place_name;
        const identifier = document.getElementById('identifier').value;

        // Format latitude and longitude to 6 decimal places
        const latitude = coordinates[1].toFixed(6);
        const longitude = coordinates[0].toFixed(6);

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
            .setHTML(`<strong>${address}</strong><br>Latitude: ${latitude}<br>Longitude: ${longitude}`)
            .addTo(map);

        marker.setPopup(popup);

        // Send the address, identifier, and coordinates to the server to store in the database
        sendDataToServer(identifier, address, [longitude, latitude]);

        // Update coordinates and address on marker drag
        marker.on('dragend', async function() {
            const lngLat = marker.getLngLat();
            const updatedAddress = await reverseGeocode(lngLat);

            const updatedLatitude = lngLat.lat.toFixed(6);
            const updatedLongitude = lngLat.lng.toFixed(6);

            popup.setLngLat(lngLat)
                .setHTML(`<strong>${updatedAddress}</strong><br>Latitude: ${updatedLatitude}<br>Longitude: ${updatedLongitude}`);

            // Send updated address, identifier, and coordinates to the server
            sendDataToServer(identifier, updatedAddress, [updatedLongitude, updatedLatitude]);
        });
    });

    async function reverseGeocode(lngLat) {
        const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`);
        const data = await response.json();
        return data.features[0].place_name;
    }

    function sendDataToServer(identifier, address, coordinates) {
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `identifier=${encodeURIComponent(identifier)}&address=${encodeURIComponent(address)}&latitude=${coordinates[1]}&longitude=${coordinates[0]}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            addToHistory(identifier, address, coordinates);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function addToHistory(identifier, address, coordinates) {
        history.push({ identifier, address, coordinates });
        addMarkerToMap(identifier, address, coordinates);
    }

    function addMarkerToMap(identifier, address, coordinates) {
        const marker = new mapboxgl.Marker()
            .setLngLat(coordinates)
            .setPopup(new mapboxgl.Popup({ offset: 25 })
                .setHTML(`<strong>${address}</strong><br>Latitude: ${coordinates[1].toFixed(6)}<br>Longitude: ${coordinates[0].toFixed(6)}`))
            .addTo(map);
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
    $identifier = isset($_POST['identifier']) ? (int)$_POST['identifier'] : 0;
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    $latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : 0.0;
    $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : 0.0;

    if ($identifier && $address && $latitude && $longitude) {
        // Mettre à jour ou insérer les données dans la base de données
        $sql = "INSERT INTO adresse (identifier, address, latitude, longitude) VALUES ('$identifier', '$address', '$latitude', '$longitude')
                ON DUPLICATE KEY UPDATE address='$address', latitude='$latitude', longitude='$longitude'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Record updated or created successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Invalid data received"]);
    }

    $conn->close();
}
?>
