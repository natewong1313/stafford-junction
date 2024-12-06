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
$isHispanic2;
$race2;

$econtact_first_name = $family->getEContactFirstName();
$econtact_last_name = $family->getEContactLastName();
$econtact_phone = $family->getEContactPhone();
$econtact_relation = $family->getEContactRelation();

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $args = sanitize($_POST);
    $success = update_profile($args, $familyId);
    
    if ($accessLevel > 1 && $success) {
        //header("Location: index.php");
        echo '<script>document.location = "index.php?updateSuccess";</script>';
    }elseif($accessLevel == 1 && $success ) {
        //header("Location: familyAccountDashboard.php");
        echo '<script>document.location = "familyAccountDashboard.php?updateSuccess";</script>';
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
                    <select id="state" name="state" value="<?php echo htmlspecialchars($state); ?>">
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
                    <select id="state2" name="state2" value="<?php echo htmlspecialchars($state2); ?>">
                        <option value="--">--</option>
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
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                    </select>

                    <label for="zip2">Zip Code</label>
                    <input type="number" id="zip2" name="zip2" pattern="[0-9]{5}" title="5-digit zip code" value="<?php echo htmlspecialchars($zip2); ?>">
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

                    <label for="isHispanic2">Hispanic, Latino, or Spanish Origin</label><br><br>
                    <select id="isHispanic2" name="isHispanic2">
                        <option value="" disabled selected>Select Yes or No</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <br><br>

                    <label for="race2" required>Race</label><br><br>
                    <select id="race2" name="race2">
                        <option value="" disabled selected>Select Race</option>
                        <option value="Caucasian">Caucasian</option>
                        <option value="Black/African American">Black/African American</option>
                        <option value="Native Indian/Alaska Native">Native Indian/Alaska Native</option>
                        <option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander</option>
                        <option value="Asian">Asian</option>
                        <option value="Multiracial">Multiracial</option>
                        <option value="Other">Other</option>
                    </select><br><br>

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