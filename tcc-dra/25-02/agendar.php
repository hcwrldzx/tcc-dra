<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta - Clínica Dental</title>
    <link rel="stylesheet" href="css/inicial.css">
    <style>
        .main-content {
            padding: 3rem 2rem;
            min-height: calc(100vh - 300px);
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .left-side h2 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .left-side p {
            color: #6b7280;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .right-side {
            background: #f3f4f6;
            padding: 2rem;
            border-radius: 8px;
        }

        .right-side h3 {
            font-size: 1.5rem;
            color: #1f2937;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .consultation-time {
            color: #0ea5e9;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .right-side h4 {
            color: #374151;
            font-weight: 600;
            margin-bottom: 1.5rem;
            margin-top: 1.5rem;
        }

        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .calendar-nav button {
            background: #0ea5e9;
            color: #fff;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background 0.3s;
        }

        .calendar-nav button:hover {
            background: #0284c7;
        }

        .month-year {
            font-weight: 600;
            color: #1f2937;
            flex: 1;
            text-align: center;
        }

        .calendar {
            background: #fff;
        }

        .weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .weekday {
            text-align: center;
            font-weight: 600;
            color: #6b7280;
            font-size: 0.85rem;
            padding: 0.5rem 0;
        }

        .days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .day:hover {
            background: #e5e7eb;
        }

        .day.selected {
            background: #0ea5e9;
            color: #fff;
            border-color: #0ea5e9;
        }

        .day.disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .back-button {
            margin-bottom: 2rem;
        }

        .btn-back {
            background: #e5e7eb;
            color: #374151;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        .selected-date-text {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .time-slots {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .time-period h4 {
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .slots {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .time-slot {
            padding: 0.8rem;
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            color: #374151;
            transition: all 0.3s;
        }

        .time-slot:hover {
            border-color: #0ea5e9;
            background: #e0f2fe;
        }

        .time-slot.selected {
            background: #0ea5e9;
            color: #fff;
            border-color: #0ea5e9;
        }

        .form-header {
            background: #f3f4f6;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .form-header h3 {
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .contact-info {
            color: #9ca3af;
            font-size: 0.85rem !important;
            margin-top: 1rem !important;
        }

        #appointmentForm {
            display: grid;
            gap: 1.5rem;
            max-width: 600px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0ea5e9;
            background: #f0f9ff;
        }

        .btn-submit {
            background: #0ea5e9;
            color: #fff;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 24px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.3s;
            align-self: flex-start;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #0284c7;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 2rem 1rem;
            }

            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .time-slots {
                grid-template-columns: 1fr;
            }

            .slots {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Header / Navbar -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="./" alt="DentalSoft Logo" class="logo-img">
                <h1>Dental Soft</h1>
            </div>
            
            <nav class="navbar">
                <a href="inicial.php#home" class="nav-link">HOME</a>
                <a href="inicial.php#clinica" class="nav-link">CLÍNICA</a>
                <a href="servicos.php" class="nav-link">SERVIÇOS</a>
                <a href="inicial.php#contato" class="nav-link">CONTATO</a>
            </nav>

            <a href="agendar.php" class="btn-agendar-header">AGENDAR CONSULTA</a>
        </div>
    </header>

    <div class="container main-content">
        <!-- Primeira Etapa: Calendário -->
        <div id="step-calendar" class="step active">
            <div class="content-wrapper">
                <div class="left-side">
                    <h2>Clínica Odontológica Referência</h2>
                    <p>A Clínica Odontológica Referência foi criada com o intuito de provar aos seus pacientes cuidado e tratamento dentário completos. Atendemos desde processos simples de odontologia geral até cirurgias especializadas.</p>
                </div>

                <div class="right-side">
                    <h3>Agendar Consulta</h3>
                    <p class="consultation-time">30 minutos</p>

                    <h4>Selecione uma data</h4>

                    <div class="calendar-nav">
                        <button class="prev-month" id="prevMonth">‹</button>
                        <span id="monthYear" class="month-year">março 2026</span>
                        <button class="next-month" id="nextMonth">›</button>
                    </div>

                    <div class="calendar">
                        <div class="weekdays">
                            <div class="weekday">do</div>
                            <div class="weekday">2ª</div>
                            <div class="weekday">3ª</div>
                            <div class="weekday">4ª</div>
                            <div class="weekday">5ª</div>
                            <div class="weekday">6ª</div>
                            <div class="weekday">sá</div>
                        </div>
                        <div id="calendarDays" class="days"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Etapa: Horários -->
        <div id="step-time" class="step">
            <div class="back-button">
                <button id="backToCalendar" class="btn-back">← DE VOLTA</button>
            </div>

            <h3>Agendar Consulta</h3>
            <p class="consultation-time">30 minutos</p>

            <p id="selectedDateText" class="selected-date-text">Selecione um horário em 18 de março de 2026</p>

            <div class="time-slots">
                <div class="time-period">
                    <h4>Manhã</h4>
                    <div class="slots" id="morningSlots">
                        <button class="time-slot" data-time="09:00">09:00</button>
                        <button class="time-slot" data-time="10:00">10:00</button>
                        <button class="time-slot" data-time="11:00">11:00</button>
                    </div>
                </div>

                <div class="time-period">
                    <h4> Tarde</h4>
                    <div class="slots" id="afternoonSlots">
                        <button class="time-slot" data-time="13:00">13:00</button>
                        <button class="time-slot" data-time="15:00">15:00</button>
                        <button class="time-slot" data-time="16:00">16:00</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terceira Etapa: Formulário -->
        <div id="step-form" class="step">
            <div class="back-button">
                <button id="backToTime" class="btn-back">← DE VOLTA</button>
            </div>

            <div class="form-header">
                <h3>Você está registrando: Agendar Consulta</h3>
                <p id="confirmationText">18 de março de 2026 15:00 - 15:30 America/Sao Paulo</p>
                <p class="contact-info">Se você ficou com alguma dúvida, entre em contato conosco pelo (11) 5555-5555</p>
            </div>

            <form id="appointmentForm" method="POST" action="processar_agendamento.php">
                <div class="form-group">
                    <label for="nome">
                        <span class="icon"></span> Nome*
                    </label>
                    <input type="text" id="nome" name="nome" required placeholder="Seu nome completo">
                </div>

                <div class="form-group">
                    <label for="email">
                        <span class="icon"></span> E-mail*
                    </label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com">
                </div>

                <div class="form-group">
                    <label for="telefone">
                        <span class="icon"></span> Telefone*
                    </label>
                    <input type="tel" id="telefone" name="telefone" required placeholder="(11) 9999-9999" pattern="[\(\)\s\-\d]+">
                </div>

                <div class="form-group">
                    <label for="cidade">
                        <span class="icon"></span> Cidade*
                    </label>
                    <input type="text" id="cidade" name="cidade" required placeholder="Sua cidade">
                </div>

                <div class="form-group">
                    <label for="anotacao">
                        <span class="icon"></span> Anotação
                    </label>
                    <textarea id="anotacao" name="anotacao" placeholder="Adicione observações sobre sua consulta" rows="4"></textarea>
                </div>

                <!-- Hidden fields for date and time -->
                <input type="hidden" id="selectedDate" name="selectedDate">
                <input type="hidden" id="selectedTime" name="selectedTime">

                <button type="submit" class="btn-submit">Confirmar Agendamento</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Dental Soft</h4>
                    <p>Clínica odontológica de referência em atendimento de qualidade.</p>
                </div>
                <div class="footer-section">
                    <h4>Horário</h4>
                    <p>Segunda a Sexta: 9h às 18h<br>Sábado: 9h às 13h</p>
                </div>
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p>Telefone: (XX) XXXX-XXXX<br>Email: contato@dentalsoft.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Dental Soft. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="js/agendar.js"></script>
</body>
</html>
