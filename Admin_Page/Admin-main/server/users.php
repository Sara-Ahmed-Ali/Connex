<!--Establish Database Connection -->
<?php include('../../../server/databaseconn.php')?>
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
    <title>USERS</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/icons/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <!--Fetch Data From Database -->
    <?php
        // Updated the statement to show only the active users [not deleted]
        $stmt = "SELECT User_ID, Name, National_ID, Phone, Email, Address FROM user WHERE is_deleted = 0";
        $result = mysqli_query($conn, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo '<div class="me-4 mt-3">';
            echo '<table class="table table-bordered table-striped ms-3" style="margin-right: 1rem;">';
            echo '<thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>National ID</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Control</th>
            </tr>
            </thead>
                  <tbody>';
            while($assoc = mysqli_fetch_assoc($result)){
                echo "<tr>
                    <td>{$assoc['User_ID']}</td>
                    <td>{$assoc['Name']}</td>
                    <td>{$assoc['National_ID']}</td>
                    <td>{$assoc['Phone']}</td>
                    <td>{$assoc['Email']}</td>
                    <td>{$assoc['Address']}</td>
                    <td>
                        <form action='delete_user.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this record?\")'>
                            <input type='hidden' name='id' value='{$assoc['User_ID']}'>
                            <button type='submit' class='btn btn-danger'>Delete</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</table>";
        echo '</div>';
    }
    else {
        echo "No Records Found";
    }
    mysqli_close($conn);
    ?>
    <br>
    <a href="../dashboard.php" class="btn btn-primary ms-3 mb-3">Go Home</a>
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>