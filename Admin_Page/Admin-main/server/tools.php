<?php
// Connection File to DB
include('../../../server/databaseconn.php');
// For session handling and secure access to pages
session_start();
$result = $conn->query("SELECT Category_ID, Name FROM category");

// Check if admin is logged in, else redirect to login page
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Add Tool
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $long_desc = $_POST['long_description'];
    $image = $_POST['image'];
    $category_id = $_POST['cat_id'];
    $stmt = $conn->prepare("INSERT INTO tool (Name, Description, Long_Description, Tool_Image, Category_ID, Admin_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $name, $desc, $long_desc, $image, $category_id, $_SESSION['admin_id']);
    $stmt->execute();
    echo "<script>alert('Tool is added successfully.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tools</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1>Manage Tools</h1>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Tool's Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="long_description" class="form-label">Long Description</label>
            <textarea name="long_description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Tool's Image</label>
            <input type="text" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="cat_id" class="form-label">Its Category</label>
            <select name="cat_id" class="form-control" required>
                <option value="" disabled selected>Select a category</option>
                <?php while($row = $result->fetch_assoc()){?>
                    <option value="<?php echo $row['Category_ID']; ?>"><?php echo htmlspecialchars($row['Name']);?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Tool</button>
    </form>
        <br>
    <a href="../dashboard.php" class="btn btn-primary mb-4">Go Home</a>
    <?php $conn->close(); ?>