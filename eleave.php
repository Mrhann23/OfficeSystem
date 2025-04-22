<?php
session_start();
$host = 'localhost';  // Your database host
$db = 'fyp';  // Your database name
$user = 'root';  // Database username
$pass = '';  // Database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize notification message
$message = '';
$messageType = ''; // 'success' or 'error'

// Handle add leave request
if (isset($_POST['add_leave'])) {
    $name = $_POST['employee_name'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO leave_requests (employee_name, leave_type, start_date, end_date) VALUES ('$name', '$leave_type', '$start_date', '$end_date')";

    if ($conn->query($sql) === TRUE) {
        $message = "Leave request added successfully!";
        $messageType = 'success';
    } else {
        $message = "Error adding leave request: " . $conn->error;
        $messageType = 'error';
    }
}

// Handle update leave request (edit)
if (isset($_POST['edit_leave'])) {
    $id = $_POST['id'];
    $name2 = $_POST['employee_name'];
    $leave_type2 = $_POST['leave_type'];
    $start_date2 = $_POST['start_date'];
    $end_date2 = $_POST['end_date'];

    $sql = "UPDATE leave_requests SET employee_name='$name2', leave_type='$leave_type2', start_date='$start_date2', end_date='$end_date2' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $message = "Leave request updated successfully!";
        $messageType = 'success';
    } else {
        $message = "Error updating leave request: " . $conn->error;
        $messageType = 'error';
    }
}

// Handle delete leave request
if (isset($_POST['delete_leave'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM leave_requests WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $message = "Leave request deleted successfully!";
        $messageType = 'success';
    } else {
        $message = "Error deleting leave request: " . $conn->error;
        $messageType = 'error';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="eleave.css">
    <title>Company | E-Leave</title>
    <style>
        /* Notification Banner Styles */
        .banner {
            position: fixed;
            top: 80px;
            left: 0;
            width: 50%;
            padding: 15px;
            text-align: center;
            margin-left: 402px;
            z-index: 1000;
            font-size: 18px;
            display: none; /* Initially hidden */
        }

        .banner.success {
            background-color: #28a745;
            color: white;
        }

        .banner.error {
            background-color: #dc3545;
            color: white;
        }

        .banner .close-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>

<body>
        <!-- Notification Banner -->
        <div class="banner <?php echo htmlspecialchars($messageType); ?>" id="notification-banner">
            <span><?php echo htmlspecialchars($message); ?></span>
            <span class="close-btn" onclick="hideBanner()">×</span>
        </div>

<!-- Video Background -->
<div class="video-container">
        <video autoplay muted loop id="bgVideo">
            <source src="v8.mp4" type="video/mp4">
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

    <div class="container">
        <h2>E-Leave Form</h2>
        
        <!-- Form for Adding and Editing Leave Requests -->
        <form method="POST" class="leave-form">
            <label for="employee_name">Employee Name:</label>
            <input type="text" name="employee_name" required>

            <label for="leave_type">Leave Type:</label>
            <select name="leave_type" required>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Annual Leave">Annual Leave</option>
                <option value="Emergency Leave">Emergency Leave</option>
            </select>

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

            <input type="hidden" name="id" value=""> <!-- Hidden field for editing -->

            <button type="submit" name="add_leave">Add Leave</button>
            
            <button type="submit" name="reset_form">Reset</button>
        </form>

    </div>

    <script>
    // Automatically display the banner if there is a message
    const banner = document.getElementById('notification-banner');
    if (banner && banner.textContent.trim() !== '') {
        banner.style.display = 'block'; // Show the banner

        // Automatically hide the banner after 3 seconds
        setTimeout(() => {
            banner.style.display = 'none';
        }, 3000);
    }

    // Function to manually hide the banner
    function hideBanner() {
        banner.style.display = 'none';
    }
</script>
</body>

</html>