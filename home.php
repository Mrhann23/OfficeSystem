<?php
// Start session to access user data
session_start();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Home Page</title>
</head>
<body>
    <!-- Video Background -->
    <div class="video-container">
        <video autoplay muted loop id="bgVideo">
            <source src="v2.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>


    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="image/logo2.png" alt="Logo"> <!-- Replace with your logo -->
            <span class="logo-text">Smart Tech</span>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Features</a>
                <ul class="dropdown-menu">
                    <li><a href="time.php">Time Tracking</a></li>
                    <li><a href="workspace.php">Workspace</a></li>
                    <li><a href="eleave.php">E-Leave</a></li>
                </ul>
            </li>
            <li><a href="chat.php">Community</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="login.php">Log Out</a></li>
            <!-- Profile Icon -->
            <!-- Profile Icon -->
            <div class="profile-icon">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" alt="Profile Picture" id="profile-circle">
                <div class="profile-popup" id="profile-popup">
                    <div class="profile-popup-header">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" alt="Profile Picture" class="popup-image">
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <p><strong>Email:</strong><?php echo htmlspecialchars($user['email']); ?></p>
                   
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p><br>
                        <a href="user.php" class="popup-btn">Edit Profile</a>
                        
                    </div>
                </div>
            </div>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <h1>Welcome to Smart Tech</h1>
        <p>"Transforming Ideas into Smart Technology."</p>
        <a href="attendance.php" class="cta-button">Submit Attendance</a>
    </div>

    <!-- Gallery Section -->
<div class="featured-grid">
    <div class="item">
        <video autoplay muted loop>
            <source src="v3.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
    </div>
    <div class="item">
        <video autoplay muted loop>
            <source src="v4.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
    </div>
    <div class="item">
        <video autoplay muted loop>
            <source src="v5.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
    </div>
</div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Smart Tech. All rights reserved.
        <br>
        
    </footer>
</body>
</html>
