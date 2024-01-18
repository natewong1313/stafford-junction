<?php

    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    } 
    // Require admin privileges
    if ($accessLevel < 2) {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbAppointments.php');
        $args = sanitize($_POST, null);
        $required = array(
            "name", "abbrev-name", "date", "start-time", "description", "location"
        );
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $validated = validate12hTimeAndConvertTo24h($args["start-time"]);
            if (!$validated) {
                echo 'bad time range';
                die();
            }
            $startTime = $args['start-time'] = $validated[0];
            $date = $args['date'] = validateDate($args["date"]);
            $abbrevLength = strlen($args['abbrev-name']);
            if (!$startTime || !$date || $abbrevLength > 11){
                echo 'bad args';
                die();
            }
            $id = create_event($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            $startTime = time24hto12h($startTime);
            $date = date('l, F j, Y', strtotime($date));
            require_once('database/dbMessages.php');
            system_message_all_users_except($userID, "A new event was created!", "Exciting news!\r\n\r\nThe [$name](event: $id) event at $startTime on $date was added!\r\nSign up today!");
            header("Location: event.php?id=$id&createSuccess");
            die();
        }
    }
    $date = null;
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
        $timeStamp = strtotime($date);
        if (!preg_match($datePattern, $date) || !$timeStamp) {
            header('Location: calendar.php');
            die();
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Create Appointment</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Create Appointment</h1>
        <main class="date">
            <h2>New Appointment Form</h2>
            <form id="new-event-form" method="post">
                <label for="name">Appointment Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name"> 
                <label for="name">Abbreviated Name</label>
                <input type="text" id="abbrev-name" name="abbrev-name" maxlength="11" required placeholder="Enter name that will appear on calendar">
                <label for="name">Date </label>
                <input type="date" id="date" name="date" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name">Start Time </label>
                <input type="text" id="start-time" name="start-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter start time. Ex. 12:00 PM">
                <label for="name">Description </label>
                <input type="text" id="description" name="description" required placeholder="Enter description">
                <label for="name">Location </label>
                <input type="text" id="location" name="location" required placeholder="Enter location">
                <label for="name">Animal ID </label>
                <!-- TODO: change this to be dropdown list of current animals -->
                <input type="text" id="animal" name="animal" required placeholder="Enter animal ID">
                <input type="submit" value="Create Event">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>
        </main>
    </body>
</html>