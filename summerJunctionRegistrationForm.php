<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

if(!isset($_SESSION['_id'])){
    header('Location: login.php');
    die();
}

$accessLevel = $_SESSION['access_level'];
$userID = $_SESSION['_id'];

require_once('database/dbChildren.php');
$children = retrieve_children_by_family_id($_GET['id'] ?? $userID);
include_once("database/dbSummerJunctionForm.php");

// include the header .php files
if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('include/input-validation.php');
    //require_once('database/dbSummerJunctionRegistrationForm.php');
    $args = sanitize($_POST, null);
    $required = array(
        'child-first-name',
        'child-last-name',
        'birthdate',
        'grade',
        'gender',
        'shirt-size',
        'child-address',
        'child-city',
        'neighborhood',
        'child-state',
        'child-zip',
        'parent1-first-name',
        'parent1-last-name',
        'parent1-address',
        'parent1-city',
        'parent1-state',
        'parent1-zip',
        'parent1-email',
        'parent1-cell-phone',
        'emergency-name1',
        'emergency-relationship1',
        'emergency-phone1',
        'authorized-pu',
        'primary-language',
        'hispanic-latino-spanish',
        'income',
        'other-programs',
        'insurance',
        'policy-num',
        'signature',
        'signature-date'
    );
    $missingFields = [];
    foreach ($required as $field) {
        if (empty($args[$field])) {
            $missingFields[] = $field;
        }
    }
    if (!empty($missingFields)) {
        echo "The following required fields are missing:<br>";
        foreach ($missingFields as $missingField) {
            echo "$missingField<br>";
        }
    }else{
        $success = createSummerJunctionForm($args);
        if ($success) {
            $successMessage = "Form submitted successfully";
        }
    }
}
?>

<html>
<head>
    <!-- Include universal styles formatting -->
    <?php include_once("universal.inc") ?>
    <title>Stafford Junction | Summer Junction Registration Form</title>
