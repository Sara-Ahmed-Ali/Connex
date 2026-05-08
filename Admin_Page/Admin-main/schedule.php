<!--Establish Database Connection -->
<?php include('../../server/databaseconn.php')?>
<?php
session_start();
// Check if admin is logged in, else redirect to login page
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCHEDULE</title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>

<?php
$sql = "SELECT 
            user.User_ID AS User_ID,
            user.Name AS User_name,
            expert.Name AS Expert_name,
            tool.Name AS Tool_name,
            subscription.Subscription_date,
            subscription.Payment_amount
        FROM subscription
        JOIN user ON subscription.User_ID = user.User_ID
        JOIN expert ON subscription.Expert_ID = expert.Expert_ID
        JOIN tool ON subscription.Tool_ID = tool.Tool_ID
        ORDER BY subscription.Subscription_date DESC";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    // Changed course Name to App/Tool Name
    echo "<table class='table table-bordered table-striped mt-4 container'>
            <thead class='table-light'>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>Expert Name</th>
                <th>App/Tool Name</th>
                <th>Subscription Date</th>
                <th>Payment Amount</th>
            </tr>
            </thead>
            <tbody>";
    
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>
                <td>{$row['User_ID']}</td>
                <td>{$row['User_name']}</td>
                <td>{$row['Expert_name']}</td>
                <td>{$row['Tool_name']}</td>
                <td>{$row['Subscription_date']}</td>
                <td>{$row['Payment_amount']} EGP </td>
            </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<div class='text-center mt-4'> No Records Found </div>";
}

mysqli_close($conn);
?>

<br>
    <a href="dashboard.php" class="btn btn-primary ms-5 mb-3 mt-3">Go Home</a>
    
    <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="./js/main.js"></script>
</body>
</html>