<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$current_user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$stmt = $conn->prepare("SELECT orders.*, users.name as creator_name FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$stmt_logs = $conn->prepare("SELECT audit_logs.*, users.name as actor_name FROM audit_logs JOIN users ON audit_logs.user_id = users.id WHERE order_id = ? ORDER BY created_at DESC");
$stmt_logs->bind_param("i", $id);
$stmt_logs->execute();
$logs = $stmt_logs->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Details - Protocol System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul><li><strong>Order #<?php echo $order['id']; ?></strong></li></ul>
            <ul><li><a href="dashboard.php" class="secondary">Back</a></li></ul>
        </nav>

        <div class="grid">
            <article>
                <header>
                    <div class="grid">
                        <div>
                            <strong>Status: 
                            <?php 
                                $color = 'black';
                                if($order['status'] == 'Approved') $color = 'green';
                                if($order['status'] == 'Denied') $color = 'red';
                                echo "<span style='color:$color'>" . $order['status'] . "</span>";
                            ?>
                            </strong>
                        </div>
                        <div style="text-align: right;">
                            <?php if($order['status'] == 'Pending' && ($current_user_id == $order['user_id'] || $user_role == 'admin')): ?>
                                <a href="edit_order.php?id=<?php echo $order['id']; ?>" role="button" class="outline">Edit Request</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>
                <h3><?php echo htmlspecialchars($order['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($order['description'])); ?></p>
                <small>Created by: <strong><?php echo $order['creator_name']; ?></strong> on <?php echo $order['created_at']; ?></small>
                
                <?php if ($user_role == 'admin' && $order['status'] == 'Pending'): ?>
                    <hr>
                    <footer>
                        <a href="actions.php?action=approve&id=<?php echo $order['id']; ?>" role="button" style="background-color: green; border-color: green;">Approve</a>
                        <a href="actions.php?action=deny&id=<?php echo $order['id']; ?>" role="button" style="background-color: red; border-color: red;">Deny</a>
                    </footer>
                <?php endif; ?>
            </article>

            <article>
                <header><strong>Audit History (Version Control)</strong></header>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($log = $logs->fetch_assoc()): ?>
                            <tr>
                                <td><small><?php echo date('m/d H:i', strtotime($log['created_at'])); ?></small></td>
                                <td><?php echo htmlspecialchars($log['actor_name']); ?></td>
                                <td>
                                    <strong><?php echo $log['action']; ?></strong><br>
                                    <small><i><?php echo $log['details']; ?></i></small>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </article>
        </div>
    </main>
</body>
</html>