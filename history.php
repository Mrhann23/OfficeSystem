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

// Fetch approved leaves
$approved_leaves = $conn->query("SELECT * FROM leave_requests WHERE status='Approved'");

// Fetch not approved leaves
$notapproved_leaves = $conn->query("SELECT * FROM leave_requests WHERE status='Not Approved'");

// Fetch pending leaves
$pending_leaves = $conn->query("SELECT * FROM leave_requests WHERE status='Pending'");

// Fetch notifications for submitted reports
$submitted_reports = $conn->query("SELECT * FROM reports WHERE status='Submitted'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="history.css">
    <title>Leave History & Notifications</title>
</head>
<body>


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

    <div class="split-container">
    <!-- Left Section -->
    <div class="left-section">
        <!-- Approved Leaves Section -->
        <h2>Approved Leave Requests</h2>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $approved_leaves->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>

        <!-- Not Approved Leaves Section -->
        <h2>Not Approved Leave Requests</h2>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $notapproved_leaves->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>

        <!-- Pending Leaves Section -->
        <h2>Pending Leave Requests</h2>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pending_leaves->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Right Section -->
    <div class="right-section">
        <!-- Submitted Reports Section -->
        <h2>Submitted Reports</h2>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Duty Description</th>
                    <th>Task Details</th>
                    <th>File</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SESSION['reports'] as $index => $report):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['duty']); ?></td>
                    <td><?php echo htmlspecialchars($report['task']); ?></td>
                    <td>
                        <?php if ($report['file']): ?>
                            <a href="<?php echo htmlspecialchars($report['file']); ?>" download>Download File</a>
                        <?php else: ?>
                            No File
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($report['date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
