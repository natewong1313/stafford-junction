<?php
session_cache_expire(30); // Session cache expiry
session_start(); // Start the session
ini_set("display_errors", 1);
error_reporting(E_ALL);

if (!isset($_SESSION['_id'])) {
    header('Location: login.php');
    die();
}

$accessLevel = $_SESSION['access_level'];
$userID = $_SESSION['_id'];
$success = false;

// Include necessary files
include_once("database/dbFamily.php");
include_once("database/dbChildren.php");
require_once('database/dbSchoolSuppliesForm.php');

// Retrieve family information
$family = retrieve_family_by_id($_GET['id'] ?? $userID);
$family_email = $family->getEmail();
$children = retrieve_children_by_family_id($_GET['id'] ?? $userID);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once('include/input-validation.php');
    echo $success;
    
    // Sanitize the form input
    $args = sanitize($_POST, null);

    $required = array("email", "name", "grade", "school", "community", "need_backpack");

    // Check if all required fields are submitted
    if (!wereRequiredFieldsSubmitted($args, $required)) {
        $error_message = "Not all fields complete"; // Set error message
    } else {
        // Handle 'community' field where 'Other' might be selected
        $community = $_POST['community'] == 'other' ? $_POST['community_other'] : $_POST['community'];
        
        // Add the modified community field to $args
        $args['community'] = $community;

        // Call createBackToSchoolForm with sanitized form data
        $success = createBackToSchoolForm($args);
        
        if ($success) {
            $successMessage = "Form submitted successfully";
        }
    }
}
?>

<!-- Display the error message (if any) above the form -->
<?php if (isset($error_message)) { echo "<p style='color:red;'>{$error_message}</p>"; } ?>

