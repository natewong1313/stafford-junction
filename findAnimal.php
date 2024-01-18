<?php
$ages = [
    "0", "1", "2", "3", "4", "5", "6", "7",
    "8", "9", "10", "11", "12", "13", "14", "15", 
    "16", "17", "18", "19", "20"
];

$option_keys = [
    "Rabies Given",
    "Rabies Due", 
    "Heartworm Given",
    "Heartworm Due", 
    "Distemper 1 Given",
    "Distemper 1 Due", 
    "Distemper 2 Given",
    "Distemper 2 Due", 
    "Distemper 3 Given",
    "Distemper 3 Due"
];

$other_options = [
    "Rabies Given" => "rabies_given_date",
    "Rabies Due" => "rabies_due_date",
    "Heartworm Given" => "heartworm_given_date",
    "Heartworm Due" => "heartworm_due_date",
    "Distemper 1 Given" => "distemper1_given_date",
    "Distemper 1 Due" => "distemper1_due_date",
    "Distemper 2 Given" => "distemper2_given_date",
    "Distemper 2 Due" => "distemper2_due_date",
    "Distemper 3 Given" => "distemper3_given_date",
    "Distemper 3 Due" => "distemper3_due_date",
    "rabies_given_date" => "Rabies Given",
    "rabies_due_date" => "Rabies Due",
    "heartworm_given_date" => "Heartworm Given",
    "heartworm_due_date" => "Heartworm Due",
    "distemper1_given_date" => "Distemper 1 Given",
    "distemper1_due_date" => "Distemper 1 Due",
    "distemper2_given_date" => "Distemper 2 Given",
    "distemper2_due_date" => "Distemper 2 Due",
    "distemper3_given_date" => "Distemper 3 Given",
    "distemper3_due_date" => "Distemper 3 Due"
];

function check_needs_attention($animal){
    $today = date("Y-m-d");
    if($today > $animal->get_rabies_due_date() 
            || $today > $animal->get_heartworm_due_date() 
            || $today > $animal->get_distemper1_due_date() 
            || $today > $animal->get_distemper2_due_date() 
            || $today > $animal->get_distemper3_due_date()){
        return 'yes';
    } else {
        return 'no';
    }
}

