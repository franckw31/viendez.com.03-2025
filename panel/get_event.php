<?php
include ('include/config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM events WHERE id = $id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($event);
