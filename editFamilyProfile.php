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
} else {
    header('Location: login.php');
    die();
}

// Get field values to auto-populate
$first_name = $family->getFirstName();
$last_name = $family->getLastName();
$birthdate = $family->getBirthDate();
$neighborhood = $family->getNeighborhood();
$address = $family->getAddress();
$city = $family->getCity();
$state = $family->getState();
$zip = $family->getZip();
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
$neighborhood2 = $family->getNeighborhood2();
$address2 = $family->getAddress2();
$city2 = $family->getCity2();
$state2 = $family->getState2();
$zip2 = $family->getZip2();
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
                <h3>Primary Parent / Guardian</h3>
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="first-name" >First Name</label>
                    <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($first_name); ?>">

                    <label for="last-name" >Last Name</label>
                    <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($last_name); ?>">

                    <label for="birthdate" >Date of Birth</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" max="<?php echo date('Y-m-d'); ?>">

                    <label for="neighborhood" >Neighborhood</label>
                    <input type="text" id="neighborhood" name="neighborhood" value="<?php echo htmlspecialchars($neighborhood); ?>">

                    <label for="address" >Street Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">

                    <label for="city" >City</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>">

                    <label for="state">State </label>
                    <select name="state" id="state">
                        <option value="" disabled>Select State</option>
                        <option value="AL" <?php echo (isset($state) && $state == 'AL') ? 'selected' : ''; ?>>Alabama</option>
                        <option value="AK" <?php echo (isset($state) && $state == 'AK') ? 'selected' : ''; ?>>Alaska</option>
                        <option value="AZ" <?php echo (isset($state) && $state == 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                        <option value="AR" <?php echo (isset($state) && $state == 'AR') ? 'selected' : ''; ?>>Arkansas</option>
                        <option value="CA" <?php echo (isset($state) && $state == 'CA') ? 'selected' : ''; ?>>California</option>
                        <option value="CO" <?php echo (isset($state) && $state == 'CO') ? 'selected' : ''; ?>>Colorado</option>
                        <option value="CT" <?php echo (isset($state) && $state == 'CT') ? 'selected' : ''; ?>>Connecticut</option>
                        <option value="DE" <?php echo (isset($state) && $state == 'DE') ? 'selected' : ''; ?>>Delaware</option>
                        <option value="DC" <?php echo (isset($state) && $state == 'DC') ? 'selected' : ''; ?>>District of Columbia</option>
                        <option value="FL" <?php echo (isset($state) && $state == 'FL') ? 'selected' : ''; ?>>Florida</option>
                        <option value="GA" <?php echo (isset($state) && $state == 'GA') ? 'selected' : ''; ?>>Georgia</option>
                        <option value="HI" <?php echo (isset($state) && $state == 'HI') ? 'selected' : ''; ?>>Hawaii</option>
                        <option value="ID" <?php echo (isset($state) && $state == 'ID') ? 'selected' : ''; ?>>Idaho</option>
                        <option value="IL" <?php echo (isset($state) && $state == 'IL') ? 'selected' : ''; ?>>Illinois</option>
                        <option value="IN" <?php echo (isset($state) && $state == 'IN') ? 'selected' : ''; ?>>Indiana</option>
                        <option value="IA" <?php echo (isset($state) && $state == 'IA') ? 'selected' : ''; ?>>Iowa</option>
                        <option value="KS" <?php echo (isset($state) && $state == 'KS') ? 'selected' : ''; ?>>Kansas</option>
                        <option value="KY" <?php echo (isset($state) && $state == 'KY') ? 'selected' : ''; ?>>Kentucky</option>
                        <option value="LA" <?php echo (isset($state) && $state == 'LA') ? 'selected' : ''; ?>>Louisiana</option>
                        <option value="ME" <?php echo (isset($state) && $state == 'ME') ? 'selected' : ''; ?>>Maine</option>
                        <option value="MD" <?php echo (isset($state) && $state == 'MD') ? 'selected' : ''; ?>>Maryland</option>
                        <option value="MA" <?php echo (isset($state) && $state == 'MA') ? 'selected' : ''; ?>>Massachusetts</option>
                        <option value="MI" <?php echo (isset($state) && $state == 'MI') ? 'selected' : ''; ?>>Michigan</option>
                        <option value="MN" <?php echo (isset($state) && $state == 'MN') ? 'selected' : ''; ?>>Minnesota</option>
                        <option value="MS" <?php echo (isset($state) && $state == 'MS') ? 'selected' : ''; ?>>Mississippi</option>
                        <option value="MO" <?php echo (isset($state) && $state == 'MO') ? 'selected' : ''; ?>>Missouri</option>
                        <option value="MT" <?php echo (isset($state) && $state == 'MT') ? 'selected' : ''; ?>>Montana</option>
                        <option value="NE" <?php echo (isset($state) && $state == 'NE') ? 'selected' : ''; ?>>Nebraska</option>
                        <option value="NV" <?php echo (isset($state) && $state == 'NV') ? 'selected' : ''; ?>>Nevada</option>
                        <option value="NH" <?php echo (isset($state) && $state == 'NH') ? 'selected' : ''; ?>>New Hampshire</option>
                        <option value="NJ" <?php echo (isset($state) && $state == 'NJ') ? 'selected' : ''; ?>>New Jersey</option>
                        <option value="NM" <?php echo (isset($state) && $state == 'NM') ? 'selected' : ''; ?>>New Mexico</option>
                        <option value="NY" <?php echo (isset($state) && $state == 'NY') ? 'selected' : ''; ?>>New York</option>
                        <option value="NC" <?php echo (isset($state) && $state == 'NC') ? 'selected' : ''; ?>>North Carolina</option>
                        <option value="ND" <?php echo (isset($state) && $state == 'ND') ? 'selected' : ''; ?>>North Dakota</option>
                        <option value="OH" <?php echo (isset($state) && $state == 'OH') ? 'selected' : ''; ?>>Ohio</option>
                        <option value="OK" <?php echo (isset($state) && $state == 'OK') ? 'selected' : ''; ?>>Oklahoma</option>
                        <option value="OR" <?php echo (isset($state) && $state == 'OR') ? 'selected' : ''; ?>>Oregon</option>
                        <option value="PA" <?php echo (isset($state) && $state == 'PA') ? 'selected' : ''; ?>>Pennsylvania</option>
                        <option value="RI" <?php echo (isset($state) && $state == 'RI') ? 'selected' : ''; ?>>Rhode Island</option>
                        <option value="SC" <?php echo (isset($state) && $state == 'SC') ? 'selected' : ''; ?>>South Carolina</option>
                        <option value="SD" <?php echo (isset($state) && $state == 'SD') ? 'selected' : ''; ?>>South Dakota</option>
                        <option value="TN" <?php echo (isset($state) && $state == 'TN') ? 'selected' : ''; ?>>Tennessee</option>
                        <option value="TX" <?php echo (isset($state) && $state == 'TX') ? 'selected' : ''; ?>>Texas</option>
                        <option value="UT" <?php echo (isset($state) && $state == 'UT') ? 'selected' : ''; ?>>Utah</option>
                        <option value="VT" <?php echo (isset($state) && $state == 'VT') ? 'selected' : ''; ?>>Vermont</option>
                        <option value="VA" <?php echo (isset($state) && $state == 'VA') ? 'selected' : ''; ?>>Virginia</option>
                        <option value="WA" <?php echo (isset($state) && $state == 'WA') ? 'selected' : ''; ?>>Washington</option>
                        <option value="WV" <?php echo (isset($state) && $state == 'WV') ? 'selected' : ''; ?>>West Virginia</option>
                        <option value="WI" <?php echo (isset($state) && $state == 'WI') ? 'selected' : ''; ?>>Wisconsin</option>
                        <option value="WY" <?php echo (isset($state) && $state == 'WY') ? 'selected' : ''; ?>>Wyoming</option>
                    </select>

                    <label for="zip" >Zip Code</label>
                    <input type="number" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" value="<?php echo htmlspecialchars($zip); ?>">
                
                    <label for="isHispanic">Hispanic, Latino, or Spanish Origin</label>
                    <select id="isHispanic" name="isHispanic" value="<?php echo htmlspecialchars($isHispanic); ?>">>
                        <option value="" disabled <?php echo isset($isHispanic) && $isHispanic == '' ? 'selected' : ''; ?>>Select Yes or No</option>
                        <option value="yes" <?php echo isset($isHispanic) && $isHispanic == '1' ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo isset($isHispanic) && $isHispanic == '0' ? 'selected' : ''; ?>>No</option>
                    </select>

                    <label for="race">Race</label>
                    <select id="race" name="race">
                        <option value="" disabled <?php echo isset($race) && $race == '' ? 'selected' : ''; ?>>Select Race</option>
                        <option value="Caucasian" <?php echo isset($race) && $race == 'Caucasian' ? 'selected' : ''; ?>>Caucasian</option>
                        <option value="Black/African American" <?php echo isset($race) && $race == 'Black/African American' ? 'selected' : ''; ?>>Black/African American</option>
                        <option value="Native Indian/Alaska Native" <?php echo isset($race) && $race == 'Native Indian/Alaska Native' ? 'selected' : ''; ?>>Native Indian/Alaska Native</option>
                        <option value="Native Hawaiian/Pacific Islander" <?php echo isset($race) && $race == 'Native Hawaiian/Pacific Islander' ? 'selected' : ''; ?>>Native Hawaiian/Pacific Islander</option>
                        <option value="Asian" <?php echo isset($race) && $race == 'Asian' ? 'selected' : ''; ?>>Asian</option>
                        <option value="Multiracial" <?php echo isset($race) && $race == 'Multiracial' ? 'selected' : ''; ?>>Multiracial</option>
                        <option value="Other" <?php echo isset($race) && $race == 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>

                    <label for="income">Estimated Household Income *</label>
                    <select name="income" id="income">
                        <option value="" disabled <?php echo isset($income) && $income == '' ? 'selected' : ''; ?>>Select Estimated Income</option>
                        <option value="Under 20,000" <?php echo isset($income) && $income == 'Under $15,0000' ? 'selected' : ''; ?>>Under 20,000</option>
                        <option value="20,000-40,000n" <?php echo isset($income) && $income == '$15,000 - $24,999' ? 'selected' : ''; ?>>20,000-40,000</option>
                        <option value="40,001-60,000" <?php echo isset($income) && $income == '$25,000 - $34,999' ? 'selected' : ''; ?>>40,001-60,000</option>
                        <option value="60,001-80,000" <?php echo isset($income) && $incomee == '$35,000 - $49,999' ? 'selected' : ''; ?>>60,001-80,000</option>
                        <option value="Over 80,000" <?php echo isset($income) && $income == '$100,000 and above' ? 'selected' : ''; ?>>Over 80,000</option>
                    </select>
                </fieldset>
                <fieldset>
                    <legend>Primary Contact Information</legend>

                    <label for="email" >E-mail</label>
                    <p><b><i>*Changing this email will also change your login username/email.*</i></b></p>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

                    <label for="phone" >Primary Phone Number</label>
                    <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" value="<?php echo htmlspecialchars($phone); ?>">

                    <label >Primary Phone Type</label>
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

                    <label for="neighborhood2" >Neighborhood</label>
                    <input type="text" id="neighborhood2" name="neighborhood2" value="<?php echo htmlspecialchars($neighborhood2); ?>">

                    <label for="address2">Street Address</label>
                    <input type="text" id="address2" name="address2" value="<?php echo htmlspecialchars($address2); ?>">

                    <label for="city2">City</label>
                    <input type="text" id="city2" name="city2" value="<?php echo htmlspecialchars($city2); ?>">

                    <label for="state2">State</label>
                    <select id="state2" name="state2">
                        <option value="" disabled>Select State</option>
                        <option value="AL" <?php echo (isset($state2) && $state2 == 'AL') ? 'selected' : ''; ?>>Alabama</option>
                        <option value="AK" <?php echo (isset($state2) && $state2 == 'AK') ? 'selected' : ''; ?>>Alaska</option>
                        <option value="AZ" <?php echo (isset($state2) && $state2 == 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                        <option value="AR" <?php echo (isset($state2) && $state2 == 'AR') ? 'selected' : ''; ?>>Arkansas</option>
                        <option value="CA" <?php echo (isset($state2) && $state2 == 'CA') ? 'selected' : ''; ?>>California</option>
                        <option value="CO" <?php echo (isset($state2) && $state2 == 'CO') ? 'selected' : ''; ?>>Colorado</option>
                        <option value="CT" <?php echo (isset($state2) && $state2 == 'CT') ? 'selected' : ''; ?>>Connecticut</option>
                        <option value="DE" <?php echo (isset($state2) && $state2 == 'DE') ? 'selected' : ''; ?>>Delaware</option>
                        <option value="DC" <?php echo (isset($state2) && $state2 == 'DC') ? 'selected' : ''; ?>>District of Columbia</option>
                        <option value="FL" <?php echo (isset($state2) && $state2 == 'FL') ? 'selected' : ''; ?>>Florida</option>
                        <option value="GA" <?php echo (isset($state2) && $state2 == 'GA') ? 'selected' : ''; ?>>Georgia</option>
                        <option value="HI" <?php echo (isset($state2) && $state2 == 'HI') ? 'selected' : ''; ?>>Hawaii</option>
                        <option value="ID" <?php echo (isset($state2) && $state2 == 'ID') ? 'selected' : ''; ?>>Idaho</option>
                        <option value="IL" <?php echo (isset($state2) && $state2 == 'IL') ? 'selected' : ''; ?>>Illinois</option>
                        <option value="IN" <?php echo (isset($state2) && $state2 == 'IN') ? 'selected' : ''; ?>>Indiana</option>
                        <option value="IA" <?php echo (isset($state2) && $state2 == 'IA') ? 'selected' : ''; ?>>Iowa</option>
                        <option value="KS" <?php echo (isset($state2) && $state2 == 'KS') ? 'selected' : ''; ?>>Kansas</option>
                        <option value="KY" <?php echo (isset($state2) && $state2 == 'KY') ? 'selected' : ''; ?>>Kentucky</option>
                        <option value="LA" <?php echo (isset($state2) && $state2 == 'LA') ? 'selected' : ''; ?>>Louisiana</option>
                        <option value="ME" <?php echo (isset($state2) && $state2 == 'ME') ? 'selected' : ''; ?>>Maine</option>
                        <option value="MD" <?php echo (isset($state2) && $state2 == 'MD') ? 'selected' : ''; ?>>Maryland</option>
                        <option value="MA" <?php echo (isset($state2) && $state2 == 'MA') ? 'selected' : ''; ?>>Massachusetts</option>
                        <option value="MI" <?php echo (isset($state2) && $state2 == 'MI') ? 'selected' : ''; ?>>Michigan</option>
                        <option value="MN" <?php echo (isset($state2) && $state2 == 'MN') ? 'selected' : ''; ?>>Minnesota</option>
                        <option value="MS" <?php echo (isset($state2) && $state2 == 'MS') ? 'selected' : ''; ?>>Mississippi</option>
                        <option value="MO" <?php echo (isset($state2) && $state2 == 'MO') ? 'selected' : ''; ?>>Missouri</option>
                        <option value="MT" <?php echo (isset($state2) && $state2 == 'MT') ? 'selected' : ''; ?>>Montana</option>
                        <option value="NE" <?php echo (isset($state2) && $state2 == 'NE') ? 'selected' : ''; ?>>Nebraska</option>
                        <option value="NV" <?php echo (isset($state2) && $state2 == 'NV') ? 'selected' : ''; ?>>Nevada</option>
                        <option value="NH" <?php echo (isset($state2) && $state2 == 'NH') ? 'selected' : ''; ?>>New Hampshire</option>
                        <option value="NJ" <?php echo (isset($state2) && $state2 == 'NJ') ? 'selected' : ''; ?>>New Jersey</option>
                        <option value="NM" <?php echo (isset($state2) && $state2 == 'NM') ? 'selected' : ''; ?>>New Mexico</option>
                        <option value="NY" <?php echo (isset($state2) && $state2 == 'NY') ? 'selected' : ''; ?>>New York</option>
                        <option value="NC" <?php echo (isset($state2) && $state2 == 'NC') ? 'selected' : ''; ?>>North Carolina</option>
                        <option value="ND" <?php echo (isset($state2) && $state2 == 'ND') ? 'selected' : ''; ?>>North Dakota</option>
                        <option value="OH" <?php echo (isset($state2) && $state2 == 'OH') ? 'selected' : ''; ?>>Ohio</option>
                        <option value="OK" <?php echo (isset($state2) && $state2 == 'OK') ? 'selected' : ''; ?>>Oklahoma</option>
                        <option value="OR" <?php echo (isset($state2) && $state2 == 'OR') ? 'selected' : ''; ?>>Oregon</option>
                        <option value="PA" <?php echo (isset($state2) && $state2 == 'PA') ? 'selected' : ''; ?>>Pennsylvania</option>
                        <option value="RI" <?php echo (isset($state2) && $state2 == 'RI') ? 'selected' : ''; ?>>Rhode Island</option>
                        <option value="SC" <?php echo (isset($state2) && $state2 == 'SC') ? 'selected' : ''; ?>>South Carolina</option>
                        <option value="SD" <?php echo (isset($state2) && $state2 == 'SD') ? 'selected' : ''; ?>>South Dakota</option>
                        <option value="TN" <?php echo (isset($state2) && $state2 == 'TN') ? 'selected' : ''; ?>>Tennessee</option>
                        <option value="TX" <?php echo (isset($state2) && $state2 == 'TX') ? 'selected' : ''; ?>>Texas</option>
                        <option value="UT" <?php echo (isset($state2) && $state2 == 'UT') ? 'selected' : ''; ?>>Utah</option>
                        <option value="VT" <?php echo (isset($state2) && $state2 == 'VT') ? 'selected' : ''; ?>>Vermont</option>
                        <option value="VA" <?php echo (isset($state2) && $state2 == 'VA') ? 'selected' : ''; ?>>Virginia</option>
                        <option value="WA" <?php echo (isset($state2) && $state2 == 'WA') ? 'selected' : ''; ?>>Washington</option>
                        <option value="WV" <?php echo (isset($state2) && $state2 == 'WV') ? 'selected' : ''; ?>>West Virginia</option>
                        <option value="WI" <?php echo (isset($state2) && $state2 == 'WI') ? 'selected' : ''; ?>>Wisconsin</option>
                        <option value="WY" <?php echo (isset($state2) && $state2 == 'WY') ? 'selected' : ''; ?>>Wyoming</option>
                    </select>

                    <label for="zip2">Zip Code</label>
                    <input type="number" id="zip2" name="zip2" pattern="[0-9]{5}" title="5-digit zip code" value="<?php echo htmlspecialchars($zip2); ?>">
                
                    <label for="isHispanic2">Hispanic, Latino, or Spanish Origin</label>
                    <select id="isHispanic2" name="isHispanic2">
                        <option value="" disabled <?php echo isset($isHispanic2) && $isHispanic2 == '' ? 'selected' : ''; ?>>Select Yes or No</option>
                        <option value="yes" <?php echo isset($isHispanic2) && $isHispanic2 == '1' ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo isset($isHispanic2) && $isHispanic2 == '0' ? 'selected' : ''; ?>>No</option>
                    </select>

                    <label for="race2">Race</label>
                    <select id="race2" name="race2">
                        <option value="" disabled <?php echo isset($race2) && $race2 == '' ? 'selected' : ''; ?>>Select Race</option>
                        <option value="Caucasian" <?php echo isset($race2) && $race2 == 'Caucasian' ? 'selected' : ''; ?>>Caucasian</option>
                        <option value="Black/African American" <?php echo isset($race2) && $race2 == 'Black/African American' ? 'selected' : ''; ?>>Black/African American</option>
                        <option value="Native Indian/Alaska Native" <?php echo isset($race2) && $race2 == 'Native Indian/Alaska Native' ? 'selected' : ''; ?>>Native Indian/Alaska Native</option>
                        <option value="Native Hawaiian/Pacific Islander" <?php echo isset($race2) && $race2 == 'Native Hawaiian/Pacific Islander' ? 'selected' : ''; ?>>Native Hawaiian/Pacific Islander</option>
                        <option value="Asian" <?php echo isset($race2) && $race2 == 'Asian' ? 'selected' : ''; ?>>Asian</option>
                        <option value="Multiracial" <?php echo isset($race2) && $race2 == 'Multiracial' ? 'selected' : ''; ?>>Multiracial</option>
                        <option value="Other" <?php echo isset($race2) && $race2 == 'Other' ? 'selected' : ''; ?>>Other</option>
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

                    <label for="econtact-relation" >Contact Relation to You</label>
                    <input type="text" id="econtact-relation" name="econtact-relation" value="<?php echo htmlspecialchars($econtact_relation); ?>">
                </fieldset>

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
