<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($title) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, title, description, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iss", $user_id, $title, $description);
        
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            
            $log_stmt = $conn->prepare("INSERT INTO audit_logs (order_id, user_id, action, details) VALUES (?, ?, 'Created', 'Order created initially.')");
            $log_stmt->bind_param("ii", $order_id, $user_id);
            $log_stmt->execute();

            header("Location: dashboard.php");
            exit;
        } else {
            die("Error creating order.");
        }
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $action = $_GET['action'];

    if ($user_role !== 'admin') {
        die("Access Denied. Only Admins can perform this action.");
    }

    $new_status = ($action == 'approve') ? 'Approved' : 'Denied';
    $log_action = ($action == 'approve') ? 'Approved' : 'Denied';

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        $details = "Status changed to " . $new_status;
        $log_stmt = $conn->prepare("INSERT INTO audit_logs (order_id, user_id, action, details) VALUES (?, ?, ?, ?)");
        $log_stmt->bind_param("iiss", $order_id, $user_id, $log_action, $details);
        $log_stmt->execute();

        header("Location: detalhes_pedido.php?id=" . $order_id);
        exit;
    }
}

header("Location: dashboard.php");
exit;
?>