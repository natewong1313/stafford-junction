<?php 

    session_cache_expire(30);
    session_start();

    // Ensure user is logged in
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }
    require_once('include/input-validation.php');
    $args = sanitize($_GET);
    if (isset($args["id"])) {
        $id = $args["id"];
    } else {
        header('Location: findAnimal.php');
        die();
  	}

  	include_once('database/dbAnimals.php');

    // need to get all events for the animal
    $animal_events = find_animal_appointments($id);

    // We need to check for a bad ID here before we query the db
    // otherwise we may be vulnerable to SQL injection(!)
  	$animal_info = fetch_animal_by_id($id);
    if ($animal_info == NULL) {
        // TODO: Need to create error page for no event found
        // header('Location: calendar.php');

        // Lauren: changing this to a more specific error message for testing
        echo 'bad animal ID';
        die();
    }

    include_once('database/dbPersons.php');
    $access_level = $_SESSION['access_level'];
    $user = retrieve_person($_SESSION['_id']);
    $active = $user->get_status() == 'Active';

    ini_set("display_errors",1);
    error_reporting(E_ALL);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $args = sanitize($_POST);
        $get = sanitize($_GET);
        if (isset($_POST['attach-post-media-submit'])) {
            // TODO: remove all this
            if ($access_level < 2) {
                echo 'forbidden';
                die();
            }
            $required = [
                'url', 'description', 'format', 'id'
            ];
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                echo "dude, args missing";
                die();
            }
            $type = 'post';
            $format = $args['format'];
            $url = $args['url'];
            if ($format == 'video') {
                $url = convertYouTubeURLToEmbedLink($url);
                if (!$url) {
                    echo "bad video link";
                    die();
                }
            } else if (!validateURL($url)) {
                echo "bad url";
                die();
            }
            $eid = $args['id'];
            $description = $args['description'];
            if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
                echo "dude, bad format";
                die();
            }
            attach_post_event_media($eid, $url, $format, $description);
            header('Location: event.php?id=' . $id . '&attachSuccess');
            die();
        }
        if (isset($_POST['attach-training-media-submit'])) {
            // TODO: remove all this
            if ($access_level < 2) {
                echo 'forbidden';
                die();
            }
            $required = [
                'url', 'description', 'format', 'id'
            ];
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                echo "dude, args missing";
                die();
            }
            $type = 'post';
            $format = $args['format'];
            $url = $args['url'];
            if ($format == 'video') {
                $url = convertYouTubeURLToEmbedLink($url);
                if (!$url) {
                    echo "bad video link";
                    die();
                }
            } else if (!validateURL($url)) {
                echo "bad url";
                die();
            }
            $eid = $args['id'];
            $description = $args['description'];
            if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
                echo "dude, bad format";
                die();
            }
            attach_event_training_media($eid, $url, $format, $description);
            header('Location: event.php?id=' . $id . '&attachSuccess');
            die();
        }
    } else {
        if (isset($args["request_type"])) {
            //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_type = $args['request_type'];
            if (!valueConstrainedTo($request_type, 
                    array('add self', 'add another', 'remove'))) {
                echo "Bad request";
                die();
            }
            $eventID = $args["id"];
    
            // Check if Get request from user is from an organization member
            // (volunteer, admin/super admin)
            if ($request_type == 'add self' && $access_level >= 1) {
                if (!$active) {
                    echo 'forbidden';
                    die();
                }
                $volunteerID = $args['selected_id'];
                $person = retrieve_person($volunteerID);
                $name = $person->get_first_name() . ' ' . $person->get_last_name();
                $name = htmlspecialchars_decode($name);
                require_once('database/dbMessages.php');
                require_once('include/output.php');
                $event = fetch_event_by_id($eventID);
                
                $eventName = htmlspecialchars_decode($event['name']);
                $eventDate = date('l, F j, Y', strtotime($event['date']));
                $eventStart = time24hto12h($event['startTime']);
                $eventEnd = time24hto12h($event['endTime']);
                system_message_all_admins("$name signed up for an event!", "Exciting news!\r\n\r\n$name signed up for the [$eventName](event: $eventID) event from $eventStart to $eventEnd on $eventDate.");
                // Check if GET request from user is from an admin/super admin
            // (Only admins and super admins can add another user)
            } else if ($request_type == 'add another' && $access_level > 1) {
                $volunteerID = strtolower($args['selected_id']);
                if ($volunteerID == 'vmsroot') {
                    echo 'invalid user id';
                    die();
                }
                require_once('database/dbMessages.php');
                require_once('include/output.php');
                $event = fetch_event_by_id($eventID);
                $eventName = htmlspecialchars_decode($event['name']);
                $eventDate = date('l, F j, Y', strtotime($event['date']));
                $eventStart = time24hto12h($event['startTime']);
                $eventEnd = time24hto12h($event['endTime']);
                send_system_message($volunteerID, 'You were assigned to an event!', "Hello,\r\n\r\nYou were assigned to the [$eventName](event: $eventID) event from $eventStart to $eventEnd on $eventDate.");
            } else {
                header('Location: event.php?id='.$eventID);
                die();
            }
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <?php
        require_once('universal.inc');
    ?>
    <title>ODHS Medicine Tracker | View Animal: <?php echo $animal_info['name'] ?></title>
    <link rel="stylesheet" href="css/event.css" type="text/css" />
    <?php if ($access_level >= 2) : ?>
        <script src="js/event.js"></script>
    <?php endif ?>
</head>

<body>
    <?php if ($access_level >= 2) : ?>
        <div id="delete-confirmation-wrapper" class="hidden">
            <div id="delete-confirmation">
                <p>Are you sure you want to permanently delete this animal?</p>
                <p>This action cannot be undone.</p>

                <form method="post" action="deleteEvent.php">
                    <input type="submit" value="Delete Animal">
                    <input type="hidden" name="id" value="<?= $id ?>">
                </form>
                <button id="delete-cancel">Cancel</button>
            </div>
        </div>
    <?php endif ?>
    <?php require_once('header.php') ?>
    <h1>View Animal</h1>
    <main class="event-info">
        <?php if (isset($_GET['createSuccess'])): ?>
            <div class="happy-toast">Animal created successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['attachSuccess'])): ?>
            <div class="happy-toast">Media attached successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['removeSuccess'])): ?>
            <div class="happy-toast">Media removed successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['editSuccess'])): ?>
            <div class="happy-toast">Animal details updated successfully!</div>
        <?php endif ?>
        <?php    
            require_once('include/output.php');
            $animal_name = $animal_info['name'];
            $animal_breed = $animal_info['breed'];
            $animal_age = $animal_info['age'];
            $animal_id = $animal_info['odhs_id'];
            $animal_gender = $animal_info['gender'];
            $animal_spay_neuter = $animal_info['spay_neuter_done'];
            $animal_microchip = $animal_info['microchip_done'];
            $animal_rabies = (($animal_info['rabies_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['rabies_given_date'])) : "");            
            $animal_heartworm = (($animal_info['heartworm_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['heartworm_given_date'])) : "");            
            $animal_distemper1 = (($animal_info['distemper1_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper1_given_date'])) : "");
            $animal_distemper2 = (($animal_info['distemper2_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper2_given_date'])) : "");
            $animal_distemper3 = (($animal_info['distemper3_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper3_given_date'])) : "");
            $animal_notes = $animal_info['notes'];
            require_once('include/time.php');
            echo '<h2 class="centered">'.$animal_name.'</h2>';
        ?>
        <div id="table-wrapper">
            <table class="centered">
                <tbody>
                    <th class="label">General</th>
                    <tr>	
                        <td class="label">ID </td>
                        <td><?php echo $animal_id ?></td>
                    </tr>
                    <tr>	
                        <td class="label">Breed </td>
                        <td><?php echo $animal_breed ?></td>     		
                    </tr>
                    <tr>	
                        <td class="label">Age </td>
                        <td><?php echo $animal_age?></td>
                    </tr>
                    <tr>	
                        <td class="label">Gender </td>
                        <td><?php echo $animal_gender?></td>     		
                    </tr>
                    <tr>	
                        <td class="label">Notes </td>
                        <td><?php echo $animal_notes?></td>
                    </tr>
                    <th>Medical</th>
                    <tr>	
                        <td class="label">Spayed/Neutered </td>
                        <td><?php echo $animal_spay_neuter?></td>     		
                    </tr>
                    <tr>	
                        <td class="label">Microchipped </td>
                        <td><?php echo $animal_microchip?></td>
                    </tr>
                    <tr>	
                        <td class="label">Rabies given </td>
                        <td><?php echo $animal_rabies?></td>
                    </tr>
                    <tr>	
                        <td class="label">Heartworm test given</td>
                        <td><?php echo $animal_heartworm?></td>
                    </tr>
                    <tr>	
                        <td class="label">Distemper 1 given </td>
                        <td><?php echo $animal_distemper1?></td>
                    </tr>
                    <tr>	
                        <td class="label">Distemper 2 given </td>
                        <td><?php echo $animal_distemper2?></td>
                    </tr>
                    <tr>	
                        <td class="label">Distemper 3 given </td>
                        <td><?php echo $animal_distemper3?></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

        <!-- Buttons for viewing each future appointment for the animal -->
        <h2 class="centered">Upcoming Appointments</h2>
        <div id="dashboard">
            <?php
                $no_future_appointments = true;
                foreach ($animal_events as $event) {
                    $event_date = date('F j, Y', strtotime($event['date']));
                    $current_date = date('Y-m-d');
                    if ($event['date'] >= $current_date) {
                        echo '<div class="dashboard-item" data-link="event.php?id=' . $event['id'] . '">';
                        echo '<img src="images/view-calendar.svg">';
                        echo '<span>' . $event['name'] . '</span>';
                        echo '<span>' . $event_date . '</span>';
                        echo '</div>';
                        $no_future_appointments = false;
                    }
                }
                if ($no_future_appointments) {
                    echo '<div id="table-wrapper" class="centered"><p>There are no appointments scheduled.</p></div>';
                }
            ?>
        </div>

        <?php if ($access_level >= 2) : ?>
            <!-- <form method="post" action="deleteEvent.php">
                <input type="submit" value="Delete Event">
                <input type="hidden" name="id" value="<?= $id ?>">
            </form> -->
            
            <form method="get" action="editAnimal.php">
                <input type="submit" value="Edit Animal">
                <input type="hidden" name="id" value="<?= $id ?>">
            </form>
            <button onclick="showDeleteConfirmation()">Delete Animal</button>
        <?php endif ?>

        <a href="findAnimal.php" class="button cancel" style="margin-top: -.5rem">Return to Animal Search</a>
    </main>
</body>

</html>
