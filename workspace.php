<?php

// Define upload directory path
$uploadDir = 'uploads/';

// Check if the uploads directory exists, if not, create it
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Initialize message
$message = '';
$messageType = ''; // 'success' or 'error'

// File upload logic
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['file']['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        $message = "File uploaded and Report successfully Submit!";
        $messageType = 'success';
    } else {
        $message = "Error: Failed to move the uploaded file.";
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
    <link rel="stylesheet" href="workspace.css">
    <title>Company | Workspaces</title>
    <style>
        /* Banner Notification Styles */
        .banner {
            position: center;
            top: 100px;
            left: 0;
            width: 50%;
            text-align: center;
            padding: 15px;
            margin-left: 376px;
            z-index: 1000;
            font-size: 18px;
            display: none;

        }

        .banner.success {
            background-color: #28a745;
            position: center;
            color: white;
        }

        .banner.error {
            background-color: #dc3545;
            color: white;
        }

        .banner .close-btn {
            position: absolute;
            right: 15px;
            top: 10px;
            cursor: pointer;
            font-size: 18px;
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

<div class="report-container">
    <h2>Submit Report</h2>

    <!-- Report Submission Form with File Upload -->
    <form action="" method="POST" enctype="multipart/form-data" class="report-form">
    <textarea name="duty" placeholder="Duty Description" required></textarea>
    <textarea name="task" placeholder="Task Details" required></textarea>
    
    <!-- File input field -->
    <div class="file-input-wrapper">
        <label for="file" class="file-label">Choose File</label>
        <input type="file" id="file" name="file" required>
        <span id="file-name">No file chosen</span>
    </div>

    <button type="submit" name="submit">Submit Report</button>
    <button type="reset">Reset</button>
</form>

    <!-- Report Display Table -->
    <table class="report-table">
        <tbody>
            <?php
            session_start();
            if (!isset($_SESSION['reports'])) {
                $_SESSION['reports'] = [];
            }

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
                $duty = htmlspecialchars($_POST['duty']);
                $task = htmlspecialchars($_POST['task']);
                $date = date("Y-m-d H:i:s");

                // File upload handling
                $file = $_FILES['file'];
                $filePath = 'uploads';

                if ($file['error'] == 0) {
                    $filePath = 'uploads/' . basename($file['name']);
                    move_uploaded_file($file['tmp_name'], $filePath);
                }

                // Add new report to the session array
                $_SESSION['reports'][] = [
                    'duty' => $duty,
                    'task' => $task,
                    'file' => $filePath,
                    'date' => $date
                ];
            }

            // Handle report deletion
            if (isset($_POST['delete'])) {
                $index = $_POST['delete'];
                if (file_exists($_SESSION['reports'][$index]['file'])) {
                    unlink($_SESSION['reports'][$index]['file']); // Delete file from server
                }
                unset($_SESSION['reports'][$index]);
                $_SESSION['reports'] = array_values($_SESSION['reports']); // Reindex array
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    // Function to show the banner
    function showBanner(message, type) {
        const banner = document.getElementById('notification-banner');
        banner.classList.add(type);
        banner.textContent = message;
        banner.style.display = 'block';
    }

    // Automatically hide banner after 3 seconds
    const banner = document.getElementById('notification-banner');
    if (banner && banner.textContent.trim() !== '') {
        banner.style.display = 'block';
        setTimeout(() => {
            banner.style.display = 'none';
        }, 3000);
    }

    // Function to hide the banner when the close button is clicked
    function hideBanner() {
        const banner = document.getElementById('notification-banner');
        banner.style.display = 'none';
    }

    document.getElementById('file').addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.getElementById('file-name').textContent = fileName;
    });
</script>

</body>
</html>
