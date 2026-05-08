<?php include('../../../server/databaseconn.php')?>
<?php 
    // Started a session
    session_start();
    // Storing admin_id in a variable
    $admin_id = $_SESSION['admin_id'];
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])){
        //Ensure integer value [More secure]
        $id = intval($_POST['id']);
        //Execute query
        // Updated the stmt to update is_deleted row and record admin_id
        $stmt = "UPDATE user SET is_deleted = 1, Admin_ID = $admin_id WHERE User_ID = '$id'";
        if(mysqli_query($conn, $stmt)){
            echo "<script>
            alert('Record Deleted Successfully!'); window.location.href='users.php';
            </script>";
        }
        else{
            echo "<script>
            alert('Error Deleting Record.'); window.location.href='users.php';
            </script>";
        }
        mysqli_close($conn);
    }
    else{
        echo "<script>
        alert('Invalid Request Method'); window.location.href='users.php';
        </script>";
    }
?>