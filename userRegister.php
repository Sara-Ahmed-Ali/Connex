<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Registration</title>
  <link rel="icon" href="./assets/img/icon.png">
  <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/icons/all.min.css">
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <?php
    if (isset($_SESSION['message'])) {
        $type = $_SESSION['message_type']; // success, error, etc.
        echo "<div class='alert $type'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
  ?>
  
  <div class="back-home">
    <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270"></i></a>
  </div>

  <div class="hero">
    <h1 class="ms-5">User Registration</h1>
  </div>

  <div class="container ">
    <div class="col-lg-8">
      <form action="server/server_1.php" method="post" id="registrationForm" novalidate>
        <div class="d-lg-flex d-sm-inline" id="consult-input">
          <div class="input-group">
            <input type="text" placeholder="Name" class="col-lg-4 col-sm-12 mt-4" id="user_name" name="username">
            <span class="error-message" id="user_name_error"></span>
          </div>

          <div class="input-group">
            <input type="text" placeholder="National ID" class="col-lg-4 col-sm-12 mt-4 " id="regist-id" name="national_id">
            <span class="error-message" id="regist-id_error"></span>
          </div>
        </div>

        <div class="d-lg-flex d-sm-inline" id="consult-input">
          <div class="input-group">
            <input type="text" placeholder="Phone" class="col-lg-4 col-sm-12 mt-4" maxlength="12" id="regist-phone" name="user_phone">
            <span class="error-message" id="regist-phone_error"></span>
          </div>

          <div class="input-group">
            <input type="text" placeholder="12 Street Name, City, State" class="col-lg-4 col-sm-12 mt-4 " id="regist-address" name="user_address">
            <span class="error-message" id="regist-address_error"></span>
          </div>
        </div>

        <div class="d-lg-flex d-sm-inline" id="consult-input">
          <div class="input-group">
            <input type="password" placeholder="Password" class="col-lg-4 col-sm-12 mt-4" id="user_password" name="user_password">
            <span class="error-message" id="user_password_error"></span>
          </div>

          <div class="input-group">
            <input type="email" placeholder="Email" class="col-lg-4 col-sm-12 mt-4 " id="regist-email" name="user_email">
            <span class="error-message" id="regist-email_error"></span>
          </div>
        </div>

        <div class="d-lg-block d-sm-inline">
          <label class="log-link">you have an account? <a href="userLOGIN.php">Log in</a></label>
        </div>

        <div class="regist">
          <button class="btn" type="submit" name="register_button_user">Register</button>
        </div>
      </form>
    </div>
  </div>

  <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
