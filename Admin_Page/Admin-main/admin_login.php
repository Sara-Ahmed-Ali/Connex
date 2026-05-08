<?php
// Connection File to DB
include('../../server/databaseconn.php');
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Santize input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    // Changed to trim , more stable and secure
    $password = trim($_POST['password']);
    // Create statement
    // WHERE BINARY means the exact case
    $stmt = $conn->prepare("SELECT Name, Pwd, Admin_ID FROM admin WHERE BINARY Name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) > 0){
            while($assoc = mysqli_fetch_assoc($result)){
                $stored_hash = $assoc['Pwd'];
                if(password_verify($password, $stored_hash)){
                    $_SESSION["admin_logged_in"] = true;
                    $_SESSION["admin_id"] = $assoc['Admin_ID'];
                    header("location: dashboard.php");
                }
                else{
                    //To Display an admin-friendly notification
                    $error = "Incorrect password";
                }
            }
    }
    else{
        //To Display an admin-friendly notification
            $error = "No admin Found or Incorrect username / password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
</head>
<body>
    <div style="max-width: 300px; margin: 100px auto;">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input class="form-control" type="text" id="username" name="username" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" id="password" name="password" required />
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif (!empty($_SESSION["message"]) && !empty($_SESSION["message_type"])): ?>
                <div class="alert alert-<?php echo $_SESSION["message_type"]; ?>">
                    <?php echo $_SESSION["message"]; ?>
                </div>
            <?php endif; ?>
            <button class="btn btn-primary" type="submit">Login</button>
        </form>
        <br>
            <!--Add onclick direct to admin_reg.php -->
        <button class="btn btn-secondary" onclick="window.location.href='admin_reg.php'">Add Admin</button>
    </div>
</body>
</html>
<?php
if (!empty($_SESSION["message"])) {
    unset($_SESSION["message"]);
    unset($_SESSION["message_type"]);
}
?>
<?php $conn->close(); ?>
