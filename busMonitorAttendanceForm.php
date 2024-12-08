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
    require_once('database/dbBusMonitorForm.php');
    require_once('include/input-validation.php'); 
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitAll'])) {
    // Get the selected route
    $selectedRoute = isset($_POST['route']) ? $_POST['route'] : null;

    if (!$selectedRoute) {
        header("Location: busMonitorAttendanceForm.php?error=" . urlencode("Route not selected."));
        exit();
    }

    // Define route-specific locations
    $northLocations = ['Foxwood'];
    $southLocations = ['Meadows', 'Jefferson Place', 'Olde Forge', 'England Run'];
    $locations = $selectedRoute === 'north' ? $northLocations : $southLocations;

    $connection = connect(); // Establish database connection
    $attendance_date = date('Y-m-d'); // Today's date
    $batch_data = []; // Array to collect all data for batch insert

    foreach ($locations as $location) {
        $route_id = getRouteId($location, $connection);
        if (!$route_id) {
            error_log("No route_id found for location: $location");
            continue; // Skip if no route_id is found
        }

        // Fetch all volunteers and attendees for this location
        $volunteers = getVolunteersForLocation($location);
        $attendees = getAttendeesForLocation($location);

        // Track present participants for this location only
        $present_volunteers = isset($_POST["{$location}_volunteers"]) ? $_POST["{$location}_volunteers"] : [];
        $present_attendees = isset($_POST["{$location}_attendees"]) ? $_POST["{$location}_attendees"] : [];

        // Process volunteers
        foreach ($volunteers as $volunteer) {
            $participant_id = $volunteer['id'];
            $is_present = in_array($participant_id, $present_volunteers) ? 1 : 0;

            $batch_data[] = [
                'route_id' => $route_id,
                'participant_id' => $participant_id,
                'participant_type' => 'volunteer',
                'attendance_date' => $attendance_date,
                'is_present' => $is_present,
            ];
        }

        // Process attendees
        foreach ($attendees as $attendee) {
            $participant_id = $attendee['attendee_id'];
            $is_present = in_array($participant_id, $present_attendees) ? 1 : 0;

            $batch_data[] = [
                'route_id' => $route_id,
                'participant_id' => $participant_id,
                'participant_type' => 'attendee',
                'attendance_date' => $attendance_date,
                'is_present' => $is_present,
            ];
        }
    }

    // Perform batch insert
    if (!empty($batch_data)) {
        $query = "INSERT INTO dbAttendance (route_id, participant_id, participant_type, attendance_date, is_present) VALUES ";
        $query_values = [];
        $bind_types = '';
        $bind_params = [];

        foreach ($batch_data as $data) {
            $query_values[] = "(?, ?, ?, ?, ?)";
            $bind_types .= 'iissi';
            $bind_params[] = $data['route_id'];
            $bind_params[] = $data['participant_id'];
            $bind_params[] = $data['participant_type'];
            $bind_params[] = $data['attendance_date'];
            $bind_params[] = $data['is_present'];
        }

        $query .= implode(',', $query_values);
        $stmt = $connection->prepare($query);

        $stmt->bind_param($bind_types, ...$bind_params);

        if ($stmt->execute()) {
            error_log("Batch Insert Successful: " . count($batch_data) . " records inserted.");
            header("Location: busMonitorAttendanceForm.php?message=" . urlencode("Attendance successfully recorded!"));
        } else {
            error_log("Batch Insert Error: " . $stmt->error);
            header("Location: busMonitorAttendanceForm.php?error=" . urlencode("Some attendance records failed to save."));
        }

        $stmt->close();
    } else {
        error_log("No data to insert.");
        header("Location: busMonitorAttendanceForm.php?error=" . urlencode("No data to insert."));
    }

    $connection->close();
    exit();
}

