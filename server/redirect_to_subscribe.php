<?php
session_start();
// Get expert_id
$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
// Get tool_id
$tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
// Get category_id
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($_POST['course'])  || empty($_POST['course'])) {
    // echo "<script>alert('Please select at least one course.'); window.history.back();</script>";
    header("Location: ../expertprofile.php?Expert_ID=$expert_id&tool_id=$tool_id&id=$category_id&error=1&error_message=Please select one course.");
    exit;
}

$today = strtotime(date('Y-m-d'));
$valid_course = '';


// $title = base64_decode($encoded_title); // Decode safely
$title = $_POST['course'];

if (isset($_POST['course_start_date'])) {
    $start_date = strtotime($_POST['course_start_date']);

    if ($start_date > $today) {
        $valid_course = $title;
    }
}


if (empty($valid_course)) {
    // echo "<script>alert(''); window.history.back();</script>";
    header("Location: ../expertprofile.php?Expert_ID=$expert_id&tool_id=$tool_id&id=$category_id&error=1&error_message=The selected course has already started. No valid course to subscribe to.");
    exit;
}


$_SESSION['selected_course'] = $valid_course;
$amount = $_POST['sub_plan'];

if(isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true){
        //Head to subscribe.php if condition is met
        header("Location: ../subscribe.php?Expert_ID=$expert_id&tool_id=$tool_id&amount=$amount");
        exit();
    }
else{
        //Head to Login if conditions unmet
        header("Location: ../userLOGIN.php?message=login_required");
        exit();
    }
?>