function displaySearchRow($animal, $other){
    echo "
    <tr>
        <td><a href='animal.php?id=".$animal->get_id()."'>" . $animal->get_name() . "</a></td>
        <td>" . $animal->get_breed() . "</td>
        <td>" . $animal->get_age() . "</td>
        <td>" . $animal->get_gender() . "</td>
        <td>" . $animal->get_spay_neuter_done() . "</td>
        <td>" . $animal->get_microchip_done() . "</td>
        <td>" . check_needs_attention($animal) . "</td>";
        if($other){
            $emptyCheck = $animal->get_other($other);
            if($emptyCheck == "0000-00-00"){
                echo "<td></td>";
            } else {
                echo "<td>" . $emptyCheck . "</td>";
            }
        }
    echo "</tr>";
}

    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    // admin-only access
    if ($accessLevel < 2) {
        header('Location: index.php');
        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Animal Search</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Animal Search</h1>
        <form id="animal-search" class="general" method="get">
            <?php 
                if (isset($_GET['name'])) {
                    require_once('include/input-validation.php');
                    require_once('database/dbAnimals.php');
                    $args = sanitize($_GET);
                    $required = ['name', 'breed', 'age1', 'age2', 'gender', 'spay_neuter_done', 'microchip_done', 'needs_attention', 'other'];
                    if (!wereRequiredFieldsSubmitted($args, $required, true)) {
                        echo 'Missing expected form elements';
                    }
                    $name = $args['name'];
                    $breed = $args['breed'];
                    $age1 = $args['age1'];
                    $age2 = $args['age2'];
					$gender = $args['gender'];
                    $spay_neuter_done = $args['spay_neuter_done'];
                    $microchip_done = $args['microchip_done'];
                    $needs_attention = $args['needs_attention'];
                    $other = $args['other'];
                    if (!($name || $breed || $age1 || $age2 || $gender || $spay_neuter_done || $microchip_done || $needs_attention || $other)){
                        echo '<div class="error-toast">At least one search criterion is required.</div>';
                    } else {
                        echo "<h3>Search Results</h3>";
                        $animals = find_animal($name, $breed, $age1, $age2, $gender, $spay_neuter_done, $microchip_done, $needs_attention, $other);
                        require_once('include/output.php');
                        if (count($animals) > 0) {
                            echo '
                            <div class="table-wrapper">
                                <table class="general">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Breed</th>
                                            <th>Age</th>
                                            <th>Gender</th>
											<th>Spay/Neuter</th>
                                            <th>Microchipped</th>
                                            <th>Needs Attention</th>';
                                            if($other){
                                                echo '<th>'.$other_options[$other].'</th>';
                                            }
                                        echo '</tr>
                                    </thead>
                                    <tbody class="standout">';
                            $mailingList = '';
                            $notFirst = false;
                            foreach ($animals as $animal) {
                                if ($notFirst) {
                                    $mailingList .= ', ';
                                } else {
                                    $notFirst = true;
                                }
                                if($needs_attention == "yes"){
                                    $needsAtt = check_needs_attention($animal);
                                    if($needsAtt == "yes"){
                                        displaySearchRow($animal, $other);                                    
                                    }
                                } elseif($needs_attention == "no"){
                                    $needsAtt = check_needs_attention($animal);
                                    if($needsAtt == "no"){
                                        displaySearchRow($animal, $other);                                    
                                    }
                                } else {
                                    displaySearchRow($animal, $other);                                    
                                }
                            }
                            echo '
                                    </tbody>
                                </table>
                            </div>';
                        } else {
                            echo '<div class="error-toast">Your search returned no results.</div>';
                        }
                    }
                    echo '<h3>Search Again</h3>';
                }
            ?>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php if (isset($name)) echo htmlspecialchars($_GET['name']) ?>" placeholder="Enter the name to search">
            <label for="breed">Breed</label>
            <input type="text" id="breed" name="breed" value="<?php if (isset($breed)) echo htmlspecialchars($_GET['breed']) ?>" placeholder="Enter the breed go search">
            
            <label for="age">Age</label>
            <table width=200>
                <tr>
                    <td>
                        From:
                        <select id="age1" name="age1">
                            <?php for ($i = 0; $i <= end($ages); $i++){
                                if($i == $age1){
                                    echo "<option selected='selected' value='$ages[$i]'>$ages[$i]</option>";
                                }
                                else {
                                    echo "<option value=$ages[$i]>$ages[$i]</option>";
                                }
                            }
                            ?>
                        </select>
                    </td><td>
                        To:
                        <select id="age2" name="age2">
                            <?php for ($i = 0; $i <= end($ages); $i++){
                                if($i == $age2){
                                    echo "<option selected='selected' value='$ages[$i]'>$ages[$i]</option>";
                                }
                                else {
                                    echo "<option value=$ages[$i]>$ages[$i]</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table> 

            <label for="gender">Gender</label>
			<select id="gender" name="gender">
                <option value="">Any</option>
                <option value="male" <?php if (isset($gender) && $gender == 'male') echo 'selected' ?>>Male</option>
                <option value="female" <?php if (isset($gender) && $gender == 'female') echo 'selected' ?>>Female</option>
            </select>

            <label for="spay_neuter_done">Spay / Neuter</label>
            <select id="spay_neuter_done" name="spay_neuter_done">
                <option value="">Any</option>
                <option value="yes" <?php if (isset($spay_neuter_done) && $spay_neuter_done == 'yes') echo 'selected' ?>>Yes</option>
                <option value="no" <?php if (isset($spay_neuter_done) && $spay_neuter_done == 'no') echo 'selected' ?>>No</option>
            </select>

            <label for="microchip_done">Microchipped</label>
            <select id="microchip_done" name="microchip_done">
            <option value="">Any</option>
                <option value="yes" <?php if (isset($microchip_done) && $microchip_done == 'yes') echo 'selected' ?>>Yes</option>
                <option value="no" <?php if (isset($microchip_done) && $microchip_done == 'no') echo 'selected' ?>>No</option>
            </select>

            <label for="needs_attention">Needs Attention</label>
            <select id="needs_attention" name="needs_attention">
            <option value="">Any</option>
                <option value="yes" <?php if (isset($needs_attention) && $needs_attention == 'yes') echo 'selected' ?>>Yes</option>
                <option value="no" <?php if (isset($needs_attention) && $needs_attention == 'no') echo 'selected' ?>>No</option>
            </select>

            <label for="other">Other</label>
            <select id="other" name="other">
                <option selected='selected' value="">--</option>
                <?php for ($i = 0; $i < 10; $i++){
                    $option = $option_keys[$i];
                    if($other_options[$option] == $other){
                        echo "<option selected='selected' value='$other_options[$option]'>$option</option>";
                    }
                    else {
                        echo "<option value='$other_options[$option]'>$option</option>";
                    }
                }
            ?>
            </select>

            <div id="criteria-error" class="error hidden">You must provide at least one search criterion.</div>
            <p></p>
            <input type="submit" value="Search">
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </form>
    </body>
</html>