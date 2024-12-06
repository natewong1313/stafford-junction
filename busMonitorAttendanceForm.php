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
    require_once('database/dbBusMonitorForm.php'); // Include your form logic
    require_once('include/input-validation.php'); // Include validation functions if necessary
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
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

    <div id="editButtonSection" style="text-align: center; margin-top: 2rem;">
    <button type="button" onclick="location.href='editBusMonitorData.php'">Edit Bus Monitor Data</button>
</div>
        <br>
        <!-- Main Form -->
        <form action="database/dbBusMonitorForm.php" method="post">

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
                <h3>2. Foxwood</h3>
                <p>Check all that apply:</p>
                <?php
            $location = 'Foxwood';
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='foxwood[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }
            ?>
            </div>

            <!-- South Locations -->
            <div id="southLocations" class="location-section">
                <h3>3. Meadows</h3>
                <p>Check all that apply:</p>
                <?php
            $location = 'Meadows';
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='meadows[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }
            ?>

                <h3>4. Jefferson Place</h3>
                <p>Check all that apply:</p>
                <?php
            $location = 'Jefferson Place';
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='jefferson[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }
            ?>

                <h3>5. Olde Forge</h3>
                <p>Check all that apply:</p>
                <?php
            $location = 'Olde Forge';
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='olde_forge[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }
            ?>

                <h3>6. England Run</h3>
                <p>Check all that apply:</p>
                <?php
            $location = 'England Run';
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='england_run[]' value='{$volunteer['id']}'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }
            ?>
            </div>
            <br>
            <!-- Submit Section -->
            <div id="submitSection" style="display: none; text-align: center;">
                <button type="submit" name="submitAll" style="padding: 10px 20px; font-size: 16px;">Submit All
                    Data</button>
            </div>
            <br>

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

<button type="button" style="padding: 10px 20px; font-size: 16px;" onclick="location.href='fillFormStaff.php'">Cancel</button>
</body>
</html>