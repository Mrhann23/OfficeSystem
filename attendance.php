<?php
// Start the session for attendance functionality
session_start();

// Initialize notification message and type
$message = '';
$messageType = ''; // 'success' or 'error'

// Initialize attendance array if not already set
if (!isset($_SESSION['attendance'])) {
    $_SESSION['attendance'] = [
        ['name' => 'John Doe', 'position' => 'Manager', 'date' => '2024-11-09', 'time' => '09:00 AM'],
        ['name' => 'Jane Smith', 'position' => 'Developer', 'date' => '2024-11-09', 'time' => '09:15 AM']
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) { // Add record
        $name = htmlspecialchars($_POST['name']);
        $position = htmlspecialchars($_POST['position']);
        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);

        $_SESSION['attendance'][] = [
            'name' => $name,
            'position' => $position,
            'date' => $date,
            'time' => $time
        ];

        $message = "Attendance record added successfully!";
        $messageType = 'success';
    }

    if (isset($_POST['delete'])) { // Delete record
        $index = $_POST['delete'];
        if (isset($_SESSION['attendance'][$index])) {
            unset($_SESSION['attendance'][$index]);
            $_SESSION['attendance'] = array_values($_SESSION['attendance']); // Reindex array
            $message = "Attendance record deleted successfully!";
            $messageType = 'success';
        } else {
            $message = "Error: Attendance record not found.";
            $messageType = 'error';
        }
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
    <link rel="stylesheet" href="attendance.css">
    <title>Company | Attendance</title>
    <style>
        /* Notification Banner Styles */
        .banner {
            position: fixed;
            top: 100px;
            left: 0;
            width: 50%;
            padding: 15px;
            margin-left: 375px;
            text-align: center;
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
        <span class="close-btn" onclick="hideBanner()"></span>
    </div>

    <!-- Video Background -->
    <div class="video-container">
        <video autoplay muted loop id="bgVideo">
            <source src="v6.mp4" type="video/mp4">
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

        <h2>Attendance Form</h2>

        <!-- Form to Add Attendance Record -->
        <form action="" method="POST" class="attendance-form">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="position" placeholder="Position" required>
            <input type="date" name="date" required>
            <input type="time" name="time" required>
            <button type="submit" name="submit">Add Attendance</button>
            <button type="reset">Reset</button>
        </form>

        <!-- Attendance Table -->
        <table class="attendance-table">
           
            <tbody>
                <?php
                // Initialize attendance array and add form submission
                if (!isset($_SESSION['attendance'])) {
                    $_SESSION['attendance'] = [
                        ['name' => 'John Doe', 'position' => 'Manager', 'date' => '2024-11-09', 'time' => '09:00 AM'],
                        ['name' => 'Jane Smith', 'position' => 'Developer', 'date' => '2024-11-09', 'time' => '09:15 AM']
                    ];
                }

                // Add record on form submission
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['submit'])) {
                        $name = htmlspecialchars($_POST['name']);
                        $position = htmlspecialchars($_POST['position']);
                        $date = htmlspecialchars($_POST['date']);
                        $time = htmlspecialchars($_POST['time']);

                        $_SESSION['attendance'][] = [
                            'name' => $name,
                            'position' => $position,
                            'date' => $date,
                            'time' => $time
                        ];
                    }

                    // Delete record if delete button is pressed
                    if (isset($_POST['delete'])) {
                        $index = $_POST['delete'];
                        unset($_SESSION['attendance'][$index]);
                        $_SESSION['attendance'] = array_values($_SESSION['attendance']); // Reindex array
                    }
                }

                ?>
            </tbody>
        </table>

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
