<?php
session_start();

// Se já está logado, vai direto para o painel
if(isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    header("Location: admin_painel.php");
    exit;
}

$erro = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    
    if($senha === '1234') {
        $_SESSION['admin_logged'] = true;
        header("Location: admin_painel.php");
        exit;
    } else {
        $erro = 'Senha incorreta!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Restrita - Dental Soft</title>
    <link rel="stylesheet" href="/2DS-A/25-02/css/inicial.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 250px);
            padding: 2rem;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        .login-box {
            background: #fff;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            font-size: 1.8rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
            text-align: center;
            font-weight: 700;
        }

        .login-box p {
            color: #6b7280;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group input {
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0ea5e9;
            background: #f0f9ff;
        }

        .btn-login {
            background: #0ea5e9;
            color: #fff;
            padding: 0.85rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }

        .btn-login:hover {
            background: #0284c7;
        }

        .erro {
            background: #fee2e2;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 4px solid #dc2626;
        }

        .voltar {
            text-align: center;
            margin-top: 1.5rem;
        }

        .voltar a {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .voltar a:hover {
            color: #0284c7;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 2rem;
            }

            .login-box h2 {
                font-size: 1.4rem;
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
                <a href="inicial.php#servicos" class="nav-link">SERVIÇOS</a>
                <a href="inicial.php#contato" class="nav-link">CONTATO</a>
            </nav>

            <a href="agendar.php" class="btn-agendar-header">AGENDAR CONSULTA</a>
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2>Área Restrita</h2>
            <p>Acesso exclusivo para administradores</p>
            
            <?php if($erro): ?>
                <div class="erro"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="senha">Senha de acesso:</label>
                    <input type="password" id="senha" name="senha" required autofocus placeholder="Digite a senha">
                </div>

                <button type="submit" class="btn-login">Entrar</button>
            </form>

            <div class="voltar">
                <a href="inicial.php">← Voltar para a página inicial</a>
            </div>
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
</body>
</html>
