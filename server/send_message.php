<?php
session_start();
include('./databaseconn.php');
// Get expert_id
$expert_id = isset($_GET['Expert_ID']) ? intval($_GET['Expert_ID']) : 0;
// Get tool_id
$tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
// Get category_id
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expert_id = intval($_POST['expert_id']);
    $sender_name = trim($_POST['sender_name']);
    $sender_email = trim($_POST['sender_email']);
    $message = trim($_POST['message']);

    // Save to database
    $stmt = $conn->prepare("INSERT INTO messages (expert_id, sender_name, sender_email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $expert_id, $sender_name, $sender_email, $message);
    $stmt->execute();
    $stmt->close();

    // Send email to expert
    $to = $_SESSION["expert_email"];
    $subject = "New message from $sender_name";
    $body = "You received a new message:\n\n$message\n\nFrom: $sender_name <$sender_email>";
    $headers = "From: noreply@yoursite.com";
    // mail($to, $subject, $body, $headers);

    // Send email to admin as well
    $admin_email = "admin@yoursite.com"; // Change to your admin email
    // mail($admin_email, "[Admin Copy] $subject", $body, $headers);

    // Optional redirect or success message
    header("Location: ../expertprofile.php?Expert_ID=$expert_id&tool_id=$tool_id&id=$category_id&success=1&success_message=✅ Your message has been sent successfully.");
    exit;
}
?>
