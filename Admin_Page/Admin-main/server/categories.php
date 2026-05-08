<?php
// Connection File to DB
include('../../../server/databaseconn.php');
// For session handling and secure access to pages
session_start();

// Check if admin is logged in, else redirect to login page
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $icon = $_POST['icon'];
    $stmt = $conn->prepare("INSERT INTO category (Name, Admin_ID, Description, category_icon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $_SESSION['admin_id'], $desc, $icon);
    $stmt->execute();
}

// Delete Category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("UPDATE category SET is_deleted = 1, Admin_ID = ? WHERE Category_ID = ?");
    $stmt->bind_param("ii", $_SESSION['admin_id'], $id);
    $stmt->execute();
}

// Get all category
$result = $conn->query("SELECT * FROM category WHERE is_deleted = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage categories</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1>Manage categories</h1>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="icon" class="form-label">Category Icon</label>
            <input type="text" name="icon" class="form-control">
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Category</button>
    </form>

    <h2>Existing category</h2>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Icon Name</th>
            <th>Admin ID</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Name']) ?></td>
            <td><?= htmlspecialchars($row['Description']) ?></td>
            <td><?= htmlspecialchars($row['category_icon']) ?></td>
            <td><?= htmlspecialchars($row['Admin_ID']) ?></td>
            <td>
                <a href="?delete=<?php echo $row['Category_ID']; ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure you want to delete this category?');">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
        <br>
    <a href="../dashboard.php" class="btn btn-primary mb-4">Go Home</a>
</body>
</html>

<?php $conn->close(); ?>
