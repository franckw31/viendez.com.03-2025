let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

const monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"];

function generateCalendar(month, year) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysGrid = document.getElementById('daysGrid');
    const monthDisplay = document.getElementById('monthDisplay');
    
    monthDisplay.textContent = `${monthNames[month]} ${year}`;
    daysGrid.innerHTML = '';

    let startingDay = firstDay.getDay();
    
    // Previous month's days
    const prevLastDay = new Date(year, month, 0).getDate();
    for (let i = startingDay - 1; i >= 0; i--) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'day other-month';
        dayDiv.textContent = prevLastDay - i;
        daysGrid.appendChild(dayDiv);
    }

    // Current month's days
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'day';
        if (i === currentDate.getDate() && month === currentDate.getMonth() 
            && year === currentDate.getFullYear()) {
            dayDiv.classList.add('today');
        }
        dayDiv.textContent = i;
        dayDiv.setAttribute('data-date', `${year}-${month + 1}-${i}`);
        daysGrid.appendChild(dayDiv);
    }

    // Next month's days
    const remainingDays = 42 - (startingDay + lastDay.getDate());
    for (let i = 1; i <= remainingDays; i++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'day other-month';
        dayDiv.textContent = i;
        daysGrid.appendChild(dayDiv);
    }

    updateCalendarDisplay();
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    generateCalendar(currentMonth, currentYear);
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    generateCalendar(currentMonth, currentYear);
});

// Initialize calendar
generateCalendar(currentMonth, currentYear);

// Modal handling
const modal = document.getElementById("eventModal");
const addEventBtn = document.getElementById("addEventBtn");
const closeBtn = document.getElementsByClassName("close")[0];
const eventForm = document.getElementById("eventForm");

// Replace the events array with a function to load events from the database
async function loadEvents() {
    try {
        const response = await fetch('get_events.php');
        const data = await response.json();
        if (data.success) {
            return data.events;
        }
        return [];
    } catch (error) {
        console.error('Error loading events:', error);
        return [];
    }
}

// Modify the eventForm submit handler
eventForm.onsubmit = async function(e) {
    e.preventDefault();
    
    const newEvent = {
        title: document.getElementById("eventTitle").value,
        date: document.getElementById("eventDate").value,
        startTime: document.getElementById("eventStartTime").value,
        endTime: document.getElementById("eventEndTime").value,
        description: document.getElementById("eventDescription").value
    };
    
    try {
        const response = await fetch('save_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(newEvent)
        });
        
        const result = await response.json();
        if (result.success) {
            modal.style.display = "none";
            eventForm.reset();
            await updateCalendarDisplay();
        } else {
            alert('Error saving event');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving event');
    }
}

async function updateCalendarDisplay() {
    const events = await loadEvents();
    const dayElements = document.querySelectorAll('.day');
    
    // Clear existing events
    dayElements.forEach(day => {
        const events = day.querySelectorAll('.calendar-event');
        events.forEach(event => event.remove());
    });
    
    // Display events
    events.forEach(event => {
        const dateStr = new Date(event.event_date).toISOString().split('T')[0];
        const dayElement = document.querySelector(`[data-date="${dateStr}"]`);
        if (dayElement) {
            const eventDiv = document.createElement('div');
            eventDiv.className = 'calendar-event';
            eventDiv.textContent = event.title;
            dayElement.appendChild(eventDiv);
        }
    });
}

// Modify the generateCalendar function to call updateCalendarDisplay
function generateCalendar(month, year) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysGrid = document.getElementById('daysGrid');
    const monthDisplay = document.getElementById('monthDisplay');
    
    monthDisplay.textContent = `${monthNames[month]} ${year}`;
    daysGrid.innerHTML = '';

    let startingDay = firstDay.getDay();
    
    // Previous month's days
    const prevLastDay = new Date(year, month, 0).getDate();
    for (let i = startingDay - 1; i >= 0; i--) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'day other-month';
        dayDiv.textContent = prevLastDay - i;
        daysGrid.appendChild(dayDiv);
    }

    // Current month's days
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'day';
        if (i === currentDate.getDate() && month === currentDate.getMonth() 
            && year === currentDate.getFullYear()) {
            dayDiv.classList.add('today');
        }
        dayDiv.textContent = i;
        dayDiv.setAttribute('data-date', `${year}-${month + 1}-${i}`);
        daysGrid.appendChild(dayDiv);
    }

    // Next month's days
    const remainingDays = 42 - (startingDay + lastDay.getDate());
    for (let i = 1; i <= remainingDays; i++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'day other-month';
        dayDiv.textContent = i;
        daysGrid.appendChild(dayDiv);
    }

    updateCalendarDisplay();
}
