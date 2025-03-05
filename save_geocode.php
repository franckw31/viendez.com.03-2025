<?php
echo "**".$_POST['address']."***";
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
echo "//".$_POST['address']."//";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    echo "**".$adress."***";

//    $sql = "INSERT INTO geocoding_results ('id_geo','address', 'latitude', 'longitude') VALUES ('3', '$address', '$latitude', '$longitude' )";
    $sql = "INSERT INTO `geocoding_results` ( `id_geo`,`longitude`, `latitude`, `address`) VALUES (NULL,'$longitude', '$latitude', '$address')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdd", $address, $latitude, $longitude);

    if ($stmt->execute()) {
        echo "Nouveau résultat créé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
