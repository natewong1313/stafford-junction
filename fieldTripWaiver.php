<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = null;

if(isset($_SESSION['_id'])){
    require_once('domain/Children.php');
    require_once('database/dbChildren.php');
    require_once('include/input-validation.php');
    require_once('database/dbFieldTripWaiverForm.php');
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}else {
    header("Location: login.php");
    die();
}

//necessary files
include_once("database/dbFamily.php");
$children = retrieve_children_by_family_id($_GET['id'] ?? $userID);
$family = retrieve_family_by_id($_GET['id'] ?? $userID);
$family_address = $family->getAddress();
$family_city = $family->getCity();
$family_state = $family->getState();
$family_zip = $family->getZip();
$family_email = $family->getEmail();
$guardian_name = $family->getFirstName() . " " . $family->getLastName();
$guardian_phone = $family->getPhone();
$guardian_2_name = $family->getFirstName2() . " " . $family->getLastName2();
$guardian_2_phone = $family->getPhone2();

//include_once('database/dbinfo.php');
//check if the form is submitted
if($_SERVER['REQUEST_METHOD'] == "POST"){
    //sanitize form input
    $args = sanitize($_POST, null);
    $required = array(
        'child_name',
        'child_gender',
        'child_birthdate',
        'child_neighborhood',
        'child_school',
        'child_address',
        'child_city',
        'child_state',
        'child_zip',
        'religious_foods',
        'medical_issues',
        'parent_email',
        'emergency_contact_name_1',
        'emergency_contact_relationship_1',
        'emergency_contact_phone_1',
        'emergency_contact_name_2',
        'emergency_contact_relationship_2',
        'emergency_contact_phone_2',
        'insurance_company',
        'policy_number',
        'parent_name',
        'parent_signature',
        'signature_date'
    );

// Phone fields to check if the phones are in the right format
$phoneFields = [
    'emergency_contact_phone_1',
    'emergency_contact_phone_2'
];
    
   // Track missing and invalid fields
$missingFields = [];
$invalidFields = [];

// Check if all strictly required fields are provided
foreach ($required as $field) {
    if (empty($args[$field])) {
        $missingFields[] = $field;
    }
}

// Validate all phone fields
foreach ($phoneFields as $field) {
    // Check if the field has a value
    if (!empty($args[$field])) {
        // Validate and filter the phone number
        $args[$field] = validateAndFilterPhoneNumber($args[$field]);

        // Collect invalid phone numbers
        if (!$args[$field]) {
            $invalidFields[] = $field;
        }
    }
}

// Provide feedback if any required fields are missing
if (!empty($missingFields)) {
    echo "The following required fields are missing:<br>";
    foreach ($missingFields as $missingField) {
        echo "$missingField<br>";
    }
} elseif (!empty($invalidFields)) {
    // Provide feedback if any phone fields are invalid
    echo "The following phone fields are invalid:<br>";
    foreach ($invalidFields as $invalidField) {
        echo "$invalidField<br>";
    }
} else {
        // All required fields are complete and phone fields valid, proceed to form submission
        $success = createFieldTripWaiverForm($args);
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
    <title>Stafford Junction | Field Trip Waiver Form <?php echo date("Y"); ?></title>
</head>
<body>

    <!--Please fill out error message pop up -->
    <?php if (!empty($errors)): ?>
        <h3 style="color: red;">Please correct the following errors:</h3>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Main heading of the page -->
    <h1>Stafford Junction Field Trip Release Waiver <?php echo date("Y"); ?> / Exención de Responsabilidad para Excursiones de Stafford Junction <?php echo date("Y"); ?></h1>
        <div id="formatted_form">

    <form id="fieldTripWaiverForm" action="" method="post">

    <!-- General Information Title in a Black Box -->
    <div class="info-box-rect">
        <p><strong>General Information / Información General</strong></p>
    </div>

    <!-- Child's Name -->
    <label for="child_name">Child's Name / Nombre del Niño(a)*</label><br><br>
    <select name="child_name" id="child_name" required>
        <option disabled selected>Select a child</option>
        <?php
            require_once('domain/Children.php');
            foreach ($children as $c) {
                $id = $c->getID();
                if (!isFieldTripWaiverFormComplete($id)) {
                    $name = $c->getFirstName() . " " . $c->getLastName();
                    $value = $id . "_" . $name;
                    echo "<option value='$value'>$name</option>";
                }
            }
        ?>
    </select>
    <script>
        const children = <?php echo json_encode($children); ?>;
        document.getElementById("child_name").addEventListener("change", (e) => {
            const childId = e.target.value.split("_")[0];
            const childData = children.find(child => child.id === childId);
            document.getElementById("child_gender").value = childData.gender;
            document.getElementById("child_birthdate").valueAsDate = new Date(childData.birthdate);
            document.getElementById("child_neighborhood").value = childData.neighborhood;
            document.getElementById("child_school").value = childData.school;
            document.getElementById("child_address").value = childData.address;
            document.getElementById("child_city").value = childData.city;
            document.getElementById("child_zip").value = childData.zip;
            document.getElementById("child_city").value = childData.city;
            document.getElementById("medical_issues").value = childData.medicalNotes;
        })
    </script>
    <br><br>

    <!-- Child's Gender -->
    <label for="child_gender">Gender* / Género*</label><br>
    <input type="text" name="child_gender" id="child_gender" placeholder="Gender / Género" required><br><br>

    <!-- Child's Birthdate -->
    <label for="child_birthdate">Birthdate* / Fecha de Nacimiento*</label><br>
    <input type="date" name="child_birthdate" id="child_birthdate" required><br><br>

    <!-- Child's Neighborhood -->
    <label for="child_neighborhood">Neighborhood* / Barrio*</label><br>
    <input type="text" name="child_neighborhood" id="child_neighborhood" placeholder="Neighborhood / Barrio" required><br><br>

    <!-- Child's School -->
    <label for="child_school">School* / Escuela*</label><br>
    <input type="text" name="child_school" id="child_school" placeholder="School / Escuela" required><br><br>

    <!-- Child's Street Address -->
    <label for="child_address">Street Address* / Dirección*</label><br>
    <input type="text" name="child_address" id="child_address" placeholder="Street Address / Dirección"
    required value="<?php echo htmlspecialchars($family_address); ?>"><br><br>

    <!-- Child's City -->
    <label for="child_city">City* / Ciudad*</label><br>
    <input type="text" name="child_city" id="child_city" placeholder="City / Ciudad" required value="<?php echo htmlspecialchars($family_city); ?>"><br><br>

    <!-- Child's State -->
    <label for="child_state">State* / Estado*</label><br>
    <input type="text" name="child_state" id="child_state" placeholder="State / Estado" required value="<?php echo htmlspecialchars($family_state); ?>"><br><br>

    <!-- Child's Zip Code -->
    <label for="child_zip">Zip* / Código Postal*</label><br>
    <input type="text" name="child_zip" id="child_zip" placeholder="Zip Code / Código Postal" required value="<?php echo htmlspecialchars($family_zip); ?>"><br><br>

    <!-- Medical Issues or Allergies -->
    <label for="medical_issues">Medical Issues or Allergies / Problemas Médicos o Alergias:</label><br>
    <textarea name="medical_issues" id="medical_issues" rows="3"
        placeholder="Medical issues or allergies / Problemas médicos o alergias"></textarea><br><br>

    <!-- Foods to Avoid Due to Religious Beliefs -->
    <label for="religious_foods">Foods to Avoid Due to Religious Beliefs / Alimentos a Evitar por Creencias
        Religiosas:</label><br>
    <textarea name="religious_foods" id="religious_foods" rows="3"
        placeholder="Foods to avoid due to religious beliefs / Alimentos a evitar" required></textarea><br><br>

    <!-- Parent or Guardian Email -->
    <label for="parent_email">Parent or Guardian Email* / Correo Electrónico del Padre o Tutor*</label><br>
    <input type="email" name="parent_email" id="parent_email" placeholder="Email / Correo Electrónico" required value="<?php echo htmlspecialchars($family_email); ?>"><br><br>

    <!-- Emergency Contact Information Section -->
    <br><br>
    <div class="info-box-rect">
        <p><strong>Emergency Contact and Pick-Up Information / Información de Contacto de Emergencia y Recogida</strong>
        </p>
    </div>

    <!-- Emergency Contact 1 Name -->
    <label for="emergency_contact_name_1">Emergency Contact Name 1* / Nombre del Contacto de Emergencia 1*</label><br>
    <input type="text" name="emergency_contact_name_1" id="emergency_contact_name_1"
        placeholder="Contact Name / Nombre del Contacto" required value="<?php echo htmlspecialchars($guardian_name); ?>"><br><br>

    <!-- Emergency Contact 1 Relationship -->
    <label for="emergency_contact_relationship_1">Relationship* / Relación*</label><br>
    <input type="text" name="emergency_contact_relationship_1" id="emergency_contact_relationship_1"
        placeholder="Relationship / Relación" required><br><br>

    <!-- Emergency Contact 1 Phone -->
    <label for="emergency_contact_phone_1">Phone* / Teléfono*</label><br>
        <input type="tel" name="emergency_contact_phone_1" id="emergency_contact_phone_1"
        placeholder="Phone Number / Número de Teléfono" required value="<?php echo htmlspecialchars($guardian_phone); ?>"><br><br>
   

    <!-- Emergency Contact 2 Name -->
    <label for="emergency_contact_name_2">Emergency Contact Name 2* / Nombre del Contacto de Emergencia 2*</label><br>
    <input type="text" name="emergency_contact_name_2" id="emergency_contact_name_2"
        placeholder="Contact Name / Nombre del Contacto" required value="<?php echo htmlspecialchars($guardian_2_name); ?>"><br><br>

    <!-- Emergency Contact 2 Relationship -->
    <label for="emergency_contact_relationship_2">Relationship* / Relación*</label><br>
    <input type="text" name="emergency_contact_relationship_2" id="emergency_contact_relationship_2"
        placeholder="Relationship / Relación" required><br><br>

    <!-- Emergency Contact 2 Phone -->
    <label for="emergency_contact_phone_2">Phone* / Teléfono*</label><br>
    <input type="tel" name="emergency_contact_phone_2" id="emergency_contact_phone_2"
        placeholder="Phone Number / Número de Teléfono" required value="<?php echo htmlspecialchars($guardian_2_phone); ?>"><br><br>
    </div>

    <!-- Separator Line -->
    <hr>

    <!-- Waiver Section -->
    <div class="info-box-rect">
        <p><strong>Emergency Medical Authorization and Waiver of Liability /
                Autorización Médica de Emergencia y Exención de Responsabilidad</strong></p>
    </div>
    <div id="spring_break_form">
    <div id="formatted_form">
        <div class="pickup-times">
            <!-- General Information Title in a Black Box -->
            <p>
                I hereby give my consent for my child to attend programs and activities organized by Stafford Junction.
                I understand there are inherent risks involved in any activity and I hereby release Stafford Junction,
                its employees, agents, and volunteers from all liability for any injury, loss, and/or damage to
                person/property that may occur while my child is in attendance. I authorize Stafford Junction to
                obtain immediate care and consent to the hospitalization of, the performance of necessary diagnostic
                tests upon, the use of surgery on, and/or the administration of drugs to my child if an emergency
                occurs when I cannot be located immediately. It is also understood this agreement covers only those
                situations which are true emergencies and only when I cannot be reached. I understand Stafford
                Junction will make every effort to contact me and/or the designated Emergency Contacts. I acknowledge
                I am ultimately responsible for all costs incurred not reimbursable by my health insurance provider.
            </p>
            <!-- Separator Line -->
            <br>
            <p>
                Por la presente doy mi consentimiento para que mi hijo/a participe en los programas y actividades
                organizados
                por Stafford Junction. Entiendo que existen riesgos inherentes en cualquier actividad y, por la
                presente, libero a
                Stafford Junction, sus empleados, agentes y voluntarios de toda responsabilidad por cualquier lesión,
                pérdida y/o
                daño a la persona o propiedad que pueda ocurrir mientras mi hijo/a esté presente. Autorizo a Stafford
                Junction a
                obtener atención médica inmediata y doy mi consentimiento para la hospitalización, la realización de
                pruebas diagnósticas
                necesarias, el uso de cirugía y/o la administración de medicamentos a mi hijo/a si ocurre una emergencia
                y no puedo ser
                localizado/a de inmediato. También se entiende que este acuerdo cubre únicamente aquellas situaciones
                que sean verdaderas
                emergencias y solo cuando no se pueda contactarme. Entiendo que Stafford Junction hará todo lo posible
                por contactarme a mí
                y/o a los Contactos de Emergencia designados. Reconozco que soy el/la responsable final de todos los
                costos incurridos que no
                sean reembolsables por mi proveedor de seguro médico.
            </p>

            <!-- Medical Insurance Company -->
            <label for="insurance_company">Medical Insurance Company / Compañía de Seguro Médico</label><br>
            <input type="text" name="insurance_company" id="insurance_company"
                placeholder="Insurance Company / Compañía de Seguros" required><br><br>

            <!-- Policy Number -->
            <label for="policy_number">Policy Number / Número de Póliza</label><br>
            <input type="text" name="policy_number" id="policy_number"
                placeholder="Policy Number / Policy Number" required><br><br>
        </div>

        <!-- Separator Line -->
        <hr>

        <!-- Code of Conduct Section -->
        <div class="info-box-rect">
            <p><strong>Code of Conduct / Código de Conducta</strong></p>
        </div>
        <div id="spring_break_form">
            <div class="info">
                <p>
                    Stafford Junction practices four core values: Caring, Honesty, Respect, and Responsibility.
                    We are not a day care service. The program is staffed by volunteers whose sole responsibility
                    is to provide stimulating activities to youth, preventing summer learning loss. Misbehavior
                    by students will not be tolerated.
                </p>
                <p>
                    The standard disciplinary process is as follows: verbal warning, second verbal warning
                    and parents contacted, two-day suspension from the program and parents contacted,
                    dismissal from the program.
                </p>
                <p>
                    Exceptions: If a student commits a serious infraction, the Youth Program Manager has
                    the option to immediately dismiss the child from the program.
                </p>

                <br>
                <p>
                    Stafford Junction practica cuatro valores fundamentales: Cuidado, Honestidad, Respeto y
                    Responsabilidad.
                    No somos un servicio de guardería. El programa está a cargo de voluntarios cuya única
                    responsabilidad es
                    ofrecer actividades estimulantes para los jóvenes, previniendo la pérdida de aprendizaje durante el
                    verano.
                    No se tolerará el mal comportamiento de los estudiantes.
                </p>
                <p>
                    El proceso disciplinario estándar es el siguiente: advertencia verbal, segunda advertencia verbal y
                    contacto
                    con los padres, suspensión de dos días del programa y contacto con los padres, expulsión del
                    programa.
                </p>
                <p>
                    Excepciones: Si un estudiante comete una infracción grave, el Gerente del Programa Juvenil tiene la
                    opción de
                    expulsar inmediatamente al niño del programa.
                </p>
            </div>

            <!-- Separator Line -->
            <hr>

            <!-- Photograph and Video Waiver Section -->
            <div class="info-box-rect">
                <p><strong>Photograph and Video Waiver / Exención para Fotografías y Videos</strong></p>
            </div>
            <div id="spring_break_form">
                <div class="pickup-times">
                    <p>
                        I acknowledge that Stafford Junction may utilize photographs or videos of participants
                        that may be taken during involvement in Stafford Junction activities. This includes
                        internal and external use including but not limited to Stafford Junction’s website,
                        Facebook, and publications. I consent to such uses and hereby waive all rights of
                        compensation. If I do not wish the image of my child to be included in the
                        above-mentioned, it is my responsibility to inform them to exclude themselves
                        from photographs or videos taken during such activities.
                    </p>
                    <br>
                    <p>
                        Reconozco que Stafford Junction puede utilizar fotografías o videos de los participantes
                        que se tomen durante su participación en actividades de Stafford Junction. Esto incluye
                        el uso interno y externo, como el sitio web de Stafford Junction, Facebook y publicaciones.
                        Doy mi consentimiento para dichos usos y, por la presente, renuncio a todos los derechos
                        de compensación. Si no deseo que la imagen de mi hijo/a sea incluida en los elementos
                        mencionados, es mi responsabilidad informarle que se excluya de las fotografías o videos
                        tomados durante dichas actividades.
                    </p>

                    <!-- Parent/Guardian Print Name -->
                    <label for="parent_name">Parent/Guardian Print Name* / Nombre en Letra de Imprenta del
                        Padre/Madre/Tutor*</label><br>
                    <input type="text" name="parent_name" id="parent_name"
                        placeholder="Parent/Guardian Name / Nombre del Padre/Madre/Tutor" required value="<?php echo htmlspecialchars($guardian_name); ?>"><br><br>

                    <!-- Parent/Guardian Signature -->
                    <label for="parent_signature">Parent/Guardian Signature* / Firma del Padre/Madre/Tutor*</label><br>
                    <input type="text" name="parent_signature" id="parent_signature"
                        placeholder="Parent/Guardian Signature / Firma del Padre/Madre/Tutor*" required><br><br>

                    <!-- Signature Date -->
                    <label for="signature_date">Date* / Fecha*</label><br>
                    <input type="date" name="signature_date" id="signature_date" required><br><br>
                </div>
                <hr>

               <!-- Submit and Cancel buttons -->
        <button type="submit" id="submit">Submit</button>
                <?php 
                    if (isset($_GET['id'])) {
                        echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel</a>';
                    } else {
                        echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>';
                    }
                ?>
        </div>
    </form>
    </div>
    <?php //If the user is an admin or staff, the message should appear at index.php
            if($_SERVER['REQUEST_METHOD'] == "POST" && $success){
                if (isset($_GET['id'])) {
                    echo '<script>document.location = "fillForm.php?formSubmitSuccess&id=' . $_GET['id'] . '";</script>';
                } else {
                    echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
                }
            } else if ($_SERVER['REQUEST_METHOD'] == "POST" && !$success) {
                if (isset($_GET['id'])) {
                    echo '<script>document.location = "fillForm.php?formSubmitFail&id=' . $_GET['id'] . '";</script>';
                } else {
                    echo '<script>document.location = "fillForm.php?formSubmitFail";</script>';
                }
            }
        ?>
    
</body>
</html>