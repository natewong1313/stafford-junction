<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

if (isset($_SESSION['_id'])) {
    require_once('database/dbFamily.php');
    require_once('include/input-validation.php');
    require_once("domain/Family.php");
    
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
    
    // Get the family ID from the GET parameter or default to the logged-in user's ID
    $familyId = $_GET['id'] ?? $userID;
    
    // Retrieve the selected family's profile
    $family = retrieve_family_by_id($familyId);
    $languages = retrieve_family_langauges($familyId);
    $assistances = retrieve_family_assistance($familyId);
} else {
    header('Location: login.php');
    die();
}

// Get field values to auto-populate
$first_name = $family->getFirstName();
$last_name = $family->getLastName();
$birthdate = $family->getBirthDate();
$address = $family->getAddress();
$city = $family->getCity();
$state = $family->getState();
$zip = $family->getZip();
$neighborhood = $family->getNeighborhood();
$email = $family->getEmail();
$phone = $family->getPhone();
$phone_type = $family->getPhoneType();
$secondary_phone = $family->getSecondaryPhone();
$secondary_phone_type = $family->getSecondaryPhoneType();
$isHispanic = $family->isHispanic();
$race = $family->getRace();
$income = $family->getIncome();

$first_name2 = $family->getFirstName2();
$last_name2 = $family->getLastName2();
$birthdate2 = $family->getBirthDate2();
$address2 = $family->getAddress2();
$city2 = $family->getCity2();
$state2 = $family->getState2();
$zip2 = $family->getZip2();
$neighborhood2 = $family->getNeighborhood2();
$email2 = $family->getEmail2();
$phone2 = $family->getPhone2();
$phone_type2 = $family->getPhoneType2();
$secondary_phone2 = $family->getSecondaryPhone2();
$secondary_phone_type2 = $family->getSecondaryPhoneType2();
$isHispanic2 = $family->isHispanic2();
$race2 = $family->getRace2();

