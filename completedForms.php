<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if(isset($_SESSION['_id'])){
    $loggedIn = true;
    $accessLevel = 1;
    $userID = $_SESSION['_id'];
}else {
    header("Location: login.php");
}

//include these files here
require_once("database/dbFamily.php");
require_once("database/dbHolidayMealBag.php");

/**
 * Below is where all of the forms for family accounts are retrieved
 */

//retrieve data from holiday meal bag database; if there is no data to retrieve, this will be null
$holiday_meal_bag_form = get_data_by_family_id($userID);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Children Accounts</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Completed Forms</h1>

        <!--Check that holiday meal bag form is complete, if it is, have a link that will direct the user to filled out form-->
        <?php if($holiday_meal_bag_form != null): ?>
            <h2 style="margin-left: 20px; display: inline;">Holiday Meal Bag</h2>
            <a href="holidayMealBagComplete.php">  View</a>
        <?php endif ?>
        

    </body>
</html>
