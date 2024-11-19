<!--This file shows the complete account information of a child account -->
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
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php 
            require_once('header.php');
            require_once('include/output.php');
        ?>
        <h1>Account Page for <?php echo $child->getFirstName() ?></h1>

        <div  id="view-family" style="margin-left: 20px; margin-right: 20px">
        <main class="general">
        <?php if(isset($child)) {
            echo '<fieldset>';
            echo '<legend>' . $child->getFirstName() . '</legend>';
            echo '<label>Account ID</label>';
            echo '<p>' . $child->getID() . '</a></p>';
            echo '<label>Full Name</label>';
            echo '<p>' . $child->getFirstName() . " " . $child->getLastName() . '</p>';
            echo '<label>Date of Birth</label>';
            echo '<p>' . $child->getBirthdate() . '</p>';
            echo '<label>Gender</label>';
            echo '<p>' . $child->getGender() . '</p>';
            echo '<label>Medical Notes</label>';
            echo '<p>' . $child->getMedicalNotes() . '</p>';
            echo '<label>Other Notes</label>';
            echo '<p>' . $child->getNotes() . '</p>';
            echo '<label>Enrolled Programs</label>';
            echo '<p>' . '</p>';
            echo '</fieldset>';
        }
        
        ?>
        
        </div>


        <?php if($_SESSION['access_level'] == 1): ?>
        <a class="button cancel button_style" href="familyAccountDashboard.php" style="margin-top: 3rem;">Return to Dashboard</a>
        <?php endif ?>
        <?php if($_SESSION['access_level'] > 1 && !isset($_GET['findChildren'])): ?>
        <a class="button cancel button_style" href="findFamily.php" style="margin-top: 3rem;">Return to Search</a>
        <?php endif?>
        <?php if($_SESSION['access_level'] > 1 && isset($_GET['findChildren'])): ?>
        <a class="button cancel button_style" href="findChildren.php" style="margin-top: 3rem;">Return to Search</a>
        <?php endif?>
        

    </body>
</html>