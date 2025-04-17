<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'dbs9616600',
    'user' => 'root',
    'pass' => 'Kookies7*'
];

// Database connection function
function getDbConnection($config) {
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        return new PDO($dsn, $config['user'], $config['pass'], $options);
    } catch(PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return false;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $conn = getDbConnection($db_config);
    
    if (!$conn) {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit;
    }
    
    switch ($data['action'] ?? '') {
        case 'save':
            try {
                $conn->beginTransaction();
                
                $stmt = $conn->prepare("INSERT INTO blind_structures (name) VALUES (?)");
                if (!$stmt->execute([$data['name']])) {
                    throw new Exception("Failed to save structure name");
                }
                $structureId = $conn->lastInsertId();
                
                $stmt = $conn->prepare("
                    INSERT INTO blind_levels 
                    (structure_id, level, small_blind, big_blind, ante, duration) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                foreach ($data['levels'] as $level) {
                    if (!$stmt->execute([
                        $structureId,
                        $level['level'],
                        $level['small_blind'],
                        $level['big_blind'],
                        $level['ante'],
                        $level['duration']
                    ])) {
                        throw new Exception("Failed to save blind level");
                    }
                }
                
                $conn->commit();
                echo json_encode(['success' => true, 'id' => $structureId]);
            } catch (Exception $e) {
                $conn->rollBack();
                error_log("Save error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'load':
            try {
                if (!isset($data['id'])) {
                    throw new Exception("No structure ID provided");
                }
                
                $stmt = $conn->prepare("
                    SELECT * FROM blind_levels 
                    WHERE structure_id = ? 
                    ORDER BY level ASC
                ");
                $stmt->execute([$data['id']]);
                $levels = $stmt->fetchAll();
                
                if (empty($levels)) {
                    throw new Exception("No blind levels found");
                }
                
                echo json_encode(['success' => true, 'levels' => $levels]);
            } catch (Exception $e) {
                error_log("Load error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'list':
            try {
                $stmt = $conn->query("
                    SELECT 
                        bs.id,
                        bs.name,
                        bs.created_at,
                        COUNT(bl.id) as level_count 
                    FROM blind_structures bs 
                    LEFT JOIN blind_levels bl ON bs.id = bl.structure_id 
                    GROUP BY bs.id, bs.name, bs.created_at
                    ORDER BY bs.created_at DESC
                ");
                
                $structures = $stmt->fetchAll();
                echo json_encode(['success' => true, 'structures' => $structures]);
            } catch (Exception $e) {
                error_log("List error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;

        case 'delete':
            try {
                if (!isset($data['id'])) {
                    throw new Exception("No structure ID provided");
                }
                
                $conn->beginTransaction();
                
                $stmt = $conn->prepare("DELETE FROM blind_levels WHERE structure_id = ?");
                $stmt->execute([$data['id']]);
                
                $stmt = $conn->prepare("DELETE FROM blind_structures WHERE id = ?");
                $stmt->execute([$data['id']]);
                
                $conn->commit();
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                $conn->rollBack();
                error_log("Delete error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'rename':
            try {
                if (!isset($data['id']) || !isset($data['name'])) {
                    throw new Exception("Missing required data");
                }
                
                $stmt = $conn->prepare("UPDATE blind_structures SET name = ? WHERE id = ?");
                if (!$stmt->execute([$data['name'], $data['id']])) {
                    throw new Exception("Failed to rename structure");
                }
                
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                error_log("Rename error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poker Timer</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* ... existing styles ... */
        /* Global styles */
    body {
        background-color: #121212;
        color: white;
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
    }
    section.time-controls {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin: 10px 0;
}

.time-controls button {
    padding: 10px;
    font-size: 16px;
}

@media (max-width: 480px) {
    .time-controls {
        grid-template-columns: 1fr;
    }
}
    /* Container */
    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #1E1E1E;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    /* Timer display */
    .timer-display {
        font-size: 80px;
        font-weight: 700;
        color:rgb(255, 17, 0);
        text-align: center;
        margin: 20px 0;
        font-variant-numeric: tabular-nums;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Blind info */
    .blind-info {
        font-size: 96px;
        color:rgb(42, 164, 235);
        text-align: center;
        margin: 15px 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .blind-info-next {
        font-size: 32px;
        color:rgb(227, 243, 9);
        text-align: center;
        margin: 15px 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    /* Controls */
    .controls {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin: 20px 0;
    }

    /* Buttons */
    button {
        padding: 15px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-transform: uppercase;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    button:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .start-btn { 
        background-color: #4CAF50; 
        color: white; 
    }

    .pause-btn { 
        background-color: #FFC107; 
        color: black; 
    }

    .reset-btn { 
        background-color: #F44336; 
        color: white; 
    }

    .edit-btn { 
        background-color: #2196F3; 
        color: white;
        width: 100%;
        margin-top: 10px;
    }

    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    /* Edit Panel */
    .edit-panel, .load-panel {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        padding: 20px;
        z-index: 1000;
        overflow-y: auto;
    }

    .edit-content, .load-content {
        background: #1E1E1E;
        padding: 20px;
        border-radius: 15px;
        max-width: 600px;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0,0,0,0.4);
    }

    /* Blind Editor */
    .blind-headers {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: 10px 0;
        font-weight: bold;
        color: #90CAF9;
        padding: 0 10px;
    }

    .blind-editor {
        margin: 20px 0;
        padding: 10px;
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
    }

    .blind-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: 10px 0;
        position: relative;
        align-items: center;
    }

    .blind-row input {
        width: 100%;
        padding: 8px;
        background: #333;
        border: 1px solid #555;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        box-sizing: border-box;
    }

    .blind-row input:focus {
        outline: none;
        border-color: #2196F3;
        box-shadow: 0 0 0 2px rgba(33,150,243,0.2);
    }

    /* Remove Button */
    .remove-btn {
        position: absolute;
        right: -30px;
        width: 24px;
        height: 24px;
        background: #F44336;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: #D32F2F;
        transform: scale(1.1);
    }

    /* Structure Items */
    .structure-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        margin: 10px 0;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .structure-item:hover {
        background: rgba(255,255,255,0.15);
    }

    .structure-info {
        flex: 1;
        font-size: 16px;
    }

    .structure-info div {
        color: #90CAF9;
        font-size: 14px;
        margin-top: 5px;
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .actions button {
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Edit Actions */
    .edit-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 20px;
    }

    /* Responsive Design */
    @media (max-width: 480px) {
        body {
            padding: 10px;
        }

        .timer-display { 
            font-size: 72px; 
        }

        .blind-info { 
            font-size: 24px; 
        }

        .controls { 
            grid-template-columns: 1fr; 
        }

        .actions { 
            flex-direction: column; 
        }

        .blind-row {
            grid-template-columns: 1fr;
        }

        .remove-btn {
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .edit-content, .load-content {
            margin: 10px;
            padding: 15px;
        }
    }

    /* Dark mode optimization */
    @media (prefers-color-scheme: dark) {
        .blind-row input {
            background: #2A2A2A;
        }

        .structure-item {
            background: rgba(255,255,255,0.08);
        }

        .structure-item:hover {
            background: rgba(255,255,255,0.12);
        }
    }
        .edit-panel {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            padding: 20px;
            z-index: 1000;
        }

        .edit-content {
            background: #1E1E1E;
            padding: 20px;
            border-radius: 15px;
            max-width: 600px;
            margin: 20px auto;
            max-height: 90vh;
            overflow-y: auto;
        }

        .blind-editor {
            margin: 20px 0;
        }

        .blind-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 10px 0;
            position: relative;
        }

        .blind-row input {
            width: 100%;
            padding: 8px;
            background: #333;
            border: 1px solid #555;
            border-radius: 4px;
            color: white;
        }

        .remove-btn {
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background: #F44336;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
    .blind-headers {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 15px;
    color: #90CAF9;
    font-weight: bold;
}

.blind-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin: 10px 0;
    position: relative;
}

.blind-row input {
    width: 100%;
    padding: 8px;
    background: #333;
    border: 1px solid #555;
    border-radius: 4px;
    color: white;
}

.remove-btn {
    position: absolute;
    right: -30px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    background: #F44336;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="blind-info">Niveau <span id="level">1</span></div>
        <div class="blind-info"><span id="blinds">25/50</span></div> 
        <!-- <div class="blind-info">Ante: <span id="ante">0</span></div> -->
        <div class="timer-display" id="timer">15:00</div>
        <div class="blind-info-next">Next: <span id="next-blind">50/100</span></div>
        
        <div class="controls">
            <button class="start-btn" id="startBtn">Start</button>
            <button class="pause-btn" id="pauseBtn">Pause</button>
            <button class="reset-btn" id="resetBtn">Reset</button>
        </div>
        <div class="time-controls">
            <button class="edit-btn" id="minusMinBtn">- 1 Minute</button>
            <button class="edit-btn" id="plusMinBtn">+ 1 Minute</button>
        </div>
        
        <button class="edit-btn" id="editBtn">Edit Blinds</button>
        <button class="edit-btn" id="saveToDbBtn">Save Structure</button>
        <button class="edit-btn" id="loadFromDbBtn">Load Structure</button>
    </div>

    <div class="edit-panel" id="editPanel">
    <div class="edit-content">
        <h2 style="color: #90CAF9;">Edit Blind Structure</h2>
        <div class="blind-editor" id="blindEditor"></div>
        <button class="edit-btn" id="addLevelBtn">+ Add Level</button>
        <div class="edit-actions">
            <button class="start-btn" id="saveEditBtn">Save Changes</button>
            <button class="reset-btn" id="cancelEditBtn">Cancel</button>
        </div>
    </div>
</div>

    <div class="load-panel" id="loadPanel">
        <div class="load-content">
            <h2 style="color: #90CAF9;">Load Blind Structure</h2>
            <div id="structuresList"></div>
            <button class="reset-btn" id="closeLoadBtn">Close</button>
        </div>
    </div>
    <div style="text-align: center; margin-top: 10px; color: #90CAF9; font-size: 12px;">
    Click anywhere to enable sound notifications
</div>
        
    <audio id="levelSound" preload="auto">
        <source src="level-up.mp3" type="audio/mpeg">
    </audio>
    <audio id="endSound" preload="auto">
        <source src="end.mp3" type="audio/mpeg">
    </audio>
    <audio id="levelSound">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1HOTgzLyspJyUjIiAfHx4dHRwcHBsaGhsZGhoZGhsaGxwaGxwbHBwcHR0dHh4dHh8eHh8fHyAhICEhISIjIiMkIyQkJSYlJiYnKCcpKSorKywtLS4vMDEyMzQ2Nzg5Ozw9P0BBQkNFRkdISUpLTE1OT1BRUVJTVFVVVlVWV1dYV1hXWFhZWFdYV1hXWFhXWFdYV1hYWFhYWVlaW1tcXV5fYGFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6ent8fX5/gIGCg4SFhoeIiYqLjI2Oj5CRkpOUlZaXmJmam5ydnp+goaKjpKWmp6ipqqusra6vsLGys7S1tre4ubq7vL2+v8DBwsPExcbHyMnKy8zNzs/Q0dLT1NXW19jZ2tvc3d7f4OHi4+Tl5ufo6err7O3u7/Dx8vP09fb3+Pn6+/z9/v8AAQIDBAUGBwgJCgsMDQ4PEBESExQVFhcYGRobHB0eHyAhIiMkJSYnKCkqKywtLi8wMTIzNDU2Nzg5Ojs8PT4/QEFCQ0RFRkdISUpLTE1OT1BRUlNUVVZXWFlaW1xdXl9gYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXp7fH1+f4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NHS09TV1tfY2drb3N3e3+Dh4uPk5ebn6Onq6+zt7u/w8fLz9PX29/j5+vv8/f7/AAECAwQFBgcICQoLDA0ODxAREhMUFRYXGBkaGxwdHh8gISIjJCUmJygpKissLS0uLzAxMjM0NTY3ODk6Ozw9Pj9AQUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVpbXF1eX2BhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ent8fX5/gIGCg4SFhoeIiYqLjI2Oj5CRkpOUlZaXmJmam5ydnp+goaKjpKWmp6ipqqusra6vsLGys7S1tre4ubq7vL2+v8DBwsPExcbHyMnKy8zNzs/Q0dLT1NXW19jZ2tvc3d7f4OHi4+Tl5ufo6err7O3u7/Dx8vP09fb3+Pn6+/z9/v8=" type="audio/wav">
    </audio>
    <audio id="endSound">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1HOTgzLyspJyUjIiAfHx4dHRwcHBsaGhsZGhoZGhsaGxwaGxwbHBwcHR0dHh4dHh8eHh8fHyAhICEhISIjIiMkIyQkJSYlJiYnKCcpKSorKywtLS4vMDEyMzQ2Nzg5Ozw9P0BBQkNFRkdISUpLTE1OT1BRUVJTVFVVVlVWV1dYV1hXWFhZWFdYV1hXWFhXWFdYV1hYWFhYWVlaW1tcXV5fYGFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6ent8fX5/gIGCg4SFhoeIiYqLjI2Oj5CRkpOUlZaXmJmam5ydnp+goaKjpKWmp6ipqqusra6vsLGys7S1tre4ubq7vL2+v8DBwsPExcbHyMnKy8zNzs/Q0dLT1NXW19jZ2tvc3d7f4OHi4+Tl5ufo6err7O3u7/Dx8vP09fb3+Pn6+/z9/v8AAQIDBAUGBwgJCgsMDQ4PEBESExQVFhcYGRobHB0eHyAhIiMkJSYnKCkqKywtLi8wMTIzNDU2Nzg5Ojs8PT4/QEFCQ0RFRkdISUpLTE1OT1BRUlNUVVZXWFlaW1xdXl9gYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXp7fH1+f4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NHS09TV1tfY2drb3N3e3+Dh4uPk5ebn6Onq6+zt7u/w8fLz9PX29/j5+vv8/f7/AAECAwQFBgcICQoLDA0ODxAREhMUFRYXGBkaGxwdHh8gISIjJCUmJygpKissLS0uLzAxMjM0NTY3ODk6Ozw9Pj9AQUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVpbXF1eX2BhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ent8fX5/gIGCg4SFhoeIiYqLjI2Oj5CRkpOUlZaXmJmam5ydnp+goaKjpKWmp6ipqqusra6vsLGys7S1tre4ubq7vL2+v8DBwsPExcbHyMnKy8zNzs/Q0dLT1NXW19jZ2tvc3d7f4OHi4+Tl5ufo6err7O3u7/Dx8vP09fb3+Pn6+/z9/v8=" type="audio/wav">
    </audio>

    <script>
        // Initial blind structure
        let blindLevels = [
            { level: 1, small_blind: 100, big_blind: 200, ante: 0, duration: 1200 },
            { level: 2, small_blind: 200, big_blind: 400, ante: 0, duration: 1200 },
            { level: 3, small_blind: 300, big_blind: 600, ante: 0, duration: 1200 },
            { level: 4, small_blind: 400, big_blind: 800, ante: 0, duration: 1200 },
            { level: 5, small_blind: 500, big_blind: 1000, ante: 0, duration: 1200 },
            { level: 6, small_blind: 0, big_blind: 0, ante: 0, duration: 600 },
            { level: 7, small_blind: 600, big_blind: 1200, ante: 0, duration: 900 },
            { level: 8, small_blind: 800, big_blind: 1600, ante: 0, duration: 900 },
            { level: 9, small_blind: 1000, big_blind: 2000, ante: 0, duration: 900 },
            { level: 10, small_blind: 1500, big_blind: 3000, ante: 0, duration: 900 },
            { level: 11, small_blind: 2000, big_blind: 4000, ante: 0, duration: 900 }

        ];

        let currentLevel = 0;
        let timeLeft = blindLevels[0].duration;
        let timerInterval;
        let isRunning = false;
    
        // Timer functions
        function updateDisplay() {
            const minutes = Math.floor(Math.max(0, timeLeft) / 60);
            const seconds = Math.max(0, timeLeft) % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            const currentBlinds = blindLevels[currentLevel];
            document.getElementById('level').textContent = currentBlinds.level;
            document.getElementById('blinds').textContent = 
                `${currentBlinds.small_blind}/${currentBlinds.big_blind}`;
            document.getElementById('ante').textContent = currentBlinds.ante;
            
            if (currentLevel < blindLevels.length - 1) {
                const nextBlinds = blindLevels[currentLevel + 1];
                document.getElementById('next-blind').textContent = 
                    `${nextBlinds.small_blind}/${nextBlinds.big_blind}`;
            } else {
                document.getElementById('next-blind').textContent = 'Tournament End';
            }

            // Update minute adjustment buttons state
            document.getElementById('minusMinBtn').disabled = isRunning;
            document.getElementById('plusMinBtn').disabled = isRunning;
        }
        
        function initAudio() {
    const sounds = ['levelSound', 'endSound'];
    sounds.forEach(soundId => {
        const sound = document.getElementById(soundId);
        if (sound) {
            sound.load();
            // Set volume to 0 and play/pause to initialize
            sound.volume = 0;
            sound.play().then(() => {
                sound.pause();
                sound.volume = 1;
            }).catch(() => {});
        }
    });
}

        function playSound(soundId) {
    try {
        const sound = document.getElementById(soundId);
        if (sound) {
            sound.currentTime = 0;
            const playPromise = sound.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    // Playback started successfully
                }).catch(error => {
                    console.log('Playback prevented:', error);
                    // Create a "silent" audio context to unlock audio
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    audioContext.resume().then(() => {
                        console.log('Audio context resumed');
                        sound.play().catch(e => console.log('Retry failed:', e));
                    });
                });
            }
        }
    } catch (e) {
        console.log('Sound error:', e);
    }
}

        function startTimer() {
            if (!isRunning) {
                isRunning = true;
                document.getElementById('minusMinBtn').disabled = true;
                document.getElementById('plusMinBtn').disabled = true;
                timerInterval = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateDisplay();
                        // Play warning sound at 30 seconds
                        if (timeLeft === 30) {
                            playSound('levelSound');
                        }
                    } else {
                        if (currentLevel < blindLevels.length - 1) {
                            currentLevel++;
                            timeLeft = blindLevels[currentLevel].duration;
                            updateDisplay();
                            playSound('levelSound'); // Play sound when level changes
                        } else {
                            clearInterval(timerInterval);
                            playSound('endSound'); // Play sound when tournament ends
                            alert('Tournament finished!');
                        }
                    }
                }, 1000);
            }
        }

        function pauseTimer() {
            clearInterval(timerInterval);
            isRunning = false;
            document.getElementById('minusMinBtn').disabled = false;
            document.getElementById('plusMinBtn').disabled = false;
        }

        function resetTimer() {
            clearInterval(timerInterval);
            isRunning = false;
            currentLevel = 0;
            timeLeft = blindLevels[0].duration;
            updateDisplay();
        }

        // Time adjustment function
        function adjustTime(minutes) {
            if (!isRunning) {
                timeLeft += minutes * 60;
                if (timeLeft < 0) timeLeft = 0;
                updateDisplay();
            }
        }

        // Structure management functions
        function showEditPanel() {
    const editPanel = document.getElementById('editPanel');
    if (editPanel) {
        renderBlindEditor();
        editPanel.style.display = 'block';
    }
}

function renderBlindEditor() {
    const blindEditor = document.getElementById('blindEditor');
    if (!blindEditor) return;

    // Add headers
    const headers = `
        <div class="blind-headers">
            <div>Small Blind</div>
            <div>Big Blind</div>
            <div>Ante</div>
            <div>Duration (min)</div>
        </div>
    `;

    const rows = blindLevels.map((level, index) => `
        <div class="blind-row" data-level="${index + 1}">
            <input type="number" value="${level.small_blind}" min="0" step="25" class="small-blind">
            <input type="number" value="${level.big_blind}" min="0" step="50" class="big-blind">
            <input type="number" value="${level.ante}" min="0" step="25" class="ante">
            <input type="number" value="${level.duration / 60}" min="1" max="60" class="duration">
            ${index > 0 ? `<div class="remove-btn" onclick="removeLevel(${index})">×</div>` : ''}
        </div>
    `).join('');

    blindEditor.innerHTML = headers + rows;
}

        function addLevel() {
            const lastLevel = blindLevels[blindLevels.length - 1];
            const newLevel = {
                level: lastLevel.level + 1,
                small_blind: lastLevel.small_blind * 2,
                big_blind: lastLevel.big_blind * 2,
                ante: lastLevel.ante + 25,
                duration: 900
            };
            blindLevels.push(newLevel);
            renderBlindEditor();
        }

        function removeLevel(index) {
            if (index > 0 && index < blindLevels.length) {
                blindLevels.splice(index, 1);
                blindLevels.forEach((level, i) => level.level = i + 1);
                renderBlindEditor();
            }
        }

        // Database functions
        async function saveToDatabase() {
            const name = prompt("Enter a name for this blind structure:");
            if (!name) return;

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'save',
                        name: name,
                        levels: blindLevels
                    })
                });

                const result = await response.json();
                if (result.success) {
                    alert('Structure saved successfully!');
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                console.error('Save error:', error);
                alert('Error saving structure: ' + error.message);
            }
        }

        async function showLoadPanel() {
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'list'})
                });

                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.error);
                }

                const structures = result.structures || [];
                const html = structures.map(s => `
                    <div class="structure-item">
                        <div class="structure-info">
                            ${s.name} (${new Date(s.created_at).toLocaleDateString()})
                            <div>Levels: ${s.level_count}</div>
                        </div>
                        <div class="actions">
                            <button class="edit-btn" onclick="loadStructure(${s.id})">Load</button>
                            <button class="edit-btn" onclick="renameStructure(${s.id}, '${s.name}')">Rename</button>
                            <button class="reset-btn" onclick="deleteStructure(${s.id}, '${s.name}')">Delete</button>
                        </div>
                    </div>
                `).join('');

                document.getElementById('structuresList').innerHTML = 
                    structures.length ? html : '<div class="structure-item">No saved structures</div>';
                document.getElementById('loadPanel').style.display = 'block';
            } catch (error) {
                console.error('Load error:', error);
                alert('Error loading structures: ' + error.message);
            }
        }

        async function loadStructure(id) {
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'load', id: id})
                });

                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.error);
                }

                blindLevels = result.levels;
                currentLevel = 0;
                timeLeft = blindLevels[0].duration;
                updateDisplay();
                document.getElementById('loadPanel').style.display = 'none';
            } catch (error) {
                console.error('Load error:', error);
                alert('Error loading structure: ' + error.message);
            }
        }

        async function deleteStructure(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"?`)) {
                return;
            }

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'delete', id: id})
                });

                const result = await response.json();
                if (result.success) {
                    alert('Structure deleted successfully!');
                    showLoadPanel();
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Error deleting structure: ' + error.message);
            }
        }

        async function renameStructure(id, oldName) {
            const newName = prompt("Enter new name:", oldName);
            if (!newName || newName === oldName) return;

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'rename',
                        id: id,
                        name: newName
                    })
                });

                const result = await response.json();
                if (result.success) {
                    alert('Structure renamed successfully!');
                    showLoadPanel();
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                console.error('Rename error:', error);
                alert('Error renaming structure: ' + error.message);
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
    // Initialize all buttons and displays
    updateDisplay();
    
    // Main control buttons
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');
    
    if (startBtn) startBtn.addEventListener('click', () => {
        initAudio();
        startTimer();
    });
    if (pauseBtn) pauseBtn.addEventListener('click', pauseTimer);
    if (resetBtn) resetBtn.addEventListener('click', resetTimer);

    // Time adjustment buttons
    const minusMinBtn = document.getElementById('minusMinBtn');
    const plusMinBtn = document.getElementById('plusMinBtn');
    
    if (minusMinBtn) minusMinBtn.addEventListener('click', () => adjustTime(-1));
    if (plusMinBtn) plusMinBtn.addEventListener('click', () => adjustTime(1));

    // Structure management buttons
    const editBtn = document.getElementById('editBtn');
    const saveToDbBtn = document.getElementById('saveToDbBtn');
    const loadFromDbBtn = document.getElementById('loadFromDbBtn');
    const closeLoadBtn = document.getElementById('closeLoadBtn');
    const addLevelBtn = document.getElementById('addLevelBtn');
    const saveEditBtn = document.getElementById('saveEditBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    
    if (editBtn) editBtn.addEventListener('click', showEditPanel);
    if (saveToDbBtn) saveToDbBtn.addEventListener('click', saveToDatabase);
    if (loadFromDbBtn) loadFromDbBtn.addEventListener('click', showLoadPanel);
    if (closeLoadBtn) closeLoadBtn.addEventListener('click', () => {
        document.getElementById('loadPanel').style.display = 'none';
    });
    if (addLevelBtn) addLevelBtn.addEventListener('click', addLevel);
    
    if (saveEditBtn) {
        saveEditBtn.addEventListener('click', () => {
            const rows = document.querySelectorAll('.blind-row');
            const newStructure = Array.from(rows).map((row, index) => {
                const smallBlind = parseInt(row.querySelector('.small-blind').value) || 0;
                const bigBlind = parseInt(row.querySelector('.big-blind').value) || 0;
                const ante = parseInt(row.querySelector('.ante').value) || 0;
                const duration = (parseInt(row.querySelector('.duration').value) || 15) * 60;

                return {
                    level: index + 1,
                    small_blind: smallBlind,
                    big_blind: bigBlind,
                    ante: ante,
                    duration: duration
                };
            });

            if (validateStructure(newStructure)) {
                blindLevels = newStructure;
                currentLevel = 0;
                timeLeft = blindLevels[0].duration;
                updateDisplay();
                hideEditPanel();
            }
        });
    }
    
    if (cancelEditBtn) cancelEditBtn.addEventListener('click', hideEditPanel);

    // Initialize audio on first user interaction
    document.addEventListener('click', () => {
        initAudio();
    }, { once: true });
});

