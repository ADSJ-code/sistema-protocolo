<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM orders ORDER BY created_at DESC";
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
                <li>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
                <li><a href="logout.php" role="button" class="secondary">Logout</a></li>
            </ul>
        </nav>

        <section>
            <header>
                <h2>Dashboard</h2>
                <p>Role: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong></p>
            </header>

            <h3>Recent Orders</h3>
            <div class="overflow-auto">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
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
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="detalhes_pedido.php?id=<?php echo $row['id']; ?>">View Details</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>