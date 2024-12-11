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
        <title>Stafford Junction | Children Accounts</title>
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
        <p>Click on an Account ID to view or edit that child's account.</p>
        <?php if(isset($children) && !empty($children)) {
            echo '
            <div class="table-wrapper">
                <table class="general">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Account ID</th>';
                        echo '<th>Full Name</th>';
                        echo '<th>Date of Birth</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Medical Notes</th>';
                        echo '<th>Other Notes</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody class="standout">';
                foreach ($children as $acct) {
                    echo '<tr>';
                    echo '<td><a href=childAccount.php?id=' . $acct->getID() . '>' . $acct->getID() . '</a></td>';
                    echo '<td>' . $acct->getFirstName() . ' ' . $acct->getLastName() . '</td>';
                    echo '<td>' . $acct->getBirthdate() . '</td>';
                    echo '<td>' . $acct->getGender() . '</td>';
                    echo '<td>' . $acct->getMedicalNotes() . '</td>';
                    echo '<td>' . $acct->getNotes() . '</td>';
                    echo '</tr>';
                }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<p>No children accounts found.</p>';
        }

        ?>
        </div>

        
        <?php if($_SESSION['access_level'] >= 1): ?>
            <a class="button cancel button_style" href="familyAccountDashboard.php" style="margin-top: 1rem;">Return to Dashboard</a>
        <?php endif ?>
        

    </body>
</html>

