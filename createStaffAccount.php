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


<<!DOCTYPE html>
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
    </body>
</html>