<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    //0 - Volunteer, 1 - Family, >=2 Staff/Admin
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

//these files will give us all the family and child functionality and access to the family account and child database
require_once("database/dbFamily.php");
require_once("domain/Family.php");
require_once("database/dbChildren.php");
require_once("include/input-validation.php");

//files needed to check program enrollment
require_once("database/dbBrainBuildersRegistration.php");
require_once("database/dbSummerJunctionForm.php");

//get child by id from childrenInAccount.php
$child = retrieve_child_by_id($_GET['id']);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Child Account Information</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php 
            //require_once('header.php');
            require_once('include/output.php');
        ?>
        <h1>Account Page for <?php echo $child->getFirstName() . " " . $child->getLastName() ?></h1>

        <div  id="view-family" style="margin-left: 20px; margin-right: 20px">
        <main class="general">
        <?php if(isset($child)) {
            echo '<fieldset>';
            echo '<label>Full Name</label>';
            echo '<p>' . $child->getFirstName() . " " . $child->getLastName() . '</p>';
            echo '<label>Date of Birth</label>';
            echo '<p>' . $child->getBirthdate() . '</p>';
            echo '<label>Neighborhood</label>';
            echo '<p>' . $child->getNeighborhood() . '</p>';
            echo '<label>Address</label>';
            echo '<p>' . $child->getAddress() . " " . $child->getNeighborhood() . " " . $child->getCity() . " " . $child->getState() . " " . $child->getZip() . '</p>';
            echo '<label>Gender</label>';
            echo '<p>' . ucfirst($child->getGender()) . '</p>';
            echo '<label>School</label>';
            echo '<p>' . $child->getSchool() . '</p>';
            echo '<label>Grade</label>';
            echo '<p>' . $child->getGrade() . '</p>';
            echo '<label>Hispanic, Latino, or Spanish Origin</label>';
            echo '<p>' . ($child->isHispanic() == 1 ? 'Yes' : 'No') . '</p>';
            echo '<label>Race</label>';
            echo '<p>' . $child->getRace() . '</p>';
            echo '<label>Medical Notes</label>';
            echo '<p>' . $child->getMedicalNotes() . '</p>';
            echo '<label>Other Notes</label>';
            echo '<p>' . $child->getNotes() . '</p>';
            echo '<label>Enrolled Programs</label>';

            if(isBrainBuildersRegistrationComplete($_GET['id'] ?? $child->getID())){
                echo "Brain Builders" . "<br>";
            }

            if(isSummerJunctionFormComplete($_GET['id'] ?? $child->getID())){
                echo "Summer Junction" . "<br>";
            }

            
            
        }
        
        ?>
        
        </div>

       
        <?php if(isset($_SESSION['access_level']) && $_SESSION['access_level'] == 1): ?>
            <a class="button cancel button_style" href="childrenInAccount.php" style="margin-top: 3rem;">Back</a>
        <?php endif ?>
        <?php if(isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2 && !isset($_GET['findChildren'])): ?>
            <a class="button cancel button_style" href="findFamily.php" style="margin-top: 3rem;">Return to Search</a>
        <?php endif?>
        <?php if(isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2 && isset($_GET['findChildren'])): ?>
            <a class="button cancel button_style" href="findChildren.php" style="margin-top: 3rem;">Return to Search</a>
        <?php endif?>
        

    </body>
</html>