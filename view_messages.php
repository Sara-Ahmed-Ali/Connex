<?php
session_start();
include('server/databaseconn.php');

// Get logged-in expert ID from session
$expert_id = isset($_SESSION['expert_id']) ? intval($_SESSION['expert_id']) : 0;

// Fetch messages for this expert
$stmt = $conn->prepare("SELECT id, sender_name, sender_email, message, sent_at, reply, reply_sent_at FROM messages WHERE expert_id = ? ORDER BY sent_at DESC");
$stmt->bind_param("i", $expert_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Messages</title>
  <link rel="stylesheet" href="./assets/icons/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- <div class="back-home">
    <a href="edit_expertprofile.php?Expert_ID=<//?php echo $expert_id;?>"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270" style="font-size: 1.6rem; color: #AB7442; padding: 20px;"></i></a>
</div> -->
<div class="container mt-5">
  <h2 class="mb-4">Messages Sent to You</h2>
  <?php if ($result->num_rows > 0): ?>
    <div class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="list-group-item mb-4">
            <h5><?= htmlspecialchars($row['sender_name']) ?> 
                <small class="text-muted">(<?= htmlspecialchars($row['sender_email']) ?>)</small>
            </h5>
            <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <small class="text-muted">Sent at: <?= $row['sent_at'] ?></small>

            <?php if (!empty($row['reply'])): ?>
            <div class="mt-3 p-3 bg-light border">
                <strong>Your Reply:</strong><br>
                <?= nl2br(htmlspecialchars($row['reply'])) ?><br>
                <small class="text-muted">Replied at: <?= $row['reply_sent_at'] ?></small>
            </div>
            <?php else: ?>
            <!-- Reply Form -->
            <form action="send_reply.php" method="POST" class="mt-3">
                <div class="mb-2">
                <textarea name="reply" class="form-control" rows="3" required placeholder="Type your reply..."></textarea>
                </div>
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-sm btn-success">Send Reply</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>

    </div>
    <br>
    <br>
    <a href="edit_expertprofile.php?Expert_ID=<?php echo $expert_id;?>" class="btn btn-secondary">Go Back</a>
  <?php else: ?>
    <p>No messages yet.</p>
    <a href="edit_expertprofile.php?Expert_ID=<?php echo $expert_id;?>" class="btn btn-secondary">Go Back</a>
  <?php endif; ?>
</div>
</body>
</html>
