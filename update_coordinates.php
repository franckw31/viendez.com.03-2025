<?php
// update_coordinates.php

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
$id = $data['id'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Log des données reçues
error_log("Mise à jour de l'adresse ID: $id, Latitude: $latitude, Longitude: $longitude");

// Vérifiez que les données sont bien reçues
if ($id && isset($latitude) && isset($longitude)) {
    $stmt = $pdo->prepare("UPDATE adresses SET latitude = ?, longitude = ? WHERE id = ?");
    $stmt->execute([$latitude, $longitude, $id]);
    echo "Coordonnées mises à jour pour l'adresse ID $id";
} else {
    echo "Données manquantes pour la mise à jour.";
    error_log("Données manquantes pour la mise à jour.");
}
?>
