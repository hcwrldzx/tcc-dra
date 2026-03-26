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
        const isSunday = date.getDay() === 0;
        const isDisabled = (isPast && !isToday) || isSunday;
        
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
    if (date.getDay() === 0) {
        alert('Domingo não está disponível para agendamento. Por favor escolha outra data.');
        return;
    }

    // Remove previous selection
    document.querySelectorAll('.calendar-day.selected').forEach(el => {
        el.classList.remove('selected');
    });

    // Add selection to clicked element
    element.classList.add('selected');
    selectedDate = date;
    selectedDateInput.value = date.toISOString().split('T')[0];

    // Atualiza slots de horário, texto e vai para etapa de horário
    renderTimeSlots();
    updateDateDisplay();
    goToStep('time');
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

// Return available time slots based on weekday
function getAvailableSlots(date) {
    const day = date.getDay(); // 0 Domingo, 6 Sábado

    if (day === 0) {
        return []; // Domingo indisponível
    }

    if (day === 6) {
        // Sábado 9-12
        return ['09:00', '10:00', '11:00', '12:00'];
    }

    // Segunda a sexta: 9-12 e 13-19
    return ['09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00'];
}

// Atualiza slots na página e anexa os eventos
function renderTimeSlots() {
    const afternoonSlots = document.getElementById('afternoonSlots');
    const morningSlots = document.getElementById('morningSlots');

    morningSlots.innerHTML = '';
    afternoonSlots.innerHTML = '';

    if (!selectedDate) {
        document.getElementById('selectedDateText').textContent = 'Selecione um dia no calendário para ver os horários.';
        return;
    }

    const slots = getAvailableSlots(selectedDate);

    if (slots.length === 0) {
        document.getElementById('selectedDateText').textContent = 'Clínica não disponível para agendamentos aos domingos.';
        return;
    }

    slots.forEach((time) => {
        const btn = document.createElement('button');
        btn.className = 'time-slot';
        btn.dataset.time = time;
        btn.textContent = time;

        btn.addEventListener('click', () => {
            document.querySelectorAll('.time-slot.selected').forEach(el => {
                el.classList.remove('selected');
            });

            btn.classList.add('selected');
            selectedTime = time;
            selectedTimeInput.value = selectedTime;
            updateDateDisplay();

            setTimeout(() => {
                goToStep('form');
            }, 300);
        });

        const hour = parseInt(time.split(':')[0], 10);
        if (hour < 13) {
            morningSlots.appendChild(btn);
        } else {
            afternoonSlots.appendChild(btn);
        }
    });

    if (slots.length > 0) {
        document.getElementById('selectedDateText').textContent = `Selecione um horário em ${selectedDate.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}`;
    }
}

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
currentDate = new Date();
renderCalendar();
renderTimeSlots();
