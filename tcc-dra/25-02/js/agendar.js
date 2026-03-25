// Calendar functionality
let currentDate = new Date(2026, 2, 2); // Start at March 2026
let selectedDate = null;
let selectedTime = null;

const monthYear = document.getElementById('monthYear');
const calendarDays = document.getElementById('calendarDays');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');
const backToCalendarBtn = document.getElementById('backToCalendar');
const backToTimeBtn = document.getElementById('backToTime');
const selectedDateInput = document.getElementById('selectedDate');
const selectedTimeInput = document.getElementById('selectedTime');

// Portuguese month names
const monthNames = [
    'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
    'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
];

// Initialize calendar
function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Update header
    monthYear.textContent = `${monthNames[month]} ${year}`;

    // Clear previous days
    calendarDays.innerHTML = '';

    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    // Adjust for Sunday = 0 to Sunday = 6 (week starts on Sunday)
    const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();

    // Previous month days
    for (let i = adjustedFirstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        const dayElement = createDayElement(day, 'other-month', false);
        calendarDays.appendChild(dayElement);
    }

    // Current month days
    const today = new Date();
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const isToday = date.toDateString() === today.toDateString();
        const isPast = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
        const isDisabled = isPast && !isToday;
        
        const dayElement = createDayElement(
            day,
            isDisabled ? 'disabled' : 'available',
            !isDisabled
        );
        dayElement.dataset.date = date.toISOString().split('T')[0];
        
        if (!isDisabled) {
            dayElement.addEventListener('click', () => selectDate(dayElement, date));
        }

        if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
            dayElement.classList.add('selected');
        }

        calendarDays.appendChild(dayElement);
    }

    // Next month days
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells; // 6 weeks * 7 days
    for (let day = 1; day <= remainingCells; day++) {
        const dayElement = createDayElement(day, 'other-month', false);
        calendarDays.appendChild(dayElement);
    }
}

function createDayElement(day, className, isClickable) {
    const dayElement = document.createElement('div');
    dayElement.className = `calendar-day ${className}`;
    dayElement.textContent = day;
    return dayElement;
}

function selectDate(element, date) {
    // Remove previous selection
    document.querySelectorAll('.calendar-day.selected').forEach(el => {
        el.classList.remove('selected');
    });

    // Add selection to clicked element
    element.classList.add('selected');
    selectedDate = date;
    selectedDateInput.value = date.toISOString().split('T')[0];

    // Move to time selection step
    goToStep('time');
    updateDateDisplay();
}

function updateDateDisplay() {
    if (selectedDate) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = selectedDate.toLocaleDateString('pt-BR', options);
        const capitalizedDate = dateString.charAt(0).toUpperCase() + dateString.slice(1);
        document.getElementById('selectedDateText').textContent = `Selecione um horário em ${capitalizedDate}`;
        document.getElementById('confirmationText').textContent = `${capitalizedDate} ${selectedTime || '15:00'} - ${getEndTime(selectedTime || '15:00')} America/Sao Paulo`;
    }
}

function getEndTime(time) {
    const [hours, minutes] = time.split(':');
    const date = new Date();
    date.setHours(parseInt(hours), parseInt(minutes), 0);
    date.setMinutes(date.getMinutes() + 30);
    return `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
}

// Time slot selection
document.querySelectorAll('.time-slot').forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove previous selection
        document.querySelectorAll('.time-slot.selected').forEach(el => {
            el.classList.remove('selected');
        });

        // Add selection to clicked button
        btn.classList.add('selected');
        selectedTime = btn.dataset.time;
        selectedTimeInput.value = selectedTime;
        updateDateDisplay();

        // Move to form step
        setTimeout(() => {
            goToStep('form');
        }, 300);
    });
});

// Navigation between steps
prevMonthBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextMonthBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

backToCalendarBtn.addEventListener('click', () => {
    goToStep('calendar');
});

backToTimeBtn.addEventListener('click', () => {
    goToStep('time');
});

function goToStep(step) {
    // Hide all steps
    document.querySelectorAll('.step').forEach(el => {
        el.classList.remove('active');
    });

    // Show target step
    if (step === 'calendar') {
        document.getElementById('step-calendar').classList.add('active');
    } else if (step === 'time') {
        document.getElementById('step-time').classList.add('active');
    } else if (step === 'form') {
        document.getElementById('step-form').classList.add('active');
    }
}

// Form submission
document.getElementById('appointmentForm').addEventListener('submit', (e) => {
    e.preventDefault();
    
    // Salvare dados do agendamento em localStorage para exibir na página de confirmação
    const agendamento = {
        nome: document.getElementById('nome').value,
        email: document.getElementById('email').value,
        telefone: document.getElementById('telefone').value,
        cidade: document.getElementById('cidade').value,
        data: selectedDate.toLocaleDateString('pt-BR'),
        horario: selectedTime
    };
    
    localStorage.setItem('agendamento', JSON.stringify(agendamento));
    
    // Aqui você poderia fazer um request AJAX para salvar no banco de dados
    // Por enquanto, redireciona para confirmação
    window.location.href = 'confirmacao.php';
});

// Initialize
renderCalendar();
