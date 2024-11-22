<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if(!isset($_SESSION['_id'])){
    header('Location: login.php');
    die();
}

$loggedIn = true;
$accessLevel = $_SESSION['access_level'];
$userID = $_SESSION['_id'];

require_once('database/dbBrainBuildersRegistrationForm.php');
require_once('include/input-validation.php');
require_once('domain/Family.php');
require_once('database/dbFamily.php');

//get required family and child information
$family = retrieve_family_by_id($_GET['id'] ?? $userID); //$_GET['id] will have the family id needed to fill form if the staff are trying to fill a form out for that family
$family_children = getChildren($family->getId());

$family_name1 = $family->getFirstName() . " " . $family->getLastName();
$family_phone1 = $family->getPhone();
$family_address1 = $family->getAddress();
$family_city1 = $family->getCity();
$family_state1 = $family->getState();
$family_zip1 = $family->getZip();
$family_email1 = $family->getEmail();
$family_altphone1 = $family->getSecondaryPhone();

$family_name2 = $family->getFirstName2() . " " . $family->getLastName2();
$family_phone2 = $family->getPhone2();
$family_address2 = $family->getAddress2();
$family_city2 = $family->getCity2();
$family_state2 = $family->getState2();
$family_zip2 = $family->getZip2();
$family_email2 = $family->getEmail2();
$family_altphone2 = $family->getSecondaryPhone2();

$family_emergency_name1 = $family->getEContactFirstName() . " " . $family->getEContactLastName();
$family_emergency_relation1 = $family->getEContactRelation();
$family_emergency_phone1 = $family->getEContactPhone();

$family_isHispanic1 = $family->isHispanic();
$family_race1 = $family->getRace();

$family_income = $family->getIncome();

try {
    $connection = connect();

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        require_once('include/input-validation.php');
        
        $parent1_phone = validateAndFilterPhoneNumber($_POST['parent1_phone']);
        if (!$parent1_phone) {
            $errors[] = "Invalid phone number format: Parent 1 Phone Number";
        }

        $parent1_altPhone = validateAndFilterPhoneNumber($_POST['parent1_altPhone']);
        if (!$parent1_altPhone) {
            $errors[] = "Invalid phone number format: Parent 1 Alternate Phone Number.";
        }

        $parent1_email = validateEmail($_POST['parent1_email']);
        if (!$parent1_email) {
            $errors[] = "Invalid email format: Parent 1 Email.";
        }

        $parent2_phone = validateAndFilterPhoneNumber($_POST['parent1_phone']);
        if (!$parent2_phone) {
            $errors[] = "Invalid phone number format: Parent 2 Phone Number";
        }

        $parent2_altPhone = validateAndFilterPhoneNumber($_POST['parent1_altPhone']);
        if (!$parent2_altPhone) {
            $errors[] = "Invalid phone number format: Parent 2 Alternate Phone Number.";
        }

        $parent2_email = validateEmail($_POST['parent2_email']);
        if (!$parent2_email) {
            $errors[] = "Invalid email format: Parent 2 Email.";
        }

        $emergency_phone1 = validateAndFilterPhoneNumber($_POST['emergency_phone1']);
        if (!$emergency_phone1) {
            $errors[] = "Invalid phone number format: Emergency Contact 1 Phone Number";
        }

        $emergency_phone2 = validateAndFilterPhoneNumber($_POST['emergency_phone2']);
        if (!$emergency_phone2) {
            throw new Exception("Invalid phone number format: Emergency Contact 2 Alternate Phone Number.");
        }
        
        $args = sanitize($_POST, null);

        $required = array(
            "child_first_name", "child_last_name", "gender", "school_name", "grade", "birthdate", 
            "child_address", "child_city", "child_state", "child_zip", "child_medical_allergies", "child_food_avoidances", 
            "parent1_name", "parent1_phone", "parent1_address", "parent1_city", "parent1_state", "parent1_zip", "parent1_email", "parent1_altPhone", 
            "parent2_name", "parent2_phone", "parent2_address", "parent2_city", "parent2_state", "parent2_zip", "parent2_email", "parent2_altPhone", 
            "emergency_name1", "emergency_relationship1", "emergency_phone1", "emergency_name2", "emergency_relationship2", "emergency_phone2", 
            "authorized_pu", "not_authorized_pu", "primary_language", "hispanic_latino_spanish", "race", 
            "num_unemployed", "num_retired", "num_unemployed_student", "num_employed_fulltime", "num_employed_parttime", "num_employed_student", 
            "income", "other_programs", "lunch", "transportation", "participation", 
            "parent_initials", "signature", "signature_date", "waiver_child_name", "waiver_dob", "waiver_parent_name", 
            "waiver_provider_name", "waiver_provider_address", "waiver_phone_and_fax", "waiver_signature", "waiver_date"
        );

        if (wereRequiredFieldsSubmitted($args, $required)) {
            
        }

        $bbID = createbrainBuildersRegistrationForm($args);
    
        if (empty($bbID)) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    mysqli_rollback($connection);
    mysqli_close($connection);
    return null;
}


