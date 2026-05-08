<?php include('../../../server/databaseconn.php')?>
<?php 
    session_start();
    $admin_id = $_SESSION['admin_id'];
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])){
        //Ensure integer value [More Secure]
        $id = intval($_POST['id']);
        //Check which button
        //Expert accepted
        if(isset($_POST['accept_button_expert'])){
            //Execute query
            // Updated the query to update the Admin_Id column
            $stmt = "UPDATE expert SET Status = 'ACCEPTED', Admin_ID = $admin_id WHERE expert.Expert_ID = $id";
            if(mysqli_query($conn, $stmt)){
                echo "<script>
                    alert('Expert Accepted'); window.location.href='expert.php';
                </script>";
            }
            else{
                echo"<script>
                    alert('Something is wrong'); window.location.href='expert.php';
                </script>";
            }
        }
        //Expert rejected
        else if(isset($_POST['reject_button_expert'])){
            //Execute query
            //Updated the query to update Admin_ID
            $stmt = "UPDATE expert SET Status = 'REJECTED', Admin_ID = $admin_id WHERE expert.Expert_ID = $id";
            if(mysqli_query($conn, $stmt)){
                echo "<script>
                    alert('Expert Rejected'); window.location.href='expert.php';
                </script>";
            }
            else{
                echo"<script>
                    alert('Something is wrong'); window.location.href='expert.php';
                </script>";
            }
        }
        mysqli_close($conn);
    }
    else{
        echo "<script>
        alert('Invalid Request Method'); window.location.href='expert.php';
        </script>";
    }
?>