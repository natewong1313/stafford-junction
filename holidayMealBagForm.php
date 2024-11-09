<?php

session_cache_expire(30);
session_start();

if (!isset($_SESSION["_id"])) {
    header("Location: login.php");
    die();
}

$loggedIn = true;
$accessLevel = $_SESSION['access_level'];
$userID = $_SESSION['_id'];
$successMessage = "";
include_once("database/dbFamily.php");
$family = retrieve_family_by_id($userID);
$family_email = $family->getEmail();
$family_full_name = $family->getFirstName() . " " . $family->getLastName();
$family_full_addr = $family->getAddress() . ", " . $family->getCity() . ", " . $family->getState() . ", " . $family->getZip();
$family_phone = $family->getPhone();

function validateAndFilterPhoneNumber($phone) {
    $filteredPhone = preg_replace('/\D/', '', $phone);  // Remove non-digits
    return (strlen($filteredPhone) === 10) ? $filteredPhone : false;
}

include_once('database/dbinfo.php');
try {
    $conn = connect();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get form data
        $email = $_POST['email'];
        $household = (int) $_POST['household'];
        $meal_bag = $_POST['meal_bag'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $photo_release = $_POST['photo_release'];

        
        // Filter and validate the phone number
        $phone = validateAndFilterPhoneNumber($_POST['phone']);
        
        if (!$phone) {
            $errors[] = "Invalid phone number format.";
        }

        //validate form fields
        $errors = [];
        if ($household <= 0) {
            $errors[] = "Invalid household size.";
        }
        if ($photo_release === null) {
            $errors[] = "Photo release must be selected.";
        }
        if (empty($email) || empty($meal_bag) || empty($name) || empty($address) || empty($phone)) {
            $errors[] = "All fields are required.";
        }

        //insert data if no validation errors
        if (empty($errors)) {
            $query = "
                INSERT INTO dbHolidayMealBagForm (family_id, email, household_size, meal_bag, name, address, phone, photo_release)
                values ('$userID', '$email', '$household', '$meal_bag', '$name', '$address', '$phone', '$photo_release');
            ";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                $errors[] = "Error submitting form.";
            }else{
                $successMessage = "Form submitted successfully!";
            }
            $id = mysqli_insert_id($conn);
            mysqli_commit($conn);
            mysqli_close($conn);
        }
    }
} catch (PDOException $e) {
    $errors[] = "Connection failed: " . $e->getMessage();
}
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
    <?php if (!empty($successMessage)): ?>
        <h3><?php echo $successMessage; ?></h3>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <h3 style="color: red;">Please correct the following errors:</h3>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<h1>Holiday Meal Bag <?php echo date("Y"); ?></h1>
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
    <?php
        if($successMessage){
            echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
        }
    ?>
</form>
    </div>
</body>
</html>

