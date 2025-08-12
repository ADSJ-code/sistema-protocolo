<?php
require_once 'db_connection.php';

if (!isset($_SESSION['utilizador_id']) || !isset($_GET['id'])) {
    header("location: index.php");
    exit;
}

$pedido_id = $_GET['id'];
$historico = [];
$pedido_atual = null;

$stmt_pedido = $conn->prepare("SELECT p.*, u.nome AS utilizador_nome FROM pedidos p JOIN utilizadores u ON p.utilizador_id = u.id WHERE p.id = :id");
$stmt_pedido->bindParam(':id', $pedido_id);
$stmt_pedido->execute();
$pedido_atual = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

$stmt_historico = $conn->prepare("SELECT h.*, u.nome AS gestor_nome FROM historico_status h JOIN utilizadores u ON h.gestor_id = u.id WHERE h.pedido_id = :pedido_id ORDER BY h.data_alteracao DESC");
$stmt_historico->bindParam(':pedido_id', $pedido_id);
$stmt_historico->execute();
$historico = $stmt_historico->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Pedido - Sistema de Protocolo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><strong>Sistema de Protocolo</strong></li>
            </ul>
            <ul>
                <li><a href="dashboard.php">Voltar ao Dashboard</a></li>
                <li><a href="logout.php" role="button" class="secondary">Sair</a></li>
            </ul>
        </nav>

        <?php if ($pedido_atual): ?>
            <hgroup>
                <h1>Detalhes do Pedido #<?php echo htmlspecialchars($pedido_atual['id']); ?></h1>
                <h2>Submetido por <?php echo htmlspecialchars($pedido_atual['utilizador_nome']); ?> em <?php echo date('d/m/Y H:i', strtotime($pedido_atual['data_submissao'])); ?></h2>
            </hgroup>
            <article>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($pedido_atual['descricao']); ?></p>
                <p><strong>Status Atual:</strong> <?php echo htmlspecialchars($pedido_atual['status']); ?></p>
            </article>

            <hr>

            <h3>Histórico de Alterações de Status</h3>
            <table>
                <thead>
                    <tr>
                        <th>Data da Alteração</th>
                        <th>Status Anterior</th>
                        <th>Status Novo</th>
                        <th>Ação por</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $item): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($item['data_alteracao'])); ?></td>
                            <td><?php echo htmlspecialchars($item['status_anterior'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item['status_novo']); ?></td>
                            <td><?php echo htmlspecialchars($item['gestor_nome']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <h1>Pedido não encontrado.</h1>
        <?php endif; ?>
    </main>
</body>
</html>