<?php
// Pre-fill form data if form_id is provided and display as read-only if form is already submitted
$form_data = null;
if (isset($_GET['form_id'])) {
    $form_id = intval($_GET['form_id']);
    $form_data = getSchoolSuppliesFormById($form_id);
}
?>

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

        <form method="POST" action="">
            <!-- Email field (pre-filled from session) -->
            <label for="email">1. Email*</label><br><br>
            <input type="text" name="email" id="email" placeholder="Email" required value="<?php echo htmlspecialchars($family_email); ?>" <?php if ($form_data) echo 'disabled'; ?>><br><br>

            <!-- Child Name -->
            <label for="name">2. Child Name / Nombre del Estudiante*</label><br><br>
            <select name="name" id="name" required <?php if ($form_data) echo 'disabled'; ?>>
                <option disabled selected>Select a child</option>
                <?php
                    require_once('domain/Children.php'); 
                    foreach ($children as $c){
                        $id = $c->getID();
                        // Check if form was already completed for the child
                        if (!isBackToSchoolFormComplete($id)) {
                            $name = $c->getFirstName() . " " . $c->getLastName();
                            $value = $id . "_" . $name;
                            $selected = ($form_data && $form_data['child_name'] == $name) ? 'selected' : '';
                            echo "<option value='$value' $selected>$name</option>";
                        }
                    }
                ?>
                </select>
                <br><br>

            <!-- Grade -->
            <label for="grade">3. Grade / Grado*</label><br><br>
            <input type="text" name="grade" id="grade" placeholder="Grade/Grado" value="<?php echo htmlspecialchars($form_data['grade'] ?? ''); ?>" required <?php if ($form_data) echo 'disabled'; ?>><br><br>

            <!-- School -->
            <label for="school">4. School / Escuela*</label><br><br>
            <input type="text" name="school" id="school" placeholder="School/Escuela" value="<?php echo htmlspecialchars($form_data['school'] ?? ''); ?>" required <?php if ($form_data) echo 'disabled'; ?>><br><br>

            <script>
                const children = <?php echo json_encode($children); ?>;
                document.getElementById("name").addEventListener("change", (e) => {
                    const childId = e.target.value.split("_")[0];
                    const childData = children.find(child => child.id === childId);
                    document.getElementById("grade").value = childData.grade;
                    document.getElementById("school").value = childData.school;
                })
            </script>

            <!-- Community Bag Info -->
            <label>5. Will you pick up the bag during Community Day or need it brought to you? / ¿Recogerás la bolsa durante el Día de la Comunidad o necesitarás que te la traigan?</label><br><br>

            <p>Mark only one oval</p><br>
            <input type="radio" id="choice_1" name="community" value="pick_up" required <?php if ($form_data && $form_data['bag_pickup_method'] == 'pick_up') echo 'checked'; ?> <?php if ($form_data) echo 'disabled'; ?>>
            <label for="choice_1">I will pick up the bag on Community day (August 10, 12-3pm). / Recogeré la mochila durante el Día de la Comunidad (10 de Agosto, 12PM - 3PM)</label><br><br>

            <input type="radio" id="choice_2" name="community" value="no_pick_up" required <?php if ($form_data && $form_data['bag_pickup_method'] == 'no_pick_up') echo 'checked'; ?> <?php if ($form_data) echo 'disabled'; ?>>
            <label for="choice_2">I will not be able to attend Community Day, I will need the bag brought to me. / No puedo atender el Día de Comunidad, necesito que me traigan la mochila.</label><br><br>

            <input type="radio" id="choice_3" name="community" value="other" required <?php if ($form_data && $form_data['bag_pickup_method'] == 'other') echo 'checked'; ?> <?php if ($form_data) echo 'disabled'; ?>>
            <label for="choice_3">Other</label>
            <input type="text" name="community" id="other" value="<?php echo htmlspecialchars($form_data['community_other'] ?? ''); ?>" disabled <?php if ($form_data) echo 'disabled'; ?>><br><br>



            <script>
                // JavaScript to enable 'Other' input when 'Other' is selected
                const choice3 = document.getElementById('choice_3');
                const myInput = document.getElementById('other'); 

                // Enable "Other" text input when the 'Other' radio button is selected
                choice3.addEventListener('change', () => {
                    if (choice3.checked) {
                        myInput.disabled = false;
                        myInput.required = true; // Make the input field required when 'Other' is selected
                    }
                });

                // Disable "Other" input when the other options are selected
                document.getElementById('choice_1').addEventListener('change', () => { 
                    myInput.disabled = true;
                    myInput.required = false; // Make it not required
                });
                document.getElementById('choice_2').addEventListener('change', () => { 
                    myInput.disabled = true;
                    myInput.required = false; // Make it not required
                });

           </script>

            <!-- Backpack -->
            <br><br>

            <label>6. Will you need a backpack? / ¿Necesitarás una mochila?* </label><br><br>
            <p>Mark only one oval</p><br>
            <input type="radio" id="choice_a" name="need_backpack" value="have_backpack_already" <?php if ($form_data && $form_data['need_backpack'] == 'have_backpack_already') echo 'checked'; ?> <?php if ($form_data) echo 'disabled'; ?>>
            <label for="choice_a">I already have a backpack, I just need school supplies. / Ya tengo mochila, solo necesito útiles escolares.</label><br><br>

            <input type="radio" id="choice_b" name="need_backpack" value="need_backpack" <?php if ($form_data && $form_data['need_backpack'] == 'need_backpack') echo 'checked'; ?> <?php if ($form_data) echo 'disabled'; ?>>
            <label for="choice_b">I need a backpack. / Necesito una mochila.</label><br><br>

            <?php if (!$form_data): ?>
                <button type="submit" id="submit">Submit</button>
                <?php
                    if (isset($_GET['id'])) {
                        echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel</a>';
                    } else {
                        echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>';
                    }
                ?>
            <?php endif; ?>

            <?php if ($form_data): ?>
                <!-- Delete Button for Submitted Forms -->
                <form method="POST" action="database/dbSchoolSuppliesForm.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="form_id" value="<?php echo $form_data['id']; ?>"> <!-- Pass form ID to identify which form to delete -->
                    <button type="submit" name="deleteForm" value="delete">Delete</button>
                </form>

                <!-- Return to Dashboard -->
                <?php if($accessLevel > 1):?> <!--If staff or admin-->
                    <a class="button cancel" href="index.php">Return to Dashboard</a>
                <?php else: ?> <!--If family-->
                    <a class="button cancel" href="familyAccountDashboard.php">Return to Dashboard</a>
                <?php endif ?>
            <?php endif; ?>

    </form>
</div>
</body>
</html>
