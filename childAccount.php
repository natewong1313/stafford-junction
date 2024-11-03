<!--This file shows the complete account information of a child account -->
<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['id'])) {
    $loggedIn = true;
    //0 - Volunteer, 1 - Family, >=2 Staff/Admin
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['id'];
}

//these files will give us all the family and child functionality and access to the family account and child database
require_once("database/dbFamily.php");
require_once("domain/Family.php");
require_once("database/dbChildren.php");
require_once("include/input-validation.php");

//get child by id from childrenInAccount.php
$child = retrieve_child_by_id($_GET['id']);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Child Account</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Account Page for <?php echo $child->getFirstName() ?></h1>

        <div style="margin-left: 20px; margin-right: 20px">
        <?php 
            if(isset($child)){
                echo "<h2>Primary Information</h2>";
                echo "<h3>Name:</h3>" . $child->getFirstName() . " " . $child->getLastName();
                echo "<br>";
                echo "<h3>Birthdate:</h3>" . $child->getBirthdate();
                echo "<br>";
                echo "<h3>Gender</h3>" . $child->getGender(); 
                echo "<br>";
                echo "<h3>Medical Notes:</h3>" . $child->getMedicalNotes();
                echo "<br>";
                echo "<h3>Notes:</h3>" . $child->getMedicalNotes();
                echo "<br>";
                echo "<h3>Enrolled Programs:</h3>";

            }
        
        ?>
        </div>
        <?php if($_SESSION['access_level'] == 1): ?>
        <a class="button cancel button_style" href="familyAccountDashboard.php" style="margin-top: 3rem;">Return to Dashboard</a>
        <?php endif ?>
        <?php if($_SESSION['access_level'] > 1): ?>
        <a class="button cancel button_style" href="findFamily.php" style="margin-top: 3rem;">Return to Search</a>
        <?php endif?>
        

        
        
    </body>
</html>