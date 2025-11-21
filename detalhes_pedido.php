<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - Protocol System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><strong>Protocol System</strong></li>
            </ul>
            <ul>
                <li><a href="dashboard.php" role="button" class="secondary">Back to Dashboard</a></li>
            </ul>
        </nav>

        <article>
            <header>
                <h1>Order #<?php echo $order['id']; ?></h1>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            </header>
            
            <label><strong>Title</strong></label>
            <p><?php echo htmlspecialchars($order['title']); ?></p>

            <label><strong>Description</strong></label>
            <p><?php echo nl2br(htmlspecialchars($order['description'])); ?></p>

            <label><strong>Date Created</strong></label>
            <p><?php echo $order['created_at']; ?></p>
        </article>
    </main>
</body>
</html>