<?php
$duration_years = [
    "0", "1", "2", "3", "4", "5", "6", "7",
    "8", "9", "10", "11", "12", "13", "14", "15", 
    "16", "17", "18", "19", "20"
];

function displaySearchRow($location){
    $services = find_services_for_location($location['id']);
    echo "
    <tr>
        <td><a href='viewLocation.php?id=".$location['id']."'>" . $location['name'] . "</a></td>
        <td>" . $location['address'] . "</td>";
    echo "<td>";
    $length = count($services);
    for ($i = 0; $i < $length; $i++) { 
        echo $services[$i]['name'];
        if ($i < $length - 1) {
            echo ', ';
        }
    }
    echo "</td></tr>";
}

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
        require_once('database/dbServices.php');
        $args = sanitize($_POST, null);
        $required = array(
			"name", "address"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $id = create_location($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            require_once('database/dbMessages.php');
			//header("Location: index.php?locationAdded");
            header("Location: viewLocation.php?id=$id&locationAdded");
            die();
        }
    }
    $date = null;

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Animals | Add Location</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Location</h1>
        <main class="date">
            <h2>New Location Form</h2>
            <form id="new-service-form" method="post">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" required placeholder="Enter location's name"> 
                <label for="name">Address *</label>
                <input type="text" id="address" name="address" required placeholder="Enter location's address"> 
				
                <fieldset>
                    <label for="name">Services Available *</label>
                    <?php 
                        // fetch data from the $all_services variable
                        // and individually display as an option
                        echo '<ul>';
                        include_once('database/dbServices.php');
                        $all_services = find_allServices();
                        if(!$all_services) {
                            echo "No Services Added. Added Services will appear here.";
                        } else {
                            foreach($all_services as $service) {
                                echo '<li><input class="checkboxes" type="checkbox" name="services_available[]" value="' . $service['id'] . '" required/> ' . $service['name'] . '</li>';
                            }
                        }
                        echo '</ul>';
                    ?>
                </fieldset>
                <p></p>
                <input type="submit" value="Add New Location">
            </form>
            <?php if ($date): ?>
                <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
            <?php else: ?>
                <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
            <?php endif ?>
            <?php
                $locations = find_allLocations();
                require_once('include/output.php');
                if ($locations){
                    echo "<h3></h3>";
                    echo "<h3>Current Locations</h3>";
                    echo '
                    <div class="table-wrapper">
                        <table class="general">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Services</th>
                                </tr>
                            </thead>
                            <tbody class="standout">';
                    $mailingList = '';
                    $notFirst = false;
                    foreach ($locations as $location) {
                        if ($notFirst) {
                            $mailingList .= ', ';
                        } else {
                            $notFirst = true;
                        }
                        displaySearchRow($location);                                    
                    }
                    echo '
                            </tbody>
                        </table>
                    </div>';
                }
            ?>

            <!-- Require at least one checkbox be checked -->
            <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });
            </script>
        </main>
    </body>
</html>