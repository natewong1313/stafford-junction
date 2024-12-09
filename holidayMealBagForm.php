<?php

session_cache_expire(30);
session_start();

if (!isset($_SESSION["_id"])) {
    header("Location: login.php");
    die();
}

$accessLevel = $_SESSION['access_level'];
$userID = $_SESSION['_id'];
$successMessage = "";
include_once("database/dbFamily.php");
$family = retrieve_family_by_id($_GET['id'] ?? $userID); //$_GET['id] will have the family id needed to fill form if the staff are trying to fill a form out for that family
$family_email = $family->getEmail();
$family_full_name = $family->getFirstName() . " " . $family->getLastName();
$family_full_addr = $family->getAddress() . ", " . $family->getCity() . ", " . $family->getState() . ", " . $family->getZip();
$family_phone = $family->getPhone();

$children = retrieve_children_by_family_id($_GET['id'] ?? $userID);
$children_count = count($children);

// Handle form deletion request
include_once("database/dbHolidayMealBag.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Call the delete function and handle errors or success messages
    $result = deleteHolidayMealBagForm($family->getId());
    if ($result) {
        $successMessage = "Form deleted successfully!";
        echo '<script>document.location = "fillForm.php?formDeleteSuccess&id=' . $_GET['id'] . '";</script>';
        exit; // Ensure the script stops here after redirection
    } else {
        $errors[] = "Error deleting the form.";
    }
}

function validateAndFilterPhoneNumber($phone) {
    $filteredPhone = preg_replace('/\D/', '', $phone);  // Remove non-digits
    return (strlen($filteredPhone) === 10) ? $filteredPhone : false;
}

include_once('database/dbinfo.php');
try {
    //Retrieve the data from the database. If the user has already filled out this form, this variable will store the users data
    //$data = getHolidayMealBagData($userID);
    $data = getHolidayMealBagData($family->getId()); //its possible that userID would store the staff id instead, so its important to use the family object retrieved by $_GET['id'] up top to ensure we are checking for the family
    //If the user hasn't submitted this form yet
    if($data == null){
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
            $id = $family->getId();

            
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
                    values ('$id', '$email', '$household', '$meal_bag', '$name', '$address', '$phone', '$photo_release');
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

    <form method="POST">
        <!-- 1. Email -->
        <label for="email">Email *</label><br>
        <?php if($data): ?>
            <input type="email" style="background-color: yellow; color: black;" name="email" disabled value="<?php echo htmlspecialchars($data['email']); ?>"><br><br>
        <?php elseif (!$data): ?>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($family_email); ?>"><br><br>
        <?php endif ?>
        
        <!-- 2. How many in household -->
        <label for="household">How many in household / ¿Cuántas personas hay en su hogar? *</label><br>
        <?php if($data): ?>
            <input type="number" style="background-color: yellow; color: black;" id="household" name="household" disabled value="<?php echo htmlspecialchars($data['household_size']); ?>"><br><br>
        <?php elseif (!$data): ?>
            <input type="number" id="household" name="household" required value="<?php echo htmlspecialchars($children_count); ?>"><br><br>
        <?php endif ?>

        <!-- 3. Would you like a Holiday Meal Bag? -->
        <p>Would you like a Holiday Meal Bag? / ¿Desea una de bolsa de comida para los dias festivos? *</p>
        <?php if($data): ?>
            <p>Selected: <?php echo htmlspecialchars($data['meal_bag']);?></p><br>
        <?php elseif(!$data): ?>
            <input type="radio" id="thanksgiving" name="meal_bag" value="Thanksgiving" required>
            <label for="thanksgiving">Thanksgiving / Acción de gracias</label><br>
            <input type="radio" id="christmas" name="meal_bag" value="Christmas" required>
            <label for="christmas">Christmas / Navidad</label><br>
            <input type="radio" id="both" name="meal_bag" value="Both" required>
            <label for="both">Both / los dos</label><br><br>
        <?php endif ?>

        <!-- 4. Name -->
        <label for="name">Name / Nombre *</label><br>
        <?php if($data): ?>
            <input style="background-color: yellow; color: black;"type="text" id="name" name="name" disabled value="<?php echo htmlspecialchars($data['name']); ?>"><br><br>
        <?php elseif(!$data): ?>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($family_full_name); ?>"><br><br>
        <?php endif ?>

        <!-- 5. Complete Address -->
        <label for="address">Complete Address / Dirección completa *</label><br>
        <?php if($data): ?>
            <input style="background-color: yellow; color: black;" type="text" id="address" name="address" disabled value="<?php echo htmlspecialchars($data['address']); ?>"><br><br>
        <?php elseif(!$data): ?>
            <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($family_full_addr); ?>"><br><br>
        <?php endif?>

        <!-- 6. Phone Number -->
        <label for="phone">Phone Number / Número de teléfono *</label><br>
        <?php if($data): ?>
            <input style="background-color: yellow; color: black;" type="tel" id="phone" name="phone" disabled  value="<?php echo htmlspecialchars($data['phone']); ?>"><br><br>
        <?php elseif(!$data): ?>
            <input type="tel" id="phone" name="phone" required  value="<?php echo htmlspecialchars($family_phone); ?>"><br><br>
        <?php endif?>

        <!-- 7. Photo Release -->
        <p>Stafford Junction Photo Release / Autorización de Prensa de Stafford Junction *</p>
        <?php if($data): 
            $choice = "no";
                if($data['photo_release'] == 1){
                    $choice = "yes";
                } ?>
            <p>Selected: <?php echo $choice ?></p><br>
        <?php elseif(!$data): ?>
            <input type="radio" id="release_yes" name="photo_release" value="1" required>
            <label for="release_yes">Yes / Si</label><br>
            <input type="radio" id="release_no" name="photo_release" value="0" required>
            <label for="release_no">No / No</label><br><br>
        <?php endif ?>

        <?php if($data): ?>
            <!-- Add a Delete button if the form has been submitted -->
            <form method="POST" action="holidayMealBagForm.php">
                <button type="submit" name="delete">Delete</button>
            </form>

            <?php if($accessLevel > 1):?> <!--If staff or admin, return back to index.php-->
                <a class="button cancel" href="index.php">Return to Dashboard</a>
            <?php else: ?> <!--If family, return back to family home page-->
                <a class="button cancel" href="familyAccountDashboard.php">Return to Dashboard</a>
            <?php endif ?>
        <?php else: ?>
        <button type="submit">Submit</button><br>
        <?php 
            if (isset($_GET['id'])) {
                echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel</a>';
            } else {
                echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>';
            }
        ?>
        </div>
        <?php
           //if registration successful, create pop up notification and direct user back to login
           if($_SERVER['REQUEST_METHOD'] == "POST" && $successMessage != ""){
                if (isset($_GET['id'])) {
                    echo '<script>document.location = "fillForm.php?formSubmitSuccess&id=' . $_GET['id'] . '";</script>';
                } else {
                    echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
                }
            } else if ($_SERVER['REQUEST_METHOD'] == "POST" && $successMessage == "") {
                if (isset($_GET['id'])) {
                    echo '<script>document.location = "fillForm.php?formSubmitFail&id=' . $_GET['id'] . '";</script>';
                } else {
                    echo '<script>document.location = "fillForm.php?formSubmitFail";</script>';
                }
            }
        ?>
        <?php endif ?>
    </form>
</div>
    


</body>
</html>

