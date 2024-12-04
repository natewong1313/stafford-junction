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
        <title>Stafford Junction | Staff Forms</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Staff Forms</h1>
        <?php 
            if (isset($_GET['formSubmitSuccess'])) {
                echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Form Successfully Submitted!</div>';
            }
        ?>
        <main class='dashboard'>
            <div id="dashboard">
                <?php if ($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="busMonitorAttendanceForm.php">
                    <img src="images/school-bus-vehicle-svgrepo-com.svg">
                    <span>Bus Monitor Attendance Form</span>
                </div>

                <div class="dashboard-item" data-link="actualActivityForm.php">
                        <img src="images/actualActivity-svgrepo.svg">
                        <span>Actual Activity Form</span> 
                    </div>
                <?php endif ?>
                </div>
            </div>
            
            <!--Return to Staff Dashboard-->
            <a class="button cancel" href="index.php" style="margin-top: 3rem;">Return to Dashboard</a>
        </main>
    </body>
</html>

