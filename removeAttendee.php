<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once('database/dbBusMonitorForm.php'); 

$loggedIn = false;
$accessLevel = 0;
$userID = null;

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

// Handle form submission to remove an attendee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeAttendee'])) {
    $attendee_id = intval($_POST['attendee_id']);
    $route_id = intval($_POST['route_id']);

    if (empty($attendee_id) || empty($route_id)) {
        header("Location: removeAttendee.php?error=" . urlencode("Both attendee and route must be selected."));
        exit();
    }

    // Remove attendee from the database
    $connection = connect();

    $deleteQuery = "DELETE FROM dbAttendees WHERE attendee_id = ? AND route_id = ?";
    $stmt = $connection->prepare($deleteQuery);
    $stmt->bind_param("ii", $attendee_id, $route_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $stmt->close();
        $connection->close();
        header("Location: removeAttendee.php?message=" . urlencode("Attendee was successfully removed from the route!"));
        exit();
    } else {
        $stmt->close();
        $connection->close();
        header("Location: removeAttendee.php?error=" . urlencode("Error removing attendee. Either the attendee is not assigned to this route or something went wrong."));
        exit();
    }
}

// Handle route selection
$selectedRouteId = isset($_POST['route_id']) ? intval($_POST['route_id']) : null;
$attendees = [];
if ($selectedRouteId) {
    $connection = connect();
    $query = "SELECT attendee_id, name FROM dbAttendees WHERE route_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $selectedRouteId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $attendees[] = $row;
    }

    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("universal.inc"); ?>
    <title>Remove Attendee</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
    <h1>Remove Attendee from Route</h1>
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

        <!-- Form to Select Route and Attendee -->
        <form action="removeAttendee.php" method="post">
            <label for="route_id">Select Route:</label>
            <select name="route_id" id="route_id" required onchange="this.form.submit()">
                <option value="" disabled selected>Select a Route</option>
                <?php
                // Fetch all routes from the database
                $connection = connect();
                $query = "SELECT route_id, route_name FROM dbRoute";
                $result = $connection->query($query);

                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['route_id'] == $selectedRouteId) ? "selected" : "";
                    echo "<option value='{$row['route_id']}' $selected>{$row['route_name']}</option>";
                }

                $connection->close();
                ?>
            </select>
            <br><br>

            <?php if ($selectedRouteId): ?>
                <label for="attendee_id">Select Attendee:</label>
                <select name="attendee_id" id="attendee_id" required>
                    <option value="" disabled selected>Select an Attendee</option>
                    <?php
                    // Populate the attendees dropdown based on the selected route
                    if (!empty($attendees)) {
                        foreach ($attendees as $attendee) {
                            echo "<option value='{$attendee['attendee_id']}'>{$attendee['name']}</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No attendees found for this route</option>";
                    }
                    ?>
                </select>
                <br><br>
            <?php endif; ?>

            <?php if ($selectedRouteId && !empty($attendees)): ?>
                <button type="submit" name="removeAttendee">Remove Attendee</button>
            <?php endif; ?>
        </form>

        <br>
        <a href="editBusMonitorData.php" style="text-decoration: none;">
            <button style="padding: 10px 20px; font-size: 16px;">Cancel</button>
        </a>
    </div>
</body>
</html>
