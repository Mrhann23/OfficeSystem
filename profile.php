<?php
// Start session to access user data
session_start();

// Redirect to login if no user data in session
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data from the session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-image">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" alt="Profile Image">
            </div>
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
            </div>
            <a href="user.php" class="btn">Edit Profile</a>
            <a href="home.php" class="btn">Back to Home</a>
        </div>
    </div>
</body>
</html>
