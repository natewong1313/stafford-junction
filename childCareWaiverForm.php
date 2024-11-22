<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

include_once("database/dbChildren.php");
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
require_once('database/dbChildCareForm.php');


// Retrieve family information
if ($loggedIn) {
    $family = retrieve_family_by_id($_GET['id'] ?? $userID); //$_GET['id] will have the family id needed to fill form if the staff are trying to fill a form out for that family
    //$family = retrieve_family_by_id($_SESSION["_id"]);
    $family_email = $family->getEmail();
    //retrieve children by family ID
    $children = retrieve_children_by_family_id($_SESSION["_id"]);

}
include_once("database/dbFamily.php");
$family = retrieve_family_by_id($_SESSION["_id"]);
$guardian_email = $family->getEmail();
$guardian_fname = $family->getFirstName();
$guardian_lname = $family->getLastName();
$guardian_address = $family->getAddress();
$guardian_city = $family->getCity();
$guardian_state = $family->getState();
$guardian_zip = $family->getZip();
$guardian_phone = $family->getPhone();
$guardian_2_fname = $family->getFirstName2();
$guardian_2_lname = $family->getLastName2();
$guardian_2_address = $family->getAddress2();
$guardian_2_city = $family->getCity2();
$guardian_2_state = $family->getState2();
$guardian_2_zip = $family->getZip2();
$guardian_2_email = $family->getEmail2();
$guardian_2_phone = $family->getPhone2();

