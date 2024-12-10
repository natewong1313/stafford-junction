<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = null;

function data_dump($val){
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
    die();
}

if(isset($_SESSION['_id'])){
    require_once("database/dbFamily.php");
    require_once("database/dbChildren.php");
    require_once('database/dbBrainBuildersRegistration.php');
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
    $family = retrieve_family_by_id($_GET['id'] ?? $userID);
    $children = retrieve_children_by_family_id($_GET['id'] ?? $userID);
    //data_dump($children);
    $address = $family->getAddress();
    $city = $family->getCity();
    $phone = $family->getPhone();
    $zip = $family->getZip();
    $email = $family->getEmail();
    $emergency_contact_name = $family->getEContactFirstName() . " " . $family->getEContactLastName();
    $econtactRelation = $family->getEContactRelation();
    $econtactPhone = $family->getEContactPhone();
    
    $parent2Name = null;
}

// include the header .php files
if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('include/input-validation.php');
    require_once('database/dbChildren.php');
    require_once('database/dbBrainBuildersRegistration.php');
    $args = sanitize($_POST, null);
    $n = explode(" ", $args['name']);
    //data_dump($n);
    //$childToRegister = retrieve_child_by_firstName_lastName_famID($args['child-first-name'], $args['child-last-name'], $_GET['id'] ?? $userID);
    $childToRegister = retrieve_child_by_firstName_lastName_famID($n[0], $n[1], $_GET['id'] ?? $userID);
    $success = register($args, $childToRegister['id']);
}
?>

<html>
<head>
    <!-- Include universal styles formatting -->
    <?php include_once("universal.inc") ?>
    <title>Stafford Junction | Brain Builders Student Registration Form</title>
