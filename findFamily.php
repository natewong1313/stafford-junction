<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
// admin-only access
if ($accessLevel < 2) {
    header('Location: index.php');
    die();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once("database/dbFamily.php");
    require_once("domain/Family.php");
    require_once("include/input-validation.php");

    $args = sanitize($_POST, null);

    $family = retrieve_family($args); //retrieves family by email for now (may change late)

}
?>

!<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Find Family Account</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Search Family Account</h1>

        <form id="formatted_form" method="POST">
            <label for="email">Account Email</label>
            <input type="text" name="email" required placeholder="Email">
            <button type="submit">Search</button>

            <?php
            if(isset($family)){
                echo "<h3>Search Results</h3>";
                echo "<h4>First Name: " . $family->getFirstName();
            }

            ?>
        </form>

        
        
    </body>
</html>