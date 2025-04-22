<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fyp');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch login attempts from the database
$query = "SELECT * FROM login_attempts ORDER BY timestamp DESC";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Login Tracking</title>
    <link rel="stylesheet" href="tracking.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
</head>
<body>
    <div class="container">
        <h1>Login Tracking Dashboard</h1>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['latitude']; ?></td>
                    <td><?php echo $row['longitude']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                    <td>
                        <button onclick="showLocation(<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>)">
                            View Map
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div id="map"></div>
    </div>

    <script>
        // Initialize Google Maps
        function showLocation(lat, lng) {
            const mapDiv = document.getElementById('map');
            mapDiv.style.display = 'block';

            const map = new google.maps.Map(mapDiv, {
                center: { lat: lat, lng: lng },
                zoom: 15
            });

            new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
            });
        }
    </script>
</body>
</html>
