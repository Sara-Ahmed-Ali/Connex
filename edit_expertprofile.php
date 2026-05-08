<?php
session_start();
include('server/databaseconn.php');

$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated data from form
    $name = $_POST['Name'] ?? '';
    $bio = $_POST['Bio'] ?? '';

    // Handle image upload
    $image_path = null;
    $image_name = null;
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['Image']['name'];
        $file_tmp = $_FILES['Image']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "expert_" . $expert_id . "." . $ext;
            $new_filepath = "server/uploads/experts-img/" . $new_filename;
            $full_path = __DIR__ . "/" . $new_filepath;

           if (move_uploaded_file($file_tmp, $full_path)) {
             
            // Update image path & name in DB
              $stmt = $conn->prepare("UPDATE expert SET Image_name = ?, Image_path = ? WHERE Expert_ID = ?");
              $stmt->bind_param("ssi", $new_filename, $new_filepath, $expert_id);
              $stmt->execute();
            } 
           else {
              $error = "Failed to upload the image.";
            }
        } 
        else {
            $error = "Unsupported image type.";
        }
    }

    if (!$error) {
        // Update expert main info
        $stmt = $conn->prepare("UPDATE expert SET Name=?, Bio=? WHERE Expert_ID=?");
        $stmt->bind_param("ssi", $name, $bio, $expert_id);
        $stmt->execute();

        // Update schedule
       if (!empty($_POST['day']) && !empty($_POST['start_time']) && !empty($_POST['end_time'])) {
           $days = $_POST['day'];
           $start_times = $_POST['start_time'];
           $end_times = $_POST['end_time'];

           $del_stmt = $conn->prepare("DELETE FROM expert_schedule WHERE Expert_ID = ?");
            $del_stmt->bind_param("i", $expert_id);
            $del_stmt->execute();
            $del_stmt->close();

        $stmt = $conn->prepare("INSERT INTO expert_schedule (Expert_ID, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");

          for ($i = 0; $i < count($days); $i++) {
            if (!empty($days[$i]) && !empty($start_times[$i]) && !empty($end_times[$i])) {
            $start = substr($start_times[$i], 0, 5);
            $end = substr($end_times[$i], 0, 5);
           $stmt->bind_param("isss", $expert_id, $days[$i], $start, $end);
           $stmt->execute();
          }
        }
       $stmt->close();
       }

        $success = "Profile updated successfully.";
    }
}

// Fetch expert data
$expert_stmt = $conn->prepare("SELECT Name, Bio, Image_name, Image_path, Expert_ID FROM expert WHERE Expert_ID = ?");
$expert_stmt->bind_param("i", $expert_id);
$expert_stmt->execute();
$expert_result = $expert_stmt->get_result();
$expert = $expert_result->fetch_assoc(); 

if (!$expert) {
    echo "<h2>Expert not found</h2>";
    exit;
}

