<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

// Ensure user is logged in
if (isset($_SESSION['_id'])) {
    require_once('include/input-validation.php'); 
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
}
?>

<?php
if (isset($_GET['message'])) {
    echo "<p style='color: red; font-weight: bold;'>" . htmlspecialchars($_GET['message']) . "</p>";
}

if (isset($_GET['error'])) {
    echo "<p style='color: red; font-weight: bold;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require('universal.inc'); ?>
    <title>Stafford Junction | Forms</title>
</head>
<body>
    <h1>Edit Bus Monitor Data</h1>
    <div id="formatted_form">
    <main class='dashboard'>
        <div id="dashboard">
            <!-- Add Volunteer -->
            <div class="dashboard-item" data-link="addRouteVolunteer.php">
                <span>Add Route Volunteer</span>
            </div>

            <!-- Remove Volunteer -->
            <div class="dashboard-item" data-link="removeVolunteer.php">
                <span>Remove Route Volunteer</span>
            </div>

            <!-- Add Attendee -->
            <div class="dashboard-item" data-link="addAttendee.php">
                <span>Add Attendee</span>
            </div>

            <!-- Remove Attendee -->
            <div class="dashboard-item" data-link="removeAttendee.php">
                <span>Remove Attendee</span>
            </div>

            <!-- Cancel -->
            <div class="dashboard-item" data-link="busMonitorAttendanceForm.php">
                <span>Cancel</span>
            </div>
            </div>
    </main>
    <script>
    // Add click functionality to dashboard items
    document.querySelectorAll('.dashboard-item').forEach(item => {
        item.addEventListener('click', () => {
            window.location.href = item.getAttribute('data-link');
        });
    });
    </script>
</body>
</html>