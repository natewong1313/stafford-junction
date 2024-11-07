<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

if(isset($_SESSION['_id'])){
    require_once('database/dbFamily.php');
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
}

include_once('domain/Family.php');
$family = retrieve_family_by_id($_SESSION["_id"]);


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $args = sanitize($_POST);
    $success = update_profile($args);
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
                    <label for="first-name" >First Name</label>
                    <input type="text" id="first-name" name="first-name"  placeholder="Enter your first name">

                    <label for="last-name" >Last Name</label>
                    <input type="text" id="last-name" name="last-name"  placeholder="Enter your last name">

                    <label for="birthdate" >Date of Birth</label>
                    <input type="date" id="birthdate" name="birthdate"  placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">


                    <label for="address" >Street Address</label>
                    <input type="text" id="address" name="address"  placeholder="Enter your street address">

                    <label for="city" >City</label>
                    <input type="text" id="city" name="city"  placeholder="Enter your city">

                    <label for="state" >State</label>
                    <select id="state" name="state" >
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

                    <label for="zip" >Zip Code</label>
                    <input type="text" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code">
                </fieldset>

                <fieldset>
                    <legend>Contact Information</legend>

                    <label for="email" >E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Enter your e-mail address">

                    <label for="phone" >Primary Phone Number</label>
                    <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

                    <label >Primary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="phone-type-cellphone" name="phone-type" value="cellphone"><label for="phone-type-cellphone">Cell</label>
                        <input type="radio" id="phone-type-home" name="phone-type" value="home"><label for="phone-type-home">Home</label>
                        <input type="radio" id="phone-type-work" name="phone-type" value="work"><label for="phone-type-work">Work</label>
                    </div>

                    <label for="secondary-phone">Secondary Phone Number</label>
                    <input type="tel" id="secondary-phone" name="secondary-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

                    <label>Secondary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="secondary-phone-type-cellphone" name="secondary-phone-type" value="cellphone"><label for="secondary-phone-type-cellphone">Cell</label>
                        <input type="radio" id="secondary-phone-type-home" name="secondary-phone-type" value="home"><label for="secondary-phone-type-home">Home</label>
                        <input type="radio" id="secondary-phone-type-work" name="secondary-phone-type" value="work"><label for="secondary-phone-type-work">Work</label>
                    </div>
                </fieldset>

                <h3>Secondary Parent / Guardian</h3>
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="first-name2">First Name</label>
                    <input type="text" id="first-name2" name="first-name2" placeholder="Enter your first name">

                    <label for="last-name2">Last Name</label>
                    <input type="text" id="last-name2" name="last-name2" placeholder="Enter your last name">

                    <label for="birthdate2">Date of Birth</label>
                    <input type="date" id="birthdate2" name="birthdate2" placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">


                    <label for="address2">Street Address</label>
                    <input type="text" id="address2" name="address2" placeholder="Enter your street address">

                    <label for="city2">City</label>
                    <input type="text" id="city2" name="city2" placeholder="Enter your city">

                    <label for="state2">State</label>
                    <select id="state2" name="state2">
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
                    <input type="text" id="zip2" name="zip2" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code">
                </fieldset>
                <fieldset>
                    <legend>Contact Information</legend>
                    <label for="emai2l">E-mail</label>
                    <input type="email" id="email2" name="email2" placeholder="Enter your e-mail address">

                    <label for="phone2">Primary Phone Number</label>
                    <input type="tel" id="phone2" name="phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

                    <label>Primary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="phone-type-cellphone2" name="phone-type2" value="cellphone"><label for="phone-type-cellphone2">Cell</label>
                        <input type="radio" id="phone-type-home2" name="phone-type2" value="home"><label for="phone-type-home2">Home</label>
                        <input type="radio" id="phone-type-work2" name="phone-type2" value="work"><label for="phone-type-work2">Work</label>
                    </div>

                    <label for="secondary-phone2">Secondary Phone Number</label>
                    <input type="tel" id="secondary-phone2" name="secondary-phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

                    <label>Secondary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="secondary-phone-type-cellphone2" name="secondary-phone-type2" value="cellphone"><label for="secondary-phone-type-cellphone2">Cell</label>
                        <input type="radio" id="secondary-phone-type-home2" name="secondary-phone-type2" value="home"><label for="secondary-phone-type-home2">Home</label>
                        <input type="radio" id="secondary-phone-type-work2" name="secondary-phone-type2" value="work"><label for="secondary-phone-type-work2">Work</label>
                    </div>
                </fieldset>

                <h3>Emergency Contact</h3>
                <fieldset>
                    <label for="econtact-first-name" >Contact First Name</label>
                    <input type="text" id="econtact-first-name" name="econtact-first-name" placeholder="Enter emergency contact first name">

                    <label for="econtact-last-name" >Contact Last Name</label>
                    <input type="text" id="econtact-last-name" name="econtact-last-name" placeholder="Enter emergency contact last name">

                    <label for="econtact-phone" >Contact Phone Number</label>
                    <input type="tel" id="econtact-phone" name="econtact-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Enter emergency contact phone number. Ex. (555) 555-5555">

                    <label for="econtact-name" >Contact Relation to You</label>
                    <input type="text" id="econtact-relation" name="econtact-relation" placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">
                </fieldset>

                <h3>Login Credentials</h3>
                <fieldset>
                    <label for="question" >Enter Security Question</label>
                    <input type="text" id="question" name="question" placeholder="Security Question">

                    <label for="answer" >Enter Security Answer</label>
                    <input type="text" id="answer" name="answer" placeholder="Security Answer">
                </fieldset>

                <input type="submit" name="profile-edit-form" value="Update Profile">
            </form>
            <a class="button cancel" href="familyAccountDashboard.php" style="margin-top: .5rem">Cancel</a>
        </main>
    </body>
</html>