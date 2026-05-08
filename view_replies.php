<?php
session_start();
include('server/databaseconn.php');

// Fetch messages for this user from each expert
$stmt = $conn->prepare(
"SELECT id, messages.Expert_ID, sender_name, message, sent_at, reply, reply_sent_at, expert.Name FROM messages
INNER JOIN
expert ON expert.Expert_ID = messages.Expert_ID WHERE sender_name = ? AND messages.reply_sent_at != 'NULL' ORDER BY messages.sent_at DESC;"
);
$stmt->bind_param("s", $_SESSION["user_name"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Fetch Expert's name
// $stmt = $conn->prepare("SELECT Expert_ID, Name FROM expert WHERE Expert_ID = ?");
// $stmt->bind_param("i", )

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your received replies</title>
  <link rel="stylesheet" href="./assets/icons/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- <div class="back-home">
    <a href="edit_expertprofile.php?Expert_ID=<//?php echo $expert_id;?>"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270" style="font-size: 1.6rem; color: #AB7442; padding: 20px;"></i></a>
</div> -->
<div class="container mt-5">
  <h2 class="mb-4">Replies Sent to You</h2>
  <?php if ($result->num_rows > 0): ?>
    <div class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="list-group-item mb-4">
            <h5>From: <?= htmlspecialchars($row['Name']) ?> 
                <!-- <small class="text-muted">(<//?= htmlspecialchars($row['sender_email']) ?>)</small> -->
            </h5>
            <p><?= nl2br(htmlspecialchars($row['reply'])) ?></p>
            <small class="text-muted">Sent at: <?= $row['reply_sent_at'] ?></small>
            <div class="mt-3 p-3 bg-light border">
                <strong>Your Message:</strong><br>
                <?= nl2br(htmlspecialchars($row['message']))?><br>
                <small>Sent at: <?= $row['sent_at']?></small>
            </div>

            <!-- <//?php if (!empty($row['reply'])): ?>
            <div class="mt-3 p-3 bg-light border">
                <strong>Your Reply:</strong><br>
                <//?= nl2br(htmlspecialchars($row['reply'])) ?><br>
                <small class="text-muted">Replied at: <//?= $row['reply_sent_at'] ?></small>
            </div> -->
            <!-- php else:-->
            <!-- Reply Form -->
            <!-- <form action="send_reply.php" method="POST" class="mt-3">
                <div class="mb-2">
                <textarea name="reply" class="form-control" rows="3" required placeholder="Type your reply..."></textarea>
                </div>
                <input type="hidden" name="id" value="<//?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-sm btn-success">Send Reply</button>
            </form> -->
            <!--php endif; -->
        </div>
        <?php endwhile; ?>

    </div>
    <br>
    <br>
    <a href="userprofile.php?User_ID=<?php echo $_SESSION["user_id"]?>" class="btn btn-secondary">Go Back</a>
  <?php else: ?>
    <p>No Replies yet.</p>
    <a href="userprofile.php?User_ID=<?php echo $_SESSION["user_id"]?>" class="btn btn-secondary">Go Back</a>
  <?php endif; ?>
</div>
</body>
</html>
