<?php
session_start();
include('server/databaseconn.php');

$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;

// Optional: Redirect if Expert_ID is missing
if (!$expert_id) {
    echo "<h2>Expert not found</h2>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expert_id = $_SESSION['expert_id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $material_link = $_POST['material_link'];

    $stmt = $conn->prepare("
        INSERT INTO course (Expert_ID, Title, description, start_date, end_date, material_link)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssss", $expert_id, $name, $desc, $start_date, $end_date, $material_link);
    $stmt->execute();

    header("Location: edit_expertprofile.php?Expert_ID= $expert_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Course</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom CSS (optional reuse) -->
  <link rel="stylesheet" href="./css/profile.css">

  <style>
    .form-container {
      max-width: 700px;
      margin: 50px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    .btn-custom {
      background-color: #AB7442;
      color: white;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2><i class="bi bi-journal-plus"></i> Add a New Course</h2>

  <form method="POST" action="add_course.php?Expert_ID=<?= $expert_id ?>">
    <div class="mb-3">
      <label for="name" class="form-label">Course Name</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label for="start_date" class="form-label">Start Date</label>
      <input type="date" class="form-control" id="start_date" name="start_date" required>
    </div>

    <div class="mb-3">
      <label for="end_date" class="form-label">End Date</label>
      <input type="date" class="form-control" id="end_date" name="end_date" required>
    </div>

    <div class="mb-3">
      <label for="material_link" class="form-label">Material Link</label>
      <input type="text" class="form-control" id="material_link" name="material_link" required>
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-custom">
        <i class="bi bi-plus-circle"></i> Add Course
      </button>
      <a href="edit_expertprofile.php?Expert_ID=<?= $expert_id ?>" class="btn btn-secondary">Go Back</a>
    </div>
  </form>
</div>

</body>
</html>
