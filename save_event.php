<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("INSERT INTO events (title, event_date, start_time, end_time, description) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $data['title'],
        $data['date'],
        $data['startTime'],
        $data['endTime'],
        $data['description']
    ]);
    
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
