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
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Children Accounts</h1>
        <div style="margin-left: 40px; margin-right: 40px;">
        <?php
        if(isset($children)){
                echo '<h3>Children Summary</h3>';
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Acct ID</th>
                                <th>Name</th>
                                <th>Date of Birth</th>
                                <th>gender</th>
                                <th>medical notes</th>
                                <th>notes</th>';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        foreach($children as $acct){
                            echo '<tr>';
                            echo '<td><a href=childAccount.php?id=' . $acct->getID() . '>' . $acct->getID() . '</a></td>';
                            echo '<td>' . $acct->getFirstName() . " " . $acct->getLastName() . '</td>';
                            echo '<td>' . $acct->getBirthdate() . '</td>';
                            echo '<td>' . $acct->getGender() . '</td>';
                            echo '<td>' . $acct->getMedicalNotes() . '</td>';
                            echo '<td>' . $acct->getNotes() . '</td>';
                            echo '<tr>';
                        }
                        
                echo '
                        </tbody>
                    </table>
                </div>';
            }
            ?>
        
        </div>
        

        <?php if($_SESSION['access_level'] == 1): ?>
        <a class="button cancel, button_style" href="familyAccountDashboard.php" style="margin-top: 3rem;">Return to Dashboard</a>
        <?php endif ?>
        

        
        
    </body>
</html>

