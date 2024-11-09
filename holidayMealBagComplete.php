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

include_once("database/dbFamily.php");

//retrieve holiday meal bag data
$data = getHolidayMealBagData($userID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <title>Holiday Meal Bag <?php echo date("Y"); ?></title>
</head>
    <body>
    <?php require_once("header.php"); ?>

    <h1>Holiday Meal Bag <?php echo date("Y"); ?></h1>
    <div id="formatted_form">

        <p>*Subject to availability / Sujeto a disponibilidad</p>
        <p>*Based on donations, requests will be processed on a first-come, first-served basis / Basado en donaciones, solicitudes seran procesadas en orden que sean recibidas</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <!-- 1. Email -->
            <label for="email">Email *</label><br>
            <input type="email" style="background-color: yellow; color: black;" name="email" disabled value="<?php echo htmlspecialchars($data['email']); ?>"><br><br>
            <!-- 2. How many in household -->
            <label for="household">How many in household / ¿Cuántas personas hay en su hogar? *</label><br>
            <input type="number" style="background-color: yellow; color: black;" id="household" name="household" disabled value="<?php echo htmlspecialchars($data['household_size']); ?>"><br><br>

            <!-- 3. Would you like a Holiday Meal Bag? -->
            <p>Would you like a Holiday Meal Bag? / ¿Desea una de bolsa de comida para los dias festivos? *</p>
            <p>Selected: <?php echo htmlspecialchars($data['meal_bag']);?></p>

            <!-- 4. Name -->
            <label for="name">Name / Nombre *</label><br>
            <input style="background-color: yellow; color: black;"type="text" id="name" name="name" disabled value="<?php echo htmlspecialchars($data['name']); ?>"><br><br>

            <!-- 5. Complete Address -->
            <label for="address">Complete Address / Dirección completa *</label><br>
            <input style="background-color: yellow; color: black;" type="text" id="address" name="address" disabled value="<?php echo htmlspecialchars($data['address']); ?>"><br><br>

            <!-- 6. Phone Number -->
            <label for="phone">Phone Number / Número de teléfono *</label><br>
            <input style="background-color: yellow; color: black;" type="tel" id="phone" name="phone" disabled  value="<?php echo htmlspecialchars($data['phone']); ?>"><br><br>

            <!-- 7. Photo Release -->
            <p>Stafford Junction Photo Release / Autorización de Prensa de Stafford Junction *</p>
            
            <!-- Instead of dispaly 0 or 1, make a variable that holds yes or no and display that instead -->
            <?php 
                $choice = "no";
                if($data['photo_release'] == 1){
                    $choice = "yes";
                }
            ?>
            <p>Selected: <?php echo $choice ?></p>

            <a class="button cancel" href="familyAccountDashboard.php" style="margin-top: .5rem">Back to Home</a>
        </form>
    </div>
    </body>
</html>