<?php
session_start();

// If there is a form submission for resetting the session, we can clear it
if (isset($_POST['reset'])) {
    unset($_SESSION['elapsed_time']);
}

// Initialize total time (this will persist across page reloads)
$totalTime = $_SESSION['elapsed_time'] ?? 0;
$formattedTime = gmdate("H:i:s", $totalTime);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="timer.css">
    <title>Company | Time Tracking</title>

    <script>
        let elapsedTime = <?php echo $totalTime; ?>; // Get the total time from PHP
        let timer; // Reference to the interval
        let isRunning = false;

        // Function to update the timer display
        function updateTimerDisplay() {
            let hours = Math.floor(elapsedTime / 3600);
            let minutes = Math.floor((elapsedTime % 3600) / 60);
            let seconds = elapsedTime % 60;

            // Pad the numbers with leading zeros if necessary
            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            document.getElementById("timer-display").textContent = hours + ":" + minutes + ":" + seconds;
        }

        // Start the timer
        function startTimer() {
            if (!isRunning) {
                isRunning = true;
                timer = setInterval(() => {
                    elapsedTime++;
                    updateTimerDisplay();
                }, 1000);
            }
        }

        // Stop the timer
        function stopTimer() {
            clearInterval(timer);
            isRunning = false;
            // Save the elapsed time in a hidden form input (for PHP to capture)
            document.getElementById("elapsed_time").value = elapsedTime;
            // Optionally, you can make an AJAX request here to store this data in PHP or update it in session.
        }

        // Reset the timer
        function resetTimer() {
            clearInterval(timer);
            isRunning = false;
            elapsedTime = 0;
            updateTimerDisplay();
            // Reset the elapsed time in the hidden form input
            document.getElementById("elapsed_time").value = elapsedTime;
        }

        // On page load, check if we need to start the timer
        window.onload = function() {
            updateTimerDisplay();
            // You can add a condition to start the timer automatically if it was already running before
        };
    </script>
</head>
<body>
     
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

    <div class="timer-container">
        <h2>Employee Timer Tracker</h2>
        <div id="timer-display" class="timer-display"><?php echo $formattedTime; ?></div>

        <!-- Timer Control Buttons -->
        <form method="POST" action="time.php">
            <button type="button" name="play" class="play-btn" onclick="startTimer()" <?php echo isset($_SESSION['start_time']) ? 'disabled' : ''; ?>>Play</button>
            <button type="button" name="stop" class="stop-btn" onclick="stopTimer()" <?php echo !isset($_SESSION['start_time']) ?  : ''; ?>>Stop</button>
            <button type="button" name="reset" class="reset-btn" onclick="resetTimer()">Reset</button>

            <!-- Hidden form to store elapsed time in session -->
            <input type="hidden" name="elapsed_time" id="elapsed_time" value="<?php echo $totalTime; ?>">
        </form>
    </div>
</body>

</html>