if (isset($_GET['message'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['message']) . "</p>";
}
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("universal.inc"); ?>
    <title>Bus Monitor Attendance Form</title>
    <link rel="stylesheet" href="base.css">
    <style>
    .location-section {
        display: none;
        /* Hide sections by default */
    }
    </style>
</head>

<body>
    <h1>Bus Monitor Attendance Form</h1>
    <div id="formatted_form">
        <!-- Edit Button Section -->
        <div id="editButtonSection" style="text-align: center; margin-top: 2rem;">
            <button type="button" onclick="location.href='editBusMonitorData.php'">Edit Bus Monitor Data</button>
        </div>
        <br>

        <!-- Main Attendance Form -->
        <form action="busMonitorAttendanceForm.php" method="post">
            <!-- Route Selection -->
            <h2>1. Which route?</h2>
            <label>
                <input type="radio" name="route" value="north" required onclick="showLocations('north')"> North
            </label><br>
            <label>
                <input type="radio" name="route" value="south" onclick="showLocations('south')"> South
            </label><br><br>

            <!-- North Locations -->
            <div id="northLocations" class="location-section">
                <hr>
                <h3>Foxwood</h3>
                <p>Check all volunteers:</p>
                <?php
            $location = 'Foxwood';
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='Foxwood_volunteers[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }

            echo '<br>';

            echo "<p>Check all attendees:</p>";
            $attendees = getAttendeesForLocation($location);
            if (!empty($attendees)) {
                foreach ($attendees as $attendee) {
                    echo "<label><input type='checkbox' name='Foxwood_attendees[]' value='{$attendee['attendee_id']}'> {$attendee['name']}</label><br>";
                }
            } else {
                echo "<p>No attendees found for $location.</p>";
            }
            ?>
                <hr>
            </div>

            <!-- South Locations -->
            <div id="southLocations" class="location-section">
                <?php
            $southLocations = ['Meadows', 'Jefferson Place', 'Olde Forge', 'England Run'];
            foreach ($southLocations as $location) {
                echo "<h3>$location</h3>";
                echo "<p>Check all volunteers:</p>";
                $volunteers = getVolunteersForLocation($location);
                if (!empty($volunteers)) {
                    foreach ($volunteers as $volunteer) {
                        echo "<label><input type='checkbox' name='{$location}_volunteers[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                    }
                } else {
                    echo "<p>No volunteers found for $location.</p>";
                }

                echo '<br>';

                echo "<p>Check all attendees:</p>";
                $attendees = getAttendeesForLocation($location);
                if (!empty($attendees)) {
                    foreach ($attendees as $attendee) {
                        echo "<label><input type='checkbox' name='{$location}_attendees[]' value='{$attendee['attendee_id']}'> {$attendee['name']}</label><br>";
                    }
                } else {
                    echo "<p>No attendees found for $location.</p>";
                }
                echo '<hr>';
            }
            ?>
            </div>
            <br>

            <!-- Submit Section -->
            <div id="submitSection" style="display: none; text-align: center;">
                <button type="submit" name="submitAll" style="padding: 10px 20px; font-size: 16px;">Submit</button>
            </div>
        </form>

        <!-- Cancel Button -->
        <br>
        <button type="button" style="padding: 10px 20px; font-size: 16px;"
            onclick="location.href='fillFormStaff.php'">Cancel</button>
    </div>

    <script>
    function showLocations(route) {
        // Hide all sections initially
        document.getElementById('northLocations').style.display = 'none';
        document.getElementById('southLocations').style.display = 'none';
        document.getElementById('submitSection').style.display = 'none';
        document.getElementById('editButtonSection').style.display = 'none';

        // Show the selected route's locations
        if (route === 'north') {
            document.getElementById('northLocations').style.display = 'block';
        } else if (route === 'south') {
            document.getElementById('southLocations').style.display = 'block';
        }

        // Show the Submit and Edit Buttons
        document.getElementById('submitSection').style.display = 'block';
        document.getElementById('editButtonSection').style.display = 'block';
    }
    </script>
</body>

</html>