<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('include/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = !empty($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : $start_date;

    // Format dates properly for MySQL
    $start_date = date('Y-m-d H:i:s', strtotime($start_date));
    $end_date = date('Y-m-d H:i:s', strtotime($end_date));

    $query = "INSERT INTO events (title, description, start_date, end_date) 
              VALUES ('$title', '$description', '$start_date', '$end_date')";
    
    if (!mysqli_query($conn, $query)) {
        die("Error: " . mysqli_error($conn));
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}

$default_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un événement</title>
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        @media screen and (max-width: 768px) {
            .form-group input, .form-group textarea { 
                font-size: 14px;
                padding: 6px;
            }
            button { padding: 8px 16px; }
        }
    </style>
</head>
<body>
    <h2>Nouvel événement</h2>
    <form method="POST">
        <div class="form-group">
            <label>Titre :</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description :</label>
            <textarea name="description"></textarea>
        </div>
        <div class="form-group">
            <label>Date de début :</label>
            <input type="datetime-local" name="start_date" value="<?php echo $default_date; ?>T00:00" required>
        </div>
        <div class="form-group">
            <label>Date de fin :</label>
            <input type="datetime-local" name="end_date">
        </div>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
