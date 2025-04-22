<?php
// Database connection
$host = 'localhost';
$db = 'your_database';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch uploaded files from the database
$sql = "SELECT * FROM file_uploads";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Uploaded Files</h2>";
    echo "<table border='1'><tr><th>File Name</th><th>File Size</th><th>Actions</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['file_name']) . "</td>";
        echo "<td>" . $row['file_size'] . " bytes</td>";
        echo "<td><a href='" . $row['file_path'] . "' download>Download</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No files uploaded yet.";
}

$conn->close();
?>
