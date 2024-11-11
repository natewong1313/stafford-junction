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
        <main class='dashboard'>
            <?php echo "<p>Hello" . $_SESSION['f_name'] . "!</p>";?>
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

                <!--Dashboard button that allows the family account to add another child to the account-->
                <div class="dashboard-item" data-link="addChild.php">
                    <img src="images/add-square-svgrepo-com.svg">
                    <span>Add Child</span>
                </div>
                
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>