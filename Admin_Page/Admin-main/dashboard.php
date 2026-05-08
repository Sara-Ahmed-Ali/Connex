<?php
session_start();

// Check if admin is logged in, else redirect to login page
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD</title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
    <button class="btn btn-danger" onclick="window.location.href='admin_logout.php'">Log Out</button>
    <div class="content-page">
        <div class="content-header">
        <div class="nav-h1">
            <h1>ADMIN BOARD</h1>
        </div>
    <div class="container">
        <div class="row">
            <div class="d-flex">
                <div class="users" id="box"  onclick="location.href='./server/users.php';">
                    <i class="fa-solid fa-users"></i>
                    <h1>USERS</h1>
                </div>
                <div class="experts" id="box" onclick="location.href='./server/expert.php';">
                    <i class="fas fa-comments"></i>
                    <h1> EXPERTS</h1>
                </div>
            </div>
            <div class="d-flex">
                <div class="reports" id="box" onclick="location.href='reports.php';">
                    <i class="fa-solid fa-chart-gantt"></i>
                    <h1>REPORTS</h1>
                </div>
                <div class="schedule" id="box" onclick="location.href='schedule.php';">
                    <i class="fa-solid fa-clock"></i>
                    <h1>SCHEDULE</h1>
                </div>
            </div>
            <!--Added Categories Icon for adding and deleting categories-->
            <div class="d-flex">
            <div class="categories" id="box" onclick="location.href='./server/categories.php';">
                <i class="fa-solid fa-folder"></i>
                <h1>CATEGORIES</h1>
            </div>
            <!--For adding tools for each category [under construction] -->
            <div class="tools" id="box" onclick="location.href='./server/tools.php';">
                <i class="fa-solid fa-tools"></i>
                <h1>TOOLS</h1>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
    <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="./js/main.js"></script>
</body>
</html>