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


                </div>
            </div>
            <a class="button cancel" href="index.php" style="margin-top: 3rem;">Return to Dashboard</a>
        </main>
    </body>
</html>

