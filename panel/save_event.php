<?php
include ('include/config.php');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$title = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
$end_date = !empty($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : $start_date;

$query = "UPDATE events SET 
          title = '$title',
          description = '$description',
          start_date = '$start_date',
          end_date = '$end_date'
          WHERE id = $id";

mysqli_query($conn, $query);
header('Content-Type: application/json');
echo json_encode(['success' => true]);
