<?php 
    session_start();
    // Get expert_id
    $expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
    // Get tool_id
    $tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        // Check of selection is empty
        if (!empty($_GET['selected_material'])){
            // Check if user is logged in
            if (isset($_SESSION["user_logged_in"])){
                $selected_material = $_GET['selected_material'];
                $amount = $_GET['price_amount'];
                header("Location: ../subscribe.php?Expert_ID=$expert_id&tool_id=$tool_id&amount=$amount");
                exit;
            }
            // Redirect to login
            else {
                header("Location: ../userLOGIN.php?message=login_required");
                exit;                
            }
        }
        // Go back to get_material.php
        else {
            // A way to notify the user instead of alert
            echo "<script>alert('Please select a course.');window.history.back();</script>";
            exit;
        }
    }
?>