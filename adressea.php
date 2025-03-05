<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplace par ton nom d'utilisateur MySQL
$password = "Kookies7*"; // Remplace par ton mot de passe MySQL
$dbname = "dbs9616600"; // Remplace par le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Lire les adresses depuis la base de données
$stmt = $pdo->query("SELECT id, address, latitude, longitude FROM adresses");
$rows = $stmt->fetchAll();

// Log des adresses lues
error_log("Adresses lues depuis la base de données : " . print_r($rows, true));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Géocodage avec Mapbox</title>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css" />
    <style>
        #address-history {
            margin-top: 20px;
        }
        .address-item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div id="map" style="width: 100%; height: 400px;"></div>
    <div id="address-history">
        <h2>Historique des adresses trouvées :</h2>
        <ul>
            <?php foreach ($rows as $row): ?>
                <li class="address-item">
                    <strong>Adresse :</strong> <?php echo htmlspecialchars($row['address']); ?><br>
                    <strong>Latitude :</strong> <?php echo $row['latitude']; ?>,
                    <strong>Longitude :</strong> <?php echo $row['longitude']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiZnJhbmNrdzMxIiwiYSI6ImNsbmJqemU5cjA0MDYya3RkczNrMHdqb2wifQ.6NLEMz-lShL80j9QuGW9cA';

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [2.3522, 48.8566], // Coordonnées de Paris
            zoom: 12
        });

        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl
        });

        document.getElementById('map').appendChild(geocoder.onAdd(map));

        // Fonction pour obtenir les coordonnées d'une adresse
        async function getCoordinates(address) {
            try {
                const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(address)}.json?access_token=${mapboxgl.accessToken}`);
                const data = await response.json();
                if (data.features.length > 0) {
                    const [lng, lat] = data.features[0].geometry.coordinates;
                    return { lat, lng };
                }
            } catch (error) {
                console.error('Erreur lors du géocodage :', error);
            }
            return { lat: null, lng: null };
        }

        // Ajouter une nouvelle adresse
        async function addAddress(address) {
            const { lat, lng } = await getCoordinates(address);
            if (lat && lng) {
                fetch('insert_address.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ address: address, latitude: lat, longitude: lng })
                }).then(response => response.text())
                  .then(data => {
                      console.log(data);
                      // Recharger la liste des adresses
                      location.reload();
                  })
                  .catch(error => console.error('Erreur:', error));
            }
        }

        // Exemple d'ajout de nouvelles adresses
        const newAddresses = [
            '1600 Amphitheatre Parkway, Mountain View, CA',
            '1 Infinite Loop, Cupertino, CA',
            '1 Hacker Way, Menlo Park, CA'
        ];

        newAddresses.forEach(addAddress);
    </script>
</body>
</html>
