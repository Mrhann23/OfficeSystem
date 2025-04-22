<?php
session_start();
$host = 'localhost';  // Database host
$db = 'fyp';  // Database name
$user = 'root';  // Database username
$pass = '';  // Database password

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define upload directory path
$uploadDir = 'uploads/';

// Check if the uploads directory exists, if not, create it
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Variable to hold the banner message
$bannerMessage = '';

if (isset($_POST['delete'])) {
    $index = $_POST['delete'];
    if (file_exists($_SESSION['reports'][$index]['file'])) {
        unlink($_SESSION['reports'][$index]['file']); // Delete file from server
    }
    unset($_SESSION['reports'][$index]);
    $_SESSION['reports'] = array_values($_SESSION['reports']); // Reindex array

    // Set the banner message after deletion
    $bannerMessage = "Report deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="managereport.css">
    <title>Company | Manage Report</title>
    <style>
        /* Popup banner styles */
        .popup-banner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 50%;
            margin-left: 375px;
            padding: 15px;
            background-color: #dc3545;
            color: white;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            z-index: 1000;
        }
        .popup-banner.show {
            display: block;
            animation: fadeOut 4s forwards;
        }
        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            80% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                display: none;
            }
        }
    </style>
</head>
<body>
    

    <!-- Video Background -->
    <video autoplay muted loop id="bgVideo">
        <source src="v1.mp4" type="video/mp4">
    </video>

    <!-- Popup Banner -->
    <div id="popup-banner" class="popup-banner"><?php echo $bannerMessage; ?></div>

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

    <div class="main-content">
        <!-- Report Display Table -->
        <h2>Submitted Reports</h2>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Duty Description</th>
                    <th>Task Details</th>
                    <th>File</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($_SESSION['reports'])) {
                    $_SESSION['reports'] = [];
                }

                foreach ($_SESSION['reports'] as $index => $report) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($report['duty']) . "</td>";
                    echo "<td>" . htmlspecialchars($report['task']) . "</td>";
                    echo "<td>" . ($report['file'] ? "<a href='" . htmlspecialchars($report['file']) . "' download>Download File</a>" : "No File") . "</td>";
                    echo "<td>" . htmlspecialchars($report['date']) . "</td>";
                    echo "<td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='delete' value='" . $index . "'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <style>
        .delete-btn {
            padding: 5px 10px;
            color: #fff;
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: red;
        }
    </style>

    <script>
        // Show the popup banner if a message is present
        document.addEventListener('DOMContentLoaded', function () {
            const banner = document.getElementById('popup-banner');
            if (banner.textContent.trim() !== '') {
                banner.classList.add('show');
            }
        });
    </script>
</body>
</html>
