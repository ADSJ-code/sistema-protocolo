<?php
session_start();

if (!isset($_SESSION['utilizador_id'])) {
    header("Location: index.php");
    exit;
}
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
                <li>Olá, <?php echo htmlspecialchars($_SESSION['utilizador_nome']); ?></li>
                <li><a href="logout.php" role="button" class="secondary">Sair</a></li>
            </ul>
        </nav>

        <section>
            <article>
                <header>Dashboard</header>
                <p>Bem-vindo ao painel administrativo.</p>
                <p>Seu cargo atual é: <strong><?php echo htmlspecialchars($_SESSION['utilizador_role']); ?></strong></p>
            </article>
        </section>
    </main>
</body>
</html>