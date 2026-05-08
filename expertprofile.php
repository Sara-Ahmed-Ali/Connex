<?php
include('server/databaseconn.php');
session_start();
$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
// success notification.
$showSuccess = isset($_GET['success']) && $_GET['success'] == 1;
$success_message = isset($_GET['success_message']) ? urldecode($_GET['success_message']) : '';
// Failure notification
$showError = isset($_GET['error']) && $_GET['error'] == 1;
$error_message = isset($_GET['error_message']) ? urldecode($_GET['error_message']) : '';
// Get tool_id
$tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
// Get category_id
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Fetch expert data
// Included email in the stmt
$expert_stmt = $conn->prepare("SELECT Name, Email, Bio, Gender, Expert_ID, Image_name, Image_path FROM expert WHERE Expert_ID = ?");
$expert_stmt->bind_param("i", $expert_id);
$expert_stmt->execute();
$expert_result = $expert_stmt->get_result();
$expert = $expert_result->fetch_assoc(); 
// Fetch expert's email into session
$_SESSION["expert_email"] = $expert["Email"];
// Fetch expert's name into session
$_SESSION["expert_name"] = $expert["Name"];

if (!$expert) {
    echo "<h2>Expert not found</h2>";
    exit; 
}
// Fetch associated schedule
$schedule_stmt = $conn->prepare("SELECT day_of_week, start_time, end_time FROM expert_schedule WHERE Expert_ID = ?");
$schedule_stmt->bind_param("i", $expert_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();
while ($schedule = $schedule_result->fetch_assoc()) {
  $days[] = $schedule['day_of_week'];
  $start_times[] = $schedule['start_time'];
  $end_times[] = $schedule['end_time'];
}
$schedule_stmt->close();
// Fetch associated tools
$tool_stmt = $conn->prepare("
    SELECT t.Name 
    FROM tool t
    INNER JOIN expert_tool et ON t.Tool_ID = et.Tool_ID
    WHERE et.Expert_ID = ?
");
$tool_stmt->bind_param("i", $expert_id);
$tool_stmt->execute();
$tool_result = $tool_stmt->get_result();

$tools = [];
while ($row = $tool_result->fetch_assoc()) {
    $tools[] = $row['Name'];
}

// Fetch associated courses
$course_stmt = $conn->prepare("SELECT Title, Description, start_date, end_date FROM course WHERE Expert_ID = ?");
$course_stmt->bind_param("i", $expert_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();

$courses = [];
while ($row = $course_result->fetch_assoc()) {
    $courses[] = $row;
}
$course_stmt->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Expert Profile</title>
  <link rel="stylesheet" href="./css/profile.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
<!-- Bootstrap styling for the notification-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body> 
<!-- Show success message -->
<?php if ($showSuccess): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 10px;">
    <?php echo $success_message;?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<!-- Show error message-->
  <?php elseif ($showError):?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 10px;">
    <?php echo $error_message;?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="profile-container">
  <div class="profile-card">

    <div class="profile-left">
     <!-- Messaging System?-->
     <h1 class="expert-name d-flex align-items-center">
      <?= htmlspecialchars($expert['Name']) ?>
      <!--log(final_touches) changed condition to allow only logged-in users to message experts-->
      <?php if (isset($_SESSION["user_logged_in"])):?>
        <a href="contact_expert.php?Expert_ID=<?php echo $expert_id; ?>&tool_id=<?php echo $tool_id; ?>&id=<?php echo $category_id;?>" class="btn btn-outline-primary btn-sm ms-3" title="Contact <?php echo $expert['Name'];?>" style="border-color:rgb(255, 255, 255);">
          <i class="bi bi-envelope-fill" style="color: #AB7442; font-size: 2rem;"></i>
        </a>
      <?php endif;?>
      </h1>
      <div class="divider"></div>

      <p class="expert-description">
        <?= nl2br(htmlspecialchars($expert['Bio'])) ?>      
      </p>

      <div class="skills">
          <?php if (!empty($tools)): ?>
            <?php foreach ($tools as $tool): ?>
              <span class="skill-tag"><?= htmlspecialchars($tool) ?></span>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No tools registered.</p>
          <?php endif; ?>
      </div>
      <?php if (!empty($days)): ?>
      <div class="schedule">
        <h2>Available Schedule</h2>
        <table class="schedule-table">
          <tr>
            <th>Day</th>
            <th>Time</th>
          </tr>
          <?php for ($i = 0; $i < count($days); $i++):?>
          <tr>
            <td><?php echo $days[$i];?></td>
            <td><?php echo $start_times[$i];?> – <?php echo $end_times[$i];?></td>
          </tr>
          <?php endfor; ?>
        </table>
      </div>
      <?php else: ?>
      <?php echo "<p>No Schedule Found</p>"?>
      <?php endif; ?>
      <br>
<?php if (!empty($courses)): ?>
  <div class="courses">
    <h2>Offered Courses <?php if (!isset($_SESSION["expert_logged_in"])): ?><small style="font-size: 14px;">(Select a course from below)</small><?php endif;?></h2>
    <form method="POST" action="./server/redirect_to_subscribe.php?Expert_ID=<?php echo $expert_id;?>&tool_id=<?php echo $tool_id;?>&id=<?php echo $category_id;?>">
      <ul class="course-list">
        <?php
        foreach ($courses as $course): 
          $course_title = $course['Title']; // no htmlspecialchars yet
          $safe_title = base64_encode($course_title);
          $start_date = strtotime($course['start_date']);
          $now = time();
          $is_expired = $start_date < $now;
        ?>
          <li>
            <?php if (!isset($_SESSION["expert_logged_in"])): ?>
              <label>
              <!--changing to radio button -->
                <input 
                  type="radio" 
                  name="course" 
                  value="<?= $course_title ?>"
                  <?= $is_expired ? 'disabled' : '' ?>
                >
                <strong><?= htmlspecialchars($course_title) ?></strong>
                <?php if ($is_expired): ?>
                  <span style="color: red; font-size: 0.9em;">(Closed)</span>
                <?php endif; ?>
              </label>
              <!-- <input type="hidden" name="course_start_dates[<//?= $safe_title ?>]" value="<//?= $course['start_date'] ?>"> -->
               <input type="hidden" name="course_start_date" value="<?= $course['start_date'] ?>">
            <?php else: ?>
              <strong><?= htmlspecialchars($course_title) ?></strong>
            <?php endif; ?>
            <br>
            Duration: 
            <em><?= date('l, d F Y', strtotime($course['start_date'])) ?> – <?= date('l, d F Y', strtotime($course['end_date'])) ?></em>
            <hr>
          </li>
        <?php endforeach; ?>
      </ul>
      <!--Add a selection for subscription type -->
      <?php $sub_plan_A = 1000; $sub_plan_B = 1500;?>
      <?php 
        if ($category_id == 6){
          // project management
          $sub_plan_one = 400;
        }
        elseif ($category_id == 7){
          // Ai tools
          $sub_plan_one = 500;
        }
        else if ($category_id == 11){
          // System Analysis
          $sub_plan_one = 500;
        }
        else if ($category_id == 8){
          // Web Application
          $sub_plan_one = 400;
        }
        else if ($category_id == 9){
          // Microsoft Office
          $sub_plan_one = 400;
        }
        else if ($category_id == 12){
          // Mobile Apps
          $sub_plan_one = 400;
        }
        else if ($category_id == 13){
          // UI/UX
          $sub_plan_one = 500;
        }
        else{
          $sub_plan_one = 500;
        }
      ?>
      <?php if (!isset($_SESSION["expert_logged_in"])): ?>
      <label>Please choose your subscription plan: </label><br>
        <input type="radio" name="sub_plan" value="<?= $sub_plan_one?>"> 1 Session/month: <?= $sub_plan_one ?> <br>
        <input type="radio" name="sub_plan" value="<?= $sub_plan_A?>"> Plan A: 3 Sessions/month: <?= $sub_plan_A?> <br>
        <input type="radio" name="sub_plan" value="<?= $sub_plan_B?>"> Plan B: Unlimited Sessions/month: <?= $sub_plan_B?> <br>
      <?php endif;?>
      <?php if (!isset($_SESSION["expert_logged_in"])): ?>
        <button class="subscribe-button" onclick="location.href='./server/redirect_to_subscribe.php?Expert_ID=<?php echo $expert_id; ?>&tool_id=<?php echo $tool_id; ?>';">Subscribe Now</button>
      <?php endif; ?>
    </form>
          <?php if (!isset($_SESSION["expert_logged_in"])):?>
            <br>
            <br>
            <div>
              <a 
                href="./server/get_material.php?Expert_ID=<?= $expert_id ?>&tool_id=<?= $tool_id ?>&id=<?= $category_id ?>&material_only=true"
                style="font-size: 0.9em; color: #007BFF; text-decoration: underline;"
              >
                Need the material only?
              </a>
            </div>
            <?php endif;?>
  </div>
<?php else: ?>
  <p>No courses available.</p>
<?php endif; ?>


      <button class="subscribe-button" onclick="location.href='tool_details.php?tool_id=<?php echo $tool_id; ?>&id=<?php echo $category_id; ?>';">Go Back</button>
      <div class="social-links">
        <a href="#">LinkedIn</a> |
        <a href="#">GitHub</a> |
        <a href="#">Portfolio</a>
      </div>
      <!--An expert cannot subscribe through an expert account -->
      <!-- <//?php if (!isset($_SESSION["expert_logged_in"])):?>
      <button class="subscribe-button" onclick="location.href='./server/redirect_to_subscribe.php';">Subscribe Now</button>
      <//?php endif;?> -->
      <!-- <button class="subscribe-button" id="go-to-payment">Subscribe Now</button> -->
    </div>

    <div class="profile-right">
   <?php

if (!empty($expert['Image_path']) && file_exists($expert['Image_path'])) {
    $image_url = $expert['Image_path'];
}
else {
    $image_url = "server/uploads/experts-img/img_default.jpg";
}

?>

<img src="<?= htmlspecialchars($image_url) ?>" alt="Expert Photo" class="profile-photo" style="width:300px; height:300px; object-fit: cover;">

    </div>


  </div>
</div> 

<script src="./js/main.js"></script>
</body>
</html>