// Fetch associated schedule
$schedule_stmt = $conn->prepare("SELECT id, day_of_week, start_time, end_time FROM expert_schedule WHERE Expert_ID = ?");
$schedule_stmt->bind_param("i", $expert_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();
$days = [];
$start_times = [];
$end_times = [];
$schedule_ids = [];

while ($schedule = $schedule_result->fetch_assoc()) {
  $schedule_ids[] = $schedule['id'];
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

// Image URL logic
if (!empty($expert['Image_path']) && file_exists($expert['Image_path'])) {
    $image_url = $expert['Image_path'];
}
else {
    $image_url = "server/uploads/experts-img/img_default.jpg";
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
  <link rel="stylesheet" href="./assets/icons/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<style>
  .image-upload-wrapper {
  position: relative; 
  display: inline-block;
}
  .upload-icon {
  position: absolute;
  bottom: 20px;
  left: 20px; 
  background-color: white; 
  color: black; 
  font-size: 32px; 
  width: 50px;
  height: 50px;
  border-radius: 50%;
  text-align: center;
  line-height: 50px;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
  transition: background-color 0.3s, color 0.3s;
  font-weight: bold;
  z-index: 2;
}

.upload-icon:hover {
  background-color: black; 
  color: white; 
}
.error-message{
  position: fixed;
  top: 20px;
  left: 20px;
  padding: 10px 20px;
  background-color: #f8d7da;
  color: #721c24;
  border-radius: 5px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  font-weight: bold;
  z-index: 9999;
  opacity: 1;
  transition: opacity 1s ease;
}

.fade-out {
  opacity: 0;
}
</style>

<script>
function makeEditable(id, multiline = false) {
    const el = document.getElementById(id);
    if(el.getAttribute('data-editing') === 'true') return;

    let input;
    if(multiline) {
      input = document.createElement('textarea');
      input.rows = 4;
      input.style.width = '100%';
      input.style.height = '120px';
      input.style.resize = 'none';  
      input.style.boxSizing = 'border-box';
      input.style.fontFamily = 'inherit';
      input.style.fontSize = '1rem';
      input.style.padding = '8px';
      input.style.border = '1px solid #ccc';
      input.style.borderRadius = '5px';
    } else {
      input = document.createElement('input');
      input.type = 'text';
      input.style.boxSizing = 'border-box';
      input.style.fontSize = '20px';
      input.style.padding = '8px';
      input.style.borderRadius = '5px';
    }
    input.className = 'edit-input';
    input.value = el.innerText.trim();
    input.id = id + '_input';

    el.style.display = 'none';
    el.parentNode.insertBefore(input, el);

    input.focus();

    input.addEventListener('blur', () => {
      if(input.value.trim() === '') {
        alert('Field cannot be empty.');
        input.focus();
        return;
      }
      el.innerText = input.value;
      el.style.display = '';
      input.remove();
      el.setAttribute('data-editing', 'false');
    });
    el.setAttribute('data-editing', 'true');
}

function gatherAndSubmit() {
    document.getElementById('NameInput').value = document.getElementById('expertName').innerText.trim();
    document.getElementById('BioInput').value = document.getElementById('expertBio').innerText.trim();
    document.getElementById('editForm').submit();
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.editable-day').forEach(cell => {
    cell.addEventListener('click', function () {
      if (this.querySelector('select')) return; 

      const currentDay = this.innerText.trim();
      const days = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

      const select = document.createElement('select');
      select.name = "day[]";

      days.forEach(day => {
        const option = document.createElement('option');
        option.value = day;
        option.textContent = day;
        if (day === currentDay) option.selected = true;
        select.appendChild(option);
      });

      this.textContent = '';
      this.appendChild(select);
    });
  });

  document.querySelectorAll('.editable-time').forEach(cell => {
    cell.addEventListener('click', function () {
      if (this.querySelector('input')) return;

      const start = this.getAttribute('data-start').substring(0,5);
      const end = this.getAttribute('data-end').substring(0,5);

      this.innerHTML = `
        <input type="time" name="start_time[]" value="${start}" required>
        <input type="time" name="end_time[]" value="${end}" required>
      `;
    });
  });
});
function addScheduleSlot() {
  const container = document.getElementById('schedule-container');

  const newSlot = document.createElement('div');
  newSlot.classList.add('schedule-slot');

  newSlot.innerHTML = `
    <br> <label>Day:</label>
    <select name="day[]">
      <option>Monday</option>
      <option>Tuesday</option>
      <option>Wednesday</option> 
      <option>Thursday</option>
      <option>Friday</option>
      <option>Saturday</option>
      <option>Sunday</option>
    </select> <br> <br>

    <label>Start Time:</label>
    <input type="time" name="start_time[]" required>

    <label>End Time:</label>
    <input type="time" name="end_time[]" required>
  `;

  container.appendChild(newSlot);
}
</script>

</head>
<body> 
<?php if ($success): ?>
  <p class="success-message"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
  <p class="message error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="back-home">
    <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270"></i></a>
  </div>

<form id="editForm" method="post" enctype="multipart/form-data" action="">
<input type="hidden" id="NameInput" name="Name" value="<?= htmlspecialchars($expert['Name']) ?>">
<input type="hidden" id="BioInput" name="Bio" value="<?= htmlspecialchars($expert['Bio']) ?>">


