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

//include these files here for all forms
require_once("database/dbFamily.php");
require_once("database/dbHolidayMealBag.php");
require_once("database/dbSchoolSuppliesForm.php");
require_once("database/dbAngelGiftForm.php");
require_once('database/dbProgramInterestForm.php');

/**
 * Below is where all of the forms for family accounts are retrieved
 */

//retrieve data from holiday meal bag database; if there is no data to retrieve, this will be null
$holiday_meal_bag_form = get_data_by_family_id($userID);
$school_supplies_forms = get_school_supplies_form_by_family_id($userID);
$angel_gift_forms = get_angel_gift_forms_by_family_id($userID);
$programInterestForm = getProgramInterestFormData($userID);

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
        <link rel="stylesheet" href="css/base.css" type="text/css" />
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Completed Forms</h1>

        <!--Check that holiday meal bag form is complete, if it is, have a link that will direct the user to filled out form-->
        <?php if($holiday_meal_bag_form != null): ?>
            <h2 style="margin-left: 20px; display: inline;">Holiday Meal Bag</h2>
            <a href="holidayMealBagForm.php" class="inline-button">View</a><br>
        <?php endif ?>

        <!-- Program Interest Forms -->
        <?php if($programInterestForm != null): ?>
            <h2 style="margin-left: 20px; display: inline;">Program Interest Form</h2>
            <a href="programInterestForm.php" class="inline-button">View</a><br>
        <?php endif ?>

        <!--Print out the school supplies form for each child in the family-->
        <?php if (!empty($school_supplies_forms)): ?>
            <h2 style="margin-left: 20px; display: inline;">School Supplies Forms</h2>
            <ul style="margin-left: 40px;"> <!-- Indent all list items -->
                <?php foreach ($school_supplies_forms as $form): ?>
                    <li>
                        Child Name: <?= htmlspecialchars($form['child_name']) ?>
                        <a href="schoolSuppliesForm.php?form_id=<?=$form['id'] ?>" class="inline-button">View</a>
                   </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!--Print out the angel gift form for the family-->
        <?php if (!empty($angel_gift_forms)): ?>
            <h2 style="margin-left: 20px; display: inline;">Angel Gift Forms</h2>
            <ul style="margin-left: 40px;"> <!-- Indent all list items -->
                <?php foreach ($angel_gift_forms as $form): ?>
                    <li>
                        Child Name: <?= htmlspecialchars($form['child_name']) ?>
                        <a href="angelGiftForm.php" class="inline-button">View</a><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </body>
</html>
