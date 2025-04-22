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
    <link rel="stylesheet" href="adminchat.css">
    <title>Community Chat</title>
</head>
<body>

    <!-- Video Background -->
    <video autoplay muted loop id="bgVideo">
        <source src="v1.mp4" type="video/mp4">
    </video>
    
    <!-- Side Navigation Bar -->
    <div class="sidenav">
        <div class="logo">
            <img src="image/logo.png" alt="Company Logo" style="width: 80%; height: auto;">
        </div>
        <ul>
            <li><a href="manageattendance.php">Manage Attendance</a></li>
            <li><a href="managereport.php">Manage Submitted Report</a></li>
            <li><a href="manageeleave.php">Manage E-Leave</a></li>
            <li><a href="adminchat.php">Admin Community</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
        <div class="chat-box">
            <h2>Community Chat</h2>
            <div class="messages">
                <?php while ($row = $messages->fetch_assoc()): ?>
                    <div class="message">
                        <span class="username"><?php echo $row['username']; ?>:</span>
                        <span class="text"><?php echo $row['message']; ?></span>
                        <span class="timestamp"><?php echo $row['timestamp']; ?></span>

                        <!-- Display Admin Reply if exists -->
                        <?php if (!empty($row['admin_reply'])): ?>
                            <div class="admin-reply">
                                <span class="admin-label">Admin:</span>
                                <span class="reply-text"><?php echo htmlspecialchars($row['admin_reply']); ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Admin Reply Form -->
                        <form method="POST" class="admin-reply-form">
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <textarea name="reply" placeholder="Type admin reply here..." required></textarea>
                            <button type="submit">Reply</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        
    </div>

</body>
</html>
