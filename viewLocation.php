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
        var_dump($args);
#        header('Location: findAnimal.php');
        die();
  	}

  	include_once('database/dbServices.php');

    // need to get all events for the animal
    $location = find_location($id);

    // We need to check for a bad ID here before we query the db
    // otherwise we may be vulnerable to SQL injection(!)
  	// $animal_info = fetch_animal_by_id($id);
    // if ($animal_info == NULL) {
    //     // TODO: Need to create error page for no event found
    //     // header('Location: calendar.php');

    //     // Lauren: changing this to a more specific error message for testing
    //     echo 'bad animal ID';
    //     die();
    // }

    include_once('database/dbPersons.php');
    $access_level = $_SESSION['access_level'];
    $user = retrieve_person($_SESSION['_id']);
    $active = $user->get_status() == 'Active';

    ini_set("display_errors",1);
    error_reporting(E_ALL);
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        require_once('include/input-validation.php');
        require_once('database/dbServices.php');
        $args = sanitize($_GET, null);
#        var_dump($args);
        $required = array(
			"id"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $location = find_location($args['id']);
#            echo "AAAAAAAAAAAAAAAH";
#            echo $service['name'];
#            var_dump($service);
            if(!$location){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            $name = htmlspecialchars_decode($location["name"]);
            #require_once('database/dbMessages.php');
            #header("Location: animal.php?id=$id&createSuccess");
           # die();
        }
    } else {

    }
?>

<!DOCTYPE html>
<html>

<head>
    <?php
        require_once('universal.inc');
    ?>
    <title>ODHS Medicine Tracker | View Location: <?php echo $location['name'] ?></title>
    <link rel="stylesheet" href="css/event.css" type="text/css" />
    <?php if ($access_level >= 2) : ?>
        <script src="js/event.js"></script>
    <?php endif ?>
</head>

<body>
    <?php if ($access_level >= 2) : ?>
        <div id="delete-confirmation-wrapper" class="hidden">
            <div id="delete-confirmation">
                <p>Are you sure you want to permanently delete this location?</p>
                <p>This action cannot be undone.</p>

                <form method="post" action="deleteLocation.php">
                    <input type="submit" value="Delete Location">
                    <input type="hidden" name="id" value="<?= $id ?>">
                </form>
                <button id="delete-cancel">Cancel</button>
            </div>
        </div>
    <?php endif ?>
    <?php require_once('header.php') ?>
    <h1>View Location</h1>
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
        <?php if (isset($_GET['locationAdded'])): ?>
            <div class="happy-toast">Location successfully added!</div>
        <?php endif ?>
        <?php    
            require_once('include/output.php');
#            $service = $animal_info['name'];
#            $animal_breed = $animal_info['breed'];
#            $animal_age = $animal_info['age'];
            require_once('include/time.php');
#            echo '<h2 class="centered">'.$animal_name.'</h2>';
        ?>
        <div id="table-wrapper">
            <table class="centered">
                <tbody>
                    <th class="label" style="min-width:170px">Location Info</th>
                    <tr>	
                        <td class="label">Name </td>
                        <td><?php echo $location['name'] ?></td>
                    </tr>
                    <tr>	
                        <td class="label">Address </td>
                        <td><?php echo $location['address'] ?></td>
                    </tr>
                    <tr>	
                        <td class="label">Services Available: </td>
                        <td>
                            <?php 
                                $services = find_services_for_location($location['id']);
                                $length = count($services);
                                for ($i = 0; $i < $length; $i++) { 
                                    echo $services[$i]['name'];
                                    if ($i < $length - 1) {
                                        echo ', ';
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>	
                        <td class="label"> </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if ($access_level >= 2) : ?>
            <!-- <form method="post" action="deleteEvent.php">
                <input type="submit" value="Delete Event">
                <input type="hidden" name="id" value="<?= $id ?>">
            </form> -->
            
            <form method="get" action="addLocation.php">
                <input type="submit" value="Add New Location">
            </form>
            <button onclick="showDeleteConfirmation()">Delete Location</button>
        <?php endif ?>

        <a href="index.php" class="button cancel" style="margin-top: -.5rem">Return to Dashboard</a>
    </main>
</body>

</html>
