<?php
// Start the session for attendance functionality
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
// Variable to hold the banner message
$bannerMessage = '';

if (isset($_POST['delete'])) {
    $index = $_POST['delete'];
    if (file_exists($_SESSION['attendance'][$index]['file'])) {
        unlink($_SESSION['attendance'][$index]['file']); // Delete file from server
    }
    unset($_SESSION['attendance'][$index]);
    $_SESSION['attendance'] = array_values($_SESSION['attendance']); // Reindex array

    // Set the banner message after deletion
    $bannerMessage = "Attendance deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="manageattendance.css">
    <title>Company | Manage Attendance</title>
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
        <!-- Attendance Table -->
        <h2>Attendance Record</h2>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
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

                // Display attendance records
                foreach ($_SESSION['attendance'] as $index => $record) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($record['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['position']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['time']) . "</td>";
                    echo "<td>
                            <form action='' method='POST' style='display:inline;'>
                                <button type='submit' name='delete' value='$index' class='delete-btn'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

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