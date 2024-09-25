<!-- hello world -->
<?php
$ages = [
    "0", "1", "2", "3", "4", "5", "6", "7",
    "8", "9", "10", "11", "12", "13", "14", "15", 
    "16", "17", "18", "19", "20"
];
    // A comment for the github assignment

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
        require_once('database/dbAnimals.php');
        $args = sanitize($_POST, null);
        $required = array(
			"name", "breed", "age", "gender", "spay_neuter_done", "microchip_done"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $id = create_animal($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            require_once('database/dbMessages.php');
            header("Location: animal.php?id=$id&createSuccess");
            die();
        }
    }
    $date = null;

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Animals | Add Animal</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Animal</h1>
        <main class="date">
            <h2>New Animal Form</h2>
            <form id="new-animal-form" method="post">
                
                <label for="name">Animal ID *</label>
                <input type="text" id="odhsid" name="odhsid" required placeholder="Enter animal's ID">
                <label for="name">Animal Name *</label>
                <input type="text" id="name" name="name" required placeholder="Enter animal's name"> 
                <label for="name">Breed *</label>
                <input type="text" id="breed" name="breed" required placeholder="Enter animal's breed"> 
				
                
                <label for="name">Age *</label>				
                <select id="age" name="age" required>
                    <option value="">--</option>
                    <?php for ($i = 0; $i <= end($ages); $i++){
                            echo "<option value=$ages[$i]>$ages[$i]</option>";
                    }
                            ?>
                </select>

                <label for="gender">Gender *</label>
                <select id="gender" name="gender" required>
                    <option value="">--</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>

                <label for="name">Notes </label>
                <input type="text" id="notes" name="notes" placeholder="Enter notes about animal">
                
                <label for="spay_neuter_done">Spay/Neuter Status *</label>
                <select id="spay_neuter_done" name="spay_neuter_done" required>
                    <option value="">--</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
				
                <label for="name">Spay/Neuter Date </label>
                <input type="date" id="spay_neuter_date" name="spay_neuter_date" placeholder=NULL>
                
                <label for="name">Rabies Given Date </label>
                <input type="date" id="rabies_given_date" name="rabies_given_date" placeholder=NULL>

				<label for="name">Rabies Due Date </label>
                <input type="date" id="rabies_due_date" name="rabies_due_date" placeholder=NULL>
                <label for="name">Heartworms Given Date </label>
                <input type="date" id="heartworm_given_date" name="heartworm_given_date" placeholder=NULL>
				<label for="name">Heartworms Due Date </label>
                <input type="date" id="heartworm_due_date" name="heartworm_due_date" placeholder=NULL>	
                <label for="name">Distemper 1 Given Date </label>
                <input type="date" id="distemper1_given_date" name="distemper1_given_date" placeholder=NULL>
				<label for="name">Distemper 1 Due Date </label>
                <input type="date" id="distemper1_due_date" name="distemper1_due_date" placeholder=NULL>
                <label for="name">Distemper 2 Given Date </label>
                <input type="date" id="distemper2_given_date" name="distemper2_given_date" placeholder=NULL>
				<label for="name">Distemper 2 Due Date </label>
                <input type="date" id="distemper2_due_date" name="distemper2_due_date" placeholder=NULL>				
				<label for="name">Distemper 3 Given Date</label>
                <input type="date" id="distemper3_given_date" name="distemper3_given_date" placeholder=NULL>
				<label for="name">Distemper 3 Due Date </label>
                <input type="date" id="distemper3_due_date" name="distemper3_due_date" placeholder=NULL>	
				
                
                <label for="microchip_done">Microchip Status *</label>
                <select id="microchip_done" name="microchip_done" required>
                    <option value="">--</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <p></p>
                <input type="submit" value="Create New Animal">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>
        </main>
    </body>
</html>
