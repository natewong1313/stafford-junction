<?php
session_start();
require_once('database/dbBusMonitorForm.php'); 
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['_id'])) {
    header('Location: login.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeSelected'])) {
    if (!empty($_POST['volunteers_to_remove']) && !empty($_POST['route_id'])) {
        $route_id = intval($_POST['route_id']);
        $volunteers_to_remove = $_POST['volunteers_to_remove']; // Array of volunteer IDs
        $errors = []; // Collect errors for failed deletions

        foreach ($volunteers_to_remove as $volunteer_id) {
            $result = deleteVolunteerFromRoute($route_id, $volunteer_id); // Pass route_id and volunteer_id
            if (!$result['success']) {
                $errors[] = "Error removing volunteer ID {$volunteer_id}: {$result['error']}";
            }
        }

        if (empty($errors)) {
            header("Location: removeVolunteer.php?message=" . urlencode("Selected volunteers have been removed successfully from the route."));
        } else {
            header("Location: removeVolunteer.php?error=" . urlencode(implode(", ", $errors)));
        }
    } else {
        header("Location: removeVolunteer.php?error=" . urlencode("No route or volunteers selected to remove."));
    }
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("universal.inc"); ?>
    <title>Remove Volunteers from Routes</title>
    <link rel="stylesheet" href="base.css">
    <style>
    .location-section {
        display: none;
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <h1>Remove Volunteers from Routes</h1>
    <div id="formatted_form">

        <!-- Display Messages -->
        <?php
    if (isset($_GET['message'])) {
        echo "<p style='color: red; font-weight: bold;'>" . htmlspecialchars($_GET['message']) . "</p>";
    }
    if (isset($_GET['error'])) {
        echo "<p style='color: red; font-weight: bold;'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>

        <!-- Route Selection -->
        <h2>1. Select a Route</h2>
        <label>
            <input type="radio" name="route" value="north" required onclick="showLocations('north')"> North
        </label><br>
        <label>
            <input type="radio" name="route" value="south" onclick="showLocations('south')"> South
        </label><br><br>

        <!-- Form to Remove Volunteers -->
        <form method="post" action="removeVolunteer.php">
            <!-- Hidden field to pass the selected route ID -->
            <input type="hidden" name="route_id" id="route_id_hidden" value="">

            <!-- North Locations -->
            <div id="northLocations" class="location-section" style="display: none;">
                <h3>2. Foxwood</h3>
                <p>Select volunteers to remove:</p>
                <?php
        $location = 'Foxwood';
        $volunteers = getVolunteersForLocation($location);
        if (!empty($volunteers)) {
            foreach ($volunteers as $volunteer) {
                echo "<label><input type='checkbox' name='volunteers_to_remove[]' value='{$volunteer['id']}' data-route-id='6'> {$volunteer['fullName']}</label><br>";
            }
        } else {
            echo "<p>No volunteers found for $location.</p>";
        }
        ?>
            </div>

            <!-- South Locations -->
            <div id="southLocations" class="location-section" style="display: none;">
                <?php
        $southNeighborhoods = [
            'Meadows' => 7,
            'Jefferson Place' => 8,
            'Olde Forge' => 9,
            'England Run' => 10
        ];

        foreach ($southNeighborhoods as $location => $route_id) {
            echo "<h3>$location</h3><p>Select volunteers to remove:</p>";
            $volunteers = getVolunteersForLocation($location);
            if (!empty($volunteers)) {
                foreach ($volunteers as $volunteer) {
                    echo "<label><input type='checkbox' name='volunteers_to_remove[]' value='{$volunteer['id']}' data-route-id='$route_id'> {$volunteer['fullName']}</label><br>";
                }
            } else {
                echo "<p>No volunteers found for $location.</p>";
            }
        }
        ?>
            </div>

            <!-- Submit Section -->
            <div id="submitSection" style="display: none; text-align: center;">
                <button type="submit" name="removeSelected" style="padding: 10px 20px; font-size: 16px;">Remove Selected
                    Volunteers</button>
            </div>
        </form>
        <br>
        <a href="editBusMonitorData.php" style="text-decoration: none;">
            <button style="padding: 10px 20px; font-size: 16px;">Cancel</button>
        </a>
        <script>
        function showLocations(route) {
            // Hide all sections initially
            document.getElementById('northLocations').style.display = 'none';
            document.getElementById('southLocations').style.display = 'none';
            document.getElementById('submitSection').style.display = 'none';

            const routeIdHidden = document.getElementById('route_id_hidden');

            if (route === 'north') {
                // Foxwood (North route ID)
                document.getElementById('northLocations').style.display = 'block';
                routeIdHidden.value = 6; // Route ID for Foxwood
            } else if (route === 'south') {
                // Show South locations
                document.getElementById('southLocations').style.display = 'block';
                routeIdHidden.value = ''; // Reset route ID for South
            }

            // Show the Submit Button
            document.getElementById('submitSection').style.display = 'block';
        }

        // Set route_id dynamically for each checkbox click
        document.querySelectorAll('[name="volunteers_to_remove[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const routeIdHidden = document.getElementById('route_id_hidden');
                const selectedRouteId = this.getAttribute(
                'data-route-id'); // Get route ID from data attribute
                routeIdHidden.value = selectedRouteId; // Set hidden field value
                console.log(`Route ID set to: ${selectedRouteId}`); // Debugging
            });
        });
        </script>
</body>

</html>