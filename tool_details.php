<?php
include('server/databaseconn.php');

$tool_id = isset($_GET['tool_id']) ? intval($_GET['tool_id']) : 0;
// Get category id
$category_id =isset($_GET['id']) ? intval($_GET['id']) : 0;
// Fetch tool data
$tool_stmt = $conn->prepare("SELECT Name, Description, Long_Description, Tool_Image FROM tool WHERE Tool_ID = ?");
$tool_stmt->bind_param("i", $tool_id);
$tool_stmt->execute();
$tool_result = $tool_stmt->get_result();
$tool = $tool_result->fetch_assoc(); 

if (!$tool) {
    echo "<h2>Tool not found</h2>";
    exit;
}

// Fetch associated experts (many-to-many)
$expert_stmt = $conn->prepare("
    SELECT e.Expert_ID, e.Name, e.Image_path 
    FROM expert e
    INNER JOIN expert_tool et ON e.Expert_ID = et.Expert_ID
    WHERE et.Tool_ID = ? and e.status = 'ACCEPTED'
");
$expert_stmt->bind_param("i", $tool_id);
$expert_stmt->execute();
$experts = $expert_stmt->get_result();

// to dynamically set the price for each category
if ($category_id == 6){
    // project management
    $sub_plan_one = 400;
}
elseif ($category_id == 7){
    // Ai tools
    $sub_plan_one = 500;
}
else if ($category_id == 11){
    // System Analysis
    $sub_plan_one = 500;
}
else if ($category_id == 8){
    // Web Application
    $sub_plan_one = 400;
}
else if ($category_id == 9){
    // Microsoft Office
    $sub_plan_one = 400;
}
else if ($category_id == 12){
    // Mobile Apps
    $sub_plan_one = 400;
}
else if ($category_id == 13){
    // UI/UX
    $sub_plan_one = 500;
}
else{
    $sub_plan_one = 500;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tool['Name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/img/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .content-page {
            max-width: 1000px;  
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .content-header img {
            max-width: 800px;
            height: 80%;
            width: 40%;
            object-fit: contain;
            border-radius: 10px;
        }
        .content-description {
            width: 50%;
            text-align: left;
            direction: ltr;
        }
        .experts-section {
            margin-top: 40px;
            text-align: center;
        }
        .experts-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .expert-card {
            width: 30%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .expert-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .subscribe-btn {
            background-color: #ad7037;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .subscribe-btn:hover {
            background-color: #bb8450;
        }
        .subscribe-btn a {
            text-decoration: none;
            color: white;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="content-page">
    <div class="back-home">
      <a href="category_details.php?id=<?php echo $category_id;?>" title="Go Back"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270" style="font-size: 2rem;;"></i></a>
    </div>
    <div class="content-header">
        <img src="./assets/img/<?= htmlspecialchars($tool['Tool_Image']) ?>" alt="<?= htmlspecialchars($tool['Name']) ?> Image">
        <div class="content-description">
            <h2><?= htmlspecialchars($tool['Name']) ?></h2>
            <p><?= nl2br(htmlspecialchars($tool['Description'])) ?></p>
            <p><?= nl2br(htmlspecialchars($tool['Long_Description'])) ?></p>
            <?php echo "<h5>Pricing plans: </h5>"?>
            <?php echo "<p>For 1 Session/month: $sub_plan_one</p>";?>
            <?php echo "<p>Subscription Plan A: 3 Sessions/month: 1000</p>";?>
            <?php echo "<p>Subscription Plan B: Unlimited Sessions/month: 1500</p>";?>
        </div>
    </div>

    <div class="experts-section">
        <h3>Meet Our Experts</h3>
        <div class="experts-container">
            <?php if ($experts->num_rows > 0): ?>
                <?php
                while($expert = $experts->fetch_assoc()):
                if (!empty($expert['Image_path']) && file_exists($expert['Image_path'])) {
                   $image_path = $expert['Image_path'];
                }  
                else {
                   $image_path = "server/uploads/experts-img/img_default.jpg";
                }
             ?>
            <div class="expert-card">
                <img src="<?= htmlspecialchars($image_path) ?>" alt="Expert photo" class="profile-photo" style="width:100px; height:100px; border-radius:50%; object-fit:cover;">
                <h4><?= htmlspecialchars($expert['Name']) ?></h4>
                <button class="subscribe-btn">
                    <a href="expertprofile.php?Expert_ID=<?php echo $expert['Expert_ID']; ?>&tool_id=<?php echo $tool_id; ?>&id=<?php echo $category_id; ?>">View Profile</a>
                </button>
            </div>
                <?php endwhile; ?>
 
            <?php else: ?>
                <p>No experts found for this tool.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- <a href="category_details.php?category_id=..." class="back-link">&#8592; Back to Tools</a> -->
</div>
</body>
</html>
