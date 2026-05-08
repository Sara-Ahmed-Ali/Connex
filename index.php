<?php session_start();?>
<!--New addition -->
<?php 
    include('server/databaseconn.php');
    $sql = "SELECT category_ID, Name, category_icon FROM category WHERE is_deleted = 0";
    $result = $conn->query($sql);?>
<!-- -->
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONNEX</title>
    <link rel="icon" href="./assets/img/icon.png">
     <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&display=swap" rel="stylesheet">
    <style>
    .contact-btn{
        width: 200px;
        height: 60px;
        font-size: 20px; 
      }
      .about-text h1 {
    font-size: 60px;
    margin-bottom: 10px;
    color: #111;
    font-weight: bold;
  }
    .about-text p{
      font-size:19px ;
    }
     .vision{
      padding-top: 20px;
      color: rgb(152, 96, 28);
      font-weight: bold;
      font-size: 24px;
     }
     .about-image img {
  max-width: 380px;
  border-radius: 10px;
}
#logout .nav-link:hover{
    color: black;
}
   .info-box {
    width: 300px;
    font-size: 20px;
   }
   li #logout .nav-link{
     margin-top:0px !important;
   }
   #logout .nav-link{
    background-color: var(--maincolor) !important;
    color: white;
    padding: 13px;
    padding-left: 45px;
    padding-right: 45px;
    padding-bottom: 20px;
    height: 100%;
    margin: 0px !important;
}
#logout .nav-link:hover{
    color: #000 !important; 
}
    </style>
