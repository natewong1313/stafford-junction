<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <title>Stafford Junction | Forms</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Forms</h1>
        <main class='dashboard'>
            <div id="dashboard">
                <div class="dashboard-item" data-link="holidayMealBagForm.php">
                    <img src="images/holdiayMealBagIcon.svg">
                    <span>Holiday Meal Bag Form</span>
                </div>

                <div class="dashboard-item" data-link="schoolSuppliesForm.php">
                    <img src="images/school-supplies-svgrepo-com.svg">
                    <span>School Supplies Form</span> </div>


                    <div class="dashboard-item" data-link="springBreakForm.php">
                    <img src="images/tent-svgrepo-com.svg">
                    <span>Spring Break Form</span> </div>

                    <div class="dashboard-item" data-link="angelGiftForm.php">
                    <img src="images/angel.svg">
                    <span>Angel Gifts Wish Form</span> </div>

                    <div class="dashboard-item" data-link="actualActivityForm.php">
                    <img src="images/actualActivity-svgrepo.svg">
                    <span>Actual Activity Form</span> </div>

                    <div class="dashboard-item" data-link="childCareWaiverForm.php">
                    <img src="images/signature.svg">
                    <span>Child Care Waiver Form</span> </div>

                    <div class="dashboard-item" data-link="fieldTripWaiver.php">
                    <img src="images/location.svg">
                    <span>Field Trip Waiver Form</span> </div>

                    <div class="dashboard-item" data-link="programInterestForm.php">
                    <img src="images/interest.svg">
                    <span>Program Interest Form</span> </div>

                    <div class="dashboard-item" data-link="brainBuildersRegistrationForm.php">
                    <img src="images/brainBuilders.svg">
                    <span>Brain Builders Student Registration Form</span> </div>
                </div>
            </div>
            <!--Below deals with the 'Return to dashboard'. Since multiple dashboards share this file, the dashboard to return to will be different depending on access level-->
            
            <!--If the user is a family account, the button will return to the familyAccountDashboard homepage-->
            <?php if($_SESSION['access_level'] == 1): ?> 
            <a class="button cancel" href="familyAccountDashboard.php" style="margin-top: 3rem;">Return to Dashboard</a>
            <?php endif ?>
            <!-- If the user is staff, the button will return to the index.php homepage -->
            <?php if($_SESSION['access_level'] > 1): ?>
            <a class="button cancel" href="index.php" style="margin-top: 3rem;">Return to Dashboard</a>
            <?php endif ?>
        </main>
    </body>
</html>

