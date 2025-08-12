<?php
require_once 'db_connection.php';

if (!isset($_SESSION['utilizador_id'])) {
    header("location: index.php");
    exit;
}

$erro = $sucesso = "";
$pedidos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['descricao'])) {
    $descricao = trim($_POST['descricao']);
    if (!empty($descricao)) {
        $stmt = $conn->prepare("INSERT INTO pedidos (descricao, utilizador_id) VALUES (:descricao, :utilizador_id)");
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':utilizador_id', $_SESSION['utilizador_id']);
        $stmt->execute();
        $sucesso = "Pedido submetido com sucesso!";
    } else {
        $erro = "A descrição não pode estar em branco.";
    }
}

if ($_SESSION['utilizador_cargo'] == 'Gestor' && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];
    $novo_status = $_POST['novo_status'];
    $gestor_id = $_SESSION['utilizador_id'];

    $stmt_status_antigo = $conn->prepare("SELECT status FROM pedidos WHERE id = :id");
    $stmt_status_antigo->bindParam(':id', $pedido_id);
    $stmt_status_antigo->execute();
    $resultado = $stmt_status_antigo->fetch(PDO::FETCH_ASSOC);
    $status_anterior = $resultado ? $resultado['status'] : null;

    $stmt_update = $conn->prepare("UPDATE pedidos SET status = :status, data_ultima_acao = NOW(), gestor_id_acao = :gestor_id WHERE id = :id");
    $stmt_update->bindParam(':status', $novo_status);
    $stmt_update->bindParam(':gestor_id', $gestor_id);
    $stmt_update->bindParam(':id', $pedido_id);
    $stmt_update->execute();

    $stmt_historico = $conn->prepare("INSERT INTO historico_status (pedido_id, status_anterior, status_novo, gestor_id) VALUES (:pedido_id, :status_anterior, :status_novo, :gestor_id)");
    $stmt_historico->bindParam(':pedido_id', $pedido_id);
    $stmt_historico->bindParam(':status_anterior', $status_anterior);
    $stmt_historico->bindParam(':status_novo', $novo_status);
    $stmt_historico->bindParam(':gestor_id', $gestor_id);
    $stmt_historico->execute();

    $sucesso = "Status do pedido atualizado com sucesso!";
}

if ($_SESSION['utilizador_cargo'] == 'Gestor') {
    $stmt = $conn->prepare("SELECT p.id, p.descricao, p.status, u.nome AS utilizador_nome, p.data_submissao, p.data_ultima_acao, p.utilizador_id, p.gestor_id_acao FROM pedidos p JOIN utilizadores u ON p.utilizador_id = u.id ORDER BY p.data_submissao DESC");
} else {
    $stmt = $conn->prepare("SELECT id, descricao, status, data_submissao, data_ultima_acao FROM pedidos WHERE utilizador_id = :utilizador_id ORDER BY data_submissao DESC");
    $stmt->bindParam(':utilizador_id', $_SESSION['utilizador_id']);
}
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Protocolo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><strong>Sistema de Protocolo</strong></li>
            </ul>
            <ul>
                <li>Bem-vindo, <?php echo htmlspecialchars($_SESSION['utilizador_nome']); ?>!</li>
                <li><a href="logout.php" role="button" class="secondary">Sair</a></li>
            </ul>
        </nav>

        <h1>Dashboard</h1>

        <article>
            <h3>Submeter Novo Pedido</h3>
            <form action="dashboard.php" method="post">
                <textarea name="descricao" placeholder="Descreva o seu pedido aqui..." required></textarea>
                <button type="submit">Enviar Pedido</button>
            </form>
        </article>

        <hr>

        <h3><?php echo ($_SESSION['utilizador_cargo'] == 'Gestor') ? 'Histórico de Pedidos' : 'Meus Pedidos'; ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Data de Submissão</th>
                    <th>Última Ação em</th>
                    <th>Histórico</th>
                    <?php if ($_SESSION['utilizador_cargo'] == 'Gestor'): ?>
                        <th>Submetido por</th>
                        <th colspan="2">Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <?php 
                        $alerta_auto_aprovacao = ($_SESSION['utilizador_cargo'] == 'Gestor' && $pedido['utilizador_id'] == $pedido['gestor_id_acao']);
                    ?>
                    <tr <?php if($alerta_auto_aprovacao) { echo 'style="background-color: #5d2b2b;"'; } ?>>
                        <td>
                            <?php echo htmlspecialchars($pedido['descricao']); ?>
                            <?php if($alerta_auto_aprovacao): ?>
                                <br><small style="color: #ffb8b8;">(Ação realizada pelo próprio criador)</small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($pedido['status']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_submissao'])); ?></td>
                        <td><?php echo $pedido['data_ultima_acao'] ? date('d/m/Y H:i', strtotime($pedido['data_ultima_acao'])) : 'N/A'; ?></td>
                        <td>
                            <a href="detalhes_pedido.php?id=<?php echo $pedido['id']; ?>" role="button">Ver</a>
                        </td>
                        <?php if ($_SESSION['utilizador_cargo'] == 'Gestor'): ?>
                            <td><?php echo htmlspecialchars($pedido['utilizador_nome']); ?></td>
                            <td>
                                <form action="dashboard.php" method="post" style="margin: 0;">
                                    <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                    <input type="hidden" name="novo_status" value="Aprovado">
                                    <button type="submit" class="contrast">Aprovar</button>
                                </form>
                            </td>
                            <td>
                                <form action="dashboard.php" method="post" style="margin: 0;">
                                    <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                    <input type="hidden" name="novo_status" value="Rejeitado">
                                    <button type="submit" class="secondary">Rejeitar</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>