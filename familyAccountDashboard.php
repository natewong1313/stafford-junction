<?php

session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require ('universal.inc'); ?>
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Family Dashboard</h1>
        <main class='dashboard'>
            <?php echo "<p>Hello" . $_SESSION['f_name'] . "!</p>";?>
            <?php if (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['deleteService'])): ?>
                <div class="happy-toast">Service successfully removed!</div>
            <?php elseif (isset($_GET['serviceAdded'])): ?>
                <div class="happy-toast">Service successfully added!</div>
            <?php elseif (isset($_GET['animalRemoved'])): ?>
                <div class="happy-toast">Animal successfully removed!</div>
            <?php elseif (isset($_GET['locationAdded'])): ?>
                <div class="happy-toast">Location successfully added!</div>
            <?php elseif (isset($_GET['deleteLocation'])): ?>
                <div class="happy-toast">Location successfully removed!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php endif ?>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <div class="dashboard-item" data-link="">
                    <img src="images/person-search.svg">
                    <span>View Account</span>
                </div>
                <!--Dashboard button that directs the user to the forms page-->
                <div class="dashboard-item" data-link="fillForm.php">
                    <img src="images/form-dropdown-svgrepo-com.svg">
                    <span>Fill Out Form</span>
                </div>
                
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>