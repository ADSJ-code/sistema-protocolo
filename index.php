<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'db_connection.php';

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");

        if ($stmt === FALSE) {
            die($conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                header("location: dashboard.php");
                exit;
            } else {
                $login_error = "Invalid email or password.";
            }
        } else {
            $login_error = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $login_error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Protocol System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <article>
            <hgroup>
                <h1>Protocol System</h1>
                <h2>Access your account</h2>
            </hgroup>
            <form action="index.php" method="post">
                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password" required>

                <?php 
                if(!empty($login_error)){
                    echo '<small style="color: red;">' . htmlspecialchars($login_error) . '</small>';
                }
                ?>

                <button type="submit">Login</button>
            </form>
        </article>
    </main>
</body>
</html>