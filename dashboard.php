<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$query = "SELECT orders.*, users.name as creator_name 
          FROM orders 
          JOIN users ON orders.user_id = users.id 
          ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Protocol System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><strong>Protocol System</strong></li>
            </ul>
            <ul>
                <li>User: <?php echo htmlspecialchars($_SESSION['user_name']); ?> (<?php echo $_SESSION['user_role']; ?>)</li>
                <li><a href="logout.php" class="secondary">Logout</a></li>
            </ul>
        </nav>

        <section>
            <div class="grid">
                <h1>Dashboard</h1>
                <div style="text-align: right;">
                    <a href="create_order.php" role="button"><strong>+ New Request</strong></a>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Created By</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['creator_name']); ?></td>
                                <td>
                                    <?php 
                                    $color = 'black';
                                    if($row['status'] == 'Approved') $color = 'green';
                                    if($row['status'] == 'Denied') $color = 'red';
                                    ?>
                                    <strong style="color: <?php echo $color; ?>"><?php echo htmlspecialchars($row['status']); ?></strong>
                                </td>
                                <td><?php echo date('M d, H:i', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="detalhes_pedido.php?id=<?php echo $row['id']; ?>">Manage</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>