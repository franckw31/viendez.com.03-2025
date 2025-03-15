<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('include/config.php');

// Handle month navigation properly
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Fix month overflow/underflow
if ($month > 12) {
    $month = 1;
    $year++;
}
if ($month < 1) {
    $month = 12;
    $year--;
}

// Get the first day of the month
$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$day_of_week = date('w', $first_day);

// Fix the query to handle date format correctly
$query = "SELECT * FROM events WHERE DATE_FORMAT(start_date, '%m') = '$month' AND DATE_FORMAT(start_date, '%Y') = '$year'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$events = [];
while($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

// Create events array indexed by day
$events_by_day = [];
foreach ($events as $event) {
    $day = date('j', strtotime($event['start_date']));
    $events_by_day[$day][] = $event;
}

setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');

$months_fr = array(
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
);

$days_fr = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des événements</title>
    <style>
        .calendar { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .calendar th { 
            border: 1px solid #ddd; 
            padding: 2px; 
            height: 25px;
            font-size: 13px;
            background-color: #f8f9fa;
        }
        .calendar td { 
            border: 1px solid #ddd; 
            padding: 4px; 
            vertical-align: top; 
            height: 80px;
            width: 14.28%; 
            position: relative;
            font-size: 14px;
        }
        .event-container {
            max-height: 50px; /* reduced from 85px */
            overflow-y: auto;
            margin-bottom: 15px;
        }
        .event { 
            background-color: #f0f0f0; 
            margin: 1px; 
            padding: 3px; 
            border-radius: 2px;
            font-size: 12px;
            display: block;
        }
        .event span { 
            cursor: pointer;
            display: flex;
            align-items: left;
            width: 100%;
            font-size: 12px;
            font-weight: bold;
        }
        .event span:hover {
            background: rgba(0,0,0,0.05);
            border-radius: 2px;
        }
        .event-time {
            color: #666;
            font-weight: normal;
            margin-right: 5px;
            font-size: 12px;
            white-space: nowrap;
        }
        .add-event { 
            background-color: #4CAF50; 
            color: white; 
            padding: 1px 3px; 
            text-decoration: none; 
            border-radius: 2px;
            font-size: 12px; /* increased from 10px */
            margin-left: auto; /* Change from margin-left: 5px */
        }
        .day-header {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 16px; /* increased size for day numbers */
            justify-content: space-between; /* Add this line */
        }
        .event-actions { margin-top: 2px; }
        .today { background-color: #e8f5e9; }
        @media screen and (max-width: 768px) {
            .calendar th, .calendar td {
                padding: 2px;
                height: 50px;
                font-size: 12px;
            }
            .event { font-size: 10px; }
            .event-actions { display: flex; }
            .day-header { font-size: 14px; }
            .event-container { max-height: 40px; }
            .event span { 
                font-size: 12px; 
            }
        }
        @media screen and (max-width: 480px) {
            .calendar th, .calendar td {
                height: 50px;
                font-size: 11px;
            }
            .event-actions { flex-direction: column; }
            .event span { 
                font-size: 11px; 
            }
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
        }
        .modal-content {
            background: white;
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
        }
        .calendar td { cursor: pointer; }
        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: right;
            margin-bottom: 10px;
        }
        .navigation {
            display: flex;
            gap: 10px;
        }
        .event-title-line {
            display: flex;
            align-items: left;
            white-space: nowrap;
            overflow: hidden;
        }
        .event-title-text {
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <?php
    // Fix month number by removing leading zeros
    $month_number = intval($month);
    ?>
    
    <div class="header-nav">
        <h2><?php echo $months_fr[$month_number] . ' ' . $year; ?></h2>
        <div class="navigation">
            <a href="?month=<?php echo $month-1; ?>&year=<?php echo $year; ?>" class="edit-btn">Précédent</a>
            <a href="?month=<?php echo $month+1; ?>&year=<?php echo $year; ?>" class="edit-btn">Suivant</a>
        </div>
    </div>

    <table class="calendar">
        <tr>
            <?php foreach($days_fr as $day) echo "<th>$day</th>"; ?>
        </tr>
        <?php
        $day_count = 1;
        $cell_count = 0;
        $today = date('Y-m-d');

        echo "<tr>";
        
        // Fill in blank cells for first week
        for ($i = 0; $i < $day_of_week; $i++) {
            echo "<td></td>";
            $cell_count++;
        }

        // Fill in days of month
        for ($day = 1; $day <= $days_in_month; $day++) {
            if ($cell_count == 7) {
                echo "</tr><tr>";
                $cell_count = 0;
            }

            $current_date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
            $is_today = ($current_date == $today) ? ' class="today"' : '';

            echo "<td{$is_today} onclick='showDayEvents(\"$year-$month-$day\")'>";
            echo "<div class='day-header'>";
            echo "<span>$day</span>";
            echo "<a href='add_event.php?date=$year-$month-$day' class='add-event'>+</a>";
            echo "</div>";
            echo "<div class='event-container'>";
            
            if (isset($events_by_day[$day])) {
                foreach ($events_by_day[$day] as $event) {
                    echo "<div class='event'>";
                    echo "<div class='event-title-line' onclick='window.location.href=\"edit_event.php?id=" . $event['id'] . "\"'>" . 
                         "<span class='event-title-text'>" . htmlspecialchars($event['title']) . "</span></div>";
                    echo "</div>";
                }
            }
            
            echo "</div>";
            echo "</td>";

            $cell_count++;
        }

        // Fill in remaining cells
        while ($cell_count < 7) {
            echo "<td></td>";
            $cell_count++;
        }

        echo "</tr>";
        ?>
    </table>

    <div id="dayEventsModal" class="modal">
        <div class="modal-content">
            <h3>Événements du <span id="selectedDate"></span></h3>
            <div id="dayEventsList"></div>
            <button onclick="document.getElementById('dayEventsModal').style.display='none'">Fermer</button>
        </div>
    </div>

    <script>
    function showDayEvents(date) {
        const modal = document.getElementById('dayEventsModal');
        const eventsList = document.getElementById('dayEventsList');
        const selectedDate = document.getElementById('selectedDate');
        
        // Format date for display
        const displayDate = new Date(date).toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        selectedDate.textContent = displayDate;
        
        // Get events for this day from PHP
        fetch('get_day_events.php?date=' + date)
            .then(response => response.json())
            .then(events => {
                eventsList.innerHTML = events.length ? 
                    events.map(event => `
                        <div class="event">
                            <div class="event-title-line" onclick='window.location.href="edit_event.php?id=${event.id}"' style="cursor:pointer">
                                <span class="event-title-text">${event.title}</span>
                            </div>
                            ${event.description ? `<p>${event.description}</p>` : ''}
                        </div>
                    `).join('') :
                    '<p>Aucun événement ce jour</p>';
                
                eventsList.innerHTML += `
                    <a href="add_event.php?date=${date}" class="add-event">Ajouter un événement</a>
                `;
            });
        
        modal.style.display = 'block';
    }
    </script>
</body>
</html>
