<?php
session_start();

// Verifica se está logado
if(!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$arquivo_dados = 'dados_procedimentos.json';

// Carregar dados existentes
$procedimentos = [];
if(file_exists($arquivo_dados)) {
    $json = file_get_contents($arquivo_dados);
    $procedimentos = json_decode($json, true) ?? [];
}

// Processar novo registro
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo = [
        'id' => time(),
        'data_saida' => $_POST['data_saida'] ?? '',
        'data_entrada' => $_POST['data_entrada'] ?? '',
        'nome_paciente' => $_POST['nome_paciente'] ?? '',
        'servico_realizado' => $_POST['servico_realizado'] ?? '',
        'laboratorio' => $_POST['laboratorio'] ?? '',
        'valor' => $_POST['valor'] ?? '',
        'valor_bruto' => $_POST['valor_bruto'] ?? '',
        'procedimento' => $_POST['procedimento'] ?? '',
        'observacao' => $_POST['observacao'] ?? '',
    ];
    
    $procedimentos[] = $novo;
    file_put_contents($arquivo_dados, json_encode($procedimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $mensagem = 'Procedimento registrado com sucesso!';
}

// Deletar registro
if(isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);
    $procedimentos = array_filter($procedimentos, function($p) use($id) {
        return $p['id'] !== $id;
    });
    $procedimentos = array_values($procedimentos);
    file_put_contents($arquivo_dados, json_encode($procedimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Dental Soft</title>
    <link rel="stylesheet" href="/2DS-A/25-02/css/inicial.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #1e3a8a;
            color: #fff;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            font-size: 1rem;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 1rem;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 1rem 1.5rem;
            color: #fff;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar-menu a:hover {
            background: #0ea5e9;
        }

        .sidebar-menu a.active {
            background: #0ea5e9;
            border-left: 4px solid #fff;
            padding-left: calc(1.5rem - 4px);
        }

        .logout-btn {
            position: absolute;
            bottom: 1rem;
            left: 1.5rem;
            right: 1.5rem;
            padding: 0.75rem;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            background: #f9fafb;
            min-height: 100vh;
        }

        .admin-header {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .admin-header p {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .mensagem {
            background: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #22c55e;
        }

        .form-section {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .form-section h2 {
            color: #1f2937;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-grid.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.9rem;
            transition: all 0.3s;
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
            padding: 0.85rem 2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.3s;
            width: fit-content;
        }

        .btn-submit:hover {
            background: #0284c7;
        }

        .tabela-section {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .tabela-section h2 {
            color: #1f2937;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f3f4f6;
        }

        table th {
            padding: 1rem;
            text-align: left;
            color: #374151;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.85rem;
        }

        table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.9rem;
        }

        table tr:hover {
            background: #f9fafb;
        }

        .btn-deletar {
            background: #dc2626;
            color: #fff;
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: background 0.3s;
        }

        .btn-deletar:hover {
            background: #b91c1c;
        }

        .btn-det {
            background: #3b82f6;
            color: #fff;
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: background 0.3s;
            margin-right: 0.5rem;
        }

        .btn-det:hover {
            background: #2563eb;
        }

        .vazio {
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
        }

        /* Modal detalhes */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal.active {
            display: block;
        }

        .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .modal-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
        }

        .modal-close:hover {
            color: #1f2937;
        }

        .modal h2 {
            color: #1f2937;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .modal .detail-row {
            margin-bottom: 0.75rem;
        }

        .modal .detail-row span {
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 1rem 0;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.8rem;
            }

            table th,
            table td {
                padding: 0.5rem;
            }

            .logout-btn {
                position: static;
                width: calc(100% - 3rem);
                margin: 1rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Dental Soft</h2>
            <ul class="sidebar-menu">
                <li><a href="#" class="active">Painel</a></li>
                <li><a href="#registros">Registros</a></li>
            </ul>
            <form method="GET" action="admin_logout.php" style="margin-top: auto;">
                <button type="submit" class="logout-btn">Sair</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="admin-header">
                <h1>Painel Administrativo</h1>
                <p>Gerenciar procedimentos e atendimentos da clínica</p>
            </div>

            <?php if(isset($mensagem)): ?>
                <div class="mensagem"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <!-- Formulário de Registro -->
            <div class="form-section">
                <h2>Novo Registro de Procedimento</h2>
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="data_saida">Data Saída</label>
                            <input type="date" id="data_saida" name="data_saida" required>
                        </div>
                        <div class="form-group">
                            <label for="data_entrada">Data Entrada</label>
                            <input type="date" id="data_entrada" name="data_entrada" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nome_paciente">Nome do Paciente*</label>
                            <input type="text" id="nome_paciente" name="nome_paciente" required>
                        </div>
                        <div class="form-group">
                            <label for="servico_realizado">Serviço Realizado</label>
                            <input type="text" id="servico_realizado" name="servico_realizado">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="laboratorio">Laboratório</label>
                            <input type="text" id="laboratorio" name="laboratorio">
                        </div>
                        <div class="form-group">
                            <label for="procedimento">Procedimento</label>
                            <input type="text" id="procedimento" name="procedimento">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" id="valor" name="valor" placeholder="R$">
                        </div>
                        <div class="form-group">
                            <label for="valor_bruto">Valor Bruto</label>
                            <input type="text" id="valor_bruto" name="valor_bruto" placeholder="R$">
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Registrar Procedimento</button>
                </form>
            </div>

            <!-- Tabela de Registros -->
            <div class="tabela-section" id="registros">
                <h2>Registros de Procedimentos</h2>
                <?php if(count($procedimentos) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Data Saída</th>
                                <th>Data Entrada</th>
                                <th>Paciente</th>
                                <th>Serviço</th>
                                <th>Laboratório</th>
                                <th>Valor</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($procedimentos as $proc): ?>
                                <tr>
                                    <td><?php echo $proc['data_saida']; ?></td>
                                    <td><?php echo $proc['data_entrada']; ?></td>
                                    <td><?php echo $proc['nome_paciente']; ?></td>
                                    <td><?php echo $proc['servico_realizado']; ?></td>
                                    <td><?php echo $proc['laboratorio']; ?></td>
                                    <td><?php echo $proc['valor']; ?></td>
                                    <td>
                                        <button type="button" class="btn-det" 
                                            data-id="<?php echo $proc['id']; ?>" 
                                            data-data_saida="<?php echo $proc['data_saida']; ?>" 
                                            data-data_entrada="<?php echo $proc['data_entrada']; ?>" 
                                            data-nome_paciente="<?php echo htmlspecialchars($proc['nome_paciente']); ?>" 
                                            data-servico_realizado="<?php echo htmlspecialchars($proc['servico_realizado']); ?>" 
                                            data-laboratorio="<?php echo htmlspecialchars($proc['laboratorio']); ?>" 
                                            data-valor="<?php echo $proc['valor']; ?>" 
                                            data-valor_bruto="<?php echo $proc['valor_bruto']; ?>" 
                                            data-procedimento="<?php echo htmlspecialchars($proc['procedimento']); ?>" 
                                            data-observacao="<?php echo htmlspecialchars($proc['observacao']); ?>"
                                        >Detalhes</button>
                                        <a href="?deletar=<?php echo $proc['id']; ?>" onclick="return confirm('Tem certeza?');">
                                            <button type="button" class="btn-deletar">Deletar</button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="vazio">
                        <p>Nenhum registro de procedimento ainda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
