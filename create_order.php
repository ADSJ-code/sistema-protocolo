<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Order - Protocol System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"/>
</head>
<body>
    <main class="container">
        <nav>
            <ul><li><strong>Protocol System</strong></li></ul>
            <ul><li><a href="dashboard.php" class="secondary">Back</a></li></ul>
        </nav>
        <article>
            <header><h1>Create New Request</h1></header>
            <form action="actions.php" method="post">
                <input type="hidden" name="action" value="create">
                
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>

                <label for="description">Description</label>
                <textarea name="description" id="description" required></textarea>

                <button type="submit">Submit Request</button>
            </form>
        </article>
    </main>
</body>
</html>