</head>
    <body>
    <?php //If the user is an admin or staff, the message should appear at index.php
        if(isset($successMessage) && $accessLevel > 1){
            echo '<script>document.location = "fillForm.php?formSubmitSuccess&id=' . $_GET['id'] . '";</script>';
        }else if(isset($successMessage) && $accessLevel == 1){ //If the user is a family, the success message should apprear at family dashboard
            echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
        }
    ?>
    <h1>Summer Junction Registration Form</h1>
        <div id="formatted_form">
            
            <p>Space is limited. Registrations will be taken on a first come, first served basis.<br><br>
            All sections of the application umst be filled out completely to be processed.<br><br>
            Questions? Email us at office@staffordjunction.org or call us at 540-368-0081.</p><br>

            <p>Espacio es limitado. Las registraciones se aprobarán en orden que sean recibidas.
            Todas las secciones de la solicitud deben completarse en su totalidad para ser procesada.<br><br>
            ¿Preguntas? Envíenos un correo electrónico a office-staffordjunction.org o llámenos al 540-368-0081.</p><br>

            <p><b>* Indicates a required field / Indica un campo obligatorio</b></p><br>

            <form id="brainBuildersStudentRegistrationForm" action="" method="post">      
                <h2>Camp Selection / Selección de Campamentos</h2><br>
                <p><b>Summer Junction Camps, Dates, and Times (Please Check All Camps Your Child Will Attend and Circle
                the Correct Session According to Grade Completed as of May 2024)</b></p><br>

                <p><b>Campamentos, fechas, horarios de Summer Junction (por favor, marque los programas a los que asistirá
                su hijo/a y circule la sesión que corresponde al grado que completo en mayo del 2024)</b></p><br>

                <input type="checkbox" id="steam" name="steam" value="steam">
                <label for="steam"> STEAM (Science, Technology, Engineering, Arts, and Math) Camp at Stafford Junction, 
                    June 10 - June 13, 12 PM – 3 PM. <b>6th - 12th grade only.</b> / Campamento de STEAM (Ciencias, Tecnología, Ingeniería, Arte y Matemáticas) 
                    en Stafford Junction, del 5 de junio al 8 de junio, 1PM – 4PM. <b>Sólo Grados 6 - 12</b></label><br><br>
                <input type="checkbox" id="summer-camp" name="summer-camp" value="summer-camp">
                <label for="summer-camp"> Summer Camp at Stafford Junction runs from July 1 – August 2, 12 PM – 3 PM. / 
                Campamento de verano en Stafford Junction del 1 de julio al 2 de agosto, 12PM – 3PM. </label><br><br>
                
                <p>K – 2nd (Monday, Wednesday) / K – 2nd (lunes y miércoles)<br>
                Fridays - July 12 & July 26 Soccer at Pratt Park / viernes - 12 y 26 de julio fútbol en Pratt Park<br>
                Friday - July 19 YMCA Waterpark / viernes - 19 de julio parque acuático YMCA<p><br>

                <p>3rd - 5th grade (Tuesday, Thursday) / 3rd – 5th grade (martes y jueves)<br>
                Fridays - July 12 & July 26 Soccer at Pratt Park / viernes - 12 y 26 de julio fútbol en Pratt Park<br>
                Friday - August 2 YMCA Waterpark / viernes - 2 de agosto parque acuático YMCA<p><br>

                <br><br>

                <h2>Student Information / Información general del niño</h2><br>      
                <label for="child-name">Child Name / Nombre del Estudiante*</label><br><br>
                <select name="child-name" id="child-name" required>
                    <option disabled selected>Select a child</option>
                    <?php
                        require_once('domain/Children.php'); 
                        foreach ($children as $c){
                            $id = $c->getID();
                            if (!isSummerJunctionFormComplete($id)) {
                                $name = $c->getFirstName() . " " . $c->getLastName();
                                $value = $id . "_" . $name;
                                echo "<option value='$value'>$name</option>";
                            }
                        }
                    ?>
                </select>
                 <script>
                    const children = <?php echo json_encode($children); ?>;
                    document.getElementById("child-name").addEventListener("change", (e) => {
                        const childId = e.target.value.split("_")[0];
                        const childData = children.find(child => child.id === childId);
                        document.getElementById("child-first-name").value = childData.firstName;
                        document.getElementById("child-last-name").value = childData.lastName;
                        document.getElementById("birthdate").value = childData.birthdate;
                        document.getElementById("grade").value = childData.grade;
                        document.getElementById("gender").value = childData.gender;
                        document.getElementById("neighborhood").value = childData.neighborhood;
                        document.getElementById("child-address").value = childData.address;
                        document.getElementById("child-city").value = childData.city;
                        document.getElementById("child-state").value = childData.state;
                        document.getElementById("child-zip").value = childData.zip;
                        document.getElementById("child-medical-allergies").value = childData.medicalNotes;
                    })
                </script>
                <br><br> 
                <!--Child First Name-->
                <label for="child-first-name">Child First Name / Nombre *</label><br><br>
                <input type="text" name="child-first-name" id="child-first-name" required placeholder="Child First Name" required><br><br>

                <!--Child Last Name-->
                <label for="child-last-name">Child Last Name / Apellido *</label><br><br>
                <input type="text" name="child-last-name" id="child-last-name" required placeholder="Child Last Name" required><br><br>

                <!--Date of Birth-->
                <label for="birthdate">Date of Birth / Fecha de nacimiento *</label><br><br>
                <input type="date" id="birthdate" name="birthdate" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>"><br><br>

                <!--Grade Completed as of May 2024-->
                <label for="grade">Grade Completed as of May 2024 / Grado completo en mayo de 2024 *</label><br><br>
                <input type="text" name="grade" id="grade" required placeholder="Grade" required><br><br>

                <!--Gender-->
                <label for="gender">Gender / Género *</label><br><br>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="male">Male / Hombre</option>
                        <option value="female">Female / Mujer</option>
                    </select>
                <br><br>

                <!--T-Shirt Size-->
                <label for="shirt-size">T-shirt Size / Tallas de camiseta *</label><br><br>
                    <select id="shirt-size" name="shirt-size" required>
                        <option value="" disabled selected>Select T-shirt Size</option>
                        <option value="child-xs">Child X-Small / Talla Niño/a Extra Pequeño</option>
                        <option value="child-s">Child Small / Talla Niño/a Pequeño</option>
                        <option value="child-m">Child Medium / Talla Niño/a Mediano</option>
                        <option value="child-l">Child Large / Talla Niño/a Grande</option>
                        <option value="child-xl">Child X-Large / Talla Niño/a Extragrande</option>
                        <option value="adult-s">Adult Small / Talla Adulto Pequeño</option>
                        <option value="adult-m">Adult Medium / Talla Adulto Mediano</option>
                        <option value="adult-l">Adult Large / Talla Adulto Grande</option>
                        <option value="adult-xl">Adult X-Large / Talla Adulto Extragrande</option>
                        <option value="adult-2x">Adult 2X / Talla Adulto Extra Extragrande</option>
                    </select>
                <br><br>

                <!--Neighborhood-->
                <label for="neighborhood">Neighborhood / Vecindario *</label><br><br>
                <input type="text" id="neighborhood" name="neighborhood" required placeholder="Neighborhood"><br><br>

                <!--Street Address-->
                <label for0="child-address">Street Address / Dirección *</label><br><br>
                <input type="text" id="child-address" name="child-address" required placeholder="Enter your street address"><br><br>

                <!--City-->
                <label for="child-city">City / Ciudad *</label><br><br>
                <input type="text" id="child-city" name="child-city" required placeholder="Enter your city"><br><br>

                <!--State-->
                <label for="child-state">State / Estado *</label><br><br>
                <select id="child-state" name="child-state" required>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA" selected>Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select><br><br>

                <!--Zip-->
                <label for="child-zip" required>Zip Code / Código postal *</label><br><br>
                <input type="text" id="child-zip" name="child-zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code"><br><br>

                <!--Medical issues or allergies-->
                <label for="child-medical-allergies" required>Medical issues or allergies / Problemas médicos o alergias</label><br><br>
                <input type="text" id="child-medical-allergies" name="child-medical-allergies" placeholder="Medical issues or allergies"><br><br>

                <!--Foods to avoid due to religious beliefs-->
                <label for="child-food-avoidances" required>Foods to avoid due to religious beliefs / Alimentos para evitar debido a creencias religiosas</label><br><br>
                <input type="text" id="child-food-avoidances" name="child-food-avoidances" placeholder="Foods to avoid due to religious beliefs"><br><br>

            <h2>Parents or Guardians / Padres o Guardianes</h2><br>

            <h3>Parent or Guardian 1 / Padre o Guardiane 1</h3>
            <br>
                <!--Parent 1 First Name-->
                <label for="parent1-first-name">First Name / Nombre *</label><br><br>
                <input type="text" id="parent1-first-name" name="parent1-first-name" required placeholder="Parent 1 First Name"><br><br>

                <!--Parent 1 Last Name-->
                <label for="parent1-last-name">Last Name / Apellido *</label><br><br>
                <input type="text" id="parent1-last-name" name="parent1-last-name" required placeholder="Parent 1 Last Name"><br><br>

                <!--Street Address-->
                <label for0="parent1-address">Street Address / Dirección *</label><br><br>
                <input type="text" id="parent1-address" name="parent1-address" required placeholder="Enter your street address"><br><br>

                <!--City-->
                <label for="parent1-city">City / Ciudad *</label><br><br>
                <input type="text" id="parent1-city" name="parent1-city" required placeholder="Enter your city"><br><br>

                <!--State-->
                <label for="parent1-state">State / Estado *</label><br><br>
                <select id="parent1-state" name="parent1-state" required>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA" selected>Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select><br><br>

                <!--Zip-->
                <label for="parent1-zip">Zip Code / Código postal *</label><br><br>
                <input type="text" id="parent1-zip" name="parent1-zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code"><br><br>

                <!--Email-->
                <label for="parent1-email">Email / Correo electrónico *</label><br><br>
                <input type="text" id="parent1-email" name="parent1-email" required placeholder="Enter your city"><br><br>

                <!--Cell Phone-->
                <label for="parent1-cell-phone" required>Cell Phone Number / Celular *</label><br><br>
                <input type="tel" id="parent1-cell-phone" name="parent1-cell-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555"><br><br>

                <!--Home Phone-->
                <label for="parent1-home-phone" required>Home Phone Number / Tel. Casa </label><br><br>
                <input type="tel" id="parent1-home-phone" name="parent1-home-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

                <!--Work Phone-->
                <label for="parent1-work-phone" required>Work Phone Number/ Tel. trabajo </label><br><br>
                <input type="tel" id="parent1-work-phone" name="parent1-work-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

            <h3>Parent or Guardian 2 / Padre o Guardiane 2</h3>
            <br>
                <!--Parent 2 First Name-->
                <label for="parent2-first-name">First Name / Nombre</label><br><br>
                <input type="text" id="parent2-first-name" name="parent2-first-name" placeholder="Parent 2 First Name"><br><br>

                <!--Parent 2 Last Name-->
                <label for="parent2-last-name">Last Name/ Apellido</label><br><br>
                <input type="text" id="parent2-last-name" name="parent2-last-name" placeholder="Parent 1 Last Name"><br><br>

                <!--Street Address-->
                <label for0="parent2-address">Street Address / Dirección</label><br><br>
                <input type="text" id="parent2-address" name="parent2-address" placeholder="Enter your street address"><br><br>

                <!--City-->
                <label for="parent2-city">City / Ciudad</label><br><br>
                <input type="text" id="parent2-city" name="parent2-city" placeholder="Enter your city"><br><br>

                <!--State-->
                <label for="parent2-state">State / Estado</label><br><br>
                <select id="parent2-state" name="parent2-state">
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA" selected>Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select><br><br>

                <!--Zip-->
                <label for="parent2-zip" required>Zip Code / Código postal</label><br><br>
                <input type="text" id="parent2-zip" name="parent2-zip" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code"><br><br>

                <!--Email-->
                <label for="parent2-email" required>Email / Correo electrónico</label><br><br>
                <input type="text" id="parent2-email" name="parent2-email" placeholder="Enter your city"><br><br>

                <!--Cell Phone-->
                <label for="parent2-cell-phone" required>Cell Phone Number / Celular</label><br><br>
                <input type="tel" id="parent2-cell-phone" name="parent2-cell-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

                <!--Home Phone-->
                <label for="parent2-home-phone" required>Home Phone Number / Tel. Casa</label><br><br>
                <input type="tel" id="parent2-home-phone" name="parent2-home-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

                <!--Work Phone-->
                <label for="parent2-work-phone" required>Work Phone Number / Tel. trabajo</label><br><br>
                <input type="tel" id="parent2-work-phone" name="parent2-work-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

            <h2>Emergency Contact and Pick-Up Information / Contacto de emergencia y personas autorizadas que puedan recoger a su hijo/a</h2><br>

            <h3>Emergency Contact 1 / Contacto de emergencia 1</h3><br>

                <!--Name-->
                <label for="emergency-name1" required>Full Name / Nombre completo *</label><br><br>
                <input type="text" id="emergency-name1" name="emergency-name1" required placeholder="Enter full name"><br><br>

                <!--Relationship-->
                <label for="emergency-relationship1" required>Relationship to Child / Relación *</label><br><br>
                <input type="text" id="emergency-relationship1" name="emergency-relationship1" required placeholder="Enter person's relationship to child"><br><br>

                <!--Phone-->
                <label for="emergency-phone1" required>Phone / Teléfono *</label><br><br>
                <input type="tel" id="emergency-phone1" name="emergency-phone1" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555"><br><br>

            <h3>Emergency Contact 2 / Contacto de emergencia 2</h3><br>

                <!--Name-->
                <label for="emergency-name2" required>Full Name / Nombre completo</label><br><br>
                <input type="text" id="emergency-name2" name="emergency-name2" placeholder="Enter full name"><br><br>

                <!--Relationship-->
                <label for="emergency-relationship2" required>Relationship to Child / Relación</label><br><br>
                <input type="text" id="emergency-relationship2" name="emergency-relationship2" placeholder="Enter person's relationship to child"><br><br>

                <!--Phone-->
                <label for="emergency-phone2" required>Phone / Teléfono</label><br><br>
                <input type="tel" id="emergency-phone2" name="emergency-phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555"><br><br>

                <p>------</p><br><br>

                <!--Persons Authorized for Pick-Up-->
                <label for="authorized-pu" required>Persons authorized to pick up child / Personas autorizadas para recoger a su hijo/a *</label><br><br>
                <input type="text" id="authorized-pu" name="authorized-pu" required placeholder="Enter persons names"><br><br>
                
                <!--Persons NOT Authorized for Pick-Up-->
                <label for="not-authorized-pu" required>Persons <b><u>NOT</u></b> authorized to pick up child / Personas <b><u>NO</u></b> autorizadas para recoger a su hijo/a</label><br><br>
                <input type="text" id="not-authorized-pu" name="not-authorized-pu" placeholder="Enter persons names"><br><br>

            <h2>Additional Required Information (Optional) / Información adicional (opcional)</h2>
            <p>This information is for Stafford Junction funding purposes only. / esta información solo será usada por Stafford Junction con fines de
            aplicación de becas.</p><br>

                <!--Parent's Primary Language-->
                <label for="primary-language" required>Primary Language / Idioma principal de los padres *</label><br><br>
                <input type="text" id="primary-language" name="primary-language" placeholder="English, Spanish, Farsi, etc." required><br><br>

                <!--Hispanic, Latino, or Spanish Origin-->
                <label for="hispanic-latino-spanish" required>Hispanic, Latino, or Spanish Origin / Origen hispano, latino o español *</label><br><br>
                <select id="hispanic-latino-spanish" name="hispanic-latino-spanish" required>
                    <option value="" disabled selected>Select Yes or No</option>
                    <option value="yes">Yes / Sí</option>
                    <option value="no">No</option>
                </select>
                <br><br>

                <!--Race-->
                <label for="race" required>Race / Raza</label><br><br>
                <select id="race" name="race" required>
                    <option value="" disabled selected>Select Race</option>
                    <option value="Caucasian">Caucasian / Blanca</option>
                    <option value="Black/African American">Black/African American / Negra o afroamericana</option>
                    <option value="Native Indian/Alaska Native">Native Indian/Alaska Native / Indígenas de las Américas o nativos de Alaska</option>
                    <option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander / Nativo de Hawái o Islas del Pacífico</option>
                    <option value="Asian">Asian / Asiática</option>
                    <option value="Multiracial">Multiracial / Alguna otra raza</option>
                    <option value="Other">Other / Otro</option>
                </select><br><br>

                <!--Num Unemployed in Household-->
                <label for="num-unemployed">Number of Unemployed in Household / Cantidad de desempleados en el hogar</label><br><br>
                <input type="number" id="num-unemployed" name="num-unemployed" placeholder="Enter number of unemployed"><br><br>

                <!--Num Retired in Household-->
                <label for="num-retired">Number of Retired in Household / Cantidad de jubilados en el hogar</label><br><br>
                <input type="number" id="num-retired" name="num-retired" placeholder="Enter number of retired"><br><br>

                <!--Num Unemployed Student in Household-->
                <label for="num-unemployed-student">Number of Unemployed Students in Household / Cantidad de estudiantes desempleados en el hogar</label><br><br>
                <input type="number" id="num-unemployed-student" name="num-unemployed-student" placeholder="Enter number of unemployed students"><br><br>

                <!--Num Employed Full-Time in Household-->
                <label for="num-employed-fulltime">Number of Full-Time Employed in Household / Cantidad de personas con empleo de tiempo completo en el hogar</label><br><br>
                <input type="number" id="num-employed-fulltime" name="num-employed-fulltime" placeholder="Enter number of full-time employed"><br><br>

                <!--Num Employed Part-Time in Household-->
                <label for="num-employed-parttime">Number of Part-Time Employed in Household / Cantidad de personas con empleo de tiempo parcial en el hogar</label><br><br>
                <input type="number" id="num-employed-parttime" name="num-employed-parttime" placeholder="Enter number of part-time employed"><br><br>

                <!--Num Employed Student in Household-->
                <label for="num-employed-student">Number of Employed Students in Household / Cantidad de estudiantes empleados en el hogar</label><br><br>
                <input type="number" id="num-employed-student" name="num-employed-student" placeholder="Enter number of employed students"><br><br>

                <!--Estimated Household Income-->
                <label for="income">Estimated Household Income / Ingresos familiares estimados *</label><br><br>
                <select id="income" name="income" required>
                    <option value="" disabled selected>Select Estimated Income</option>
                    <option value="Under 20,000">Under/Bajo 20,000</option>
                    <option value="20,000-40,000n">20,000-40,000</option>
                    <option value="40,001-60,000">40,001-60,000</option>
                    <option value="60,001-80,000">60,001-80,000</option>
                    <option value="Over 80,000">Over/Sobre 80,000</option>
                </select><br><br>

                <!--Other Programs-->
                <label for="other-programs">Other Programs / Otro ingresos *</label><br><br>
                <input type="text" id="other-programs" name="other-programs" required placeholder="(WIC, SNAP, SSI, SSD, etc.)"><br><br>

                <!--Free/Reduced Lunch-->
                <label for="lunch">Does the enrolling child receive free or reduced lunch? / ¿El niño/a inscrito recibe almuerzo gratis o reducido? *</label><br><br>
                <select id="lunch" name="lunch" required>
                    <option value="" disabled selected>Select</option>
                    <option value="free">Free</option>
                    <option value="reduced">Reduced / Gratis</option>
                    <option value="neither">Neither / Reducido</option>
                </select>
                <br><br>

            <h2>Emergency Medical Authorization and Waiver of Liability / Autorización médica de emergencia y exención de responsabilidad</h2><br>
                <p>I consent to my child's participation in the programs and activities offered by Stafford Junction. I acknowledge the
                risks associated with such activities, and I release Stafford Junction, its employees, agents, and volunteers from
                liability for any injury, loss, or damage to persons or property that may occur. In an emergency where I cannot be
                reached immediately, I authorize Stafford Junction to obtain prompt medical care, including diagnostic tests, surgery,
                hospitalization, and/or medication administration for my child. I understand that this authorization applies exclusively
                to genuine emergencies where I cannot be reached, and that Stafford Junction will make every effort to reach me or
                the designated emergency contacts. I understand that I am responsible for any costs incurred for my child's medical
                treatment not covered or reimbursed by my health insurance provider.</p><br>

                <p>Doy mi consentimiento para que mi hijo/a asista a los programas y actividades organizados por Stafford Junction.
                Entiendo que hay riesgos involucrados en cualquier actividad y libero a Stafford Junction, sus empleados, agentes y
                voluntarios de toda responsabilidad por cualquier lesión, pérdida y/o daño a la persona / propiedad que pueda ocurrir.
                Autorizo a Stafford Junction a obtener atención inmediata y doy consentimiento para la hospitalización, el desempeño
                de pruebas diagnósticas necesarias, el uso de cirugía y/o la administración de medicamentos a mi hijo/a si ocurre una
                emergencia cuando no pueda ser localizado inmediatamente. También se entiende que este acuerdo abarca
                únicamente aquellas situaciones que son verdaderas emergencias y sólo cuando no pueda ser localizado. Entiendo
                que Stafford Junction hará todo lo posible para ponerse en contacto conmigo y/o con los contactos de emergencia
                designados. Reconozco que soy responsable en última instancia de todos los costos incurridos no reembolsables por
                mi proveedor de seguro médico.</p><br>
                
                <!--Medical Insurance Company-->
                <label for="insurance">Medical Insurance Company / Compañía de Seguro Médico *</label><br><br>
                <input type="text" name="insurance" id="insurance" required placeholder="Medical Insurance Company"><br><br>

                <!--Policy Number-->
                <label for="policy-num">Policy Number / Número de póliza *</label><br><br>
                <input type="text" name="policy-num" id="policy-num" required placeholder="Policy Number"><br><br>

            <h2>Transportion / Transporte</h2><br>
                <p>I consent to the following transportation situations, allowing for my child to be transported as outlined:</p><br>
                
                <p><b><u>STEAM Camp</u> (June 10-June 13):</b> transportation is provided by Stafford Junction van on a first come, first served
                basis. Max capacity of 13 participants.</p><br>

                <p><b><u>Summer Junction</u> (July 19 - August 2):</b> transportation is provided through SCPS school buses.</p><br>

                <p><b>* After confirming the application, specific pick-up and drop-off locations and times will be shared. *</b></p><br>

                <p>Doy mi consentimiento a los siguientes modos de transporte que permiten que mi hijo/a sea transportado como se describe:</p><br>

                <p><b><u>STEAM Camp</u> (lunes 10 de junio – jueves 13 de junio):</b> el transporte será proporcionado a través del van de
                Stafford Junction en orden recibido, máximo 13 participantes.</p><br>

                <p><b><u>Summer Junction</u> (lunes 1 de julio – viernes 2 de agosto):</b> el transporte será proporcionado a través de buses
                escolares.</p><br>

                <p><b>* Las ubicaciones y horarios específicos de recogida y entrega se anunciarán después de que confirmemos su registración. *</b></p>

            <br><br>

            <h2>Code of Conduct / Código de conducta</h2><br>
                <p>Stafford Junction practices four core values: Caring, Honesty, Respect, and Responsibility. We are not a daycare
                service. The program is staffed by volunteers whose sole responsibility is to provide stimulating activities to youth,
                preventing summer learning loss. Misbehavior by students will not be tolerated.</p><br>

                <p>The standard disciplinary process is as follows: (1) verbal warning, (2) a second verbal warning and parents contacted,
                (3) dismissal from the program.</p><br>

                <p>Exceptions: If a student commits a serious infraction, the Youth Program Manager has the option to immediately
                dismiss the child from the program.</p><br><br>

                <p>Stafford Junction practica cuatro valores fundamentales: afecto, honestidad, respeto, y responsabilidad. No somos un
                servicio de guardería. El programa cuenta con voluntarios cuya única responsabilidad es proporcionar actividades
                estimulantes a los jóvenes, evitando la pérdida de aprendizaje durante el verano. La mala conducta de los estudiantes
                no será tolerada.</p><br>

                <p>El proceso disciplinario es el siguiente: (1) advertencia verbal, (2) segunda advertencia verbal y contacto con los
                padres, (3) suspensión de dos días del programa y contacto con los padres, (4) finalmente expulsión del programa.</p><br>

                <p>Excepciones: Si un estudiante comete una infracción grave, el gerente de programas juveniles puede despedir
                inmediatamente al niño/a del programa.</p><br><br>

            <h2>Photograph and Video Waiver</h2><br>
                <p>I acknowledge that Stafford Junction may utilize photographs or videos of participants that may be taken during
                involvement in Stafford Junction activities. This includes internal and external use, including but not limited to
                Stafford Junction’s website, Facebook, and publications. I consent to such uses and hereby waive all rights of
                compensation. If I do not wish the image of my child to be included in those mentioned above, it is my responsibility to
                inform them to exclude themselves from photographs or videos taken during such activities.</p><br><br>

                <p>Reconozco que Stafford Junction puede utilizar fotografías o vídeos de los participantes que sean tomadas durante su
                participación en las actividades de Stafford Junction. Esto incluye uso interno y externo, incluyendo, pero no limitado
                a la página web de Stafford Junction, Facebook, y publicaciones. Doy mi consentimiento para tales usos y renuncio a
                todos los derechos de compensación. Si no deseo que la imagen de mi hijo/a se incluya en lo anteriormente
                mencionado, es mi responsabilidad informarles que no participen en las fotografías o vídeos tomados durante dichas
                actividades.</p><br><br>

            <h2>Acknowledgment and Consent</h2><br>
                <p>By signing below, I acknowledge, understand, accept, and agree to all policies and waivers stated and outlined in this
                Summer Junction Registration Form for the current school year.</p><br>
                <p>By electronically signing, you agree that your e-signature holds the same legal validity and effect as a handwritten signature.</p><br><br>

                <p>Al firmar a continuación, reconozco, entiendo, acepto y estoy de acuerdo con todas las políticas y exenciones indicadas y descritas en este 
                Formulario de Registro de Summer Junction para el año escolar actual.</p><br>
                <p>Al firmar electrónicamente, usted acepta que su firma electrónica tiene la misma validez y efecto legal que una firma manuscrita.</p><br><br>

                <!--Parent/Guardian Electronic Signature-->
                <label for="signature">Parent/Guardian Signature / Firma del padre/tutor *</label><br><br>
                <input type="text" name="signature" id="signature" required placeholder="Parent/Guardian Signature" required><br><br>

                <!--Date-->
                <label for="signature-date">Date / Fecha *</label><br><br>
                <input type="date" id="signature-date" name="signature-date" required placeholder="Date" max="<?php echo date('Y-m-d'); ?>"><br><br>

                <button type="submit" id="submit">Submit / Enviar</button>

            <?php 
                if (isset($_GET['id'])) {
                    echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel / Cancelar</a>';
                } else {
                    echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel / Cancelar</a>';
                }
            ?>

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
            </form>
        </div>

    </body>
</html>