</head>
    <body>
    <h1>Brain Builders Registration Form 2024-2025</h1>
        <div id="formatted_form">
            
            <p><b>* Indicates a required field</b></p><br>

            <h2>Student Information</h2><br>
            <form id="brainBuildersStudentRegistrationForm" action="" method="post">             
                <!--Child First Name-->
                <!--
                <label for="child-first-name">Child First Name *</label><br><br>
                <input type="text" name="child-first-name" id="child-first-name" required placeholder="Child First Name" required><br><br>
                -->
                <!--Child Last Name-->
                <!--
                <label for="child-last-name">Child Last Name *</label><br><br>
                <input type="text" name="child-last-name" id="child-last-name" required placeholder="Child Last Name" required><br><br>
                -->

                <!-- Child Name -->
                <label for="name">Child Name / Nombre del Hijo*</label><br><br>
                <select name="name" id="name" required>
                <?php
                foreach ($children as $c){ //cycle through each child of family account user
                    $id = $c->getID();
                    // Check if form was already completed for the child
                    if (!isBrainBuildersRegistrationComplete($id)) {
                        $name = $c->getFirstName() . " " . $c->getLastName(); //display name if they don't have a form filled out for them
                        //$value = $id . "_" . $name;
                        echo "<option>$name</option>";
                    }
                }
                ?>
                </select>


                <!--Gender-->
                <label for="gender">Gender *</label><br><br>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                <br><br>

                <!--School Name-->
                <label for="school-name">School Name *</label><br><br>
                <input type="text" name="school-name" id="school-name" required placeholder="School Name" required><br><br>

                <!--Grade-->
                <label for="grade">Grade *</label><br><br>
                <input type="text" name="grade" id="grade" required placeholder="Grade/Grado" required><br><br>

                <!--Date of Birth-->
                <label for="birthdate">Date of Birth *</label><br><br>
                <input type="date" id="birthdate" name="birthdate" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>"><br><br>

                <!--Street Address-->
                <label for0="child-address">Street Address *</label><br><br>
                <input type="text" id="child-address" name="child-address" required placeholder="Enter your street address"><br><br>

                <!--City-->
                <label for="child-city">City *</label><br><br>
                <input type="text" id="child-city" name="child-city" required placeholder="Enter your city"><br><br>

                <!--State-->
                <label for="child-state">State *</label><br><br>
                <select id="child-state" name="child-state" required>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA" selected>Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select><br><br>

                <!--Zip-->
                <label for="child-zip" required>Zip Code *</label><br><br>
                <input type="text" id="child-zip" name="child-zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code"><br><br>

                <!--Medical issues or allergies-->
                <label for="child-medical-allergies" required>Medical issues or allergies</label><br><br>
                <input type="text" id="child-medical-allergies" name="child-medical-allergies" placeholder="Medical issues or allergies"><br><br>

                <!--Foods to avoid due to religious beliefs-->
                <label for="child-food-avoidances" required>Foods to avoid due to religious beliefs</label><br><br>
                <input type="text" id="child-food-avoidances" name="child-food-avoidances" placeholder="Foods to avoid due to religious beliefs"><br><br>

            <h2>General Information</h2><br>

            <h3>Parent 1</h3>
            <br>
                <!--Parent 1 Name-->
                <label for="parent1-name">Full Name *</label><br><br>
                <input type="text" id="parent1-name" name="parent1-name" required placeholder="Parent 1 Full Name" value="<?php echo htmlspecialchars($family->getFirstName() . " " . $family->getLastName()); ?>"><br><br>

                <!--Cell Phone-->
                <label for="parent1-phone">Primary Phone Number *</label><br><br>
                <input type="tel" id="parent1-phone" name="parent1-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555" value="<?php echo htmlspecialchars($phone); ?>"><br><br>

                <!--Street Address-->
                <label for0="parent1-address">Street Address *</label><br><br>
                <input type="text" id="parent1-address" name="parent1-address" required placeholder="Enter your street address" value="<?php echo htmlspecialchars($address); ?>"><br><br>

                <!--City-->
                <label for="parent1-city">City *</label><br><br>
                <input type="text" id="parent1-city" name="parent1-city" required placeholder="Enter your city" value="<?php echo htmlspecialchars($city); ?>"><br><br>

                <!--State-->
                <label for="parent1-state">State *</label><br><br>
                <select id="parent1-state" name="parent1-state" required>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA" selected>Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select><br><br>

                <!--Zip-->
                <label for="parent1-zip">Zip Code *</label><br><br>
                <input type="text" id="parent1-zip" name="parent1-zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code" value="<?php echo htmlspecialchars($zip); ?>"><br><br>

                <!--Email-->
                <label for="parent1-email">Email *</label><br><br>
                <input type="text" id="parent1-email" name="parent1-email" required placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>"><br><br>

                <!--Alternate Phone-->
                <label for="parent1-altPhone">Alternate Phone Number</label><br><br>
                <input type="tel" id="parent1-altPhone" name="parent1-altPhone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

            <h3>Parent 2</h3>
            <br>
                <!--Parent 2 Name-->
                <label for="parent2-name">Full Name</label><br><br>
                <input type="text" id="parent2-name" name="parent2-name" placeholder="Parent 2 Full Name" value="<?php echo htmlspecialchars($family->getFirstName2() . " " . $family->getLastName2() ?? ""); ?>"><br><br>

                <!--Cell Phone-->
                <label for="parent2-phone">Primary Phone Number</label><br><br>
                <input type="tel" id="parent2-phone" name="parent2-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555" value="<?php echo htmlspecialchars($family->getPhone2() ?? ""); ?>"><br><br>

                <!--Street Address-->
                <label for0="parent2-address">Street Address</label><br><br>
                <input type="text" id="parent2-address" name="parent2-address" placeholder="Enter your street address" value="<?php echo htmlspecialchars($family->getAddress2() ?? ""); ?>"><br><br>

                <!--City-->
                <label for="parent2-city">City</label><br><br>
                <input type="text" id="parent2-city" name="parent2-city" placeholder="Enter your city" value="<?php echo htmlspecialchars($family->getCity2()); ?>"><br><br>

                <!--State-->
                <label for="parent2-state">State</label><br><br>
                <select id="parent2-state" name="parent2-state">
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA" selected>Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select><br><br>

                <!--Zip-->
                <label for="parent2-zip" required>Zip Code</label><br><br>
                <input type="text" id="parent2-zip" name="parent2-zip" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code" value="<?php echo htmlspecialchars($family->getZip2()); ?>"><br><br>

                <!--Email-->
                <label for="parent2-email" required>Email</label><br><br>
                <input type="text" id="parent2-email" name="parent2-email" placeholder="Enter your city" value="<?php echo htmlspecialchars($family->getEmail2()); ?>"><br><br>

                <!--Alternate Phone-->
                <label for="parent2-altPhone" required>Alternate Phone Number</label><br><br>
                <input type="tel" id="parent2-altPhone" name="parent2-altPhone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

            <h2>Emergency Contact and Pick-Up Information</h2><br>

            <h3>Emergency Contact 1</h3><br>

                <!--Name-->
                <label for="emergency-name1" required>Full Name *</label><br><br>
                <input type="text" id="emergency-name1" name="emergency-name1" required placeholder="Enter full name" value="<?php echo htmlspecialchars($emergency_contact_name); ?>"><br><br>

                <!--Relationship-->
                <label for="emergency-relationship1" required>Relationship to Child *</label><br><br>
                <input type="text" id="emergency-relationship1" name="emergency-relationship1" required placeholder="Enter person's relationship to child"><br><br>

                <!--Phone-->
                <label for="emergency-phone1" required>Phone *</label><br><br>
                <input type="tel" id="emergency-phone1" name="emergency-phone1" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555" value="<?php echo htmlspecialchars($econtactPhone); ?>"><br><br>

                <h3>Emergency Contact 2</h3><br>

                <!--Name-->
                <label for="emergency-name2" required>Full Name</label><br><br>
                <input type="text" id="emergency-name2" name="emergency-name2" placeholder="Enter full name"><br><br>

                <!--Relationship-->
                <label for="emergency-relationship2" required>Relationship to Child</label><br><br>
                <input type="text" id="emergency-relationship2" name="emergency-relationship2" placeholder="Enter person's relationship to child"><br><br>

                <!--Phone-->
                <label for="emergency-phone2" required>Phone</label><br><br>
                <input type="tel" id="emergency-phone2" name="emergency-phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

                <p>------</p><br><br>

                <!--Persons Authorized for Pick-Up-->
                <label for="authorized-pu" required>Persons authorized to pick up child *</label><br><br>
                <input type="text" id="authorized-pu" name="authorized-pu" required placeholder="Enter persons names"><br><br>
                
                <!--Persons NOT Authorized for Pick-Up-->
                <label for="not-authorized-pu" required>Persons <b><u>NOT</u></b> authorized to pick up child</label><br><br>
                <input type="text" id="not-authorized-pu" name="not-authorized-pu" placeholder="Enter persons names"><br><br>

            <h2>Additional Required Information</h2>
            <p>This information is for Stafford Junction funding purposes only.</p><br>

                <!--Parent's Primary Language-->
                <label for="primary-language" required>Primary Language *</label><br><br>
                <input type="text" id="primary-language" name="primary-language" required placeholder="English, Spanish, Farsi, etc."><br><br>

                <!--Hispanic, Latino, or Spanish Origin-->
                <label for="hispanic-latino-spanish">Hispanic, Latino, or Spanish Origin *</label><br><br>
                <select id="hispanic-latino-spanish" name="hispanic-latino-spanish" required>
                    <option value="" disabled selected>Select Yes or No</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <br><br>

                <!--Race-->
                <label for="race" required>Race *</label><br><br>
                <select id="race" name="race" required>
                    <option value="" disabled selected>Select Race</option>
                    <option value="Caucasian">Caucasian</option>
                    <option value="Black/African American">Black/African American</option>
                    <option value="Native Indian/Alaska Native">Native Indian/Alaska Native</option>
                    <option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander</option>
                    <option value="Asian">Asian</option>
                    <option value="Multiracial">Multiracial</option>
                    <option value="Other">Other</option>
                </select><br><br>

                <!--Num Unemployed in Household-->
                <label for="num-unemployed" required>Number of Unemployed in Household *</label><br><br>
                <input type="number" id="num-unemployed" name="num-unemployed" required placeholder="Enter number of unemployed"><br><br>

                <!--Num Retired in Household-->
                <label for="num-retired" required>Number of Retired in Household *</label><br><br>
                <input type="number" id="num-retired" name="num-retired" required placeholder="Enter number of retired"><br><br>

                <!--Num Unemployed Student in Household-->
                <label for="num-unemployed-student" required>Number of Unemployed Students in Household *</label><br><br>
                <input type="number" id="num-unemployed-student" name="num-unemployed-student" required placeholder="Enter number of unemployed students"><br><br>

                <!--Num Employed Full-Time in Household-->
                <label for="num-employed-fulltime" required>Number of Full-Time Employed in Household *</label><br><br>
                <input type="number" id="num-employed-fulltime" name="num-employed-fulltime" required placeholder="Enter number of full-time employed"><br><br>

                <!--Num Employed Part-Time in Household-->
                <label for="num-employed-parttime" required>Number of Part-Time Employed in Household *</label><br><br>
                <input type="number" id="num-employed-parttime" name="num-employed-parttime" required placeholder="Enter number of part-time employed"><br><br>

                <!--Num Employed Student in Household-->
                <label for="num-employed-student" required>Number of Employed Students in Household *</label><br><br>
                <input type="number" id="num-employed-student" name="num-employed-student" required placeholder="Enter number of employed students"><br><br>


                <!--Estimated Household Income-->
                <label for="income">Estimated Household Income *</label><br><br>
                <select id="income" name="income" required>
                    <option value="" disabled selected>Select Estimated Income</option>
                    <option value="Under 20,000">Under 20,000</option>
                    <option value="20,000-40,000n">20,000-40,000</option>
                    <option value="40,001-60,000">40,001-60,000</option>
                    <option value="60,001-80,000">60,001-80,000</option>
                    <option value="Over 80,000">Over 80,000</option>
                </select><br><br>

                <!--Other Programs-->
                <label for="other-programs" required>Other Programs *</label><br><br>
                <input type="text" id="other-programs" name="other-programs" required placeholder="(WIC, SNAP, SSI, SSD, etc.)"><br><br>

                <!--Free/Reduced Lunch-->
                <label for="lunch">Does the enrolling child receive free or reduced lunch? *</label><br><br>
                <select id="lunch" name="lunch" required>
                    <option value="" disabled selected>Select</option>
                    <option value="free">Free</option>
                    <option value="reduced">Reduced</option>
                    <option value="neither">Neither</option>
                </select>
                <br><br>

            <h2>Transportion</h2><br>
                <p>Stafford Junction provides transportation home after the Brain Builders program. Please check one of the following:</p><br>
                
                <div class="radio-group">
                    <input type="radio" id="needs-transportation" name="needs-transportation" value="needs-transportation"><label for="phone-type-cellphone">My child has permission to be transported by Stafford Junction staff/volunteers in Stafford Junction vehicles.</label>
                    <input type="radio" id="transports-themselves" name="needs-transportation" value="transports-themselves"><label for="phone-type-home">I will make alternate arrangements for my child to be transported home.</label>
                </div>

            <br><br>

            <h2>Code of Conduct</h2><br>
                <p>Stafford Junction practices four core values: Caring, Honesty, Respect, and Responsibility. We are not a daycare
                service. The program is staffed by volunteers whose sole responsibility is to provide stimulating activities to youth,
                preventing summer learning loss. Misbehavior by students will not be tolerated.</p><br>

                <p>The standard disciplinary process is as follows: (1) verbal warning, (2) a second verbal warning and parents contacted,
                (3) dismissal from the program.</p><br>

                <p>Exceptions: If a student commits a serious infraction, the Youth Program Manager has the option to immediately
                dismiss the child from the program.</p><br><br>

            <h2>Photograph and Video Waiver</h2><br>
                <p>I acknowledge that Stafford Junction may utilize photographs or videos of participants that may be taken during
                involvement in Stafford Junction activities. This includes internal and external use, including but not limited to
                Stafford Junction’s website, Facebook, and publications. I consent to such uses and hereby waive all rights of
                compensation. If I do not wish the image of my child to be included in those mentioned above, it is my responsibility to
                inform them to exclude themselves from photographs or videos taken during such activities.</p><br><br>

            <h2>Other Activities & Programs</h2><br>
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
                <label for="parent-initials">Parent Initials *</label><br><br>
                <input type="text" name="parent-initials" id="parent-initials" required placeholder="Parent Initials" required><br><br>

            <h2>Acknowledgment and Consent</h2><br>
                <p>By signing below, I acknowledge, understand, accept, and agree to all policies and waivers stated and outlined in this
                Brain Builders Enrollment Form for the current school year.</p><br>
                <p>By electronically signing, you agree that your e-signature holds the same legal validity and effect as a handwritten signature.</p><br>

                <!--Parent/Guardian Electronic Signature-->
                <label for="signature">Parent/Guardian Signature *</label><br><br>
                <input type="text" name="signature" id="signature" required placeholder="Parent/Guardian Signature" required><br><br>

                <!--Date-->
                <label for="signature-date">Date *</label><br><br>
                <input type="date" id="signature-date" name="signature-date" required placeholder="Date" max="<?php echo date('Y-m-d'); ?>"><br><br>

            <h2>PERMISSION FOR MUTUAL RELEASE OF INFORMATION</h2><br>
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
                <label for="waiver-child-name">Child's Full Name *</label><br><br>
                <input type="text" name="waiver-child-name" id="waiver-child-name" required placeholder="Child's Full Name" required><br><br>

                <!--Child's Date of Birth-->
                <label for="waiver-dob">Child's Date of Birth *</label><br><br>
                <input type="date" id="waiver-dob" name="waiver-dob" required placeholder="Child's Date of Birth" max="<?php echo date('Y-m-d'); ?>"><br><br>

                <!--Parent/Guardian Name-->
                <label for="waiver-parent-name">Parent/Guardian Name *</label><br><br>
                <input type="text" name="waiver-parent-name" id="waiver-parent-name" required placeholder="Parent/Guardian Name" value="<?php echo htmlspecialchars($family->getFirstName() . " " . $family->getLastName()); ?>" required><br><br>

                <!--Provider's Name-->
                <label for="waiver-provider-name">Provider's Name *</label><br><br>
                <input type="text" name="waiver-provider-name" id="waiver-provider-name" required placeholder="Provider's Name" required><br><br>

                <!--Address-->
                <label for="waiver-provider-address">Provider's Address *</label><br><br>
                <input type="text" name="waiver-provider-address" id="waiver-provider-address" required placeholder="Provider's Address" required><br><br>

                <!--Phone & Fax-->
                <label for="waiver-phone-and-fax">Provider's Phone & Fax *</label><br><br>
                <input type="text" name="waiver-phone-and-fax" id="waiver-phone-and-fax" required placeholder="Provider's Phone & Fax" required><br><br>

                <!--Signature-->
                <label for="waiver-signature">Parent / Legal Guardian / Surrogate/ Eligible Student Signature *</label><br>
                <p>By electronically signing, you agree that your e-signature holds the same legal validity and effect as a handwritten signature.</p><br><br>
                <input type="text" name="waiver-signature" id="waiver-signature" required placeholder="Parent / Legal Guardian / Surrogate/ Eligible Student Signature" required><br><br>

                <!--Date-->
                <label for="waiver-date" required>Date *</label><br><br>
                <input type="date" id="waiver-date" name="waiver-date" required placeholder="Date" max="<?php echo date('Y-m-d'); ?>"><br><br>

                <button type="submit" id="submit">Submit</button>

                <?php
                    if($_SERVER['REQUEST_METHOD'] == "POST" && $success){
                        if (isset($_GET['id'])) {
                            echo '<script>document.location = "fillForm.php?formSubmitSuccess&id=' . $_GET['id'] . '";</script>';
                        } else {
                            echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
                        }
                    } 
                ?>

                <?php 
                if (isset($_GET['id'])) {
                    echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel</a>';
                } else {
                    echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>';
                }
                ?>
                <!--<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>-->
            </form>
        </div>

    </body>
</html>