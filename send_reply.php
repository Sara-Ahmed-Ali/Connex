<?php
session_start();
include('server/databaseconn.php');

$expert_id = intval($_SESSION['expert_id']);
$message_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$reply = isset($_POST['reply']) ? trim($_POST['reply']) : '';

if ($message_id > 0 && $reply !== '') {
    $stmt = $conn->prepare("UPDATE messages SET reply = ?, reply_sent_at = NOW() WHERE id = ? AND Expert_ID = ?");
    $stmt->bind_param("sii", $reply, $message_id, $expert_id);
    $stmt->execute();
}

header("Location: view_messages.php?Expert_ID=$expert_id");
exit;
