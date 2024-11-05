<?php
//start session and check login status
session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

//allow only logged-in users to submit the form
if (!$loggedIn || $accessLevel < 1) {
    die("Access denied. Please log in to access the form.");
}

//database connection

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
        $photo_release = filter_var($_POST['photo_release'], FILTER_VALIDATE_BOOLEAN);

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
            $stmt = $conn->prepare("INSERT INTO dbHolidayMealBagForm (family_id, email, household_size, meal_bag, name, address, phone, photo_release)
                                    VALUES (:family_id, :email, :household_size, :meal_bag, :name, :address, :phone, :photo_release)");

            $stmt->bindParam(':family_id', $userID);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':household_size', $household);
            $stmt->bindParam(':meal_bag', $meal_bag);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':photo_release', $photo_release, PDO::PARAM_BOOL);

            if ($stmt->execute()) {
                $successMessage = "Form submitted successfully!";
            } else {
                $errors[] = "Error submitting form.";
            }
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
    <input type="email" id="email" name="email" required><br><br>

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
    <input type="text" id="name" name="name" required><br><br>

    <!-- 5. Complete Address -->
    <label for="address">Complete Address / Dirección completa *</label><br>
    <input type="text" id="address" name="address" required><br><br>

    <!-- 6. Phone Number -->
    <label for="phone">Phone Number / Número de teléfono *</label><br>
    <input type="tel" id="phone" name="phone" required><br><br>

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

