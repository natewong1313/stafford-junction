<?php
require_once("dbinfo.php");

/**
 * Assign a volunteer to a route and neighborhood.
 *
 * @param string $route The route direction (e.g., 'north' or 'south').
 * @param string $neighborhood The name of the neighborhood.
 * @param int $volunteer_id The ID of the volunteer.
 * @return array Result of the operation with success and message/error keys.
 */
function assignVolunteerToRouteAndNeighborhood($route, $neighborhood, $volunteer_id) {
    if (empty($route) || empty($neighborhood) || empty($volunteer_id)) {
        return ['success' => false, 'error' => 'All fields (route, neighborhood, and volunteer) are required.'];
    }

    $connection = connect();

    // Fetch route_id and route_name
    $query = "SELECT route_id, route_name FROM dbRoute WHERE route_direction = ? AND route_name = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $route, $neighborhood);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $connection->close();
        return ['success' => false, 'error' => 'The selected route and neighborhood combination is invalid.'];
    }

    $row = $result->fetch_assoc();
    $route_id = $row['route_id'];
    $route_name = $row['route_name'];
    $stmt->close();

    // Check for existing assignment
    error_log("Checking for duplicate assignment: Route ID = $route_id, Volunteer ID = $volunteer_id");

    $checkQuery = "SELECT v.fullName FROM dbRouteVolunteers rv 
               JOIN dbVolunteers v ON rv.volunteer_id = v.id
               WHERE rv.route_id = ? AND rv.volunteer_id = ?";
$stmt = $connection->prepare($checkQuery);
$stmt->bind_param("ii", $route_id, $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $volunteer = $result->fetch_assoc();
    $volunteer_name = $volunteer['fullName'];
    $stmt->close();
    $connection->close();
    return [
        'success' => false,
        'error' => "{$volunteer_name} is already assigned to the route {$route_name}."
    ]; // Prevents insertion if duplicate is found
}
$stmt->close();

    // Insert into dbRouteVolunteers
    try {
        $insertQuery = "INSERT INTO dbRouteVolunteers (route_id, volunteer_id) VALUES (?, ?)";
        $stmt = $connection->prepare($insertQuery);
        $stmt->bind_param("ii", $route_id, $volunteer_id);

        if ($stmt->execute()) {
            $stmt->close();
            $connection->close();
            return [
                'success' => true,
                'message' => "{$volunteer_name} was successfully assigned to the route {$route_name}."
            ];
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Duplicate entry error code
            return [
                'success' => false,
                'error' => "{$volunteer_name} is already assigned to the route {$route_name}."
            ];
        } else {
            return [
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ];
        }
    }
}

/**
 * Mark attendance for volunteers on a specific route.
 *
 * @param string $route The route name ('north' or 'south').
 * @param array $attendance Array of volunteer IDs marked as present.
 * @return bool True if successful, false otherwise.
 */
function submitAttendance($route, $attendance) {
    $conn = connect();

    // Get the route_id for the given route
    $query = "SELECT route_id FROM dbRoute WHERE route_direction = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $route);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        $stmt->close();
        $conn->close();
        return false; // Route not found
    }

    $row = $result->fetch_assoc();
    $route_id = $row['route_id'];
    $stmt->close();

    // Update attendance in dbAttendance
    foreach ($attendance as $volunteer_id) {
        $query = "
            INSERT INTO dbAttendance (route_id, volunteer_id, attendance_date, isPresent)
            VALUES (?, ?, CURDATE(), 1)
            ON DUPLICATE KEY UPDATE isPresent = 1
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $route_id, $volunteer_id);

        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return false; // Attendance update failed
        }
    }

    $stmt->close();
    $conn->close();
    return true; // Attendance submitted successfully
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign'])) {
        // Assign a volunteer to a route
        $route = $_POST['route'];
        $volunteer_id = intval($_POST['volunteer_id']);

        if (assignVolunteerToRoute($route, $volunteer_id)) {
            $message = "Volunteer successfully assigned to the route.";
        } else {
            $message = "Failed to assign the volunteer. Please try again.";
        }

        header("Location: ../busMonitorAttendanceForm.php?message=" . urlencode($message));
        exit();
    }

    if (isset($_POST['submitAttendance'])) {
        // Submit attendance for the selected route
        $route = $_POST['route'];
        $attendance = isset($_POST['attendance']) ? $_POST['attendance'] : [];

        if (submitAttendance($route, $attendance)) {
            $message = "Attendance successfully submitted.";
        } else {
            $message = "Failed to submit attendance. Please try again.";
        }

        header("Location: ../busMonitorAttendanceForm.php?message=" . urlencode($message));
        exit();
    }
}

