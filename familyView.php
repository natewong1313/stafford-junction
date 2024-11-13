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
                                '//<th>Acct ID</th>
                                '<th>Name</th>
                                <th>Date of Birth</th>
                                <th>Adress</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Phone Type</th>
                                <th>Secondary Phone</th>
                                <th>Secondary Phone Type</th>';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        foreach($family as $acct){
                            echo '<tr>';
                            //echo '<td><a href=familyAccount.php?id=' . $acct->getId() . '>' . $acct->getId() . '</a></td>';
                            echo '<td>' . $acct->getFirstName() . " " . $acct->getLastName() . '</td>';
                            echo '<td>' . $acct->getBirthdate() . '</td>';
                            echo '<td>' . $acct->getAddress() . ', ' . $acct->getCity() . ', ' $acct->getState() . ' ' . $acct->getZip() '</td>';
                            echo '<td>' . $acct->getEmail() . '</td>';
                            echo '<td>' . $acct->getPhone() . '</td>';
                            echo '<td>' . $acct->getPhoneType() . '</td>';
                            echo '<td>' . $acct->getSecondaryPhone()  . '</td>';
                            echo '<td>' . $acct->getSecondaryPhoneType() . '</td>';
                            echo '<tr>';
                        }
                        
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
                                <th>Adress</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Phone Type</th>
                                <th>Secondary Phone</th>
                                <th>Secondary Phone Type</th>';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        foreach($family as $acct){
                            echo '<tr>';
                            echo '<td>' . $acct->getFirstName2() . " " . $acct->getLastName() . '</td>';
                            echo '<td>' . $acct->getBirthdate2() . '</td>';
                            echo '<td>' . $acct->getAddress2() . ', ' . $acct->getCity() . ', ' $acct->getState() . ' ' . $acct->getZip() '</td>';
                            echo '<td>' . $acct->getEmail2() . '</td>';
                            echo '<td>' . $acct->getPhone2() . '</td>';
                            echo '<td>' . $acct->getPhoneType2() . '</td>';
                            echo '<td>' . $acct->getSecondaryPhone2()  . '</td>';
                            echo '<td>' . $acct->getSecondaryPhoneType2() . '</td>';
                            echo '<tr>';
                        }
                        
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
                        foreach($family as $acct){
                            echo '<tr>';
                            echo '<td>' . $acct->getEContactFirstName() . " " . $acct->getEContactLastName() . '</td>';
                            echo '<td>' . $acct->getEContactPhone() . '</td>';
                            echo '<td>' . $acct->getEContactRelation() . '</td>';
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

