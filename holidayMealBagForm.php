<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <title>Holiday Meal Bag 2023</title>
</head>
<body>

<?php

session_cache_expire(30);
session_start();

if (!isset($_SESSION["_id"])) {
    header("Location: login.php");
    die();
}
include_once("database/dbFamily.php");
$family = get_family_by_person_id($_SESSION["_id"]);
$family_email = $family->getEmail();
$family_full_name = $family->getFirstName() . " " . $family->getLastName();
$family_full_addr = $family->getAddress() . ", " . $family->getCity() . ", " . $family->getState() . ", " . $family->getZip();
$family_phone = $family->getPhone();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $household = (int) $_POST['household'];  // Cast to int for question 2
    $meal_bag = $_POST['meal_bag'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $photo_release = filter_var($_POST['photo_release'], FILTER_VALIDATE_BOOLEAN);  // Convert to boolean for question 7

    // Validation for Question 2 (Household) and Question 7 (Photo Release)
    $errors = [];

    // Validate that household is a positive integer
    if (!is_int($household) || $household <= 0) {
        $errors[] = "Please enter a valid number for household members.";
    }

    // Validate that photo_release is a boolean (it should be true or false)
    if ($photo_release === null) {
        $errors[] = "Please select a valid option for photo release.";
    }

    // Check if all other required fields are filled
    if (empty($email) || empty($meal_bag) || empty($name) || empty($address) || empty($phone)) {
        $errors[] = "Please fill out all required fields.";
    }

    // If no errors, process the form data
    if (empty($errors)) {
        echo "<h3>Form submitted successfully!</h3>";
        echo "Email: $email<br>";
        echo "Household: $household<br>";
        echo "Meal Bag: $meal_bag<br>";
        echo "Name: $name<br>";
        echo "Address: $address<br>";
        echo "Phone: $phone<br>";
        echo "Photo Release: " . ($photo_release ? "Yes" : "No") . "<br>";
    } else {
        // Display errors
        echo "<h3 style='color: red;'>Please correct the following errors:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>

<h1>Holiday Meal Bag 2023</h1>
    <div id="formatted_form">

<p>*Subject to availability / Sujeto a disponibilidad</p>
<p>*Based on donations, requests will be processed on a first-come, first-served basis / Basado en donaciones, solicitudes seran procesadas en orden que sean recibidas</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <!-- 1. Email -->
    <label for="email">Email *</label><br>
    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($family_email); ?>"><br><br>
    <!-- 2. How many in household -->
    <label for="household">How many in household / ¿Cuántas personas hay en su hogar? *</label><br>
    <input type="number" id="household" name="household" required><br><br>

    <!-- 3. Would you like a Holiday Meal Bag? -->
    <p>Would you like a Holiday Meal Bag? / ¿Desea una de bolsa de comida para los dias festivos? *</p>
    <input type="radio" id="thanksgiving" name="meal_bag" value="Thanksgiving" required>
    <label for="thanksgiving">Thanksgiving / Acción de gracias</label><br>
    <input type="radio" id="christmas" name="meal_bag" value="Christmas" required>
    <label for="christmas">Christmas / Navidad</label><br>
    <input type="radio" id="both" name="meal_bag" value="Both" required>
    <label for="both">Both / los dos</label><br><br>

    <!-- 4. Name -->
    <label for="name">Name / Nombre *</label><br>
    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($family_full_name); ?>"><br><br>

    <!-- 5. Complete Address -->
    <label for="address">Complete Address / Dirección completa *</label><br>
    <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($family_full_addr); ?>"><br><br>

    <!-- 6. Phone Number -->
    <label for="phone">Phone Number / Número de teléfono *</label><br>
    <input type="tel" id="phone" name="phone" required  value="<?php echo htmlspecialchars($family_phone); ?>"><br><br>

    <!-- 7. Photo Release -->
    <p>Stafford Junction Photo Release / Autorización de Prensa de Stafford Junction *</p>
    <input type="radio" id="release_yes" name="photo_release" value="1" required>
    <label for="release_yes">Yes / Si</label><br>
    <input type="radio" id="release_no" name="photo_release" value="0" required>
    <label for="release_no">No / No</label><br><br>

    <button type="submit">Submit</button>
    <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
</form>
    </div>
</body>
</html>

