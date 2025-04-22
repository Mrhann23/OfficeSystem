<?php
session_start();

// Connect to the database
$host = 'localhost';
$db = 'fyp';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert new message into the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['message'])) {
    $username = htmlspecialchars($_POST['username']);
    $message = htmlspecialchars($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO message (username, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $message);
    $stmt->execute();
    $stmt->close();
}

// Insert admin reply into both 'message' and 'community_chat' tables
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply']) && isset($_POST['message_id'])) {
    $reply = htmlspecialchars($_POST['reply']);
    $message_id = intval($_POST['message_id']);
    
    // Update the reply in the 'message' table
    $stmt = $conn->prepare("UPDATE message SET admin_reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply, $message_id);
    $stmt->execute();
    $stmt->close();
    
    // Insert the reply into the 'community_chat' table
    $stmt = $conn->prepare("INSERT INTO community_chat (admin_reply, time) VALUES (?, NOW())");
    $stmt->bind_param("s", $reply);
    $stmt->execute();
    $stmt->close();
}

// Fetch chat history
$messages = $conn->query("SELECT * FROM message ORDER BY timestamp DESC LIMIT 50");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="chat.css">
    <title>Community Chat</title>
</head>
<body>

    <!-- Video Background -->
    <div class="video-container">
        <video autoplay muted loop id="bgVideo">
            <source src="v7.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="image/logo.png" alt="Logo"> <!-- Replace with your logo -->
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
        </ul>
    </nav>


<div class="chat-container">
    <div class="chat-box">
        <h2>Community Chat</h2>
        <div class="messages">
            <?php while ($row = $messages->fetch_assoc()): ?>
                <div class="message">
                    <span class="username"><?php echo $row['username']; ?>:</span>
                    <span class="text"><?php echo $row['message']; ?></span>
                    <span class="timestamp"><?php echo $row['timestamp']; ?></span>
                </div>
                <!-- Display Admin Reply if exists -->
                <?php if (!empty($row['admin_reply'])): ?>
                            <div class="admin-reply">
                                <span class="admin-label" color="purple">Admin:</span>
                                <span class="reply-text"><?php echo htmlspecialchars($row['admin_reply']); ?></span>
                            </div>
                        <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>
    
    <form class="chat-form" method="POST">
        <input type="text" name="username" placeholder="Your name" required>
        <textarea name="message" placeholder="Type your message" required></textarea>
        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
