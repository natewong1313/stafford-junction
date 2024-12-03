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

$family = retrieve_family_by_id($_GET['id'] ?? $userID); //either retrieve the family by the unique account identifier set in $userID, or inside the GET array if being accessed from staff account

//grabs all the children associated with family account and puts it into an array
$children = getChildren($family->getId());

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
        <style>
            .general tbody tr:hover {
                background-color: #cccccc; /* Light grey color */
            }
        </style>
    </head>
    <body>
        <?php 
            require_once('header.php'); 
            require_once('include/output.php');
        ?>
        <h1>Family Account Information</h1>

        <div id="view-family" style="margin-left: 20px; margin-right: 20px">
        <main class="general">
            <?php
                if (isset($_GET['pcSuccess'])) {
                    echo '<div class="happy-toast" style="text-align: center;">Password changed successfully!</div>';
                }
            ?>

            <h3>Primary Parent / Guardian</h3>
            <fieldset>
            <legend>General Information</legend>
                <label>Name</label>
                <p><?php echo $family->getFirstName() . " " . $family->getLastName() ?></p>
                <label>Date of Birth</label>
                <p><?php echo $family->getBirthdate() ?></p>
                <label>Neighborhood</label>
                <p><?php echo $family->getNeighborhood() ?></p>
                <label>Address</label>
                <p><?php echo $family->getAddress() . ", " . $family->getCity() . ", " . $family->getState() . " " . $family->getZip() ?></p>
                <label>Hispanic, Latino, or Spanish Origin</label>
                <p><?php 
                    if ($family->isHispanic() == 1) {
                        echo 'Yes';
                    } elseif ($family->isHispanic() == 0) {
                        echo 'No';
                    }
                    ?></p>
                <label>Race</label>
                <p><?php echo $family->getRace() ?></p>
            </fieldset>
            <fieldset>
            <legend> Contact Information</legend>
                <label>Email</label>
                <p><?php echo $family->getEmail() ?></p>
                <label>Phone</label>
                <p><?php echo $family->getPhone() ?></p>
                <label>Phone type</label>
                <p><?php 
                if ($family->getPhoneType() == 'cellphone') {
                    echo 'Cell';
                } elseif ($family->getPhoneType() == 'home') {
                    echo 'Home';
                } elseif ($family->getPhoneType() == 'work') {
                    echo 'Work';
                }
                ?></p>
                <label>Secondary phone</label>
                <p><?php echo $family->getSecondaryPhone() ?></p>
                <label>Secondary phone type</label>
                <p><?php 
                if ($family->getSecondaryPhoneType() == 'cellphone') {
                    echo 'Cell';
                } elseif ($family->getSecondaryPhoneType() == 'home') {
                    echo 'Home';
                } elseif ($family->getSecondaryPhoneType() == 'work') {
                    echo 'Work';
                }
                ?></p>
            </fieldset>

            <!-- Only display Secondary Parent fieldset if there is information-->
            <?php
            if (
                !empty($family->getFirstName2()) || 
                !empty($family->getLastName2()) || 
                !empty($family->getBirthDate2()) || 
                !empty($family->getNeighborhood2()) || 
                !empty($family->getAddress2()) || 
                !empty($family->getCity2()) || 
                !empty($family->getState2()) || 
                !empty($family->getZip2()) || 
                $family->isHispanic2() !== null || 
                !empty($family->getRace2()) || 
                !empty($family->getEmail2()) || 
                !empty($family->getPhone2()) || 
                !empty($family->getPhoneType2()) || 
                !empty($family->getSecondaryPhone2()) || 
                !empty($family->getSecondaryPhoneType2())
            ): ?>

            <h3>Secondary Parent / Guardian</h3>
            <fieldset>
                <legend>General Information</legend>
                    <?php if (!empty($family->getFirstName2()) || !empty($family->getLastName2())): ?>
                        <label>Name</label>
                        <p><?php echo (!empty($family->getFirstName2()) ? $family->getFirstName2() : '') . " " . 
                            (!empty($family->getLastName2()) ? $family->getLastName2() : ''); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getBirthDate2())): ?>
                        <label>Birthdate</label>
                        <p><?php echo $family->getBirthDate2() ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getNeighborhood2())): ?>
                        <label>Neighborhood</label>
                        <p><?php echo $family->getNeighborhood2() ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getAddress2()) || !empty($family->getCity2()) || !empty($family->getState2()) || !empty($family->getZip2())): ?>
                        <label>Address</label>
                        <p><?php echo $family->getAddress2() . ", " . $family->getCity2() . ", " . $family->getState2() . " " . $family->getZip2() ?></p>
                    <?php endif; ?>
                    
                    <?php if ($family->isHispanic2() !== null): ?>
                        <label>Hispanic, Latino, or Spanish Origin</label>
                        <p><?php 
                        if ($family->isHispanic2() == 1) {
                            echo 'Yes';
                        } elseif ($family->isHispanic2() == 0) {
                            echo 'No';
                        }
                        ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getRace2())): ?>
                        <label>Race</label>
                        <p><?php echo $family->getRace2() ?></p>
                    <?php endif; ?>
                </fieldset>

                <fieldset>
                    <legend>Contact Information</legend>
                    <?php if (!empty($family->getEmail2())): ?>
                        <label>Email</label>
                        <p><?php echo $family->getEmail2() ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getPhone2())): ?>
                        <label>Phone</label>
                        <p><?php echo $family->getPhone2() ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getPhoneType2())): ?>
                        <label>Phone type</label>
                        <p><?php 
                        if ($family->getPhoneType2() == 'cellphone') {
                            echo 'Cell';
                        } elseif ($family->getPhoneType2() == 'home') {
                            echo 'Home';
                        } elseif ($family->getPhoneType2() == 'work') {
                            echo 'Work';
                        }
                        ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getSecondaryPhone2())): ?>
                        <label>Secondary phone</label>
                        <p><?php echo $family->getSecondaryPhone2() ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($family->getSecondaryPhoneType2())): ?>
                        <label>Secondary phone type</label>
                        <p><?php 
                        if ($family->getSecondaryPhoneType2() == 'cellphone') {
                            echo 'Cell';
                        } elseif ($family->getSecondaryPhoneType2() == 'home') {
                            echo 'Home';
                        } elseif ($family->getSecondaryPhoneType2() == 'work') {
                            echo 'Work';
                        }
                        ?></p>
                    <?php endif; ?>
                </fieldset>
            <?php endif; ?>

            <h3>Emergency Contact</h3>
            <fieldset>
                <label>Name</label>
                <p><?php echo $family->getEContactFirstName() . " " . $family->getEContactLastName() ?></p>
                <label>Phone</label>
                <p><?php echo $family->getEContactPhone() ?></p>
                <label>Relation</label>
                <p><?php echo $family->getEContactRelation() ?></p>
            </fieldset>

            <h3>Household Information</h3>
            <fieldset>
                <label>Income</label>
                <p><?php echo $family->getIncome() ?></p>
                <!--Household Languages-->
                <!--Current Assistance-->
                <!--Add once there are get() for these atttributes-->
            </fieldset>
        
            <!--If staff account, displays summarries of children in table format with links to their own account pages-->
            <?php if($_SESSION['access_level'] > 1 && isset($children) && !empty($children)) {
                echo '<h3>Children Summary</h3>';
                echo '<fieldset>';
                echo '<p>Click on an Account ID to view or edit that child\'s account.</p>';
                echo '
                <div class="table-wrapper">
                    <table class="general">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>Full Name</th>';
                            echo '<th>Date of Birth</th>';
                            echo '<th>Gender</th>';
                            echo '<th>Medical Notes</th>';
                            echo '<th>Other Notes</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody class="standout">';
                    foreach ($children as $acct) {
                        $id = $acct->getID();
                        echo "<tr onclick=\"window.location.href='childAccount.php?id=$id'\" style='cursor: pointer;'>";
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
                echo '</fieldset>';
            } 
            ?>
        </div>


        <!-- Archive Family Buttons -->
        <?php if($_SESSION['access_level'] > 1 && !$family->isArchived()): ?>
        <form method="post">
            <button type="submit" name="archive" class="button_stlye">Archive Family</button>
        </form>
        <?php endif?>
        <?php if($_SESSION['access_level'] > 1 && $family->isArchived()): ?>
        <form method="post">
            <button type="submit" name="unarchive" class="button_stlye">Unarchive Family</button>
        </form>
        <?php endif?>

        <!-- Edit Family Button -->
        <?php if($_SESSION['access_level'] > 1): ?>
            <a class="button edit" href="editFamilyProfile.php?id=<?php echo $family->getId(); ?>" style="margin-top: .5rem;">Edit Family</a>
        <?php endif?>
        <?php if($accessLevel > 1): ?> <!--Option for staff to fill a form out for family-->
            <a class="button edit" href="fillForm.php?id=<?php echo $family->getId(); ?>" style="margin-top: .5rem;">Fill Form</a>   
        <?php endif?>
        <?php if($accessLevel > 1): ?> <!--Option for staff to fill a form out for family-->
            <a class="button edit" href="forgotPassword.php?id=<?php echo $family->getId(); ?>" style="margin-top: .5rem;">Change Password</a>   
        <?php endif?>

        <!-- Cancel Buttons -->
        <?php if($_SESSION['access_level'] == 1): ?>
            <a class="button cancel button_stlye" href="familyAccountDashboard.php" style="margin-top: 1rem;">Return to Dashboard</a>
        <?php endif ?>
        <?php if($_SESSION['access_level'] > 1): ?>
            <a class="button cancel button_stlye" href="findFamily.php" style="margin-top: 1rem;">Return to Search</a>
        <?php endif?>   
    </body>
</html>

