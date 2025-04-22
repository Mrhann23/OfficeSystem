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
    $bannerMessage = "Leave deleted successfully!";
}

// Handle add leave request
if (isset($_POST['add_leave'])) {
    $name = $_POST['employee_name'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO leave_requests (employee_name, leave_type, start_date, end_date, status) VALUES ('$name', '$leave_type', '$start_date', '$end_date', 'Pending')";
    $conn->query($sql);
}

// Handle update leave request (edit)
if (isset($_POST['edit_leave'])) {
    $id = $_POST['id'];
    $sql = "UPDATE leave_requests SET status='Not Approved' WHERE id=$id";
    $conn->query($sql);
}

// Handle delete leave request
if (isset($_POST['delete_leave'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM leave_requests WHERE id=$id";
    $conn->query($sql);
}

// Handle approve leave request
if (isset($_POST['approve_leave'])) {
    $id = $_POST['id'];
    $sql = "UPDATE leave_requests SET status='Approved' WHERE id=$id";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="manageeleave.css">
    <title>Company | Leave Management</title>
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

    <div class="container">
    <h3>Existing Leave Requests</h3>

    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetching all leave requests from the database
            $result = $conn->query("SELECT * FROM leave_requests");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['employee_name'] . "</td>";
                echo "<td>" . $row['leave_type'] . "</td>";
                echo "<td>" . $row['start_date'] . "</td>";
                echo "<td>" . $row['end_date'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>
                        <form method='POST' class='action-form' style='display:inline;'>
                            <button type='submit' name='approve_leave' class='approve-btn'>Approve</button>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                        </form>
                        <form method='POST' class='action-form' style='display:inline;'>
                            <button type='submit' name='edit_leave' class='edit-btn'>Decline</button>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                        </form>
                        <form method='POST' class='action-form' style='display:inline;'>
                            <button type='submit' name='delete_leave' class='delete-btn'>Delete</button>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
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
