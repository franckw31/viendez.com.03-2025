<!DOCTYPE html>
<html>

<head>
    <!-- Include Leaflet CSS from CDN -->
    <link data-require="leaflet@1.0.3" data-semver="1.0.3" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.css" />
    <!-- Include Leaflet JS from CDN -->
    <script data-require="leaflet@1.0.3" data-semver="1.0.3" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.js"></script>
    <!-- Include custom geocoder control script -->
    <script src="Control.Geocoder.js"></script>
    <!-- Include custom styles -->
    <link rel="stylesheet" href="style.css" />
    <!-- Include custom geocoder control styles -->
    <link rel="stylesheet" href="Control.Geocoder.css" />
</head>

<body>
    <!-- Map container -->
    <div id="Modalmap"></div><br/><br/>
    <!-- Input for latitude -->
    Lat <input type="text" id="Latitude"/><br/>
    <!-- Input for longitude -->
    Lon <input type="text" id="Longitude"/>
    <!-- Include custom script -->
    <script src="script.js"></script>
</body>

</html>