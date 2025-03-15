<?php
include ('include/config.php');

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$query = "SELECT * FROM events WHERE DATE(start_date) = '$date' ORDER BY start_date";
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'start_date' => $row['start_date'],
        'end_date' => $row['end_date']
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
