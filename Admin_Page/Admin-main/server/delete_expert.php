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
        //Updated the query to update is_deleted attr and record admin_id
        $stmt = "UPDATE expert SET Status = 'DELETED', Admin_ID = $admin_id WHERE Expert_ID = '$id'";
        if(mysqli_query($conn, $stmt)){
            echo "<script>
            alert('Record Deleted Successfully!'); window.location.href='expert.php';
            </script>";
        }
        else{
            echo "<script>
            alert('Error Deleting Record.'); window.location.href='expert.php';
            </script>";
        }
        mysqli_close($conn);
    }
    else{
        echo "<script>
        alert('Invalid Request Method'); window.location.href='expert.php';
        </script>";
    }
?>