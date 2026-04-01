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
    <title>Área Restrita - AFA Odontologia</title>
    <link rel="stylesheet" href="css/inicial.css">
    <link rel="stylesheet" href="css/adm.css">
    

</head>
<body>
    <!-- Header / Navbar -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="afa_odontologia.png" alt="AFA Odontologia" class="logo-img" title="AFA Odontologia">
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
                    <h4>AFA Odontologia</h4>
                    <p>Clínica odontológica de referência em atendimento de qualidade.</p>
                </div>
                <div class="footer-section">
                    <h4>Horário</h4>
                    <p>Segunda a Sexta: 9h às 18h<br>Sábado: 9h às 13h</p>
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
