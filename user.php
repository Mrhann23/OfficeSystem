<?php
// Start session to store user data
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fyp');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Redirect to login if no user data in session
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data from the session
$user = $_SESSION['user'];
$userId = $user['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    // Handle profile image upload
    $imageData = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $imageData = file_get_contents($_FILES['profile_image']['tmp_name']);
    }

    // Update user data in the database
    if ($imageData) {
        $query = "UPDATE user SET name = ?, email = ?, phone_number = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssi', $name, $email, $phone, $imageData, $userId);
    } else {
        $query = "UPDATE user SET name = ?, email = ?, phone_number = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $name, $email, $phone, $userId);
    }

    if ($stmt->execute()) {
        // Update session data with the latest values from the database
        $result = $conn->query("SELECT * FROM user WHERE id = $userId");
        if ($result->num_rows > 0) {
            $_SESSION['user'] = $result->fetch_assoc();
        }
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update profile.');</script>";
    }
    $stmt->close();
}

// Get updated user data
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-image">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" alt="Profile Image">
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="profile-form">
                <div class="form-group">
                    <label for="profile_image">Profile Image:</label>
                    <input type="file" name="profile_image" id="profile_image">
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                </div>
                <button type="submit" class="btn">Update Profile</button>
                <a href="profile.php" class="btn">View Profile</a>
            </form>
        </div>
    </div>
</body>
</html>