$econtact_first_name = $family->getEContactFirstName();
$econtact_last_name = $family->getEContactLastName();
$econtact_phone = $family->getEContactPhone();
$econtact_relation = $family->getEContactRelation();

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $args = sanitize($_POST);
    $success = update_profile($args, $familyId);
    
    if ($accessLevel > 1) {
        header("Location: index.php");
    } else {
        header("Location: familyAccountDashboard.php");
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Edit Profile</title>
    </head>
    <body>
        <h1>Edit Profile</h1>
        <main class="signup-form">
            <form class="signup-form" method="post">
                <h2>Edit Profile</h2>

                <h3>Primary Parent / Guardian</h3>
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($first_name); ?>">

                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($last_name); ?>">

                    <label for="birthdate">Date of Birth</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" max="<?php echo date('Y-m-d'); ?>">

                    <label for="address">Street Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">

                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>">

                    <label for="state">State</label>
                    <select id="state" name="state">
                        <option value="--" readonly <?php echo ($state == '--') ? 'selected' : ''; ?>>Select State</option>
                        <option value="AL" <?php echo ($state == 'AL') ? 'selected' : ''; ?>>Alabama</option>
                        <option value="AK" <?php echo ($state == 'AK') ? 'selected' : ''; ?>>Alaska</option>
                        <option value="AZ" <?php echo ($state == 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                        <option value="AR" <?php echo ($state == 'AR') ? 'selected' : ''; ?>>Arkansas</option>
                        <option value="CA" <?php echo ($state == 'CA') ? 'selected' : ''; ?>>California</option>
                        <option value="CO" <?php echo ($state == 'CO') ? 'selected' : ''; ?>>Colorado</option>
                        <option value="CT" <?php echo ($state == 'CT') ? 'selected' : ''; ?>>Connecticut</option>
                        <option value="DE" <?php echo ($state == 'DE') ? 'selected' : ''; ?>>Delaware</option>
                        <option value="DC" <?php echo ($state == 'DC') ? 'selected' : ''; ?>>District Of Columbia</option>
                        <option value="FL" <?php echo ($state == 'FL') ? 'selected' : ''; ?>>Florida</option>
                        <option value="GA" <?php echo ($state == 'GA') ? 'selected' : ''; ?>>Georgia</option>
                        <option value="HI" <?php echo ($state == 'HI') ? 'selected' : ''; ?>>Hawaii</option>
                        <option value="ID" <?php echo ($state == 'ID') ? 'selected' : ''; ?>>Idaho</option>
                        <option value="IL" <?php echo ($state == 'IL') ? 'selected' : ''; ?>>Illinois</option>
                        <option value="IN" <?php echo ($state == 'IN') ? 'selected' : ''; ?>>Indiana</option>
                        <option value="IA" <?php echo ($state == 'IA') ? 'selected' : ''; ?>>Iowa</option>
                        <option value="KS" <?php echo ($state == 'KS') ? 'selected' : ''; ?>>Kansas</option>
                        <option value="KY" <?php echo ($state == 'KY') ? 'selected' : ''; ?>>Kentucky</option>
                        <option value="LA" <?php echo ($state == 'LA') ? 'selected' : ''; ?>>Louisiana</option>
                        <option value="ME" <?php echo ($state == 'ME') ? 'selected' : ''; ?>>Maine</option>
                        <option value="MD" <?php echo ($state == 'MD') ? 'selected' : ''; ?>>Maryland</option>
                        <option value="MA" <?php echo ($state == 'MA') ? 'selected' : ''; ?>>Massachusetts</option>
                        <option value="MI" <?php echo ($state == 'MI') ? 'selected' : ''; ?>>Michigan</option>
                        <option value="MN" <?php echo ($state == 'MN') ? 'selected' : ''; ?>>Minnesota</option>
                        <option value="MS" <?php echo ($state == 'MS') ? 'selected' : ''; ?>>Mississippi</option>
                        <option value="MO" <?php echo ($state == 'MO') ? 'selected' : ''; ?>>Missouri</option>
                        <option value="MT" <?php echo ($state == 'MT') ? 'selected' : ''; ?>>Montana</option>
                        <option value="NE" <?php echo ($state == 'NE') ? 'selected' : ''; ?>>Nebraska</option>
                        <option value="NV" <?php echo ($state == 'NV') ? 'selected' : ''; ?>>Nevada</option>
                        <option value="NH" <?php echo ($state == 'NH') ? 'selected' : ''; ?>>New Hampshire</option>
                        <option value="NJ" <?php echo ($state == 'NJ') ? 'selected' : ''; ?>>New Jersey</option>
                        <option value="NM" <?php echo ($state == 'NM') ? 'selected' : ''; ?>>New Mexico</option>
                        <option value="NY" <?php echo ($state == 'NY') ? 'selected' : ''; ?>>New York</option>
                        <option value="NC" <?php echo ($state == 'NC') ? 'selected' : ''; ?>>North Carolina</option>
                        <option value="ND" <?php echo ($state == 'ND') ? 'selected' : ''; ?>>North Dakota</option>
                        <option value="OH" <?php echo ($state == 'OH') ? 'selected' : ''; ?>>Ohio</option>
                        <option value="OK" <?php echo ($state == 'OK') ? 'selected' : ''; ?>>Oklahoma</option>
                        <option value="OR" <?php echo ($state == 'OR') ? 'selected' : ''; ?>>Oregon</option>
                        <option value="PA" <?php echo ($state == 'PA') ? 'selected' : ''; ?>>Pennsylvania</option>
                        <option value="RI" <?php echo ($state == 'RI') ? 'selected' : ''; ?>>Rhode Island</option>
                        <option value="SC" <?php echo ($state == 'SC') ? 'selected' : ''; ?>>South Carolina</option>
                        <option value="SD" <?php echo ($state == 'SD') ? 'selected' : ''; ?>>South Dakota</option>
                        <option value="TN" <?php echo ($state == 'TN') ? 'selected' : ''; ?>>Tennessee</option>
                        <option value="TX" <?php echo ($state == 'TX') ? 'selected' : ''; ?>>Texas</option>
                        <option value="UT" <?php echo ($state == 'UT') ? 'selected' : ''; ?>>Utah</option>
                        <option value="VT" <?php echo ($state == 'VT') ? 'selected' : ''; ?>>Vermont</option>
                        <option value="VA" <?php echo ($state == 'VA') ? 'selected' : ''; ?>>Virginia</option>
                        <option value="WA" <?php echo ($state == 'WA') ? 'selected' : ''; ?>>Washington</option>
                        <option value="WV" <?php echo ($state == 'WV') ? 'selected' : ''; ?>>West Virginia</option>
                        <option value="WI" <?php echo ($state == 'WI') ? 'selected' : ''; ?>>Wisconsin</option>
                        <option value="WY" <?php echo ($state == 'WY') ? 'selected' : ''; ?>>Wyoming</option>
                    </select>
                    <label for="zip">Zip Code</label>
                    <input type="number" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" value="<?php echo htmlspecialchars($zip); ?>">
                </fieldset>

                <fieldset>
                    <legend>Primary Contact Information</legend>

                    <label for="email">E-mail</label>
                    <p><b><i>*Changing this email will also change your login username/email.*</i></b></p>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

                    <label for="phone">Primary Phone Number</label>
                    <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($phone); ?>">

                    <label>Primary Phone Type</label>
                    <div class="radio-group" value="<?php echo htmlspecialchars($phone_type); ?>">
                        <input type="radio" id="phone-type-cellphone" name="phone-type" value="cellphone" <?php echo ($phone_type === 'cellphone') ? 'checked' : ''; ?>>
                        <label for="phone-type-cellphone">Cell</label>
                        <input type="radio" id="phone-type-home" name="phone-type" value="home" <?php echo ($phone_type === 'home') ? 'checked' : ''; ?>>
                        <label for="phone-type-home">Home</label>
                        <input type="radio" id="phone-type-work" name="phone-type" value="work" <?php echo ($phone_type === 'work') ? 'checked' : ''; ?>>
                        <label for="phone-type-work">Work</label>
                    </div>

                    <label for="secondary-phone">Secondary Phone Number</label>
                    <input type="tel" id="secondary-phone" name="secondary-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($secondary_phone); ?>">

                    <label>Secondary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="secondary-phone-type-cellphone" name="secondary-phone-type" value="cellphone" <?php echo ($secondary_phone_type === 'cellphone') ? 'checked' : ''; ?>>
                        <label for="secondary-phone-type-cellphone">Cell</label>
                        <input type="radio" id="secondary-phone-type-home" name="secondary-phone-type" value="home" <?php echo ($secondary_phone_type === 'home') ? 'checked' : ''; ?>>
                        <label for="secondary-phone-type-home">Home</label>
                        <input type="radio" id="secondary-phone-type-work" name="secondary-phone-type" value="work" <?php echo ($secondary_phone_type === 'work') ? 'checked' : ''; ?>>
                        <label for="secondary-phone-type-work">Work</label>
                    </div>
                </fieldset>

                <h3>Secondary Parent / Guardian</h3>
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="first-name2">First Name</label>
                    <input type="text" id="first-name2" name="first-name2" value="<?php echo htmlspecialchars($first_name2); ?>">

                    <label for="last-name2">Last Name</label>
                    <input type="text" id="last-name2" name="last-name2" value="<?php echo htmlspecialchars($last_name2); ?>">

                    <label for="birthdate2">Date of Birth</label>
                    <input type="date" id="birthdate2" name="birthdate2" value="<?php echo htmlspecialchars($birthdate2); ?>" max="<?php echo date('Y-m-d'); ?>">

                    <label for="neighborhood2">Neighborhood</label>
                    <input type="text" id="neighborhood2" name="neighborhood2" value="<?php echo htmlspecialchars($neighborhood2); ?>">
                    
                    <label for="address2">Street Address</label>
                    <input type="text" id="address2" name="address2" value="<?php echo htmlspecialchars($address2); ?>">

                    <label for="city2">City</label>
                    <input type="text" id="city2" name="city2" value="<?php echo htmlspecialchars($city2); ?>">

                    <label for="state2">State</label>
                    <select id="state2" name="state2">
                        <option value="--" <?php echo ($state2 == '--' || empty($state2)) ? 'selected' : ''; ?> readonly>Select State</option>
                        <option value="AL" <?php echo ($state2 == 'AL') ? 'selected' : ''; ?>>Alabama</option>
                        <option value="AK" <?php echo ($state2 == 'AK') ? 'selected' : ''; ?>>Alaska</option>
                        <option value="AZ" <?php echo ($state2 == 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                        <option value="AR" <?php echo ($state2 == 'AR') ? 'selected' : ''; ?>>Arkansas</option>
                        <option value="CA" <?php echo ($state2 == 'CA') ? 'selected' : ''; ?>>California</option>
                        <option value="CO" <?php echo ($state2 == 'CO') ? 'selected' : ''; ?>>Colorado</option>
                        <option value="CT" <?php echo ($state2 == 'CT') ? 'selected' : ''; ?>>Connecticut</option>
                        <option value="DE" <?php echo ($state2 == 'DE') ? 'selected' : ''; ?>>Delaware</option>
                        <option value="DC" <?php echo ($state2 == 'DC') ? 'selected' : ''; ?>>District Of Columbia</option>
                        <option value="FL" <?php echo ($state2 == 'FL') ? 'selected' : ''; ?>>Florida</option>
                        <option value="GA" <?php echo ($state2 == 'GA') ? 'selected' : ''; ?>>Georgia</option>
                        <option value="HI" <?php echo ($state2 == 'HI') ? 'selected' : ''; ?>>Hawaii</option>
                        <option value="ID" <?php echo ($state2 == 'ID') ? 'selected' : ''; ?>>Idaho</option>
                        <option value="IL" <?php echo ($state2 == 'IL') ? 'selected' : ''; ?>>Illinois</option>
                        <option value="IN" <?php echo ($state2 == 'IN') ? 'selected' : ''; ?>>Indiana</option>
                        <option value="IA" <?php echo ($state2 == 'IA') ? 'selected' : ''; ?>>Iowa</option>
                        <option value="KS" <?php echo ($state2 == 'KS') ? 'selected' : ''; ?>>Kansas</option>
                        <option value="KY" <?php echo ($state2 == 'KY') ? 'selected' : ''; ?>>Kentucky</option>
                        <option value="LA" <?php echo ($state2 == 'LA') ? 'selected' : ''; ?>>Louisiana</option>
                        <option value="ME" <?php echo ($state2 == 'ME') ? 'selected' : ''; ?>>Maine</option>
                        <option value="MD" <?php echo ($state2 == 'MD') ? 'selected' : ''; ?>>Maryland</option>
                        <option value="MA" <?php echo ($state2 == 'MA') ? 'selected' : ''; ?>>Massachusetts</option>
                        <option value="MI" <?php echo ($state2 == 'MI') ? 'selected' : ''; ?>>Michigan</option>
                        <option value="MN" <?php echo ($state2 == 'MN') ? 'selected' : ''; ?>>Minnesota</option>
                        <option value="MS" <?php echo ($state2 == 'MS') ? 'selected' : ''; ?>>Mississippi</option>
                        <option value="MO" <?php echo ($state2 == 'MO') ? 'selected' : ''; ?>>Missouri</option>
                        <option value="MT" <?php echo ($state2 == 'MT') ? 'selected' : ''; ?>>Montana</option>
                        <option value="NE" <?php echo ($state2 == 'NE') ? 'selected' : ''; ?>>Nebraska</option>
                        <option value="NV" <?php echo ($state2 == 'NV') ? 'selected' : ''; ?>>Nevada</option>
                        <option value="NH" <?php echo ($state2 == 'NH') ? 'selected' : ''; ?>>New Hampshire</option>
                        <option value="NJ" <?php echo ($state2 == 'NJ') ? 'selected' : ''; ?>>New Jersey</option>
                        <option value="NM" <?php echo ($state2 == 'NM') ? 'selected' : ''; ?>>New Mexico</option>
                        <option value="NY" <?php echo ($state2 == 'NY') ? 'selected' : ''; ?>>New York</option>
                        <option value="NC" <?php echo ($state2 == 'NC') ? 'selected' : ''; ?>>North Carolina</option>
                        <option value="ND" <?php echo ($state2 == 'ND') ? 'selected' : ''; ?>>North Dakota</option>
                        <option value="OH" <?php echo ($state2 == 'OH') ? 'selected' : ''; ?>>Ohio</option>
                        <option value="OK" <?php echo ($state2 == 'OK') ? 'selected' : ''; ?>>Oklahoma</option>
                        <option value="OR" <?php echo ($state2 == 'OR') ? 'selected' : ''; ?>>Oregon</option>
                        <option value="PA" <?php echo ($state2 == 'PA') ? 'selected' : ''; ?>>Pennsylvania</option>
                        <option value="RI" <?php echo ($state2 == 'RI') ? 'selected' : ''; ?>>Rhode Island</option>
                        <option value="SC" <?php echo ($state2 == 'SC') ? 'selected' : ''; ?>>South Carolina</option>
                        <option value="SD" <?php echo ($state2 == 'SD') ? 'selected' : ''; ?>>South Dakota</option>
                        <option value="TN" <?php echo ($state2 == 'TN') ? 'selected' : ''; ?>>Tennessee</option>
                        <option value="TX" <?php echo ($state2 == 'TX') ? 'selected' : ''; ?>>Texas</option>
                        <option value="UT" <?php echo ($state2 == 'UT') ? 'selected' : ''; ?>>Utah</option>
                        <option value="VT" <?php echo ($state2 == 'VT') ? 'selected' : ''; ?>>Vermont</option>
                        <option value="VA" <?php echo ($state2 == 'VA') ? 'selected' : ''; ?>>Virginia</option>
                        <option value="WA" <?php echo ($state2 == 'WA') ? 'selected' : ''; ?>>Washington</option>
                        <option value="WV" <?php echo ($state2 == 'WV') ? 'selected' : ''; ?>>West Virginia</option>
                        <option value="WI" <?php echo ($state2 == 'WI') ? 'selected' : ''; ?>>Wisconsin</option>
                        <option value="WY" <?php echo ($state2 == 'WY') ? 'selected' : ''; ?>>Wyoming</option>
                    </select>

                    <label for="zip2">Zip Code</label>
                    <input type="number" id="zip2" name="zip2" pattern="[0-9]{5}" title="5-digit zip code" value="<?php echo htmlspecialchars($zip2); ?>">

                    <label for="isHispanic2">Hispanic, Latino, or Spanish Origin</label>
                    <select id="isHispanic2" name="isHispanic2">
                        <option value="" <?php echo (empty($isHispanic2)) ? 'selected' : ''; ?> readonly>Select Yes or No</option>
                        <option value="1" <?php echo ($isHispanic2 == '1') ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($isHispanic2 == '2') ? 'selected' : ''; ?>>No</option>
                    </select>

                    <label for="race2" required>Race</label>
                    <select id="race2" name="race2">
                        <option value="" <?php echo (empty($race2)) ? 'selected' : ''; ?> readonly>Select Race</option>
                        <option value="Caucasian" <?php echo ($race2 == 'Caucasian') ? 'selected' : ''; ?>>Caucasian</option>
                        <option value="Black/African American" <?php echo ($race2 == 'Black/African American') ? 'selected' : ''; ?>>Black/African American</option>
                        <option value="Native Indian/Alaska Native" <?php echo ($race2 == 'Native Indian/Alaska Native') ? 'selected' : ''; ?>>Native Indian/Alaska Native</option>
                        <option value="Native Hawaiian/Pacific Islander" <?php echo ($race2 == 'Native Hawaiian/Pacific Islander') ? 'selected' : ''; ?>>Native Hawaiian/Pacific Islander</option>
                        <option value="Asian" <?php echo ($race2 == 'Asian') ? 'selected' : ''; ?>>Asian</option>
                        <option value="Multiracial" <?php echo ($race2 == 'Multiracial') ? 'selected' : ''; ?>>Multiracial</option>
                        <option value="Other" <?php echo ($race2 == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </fieldset>
                <fieldset>
                    <legend>Secondary Contact Information</legend>
                    <label for="email2">E-mail</label>
                    <input type="email" id="email2" name="email2" value="<?php echo htmlspecialchars($email2); ?>">

                    <label for="phone2">Primary Phone Number</label>
                    <input type="tel" id="phone2" name="phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($phone2); ?>">

                    <label>Primary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="phone-type-cellphone2" name="phone-type2" value="cellphone" <?php echo ($phone_type2 === 'cellphone') ? 'checked' : ''; ?>>
                        <label for="phone-type-cellphone2">Cell</label>
                        <input type="radio" id="phone-type-home2" name="phone-type2" value="home" <?php echo ($phone_type2 === 'home') ? 'checked' : ''; ?>>
                        <label for="phone-type-home2">Home</label>
                        <input type="radio" id="phone-type-work2" name="phone-type2" value="work" <?php echo ($phone_type2 === 'work') ? 'checked' : ''; ?>>
                        <label for="phone-type-work2">Work</label>
                    </div>

                    <label for="secondary-phone2">Secondary Phone Number</label>
                    <input type="tel" id="secondary-phone2" name="secondary-phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($secondary_phone2); ?>">

                    <label>Secondary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="secondary-phone-type-cellphone2" name="secondary-phone-type2" value="cellphone" <?php echo ($secondary_phone_type2 === 'cellphone') ? 'checked' : ''; ?>>
                        <label for="secondary-phone-type-cellphone2">Cell</label>
                        <input type="radio" id="secondary-phone-type-home2" name="secondary-phone-type2" value="home" <?php echo ($secondary_phone_type2 === 'home') ? 'checked' : ''; ?>>
                        <label for="secondary-phone-type-home2">Home</label>
                        <input type="radio" id="secondary-phone-type-work2" name="secondary-phone-type2" value="work" <?php echo ($secondary_phone_type2 === 'work') ? 'checked' : ''; ?>>
                        <label for="secondary-phone-type-work2">Work</label>
                    </div>
                </fieldset>

                <h3>Emergency Contact</h3>
                <fieldset>
                    <label for="econtact-first-name" >Contact First Name</label>
                    <input type="text" id="econtact-first-name" name="econtact-first-name" value="<?php echo htmlspecialchars($econtact_first_name); ?>">

                    <label for="econtact-last-name" >Contact Last Name</label>
                    <input type="text" id="econtact-last-name" name="econtact-last-name" value="<?php echo htmlspecialchars($econtact_last_name); ?>">

                    <label for="econtact-phone" >Contact Phone Number</label>
                    <input type="tel" id="econtact-phone" name="econtact-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($econtact_phone); ?>">

                    <label for="econtact-name" >Contact Relation to You</label>
                    <input type="text" id="econtact-relation" name="econtact-relation" value="<?php echo htmlspecialchars($econtact_relation); ?>">
                </fieldset>

                <h3>Household Information</h3>
                <fieldset>
                <label for="languages">* Languages Spoken in Household</label>
                <input type="text" id="languages" name="languages" value="<?php echo htmlspecialchars(implode(', ', $languages)); ?>">

                    <label for="econtact-last-name" >Contact Last Name</label>
                    <input type="text" id="econtact-last-name" name="econtact-last-name" value="<?php echo htmlspecialchars($econtact_last_name); ?>">

                    <label for="econtact-phone" >Contact Phone Number</label>
                    <input type="tel" id="econtact-phone" name="econtact-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($econtact_phone); ?>">

                    <label for="econtact-name" >Contact Relation to You</label>

                <input type="submit" name="profile-edit-form" value="Update Profile">
            </form>
            <a class="button cancel" 
                href="<?php echo $accessLevel > 1 ? 'index.php' : 'familyAccountDashboard.php'; ?>" 
                style="margin-top: .5rem">
                Cancel
            </a>
        </main>
    </body>
</html>