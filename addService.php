<?php
$duration_years = [
    "0", "1", "2", "3", "4", "5", "6", "7",
    "8", "9", "10", "11", "12", "13", "14", "15", 
    "16", "17", "18", "19", "20"
];

function displaySearchRow($location){
    echo "
    <tr>
        <td><a href='viewService.php?id=".$location['id']."'>" . $location['name'] . "</a></td>
        <td>" . $location['type'] . "</td>
        <td>" . $location['duration_years'] . "</td>
    </tr>";
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
			"name", "type", "duration_years"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $id = create_service($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            require_once('database/dbMessages.php');
			//header("Location: index.php?serviceAdded");
            header("Location: viewService.php?id=$id&serviceAdded");
            die();
        }
    }
    $date = null;

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Animals | Add Service</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Service</h1>
        <main class="date">
            <h2>New Service Form</h2>
            <form id="new-service-form" method="post">
                <label for="name">Service Name *</label>
                <input type="text" id="name" name="name" required placeholder="Enter service's name"> 
                <label for="name">Service Type</label>
                <input type="text" id="type" name="type" required placeholder="Enter service's type"> 
				
				<label for="name">Duration of Service *</label>				
                <select id="duration_years" name="duration_years" required>
                    <option value="">Last how many years</option>
                    <?php for ($i = 0; $i <= end($duration_years); $i++){
                            echo "<option value=$duration_years[$i]>$duration_years[$i]</option>";
                    }
                            ?>
                </select>
                <p></p>
                <input type="submit" value="Create New Service">
            </form>
            <?php if ($date): ?>
                <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
            <?php else: ?>
                <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
            <?php endif ?>
            <?php
                include_once('database/dbServices.php');
                $services = find_allServices();
                require_once('include/output.php');
                if ($services){
                    echo "<h3></h3>";
                    echo "<h3>Current Services</h3>";
                    echo '
                    <div class="table-wrapper">
                        <table class="general">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Duration in Years</th>
                                </tr>
                            </thead>
                            <tbody class="standout">';
                    $mailingList = '';
                    $notFirst = false;
                    foreach ($services as $service) {
                        if ($notFirst) {
                            $mailingList .= ', ';
                        } else {
                            $notFirst = true;
                        }
                        displaySearchRow($service);                                    
                    }
                    echo '
                            </tbody>
                        </table>
                    </div>';
                }
            ?>
        </main>
    </body>
</html>