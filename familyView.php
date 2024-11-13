<!--This file shows the complete account information of a family account -->
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

//these files will give us all the family functionality and access to the family account database
require_once("database/dbFamily.php");
require_once("domain/Family.php");
require_once("include/input-validation.php");

$family = retrieve_family_by_id($_GET['id'] ?? $userID); //either retrieve the family by the unique account identifier set in $userID, or inside the GET array if being accessed from staff account

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Archive or unarchive family
    if (isset($_POST['archive'])) {
        archive_family($family->getId());
    } else if (isset($_POST['unarchive'])) {
        unarchive_family($family->getId());
    }
    // Refresh page because archive button will not change without it
    header('Refresh:0');
    die();
}
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
        <h1>Family Account</h1>
        <div style="margin-left: 40px; margin-right: 40px;">
        <?php
        if(isset($family)){
            echo '<h3>Primary Information</h3>';
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Phone Type</th>
                                <th>Phone 2</th>
                                <th>Phone 2 Type</th>';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        //foreach($family as $family){
                            echo '<tr>';
                            echo '<td>' . $family->getFirstName() . " " . $family->getLastName() . '</td>';
                            echo '<td>' . $family->getBirthdate() . '</td>';
                            echo '<td>' . $family->getAddress() . ', ' . $family->getCity() . ', ' . $family->getState() . ' ' . $family->getZip() . '</td>';
                            echo '<td>' . $family->getEmail() . '</td>';
                            echo '<td>' . $family->getPhone() . '</td>';
                            echo '<td>' . $family->getPhoneType() . '</td>';
                            echo '<td>' . $family->getSecondaryPhone()  . '</td>';
                            echo '<td>' . $family->getSecondaryPhoneType() . '</td>';
                            echo '</tr>';
                        //}
                        
                echo '
                        </tbody>
                    </table>
                </div>';
            
                echo '<h3>Secondary Information</h3>';
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Phone Type</th>
                                <th>Phone 2</th>
                                <th>Phone 2 Type</th>';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        //foreach($family as $family){
                            echo '<tr>';
                            echo '<td>' . $family->getFirstName2() . " " . $family->getLastName() . '</td>';
                            echo '<td>' . $family->getBirthdate2() . '</td>';
                            echo '<td>' . $family->getAddress2() . ', ' . $family->getCity2() . ', ' . $family->getState2() . ' ' . $family->getZip2() . '</td>';
                            echo '<td>' . $family->getEmail2() . '</td>';
                            echo '<td>' . $family->getPhone2() . '</td>';
                            echo '<td>' . $family->getPhoneType2() . '</td>';
                            echo '<td>' . $family->getSecondaryPhone2()  . '</td>';
                            echo '<td>' . $family->getSecondaryPhoneType2() . '</td>';
                            echo '</tr>';
                        //}
                        
                echo '
                        </tbody>
                    </table>
                </div>';
            
                echo '<h3>Emergency Contact Information</h3>';
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Relation</th>
                                ';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        //foreach($family as $family){
                            echo '<tr>';
                            echo '<td>' . $family->getEContactFirstName() . " " . $family->getEContactLastName() . '</td>';
                            echo '<td>' . $family->getEContactPhone() . '</td>';
                            echo '<td>' . $family->getEContactRelation() . '</td>';
                            echo '</tr>';
                        //}
                        
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

        <!-- Archive Family Buttons -->
        <?php if($_SESSION['access_level'] > 1 && !$family->isArchived()): ?>
        <form method="post">
            <button type="submit" name="archive" style="margin-top: 3rem;">Archive Family</button>
        </form>
        <?php endif?>
        <?php if($_SESSION['access_level'] > 1 && $family->isArchived()): ?>
        <form method="post">
            <button type="submit" name="unarchive" style="margin-top: 3rem;">Unarchive Family</button>
        </form>
        <?php endif?>
        <!-- Cancel Buttons -->
        <?php if($_SESSION['access_level'] == 1): ?>
        <a class="button cancel" href="familyAccountDashboard.php" style="margin-top: .5rem;">Return to Dashboard</a>
        <?php endif ?>
        <?php if($_SESSION['access_level'] > 1): ?>
        <a class="button cancel" href="findFamily.php" style="margin-top: .5rem;">Return to Search</a>
        <?php endif?>   
    </body>
</html>

