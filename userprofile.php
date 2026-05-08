<?php
session_start();
include('server/databaseconn.php');

$user_id = isset($_GET['User_ID']) ? intval($_GET['User_ID']) : 0;
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['Name'] ?? '';
    $address = $_POST['Address'] ?? '';
    $phone = $_POST['Phone'] ?? '';
    $email = $_POST['Email'] ?? '';
    
    $updateAllowed = true; 

if (!empty($_POST['current_password']) || !empty($_POST['new_password'])) {
    if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Fetch current hashed password from DB
        $stmt = $conn->prepare("SELECT Password FROM user WHERE User_ID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stored = $result->fetch_assoc();

        if ($stored && password_verify($current_password, $stored['Password'])) {
            // Update to new password
            $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET Password = ? WHERE User_ID = ?");
            $stmt->bind_param("si", $hashed_new, $user_id);
            $stmt->execute();
        } else {
            $error = "Incorrect current password.";
            $updateAllowed = false; 
        }
    } else {
        $error = "Please fill in both current and new passwords.";
        $updateAllowed = false;
    }
}

if ($updateAllowed && !$error) {
    $stmt = $conn->prepare("UPDATE user SET Name = ?, Address = ?, Phone = ?, Email = ? WHERE User_ID = ?");
    $stmt->bind_param("ssssi", $name, $address, $phone, $email, $user_id);
    $stmt->execute();
    $success = "Profile updated successfully.";
}

    // Handle image upload
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['Image']['name'];
        $file_tmp = $_FILES['Image']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "user_" . $user_id . "." . $ext;
            $new_filepath = "server/uploads/users-img/" . $new_filename;
            $full_path = __DIR__ . "/" . $new_filepath;

            if (move_uploaded_file($file_tmp, $full_path)) {

             // Update image path & name in DB
                $stmt = $conn->prepare("UPDATE user SET Image_name = ?, Image_path = ? WHERE User_ID = ?");
                $stmt->bind_param("ssi", $new_filename, $new_filepath, $user_id);
                $stmt->execute();
            } else {
                $error = "Failed to upload the image.";
            }
        } else {
            $error = "Unsupported image type.";
        }
    }

    if (!$error) {
        // Update expert main info
        $stmt = $conn->prepare("UPDATE user SET Name = ?, Address = ?, Phone = ?, Email = ? WHERE User_ID = ?");
        $stmt->bind_param("ssssi", $name, $address, $phone, $email, $user_id);
        $stmt->execute();
        $success = "Profile updated successfully.";
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT Name, Address, Phone, Email, Password, Image_path FROM user WHERE User_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<h2>User not found</h2>";
    exit;
}

// Image URL logic
if (!empty($user['Image_path']) && file_exists($user['Image_path'])) {
    $image_url = $user['Image_path'];
}
else {
    $image_url = "server/uploads/users-img/img_default.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>
  <link rel="stylesheet" href="./css/profile.css">
  <link rel="stylesheet" href="./assets/icons/all.min.css">
  <!--For Styling the Inbox Icon -->
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
        background: black; 
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
    function makeEditable(id) {
      const el = document.getElementById(id);
      if (el.getAttribute('data-editing') === 'true') return;
      const input = document.createElement('input');
      input.style.boxSizing = 'border-box';
      input.style.fontSize = '17px';
      input.style.padding = '8px';
      input.style.borderRadius = '5px';
      input.type = 'text';
      input.value = el.innerText.trim();
      input.id = id + "_input";
      input.className = 'edit-input';
      el.style.display = 'none';
      el.parentNode.insertBefore(input, el);
      input.focus();
      input.addEventListener('blur', () => {
        el.innerText = input.value.trim();
        el.style.display = '';
        input.remove();
        el.setAttribute('data-editing', 'false');
      });
      el.setAttribute('data-editing', 'true');
    }

    function gatherAndSubmit() {
      document.getElementById('NameInput').value = document.getElementById('userName').innerText.trim();
      document.getElementById('AddressInput').value = document.getElementById('userAddress').innerText.trim();
      document.getElementById('PhoneInput').value = document.getElementById('userPhone').innerText.trim();
      document.getElementById('EmailInput').value = document.getElementById('userEmail').innerText.trim();
      document.getElementById('editForm').submit();
    }
    function togglePasswordForm() {
    const form = document.getElementById('passwordForm');
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
  }
  </script>
</head>
<body>

<?php if ($success): ?>
  <p class="success-message"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
  <p class="error-message"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="back-home">
    <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270"></i></a>
  </div>

<form id="editForm" method="post" enctype="multipart/form-data" action="">
  <input type="hidden" id="NameInput" name="Name" value="<?= htmlspecialchars($user['Name']) ?>">
  <input type="hidden" id="AddressInput" name="Address" value="<?= htmlspecialchars($user['Address']) ?>">
  <input type="hidden" id="PhoneInput" name="Phone" value="<?= htmlspecialchars($user['Phone']) ?>">
  <input type="hidden" id="EmailInput" name="Email" value="<?= htmlspecialchars($user['Email']) ?>">

  <div class="profile-container">
    <div class="profile-card">

      <div class="profile-left">
        <h1 id="userName" onclick="makeEditable('userName')"><?= htmlspecialchars($user['Name']) ?></h1>

        <div class="divider"></div>
         
        <br>
        <div style="display: flex;">
          <h4> Address: </h4>
          <p style="font-size: medium; margin-left: 10px;" id="userAddress" onclick="makeEditable('userAddress')"><?= htmlspecialchars($user['Address']) ?></p> 
        </div>
        <br>
        <div style="display: flex;">
          <h4> Phone: </h4>
          <p style="font-size: medium; margin-left: 10px;" id="userPhone" onclick="makeEditable('userPhone')"><?= htmlspecialchars($user['Phone']) ?></p> 
        </div>
        <br>
        <div style="display: flex;">
          <h4> Email: </h4>
          <p style="font-size: medium; margin-left: 10px;" id="userEmail" onclick="makeEditable('userEmail')"><?= htmlspecialchars($user['Email']) ?></p>
        </div>
        <br>
        <div style="display: flex;">
          <h4>Password: </h4>
          <p style="font-size: medium; margin-left: 10px; cursor: pointer;" onclick="togglePasswordForm()">**********</p>
        </div>

        <!-- Hidden Password Update Form -->
        <div id="passwordForm" style="display:none; margin-top: 10px;">
          <label>Current Password</label><br>
          <input type="password" name="current_password" style="margin-bottom: 5px;"><br>
          <label>New Password</label><br>
          <input type="password" name="new_password" style="margin-bottom: 10px;"><br>
        </div>
        

        <button type="button" class="subscribe-button" onclick="gatherAndSubmit()">Save Changes</button>
      </div>

      <div class="profile-right">

        <div class="image-upload-wrapper">
          <img src="<?= $image_url ?>" alt="User Photo" class="profile-photo" style="width:300px; height:300px;">
          <input type="file" name="Image" id="imageInput" accept="image/*"  style="display:none;" onchange="this.form.submit()">
          <label for="imageInput" class="upload-icon">+</label>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- Inbox Icon-->
<div>
    <a href="view_replies.php" class="btn btn-outline-primary btn-sm ms-3" title="View Replies" style="border-color:rgb(255, 255, 255);">
        <i class="bi bi-envelope-fill" style="color: #AB7442; font-size: 1.3rem;"> Inbox</i>
    </a>
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
  }, 8000); 
</script>

</body>
</html>