// get volunteers for every location
function getVolunteersForLocation($location) {
    // Establish database connection
    $conn = connect();

    // SQL query to fetch volunteers for the specified location
    $query = "
        SELECT v.id, CONCAT(v.lastName, ', ', v.firstName) AS fullName
        FROM dbVolunteers v
        JOIN dbRouteVolunteers rv ON v.id = rv.volunteer_id
        JOIN dbRoute r ON rv.route_id = r.route_id
        WHERE r.route_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $location); // Bind the location name (e.g., 'Foxwood', 'Meadows')
    $stmt->execute();

    $result = $stmt->get_result();

    // Check for query success
    if (!$result) {
        die("Error in getVolunteersForLocation query: " . $stmt->error);
    }

    // Fetch volunteers into an array
    $volunteers = [];
    while ($row = $result->fetch_assoc()) {
        $volunteers[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $volunteers; // Return the array of volunteers for the location
}

// get volunteers for every route
function getVolunteersForRoute($route) {
    // Connect to the database
    $conn = connect();

    // Query to fetch volunteers for the specified route
    $query = "
        SELECT v.id, CONCAT(v.lastName, ', ', v.firstName) AS fullName
        FROM dbVolunteers v
        JOIN dbRouteVolunteers rv ON v.id = rv.volunteer_id
        JOIN dbRoute r ON rv.route_id = r.route_id
        WHERE r.route_direction = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $route); // Bind the route name (e.g., 'north', 'south')
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if the query was successful
    if (!$result) {
        die("Error in getVolunteersForRoute query: " . $stmt->error);
    }

    // Fetch volunteers into an array
    $volunteers = [];
    while ($row = $result->fetch_assoc()) {
        $volunteers[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $volunteers; // Return the array of volunteers
}

// get volunteers
function getVolunteers() {
    $conn = connect(); 

    // SQL query to fetch all volunteers
    $query = "SELECT id, CONCAT(lastName, ', ', firstName) AS fullName FROM dbVolunteers";

    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn)); // Debugging for query issues
    }

    // Check if there are any rows returned
    if (mysqli_num_rows($result) === 0) {
        return []; // Return an empty array if no data found
    }

    // Populate an array with the results
    $volunteers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $volunteers[] = $row;
    }

    mysqli_close($conn); // Close the database connection
    return $volunteers;
}

// get routes the have volunteers
function getRoutesWithVolunteers() {
    $conn = connect();

    // Query to get routes with at least one volunteer assigned
    $query = "
        SELECT DISTINCT r.route_id, CONCAT(r.route_direction, ' - ', r.route_name) AS route_full
        FROM dbRoute r
        JOIN dbRouteVolunteers rv ON r.route_id = rv.route_id
    ";

    $result = $conn->query($query);

    $routes = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $routes[] = $row;
        }
    }

    $conn->close();
    return $routes; // Array of routes with assigned volunteers
}

// delete a volunteer from a route
function deleteVolunteerFromRoute($route_id, $volunteer_id) {
    $conn = connect(); // Establish the database connection

    // Prepare the SQL statement to delete only the specific volunteer from the specific route
    $query = "DELETE FROM dbRouteVolunteers WHERE route_id = ? AND volunteer_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("SQL Error (prepare): " . $conn->error); // Log preparation errors
        return ['success' => false, 'error' => "SQL Error: " . $conn->error];
    }

    // Bind the route_id and volunteer_id to the query
    $stmt->bind_param("ii", $route_id, $volunteer_id);
    error_log("Executing Query: DELETE FROM dbRouteVolunteers WHERE route_id = $route_id AND volunteer_id = $volunteer_id"); // Debugging log

    // Execute the query
    if ($stmt->execute()) {
        // Check the number of rows affected to confirm deletion
        if ($stmt->affected_rows > 0) {
            error_log("Successfully deleted Volunteer ID: $volunteer_id from Route ID: $route_id.");
            $stmt->close();
            $conn->close();
            return ['success' => true, 'message' => "Volunteer successfully removed from the route."];
        } else {
            error_log("No matching record found for Volunteer ID: $volunteer_id on Route ID: $route_id.");
            $stmt->close();
            $conn->close();
            return ['success' => false, 'error' => "No matching record found to delete."];
        }
    } else {
        error_log("SQL Error (execute): " . $stmt->error); // Log execution errors
        $stmt->close();
        $conn->close();
        return ['success' => false, 'error' => "SQL Error: " . $stmt->error];
    }
}

// get attendees for location
function getAttendeesForLocation($location) {
    $connection = connect();
    $query = "
        SELECT a.attendee_id, a.name
        FROM dbAttendees a
        JOIN dbRoute r ON a.route_id = r.route_id
        WHERE r.route_name = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $location);
    $stmt->execute();

    $result = $stmt->get_result();
    $attendees = [];
    while ($row = $result->fetch_assoc()) {
        $attendees[] = $row;
    }

    $stmt->close();
    $connection->close();

    return $attendees;
}

/**
 * Helper function to get route_id by location name.
 */
function getRouteId($location, $connection) {
    $query = "SELECT route_id FROM dbRoute WHERE route_name = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $location);
    $stmt->execute();
    $result = $stmt->get_result();
    $route_id = null;

    if ($row = $result->fetch_assoc()) {
        $route_id = $row['route_id'];
    }

    $stmt->close();
    return $route_id;
}

?>