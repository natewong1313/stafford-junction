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
        <?php 
            if (isset($_GET['formSubmitSuccess'])) {
                echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Form Successfully Submitted!</div>';
            }
        ?>
        <main class='dashboard'>
            <div id="dashboard">
                <!--Holiday Meal Bag -->
                <!--will display this dashboard item if viewing the page from staff pov. This one has the GET superglobal, 
                which will be needed so that the id of the family is saved and able to be used when a staff member fills a form out on behalf of family-->
                <?php if(isset($_GET['id'])): ?>
                    <div class="dashboard-item" data-link="holidayMealBagForm.php?id=<?php echo $_GET['id']?>">
                    <img src="images/holdiayMealBagIcon.svg">
                    <span>Holiday Meal Bag Form</span>
                </div>
                <?php else: ?> <!--Otherwise, display this one-->
                <div class="dashboard-item" data-link="holidayMealBagForm.php">
                    <img src="images/holdiayMealBagIcon.svg">
                    <span>Holiday Meal Bag Form</span>
                </div>
                <?php endif?>

                <!--School Supplies Form -->
                <div class="dashboard-item" data-link="schoolSuppliesForm.php">
                    <img src="images/school-supplies-svgrepo-com.svg">
                    <span>School Supplies Form</span> 
                </div>

                <!--Spring Break Form-->
                <div class="dashboard-item" data-link="springBreakForm.php">
                    <img src="images/tent-svgrepo-com.svg">
                    <span>Spring Break Form</span> 
                </div>
                    
                <!-- Angel Wish Gift Form -->
                <div class="dashboard-item" data-link="<?php echo isset($_GET['id']) ? "angelGiftForm.php?id=" . $_GET['id'] : "angelGiftForm.php"; ?>">
                    <img src="images/angel.svg">
                    <span>Angel Gifts Wish Form</span>
                </div>

                <!--Child Care Waiver Form -->
                <div class="dashboard-item" data-link="<?php echo isset($_GET['id']) ? "childCareWaiverForm.php?id=" . $_GET['id'] : "childCareWaiverForm.php"; ?>">
                    <img src="images/signature.svg">
                    <span>Child Care Waiver Form</span> 
                </div>

                <!--Field Trip Waiver Form-->
                <div class="dashboard-item" data-link="fieldTripWaiver.php">
                    <img src="images/location.svg">
                    <span>Field Trip Waiver Form</span> 
                </div>

                <!-- Program Interest Form -->
                <div class="dashboard-item" data-link="<?php echo isset($_GET['id']) ? "programInterestForm.php?id=" . $_GET['id'] : "programInterestForm.php"; ?>">
                    <img src="images/interest.svg">
                    <span>Program Interest Form</span> 
                </div>

                <!--Brain Builders Student Registration Form-->
                <?php if(isset($_GET['id'])): ?>
                <div class="dashboard-item" data-link="brainBuildersRegistrationForm.php?id=<?php echo $_GET['id']?>">
                    <img src="images/brainBuilders.svg">
                    <span>Brain Builders Student Registration Form</span> 
                </div>
                <?php else: ?>
                <div class="dashboard-item" data-link="brainBuildersRegistrationForm.php">
                    <img src="images/brainBuilders.svg">
                    <span>Brain Builders Student Registration Form</span> 
                </div>
                <?php endif?>
     
                <!--Brain Builders Review Form-->
                <div class="dashboard-item" data-link="BrainBuilderReviewForm.php">
                    <img src="images/brainBuilderReviewIcon.svg.svg">
                    <span>Brain Builder Review Form</span> 
                </div>
                
                <!--If the active account is a staff filling out this form, send the family id in with GET variable, otherwise just go to normal holidayPartyForm-->
                <div class="dashboard-item" data-link="<?php echo isset($_GET['id']) ? "holidayPartyForm.php?id=" . $_GET['id'] : "holidayPartyForm.php"; ?>">
                    <img src="images/party-flyer-svgrepo-com.svg">
                    <span>Brain Builders Holiday Party Form</span> 
                </div>

                <!--Summer Junction Registration Form-->
                <div class="dashboard-item" data-link="summerJunctionRegistrationForm.php">
                    <img src="images/summerJunction.svg">
                    <span>Summer Junction Registration Form</span>
                </div>

                <!--Only staff and volunteer accounts can access these forms-->

                <!--Actual Activity Form-->
                <?php if ($_SESSION['access_level'] < 1 || $_SESSION['access_level'] > 1): ?>
                <div class="dashboard-item" data-link="busMonitorAttendanceForm.php">
                    <img src="images/school-bus-vehicle-svgrepo-com.svg">
                    <span>Bus Monitor Attendance Form</span>
                </div>
                <?php endif ?>

                <!--Only staff and volunteer accounts can access these forms-->
                <?php if ($_SESSION['access_level'] < 1 || $_SESSION['access_level'] > 1): ?>
                    <div class="dashboard-item" data-link="actualActivityForm.php">
                        <img src="images/actualActivity-svgrepo.svg">
                        <span>Actual Activity Form</span> 
                    </div>
                <?php endif ?>

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