?>


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include_once("universal.inc")?>
        <title>Stafford Junction | Brain Builders Student Registration Form</title>
        <link rel="stylesheet" href="base.css">
    </head>
    <body>
        <h1>Brain Builders Registration Form 2024-2025</h1>
        <?php 
            if (isset($_GET['formSubmitFail'])) {
                echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Error Submitting Form</div>';
            }
        ?>   

        <div id="formatted_form">

        <!-- Form to obtain child autofill information -->
        <form id="childSelectBrainBuilders" method="GET" action="">
            <?php require_once('domain/Children.php') ?>
            <?php require_once('database/dbChildren.php') ?>
            <label for="childDropdown">Select a Child:</label>
            <select id="childDropdown" name="childId">
                <option value="" disabled>Select</option>
                <?php foreach ($family_children as $child): ?>
                    <option value="<?php echo $child->getID(); ?>"
                        <?php echo isset($_GET['childId']) && $_GET['childId'] == $child->getID() ? 'selected' : ''; ?>>
                        <?php echo $child->getFirstName() . " " . $child->getLastName(); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Submit">
        </form>
        <br><hr><br>

        <?php
        // If child ID is passed via GET, retrieve the child details
        if (isset($_GET['childId'])) {
            $selectedChild = retrieve_child_by_id($_GET['childId']);

            // If child is found, populate the second form with their details
            if ($selectedChild) {
                $child_first_name = $selectedChild->getFirstName();
                $child_last_name = $selectedChild->getLastName();
                $child_gender = $selectedChild->getGender();
                $child_school = $selectedChild->getSchool();
                $child_grade = $selectedChild->getGrade();
                $child_DOB = $selectedChild->getBirthDate();
                $child_address = $selectedChild->getAddress();
                $child_city = $selectedChild->getCity();
                $child_state = $selectedChild->getState();
                $child_zip = $selectedChild->getZip();
                $child_medical = $selectedChild->getMedicalNotes();
            }
        }
        ?>

        <form id="brainBuildersStudentRegistrationForm" action="" method="post">             
        
            <p><b>* Indicates a required field</b></p><br>
            <p><b>If any information is incorrect, consider editing your family account or your child's account information before continuing.</b></p>
            <br><br><br>
            
            <h2>Student Information</h2><hr><br>

            <!-- 1. Child First Name -->
            <label for="child_first_name">Child First Name *</label><br><br>
            <input type="text" style="background-color: yellow;color: black" name="child_first_name" id="child_first_name" 
                placeholder="Child First Name" disabled required 
                value="<?php echo isset($child_first_name) ? htmlspecialchars($child_first_name) : ''; ?>"><br><br>

            <!-- 2. Child Last Name -->
            <label for="child_last_name">Child Last Name *</label><br><br>
            <input type="text" style="background-color: yellow;color: black" name="child_last_name" id="child_last_name" 
                placeholder="Child Last Name" disabled required 
                value="<?php echo isset($child_last_name) ? htmlspecialchars($child_last_name) : ''; ?>"><br><br>

            <!-- 3. Child Email -->
            <label for="email">Child Email *</label><br><br>
            <input type="email" name="child_email" id="child_email" 
                placeholder="Child Email" required 
                value="<?php echo isset($family_email1) ? htmlspecialchars($family_email1) : ''; ?>"><br><br>

            <!-- 4. Gender -->
            <label for="gender">Gender *</label><br><br>
            <select id="gender" name="gender" required>
                <option value="" disabled <?php echo isset($child_gender) && $child_gender == '' ? 'selected' : ''; ?>>Select Gender</option>
                <option value="male" <?php echo isset($child_gender) && $child_gender == 'male' ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo isset($child_gender) && $child_gender == 'female' ? 'selected' : ''; ?>>Female</option>
            </select><br><br>

            <!-- 5. School Name -->
            <label for="school_name">School Name *</label><br><br>
            <input type="text" name="school_name" id="school_name" placeholder="School Name" required 
                value="<?php echo isset($child_school) ? htmlspecialchars($child_school) : ''; ?>"><br><br>

            <!-- 6. Grade -->
            <label for="grade">Grade *</label><br><br>
            <input type="text" name="grade" id="grade" placeholder="Grade/Grado" required 
                value="<?php echo isset($child_grade) ? htmlspecialchars($child_grade) : ''; ?>"><br><br>

            <!-- 7. Date of Birth -->
            <label for="birthdate">Date of Birth *</label><br><br>
            <input type="date" id="birthdate" name="birthdate" max="<?php echo date('Y-m-d'); ?>" required 
                value="<?php echo isset($child_DOB) ? htmlspecialchars($child_DOB) : ''; ?>"><br><br>

            <!-- 8. Street Address -->
            <label for="child_address">Street Address *</label><br><br>
            <input type="text" id="child_address" name="child_address" placeholder="Enter your street address" required 
                value="<?php echo isset($child_address) ? htmlspecialchars($child_address) : ''; ?>"><br><br>

            <!-- 9. City -->
            <label for="child_city">City *</label><br><br>
            <input type="text" id="child_city" name="child_city" placeholder="Enter your city" required 
                value="<?php echo isset($child_city) ? htmlspecialchars($child_city) : ''; ?>"><br><br>

            <!-- 10. State -->
            <label for="child_state">State *</label><br><br>
            <select id="child_state" name="child_state" required>
                <option value="" disabled>Select State</option>
                <option value="AL" <?php echo isset($child_state) && $child_state == 'AL' ? 'selected' : ''; ?>>Alabama</option>
                <option value="AK" <?php echo isset($child_state) && $child_state == 'AK' ? 'selected' : ''; ?>>Alaska</option>
                <option value="AZ" <?php echo isset($child_state) && $child_state == 'AZ' ? 'selected' : ''; ?>>Arizona</option>
                <option value="AR" <?php echo isset($child_state) && $child_state == 'AR' ? 'selected' : ''; ?>>Arkansas</option>
                <option value="CA" <?php echo isset($child_state) && $child_state == 'CA' ? 'selected' : ''; ?>>California</option>
                <option value="CO" <?php echo isset($child_state) && $child_state == 'CO' ? 'selected' : ''; ?>>Colorado</option>
                <option value="CT" <?php echo isset($child_state) && $child_state == 'CT' ? 'selected' : ''; ?>>Connecticut</option>
                <option value="DE" <?php echo isset($child_state) && $child_state == 'DE' ? 'selected' : ''; ?>>Delaware</option>
                <option value="FL" <?php echo isset($child_state) && $child_state == 'FL' ? 'selected' : ''; ?>>Florida</option>
                <option value="GA" <?php echo isset($child_state) && $child_state == 'GA' ? 'selected' : ''; ?>>Georgia</option>
                <option value="HI" <?php echo isset($child_state) && $child_state == 'HI' ? 'selected' : ''; ?>>Hawaii</option>
                <option value="ID" <?php echo isset($child_state) && $child_state == 'ID' ? 'selected' : ''; ?>>Idaho</option>
                <option value="IL" <?php echo isset($child_state) && $child_state == 'IL' ? 'selected' : ''; ?>>Illinois</option>
                <option value="IN" <?php echo isset($child_state) && $child_state == 'IN' ? 'selected' : ''; ?>>Indiana</option>
                <option value="IA" <?php echo isset($child_state) && $child_state == 'IA' ? 'selected' : ''; ?>>Iowa</option>
                <option value="KS" <?php echo isset($child_state) && $child_state == 'KS' ? 'selected' : ''; ?>>Kansas</option>
                <option value="KY" <?php echo isset($child_state) && $child_state == 'KY' ? 'selected' : ''; ?>>Kentucky</option>
                <option value="LA" <?php echo isset($child_state) && $child_state == 'LA' ? 'selected' : ''; ?>>Louisiana</option>
                <option value="ME" <?php echo isset($child_state) && $child_state == 'ME' ? 'selected' : ''; ?>>Maine</option>
                <option value="MD" <?php echo isset($child_state) && $child_state == 'MD' ? 'selected' : ''; ?>>Maryland</option>
                <option value="MA" <?php echo isset($child_state) && $child_state == 'MA' ? 'selected' : ''; ?>>Massachusetts</option>
                <option value="MI" <?php echo isset($child_state) && $child_state == 'MI' ? 'selected' : ''; ?>>Michigan</option>
                <option value="MN" <?php echo isset($child_state) && $child_state == 'MN' ? 'selected' : ''; ?>>Minnesota</option>
                <option value="MS" <?php echo isset($child_state) && $child_state == 'MS' ? 'selected' : ''; ?>>Mississippi</option>
                <option value="MO" <?php echo isset($child_state) && $child_state == 'MO' ? 'selected' : ''; ?>>Missouri</option>
                <option value="MT" <?php echo isset($child_state) && $child_state == 'MT' ? 'selected' : ''; ?>>Montana</option>
                <option value="NE" <?php echo isset($child_state) && $child_state == 'NE' ? 'selected' : ''; ?>>Nebraska</option>
                <option value="NV" <?php echo isset($child_state) && $child_state == 'NV' ? 'selected' : ''; ?>>Nevada</option>
                <option value="NH" <?php echo isset($child_state) && $child_state == 'NH' ? 'selected' : ''; ?>>New Hampshire</option>
                <option value="NJ" <?php echo isset($child_state) && $child_state == 'NJ' ? 'selected' : ''; ?>>New Jersey</option>
                <option value="NM" <?php echo isset($child_state) && $child_state == 'NM' ? 'selected' : ''; ?>>New Mexico</option>
                <option value="NY" <?php echo isset($child_state) && $child_state == 'NY' ? 'selected' : ''; ?>>New York</option>
                <option value="NC" <?php echo isset($child_state) && $child_state == 'NC' ? 'selected' : ''; ?>>North Carolina</option>
                <option value="ND" <?php echo isset($child_state) && $child_state == 'ND' ? 'selected' : ''; ?>>North Dakota</option>
                <option value="OH" <?php echo isset($child_state) && $child_state == 'OH' ? 'selected' : ''; ?>>Ohio</option>
                <option value="OK" <?php echo isset($child_state) && $child_state == 'OK' ? 'selected' : ''; ?>>Oklahoma</option>
                <option value="OR" <?php echo isset($child_state) && $child_state == 'OR' ? 'selected' : ''; ?>>Oregon</option>
                <option value="PA" <?php echo isset($child_state) && $child_state == 'PA' ? 'selected' : ''; ?>>Pennsylvania</option>
                <option value="RI" <?php echo isset($child_state) && $child_state == 'RI' ? 'selected' : ''; ?>>Rhode Island</option>
                <option value="SC" <?php echo isset($child_state) && $child_state == 'SC' ? 'selected' : ''; ?>>South Carolina</option>
                <option value="SD" <?php echo isset($child_state) && $child_state == 'SD' ? 'selected' : ''; ?>>South Dakota</option>
                <option value="TN" <?php echo isset($child_state) && $child_state == 'TN' ? 'selected' : ''; ?>>Tennessee</option>
                <option value="TX" <?php echo isset($child_state) && $child_state == 'TX' ? 'selected' : ''; ?>>Texas</option>
                <option value="UT" <?php echo isset($child_state) && $child_state == 'UT' ? 'selected' : ''; ?>>Utah</option>
                <option value="VA" <?php echo isset($child_state) && $child_state == 'VA' ? 'selected' : ''; ?>>Virginia</option>
                <option value="VT" <?php echo isset($child_state) && $child_state == 'VT' ? 'selected' : ''; ?>>Vermont</option>
                <option value="WA" <?php echo isset($child_state) && $child_state == 'WA' ? 'selected' : ''; ?>>Washington</option>
                <option value="WV" <?php echo isset($child_state) && $child_state == 'WV' ? 'selected' : ''; ?>>West Virginia</option>
                <option value="WI" <?php echo isset($child_state) && $child_state == 'WI' ? 'selected' : ''; ?>>Wisconsin</option>
                <option value="WY" <?php echo isset($child_state) && $child_state == 'WY' ? 'selected' : ''; ?>>Wyoming</option>
            </select><br><br>

            <!-- 11. Zip Code -->
            <label for="child_zip">Zip Code *</label><br><br>
            <input type="text" id="child_zip" name="child_zip" pattern="[0-9]{5}" placeholder="Enter your 5-digit zip code" required 
                value="<?php echo isset($child_zip) ? htmlspecialchars($child_zip) : ''; ?>"><br><br>

            <!-- 12. Medical issues or allergies -->
            <label for="child_medical_allergies">Medical issues or allergies</label><br><br>
            <input type="text" id="child_medical_allergies" name="child_medical_allergies" placeholder="Medical issues or allergies" 
                value="<?php echo isset($child_medical) ? htmlspecialchars($child_medical) : ''; ?>"><br><br>

            <!-- 13. Foods to avoid due to religious beliefs -->
            <label for="child_food_avoidances">Foods to avoid due to religious beliefs</label><br><br>
            <input type="text" id="child_food_avoidances" name="child_food_avoidances" placeholder="Foods to avoid due to religious beliefs">
            <br><br><br>
                
            <h2>Parent 1 Information</h2><hr><br>

            <!--Parent 1 Name-->
            <label for="parent1_name">Full Name *</label><br><br>
            <input type="text" id="parent1_name" name="parent1_name" required placeholder="Parent 1 Full Name" 
                value="<?php echo isset($family_name1) ? $family_name1 : ''; ?>"><br><br>

            <!--Cell Phone-->
            <label for="parent1_phone">Primary Phone Number *</label><br><br>
            <input type="tel" id="parent1_phone" name="parent1_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555" 
                value="<?php echo isset($family_phone1) ? $family_phone1 : ''; ?>"><br><br>

            <!--Street Address-->
            <label for="parent1_address">Street Address *</label><br><br>
            <input type="text" id="parent1_address" name="parent1_address" required placeholder="Enter your street address" 
                value="<?php echo isset($family_address1) ? $family_address1 : ''; ?>"><br><br>

            <!--City-->
            <label for="parent1_city">City *</label><br><br>
            <input type="text" id="parent1_city" name="parent1_city" required placeholder="Enter your city" 
                value="<?php echo isset($family_city1) ? $family_city1 : ''; ?>"><br><br>

            <!--State-->
            <label for="parent1_state">State *</label><br><br>
            <select id="parent1_state" name="parent1_state" required>
                <option value="" disabled>Select State</option>
                <option value="AL" <?php echo (isset($family_state1) && $family_state1 == 'AL') ? 'selected' : ''; ?>>Alabama</option>
                <option value="AK" <?php echo (isset($family_state1) && $family_state1 == 'AK') ? 'selected' : ''; ?>>Alaska</option>
                <option value="AZ" <?php echo (isset($family_state1) && $family_state1 == 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                <option value="AR" <?php echo (isset($family_state1) && $family_state1 == 'AR') ? 'selected' : ''; ?>>Arkansas</option>
                <option value="CA" <?php echo (isset($family_state1) && $family_state1 == 'CA') ? 'selected' : ''; ?>>California</option>
                <option value="CO" <?php echo (isset($family_state1) && $family_state1 == 'CO') ? 'selected' : ''; ?>>Colorado</option>
                <option value="CT" <?php echo (isset($family_state1) && $family_state1 == 'CT') ? 'selected' : ''; ?>>Connecticut</option>
                <option value="DE" <?php echo (isset($family_state1) && $family_state1 == 'DE') ? 'selected' : ''; ?>>Delaware</option>
                <option value="FL" <?php echo (isset($family_state1) && $family_state1 == 'FL') ? 'selected' : ''; ?>>Florida</option>
                <option value="GA" <?php echo (isset($family_state1) && $family_state1 == 'GA') ? 'selected' : ''; ?>>Georgia</option>
                <option value="HI" <?php echo (isset($family_state1) && $family_state1 == 'HI') ? 'selected' : ''; ?>>Hawaii</option>
                <option value="ID" <?php echo (isset($family_state1) && $family_state1 == 'ID') ? 'selected' : ''; ?>>Idaho</option>
                <option value="IL" <?php echo (isset($family_state1) && $family_state1 == 'IL') ? 'selected' : ''; ?>>Illinois</option>
                <option value="IN" <?php echo (isset($family_state1) && $family_state1 == 'IN') ? 'selected' : ''; ?>>Indiana</option>
                <option value="IA" <?php echo (isset($family_state1) && $family_state1 == 'IA') ? 'selected' : ''; ?>>Iowa</option>
                <option value="KS" <?php echo (isset($family_state1) && $family_state1 == 'KS') ? 'selected' : ''; ?>>Kansas</option>
                <option value="KY" <?php echo (isset($family_state1) && $family_state1 == 'KY') ? 'selected' : ''; ?>>Kentucky</option>
                <option value="LA" <?php echo (isset($family_state1) && $family_state1 == 'LA') ? 'selected' : ''; ?>>Louisiana</option>
                <option value="ME" <?php echo (isset($family_state1) && $family_state1 == 'ME') ? 'selected' : ''; ?>>Maine</option>
                <option value="MD" <?php echo (isset($family_state1) && $family_state1 == 'MD') ? 'selected' : ''; ?>>Maryland</option>
                <option value="MA" <?php echo (isset($family_state1) && $family_state1 == 'MA') ? 'selected' : ''; ?>>Massachusetts</option>
                <option value="MI" <?php echo (isset($family_state1) && $family_state1 == 'MI') ? 'selected' : ''; ?>>Michigan</option>
                <option value="MN" <?php echo (isset($family_state1) && $family_state1 == 'MN') ? 'selected' : ''; ?>>Minnesota</option>
                <option value="MS" <?php echo (isset($family_state1) && $family_state1 == 'MS') ? 'selected' : ''; ?>>Mississippi</option>
                <option value="MO" <?php echo (isset($family_state1) && $family_state1 == 'MO') ? 'selected' : ''; ?>>Missouri</option>
                <option value="MT" <?php echo (isset($family_state1) && $family_state1 == 'MT') ? 'selected' : ''; ?>>Montana</option>
                <option value="NE" <?php echo (isset($family_state1) && $family_state1 == 'NE') ? 'selected' : ''; ?>>Nebraska</option>
                <option value="NV" <?php echo (isset($family_state1) && $family_state1 == 'NV') ? 'selected' : ''; ?>>Nevada</option>
                <option value="NH" <?php echo (isset($family_state1) && $family_state1 == 'NH') ? 'selected' : ''; ?>>New Hampshire</option>
                <option value="NJ" <?php echo (isset($family_state1) && $family_state1 == 'NJ') ? 'selected' : ''; ?>>New Jersey</option>
                <option value="NM" <?php echo (isset($family_state1) && $family_state1 == 'NM') ? 'selected' : ''; ?>>New Mexico</option>
                <option value="NY" <?php echo (isset($family_state1) && $family_state1 == 'NY') ? 'selected' : ''; ?>>New York</option>
                <option value="NC" <?php echo (isset($family_state1) && $family_state1 == 'NC') ? 'selected' : ''; ?>>North Carolina</option>
                <option value="ND" <?php echo (isset($family_state1) && $family_state1 == 'ND') ? 'selected' : ''; ?>>North Dakota</option>
                <option value="OH" <?php echo (isset($family_state1) && $family_state1 == 'OH') ? 'selected' : ''; ?>>Ohio</option>
                <option value="OK" <?php echo (isset($family_state1) && $family_state1 == 'OK') ? 'selected' : ''; ?>>Oklahoma</option>
                <option value="OR" <?php echo (isset($family_state1) && $family_state1 == 'OR') ? 'selected' : ''; ?>>Oregon</option>
                <option value="PA" <?php echo (isset($family_state1) && $family_state1 == 'PA') ? 'selected' : ''; ?>>Pennsylvania</option>
                <option value="RI" <?php echo (isset($family_state1) && $family_state1 == 'RI') ? 'selected' : ''; ?>>Rhode Island</option>
                <option value="SC" <?php echo (isset($family_state1) && $family_state1 == 'SC') ? 'selected' : ''; ?>>South Carolina</option>
                <option value="SD" <?php echo (isset($family_state1) && $family_state1 == 'SD') ? 'selected' : ''; ?>>South Dakota</option>
                <option value="TN" <?php echo (isset($family_state1) && $family_state1 == 'TN') ? 'selected' : ''; ?>>Tennessee</option>
                <option value="TX" <?php echo (isset($family_state1) && $family_state1 == 'TX') ? 'selected' : ''; ?>>Texas</option>
                <option value="UT" <?php echo (isset($family_state1) && $family_state1 == 'UT') ? 'selected' : ''; ?>>Utah</option>
                <option value="VT" <?php echo (isset($family_state1) && $family_state1 == 'VT') ? 'selected' : ''; ?>>Vermont</option>
                <option value="VA" <?php echo (isset($family_state1) && $family_state1 == 'VA') ? 'selected' : ''; ?>>Virginia</option>
                <option value="WA" <?php echo (isset($family_state1) && $family_state1 == 'WA') ? 'selected' : ''; ?>>Washington</option>
                <option value="WV" <?php echo (isset($family_state1) && $family_state1 == 'WV') ? 'selected' : ''; ?>>West Virginia</option>
                <option value="WI" <?php echo (isset($family_state1) && $family_state1 == 'WI') ? 'selected' : ''; ?>>Wisconsin</option>
                <option value="WY" <?php echo (isset($family_state1) && $family_state1 == 'WY') ? 'selected' : ''; ?>>Wyoming</option>
            </select><br><br>

            <!--Zip-->
            <label for="parent1_zip">Zip Code *</label><br><br>
            <input type="text" id="parent1_zip" name="parent1_zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code" 
                value="<?php echo isset($family_zip1) ? $family_zip1 : ''; ?>"><br><br>

            <!--Email-->
            <label for="parent1_email">Email *</label><br><br>
            <input type="text" id="parent1_email" name="parent1_email" required placeholder="Enter your email" 
                value="<?php echo isset($family_email1) ? $family_email1 : ''; ?>"><br><br>

            <!--Alternate Phone-->
            <label for="parent1_altPhone">Alternate Phone Number</label><br><br>
            <input type="tel" id="parent1_altPhone" name="parent1_altPhone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555"
                value="<?php echo isset($family_altphone1) ? $family_altphone1 : ''; ?>"><br><br><br>

            <h2>Parent 2 Information</h2><hr><br>

            <!--Parent 2 Name-->
            <label for="parent2_name">Full Name</label><br><br>
            <input type="text" id="parent2_name" name="parent2_name" placeholder="Parent 2 Full Name" 
                value="<?php echo isset($family_name2) ? $family_name2 : ''; ?>"><br><br>

            <!--Cell Phone-->
            <label for="parent2_phone">Primary Phone Number</label><br><br>
            <input type="tel" id="parent2_phone" name="parent2_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555" 
                value="<?php echo isset($family_phone2) ? $family_phone2 : ''; ?>"><br><br>

            <!--Street Address-->
            <label for="parent2_address">Street Address</label><br><br>
            <input type="text" id="parent2_address" name="parent2_address" placeholder="Enter your street address" value="<?php echo isset($family_address2) ? $family_address2 : ''; ?>"><br><br>

            <!--City-->
            <label for="parent2_city">City</label><br><br>
            <input type="text" id="parent2_city" name="parent2_city" placeholder="Enter your city" value="<?php echo isset($family_city2) ? $family_city2 : ''; ?>"><br><br>

            <!--State-->
            <label for="parent2_state">State </label><br><br>
            <select id="parent2_state" name="parent2_state">
                <option value="" disabled>Select State</option>
                <option value="AL" <?php echo (isset($family_state2) && $family_state2 == 'AL') ? 'selected' : ''; ?>>Alabama</option>
                <option value="AK" <?php echo (isset($family_state2) && $family_state2 == 'AK') ? 'selected' : ''; ?>>Alaska</option>
                <option value="AZ" <?php echo (isset($family_state2) && $family_state2 == 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                <option value="AR" <?php echo (isset($family_state2) && $family_state2 == 'AR') ? 'selected' : ''; ?>>Arkansas</option>
                <option value="CA" <?php echo (isset($family_state2) && $family_state2 == 'CA') ? 'selected' : ''; ?>>California</option>
                <option value="CO" <?php echo (isset($family_state2) && $family_state2 == 'CO') ? 'selected' : ''; ?>>Colorado</option>
                <option value="CT" <?php echo (isset($family_state2) && $family_state2 == 'CT') ? 'selected' : ''; ?>>Connecticut</option>
                <option value="DE" <?php echo (isset($family_state2) && $family_state2 == 'DE') ? 'selected' : ''; ?>>Delaware</option>
                <option value="FL" <?php echo (isset($family_state2) && $family_state2 == 'FL') ? 'selected' : ''; ?>>Florida</option>
                <option value="GA" <?php echo (isset($family_state2) && $family_state2 == 'GA') ? 'selected' : ''; ?>>Georgia</option>
                <option value="HI" <?php echo (isset($family_state2) && $family_state2 == 'HI') ? 'selected' : ''; ?>>Hawaii</option>
                <option value="ID" <?php echo (isset($family_state2) && $family_state2 == 'ID') ? 'selected' : ''; ?>>Idaho</option>
                <option value="IL" <?php echo (isset($family_state2) && $family_state2 == 'IL') ? 'selected' : ''; ?>>Illinois</option>
                <option value="IN" <?php echo (isset($family_state2) && $family_state2 == 'IN') ? 'selected' : ''; ?>>Indiana</option>
                <option value="IA" <?php echo (isset($family_state2) && $family_state2 == 'IA') ? 'selected' : ''; ?>>Iowa</option>
                <option value="KS" <?php echo (isset($family_state2) && $family_state2 == 'KS') ? 'selected' : ''; ?>>Kansas</option>
                <option value="KY" <?php echo (isset($family_state2) && $family_state2 == 'KY') ? 'selected' : ''; ?>>Kentucky</option>
                <option value="LA" <?php echo (isset($family_state2) && $family_state2 == 'LA') ? 'selected' : ''; ?>>Louisiana</option>
                <option value="ME" <?php echo (isset($family_state2) && $family_state2 == 'ME') ? 'selected' : ''; ?>>Maine</option>
                <option value="MD" <?php echo (isset($family_state2) && $family_state2 == 'MD') ? 'selected' : ''; ?>>Maryland</option>
                <option value="MA" <?php echo (isset($family_state2) && $family_state2 == 'MA') ? 'selected' : ''; ?>>Massachusetts</option>
                <option value="MI" <?php echo (isset($family_state2) && $family_state2 == 'MI') ? 'selected' : ''; ?>>Michigan</option>
                <option value="MN" <?php echo (isset($family_state2) && $family_state2 == 'MN') ? 'selected' : ''; ?>>Minnesota</option>
                <option value="MS" <?php echo (isset($family_state2) && $family_state2 == 'MS') ? 'selected' : ''; ?>>Mississippi</option>
                <option value="MO" <?php echo (isset($family_state2) && $family_state2 == 'MO') ? 'selected' : ''; ?>>Missouri</option>
                <option value="MT" <?php echo (isset($family_state2) && $family_state2 == 'MT') ? 'selected' : ''; ?>>Montana</option>
                <option value="NE" <?php echo (isset($family_state2) && $family_state2 == 'NE') ? 'selected' : ''; ?>>Nebraska</option>
                <option value="NV" <?php echo (isset($family_state2) && $family_state2 == 'NV') ? 'selected' : ''; ?>>Nevada</option>
                <option value="NH" <?php echo (isset($family_state2) && $family_state2 == 'NH') ? 'selected' : ''; ?>>New Hampshire</option>
                <option value="NJ" <?php echo (isset($family_state2) && $family_state2 == 'NJ') ? 'selected' : ''; ?>>New Jersey</option>
                <option value="NM" <?php echo (isset($family_state2) && $family_state2 == 'NM') ? 'selected' : ''; ?>>New Mexico</option>
                <option value="NY" <?php echo (isset($family_state2) && $family_state2 == 'NY') ? 'selected' : ''; ?>>New York</option>
                <option value="NC" <?php echo (isset($family_state2) && $family_state2 == 'NC') ? 'selected' : ''; ?>>North Carolina</option>
                <option value="ND" <?php echo (isset($family_state2) && $family_state2 == 'ND') ? 'selected' : ''; ?>>North Dakota</option>
                <option value="OH" <?php echo (isset($family_state2) && $family_state2 == 'OH') ? 'selected' : ''; ?>>Ohio</option>
                <option value="OK" <?php echo (isset($family_state2) && $family_state2 == 'OK') ? 'selected' : ''; ?>>Oklahoma</option>
                <option value="OR" <?php echo (isset($family_state2) && $family_state2 == 'OR') ? 'selected' : ''; ?>>Oregon</option>
                <option value="PA" <?php echo (isset($family_state2) && $family_state2 == 'PA') ? 'selected' : ''; ?>>Pennsylvania</option>
                <option value="RI" <?php echo (isset($family_state2) && $family_state2 == 'RI') ? 'selected' : ''; ?>>Rhode Island</option>
                <option value="SC" <?php echo (isset($family_state2) && $family_state2 == 'SC') ? 'selected' : ''; ?>>South Carolina</option>
                <option value="SD" <?php echo (isset($family_state2) && $family_state2 == 'SD') ? 'selected' : ''; ?>>South Dakota</option>
                <option value="TN" <?php echo (isset($family_state2) && $family_state2 == 'TN') ? 'selected' : ''; ?>>Tennessee</option>
                <option value="TX" <?php echo (isset($family_state2) && $family_state2 == 'TX') ? 'selected' : ''; ?>>Texas</option>
                <option value="UT" <?php echo (isset($family_state2) && $family_state2 == 'UT') ? 'selected' : ''; ?>>Utah</option>
                <option value="VT" <?php echo (isset($family_state2) && $family_state2 == 'VT') ? 'selected' : ''; ?>>Vermont</option>
                <option value="VA" <?php echo (isset($family_state2) && $family_state2 == 'VA') ? 'selected' : ''; ?>>Virginia</option>
                <option value="WA" <?php echo (isset($family_state2) && $family_state2 == 'WA') ? 'selected' : ''; ?>>Washington</option>
                <option value="WV" <?php echo (isset($family_state2) && $family_state2 == 'WV') ? 'selected' : ''; ?>>West Virginia</option>
                <option value="WI" <?php echo (isset($family_state2) && $family_state2 == 'WI') ? 'selected' : ''; ?>>Wisconsin</option>
                <option value="WY" <?php echo (isset($family_state2) && $family_state2 == 'WY') ? 'selected' : ''; ?>>Wyoming</option>
            </select><br><br>

            <!--Zip-->
            <label for="parent2_zip" required>Zip Code</label><br><br>
            <input type="text" id="parent2_zip" name="parent2_zip" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code" 
                value="<?php echo isset($family_zip2) ? $family_zip2 : ''; ?>"><br><br>

            <!--Email-->
            <label for="parent2_email" required>Email</label><br><br>
            <input type="text" id="parent2_email" name="parent2_email" placeholder="Enter your email" 
                value="<?php echo isset($family_email2) ? $family_email2 : ''; ?>"><br><br>

            <!--Alternate Phone-->
            <label for="parent2_altPhone" required>Alternate Phone Number</label><br><br>
            <input type="tel" id="parent2_altPhone" name="parent2_altPhone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555" 
                value="<?php echo isset($family_altphone2) ? $family_altphone2 : ''; ?>"><br><br><br>

            <h2>Emergency Contact 1 Information</h2><hr><br>

            <!--Name-->
            <label for="emergency_name1" required>Full Name *</label><br><br>
            <input type="text" id="emergency_name1" name="emergency_name1" required placeholder="Enter full name" 
                value="<?php echo isset($family_emergency_name1) ? $family_emergency_name1 : ''; ?>"><br><br>

            <!--Relationship-->
            <label for="emergency_relationship1" required>Relationship to Child *</label><br><br>
            <input type="text" id="emergency_relationship1" name="emergency_relationship1" placeholder="Enter person's relationship to child" 
                value="<?php echo isset($family_emergency_relation1) ? $family_emergency_relation1 : ''; ?>" required><br><br>

            <!--Phone-->
            <label for="emergency_phone1" required>Phone *</label><br><br>
            <input type="tel" id="emergency_phone1" name="emergency_phone1" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555" 
                value="<?php echo isset($family_emergency_phone1) ? $family_emergency_phone1 : ''; ?>" required>
            <br><br><br>

            <h2>Emergency Contact 2 Information</h2><hr><br>

            <!--Name-->
            <label for="emergency_name2" required>Full Name</label><br><br>
            <input type="text" id="emergency_name2" name="emergency_name2" placeholder="Enter full name" ><br><br>

            <!--Relationship-->
            <label for="emergency_relationship2" required>Relationship to Child</label><br><br>
            <input type="text" id="emergency_relationship2" name="emergency_relationship2" placeholder="Enter person's relationship to child"><br><br>

            <!--Phone-->
            <label for="emergency_phone2" required>Phone</label><br><br>
            <input type="tel" id="emergency_phone2" name="emergency_phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (000) 000-0000">
            <br><br><br>

            <h2>Pick-Up Information</h2><hr><br>

            <!--Persons Authorized for Pick-Up-->
            <label for="authorized_pu" required>Persons authorized to pick up child *</label><br><br>
            <input type="text" id="authorized_pu" name="authorized_pu" required placeholder="Enter persons names"><br><br>
            
            <!--Persons NOT Authorized for Pick-Up-->
            <label for="not_authorized_pu" required>Persons <b><u>NOT</u></b> authorized to pick up child</label><br><br>
            <input type="text" id="not_authorized_pu" name="not_authorized_pu" placeholder="Enter persons names">
            <br><br><br>

            <h2>Other Required Information</h2><hr><br>
            <p>This information is for Stafford Junction funding purposes only.</p><br>

            <!--Parent's Primary Language-->
            <label for="primary_language" required>Parent 1 Primary Language *</label><br><br>
            <input type="text" id="primary_language" name="primary_language" required placeholder="English, Spanish, Farsi, etc."><br><br>

            <!--Hispanic, Latino, or Spanish Origin-->
            <label for="hispanic_latino_spanish">Parent 1 Hispanic, Latino, or Spanish Origin *</label><br><br>
            <select id="hispanic_latino_spanish" name="hispanic_latino_spanish" required>
                <option value="" disabled <?php echo isset($family_isHispanic1) && $family_isHispanic1 == '' ? 'selected' : ''; ?>>Select Yes or No</option>
                <option value="yes" <?php echo isset($family_isHispanic1) && $family_isHispanic1 == '1' ? 'selected' : ''; ?>>Yes</option>
                <option value="no" <?php echo isset($family_isHispanic1) && $family_isHispanic1 == '0' ? 'selected' : ''; ?>>No</option>
            </select><br><br>

            <!--Race-->
            <label for="race" required>Race *</label><br><br>
            <select id="race" name="race" required>
                <option value="" disabled <?php echo isset($family_race1) && $family_race1 == '' ? 'selected' : ''; ?>>Select Race</option>
                <option value="Caucasian" <?php echo isset($family_race1) && $family_race1 == 'Caucasian' ? 'selected' : ''; ?>>Caucasian</option>
                <option value="Black/African American" <?php echo isset($family_race1) && $family_race1 == 'Black/African American' ? 'selected' : ''; ?>>Black/African American</option>
                <option value="Native Indian/Alaska Native" <?php echo isset($family_race1) && $family_race1 == 'Native Indian/Alaska Native' ? 'selected' : ''; ?>>Native Indian/Alaska Native</option>
                <option value="Native Hawaiian/Pacific Islander" <?php echo isset($family_race1) && $family_race1 == 'Native Hawaiian/Pacific Islander' ? 'selected' : ''; ?>>Native Hawaiian/Pacific Islander</option>
                <option value="Asian" <?php echo isset($family_race1) && $family_race1 == 'Asian' ? 'selected' : ''; ?>>Asian</option>
                <option value="Multiracial" <?php echo isset($family_race1) && $family_race1 == 'Multiracial' ? 'selected' : ''; ?>>Multiracial</option>
                <option value="Other" <?php echo isset($family_race1) && $family_race1 == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select><br><br>

            <!--Num Unemployed in Household-->
            <label for="num_unemployed" required>Number of Unemployed in Household *</label><br><br>
            <input type="number" id="num_unemployed" name="num_unemployed" required placeholder="Enter number of unemployed"><br><br>

            <!--Num Retired in Household-->
            <label for="num_retired" required>Number of Retired in Household *</label><br><br>
            <input type="number" id="num_retired" name="num_retired" required placeholder="Enter number of retired"><br><br>

            <!--Num Unemployed Student in Household-->
            <label for="num_unemployed_student" required>Number of Unemployed Students in Household *</label><br><br>
            <input type="number" id="num_unemployed_student" name="num_unemployed_student" required placeholder="Enter number of unemployed students"><br><br>

            <!--Num Employed Full-Time in Household-->
            <label for="num_employed_fulltime" required>Number of Full-Time Employed in Household *</label><br><br>
            <input type="number" id="num_employed_fulltime" name="num_employed_fulltime" required placeholder="Enter number of full-time employed"><br><br>

            <!--Num Employed Part-Time in Household-->
            <label for="num_employed_parttime" required>Number of Part-Time Employed in Household *</label><br><br>
            <input type="number" id="num_employed_parttime" name="num_employed_parttime" required placeholder="Enter number of part-time employed"><br><br>

            <!--Num Employed Student in Household-->
            <label for="num_employed_student" required>Number of Employed Students in Household *</label><br><br>
            <input type="number" id="num_employed_student" name="num_employed_student" required placeholder="Enter number of employed students"><br><br>

            <!--Estimated Household Income-->
            <label for="income">Estimated Household Income *</label><br><br>
            <select id="income" name="income" required>
                <option value="" disabled <?php echo isset($family_income) && $family_income == '' ? 'selected' : ''; ?>>Select Estimated Income</option>
                <option value="Under 20,000" <?php echo isset($family_income) && $family_income == 'Under $15,0000' ? 'selected' : ''; ?>>Under 20,000</option>
                <option value="20,000-40,000n" <?php echo isset($family_income) && $family_income == '$15,000 - $24,999' ? 'selected' : ''; ?>>20,000-40,000</option>
                <option value="40,001-60,000" <?php echo isset($family_income) && $family_income == '$25,000 - $34,999' ? 'selected' : ''; ?>>40,001-60,000</option>
                <option value="60,001-80,000" <?php echo isset($family_income) && $family_income == '$35,000 - $49,999' ? 'selected' : ''; ?>>60,001-80,000</option>
                <option value="Over 80,000" <?php echo isset($family_income) && $family_income == '$100,000 and above' ? 'selected' : ''; ?>>Over 80,000</option>
            </select><br><br>

            <!--Other Programs-->
            <label for="other_programs" required>Other Programs *</label><br><br>
            <input type="text" id="other_programs" name="other_programs" required placeholder="(WIC, SNAP, SSI, SSD, etc.)"><br><br>

            <!--Free/Reduced Lunch-->
            <label for="lunch">Does the enrolling child receive free or reduced lunch? *</label><br><br>
            <select id="lunch" name="lunch" required>
                <option value="" disabled selected>Select</option>
                <option value="free">Free</option>
                <option value="reduced">Reduced</option>
                <option value="neither">Neither</option>
            </select>
            
            <br><br><br>

            <h2>Transportation</h2><hr><br>
            
            <!--Transportation-->
            <label for="transportation">Stafford Junction provides transportation home after the Brain Builders program. Please check one of the following:</p><br>
            
            <input type="radio" id="choice_1" name="transportation" value="needs_transportation">
            <label for="choice_1">My child has permission to be transported by Stafford Junction staff/volunteers in Stafford Junction vehicles.</label><br><br>

            <input type="radio" id="choice_2" name="transportation" value="transports_themselves">
            <label for="choice_2">I will make alternate arrangements for my child to be transported home.</label>
            <br><br><br>

            <h2>Code of Conduct</h2><hr><br>

            <p>Stafford Junction practices four core values: Caring, Honesty, Respect, and Responsibility. We are not a daycare
            service. The program is staffed by volunteers whose sole responsibility is to provide stimulating activities to youth,
            preventing summer learning loss. Misbehavior by students will not be tolerated.</p><br>

            <p>The standard disciplinary process is as follows: (1) verbal warning, (2) a second verbal warning and parents contacted,
            (3) dismissal from the program.</p><br>

            <p>Exceptions: If a student commits a serious infraction, the Youth Program Manager has the option to immediately
            dismiss the child from the program.</p>
            <br><br><br>

            <h2>Photograph and Video Waiver</h2><hr><br>

            <p>I acknowledge that Stafford Junction may utilize photographs or videos of participants that may be taken during
            involvement in Stafford Junction activities. This includes internal and external use, including but not limited to
            Stafford Junction’s website, Facebook, and publications. I consent to such uses and hereby waive all rights of
            compensation. If I do not wish the image of my child to be included in those mentioned above, it is my responsibility to
            inform them to exclude themselves from photographs or videos taken during such activities.</p>
            <br><br><br>

            <h2>Other Activities & Programs</h2><hr><br>

            <p>Stafford Junction offers additional free activities besides Brain Builders, including on-site options and field trips to places
            like the YMCA, fishing spots, and community events. Limited space is available. Please indicate interest by checking the
            appropriate box and initialing below. We'll inform you of the activity details and provide early sign-up opportunities.</p><br>

            <label for="participation">I would like my child to participate:</label><br><br>
            <select id="participation" name="participation" required>
                <option value="" disabled selected>Select Yes or No</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <br><br>

            <!--Parent Initials-->
            <label for="parent_initials">Parent Initials *</label><br><br>
            <input type="text" name="parent_initials" id="parent_initials" required placeholder="Parent Initials" required>
            <br><br><br>

            <h2>Acknowledgment and Consent</h2><hr><br>

            <p>By signing below, I acknowledge, understand, accept, and agree to all policies and waivers stated and outlined in this
            Brain Builders Enrollment Form for the current school year.</p><br>
            <p>By electronically signing, you agree that your e-signature holds the same legal validity and effect as a handwritten signature.</p><br>

            <!--Parent/Guardian Electronic Signature-->
            <label for="signature">Parent/Guardian Signature *</label><br><br>
            <input type="text" name="signature" id="signature" required placeholder="Parent/Guardian Signature" required><br><br>

            <!--Date-->
            <label for="signature_date">Date *</label><br><br>
            <input type="date" id="signature_date" name="signature_date"  
                placeholder="Date" max="<?php echo date('Y-m-d'); ?>" required
                value="<?php echo date('Y-m-d'); ?>">
            <br><br><br>

            <h2>PERMISSION FOR MUTUAL RELEASE OF INFORMATION</h2><hr><br>

            <p>
                Stafford County Public Schools – Department of Student Services<br>
                31 Stafford Avenue, Stafford, Virginia 22554<br>
                (540) 658-6500 FAX (540) 658-6042<br><br>

                By signing this form, I am allowing Stafford County Public Schools to exchange information with
                the agencies/people listed below; and I am allowing Stafford County Public School employees to
                discuss my child with the people/employees of the agencies listed below. This release allows
                Stafford County Public Schools’ employees to exchange with the listed agencies/people
                educational, medical, sociological, psychological, psychiatric, and treatment records and
                information related to these records. The designation of one or more contact persons is to facilitate
                communication and does not restrict access of information to and from the listed agencies and
                Stafford County Public Schools unless so specified. The child’s social security number may be
                included in the records exchanged.<br><br>

                In addition, I give permission for Stafford County Public Schools and its employees to disclose
                information about my child in the course of completing any on-line form, rating scale, inventory,
                or survey requested by the people/agency listed below. I understand that any on-line form, rating
                scale, inventory, or survey requested by the people/agency listed below may be operated and
                maintained by a third-party who contracts with the people/agency listed below.<br><br>
            </p>

            <!--Child's Full Name-->
            <label for="waiver_child_name">Child's Full Name *</label><br><br>
            <input type="text" style="background-color: yellow;color: black" name="waiver_child_name" id="waiver_child_name" 
                placeholder="Child's Full Name" required disabled
                value="<?php echo isset($child_first_name) && isset($child_last_name) ? htmlspecialchars($child_first_name . ' ' . $child_last_name) : ''; ?>"><br><br>

            <!--Child's Date of Birth-->
            <label for="waiver_dob">Child's Date of Birth *</label><br><br>
            <input type="date" id="waiver_dob" name="waiver_dob"  
                placeholder="Child's Date of Birth" max="<?php echo date('Y-m-d'); ?>" required
                value="<?php echo isset($child_DOB) ? htmlspecialchars($child_DOB) : ''; ?>"><br><br>

            <!--Parent/Guardian Name-->
            <label for="waiver_parent_name">Parent/Guardian Name *</label><br><br>
            <input type="text" name="waiver_parent_name" id="waiver_parent_name"  
                placeholder="Parent/Guardian Name" required><br><br>

            <!--Provider's Name-->
            <label for="waiver_provider_name">Provider's Name *</label><br><br>
            <input type="text" name="waiver_provider_name" id="waiver_provider_name"
                placeholder="Provider's Name" required><br><br>

            <!--Address-->
            <label for="waiver_provider_address">Provider's Address *</label><br><br>
            <input type="text" name="waiver_provider_address" id="waiver_provider_address"
                placeholder="Provider's Address" required><br><br>

            <!--Phone & Fax-->
            <label for="waiver_phone_and_fax">Provider's Phone & Fax *</label><br><br>
            <input type="text" name="waiver_phone_and_fax" id="waiver_phone_and_fax"
                placeholder="Provider's Phone & Fax" required><br><br>

            <!--Signature-->
            <label for="waiver_signature">Parent / Legal Guardian / Surrogate/ Eligible Student Signature *</label><br>
            <p>By electronically signing, you agree that your e-signature holds the same legal validity and effect as a handwritten signature.</p><br>
            <input type="text" name="waiver_signature" id="waiver_signature" 
                placeholder="Parent / Legal Guardian / Surrogate/ Eligible Student Signature" required><br><br>

            <!--Date-->
            <label for="waiver_date" required>Date *</label><br><br>
            <input type="date" id="waiver_date" name="waiver_date"
                placeholder="Date" max="<?php echo date('Y-m-d'); ?>" required
                value="<?php echo date('Y-m-d'); ?>"><br><br>

            <button type="submit" id="submit">Submit</button>
            <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
        
            
            <script type="text/javascript">
                // Create a children data object for autofill using PHP to pass the array
                const childrenData = <?php 
                    $childrenArray = [];
                    foreach ($family_children as $child) {
                        // Create an associative array for each child
                        $childrenArray[] = [
                            'id' => $child->getID(),
                            'first_name' => $child->getFirstName(),
                            'last_name' => $child->getLastName(),
                            'gender' => $child->getGender(),
                            'school_name' => $child->getSchool(),
                            'grade' => $child->getGrade(),
                            'birthdate' => $child->getBirthdate(),
                            'address' => $child->getAddress(),
                            'city' => $child->getCity(),
                            'state' => $child->getState(),
                            'zip' => $child->getZip()
                        ];
                    }
                    echo json_encode($childrenArray); 
                ?>;
                // This function will be triggered when the user selects a child
                function autofillForm(childID) {
                    // Extract the child data from the selected child's ID
                    const selectedChild = childrenData.find(child => child.id == childID);

                    if (selectedChild) {
                        // Autofill the form fields
                        document.getElementById('child_last_name').value = selectedChild.last_name;
                        document.getElementById('gender').value = selectedChild.gender;
                        document.getElementById('school_name').value = selectedChild.school_name;
                        document.getElementById('grade').value = selectedChild.grade;
                        document.getElementById('birthdate').value = selectedChild.birthdate;
                        document.getElementById('child_address').value = selectedChild.address;
                        document.getElementById('child_city').value = selectedChild.city;
                        document.getElementById('child_state').value = selectedChild.state;
                        document.getElementById('child_zip').value = selectedChild.zip;
                        // You can add more fields here as necessary
                    }
                }
            </script>
        </form>
        </div>
        <?php
            // if submission successful, create pop up notification and direct user back to fill form page
            // if fail, notify user on program interest form page
            if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($bbID)){
                echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
            } else if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($bbID)) {
                echo '<script>document.location = "brainBuildersRegistrationForm.php?formSubmitFail";</script>';
            }
        ?>
    </body>
</html>