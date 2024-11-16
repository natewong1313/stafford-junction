<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

if(isset($_SESSION['_id'])){
    //require_once('database/dbStaff.php');
    require_once('include/input-validation.php');
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
}

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Create Staff Account</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require_once("universal.inc");?>
    </head>
    <body>
        <?php require_once("header.php");?>
        <h1>Create Staff Account</h1>

        <main class="signup-form">
            <form class="signup-form" method="POST">
                <h2>Staff Account Registration Form</h2>
                <p>Please fill out each section of the following form if you would like to become a member of Stafford Junction</p>
                <p>An asterisk (*) indicates a required field.</p>

                <!--First Name-->
                <label for="firstName">First Name *</label>
                <input type="text" name="firstName" placeholder="Enter your first name" required>

                <!--Last Name-->
                <label for="lastName">Last Name *</label>
                <input type="text" name="lastName" placeholder="Enter your last name" required>

            </form>
        </main> 
    </body>
</html>