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
require_once("domain/Children.php");

//grabs all the children associated with account and puts it into an array
$children = getChildren($userID);

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
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php 
            require_once('header.php'); 
            require_once('include/output.php');
        ?>
        <h1>Family Account Information</h1>

        <div id="view-family" style="margin-left: 20px; margin-right: 20px">
        <main class="general">
            <fieldset>
                    <legend>General Information</legend>
                    <label>Account ID</label>
                    <p><?php echo $family->getId() ?></p>
            </fieldset>
            <fieldset>
            <legend>Primary Information</legend>
                <label>Name</label>
                <p><?php echo $family->getFirstName() . " " . $family->getLastName() ?></p>
                <label>Date of Birth</label>
                <p><?php echo $family->getBirthdate() ?></p>
                <label>Address</label>
                <p><?php echo $family->getAddress() . ", " . $family->getCity() . ", " . $family->getState() . " " . $family->getZip() ?></p>
                <label>Email</label>
                <p><?php echo $family->getEmail() ?></p>
                <label>Phone</label>
                <p><?php echo $family->getPhone() ?></p>
                <label>Phone type</label>
                <p><?php echo $family->getPhoneType() ?></p>
                <label>Secondary phone</label>
                <p><?php echo $family->getSecondaryPhone() ?></p>
                <label>Secondary phone type</label>
                <p><?php echo $family->getSecondaryPhoneType() ?></p>
            </fieldset>
            <fieldset>
            <legend>Secondary Information</legend>
                <label>Name</label>
                <p><?php echo $family->getFirstName2() . " " . $family->getLastName2() ?></p>
                <label>Birthdate</label>
                <p><?php echo $family->getBirthDate2() ?></p>
                <label>Address</label>
                <p><?php echo $family->getAddress2() . ", " . $family->getCity2() . ", " . $family->getState2() . " " . $family->getZip2() ?></p>
                <label>Email</label>
                <p><?php echo $family->getEmail2() ?></p>
                <label>Phone</label>
                <p><?php echo $family->getPhone2() ?></p>
                <label>Phone type</label>
                <p><?php echo $family->getPhoneType2() ?></p>
                <label>Secondary phone</label>
                <p><?php echo $family->getSecondaryPhone2() ?></p>
                <label>Secondary phone type</label>
                <p><?php echo $family->getSecondaryPhoneType2() ?></p>
            </fieldset>
            <fieldset>
            <legend>Emergency Contact</legend>
                <label>Name</label>
                <p><?php echo $family->getEContactFirstName() . " " . $family->getEContactLastName() ?></p>
                <label>Phone</label>
                <p><?php echo $family->getEContactPhone() ?></p>
                <label>Relation</label>
                <p><?php echo $family->getEContactRelation() ?></p>
            </fieldset>
            <fieldset>
            
            <?php 
            if (isset($children) && !empty($children)) {
                echo '<table style="width: 100%; border-collapse: collapse;">';
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
                echo '<tbody>';
                
                foreach ($children as $acct) {
                    echo '<tr>';
                    echo '<td><a href="childAccount.php?id=' . $acct->getID() . '">' . $acct->getID() . '</a></td>';
                    echo '<td>' . $acct->getFirstName() . ' ' . $acct->getLastName() . '</td>';
                    echo '<td>' . $acct->getBirthdate() . '</td>';
                    echo '<td>' . $acct->getGender() . '</td>';
                    echo '<td>' . $acct->getMedicalNotes() . '</td>';
                    echo '<td>' . $acct->getNotes() . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } 
            ?>
        </div>

            <!-- Archive Family Buttons -->
            <?php if($_SESSION['access_level'] > 1 && !$family->isArchived()): ?>
                <form method="post">
                    <button type="submit" name="archive" class="button_style">Archive Family</button>
                </form>
            <?php endif?>
            <?php if($_SESSION['access_level'] > 1 && $family->isArchived()): ?>
                <form method="post">
                    <button type="submit" name="unarchive" class="button_style">Unarchive Family</button>
                </form>
            <?php endif?>
            <!-- Cancel Buttons -->
            <?php if($_SESSION['access_level'] == 1): ?>
                <a class="button cancel button_style" href="familyAccountDashboard.php" style="margin-top: 1rem;">Return to Dashboard</a>
            <?php endif ?>
            <?php if($_SESSION['access_level'] > 1): ?>
                <a class="button cancel button_style" href="findFamily.php" style="margin-top: 1rem;">Return to Search</a>
            <?php endif?>   

    </body>
</html>

