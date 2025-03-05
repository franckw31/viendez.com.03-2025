<?php
// insert_address.php

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

$data = json_decode(file_get_contents('php://input'), true);
$address = $data['address'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Log des données reçues
error_log("Adresse à insérer: $address, Latitude: $latitude, Longitude: $longitude");

// Vérifiez que les données sont bien reçues
if ($address && isset($latitude) && isset($longitude)) {
    $stmt = $pdo->prepare("INSERT INTO adresses (address, latitude, longitude) VALUES (?, ?, ?)");
    $stmt->execute([$address, $latitude, $longitude]);
    echo "Nouvelle adresse ajoutée : $address";
} else {
    echo "Données manquantes pour l'insertion.";
    error_log("Données manquantes pour l'insertion.");
}
?>