</head>
<body>

    <div class="nav-parent">
        <div class="navbar0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 col-md-5 d-flex" id="nav0-content">
                        <p><i class="fa-solid fa-location-dot"></i>123 Street, Egypt, Maadi
                        </p>
                        <p><i class="fa-regular fa-clock"></i>Mon - Fri : 09.00 AM - 09.00 PM</p>
                    </div>
                    <div class="col-lg-5 col-md-5 d-flex" id="nav0-content2">
                        <p>
                          <!-- To Display a notification to user-->
                          <?php 
                          if (isset($_SESSION['message'])) {
                              // $type = $_SESSION['message_type']; // success, error, etc.
                              echo "{$_SESSION['message']}";
                              unset($_SESSION['message']);
                              unset($_SESSION['message_type']);
                          }
                          ?>
                          <!-- <i class="fa-solid fa-phone"></i>+012 345 6789 -->
                        </p>
                        <div class="i">
                        <i class="fa-brands fa-facebook-f"></i>
                        <i class="fa-brands fa-twitter"></i>
                        <i class="fa-brands fa-linkedin-in"></i>
                        <i class="fa-brands fa-instagram"></i>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">CONNEX</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link " href="#"><span style="color: var(--maincolor);">home</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#about">about</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#serivce">services</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#experts">top experts</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#all-boxes">category</a>
                  </li>
                  <!-- Show login button if neither user nor expert is logged in -->
                  <?php if (!isset($_SESSION["user_logged_in"]) && !isset($_SESSION["expert_logged_in"])): ?>
                    <li class="nav-item" id="login">  
                      <a class="nav-link" href="./userLOGIN.php">Log in</a>
                    </li>

                  <!-- Show logout and profile options if a user is logged in -->
                  <?php elseif (isset($_SESSION["user_logged_in"])): ?>
                     <li class="nav-item">
                      <a class="nav-link" href="./userprofile.php?User_ID=<?php echo htmlspecialchars($_SESSION["user_id"]);?>">View Profile</a>
                    </li>
                    <li class="nav-item" id="logout">
                      <a class="nav-link" href="./server/logout.php">Log Out</a>
                    </li>
                   

                  <!-- Show logout and profile options if an expert is logged in -->
                  <?php elseif (isset($_SESSION["expert_logged_in"])): ?>
                     <li class="nav-item">
                      <!-- Linking to edit_expertprofile.php-->
                      <a class="nav-link" href="./edit_expertprofile.php?Expert_ID=<?php echo htmlspecialchars($_SESSION["expert_id"]);?>">View Profile</a>
                    </li>
                    <li class="nav-item" id="logout">
                      <a class="nav-link" href="./server/logout.php">Log Out</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>  
            </div>
          </nav>
        </div>
    </div>

    
          <div class="caption">
            <div class="cap d-flex justify-content-center align-items-center">
            <div class="container  d-flex flex-column justify-content-center align-items-center text-center ">
                 <p> Our AI-powered platform provides expert technology consultations,<br> helping you navigate the latest innovations with ease.</p>
                 <button class="home-btn" onclick="location.href='#all-boxes'"> EXPLORE NOW</button>
              </div> 
            </div>
          </div>
            <section class="sec-1" id="ourtools">
              <div class="container" id="all-boxes">
                <div class="d-lg-flex flex-wrap justify-content-center gap-4">
                  <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-lg-5 col-sm-10 ai-box">
                      <a href="category_details.php?id=<?php echo $row['category_ID']; ?>">
                        <i class="<?php echo $row['category_icon'];?>"></i><br>
                        <?php echo htmlspecialchars($row['Name']); ?>
                      </a>
                    </div>
                  <?php endwhile; ?>
                </div>
              </div>
            </section>
  <!-- services -->
   <section class="services-section" id="serivce">
    <!-- <h4 class="section-subtitle">FEATURES</h4> -->
    <h2 class="section-title">__ Features & Services__</h2>
 
    <div class="services-container">
      <!-- Card 1 -->
      <div class="service-card">
        <img src="assets/img/expert.jpg" alt="Communications">
        <h3>Expert Consultations</h3>
        <p>Connect with certified professionals for one-on-one guidance across tech, design, and business topics — all in one place.</p>
        <a href="#" class="btn">MORE</a>
      </div>
 
      <!-- Card 2 -->
      <div class="service-card">
        <img src="assets/img/all-tools.jpg" alt="Inspired Design">
        <h3>All-in-One Toolkit</h3>
        <p>Centralized access to productivity, development, and design tools — no more switching between platforms..</p>
        <a href="#" class="btn">MORE</a>
      </div>
 
      <!-- Card 3 -->
      <div class="service-card">
        <img src="assets/img/customer.jpg" alt="Happy Customers">
        <h3>Happy Customers</h3>
        <p>Our team is here to ensure your journey is smooth — from technical help to personalized tool recommendations, we focus on your satisfaction every step of the way.</p>
        <a href="#" class="btn">MORE</a>
      </div>
    </div>
  </section>
  <!-- about us -->
   <section class="about-section" id="about">
    <div class="about-content">
      <div class="about-image">
        <img src="assets/img/us.jpg" alt="Phone in hand" />
      </div>
      <div class="about-text">
        <h1>About Us</h1>
        <h3>"We Connect People with Knowledge"</h3>
        <h2 class= "vision">Our Mission</h2>
        <p>
          We bring expert consultations and powerful tools together on one platform.
        Whether you're exploring AI, mastering Microsoft tools, or improving your design,
        we’re here to support your journey with trusted professionals and accessible services.
        </p>

        <p>Whether you join a live session or download materials directly, Expert gives you the flexibility to learn and grow at your own pace</p>

        <h2 class= "vision">Our Vision</h2>
        <p>
         To become the leading platform in the region for on-demand expertise and AI-powered solutions, trusted by individuals and organizations alike.
        </p>
      </div>
    </div>
 
    <div class="info-boxes">
      <div class="info-box">
        <h4>📞 CALL US</h4>
        <p>1 (234) 567-891,</p>
        <p>1 (234) 987-654</p>
      </div>
      <div class="info-box">
        <h4>📍 LOCATION</h4>
        <p>123 Street, Egypt, Maadi</p>
        
      </div>
      <div class="info-box">
        <h4>⏰ HOURS</h4>
        <p>Mon – Fri …… 9 am – 9 pm</p>
        
      </div>
    </div>
  </section>
  <!-- our top experts -->
  <div class="section8" id="experts">
    <div class="container">
      <div class="sec8-h1">
        <h1>__ Top Experts __</h1>
      </div>
      <div class="row">
        <div class="col-lg-2 col-md-5" id="sec8">
          <div class="sec8-img">
            <img src="./assets/img/Adam Saxton.jpg" class="img-fluid">
            <div class="sec8-i">
              <i class="fa-brands fa-linkedin"></i>
              <i class="fa-solid fa-envelope"></i>
              <i class="fa-solid fa-user"></i>
            </div>
            </div>
            <div class="sec8-content">
              <h5>Adam Saxton</h5>
              <h6>Power Bi</h6>
            </div>
          </div>
        <div class="col-lg-2 col-md-5" id="sec8">
          <div class="sec8-img">
            <img src="./assets/img/Leila Gharani.webp" class="img-fluid">
            <div class="sec8-i">
              <i class="fa-brands fa-linkedin"></i>
              <i class="fa-solid fa-envelope"></i>
              <i class="fa-solid fa-user"></i>
            </div>
            </div>
            <div class="sec8-content">
              <h5>Leila Gharani</h5>
              <h6>Execl</h6>
            </div>
          </div>
        <div class="col-lg-2 col-md-5" id="sec8">
          <div class="sec8-img">
            <img src="./assets/img/Georgia Weidman.webp" class="img-fluid">
            <div class="sec8-i">
              <i class="fa-brands fa-linkedin"></i>
              <i class="fa-solid fa-envelope"></i>
              <i class="fa-solid fa-user"></i>
            </div>
            </div>
            <div class="sec8-content">
              <h5>Georgia Weidman</h5>
              <h6>Metasploit</h6>
            </div>
          </div>
        <div class="col-lg-2 col-md-5" id="sec8">
          <div class="sec8-img">
            <img src="./assets/img/Scott Helmers.jpg" class="img-fluid">
            <div class="sec8-i">
              <i class="fa-brands fa-linkedin"></i>
              <i class="fa-solid fa-envelope"></i>
              <i class="fa-solid fa-user"></i>
            </div>
            </div>
            <div class="sec8-content">
              <h5>Scott Helmers</h5>
              <h6>Visio</h6>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

   <!-- Footer -->
   <div class="section10">
          <div class="container">
            <div class="d-lg-flex d-sm-block">
              <div class="col-lg-2 col-md-5" id="sec10-1">
                 <h3>Address</h3>
                 <p><i class="fa-solid fa-location-dot"></i> 123 Street, Egypt, Maadi</p>
                 <p><i class="fa-solid fa-phone"></i>+012 345 67890</p>
                 <p><i class="fa-solid fa-envelope"></i>CONNEX@gmail.com</p>
                 <div class="d-flex" id="first">
                  <i class="fa-brands fa-facebook-f"></i>
                  <i class="fa-brands fa-twitter"></i>
                  <i class="fa-brands fa-linkedin-in"></i>
                  <i class="fa-brands fa-youtube"></i>
                 </div>
              </div>
              <div class="col-lg-2 col-md-5" id="sec10-2">
                  <h3>Services</h3>
                  <p><i class="fa-solid fa-chevron-right"></i> Software Development</p>
                  <p><i class="fa-solid fa-chevron-right"></i> Marketing & Content Creation</p>
                  <p><i class="fa-solid fa-chevron-right"></i> Finance & Investment</p>
                  <p><i class="fa-solid fa-chevron-right"></i> Cybersecurity</p>
                  <p><i class="fa-solid fa-chevron-right"></i> Education & Learning</p>
              </div>
              <div class="col-lg-2 col-md-5" id="sec10-2">
                  <h3>Quick Links</h3>
                 <p onclick="location.href='#about'"><i class="fa-solid fa-chevron-right"></i> About Us</p>
                  <p><i class="fa-solid fa-chevron-right"></i> Contact Us</p>
                  <p onclick="location.href='#serivce'"><i class="fa-solid fa-chevron-right"></i> Our Services</p>
                  <p onclick="location.href='#all-boxes'"><i class="fa-solid fa-chevron-right"></i> Category</p>
                  <p><i class="fa-solid fa-chevron-right"></i> Support</p>
              </div>
              <div class="col-lg-2 col-md-5" id="sec10-3">
                 <h3>Feedback</h3>
                 <p>IF You have any comment. Let me know</p>
                 <div class="d-flex" id="second">
                  <input type="text" placeholder="Your Commment">
                  <button>Submit</button>
                 </div>
              </div>
            </div>
              <hr>
              <div class=" text-center" id="third">
                <p>© CONNEX , All Right Reserved.</p>
              </div>
          </div>
        </div>



     <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
     <script src="./js/main.js"></script>
</body>
</html>
<?php $conn->close(); ?>
