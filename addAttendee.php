<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once('database/dbBusMonitorForm.php'); // Ensure this includes database connection functions

$loggedIn = false;
$accessLevel = 0;
$userID = null;

// Ensure user is logged in
if (isset($_SESSION['_id'])) {
    require_once('include/input-validation.php'); // Include validation functions if necessary
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addAttendee'])) {
    // Retrieve data from the form
    $name = trim($_POST['name']); // Attendee's name
    $route_id = intval($_POST['route_id']); // Selected route ID

    if (empty($name) || empty($route_id)) {
        header("Location: addAttendee.php?error=" . urlencode("Both name and route must be selected."));
        exit();
    }

    // Insert attendee into the database
    $connection = connect(); // Ensure this function connects to your database

    $insertQuery = "INSERT INTO dbAttendees (name, route_id) VALUES (?, ?)";
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param("si", $name, $route_id);

    if ($stmt->execute()) {
        // Success
        $stmt->close();
        $connection->close();
        header("Location: addAttendee.php?message=" . urlencode("Attendee $name was successfully added to the route!"));
        exit();
    } else {
        // Error
        $error_message = $stmt->error;
        $stmt->close();
        $connection->close();
        die("Error: Failed to add attendee. " . $error_message);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("universal.inc"); ?>
    <title>Add Attendee</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
    <h1>Add Attendee to Route</h1>
    <div id="formatted_form">
        <!-- Display Success or Error Messages -->
        <?php
        if (isset($_GET['message'])) {
            echo "<p style='color: red;'>{$_GET['message']}</p>";
        }
        if (isset($_GET['error'])) {
            echo "<p style='color: red;'>{$_GET['error']}</p>";
        }
        ?>

        <!-- Form to Add Attendee -->
        <form action="addAttendee.php" method="post">
            <label for="name">Attendee Name:</label>
            <input type="text" name="name" id="name" required>
            <br><br>

            <label for="route_id">Select Route:</label>
            <select name="route_id" id="route_id" required>
                <option value="" disabled selected>Select a Route</option>
                <?php
                // Fetch all routes from the database
                $connection = connect();
                $query = "SELECT route_id, route_name FROM dbRoute";
                $result = $connection->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['route_id']}'>{$row['route_name']}</option>";
                }

                $connection->close();
                ?>
            </select>
            <br><br>

            <button type="submit" name="addAttendee">Add Attendee</button>
        </form>

        <br>
        <a href="editBusMonitorData.php" style="text-decoration: none;">
            <button style="padding: 10px 20px; font-size: 16px;">Cancel</button>
        </a>
    </div>
</body>
</html>
