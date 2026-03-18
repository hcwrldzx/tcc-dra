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

// Ordenar por data (mais recentes primeiro)
usort($procedimentos, function($a, $b) {
    return strtotime($b['data_saida']) - strtotime($a['data_saida']);
});

// Filtros
$filtro_paciente = $_GET['filtro_paciente'] ?? '';
$filtro_data_inicio = $_GET['filtro_data_inicio'] ?? '';
$filtro_data_fim = $_GET['filtro_data_fim'] ?? '';

// Aplicar filtros
$procedimentos_filtrados = $procedimentos;

if($filtro_paciente) {
    $procedimentos_filtrados = array_filter($procedimentos_filtrados, function($p) use($filtro_paciente) {
        return stripos($p['nome_paciente'], $filtro_paciente) !== false;
    });
}

if($filtro_data_inicio) {
    $procedimentos_filtrados = array_filter($procedimentos_filtrados, function($p) use($filtro_data_inicio) {
        return strtotime($p['data_saida']) >= strtotime($filtro_data_inicio);
    });
}

if($filtro_data_fim) {
    $procedimentos_filtrados = array_filter($procedimentos_filtrados, function($p) use($filtro_data_fim) {
        return strtotime($p['data_saida']) <= strtotime($filtro_data_fim);
    });
}

$procedimentos_filtrados = array_values($procedimentos_filtrados);

// Calcular totais
$total_registros = count($procedimentos);
$total_filtrados = count($procedimentos_filtrados);
$valor_total = 0;
foreach($procedimentos_filtrados as $proc) {
    $valor_str = str_replace(['R$', '.', ','], ['', '', '.'], $proc['valor']);
    $valor_total += floatval($valor_str);
}

