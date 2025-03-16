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

// Format month with leading zero if needed
$month_padded = str_pad($month, 2, '0', STR_PAD_LEFT);

// Modify the query to use the padded month
$query = "SELECT * FROM events 
          WHERE DATE_FORMAT(start_date, '%m') = '$month_padded' 
          AND YEAR(start_date) = '$year'";
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
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
        }
        body {
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            display: flex;
            flex-direction: column;
            padding-bottom: 60px; /* Height of footer */
        }
        .main-container {
            flex: 1 0 auto;
            padding: 10px;
            width: calc(100% - 20px);
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 50px; /* Réduit de 80px pour s'adapter au header plus petit */
        }
        .calendar-wrapper {
            width: 100%;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
        }
        .header-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            z-index: 100;
            padding: 8px 16px; /* Réduit de 16px 20px */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 40px; /* Ajout d'une hauteur fixe */
        }
        .header-nav .today-btn {
            padding: 4px 12px; /* Réduit de 8px 16px */
            font-size: 13px; /* Réduit la taille de police */
        }
        .calendar {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            table-layout: fixed;
            border-right: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        .calendar th {
            padding: 10px;
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            border-bottom: 1px solid #e0e0e0;
            border-left: 1px solid #e0e0e0;
            background-color: #1a73e8;
        }

        .calendar th:first-child {
            border-top-left-radius: 8px;
        }

        .calendar th:last-child {
            border-top-right-radius: 8px;
        }
        .calendar td {
            border-left: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            width: 14.28%;
            position: relative;
            padding: 0;
            min-height: 60px; /* Hauteur minimale réduite */
            height: auto; /* Permet l'extension automatique */
            vertical-align: top;
            background: #fff;
        }
        .day-header {
            padding: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-weight: bold;
            min-height: 20px;
        }
        .day-header span {
            color: #70757a;
            font-size: 18px;
            font-weight: bold;
        }
        .today {
            background-color: #e8f0fe !important;
        }
        .today .day-header span {
            background: #1a73e8;
            color: white;
            border-radius: 50%;
            width: 32px;  /* Increased from 24px */
            height: 32px; /* Increased from 24px */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px; /* Increased from 16px */
        }
        .event-container {
            padding: 0 4px;
            min-height: 30px;
            height: auto; /* Permet l'extension automatique */
            overflow-y: visible; /* Permet au contenu de s'étendre */
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .event {
            margin: 0 0 2px 0;
            padding: 0 4px;
            border-radius: 4px;
            background-color: #1a73e8;
            color: white;
            font-size: 13px;
            height: 24px;
            line-height: 24px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer; /* Add cursor pointer */
        }
        .event-title-line {
            cursor: pointer;
            width: 100%;
            height: 100%;
            display: block;
        }
        .add-event {
            opacity: 0;
            background: none;
            border: none;
            color: #1a73e8;
            font-size: 20px;
            line-height: 20px;
            padding: 0 4px;
            cursor: pointer;
        }
        td:hover .add-event {
            opacity: 1;
        }
        .header-nav h2 {
            margin: 0;
            color: #3c4043;
            font-size: 20px; /* Réduit de 26px */
            font-weight: 400;
        }
        .navigation {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        /* Add/modify responsive styles */
        @media screen and (max-width: 768px) {
            .main-container {
                padding: 5px;
                width: calc(100% - 10px);
            }
            
            .calendar td {
                min-height: 50px;
            }

            .event-container {
                min-height: 25px;
            }

            .day-header span {
                font-size: 14px;
            }

            .event {
                font-size: 11px;
                height: 20px;
                line-height: 20px;
            }
        }

        @media screen and (max-width: 480px) {
            .calendar td {
                min-height: 40px;
            }

            .event-container {
                min-height: 20px;
            }

            .day-header {
                min-height: 16px;
                padding: 4px;
            }

            .day-header span {
                font-size: 12px;
            }
        }

        .footer {
            flex-shrink: 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 50px;
            background: white;
            border-top: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        }

        .day-events {
            margin-top: 3px; /* Reduced from 10px */
            padding: 10px; /* Reduced from 20px */
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .day-events .event {
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            background-color: white;
            border: 1px solid #e0e0e0;
            color: #333;
            cursor: pointer;
            height: auto;
            line-height: 1.4;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .day-events .event:hover {
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            transform: translateY(-1px);
        }
        
        .day-events .event-title-line {
            font-size: 16px;
            color: #1a73e8;
            margin-bottom: 8px;
        }
        
        .event-description {
            margin: 8px 0;
            font-size: 14px;
            color: #5f6368;
            line-height: 1.5;
            padding: 6px 0;
            /* Removed border-top and border-bottom */
        }
        
        .event-time {
            font-size: 13px;
            color: #3c4043;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .event-time::before {
            content: '🕒';
            font-size: 14px;
        }

        .add-event-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .add-event-btn:hover {
            background: #185abc;
        }

        .edit-form {
            margin: 20px 0;
            padding: 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .edit-form h3 {
            color: #1a73e8;
            margin: 0 0 20px 0;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #3c4043;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="datetime-local"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 14px;
            color: #3c4043;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26,115,232,0.2);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 15px; /* Reduced from 25px */
            padding-top: 15px; /* Reduced from 20px */
            border-top: 1px solid #e0e0e0;
        }

        .button-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .button-group button[type="submit"] {
            background: #1a73e8;
            color: white;
        }

        .button-group button[type="submit"]:hover {
            background: #185abc;
        }

        .button-group button[type="button"] {
            background: #f1f3f4;
            color: #3c4043;
        }

        .button-group button[type="button"]:hover {
            background: #e8eaed;
        }

        .delete-btn {
            background: #dc3545 !important;
            color: white !important;
            margin-left: auto;
        }

        .delete-btn:hover {
            background: #c82333 !important;
        }

        .day-events h3 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 0 20px 0;  /* Remove top margin from h3 */
        }

        .day-events .add-event-btn {
            display: none; /* Hide button instead of removing styles completely */
        }

        .event-title-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .event-time {
            font-size: 12px;
            color: #5f6368;
            white-space: nowrap;
        }

        .event-time::before {
            display: none; /* Remove clock emoji */
        }

        .date-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .date-group .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .day-navigation {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 10px;
            padding: 5px 0;
        }

        .day-nav-btn {
            background: none;
            border: none;
            color: #1a73e8;
            cursor: pointer;
            padding: 5px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .day-nav-btn:hover {
            opacity: 0.8;
        }

        .day-nav-controls {
            display: flex;
            gap: 5px;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <?php
    // Fix month number by removing leading zeros
    $month_number = intval($month);
    
    // Add current date variables
    $current_day = date('d');
    $current_month = date('m');
    $current_year = date('Y');
    ?>
    
    <div class="main-container">
        <div class="header-nav">
            <button class="today-btn" onclick="window.location.href='agenda.php?month=<?php echo date('m'); ?>&year=<?php echo date('Y'); ?>'">
                Aujourd'hui
            </button>
            <h2>Planning : <?php echo $months_fr[$month_number]; ?></h2>
            <div class="navigation">
                <a href="javascript:void(0)" 
                   onclick="changeMonth('<?php echo str_pad($month-1, 2, '0', STR_PAD_LEFT); ?>', '<?php echo $year; ?>')" 
                   class="nav-icon">&#9664;</a>
                <a href="javascript:void(0)" 
                   onclick="changeMonth('<?php echo str_pad($month+1, 2, '0', STR_PAD_LEFT); ?>', '<?php echo $year; ?>')" 
                   class="nav-icon">&#9654;</a>
            </div>
        </div>

        <div class="calendar-wrapper">
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
                    $is_weekend = (date('N', strtotime($current_date)) >= 6) ? ' weekend' : ''; // Check if weekend

                    echo "<td class='{$is_today}{$is_weekend}' data-date='$year-$month_padded-" . str_pad($day, 2, '0', STR_PAD_LEFT) . "'>";
                    echo "<div class='day-header'>";
                    echo "<span>$day</span>";
                    echo "<button class='add-event' onclick='addEvent(\"$year-$month-$day\")'>+</button>";
                    echo "</div>";
                    echo "<div class='event-container'>";
                    
                    if (isset($events_by_day[$day])) {
                        foreach ($events_by_day[$day] as $event) {
                            echo "<div class='event' data-event-id='" . $event['id'] . "' onclick='editEvent(" . $event['id'] . ")'>";
                            echo "<div class='event-title-line'>" . 
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
        </div>

        <div class="day-events">
            <h3>
                <span>Événements du <span id="selectedDate"></span></span>
                <div class="day-nav-controls">
                    <button class="day-nav-btn" onclick="navigateDay(-1)">◀</button>
                    <button class="day-nav-btn" onclick="navigateDay(1)">▶</button>
                </div>
            </h3>
            <div id="dayEventsList"></div>
        </div>

        <div class="edit-form" id="editForm" style="display:none;">
            <h3 id="formTitle">Modifier l'événement</h3>
            <form id="eventForm" onsubmit="return saveEvent(event)">
                <input type="hidden" id="eventId" name="id">
                <input type="hidden" id="formMode" name="mode" value="edit">
                <div class="form-group">
                    <label>Titre :</label>
                    <input type="text" id="eventTitle" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description :</label>
                    <textarea id="eventDescription" name="description"></textarea>
                </div>
                <div class="date-group">
                    <div class="form-group">
                        <label>Date de début :</label>
                        <input type="datetime-local" id="eventStart" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label>Date de fin :</label>
                        <input type="datetime-local" id="eventEnd" name="end_date">
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit">Enregistrer</button>
                    <button type="button" onclick="hideEditForm()">Annuler</button>
                    <button type="button" class="delete-btn" onclick="deleteEvent()">Supprimer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Function to initialize calendar with current day events
    function initializeCalendar() {
        const today = new Date();
        const currentDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        
        // Show events for current day
        showDayEvents(currentDate);
        
        // Scroll to today's cell
        const todayCell = document.querySelector('.today');
        if (todayCell) {
            todayCell.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Call initialization when DOM is loaded
    document.addEventListener('DOMContentLoaded', initializeCalendar);

    // Replace the event delegation handler
    document.addEventListener('click', function(e) {
        const target = e.target;

        // Handle event clicks
        if (target.closest('.event')) {
            e.preventDefault();
            e.stopPropagation();
            const eventElement = target.closest('.event');
            const eventId = eventElement.dataset.eventId;
            if (eventId) {
                editEvent(eventId);
            }
            return;
        }

        // Handle add event button clicks
        if (target.classList.contains('add-event')) {
            e.preventDefault();
            e.stopPropagation();
            const cell = target.closest('td');
            if (cell) {
                const date = cell.dataset.date;
                if (date) {
                    addEvent(date);
                }
            }
            return;
        }

        // Handle calendar cell clicks
        if (target.closest('td')) {
            const cell = target.closest('td');
            const date = cell.dataset.date;
            if (date) {
                showDayEvents(date);
            }
        }
    });

    function clearContent() {
        document.getElementById('editForm').style.display = 'none';
        // Don't hide day-events here anymore
    }
    
    function showDayEvents(date) {
        if (!date) return;
        
        selectedDate = date; // Store the current date
        const selectedDateElement = document.getElementById('selectedDate');
        const eventsList = document.getElementById('dayEventsList');
        const dayEvents = document.querySelector('.day-events');
        
        document.getElementById('editForm').style.display = 'none';
        dayEvents.style.display = 'block';
        
        const displayDate = new Date(date).toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'long'
        }); // Removed year from format
        
        selectedDateElement.textContent = displayDate;
        eventsList.innerHTML = '<p>Chargement...</p>';
        
        fetch('get_day_events.php?date=' + date)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(response => {
                if (!response.success) throw new Error(response.error || 'Failed to load events');
                const events = response.data;
                
                eventsList.innerHTML = events.length ? 
                    events.map(event => `
                        <div class="event" data-event-id="${event.id}" onclick="editEvent(${event.id})">
                            <div class="event-title-line">
                                <strong>${event.title}</strong>
                                <span class="event-time">
                                    ${new Date(event.start_date).toLocaleTimeString('fr-FR', {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}
                                    ${event.end_date ? 
                                        ` - ${new Date(event.end_date).toLocaleTimeString('fr-FR', {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}` : 
                                        ''}
                                </span>
                            </div>
                            ${event.description ? 
                                `<div class="event-description">${event.description}</div>` : 
                                ''}
                        </div>
                    `).join('') :
                    '<p>Aucun événement ce jour</p>';
            })
            .catch(error => {
                console.error('Error:', error);
                eventsList.innerHTML = '<p>Erreur lors du chargement des événements</p>';
            });
    }

    function addEvent(date) {
        clearContent();
        document.querySelector('.day-events').style.display = 'none';
        document.getElementById('formTitle').textContent = 'Nouvel événement';
        document.getElementById('eventForm').reset();
        document.getElementById('formMode').value = 'add';
        document.getElementById('eventStart').value = date + 'T00:00';
        document.getElementById('eventEnd').value = date + 'T00:00';
        document.getElementById('editForm').style.display = 'block';
        // Cacher le bouton supprimer pour un nouvel événement
        document.querySelector('.delete-btn').style.display = 'none';
    }

    function editEvent(id) {
        if (!id) {
            console.error('No event ID provided');
            return;
        }
        
        // Show loading state
        document.querySelector('.day-events').style.display = 'none';
        document.getElementById('formTitle').textContent = 'Chargement...';
        document.getElementById('editForm').style.display = 'block';
        
        fetch('get_event.php?id=' + id)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(event => {
                if (!event || !event.id) throw new Error('Invalid event data');
                
                // Format the date in French
                const eventDate = new Date(event.start_date).toLocaleDateString('fr-FR', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                
                document.getElementById('formTitle').textContent = `Modifier l'événement du ${eventDate}`;
                document.getElementById('eventId').value = event.id;
                document.getElementById('eventTitle').value = event.title || '';
                document.getElementById('eventDescription').value = event.description || '';
                document.getElementById('eventStart').value = event.start_date.substr(0, 16);
                document.getElementById('eventEnd').value = event.end_date ? event.end_date.substr(0, 16) : '';
                document.getElementById('formMode').value = 'edit';
                document.querySelector('.delete-btn').style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading event:', error);
                document.getElementById('editForm').style.display = 'none';
                alert('Erreur lors du chargement de l\'événement');
            });
    }

    function hideEditForm() {
        document.getElementById('editForm').style.display = 'none';
        document.getElementById('eventForm').reset();
        document.querySelector('.day-events').style.display = 'block';
    }

    function saveEvent(e) {
        e.preventDefault();
        const formData = new FormData(document.getElementById('eventForm'));
        const mode = document.getElementById('formMode').value;
        const url = mode === 'add' ? 'add_event.php' : 'save_event.php';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                hideEditForm();
                // Refresh the current view
                const currentDate = document.querySelector('td.today')?.dataset.date || 
                                  document.querySelector('td[data-date]')?.dataset.date;
                if (currentDate) {
                    showDayEvents(currentDate);
                }
                location.reload(); // Refresh to show new event
            } else {
                throw new Error(data.error || 'Failed to save event');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving event: ' + error.message);
        });
        
        return false;
    }

    function deleteEvent() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
            const id = document.getElementById('eventId').value;
            fetch('delete_event.php?id=' + id)
            .then(() => {
                hideEditForm();
                reloadEvents();
            });
        }
    }

    function changeMonth(month, year) {
        clearContent();
        window.location.href = `agenda.php?month=${month}&year=${year}`;
    }

    function reloadEvents() {
        const urlParams = new URLSearchParams(window.location.search);
        const month = urlParams.get('month');
        const year = urlParams.get('year');
        
        if (month && year) {
            fetch('get_month_events.php?month=' + month + '&year=' + year)
                .then(response => response.json())
                .then(events => {
                    // Mettre à jour les événements dans le calendrier
                    location.reload();
                });
        }
    }

    function navigateDay(offset) {
        const currentDate = new Date(selectedDate || new Date());
        currentDate.setDate(currentDate.getDate() + offset);
        const newDate = currentDate.toISOString().split('T')[0];
        showDayEvents(newDate);
    }

    let selectedDate; // Add this at the top of your script
    </script>

    <div class="footer">
        <a href="/">Retour au site</a>
    </div>

</body>
</html>