// include the header .php file s
if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('include/input-validation.php');
    //require_once('database/dbSpringBreakForm.php');
    $args = sanitize($_POST, null);

    $required = array(
        'name',
        'child_dob',
        'child_gender',
        'child_address',
        'child_city',
        'child_state',
        'child_zip',
        'medical_issues',
        'religious_foods',

        'parent1_first_name',
        'parent1_last_name',
        'parent1_address',
        'parent1_city',
        'parent1_state',
        'parent1_zip',
        'parent1_email',
        'parent1_cell_phone',
        'parent1_home_phone', 
        'parent1_work_phone', 

        'guardian_name',
        'guardian_signature',
        'signature_date'
    );

    if(!wereRequiredFieldsSubmitted($args, $required)){
        echo "Not all fields complete";
    } else {
        //call the function to create the waiver form
        $success = createChildCareForm($args);
        
        if ($success) {
            echo "Form submitted successfully!";
        } else {
            echo "Error submitting the form.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include universal styles, scripts, or configurations via external file -->
    <?php include_once("universal.inc") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stafford Junction | Child Care Waiver Form</title>
</head>

<body>

    <!-- Main heading of the page -->
    <h1>Childcare Waiver / Exención de Cuidado Infantil</h1>
    <div id="formatted_form">

        <!-- Subtitle -->
        <p>If your child is receiving childcare during classes, please complete the form and return to <br>
            Stafford Junction, 791 Truslow Road, Fredericksburg, VA 22406</p>

        <!-- General Information Title in a Black Box -->
        <div class="info-box-rect">
            <p><strong>General Information of the Child (Please Print Clearly) / Información General del Niño (Escriba
                    con
                    Claridad)</strong></p>
        </div>

        <form method="POST" action="childCareWaiverForm.php">

        <!-- Child Name -->
        <label for="name">Child Name / Nombre del Estudiante*</label><br><br>
            <select name="name" id="name" required>
                <?php
                    require_once('domain/Children.php'); 
                    foreach ($children as $c){
                        $id = $c->getID();
                        // Check if form was already completed for the child
                        if (!isChildCareWaiverFormComplete($id)) {
                            $name = $c->getFirstName() . " " . $c->getLastName();
                            $value = $id . "_" . $name;
                            echo "<option value='$value'>$name</option>";
                        }
                    }
                ?>
                </select>
                <br><br>

        <!-- Child's Date of Birth -->
        <label for="child_dob">Date of Birth* / Fecha de Nacimiento*</label>
        <input type="date" name="child_dob" id="child_dob" placeholder="Date of Birth / Fecha de Nacimiento" <br><br>
        
        <!-- Child's Gender -->
        <label for="child_gender">Gender* / Género*</label>
        <input type="text" name="child_gender" id="child_gender" placeholder="Gender / Género" <br><br>

        <!-- Address -->
        <label for="child_address">Address* / Dirección*</label>
        <input type="text" name="child_address" id="child_address" placeholder="Street Address / Dirección" <br><br>

        <!-- City -->
        <label for="child_city">City* / Ciudad*</label>
        <input type="text" name="child_city" id="child_city" placeholder="City / Ciudad" <br><br>

        <!-- State -->
        <label for="child_state">State* / Estado*</label>
        <input type="text" name="child_state" id="child_state" placeholder="State / Estado" <br><br>

        <!-- Zip Code -->
        <label for="child_zip">Zip Code* / Código Postal*</label>
        <input type="text" name="child_zip" id="child_zip" placeholder="Zip Code / Código Postal" <br><br>

        <!-- Medical Issues or Allergies -->
        <label for="medical_issues">Medical Issues or Allergies / Problemas Médicos o Alergias: </label><br>
        <textarea name="medical_issues" id="medical_issues" rows="2"
            placeholder="Medical issues or allergies / Problemas médicos o alergias"></textarea><br><br>

        <!-- Foods to Avoid Due to Religious Beliefs -->
        <label for="religious_foods">Foods to Avoid Due to Religious Beliefs / Alimentos a Evitar por Creencias
            Religiosas:
        </label><br>
        <textarea name="religious_foods" id="religious_foods" rows="2"
            placeholder="Foods to avoid / Alimentos a evitar"></textarea><br><br>

        <!-- Parents or Guardians Section -->
        <h3>Parents or Guardians / Padres o Tutores:</h3>

        <!-- Parent 1 Information -->
        <label for="parent1_first_name">First Name* / Nombre* </label>
        <input type="text" name="parent1_first_name" id="parent1_first_name" placeholder="First Name / Nombre" required
            value="<?php echo htmlspecialchars($guardian_fname); ?>">

        <label for="parent1_last_name">Last Name* / Apellido* </label>
        <input type="text" name="parent1_last_name" id="parent1_last_name" placeholder="Last Name / Apellido" required
            value="<?php echo htmlspecialchars($guardian_lname); ?>"><br><br>

        <!-- Parent 1 Address -->
        <label for="parent1_address">Address* / Dirección* </label>
        <input type="text" name="parent1_address" id="parent1_address" placeholder="Street Address / Dirección" required
            value="<?php echo htmlspecialchars($guardian_address); ?>"><br><br>

        <!-- Parent 1 City, State, Zip -->
        <label for="parent1_city">City* / Ciudad* </label>
        <input type="text" name="parent1_city" id="parent1_city" placeholder="City / Ciudad" required
            value="<?php echo htmlspecialchars($guardian_city); ?>">

        <label for="parent1_state">State* / Estado* </label>
        <input type="text" name="parent1_state" id="parent1_state" placeholder="State / Estado" required
            value="<?php echo htmlspecialchars($guardian_state); ?>">

        <label for="parent1_zip">Zip Code* / Código Postal* </label>
        <input type="text" name="parent1_zip" id="parent1_zip" placeholder="Zip Code / Código Postal" required
            value="<?php echo htmlspecialchars($guardian_zip); ?>"><br><br>

        <!-- Parent 1 Contact Info -->
        <label for="parent1_email">Email* / Correo Electrónico* </label>
        <input type="email" name="parent1_email" id="parent1_email" placeholder="Email / Correo Electrónico" required
            value="<?php echo htmlspecialchars($guardian_email); ?>"><br><br>

        <label for="parent1_cell_phone">Cell Phone* / Teléfono Celular* </label>
        <input type="tel" name="parent1_cell_phone" id="parent1_cell_phone" placeholder="Cell Phone / Teléfono Celular"
            required value="<?php echo htmlspecialchars($guardian_phone); ?>">

        <label for="parent1_home_phone">Home Phone* / Teléfono de Casa* </label>
        <input type="tel" name="parent1_home_phone" id="parent1_home_phone" placeholder="Home Phone / Teléfono de Casa">

        <label for="parent1_work_phone">Work Phone* / Teléfono del Trabajo* </label>
        <input type="tel" name="parent1_work_phone" id="parent1_work_phone"
            placeholder="Work Phone / Teléfono del Trabajo"><br><br>

        <!-- Parent 2 Information -->
        <label for="parent2_first_name">(Parent 2) First Name* / Nombre* </label>
        <input type="text" name="parent2_first_name" id="parent2_first_name" placeholder="First Name / Nombre"
            value="<?php echo htmlspecialchars($guardian_2_fname); ?>">

        <label for="parent2_last_name">(Parent 2) Last Name* / Apellido* </label>
        <input type="text" name="parent2_last_name" id="parent2_last_name" placeholder="Last Name / Apellido"
            value="<?php echo htmlspecialchars($guardian_2_lname); ?>"><br><br>

        <!-- Parent 2 Address -->
        <label for="parent2_address">(Parent 2) Address* / Dirección* </label>
        <input type="text" name="parent2_address" id="parent2_address" placeholder="Street Address / Dirección"
            value="<?php echo htmlspecialchars($guardian_2_address); ?>"><br><br>

        <!-- Parent 2 City, State, Zip -->
        <label for="parent2_city">(Parent 2) City* / Ciudad* </label>
        <input type="text" name="parent2_city" id="parent2_city" placeholder="City / Ciudad"
            value="<?php echo htmlspecialchars($guardian_2_city); ?>">

        <label for="parent2_state">(Parent 2) State* / Estado* </label>
        <input type="text" name="parent2_state" id="parent2_state" placeholder="State / Estado"
            value="<?php echo htmlspecialchars($guardian_2_state); ?>">

        <label for="parent2_zip">(Parent 2) Zip Code* / Código Postal* </label>
        <input type="text" name="parent2_zip" id="parent2_zip" placeholder="Zip Code / Código Postal"
            value="<?php echo htmlspecialchars($guardian_2_zip); ?>"><br><br>

        <!-- Parent 2 Contact Info -->
        <label for="parent2_email">(Parent 2) Email* / Correo Electrónico* </label>
        <input type="email" name="parent2_email" id="parent2_email" placeholder="Email / Correo Electrónico"
            value="<?php echo htmlspecialchars($guardian_2_email); ?>"><br><br>

        <label for="parent2_cell_phone">(Parent 2) Cell Phone* / Teléfono Celular*</label>
        <input type="tel" name="parent2_cell_phone" id="parent2_cell_phone" placeholder="Cell Phone / Teléfono Celular"
            value="<?php echo htmlspecialchars($guardian_2_phone); ?>">

        <label for="parent2_home_phone">(Parent 2) Home Phone* / Teléfono de Casa* </label>
        <input type="tel" name="parent2_home_phone" id="parent2_home_phone" placeholder="Home Phone / Teléfono de Casa">

        <label for="parent2_work_phone">(Parent 2) Work Phone* / Teléfono del Trabajo* </label>
        <input type="tel" name="parent2_work_phone" id="parent2_work_phone"
            placeholder="Work Phone / Teléfono del Trabajo"><br><br>

        <!-- Waiver Section -->
        <div class="info-box-rect">
            <p><strong>Photograph / Video and General Waiver / Autorización General y para Fotografía /
                    Video</strong>
            </p>
        </div>

        <p>
            I acknowledge that Stafford Junction may use photographs or videos of participants taken during
            involvement
            in
            Stafford Junction activities. This includes internal and external use, including but not limited to
            Stafford
            Junction’s website, Facebook, and publications. I consent to such uses and waive all rights to
            compensation.
            If I do not wish my child’s image to be included, I am responsible for informing them to exclude
            themselves
            from photographs or videos. I acknowledge the risks associated with such activities, and I release
            Stafford
            Junction from liability for any injury, loss, or damage.
        </p>
        <br>
        <p>
            Reconozco que Stafford Junction puede utilizar fotografías o vídeos de los participantes que sean
            tomadas
            durante
            su participación en las actividades de Stafford Junction. Esto incluye uso interno y externo,
            incluyendo,
            pero no limitado
            a la página web de Stafford Junction, Facebook, y publicaciones. Doy mi consentimiento para tales usos y
            renuncio a
            todos los derechos de compensación. Si no deseo que la imagen de mi hijo/a se incluya en lo
            anteriormente
            mencionado,
            es mi responsabilidad informarles que no participen en las fotografías o vídeos tomados durante dichas
            actividades. Doy
            mi consentimiento para que mi hijo/a asista a los programas y actividades organizados por Stafford
            Junction.
            Entiendo que
            hay riesgos involucrados en cualquier actividad y libero a Stafford Junction, sus empleados, agentes y
            voluntarios de toda
            responsabilidad por cualquier lesión, pérdida y/o daño a la persona / propiedad que pueda ocurrir.
        </p>
        <br>

        <!-- Signature Section -->
        <label for="guardian_name">Parent/Guardian Print Name* / Nombre del Padre/Tutor*</label><br>
        <input type="text" name="guardian_name" id="guardian_name"
            placeholder="Parent/Guardian Name / Nombre del Padre/Tutor" required
            value="<?php echo htmlspecialchars($guardian_fname . " " . $guardian_lname); ?>"><br><br>

        <label for="guardian_signature">Parent/Guardian Signature* / Firma del Padre/Tutor*</label><br>
        <input type="text" name="guardian_signature" id="guardian_signature" placeholder="Signature / Firma"
            required><br><br>

        <label for="signature_date">Date* / Fecha*</label><br>
        <input type="date" name="signature_date" id="signature_date" required><br><br>

        <!-- Submit and Cancel buttons -->
        <button type="submit" id="submit">Submit</button>
        <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
    </div>
    </form>
    <?php
           //if registration successful, create pop up notification and direct user back to login
            if($success){
                echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
            }  
            ?>
    </div>
</body>

</html>

