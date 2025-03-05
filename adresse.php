<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géocodage avec Mapbox GL JS</title>
    <meta name='mapbox-token' content='YOUR_MAPBOX_ACCESS_TOKEN' />
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
    </style>
</head>
<body>
    <div id="map"></div>
    <div id="result">Cliquez sur un résultat pour placer le marqueur.</div>
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
            mapboxgl: mapboxgl
        });

        map.addControl(geocoder);

        let marker;

        // Écouter les résultats du géocodeur
        geocoder.on('result', function(e) {
            const coordinates = e.result.center;
            document.getElementById('result').innerText = `Coordonnées : ${coordinates[1]}, ${coordinates[0]}`;

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
        });

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            document.getElementById('result').innerText = `Nouvelles coordonnées : ${lngLat.lat}, ${lngLat.lng}`;
        }
    </script>
</body>
</html>