// Also make sure this function is defined at the top level of your script
function updateDisplay() {
    const minutes = Math.floor(Math.max(0, timeLeft) / 60);
    const seconds = Math.max(0, timeLeft) % 60;
    const timerDisplay = document.getElementById('timer');
    if (timerDisplay) {
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    const currentBlinds = blindLevels[currentLevel];
    const levelElement = document.getElementById('level');
    const blindsElement = document.getElementById('blinds');
    const nextBlindElement = document.getElementById('next-blind');
    
    if (levelElement) levelElement.textContent = currentBlinds.level;
    if (blindsElement) blindsElement.textContent = `${currentBlinds.small_blind}/${currentBlinds.big_blind}`;
    
    if (nextBlindElement) {
        if (currentLevel < blindLevels.length - 1) {
            const nextBlinds = blindLevels[currentLevel + 1];
            nextBlindElement.textContent = `${nextBlinds.small_blind}/${nextBlinds.big_blind}`;
        } else {
            nextBlindElement.textContent = 'Tournament End';
        }
    }

    // Update minute adjustment buttons state
    const minusMinBtn = document.getElementById('minusMinBtn');
    const plusMinBtn = document.getElementById('plusMinBtn');
    if (minusMinBtn) minusMinBtn.disabled = isRunning;
    if (plusMinBtn) plusMinBtn.disabled = isRunning;
}

function validateStructure(structure) {
    if (!Array.isArray(structure) || structure.length === 0) {
        alert('Invalid structure format');
        return false;
    }
    return true;
}

function hideEditPanel() {
    const editPanel = document.getElementById('editPanel');
    if (editPanel) {
        editPanel.style.display = 'none';
    }
}

function showEditPanel() {
    const editPanel = document.getElementById('editPanel');
    if (editPanel) {
        renderBlindEditor();
        editPanel.style.display = 'block';
    }
}

function renderBlindEditor() {
    const blindEditor = document.getElementById('blindEditor');
    if (!blindEditor) return;

    // Add headers
    const headers = `
        <div class="blind-headers">
            <div>Small Blind</div>
            <div>Big Blind</div>
            <div>Ante</div>
            <div>Duration (min)</div>
        </div>
    `;

    const rows = blindLevels.map((level, index) => `
        <div class="blind-row" data-level="${index + 1}">
            <input type="number" value="${level.small_blind}" min="0" step="25" class="small-blind">
            <input type="number" value="${level.big_blind}" min="0" step="50" class="big-blind">
            <input type="number" value="${level.ante}" min="0" step="25" class="ante">
            <input type="number" value="${level.duration / 60}" min="1" max="60" class="duration">
            ${index > 0 ? `<div class="remove-btn" onclick="removeLevel(${index})">×</div>` : ''}
        </div>
    `).join('');

    blindEditor.innerHTML = headers + rows;
}

document.addEventListener('DOMContentLoaded', () => {
    // ... existing code ...

    // Structure management buttons
    const editBtn = document.getElementById('editBtn');
    const addLevelBtn = document.getElementById('addLevelBtn');
    const saveEditBtn = document.getElementById('saveEditBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    
    if (editBtn) {
        editBtn.addEventListener('click', () => {
            showEditPanel();
        });
    }

    if (addLevelBtn) {
        addLevelBtn.addEventListener('click', () => {
            addLevel();
        });
    }
    
    if (saveEditBtn) {
        saveEditBtn.addEventListener('click', () => {
            const rows = document.querySelectorAll('.blind-row');
            const newStructure = Array.from(rows).map((row, index) => ({
                level: index + 1,
                small_blind: parseInt(row.querySelector('.small-blind').value) || 0,
                big_blind: parseInt(row.querySelector('.big-blind').value) || 0,
                ante: parseInt(row.querySelector('.ante').value) || 0,
                duration: (parseInt(row.querySelector('.duration').value) || 15) * 60
            }));

            if (validateStructure(newStructure)) {
                blindLevels = newStructure;
                currentLevel = 0;
                timeLeft = blindLevels[0].duration;
                updateDisplay();
                hideEditPanel();
            }
        });
    }
    
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', () => {
            hideEditPanel();
        });
    }

    // ... rest of your existing code ...
});
    </script>
</body>
</html>