<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

if ($order['status'] !== 'Pending') {
    die("Cannot edit orders that are not Pending.");
}

if ($order['user_id'] !== $user_id && $user_role !== 'admin') {
    die("Permission denied.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order - Protocol System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul><li><strong>Edit Order #<?php echo $order['id']; ?></strong></li></ul>
            <ul><li><a href="detalhes_pedido.php?id=<?php echo $order['id']; ?>" class="secondary">Cancel</a></li></ul>
        </nav>
        <article>
            <form action="actions.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($order['title']); ?>" required>

                <label for="description">Description</label>
                <textarea name="description" id="description" required><?php echo htmlspecialchars($order['description']); ?></textarea>

                <button type="submit">Save Changes</button>
            </form>
        </article>
    </main>
</body>
</html>