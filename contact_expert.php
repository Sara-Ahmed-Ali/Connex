<?php
session_start();
include('server/databaseconn.php');

$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
// Get tool_id
$tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
// Get category_id
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch expert's name for the heading
$expert_stmt = $conn->prepare("SELECT Name FROM expert WHERE Expert_ID = ?");
$expert_stmt->bind_param("i", $expert_id);
$expert_stmt->execute();
$expert_result = $expert_stmt->get_result();
$expert = $expert_result->fetch_assoc();

if (!$expert) {
    echo "<h2>Expert not found</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact <?= htmlspecialchars($expert['Name']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom CSS (reuse from profile) -->
  <link rel="stylesheet" href="./css/profile.css">

  <style>
    .contact-form-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 25px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .contact-form-container h2 {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="contact-form-container">
  <h2 class="text-center">Send a Message to <?= htmlspecialchars($expert['Name']) ?></h2>
  <form action="./server/send_message.php?Expert_ID=<?php echo $expert_id; ?>&tool_id=<?php echo $tool_id;?>&id=<?php echo $category_id; ?>" method="POST">
    <input type="hidden" name="expert_id" value="<?= $expert_id ?>">

    <div class="mb-3">
      <label for="visitor_name" class="form-label">Your Name</label>
      <input type="text" class="form-control" id="sender_name" name="sender_name" required value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '';?>">
    </div>

    <div class="mb-3">
      <label for="visitor_email" class="form-label">Your Email</label>
      <input type="email" class="form-control" id="sender_email" name="sender_email" required value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '';?>">
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn" style="background-color: #AB7442; color: white;">
            <i class="bi bi-send-fill"></i> Send Message
        </button>
      <br>
      <a href="expertprofile.php?Expert_ID=<?php echo $expert_id; ?>&tool_id=<?php echo $tool_id;?>&id=<?php echo $category_id; ?>" class="btn btn-secondary">Go Back</a>
    </div>
  </form>
</div>

</body>
</html>
