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

require_once("domain/Children.php");
require_once("database/dbFamily.php");

//grabs all the children associated with account and puts it into an array
$children = getChildren($userID);

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | View Children Accounts</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php 
            require_once('header.php'); 
            require_once('include/output.php');
        ?>
        <h1>View Children Accounts</h1>

        <div id="view-family" style="margin-left: 20px; margin-right: 20px">
        <main class="general">
        <?php if(isset($children) && !empty($children)) {
            $num = 1;
            foreach($children as $acct){
                echo '<fieldset>';
                echo '<legend>' . $acct->getFirstName() . '</legend>';
                echo '<label>Account ID</label>';
                echo '<p><a href=childAccount.php?id=' . $acct->getID() . '>' . $acct->getID() . '</a></p>';
                echo '<label>Full Name</label>';
                echo '<p>' . $acct->getFirstName() . " " . $acct->getLastName() . '</p>';
                echo '<label>Date of Birth</label>';
                echo '<p>' . $acct->getBirthdate() . '</p>';
                echo '<label>Gender</label>';
                echo '<p>' . $acct->getGender() . '</p>';
                echo '<label>Medical Notes</label>';
                echo '<p>' . $acct->getMedicalNotes() . '</p>';
                echo '<label>Other Notes</label>';
                echo '<p>' . $acct->getNotes() . '</p>';
                echo '<label>Enrolled Programs</label>';
                echo '<p>' . '</p>';
                echo '</fieldset>';
                $num++;
            } 
        } else {
            echo '<p>No children accounts found.</p>';
        }

        ?>
        </div>

        


        <?php if($_SESSION['access_level'] == 1): ?>
            <a class="button cancel button_style" href="familyAccountDashboard.php" style="margin-top: 1rem;">Return to Dashboard</a>
        <?php endif ?>
        

    </body>
</html>

