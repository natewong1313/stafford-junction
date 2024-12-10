<?php

session_cache_expire(30);
session_start();

if($_SESSION['logged_in'] == false){
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require ('universal.inc'); ?>
        <title>Account Page</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Family Dashboard</h1>
        <?php if (isset($_GET['addChildSuccess'])) {
            echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Children submitted successfully!</div>';
        }
        ?>
        <?php if (isset($_GET['formSubmitSuccess'])) {
            echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Form Submitted Successfully!</div>';
        }
        ?>
        <?php if (isset($_GET['formSubmitFailure'])) {
            echo '<div class="error-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Failed to Enroll! Child already enrolled or inelligible</div>';
        }
        ?>
        <?php if(isset($_GET['updateSuccess'])){
            echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Profile Updated!</div>';
        }
        ?>
        <main class='dashboard'>
        <?php if (isset($_GET['pcSuccess'])): ?>
            <div class="happy-toast">Password changed successfully!</div>
        <?php endif ?>
        <?php echo "<p>Hello " . ucfirst($_SESSION['f_name']) . "!</p>"; ?>

            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <!--dashboard item to view the account information of user -->
                <div class="dashboard-item" data-link="familyView.php">
                    <img src="images/person-search.svg">
                    <span>View Account</span>
                </div>

                <!--dashboard item to view a summary of all children in account -->
                <div class="dashboard-item" data-link="childrenInAccount.php">
                    <img src="images/children-svgrepo-com.svg">
                    <span>View Children Accounts</span>
                </div>

                <!--Dashboard button that directs the user to the forms page-->
                <div class="dashboard-item" data-link="fillForm.php">
                    <img src="images/form-dropdown-svgrepo-com.svg">
                    <span>Fill Out Form</span>
                </div>

                <!--Dashboard button that directs the user to edit personal profile-->
                <div class="dashboard-item" data-link="editFamilyProfile.php">
                    <img src="images/editProfile.svg">
                    <span>Edit Profile</span>
                </div>
                
                <!--Dashboard button that allows the family account to add another child to the account-->
                <div class="dashboard-item" data-link="addChild.php">
                    <img src="images/add-square-svgrepo-com.svg">
                    <span>Add Child</span>
                </div>

                <!--Dashboard button that directs user to change password page-->
                <div class="dashboard-item" data-link="changePassword.php">
                    <img src="images/change-password.svg">
                    <span>Change Password</span>
                </div>

                <!--Dashboard button that directs the user to logout-->
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>