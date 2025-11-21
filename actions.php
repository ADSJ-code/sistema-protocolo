<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
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
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $order_id = $_POST['order_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        $stmt_check = $conn->prepare("SELECT title, description, status FROM orders WHERE id = ?");
        $stmt_check->bind_param("i", $order_id);
        $stmt_check->execute();
        $current_order = $stmt_check->get_result()->fetch_assoc();

        if ($current_order && $current_order['status'] == 'Pending') {
            $stmt = $conn->prepare("UPDATE orders SET title = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $description, $order_id);
            
            if ($stmt->execute()) {
                $changes = [];
                if ($title != $current_order['title']) $changes[] = "Title updated";
                if ($description != $current_order['description']) $changes[] = "Description updated";
                $details = implode(", ", $changes);

                if (!empty($details)) {
                    $log_stmt = $conn->prepare("INSERT INTO audit_logs (order_id, user_id, action, details) VALUES (?, ?, 'Edited', ?)");
                    $log_stmt->bind_param("iis", $order_id, $user_id, $details);
                    $log_stmt->execute();
                }

                header("Location: detalhes_pedido.php?id=" . $order_id);
                exit;
            }
        }
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $action = $_GET['action'];

    if ($user_role !== 'admin') {
        die("Access Denied.");
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