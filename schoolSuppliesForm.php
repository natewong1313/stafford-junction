<?php
session_cache_expire(30); // Session cache expiry
session_start(); // Start the session
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;

// Check if user is logged in
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    $loggedIn = false;
}

// Include necessary files
include_once("database/dbFamily.php");
include_once("database/dbChildren.php");
require_once('database/dbSchoolSuppliesForm.php');

// Retrieve family information
if ($loggedIn) {
    $family = retrieve_family_by_id($_SESSION["_id"]);
    $family_email = $family->getEmail();
    //retrieve children by family ID
    $children = retrieve_children_by_family_id($_SESSION["_id"]);

}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once('include/input-validation.php');
    
    // Sanitize the form input
    $args = sanitize($_POST, null);

    $required = array("email", "name", "grade", "school", "community", "need_backpack");

    // Check if all required fields are submitted
    if (!wereRequiredFieldsSubmitted($args, $required)) {
        $error_message = "Not all fields complete"; // Set error message
    } else {
    // Call createBackToSchoolForm with sanitized form data
        $success = createBackToSchoolForm($args);
        
        if ($success) {
            echo "Form submitted successfully!";
        } else {
            echo "There was an error submitting the form.";
        }
    }
}
?>

<!-- Display the error message (if any) above the form -->
<?php if (isset($error_message)) { echo "<p style='color:red;'>{$error_message}</p>"; } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include_once("universal.inc")?>
        <title>School Supplies Form</title>
</head>
<body>

 <h1>Stafford Junction School Supplies / Utiles Escolares</h1>
    <div id="formatted_form">
        <p>Stafford Junction is holding a Back-to-School Community Day on August 10. This form will guarantee that your child will have a premade
        backpack before Community Day that can be picked up during the event. Please submit a form for each child
        </p>

        <p>Stafford Junction llevará a cabo un Día de la Comunidad de Regreso a la Escuela el 10 de agosto. Este formulario garantizará que su hijo tendrá una mochila con 
            útiles escolares antes del Día de la Comunidad que se puede recoger durante el evento. Por favor, envíe un formulario para cada niño.</p>

        <br>

        <span>* Indicates required</span><br><br>

        <form method="POST" action="schoolSuppliesForm.php">
            <!-- Email field (pre-filled from session) -->
            <label for="email">1. Email*</label><br><br>
            <input type="text" name="email" id="email" placeholder="Email" required value="<?php echo htmlspecialchars($family_email); ?>"><br><br>

            <!-- Child Name -->
            <label for="name">2. Child Name / Nombre del Estudiante*</label><br><br>
            <select name="name" id="name" required>
                <?php
                    require_once('domain/Children.php'); 
                    foreach ($children as $c){
                        $id = $c->getID();
                        // Check if form was already completed for the child
                        if (!isBackToSchoolFormComplete($id)) {
                            $name = $c->getFirstName() . " " . $c->getLastName();
                            $value = $id . "_" . $name;
                            echo "<option value='$value'>$name</option>";
                        }
                    }
                ?>
                </select>
                <br><br>

            <!-- Grade -->
            <label for="grade">3. Grade / Grado*</label><br><br>
            <input type="text" name="grade" id="grade" placeholder="Grade/Grado" required><br><br>

            <!-- School -->
            <label for="school">4. School / Escuela*</label><br><br>
            <input type="text" name="school" id="school" placeholder="School/Escuela" required><br><br>

            <!-- Community Bag Info -->
            <label>5. Will you pick up the bag during Community Day or need it brought to you? / ¿Recogerás la bolsa durante el Día de la Comunidad o necesitarás que te la traigan?</label><br><br>

            <p>Mark only one oval</p><br>
            <input type="radio" id="choice_1" name="community" value="pick_up" required>
            <label for="choice_1">I will pick up the bag on Community day (August 10, 12-3pm). / Recogeré la mochila durante el Día de la Comunidad (10 de Agosto, 12PM - 3PM)</label><br><br>

            <input type="radio" id="choice_2" name="community" value="no_pick_up" required>
            <label for="choice_2">I will not be able to attend Community Day, I will need the bag brought to me. / No puedo atender el Día de Comunidad, necesito que me traigan la mochila.</label><br><br>

            <input type="radio" id="choice_3" name="community" value="other" required>
            <label for="choice_3">Other</label>
            <input type="text" name="community" id="other" disabled>

            <script>
                // JavaScript to enable 'Other' input when 'Other' is selected
                const choice3 = document.getElementById('choice_3');
                const myInput = document.getElementById('other'); 

                choice3.addEventListener('change', () => {
                    if (choice3.checked) {
                        myInput.disabled = false;
                    }
                });

                document.getElementById('choice_1').addEventListener('change', () => { myInput.disabled = true; });
                document.getElementById('choice_2').addEventListener('change', () => { myInput.disabled = true; });
            </script>

            <!-- Backpack -->
            <br><br>

            <label>6. Will you need a backpack? / ¿Necesitarás una mochila?* </label><br><br>
            <p>Mark only one oval</p><br>
            <input type="radio" id="choice_a" name="need_backpack" value="have_backpack_already">
            <label for="choice_a">I already have a backpack, I just need school supplies. / Ya tengo mochila, solo necesito útiles escolares.</label><br><br>
            <input type="radio" id="choice_b" name="need_backpack" value="need_backpack">
            <label for="choice_b">I need a backpack. / Necesito una mochila.</label><br><br>
            <br><br>

            <button type="submit" id="submit">Submit</button>
            <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
        </form>
    </div>

</body>
</html>