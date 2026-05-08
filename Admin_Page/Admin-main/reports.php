<?php
include('../../server/databaseconn.php');
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin_login.php');
    exit();
}

$sql = "
    SELECT 
        t.Name AS Tool_name,
        COUNT(DISTINCT s.User_ID) AS Total_subscribers,
        SUM(s.Payment_amount) AS Total_payment,
        SUM(s.Payment_amount) * 0.10 AS site_share
    FROM subscription s
    JOIN tool t ON s.Tool_ID = t.Tool_ID
    GROUP BY s.Tool_ID
    ORDER BY Total_payment DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>REPORTS</title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <!--Changed the name -->
                <th>App/Tool Name</th>
                <th>Total Subscribers</th>
                <th>Total Payment (EGP)</th>
                <th>Site Share (10%)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>
                            <td>{$row['Tool_name']}</td>
                            <td>{$row['Total_subscribers']}</td>
                            <td>{$row['Total_payment']} EGP</td>
                            <td>" . number_format($row['site_share'], 2) . " EGP</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No Records Found</td></tr>";
            }
            ?>
        </tbody>
    </table>
        <a href="dashboard.php" class="btn btn-primary ms-3 mb-3 mt-3">Go Home</a>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>