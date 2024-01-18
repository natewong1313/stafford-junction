<?php
$ages = [
    "0", "1", "2", "3", "4", "5", "6", "7",
    "8", "9", "10", "11", "12", "13", "14", "15", 
    "16", "17", "18", "19", "20"
];

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
        //echo $args;
        $required = array(
			"name", "breed", "age", "gender", "spay_neuter_done", "microchip_done"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $id = update_animal($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            require_once('database/dbMessages.php');
            header("Location: animal.php?id=$id&editSuccess");
            die();
        }
    }
    elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        require_once('include/input-validation.php');
        require_once('database/dbAnimals.php');
        $args = sanitize($_GET, null);
        //echo $args;
        $required = array(
			"id"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $animal = fetch_animal_by_id($args['id']);
//            var_dump($animal);
            if(!$animal){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($animal['name']);
            require_once('database/dbMessages.php');
            //header("Location: animal.php?id=".$animal['id']."&createSuccess");
            //die();
        }
        
    }
    
    
    $date = null;

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Animals | Edit Animal</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Edit Animal</h1>
        <main class="date">
            <h2>Edit Animal Form</h2>
            <form id="new-animal-form" method="post">
                
                <input type="hidden" id="id" name="id" value="<?php if (isset($animal['id'])) echo htmlspecialchars($animal['id']) ?>">
    
                <label for="name">Animal ID *</label>
                <input type="text" id="odhs_id" name="odhs_id" required value="<?php if (isset($animal['odhs_id'])) echo htmlspecialchars($animal['odhs_id']) ?>" placeholder="Enter animal ID">

                <label for="name">Animal Name *</label>
                <input type="text" id="name" name="name" required value="<?php if (isset($animal['name'])) echo htmlspecialchars($animal['name']) ?>" placeholder="Enter animal name">
                <label for="name">Breed *</label>
                <input type="text" id="breed" name="breed" required value="<?php if (isset($animal['breed'])) echo htmlspecialchars($animal['breed']) ?>" placeholder="Enter animal breed">
				
                <label for="name">Age *</label>				
                <select id="age" name="age" required>
                    <option value="">--</option>
                    <?php for ($i = 0; $i <= end($ages); $i++){
                        if($animal["age"] == $ages[$i]){
                            echo "<option selected='selected' value=$ages[$i]>$ages[$i]</option>";
                        } else {
                            echo "<option value=$ages[$i]>$ages[$i]</option>";
                        }
                    }
                    ?>
                </select>

                <label for="gender">Gender *</label>
                <select id="gender" name="gender" required>
                    <option value="">--</option>
                    <?php
                    if($animal["gender"] == "male"){
                        echo "<option selected='selected'value='male'>Male</option>";
                    } else {
                        echo '<option value="male">Male</option>';
                    }
                    if($animal["gender"] == "female"){
                        echo "<option selected='selected'value='female'>Female</option>";
                    } else {
                        echo '<option value="female">Female</option>';
                    }
                    ?>
                </select>

                <label for="name">Notes </label>
                <input type="text" id="notes" name="notes" value="<?php if (isset($animal['notes'])) echo htmlspecialchars($animal['notes']) ?>" placeholder="Enter notes about animal">

                <label for="spay_neuter_done">Spay/Neuter Status *</label>
                <select id="spay_neuter_done" name="spay_neuter_done" required>
                    <option value="">--</option>
                    <?php
                    if($animal["spay_neuter_done"] == "yes"){
                        echo "<option selected='selected'value='yes'>Yes</option>";
                    } else {
                        echo '<option value="yes">Yes</option>';
                    }
                    if($animal["spay_neuter_done"] == "no"){
                        echo "<option selected='selected'value='no'>No</option>";
                    } else {
                        echo '<option value="no">No</option>';
                    }
                    ?>
                </select>
				
                <label for="name">Spay/Neuter Date </label>
                <input type="date" id="spay_neuter_date" name="spay_neuter_date" value="<?php if (isset($animal['spay_neuter_date'])) echo htmlspecialchars($animal['spay_neuter_date']) ?>" placeholder="">
                
                <label for="name">Rabies Given Date </label>
                <input type="date" id="rabies_given_date" name="rabies_given_date" value="<?php if (isset($animal['rabies_given_date'])) echo htmlspecialchars($animal['rabies_given_date']) ?>" placeholder="">

				<label for="name">Rabies Due Date </label>
                <input type="date" id="rabies_due_date" name="rabies_due_date" value="<?php if (isset($animal['rabies_due_date'])) echo htmlspecialchars($animal['rabies_due_date']) ?>" placeholder="">

                <label for="name">Heartworms Given Date </label>
                <input type="date" id="heartworm_given_date" name="heartworm_given_date" value="<?php if (isset($animal['heartworm_given_date'])) echo htmlspecialchars($animal['heartworm_given_date']) ?>" placeholder="">

                <label for="name">Heartworms Due Date </label>
                <input type="date" id="heartworm_due_date" name="heartworm_due_date" value="<?php if (isset($animal['heartworm_due_date'])) echo htmlspecialchars($animal['heartworm_due_date']) ?>" placeholder="">

                <label for="name">Distemper 1 Given Date </label>
                <input type="date" id="distemper1_given_date" name="distemper1_given_date" value="<?php if (isset($animal['distemper1_given_date'])) echo htmlspecialchars($animal['distemper1_given_date']) ?>" placeholder="">

                <label for="name">Distemper 1 Due Date </label>
                <input type="date" id="distemper1_due_date" name="distemper1_due_date" value="<?php if (isset($animal['distemper1_due_date'])) echo htmlspecialchars($animal['distemper1_due_date']) ?>" placeholder="">

                <label for="name">Distemper 2 Given Date </label>
                <input type="date" id="distemper2_given_date" name="distemper2_given_date" value="<?php if (isset($animal['distemper2_given_date'])) echo htmlspecialchars($animal['distemper2_given_date']) ?>" placeholder="">

                <label for="name">Distemper 2 Due Date </label>
                <input type="date" id="distemper2_due_date" name="distemper2_due_date" value="<?php if (isset($animal['distemper2_due_date'])) echo htmlspecialchars($animal['distemper2_due_date']) ?>" placeholder="">

                <label for="name">Distemper 3 Given Date</label>
                <input type="date" id="distemper3_given_date" name="distemper3_given_date" value="<?php if (isset($animal['distemper3_given_date'])) echo htmlspecialchars($animal['distemper3_given_date']) ?>" placeholder="">

                <label for="name">Distemper 3 Due Date </label>
                <input type="date" id="distemper3_due_date" name="distemper3_due_date" value="<?php if (isset($animal['distemper3_due_date'])) echo htmlspecialchars($animal['distemper3_due_date']) ?>" placeholder="">
				
                
                <label for="microchip_done">Microchip Status *</label>
                <select id="microchip_done" name="microchip_done" required>
                    <option value="">--</option>
                    <?php
                    if($animal["microchip_done"] == "yes"){
                        echo "<option selected='selected'value='yes'>Yes</option>";
                    } else {
                        echo '<option value="yes">Yes</option>';
                    }
                    if($animal["microchip_done"] == "no"){
                        echo "<option selected='selected'value='no'>No</option>";
                    } else {
                        echo '<option value="no">No</option>';
                    }
                    ?>
                </select>
                <p></p>
                <input type="submit" value="Update Animal">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="animal.php?id=<?php echo $animal['id']?>" style="margin-top: -.5rem">Cancel</a>
                <?php endif ?>
        </main>
    </body>
</html>