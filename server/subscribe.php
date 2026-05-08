<?php
session_start();
$expert_id = isset($_POST['expert_id']) ? $_POST['expert_id'] : 0;
$tool_id   = isset($_POST['tool_id']) ? $_POST['tool_id'] : 0;
// Include DB connection
include('databaseconn.php');
// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize input
    $Subscribe_name   = filter_input(INPUT_POST, "subscribe_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $Cardholder_name  = filter_input(INPUT_POST, "cardholder_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $Card_number      = filter_input(INPUT_POST, "card_number", FILTER_SANITIZE_SPECIAL_CHARS);
    $Expiry_date      = filter_input(INPUT_POST, "expiry_date", FILTER_SANITIZE_SPECIAL_CHARS);
    $Payment_amount     = filter_input(INPUT_POST, "payment", FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        $user_id   = $_SESSION['user_id'];       
        $expert_id = $_POST['expert_id']; 
        $tool_id = $_POST['tool_id'];

        $stmt = $conn->prepare("INSERT INTO subscription (Expert_ID, User_ID, Tool_ID, Name, Cardholder_name, Card_number, Expiry_date, Payment_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissssd", $expert_id, $user_id, $tool_id, $Subscribe_name, $Cardholder_name, $Card_number, $Expiry_date, $Payment_amount);
        $stmt->execute();


        $_SESSION["message"] = "Your subscription is successful. We will contact you soon.";
        $_SESSION["message_type"] = "success";
        header("Location: ../index.php");
        exit();
    } catch(Exception $e) {

        $_SESSION["message"] = "Subscription failed. Please check your details and try again.";
        $_SESSION["message_type"] = "error";
        header("Location: ../subscribe.php");
        exit();
    }
}

$conn->close();
