<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribtion</title>
    <link rel="icon" href="./assets/img/icon.png">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="css/subscribe.css">
</head>
<body>
    <!--To Display a user-friendly notification -->
  <?php
    if (isset($_SESSION['message'])) {
        $type = $_SESSION['message_type']; // success, error, etc.
        echo "<div class='my-alert $type'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
  ?>
  
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="img-sub">
                    <img src="./assets/img/robot.jpg" alt="" class="img-fluid">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="back-home">
                    <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                  </div>

                <div class="container-header">
                </div>
                <section class="sub-input">
                    <?php
                    $expert_id = isset($_GET['Expert_ID']) ? $_GET['Expert_ID'] : 0;
                    $tool_id = isset($_GET['tool_id']) ? $_GET['tool_id'] : 0;
                    $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 0;
                    ?>
                    <form action="server/subscribe.php" method="post" id="myForm">
                        <h2 class="pay-h2 mt-4">Payment Info</h2>
                        <input type="hidden" name="expert_id" value="<?php echo $expert_id; ?>">
                        <input type="hidden" name="tool_id" value="<?php echo $tool_id; ?>">
                        <div class="input-group">
                        <input type="text" placeholder="User Name" name="subscribe_name" class="col-lg-6" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '';?>">
                        </div>
                        <div class="input-group">
                            <input type="text" id="name" name="cardholder_name" placeholder="Cardholder Name">
                        </div>
            
                        <div class="input-group">
                            <input type="text" id="card-number" name="card_number" placeholder="Card Number" maxlength="19">
                        </div>
            
                        <div class="input-group">
                                <input type="text" id="expiry" name="expiry_date" placeholder="Expiry Date :MM/YY" maxlength="5">
                        </div>
                        <div class="input-group">
                                <input type="text" id="cvv" name="cvv" placeholder="CVV" maxlength="3">
                        </div>
                        <div class="input-group">
                                <input type="text" id="payment" name="payment" placeholder="Payment Amount" maxlength="5" value="<?php echo $amount;?>">
                        </div>
                         <div class="regist">
                            <button type="submit" name="subscribe_button" class="pay-btn sub-button">Subscribe</button>
                        </div>
                  </form>
                </section>
            </div>
        </div>
    </div>
     <script src="js/subscribe.js"></script>
</body>
</html>