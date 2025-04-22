<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'fyp');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fixed location (latitude and longitude for Shamelin Perkasa)
$fixedLat = 3.1257; // Shamelin Perkasa latitude
$fixedLon = 101.7405; // Shamelin Perkasa longitude

// Haversine formula to calculate distance between two coordinates
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Earth's radius in kilometers

    // Convert latitude and longitude differences to radians
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    // Calculate distance using the Haversine formula
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c; // Distance in kilometers
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 hashing to match the stored hash

    // Get user's current location from the POST request
    $currentLat = !empty($_POST['currentLat']) ? floatval($_POST['currentLat']) : $fixedLat;
    $currentLon = !empty($_POST['currentLon']) ? floatval($_POST['currentLon']) : $fixedLon;

    // Query to check user credentials
    $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Fetch user's registered coordinates
        $registeredLat = floatval($user['latitude']);
        $registeredLon = floatval($user['longitude']);

        // Calculate the distance
        $distance = calculateDistance($registeredLat, $registeredLon, $currentLat, $currentLon);

        // Set the allowed radius (e.g., 10 km)
        $allowedRadius = 5;

        if ($distance <= $allowedRadius) {
            // User is within the radius
            $_SESSION['user'] = $user;

            // Redirect based on user type
            if ($user['type'] == 1) {
                header("Location: manageattendance.php");
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            // User is out of the radius
            echo "<script>alert('You are out of the allowed login location.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}


// Registration Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Insert user into the database
    $query = "INSERT INTO user (name, phone_number, address, email, password, confirm_password)
              VALUES ('$name', '$phone_number', '$address', '$email', '$password', '$confirm_password')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Registration successful! You can now log in.');</script>";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="style.css">
    <title>Company | Login & Registration</title>
</head>
<body>

 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <img src="image/logo3.png">
        </div>
        
        <div class="nav-button">
            <button class="btn white-btn" id="loginBtn" onclick="login()">Log In</button>
            <button class="btn" id="registerBtn" onclick="register()">Register</button>
        </div>
    </nav>

<!----------------------------- Form box ----------------------------------->    
    <div class="form-box">
        
        <!-- Login Form -->
<div class="login-container" id="login">
    <div class="top">
        <span>Don't have an account? <a href="#" onclick="register()">Register</a></span>
        <header>Login</header>
    </div>
    <form method="POST" action="login.php">
    <div class="input-box">
        <input type="text" class="input-field" placeholder="Email" name="email" required>
        <i class="bx bx-user"></i>
    </div>
    <div class="input-box">
        <input type="password" class="input-field" placeholder="Password" name="password" required>
        <i class="bx bx-lock-alt"></i>
    </div>
    <!-- Hidden fields to send the user's location -->
    <input type="hidden" id="currentLat" name="currentLat">
    <input type="hidden" id="currentLon" name="currentLon">
    <div class="input-box">
        <input type="submit" class="submit" name="login" value="Log In">
    </div>
</form>

<script>
    // Fetch user's current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.getElementById('currentLat').value = position.coords.latitude;
                document.getElementById('currentLon').value = position.coords.longitude;
            },
            (error) => {
                alert('Unable to fetch location. Please enable location services.');
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
</script>

</div>


<!-- Registration Form -->
<div class="register-container" id="register">
    <div class="top">
        <span>Have an account? <a href="#" onclick="login()">Log In</a></span>
        <header>Register</header>
    </div>
    <form method="POST" action="login.php">
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Name" name="name" required>
            <i class="bx bx-user"></i>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Phone Number" name="phone_number" required>
            <i class="bx bx-phone"></i>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Address" name="address" required>
            <i class="bx bx-location-plus"></i>
        </div>
        <div class="input-box">
            <input type="email" class="input-field" placeholder="Email" name="email" required>
            <i class="bx bx-envelope"></i>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" name="password" required>
            <i class="bx bx-lock-open-alt"></i>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Confirm Password" name="confirm_password" required>
            <i class="bx bx-lock-alt"></i>
        </div>
        <div class="input-box">
            <input type="submit" class="submit" name="register" value="Register">
        </div>
    </form>
</div>

    <!-- Video Background -->
    <div class="video-container">
        <video autoplay muted loop id="bgVideo">
            <source src="v10.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

<script>
   
   function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
   }
 
</script>

<script>

    var a = document.getElementById("loginBtn");
    var b = document.getElementById("registerBtn");
    var x = document.getElementById("login");
    var y = document.getElementById("register");

    function login() {
        x.style.left = "4px";
        y.style.right = "-520px";
        a.className += " white-btn";
        b.className = "btn";
        x.style.opacity = 1;
        y.style.opacity = 0;
    }

    function register() {
        x.style.left = "-510px";
        y.style.right = "5px";
        a.className = "btn";
        b.className += " white-btn";
        x.style.opacity = 0;
        y.style.opacity = 1;
    }

</script>

</body>
</html>