<div class="profile-container">
  <div class="profile-card">

    <div class="profile-left">
        <h1 class="expert-name d-flex align-items-center" id="expertName" onclick="makeEditable('expertName')">
        <?= htmlspecialchars($expert['Name']) ?>
        </h1>
        <!-- <a href="view_messages.php?Expert_ID=<?php echo $expert_id; ?>" class="btn btn-outline-primary btn-sm ms-3" title="View messages" style="border-color:rgb(255, 255, 255);">
          <i class="bi bi-envelope-fill" style="color: #AB7442; font-size: 2rem;"></i>
        </a> -->
      <div class="divider"></div>

        <p class="expert-description" id="expertBio" onclick="makeEditable('expertBio', true)">
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
               <td class="editable-day"><?= htmlspecialchars($days[$i]) ?></td>
               <td class="editable-time" data-start="<?= substr($start_times[$i],0 ,5) ?>" data-end="<?= substr($end_times[$i],0 ,5 ) ?>">
        <?= htmlspecialchars(substr($start_times[$i],0 ,5 )) ?> - <?= htmlspecialchars(substr($end_times[$i],0 ,5 )) ?>
               </td>
          <input type="hidden" name="schedule_id[]" value="<?= htmlspecialchars($schedule_ids[$i]) ?>">
            </tr>               
       <?php endfor; ?>
        </table>
      </div>
      <?php else: ?>
      <?php echo "<p>No Schedule Found</p>"?> <br>
      <div id="schedule-container">
  <div class="schedule-slot">
    <label>Day:</label>
    <select name="day[]">
      <option>Monday</option>
      <option>Tuesday</option>
      <option>Wednesday</option> 
      <option>Thursday</option>
      <option>Friday</option>
      <option>Saturday</option>
      <option>Sunday</option>
    </select>
<br> <br>
    <label>Start Time:</label>
    <input type="time" name="start_time[]" required>

    <label>End Time:</label>
    <input type="time" name="end_time[]" required>
  </div>
</div>
      <button type="button" id="addDayBtn" class="add-day-btn" onclick="addScheduleSlot()">+ Add Another Day</button>
      <?php endif; ?>
      <!--For previewing courses -->
      <br>
<?php if (!empty($courses)): ?>
      <div class="courses">
        <h2>Offered Courses</h2>
        <ul class="course-list">
          <?php foreach ($courses as $course): ?>
            <li>
              <strong><?= htmlspecialchars($course['Title']) ?></strong><br>
                Duration: 
              <em>
                <?= date('F Y', strtotime($course['start_date'])) ?> 
                – 
                <?= date('F Y', strtotime($course['end_date'])) ?>
              </em>
              <hr>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php else: ?>
      <p>No courses available.</p>
    <?php endif; ?>
    <a href="add_course.php?Expert_ID=<?php echo $expert_id;?>" style="border: solid black 2px; color: white; background-color: #AB7442; text-align: center;">
      Add New Course
    </a>
      <!-- For previewing courses end-->

      <div class="social-links">
        <a href="#">LinkedIn</a> |
        <a href="#">GitHub</a> |
        <a href="#">Portfolio</a>
      </div>
      <button type="button" class="subscribe-button" onclick="gatherAndSubmit()">Save Changes</button>    </div>

    <div class="profile-right">

     <div class="image-upload-wrapper">
    <img src="<?= $image_url ?>" alt="Expert Photo" class="profile-photo" style="width:300px; height:300px;">
    <input type="file" id="imageInput" name="Image" accept="image/*" style="display: none;" onchange="this.form.submit()">
    <label for="imageInput" class="upload-icon">+</label>
</div>
      <br>
      <br>
      <div>
          <a href="view_messages.php?Expert_ID=<?php echo $expert_id; ?>" class="btn btn-outline-primary btn-sm ms-3" title="View messages" style="border-color:rgb(255, 255, 255);">
          <i class="bi bi-envelope-fill" style="color: #AB7442; font-size: 1.3rem;"> Inbox</i>
        </a>
      </div>

  </div>
</div> 

<script src="./js/main.js"></script>
<script>
  setTimeout(() => {
    const msg = document.querySelector('.success-message');
    if (msg) {
      msg.classList.add('fade-out');
      setTimeout(() => {
        msg.remove();
      }, 1000);
    }
  }, 7000); 
</script>
<script>
  setTimeout(() => {
    const msg = document.querySelector('.error-message');
    if (msg) {
      msg.classList.add('fade-out');
      setTimeout(() => {
        msg.remove();
      }, 1000);
    }
  }, 7000); 
</script>
</body>
</html>
