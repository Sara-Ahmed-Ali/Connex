<?php
// Connection File to DB
include('../../server/databaseconn.php');
session_start();
$error = '';

// Check request method
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Input sanitization
    $user_name = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        // Changed filter_input to trim for stability and preventing errors
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    //Check password match
    if($password !== $confirm_password){
        $error = "Passwords Do Not Match!";
    }
    else {
        // Create hash
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Create statement
        $stmt = $conn->prepare("INSERT INTO admin (Name, Email, Pwd) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_name, $email, $hash);
        try {
            $stmt->execute();
            //To Display an admin-friendly notification
            $_SESSION["message"] = "Registration Successful!";
            $_SESSION["message_type"] = "success";
            header("Location: admin_login.php");
            exit();
        } catch (mysqli_sql_exception) {
            //To Display a user-friendly notification
            $error = "Username already taken. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin registration</title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
</head>
<body>
    <div style="max-width: 300px; margin: 100px auto;">
        <h2>Admin registration</h2>
        <form method="POST" action="admin_reg.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input class="form-control" type="text" id="username" name="username" required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" type="email" id="email" name="email" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" id="password" name="password" required />
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input class="form-control" type="password" id="confirm_password" name="confirm_password" required />
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <button class="btn btn-primary" type="submit">Register</button>
        </form>
        <br>
        <button class="btn btn-secondary" onclick="window.location.href='admin_login.php'">Already registered?</button>
    </div>
</body>
</html>
<?php $conn->close(); ?>
