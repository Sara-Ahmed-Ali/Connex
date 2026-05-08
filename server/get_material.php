<?php
include("databaseconn.php");
session_start();
$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
$tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_GET['material_only'] == 'true') {
    $course_stmt = $conn->prepare("SELECT Title, Description, start_date, end_date FROM course WHERE Expert_ID = ?");
    $course_stmt->bind_param("i", $expert_id);
    $course_stmt->execute();
    $course_result = $course_stmt->get_result();
    $courses = [];
    while ($row = $course_result->fetch_assoc()) {
        $courses[] = $row;
    }
    $course_stmt->close();
} else {
    header("location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Get Course Material</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .form-container {
      max-width: 800px;
      margin: 50px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 10px;
    }

    .btn-custom {
      background-color: #AB7442;
      color: white;
    }

    .course-item {
      padding: 15px;
      border-bottom: 1px solid #ddd;
    }

    .course-item:last-child {
      border-bottom: none;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2><i class="bi bi-book"></i> Get Course Materials</h2>
  <p class="text-center text-muted mb-4">
    Courses offered by <strong><?= htmlspecialchars($_SESSION["expert_name"]) ?></strong><br>
    <!-- Changed title-->
    <small>(Please select the course you want material from)</small>
  </p>

  <?php if (!empty($courses)): ?>
    <form method="GET" action="test_1.php">
      <?php foreach ($courses as $course): ?>
        <?php 
          $course_title = $course['Title'];
          // $safe_title = base64_encode($course_title);
        ?>
        <div class="course-item">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="selected_material" value="<?= $course_title ?>" id="<?= $course_title ?>" required>
            <!--Added hidden input to send the amount -->
            <?php $price_amount = 100;?>
            <input type="hidden" name="price_amount" value="<?= $price_amount?>">
            <input type="hidden" name="Expert_ID" value="<?= $expert_id?>">
            <input type="hidden" name="tool_id" value="<?= $tool_id?>">
            <label class="form-check-label fw-bold" for="<?= $course_title ?>">
              <?= htmlspecialchars($course_title) ?>
            </label>
          </div>
          <p class="mb-1"><small class="text-muted"><?= htmlspecialchars($course['Description']) ?></small></p>
          <p class="mb-0"><i class="bi bi-calendar-event"></i> 
            <?= date('l, d F Y', strtotime($course['start_date'])) ?> – 
            <?= date('l, d F Y', strtotime($course['end_date'])) ?>
          </p>
        </div>
      <?php endforeach; ?>

      <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-custom">
          <i class="bi bi-download"></i> Get Selected Material
        </button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
          <i class="bi bi-arrow-left-circle"></i> Go Back
        </button>
      </div>
    </form>
  <?php else: ?>
    <div class="alert alert-warning text-center">No courses found for this expert.</div>
  <?php endif; ?>
</div>

</body>
</html>
