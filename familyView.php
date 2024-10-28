<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['id'];
}

require_once("database/dbFamily.php");
require_once("domain/Family.php");
require_once("include/input-validation.php");

$family = retrieve_family_by_email($userID); //retrieves family by email for now (may change later)


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | View Account</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>View Family Account</h1>

        <div style="margin-left: 20px; margin-right: 20px">
        <?php 
            if(isset($family)){
                echo "<h2>Primary Information</h2>";
                echo "<h3>Name:</h3>" . $family->getFirstName() . " " . $family->getLastName();
                echo "<br>";
                echo "<h3>Birthdate:</h3>" . $family->getBirthDate();
                echo "<br>";
                echo "<h3>Address</h3>" . $family->getAddress(); 
                echo "<br>";
                echo "<h3>City:</h3>" . $family->getCity();
                echo "<br>";
                echo "<h3>State:</h3>" . $family->getState();
                echo "<br>";
                echo "<h3>Zip</h3>" . $family->getZip();
                echo "<br>";
                echo "<h3>Email:</h3>" . $family->getEmail();
                echo "<br>";
                echo "<h3>Phone</h3>" . $family->getPhone(); 
                echo "<br>";
                echo "<h3>Phone Type:</h3>" . $family->getPhoneType();
                echo "<br>";
                echo "<h3>Secondary Phone:</h3>" . $family->getSecondaryPhoneType();
                echo "<br>";
                echo "<h3>Zip</h3>" . $family->getZip();
                echo "<br>";
                echo "<br>";
                //Secondary
                echo "<h2>Secondary Information</h2>";
                echo "<h3>Name:</h3>" . $family->getFirstName2() . " " . $family->getLastName2();
                echo "<br>";
                echo "<h3>Birthdate:</h3>" . $family->getBirthDate2();
                echo "<br>";
                echo "<h3>Address</h3>" . $family->getAddress2(); 
                echo "<br>";
                echo "<h3>City:</h3>" . $family->getCity2();
                echo "<br>";
                echo "<h3>State:</h3>" . $family->getState2();
                echo "<br>";
                echo "<h3>Zip</h3>" . $family->getZip2();
                echo "<br>";
                echo "<h3>Email:</h3>" . $family->getEmail2();
                echo "<br>";
                echo "<h3>Phone</h3>" . $family->getPhone2(); 
                echo "<br>";
                echo "<h3>Phone Type:</h3>" . $family->getSecondaryPhone2();
                echo "<br>";
                echo "<h3>Secondary Phone:</h3>" . $family->getSecondaryPhoneType2();
                echo "<br>";
                echo "<h3>Emergency Contact</h3>" . $family->getEContactFirstName() . " " . $family->getEContactLastName(); 
                echo "<br>";
                echo "<h3>Emergency Contact Number:</h3>" . $family->getEContactPhone();
                echo "<br>";
                echo "<h3>Emergency Contact Relation:</h3>" . $family->getEContactRelation();
            }
        
        ?>
        </div>
        <a class="button cancel" href="familyAccountDashboard.php" style="margin-top: 3rem;">Return to Dashboard</a>
     
        

        
        
    </body>
</html>

