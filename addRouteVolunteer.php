<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once('database/dbBusMonitorForm.php');

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRouteVolunteer'])) {
    // Retrieve data from the form
    $route_direction = $_POST['route_direction']; // e.g., "South"
    $neighborhood = $_POST['neighborhood'];       // e.g., "Meadows"
    $volunteer_id = intval($_POST['volunteer_id']); // Volunteer ID (from the dropdown)

    // Connect to the database
    $connection = connect(); 

    // Fetch route_id based on route_direction and neighborhood
    $query = "SELECT route_id FROM dbRoute WHERE route_direction = ? AND route_name = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $route_direction, $neighborhood);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // No matching route found
        $stmt->close();
        $connection->close();
        die("Error: No matching route found for the selected direction and neighborhood.");
    }

    // Get the route_id
    $row = $result->fetch_assoc();
    $route_id = $row['route_id'];
    $stmt->close();

    // Insert into dbRouteVolunteers
    $insertQuery = "INSERT INTO dbRouteVolunteers (route_id, volunteer_id) VALUES (?, ?)";
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param("ii", $route_id, $volunteer_id);

    // Fetch the volunteer's full name from the array or database
    $volunteers = getVolunteers(); // Fetch all volunteers
    $volunteer_name = '';

    // get the actual name of the volunteer
    foreach ($volunteers as $volunteer) {
        if ($volunteer['id'] == $volunteer_id) {
            $volunteer_name = $volunteer['fullName'];
            break;
        }
    }

    if ($stmt->execute()) {
        // Success
        $stmt->close();
        $connection->close();
        header("Location: editBusMonitorData.php?message=" . urlencode("$volunteer_name was successfully assigned to the route $neighborhood!"));
        exit();
    } else {
        // Error
        $error_message = $stmt->error;
        $stmt->close();
        $connection->close();
        die("Error: Failed to assign volunteer. " . $error_message);
    }
}

require_once('database/dbBusMonitorForm.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("universal.inc"); ?>
    <title>Add Route Volunteer</title>
    <link rel="stylesheet" href="base.css">
    <style>
    .location-section {
        display: none;
        /* Hide sections by default */
    }
    </style>
</head>
<body>
    <h1>Add Volunteer to Route</h1>
    <div id="formatted_form">
        <!-- Form to Assign Volunteer to Route -->
        <form action="" method="post">
            <label for="volunteer_id">Select Volunteer:</label>
            <select name="volunteer_id" id="volunteer_id" required>
                <?php
        // Fetch all volunteers from the database
        $volunteers = getVolunteers(); // This function should return a list of volunteers
        foreach ($volunteers as $volunteer) {
            echo "<option value='{$volunteer['id']}'>{$volunteer['fullName']}</option>";
        }
        ?>
            </select>
            <br><br>
            <label for="route_direction">Select Route:</label>
            <select name="route_direction" id="route_direction" required onchange="updateNeighborhoods(this.value)">
                <option value="" disabled selected>Select a Route</option> <!-- Default placeholder -->
                <option value="North">North</option>
                <option value="South">South</option>
            </select>
            <br><br>
            <label for="neighborhood">Select Neighborhood:</label>
            <select name="neighborhood" id="neighborhood" required disabled>
                <option value="" disabled selected>Select a Neighborhood</option> <!-- Default placeholder -->
            </select>
            <script>
            // Update neighborhoods based on the selected route direction
            function updateNeighborhoods(routeDirection) {
                const neighborhoodSelect = document.getElementById('neighborhood');
                neighborhoodSelect.innerHTML = ''; // Clear existing options

                if (routeDirection) { // Enable the neighborhood dropdown only if a route is selected
                    neighborhoodSelect.disabled = false; // Enable the dropdown

                    if (routeDirection === 'North') { // North route
                        neighborhoodSelect.innerHTML = `
                        <option value="Foxwood">Foxwood</option>
                    `;
                    } else if (routeDirection === 'South') { // South route
                        neighborhoodSelect.innerHTML = `
                        <option value="Meadows">Meadows</option>
                        <option value="Jefferson Place">Jefferson Place</option>
                        <option value="Olde Forge">Olde Forge</option>
                        <option value="England Run">England Run</option>
                    `;
                    }
                } else {
                    neighborhoodSelect.disabled = true; // Disable the dropdown if no route is selected
                    neighborhoodSelect.innerHTML = `<option value="" disabled selected>Select a Neighborhood</option>`;
                }
            }
            </script>
            <br><br>
            <button type="submit" name="addRouteVolunteer">Assign Volunteer</button>
        </form>
        <br>
        <a href="editBusMonitorData.php" style="text-decoration: none;">
        <button style="padding: 10px 20px; font-size: 16px;">Cancel</button>
    </a>
</body>
</html>