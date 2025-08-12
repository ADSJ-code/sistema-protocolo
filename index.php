<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';

$erro_login = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    if (!empty($email) && !empty($senha)) {
        try {
            
            $stmt = $conn->prepare("SELECT id, nome, email, cargo FROM utilizadores WHERE email = :email AND senha = :senha");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['utilizador_id'] = $utilizador['id'];
                $_SESSION['utilizador_nome'] = $utilizador['nome'];
                $_SESSION['utilizador_cargo'] = $utilizador['cargo'];

                header("location: dashboard.php");
                exit;
            } else {
                $erro_login = "Email ou senha inválidos.";
            }
        } catch(PDOException $e) {
            die("ERRO: Não foi possível executar a consulta. " . $e->getMessage());
        }
    } else {
        $erro_login = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Protocolo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <article>
            <hgroup>
                <h1>Sistema de Protocolo</h1>
                <h2>Acesse a sua conta</h2>
            </hgroup>
            <form action="index.php" method="post">
                <input type="email" name="email" placeholder="Seu email" required>
                <input type="password" name="password" placeholder="Sua senha" required>

                <?php 
                if(!empty($erro_login)){
                    echo '<small style="color: red;">' . htmlspecialchars($erro_login) . '</small>';
                }
                ?>

                <button type="submit">Entrar</button>
            </form>
        </article>
    </main>
</body>
</html>