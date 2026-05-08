<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Login</title>
    <link rel="icon" href="./assets/img/icon.png">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <!--To Display a user-friendly notification -->
  <?php
    if (isset($_SESSION['message'])) {
        $type = $_SESSION['message_type']; // success, error, etc.
        echo "<div class='alert $type'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
  ?>
    <!-- <div class="expert-img">
        <img src="./assets/img/login4.jpg">
    </div> -->
    <div class="back-home">
      <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270"></i></a>
    </div>
    <section id="user-loginPage">
        <form action="server/server_1.php" method="post">
          <div>
            <h1 class="ps-5">Expert Login</h1>
            <div class="user-input">
              <input class="col-lg-6" type="text" placeholder="username" id="expert_name" name="expert_name" required>
              <input class="col-lg-6" type="password" placeholder="Password" id="expert_password" name="expert_password" required>
            </div>
              <button type="submit" name="button_login_expert"> Log In</button> <br><br>
              <a href="expertRegister.php" class="regist-link"> you don't have an account? Register</a>
          </div>
        </form>
      </section>
      <!--This script unintentionally disabled the login button-->
      <!--It is interfering with the login button-->
      <!-- <script src="./js/main.js"></script> -->
</body>
</body>
</html>