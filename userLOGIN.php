<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="icon" href="./assets/img/icon.png">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
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
  <div class="back-home">
    <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270"></i></a>
  </div>
  
    <section id="user-loginPage">
        <form action="server/server_1.php" method="post">
          <div>
            <h1 class="ps-5">User Login</h1>
            <?php
              if(isset($_GET['message']) && $_GET['message'] === 'login_required'){
                echo "<h4 style= 'padding-left: 52px;'>Please Login To Subscribe.</h4>";
              }
            ?>
            <div class="user-input">
              <input class="col-lg-6" type="text" placeholder="username" id="user_name" name="username" required>
              <input class="col-lg-6" type="password" placeholder="Password" id="user_password" name="user_password" required>
            </div>
              <button type="submit"id="userLogin" name="button_login_user"> Log In</button> <br><br>
              <a href="userRegister.php" class="regist-link"> you don't have an account? Register</a><br>
              <a href="./expertLOGIN.php" class="regist-link"> you are an expert? Login</a>
          </div>
        </form>
      </section>
</body>
</body>
</html>