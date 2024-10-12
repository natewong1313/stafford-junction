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
        <title>Stafford Junction Homepage</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Stafford Junction Dashboard</h1>
        <main class='dashboard'>
            <div id="dashboard">
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>

