<?php
session_start();
session_destroy();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Dental Soft</title>
    <link rel="stylesheet" href="css/inicial.css">
    <link rel="stylesheet" href="css/adm.css">
    <style>
        .message-box {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 250px);
            flex-direction: column;
            padding: 2rem;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            text-align: center;
        }
        .message-box a {
            margin-top: 1rem;
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 600;
        }
        .message-box a:hover { color: #0284c7; }
    </style>
</head>
<body>
    <!-- header same as other admin pages -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="afa.png" alt="AFA Odontologia" class="logo-img" title="AFA Odontologia">
                <h1>AFA Odontologia</h1>
            </div>
            <nav class="navbar">
                <a href="inicial.php#home" class="nav-link">HOME</a>
                <a href="inicial.php#clinica" class="nav-link">CLÍNICA</a>
                <a href="inicial.php#servicos" class="nav-link">SERVIÇOS</a>
                <a href="inicial.php#contato" class="nav-link">CONTATO</a>
            </nav>
            <a href="agendar.php" class="btn-agendar-header">AGENDAR CONSULTA</a>
        </div>
    </header>

    <div class="message-box">
        <h2>Você saiu do sistema</h2>
        <p>Obrigado por utilizar o painel Administrativo.</p>
        <a href="admin_login.php">Clique aqui para entrar novamente</a>
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
                    <p>Segunda a Sexta: 9h às 12h e 14h às 19h <br>Sábado: 9h às 12h</p>
                </div>
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p>Telefone: (11) 98371-9203<br>Email: afaodontologia1@gmail.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 AFA Odontologia. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
