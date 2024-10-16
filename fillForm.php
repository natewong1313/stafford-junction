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
                    <span>School Supplies Form</span>
                </div>
            </div>
        </main>
    </body>
</html>

