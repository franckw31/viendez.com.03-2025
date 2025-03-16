<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

header('Content-Type: application/json');

try {
    if (!$conn) {
        throw new Exception("Erreur de connexion à la base de données");
    }

    if (!isset($_POST['id'], $_POST['title'], $_POST['start_date'])) {
        throw new Exception('Données manquantes');
    }

    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $buyin = intval($_POST['buyin']);
    $date_depart = date('Y-m-d H:i:s', strtotime($_POST['start_date']));
    $heure_depart = !empty($_POST['heure_depart']) ? 
                    date('Y-m-d H:i:s', strtotime($_POST['heure_depart'])) : 
                    $date_depart;
    $ville = isset($_POST['ville']) ? trim($_POST['ville']) : '';

    $query = "UPDATE activite SET 
              `titre-activite` = ?,
              `buyin` = ?,
              `date_depart` = ?,
              `heure_depart` = ?,
              `ville` = ?
              WHERE `id-activite` = ?";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception("Erreur de préparation: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, 'sisssi', $title, $buyin, $date_depart, $heure_depart, $ville, $id)) {
        throw new Exception("Erreur de liaison des paramètres");
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Erreur de mise à jour: " . mysqli_stmt_error($stmt));
    }

    echo json_encode([
        'success' => true,
        'message' => 'Activité mise à jour avec succès'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

if (isset($stmt)) mysqli_stmt_close($stmt);
if (isset($conn)) mysqli_close($conn);
