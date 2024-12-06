<?php
require_once("dbinfo.php");

/**
 * Assign a volunteer to a route.
 *
 * @param string $route The route name ('north' or 'south').
 * @param int $volunteer_id The ID of the volunteer.
 * @return bool True if successful, false otherwise.
 */
function assignVolunteerToRoute($route, $volunteer_id) {
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

    // Insert into dbRouteVolunteers
    $query = "INSERT INTO dbRouteVolunteers (route_id, volunteer_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $route_id, $volunteer_id);

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
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



function getVolunteers() {
    $conn = connect(); // Ensure this function connects to your database correctly

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
?>
