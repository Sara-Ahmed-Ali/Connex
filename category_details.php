<?php 
    include('server/databaseconn.php');
?>
<?php

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get category details
$cat_stmt = $conn->prepare("SELECT Name, Description FROM category WHERE category_ID = ? AND is_deleted = 0");
$cat_stmt->bind_param("i", $category_id);
$cat_stmt->execute();
$cat_result = $cat_stmt->get_result();

if ($cat_result->num_rows === 0) {
    echo "<h1>Category not found</h1>";
    exit;
}

$category = $cat_result->fetch_assoc();

// Get tools in that category
$tool_stmt = $conn->prepare("SELECT Tool_ID, Name, Description, Tool_Image FROM tool WHERE Category_ID = ?");
$tool_stmt->bind_param("i", $category_id);
$tool_stmt->execute();
$tool_result = $tool_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($category['Name']) ?></title>
  <link rel="icon" href="./assets/img/icon.png">
  <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/icons/all.min.css">
  <link rel="stylesheet" href="./css/main.css">
</head>
<body>
  <div class="section11">
    <div class="sec11">
      <div class="sec11-content">
        <h1><?= htmlspecialchars($category['Name']) ?></h1>
        <p><?= htmlspecialchars($category['Description']) ?></p>
      </div>
    </div>
  </div>
  <div class="section4">
    <div class="container">
      <div class="sec4-h1">
        <div class="back-home">
          <a href="index.php" title="Go Back"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270" style="font-size: 2rem;"></i></a>
        </div>
        <h1>__ OUR APPLICATIONS__</h1>
      </div>
      <div class="row">
        <?php while ($tool = $tool_result->fetch_assoc()) { ?>
          <div class="col-lg-3 col-md-5" id="sec4">
            <div class="sec4-img">
              <img src="./assets/img/<?= htmlspecialchars($tool['Tool_Image']) ?>" alt="<?= htmlspecialchars($tool['Name']) ?>" class="img-fluid">
            </div>
            <h1><?= htmlspecialchars($tool['Name']) ?></h1>
            <p><?= htmlspecialchars($tool['Description']) ?></p>
            <!-- <button>Read More <i class="fa-solid fa-arrow-right"></i></button> -->
            <button onclick="window.location.href='tool_details.php?tool_id=<?php echo $tool['Tool_ID']; ?>&id=<?php echo $category_id;?>'">Read More<i class="fa-solid fa-arrow-right"></i></button>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>