// Deletar registro
if(isset($_GET['deletar'])) {
    $deletar_id = intval($_GET['deletar']);
    $procedimentos = array_filter($procedimentos, function($p) use($deletar_id) {
        return $p['id'] !== $deletar_id;
    });
    $procedimentos = array_values($procedimentos);
    file_put_contents($arquivo_dados, json_encode($procedimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: registros.php");
    exit;
}

// Editar registro
if(isset($_POST['editar_id'])) {
    $editar_id = intval($_POST['editar_id']);
    foreach($procedimentos as $key => $proc) {
        if($proc['id'] === $editar_id) {
            $procedimentos[$key] = [
                'id' => $editar_id,
                'data_saida' => $_POST['data_saida'] ?? $proc['data_saida'],
                'data_entrada' => $_POST['data_entrada'] ?? $proc['data_entrada'],
                'nome_paciente' => $_POST['nome_paciente'] ?? $proc['nome_paciente'],
                'servico_realizado' => $_POST['servico_realizado'] ?? $proc['servico_realizado'],
                'laboratorio' => $_POST['laboratorio'] ?? $proc['laboratorio'],
                'valor' => $_POST['valor'] ?? $proc['valor'],
                'valor_bruto' => $_POST['valor_bruto'] ?? $proc['valor_bruto'],
                'procedimento' => $_POST['procedimento'] ?? $proc['procedimento'],
                'observacao' => $_POST['observacao'] ?? $proc['observacao'],
            ];
            break;
        }
    }
    file_put_contents($arquivo_dados, json_encode($procedimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: registros.php");
    exit;
}

// Modal de edição
$editar_id = isset($_GET['editar']) ? intval($_GET['editar']) : null;
$registro_edicao = null;
if($editar_id) {
    foreach($procedimentos as $proc) {
        if($proc['id'] === $editar_id) {
            $registro_edicao = $proc;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Procedimentos - Dental Soft</title>
    <link rel="stylesheet" href="css/inicial.css">
    <style>
        .registros-container {
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

        .registros-header {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .registros-header h1 {
            color: #1f2937;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-box {
            background: linear-gradient(135deg, #0ea5e9 0%, #1e40af 100%);
            color: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-box h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-box p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .filtros-section {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .filtros-section h2 {
            color: #1f2937;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filtro-group {
            display: flex;
            flex-direction: column;
        }

        .filtro-group label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }

        .filtro-group input {
            padding: 0.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .filtro-group input:focus {
            outline: none;
            border-color: #0ea5e9;
            background: #f0f9ff;
        }

        .botoes-filtro {
            display: flex;
            gap: 1rem;
        }

        .btn-filtrar {
            background: #0ea5e9;
            color: #fff;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .btn-filtrar:hover {
            background: #0284c7;
        }

        .btn-limpar {
            background: #6b7280;
            color: #fff;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .btn-limpar:hover {
            background: #4b5563;
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
            font-size: 1.2rem;
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

        .acoes {
            display: flex;
            gap: 0.5rem;
        }

        .btn-editar {
            background: #3b82f6;
            color: #fff;
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-editar:hover {
            background: #2563eb;
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

        .vazio {
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.active {
            display: block;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            position: relative;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
        }

        .close:hover {
            color: #1f2937;
        }

        .modal h2 {
            color: #1f2937;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
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
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.9rem;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0ea5e9;
            background: #f0f9ff;
        }

        .botoes-modal {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-salvar {
            background: #0ea5e9;
            color: #fff;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-salvar:hover {
            background: #0284c7;
        }

        .btn-cancelar {
            background: #6b7280;
            color: #fff;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-cancelar:hover {
            background: #4b5563;
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

            .stats {
                grid-template-columns: 1fr;
            }

            .filtros-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.75rem;
            }

            table th,
            table td {
                padding: 0.4rem;
            }

            .acoes {
                flex-direction: column;
            }

            .logout-btn {
                position: static;
                width: calc(100% - 3rem);
                margin: 1rem 1.5rem;
            }

            .modal-content {
                width: 95%;
                margin: 20% auto;
            }
        }
    </style>
</head>
<body>
    <div class="registros-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Dental Soft</h2>
            <ul class="sidebar-menu">
                <li><a href="admin_painel.php">Painel</a></li>
                <li><a href="registros.php" class="active">Registros</a></li>
            </ul>
            <form method="GET" action="admin_logout.php" style="margin-top: auto;">
                <button type="submit" class="logout-btn">Sair</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="registros-header">
                <h1>Registros de Procedimentos</h1>
                
                <!-- Estatísticas -->
                <div class="stats">
                    <div class="stat-box">
                        <h3><?php echo $total_registros; ?></h3>
                        <p>Total de Registros</p>
                    </div>
                    <div class="stat-box">
                        <h3><?php echo $total_filtrados; ?></h3>
                        <p>Registros Encontrados</p>
                    </div>
                    <div class="stat-box">
                        <h3>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></h3>
                        <p>Valor Total</p>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="filtros-section">
                <h2>Filtrar Registros</h2>
                <form method="GET" action="">
                    <div class="filtros-grid">
                        <div class="filtro-group">
                            <label for="filtro_paciente">Nome do Paciente</label>
                            <input type="text" id="filtro_paciente" name="filtro_paciente" value="<?php echo htmlspecialchars($filtro_paciente); ?>" placeholder="Buscar paciente...">
                        </div>
                        <div class="filtro-group">
                            <label for="filtro_data_inicio">Data Inicial</label>
                            <input type="date" id="filtro_data_inicio" name="filtro_data_inicio" value="<?php echo $filtro_data_inicio; ?>">
                        </div>
                        <div class="filtro-group">
                            <label for="filtro_data_fim">Data Final</label>
                            <input type="date" id="filtro_data_fim" name="filtro_data_fim" value="<?php echo $filtro_data_fim; ?>">
                        </div>
                    </div>
                    <div class="botoes-filtro">
                        <button type="submit" class="btn-filtrar">Filtrar</button>
                        <a href="registros.php" class="btn-limpar" style="text-decoration: none; display: inline-block;">Limpar Filtros</a>
                    </div>
                </form>
            </div>

            <!-- Tabela de Registros -->
            <div class="tabela-section">
                <h2>Lista de Procedimentos</h2>
                <?php if(count($procedimentos_filtrados) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Data Saída</th>
                                <th>Data Entrada</th>
                                <th>Paciente</th>
                                <th>Serviço</th>
                                <th>Laboratório</th>
                                <th>Procedimento</th>
                                <th>Valor</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($procedimentos_filtrados as $proc): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($proc['data_saida'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($proc['data_entrada'])); ?></td>
                                    <td><?php echo htmlspecialchars($proc['nome_paciente']); ?></td>
                                    <td><?php echo htmlspecialchars($proc['servico_realizado']); ?></td>
                                    <td><?php echo htmlspecialchars($proc['laboratorio']); ?></td>
                                    <td><?php echo htmlspecialchars($proc['procedimento']); ?></td>
                                    <td><?php echo htmlspecialchars($proc['valor']); ?></td>
                                    <td>
                                        <div class="acoes">
                                            <a href="?editar=<?php echo $proc['id']; ?>" class="btn-editar">Editar</a>
                                            <a href="registros.php?deletar=<?php echo $proc['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar este registro?');" class="btn-deletar">Deletar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="vazio">
                        <p>Nenhum registro encontrado com os filtros aplicados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Modal de Edição -->
    <?php if($registro_edicao): ?>
    <div class="modal active" id="editModal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editModal').classList.remove('active')">&times;</span>
            <h2>Editar Procedimento</h2>
            <form method="POST" action="">
                <input type="hidden" name="editar_id" value="<?php echo $registro_edicao['id']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_data_saida">Data Saída</label>
                        <input type="date" id="edit_data_saida" name="data_saida" value="<?php echo $registro_edicao['data_saida']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_data_entrada">Data Entrada</label>
                        <input type="date" id="edit_data_entrada" name="data_entrada" value="<?php echo $registro_edicao['data_entrada']; ?>" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_nome_paciente">Nome do Paciente</label>
                        <input type="text" id="edit_nome_paciente" name="nome_paciente" value="<?php echo htmlspecialchars($registro_edicao['nome_paciente']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_servico_realizado">Serviço Realizado</label>
                        <input type="text" id="edit_servico_realizado" name="servico_realizado" value="<?php echo htmlspecialchars($registro_edicao['servico_realizado']); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_laboratorio">Laboratório</label>
                        <input type="text" id="edit_laboratorio" name="laboratorio" value="<?php echo htmlspecialchars($registro_edicao['laboratorio']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_procedimento">Procedimento</label>
                        <input type="text" id="edit_procedimento" name="procedimento" value="<?php echo htmlspecialchars($registro_edicao['procedimento']); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_valor">Valor</label>
                        <input type="text" id="edit_valor" name="valor" value="<?php echo htmlspecialchars($registro_edicao['valor']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_valor_bruto">Valor Bruto</label>
                        <input type="text" id="edit_valor_bruto" name="valor_bruto" value="<?php echo htmlspecialchars($registro_edicao['valor_bruto']); ?>">
                    </div>
                </div>

                <div class="form-grid full">
                    <div class="form-group">
                        <label for="edit_observacao">Observação</label>
                        <textarea id="edit_observacao" name="observacao" rows="3"><?php echo htmlspecialchars($registro_edicao['observacao']); ?></textarea>
                    </div>
                </div>

                <div class="botoes-modal">
                    <button type="button" class="btn-cancelar" onclick="document.getElementById('editModal').classList.remove('active')">Cancelar</button>
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if(modal && event.target === modal) {
                modal.classList.remove('active');
            }
        }

        // Manipular delete
        function confirmarDelecao(id) {
            if(confirm('Tem certeza que deseja deletar este registro?')) {
                window.location.href = '?deletar=' + id;
            }
        }
    </script>
</body>
</html>
