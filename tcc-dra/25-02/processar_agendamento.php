<?php
// Processar o agendamento
// Este arquivo recebe os dados do formulário de agendamento

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar dados do formulário
    $nome = isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $telefone = isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : '';
    $cidade = isset($_POST['cidade']) ? htmlspecialchars($_POST['cidade']) : '';
    $anotacao = isset($_POST['anotacao']) ? htmlspecialchars($_POST['anotacao']) : '';
    $selectedDate = isset($_POST['selectedDate']) ? htmlspecialchars($_POST['selectedDate']) : '';
    $selectedTime = isset($_POST['selectedTime']) ? htmlspecialchars($_POST['selectedTime']) : '';

    // Aqui você poderia:
    // 1. Salvar em um banco de dados
    // 2. Enviar um email
    // 3. Integrar com um sistema de agendamento

    // Por enquanto, vamos criar uma confirmação simples
    session_start();
    $_SESSION['agendamento_confirmado'] = [
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'cidade' => $cidade,
        'data' => $selectedDate,
        'horario' => $selectedTime
    ];

    // Redirecionar para página de confirmação
    header('Location: confirmacao.php');
    exit;
} else {
    // Se não for POST, redirecionar para a página inicial
    header('Location: inicial.php');
    exit;
}
?>
