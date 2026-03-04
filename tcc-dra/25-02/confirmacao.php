<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Agendamento - Minha Clínica</title>
    <link rel="stylesheet" href="css/confirmacao.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="images/logo-dentalsoft.svg" alt="DentalSoft Logo" class="logo-img">
                <h1>Minha Clínica</h1>
            </div>
        </div>
    </header>

    <div class="container main-content">
        <div class="confirmation-card">
            <div class="success-icon">✓</div>
            
            <h1>Agendamento Confirmado!</h1>
            
            <p class="message">Sua consulta foi agendada com sucesso. Um email de confirmação foi enviado.</p>

            <div class="details">
                <div class="detail-item">
                    <span class="label">Nome:</span>
                    <span class="value" id="confirmNome">-</span>
                </div>
                <div class="detail-item">
                    <span class="label">Email:</span>
                    <span class="value" id="confirmEmail">-</span>
                </div>
                <div class="detail-item">
                    <span class="label">Telefone:</span>
                    <span class="value" id="confirmTelefone">-</span>
                </div>
                <div class="detail-item">
                    <span class="label">Cidade:</span>
                    <span class="value" id="confirmCidade">-</span>
                </div>
                <div class="detail-item">
                    <span class="label">Data:</span>
                    <span class="value" id="confirmData">-</span>
                </div>
                <div class="detail-item">
                    <span class="label">Horário:</span>
                    <span class="value" id="confirmHorario">-</span>
                </div>
            </div>

            <p class="contact-notice">Se tiver dúvidas, entre em contato conosco pelo (11) 5555-5555</p>

            <a href="inicial.php" class="btn-home">Voltar à Página Inicial</a>
        </div>
    </div>

    <script>
        // Ler dados da sessão e exibir
        // Para isso seria necessário passar os dados via PHP ou localStorage
        
        // Usando localStorage como alternativa
        const agendamento = JSON.parse(localStorage.getItem('agendamento') || '{}');
        
        if (agendamento.nome) {
            document.getElementById('confirmNome').textContent = agendamento.nome;
            document.getElementById('confirmEmail').textContent = agendamento.email;
            document.getElementById('confirmTelefone').textContent = agendamento.telefone;
            document.getElementById('confirmCidade').textContent = agendamento.cidade;
            document.getElementById('confirmData').textContent = agendamento.data;
            document.getElementById('confirmHorario').textContent = agendamento.horario;
        }
    </script>
</body>
</html>
