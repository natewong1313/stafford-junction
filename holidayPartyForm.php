<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if(isset($_SESSION['_id'])){
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

// include the header .php file s
if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('include/input-validation.php');
    //require_once('database/dbSpringBreakForm.php');
    $args = sanitize($_POST, null);

    $required = array(
        'email',
        'child_first_name',
        'child_last_name',
        'isAttending',
        'transportation',
        'neighborhood',
        'question_comments',
    );
    
    if(!wereRequiredFieldsSubmitted($args, $required)){
        echo "Not all fields complete";
        die();
    }else {
        require_once("database/dbChildren.php");
        require_once("database/dbHolidayPartyForm.php");
        
        //retrieves child specified in form
        $row = retrieve_child_by_firstName_lastName_famID($args['child_first_name'], $args['child_last_name'], $userID);
        $success = insert_into_dbHolidayPartyForm($args, $row['id']); //Add to database form data and child id

        if($success){
            $successMessage = "Form submitted successfully!";
        }
    }
}

?>

<html>

<head>
    <!-- Include universal styles, scripts, or configurations via external file -->
    <?php include_once("universal.inc") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stafford Junction | Holiday Party Form</title>
</head>

<body>

    <!-- Main heading of the page -->
    <h1>Stafford Junction Brain Builders Holiday - Party Celebración de días festivos</h1>

    <!-- Container for formatted form content -->
    <div id="formatted_form">

        <!-- Event details in English -->
        <p><b>When: Thursday, December 21, 1:00-4:00 PM</b></p>
        <br>
        <p><b>Where: 791 Truslow Road, Fredericksburg, VA. 22406</b></p>
        <br>
        <p>
            Please let us know if your student will be able to attend the Holiday Party. Transportation pick-up/drop-off
            times will be distributed on December 18. Meals will be provided. <br><br>
            Please email <b>crice@staffordjunction.org</b> with any questions you may have.
        </p>
        <hr>
        <br>

        <!-- Event details in Spanish -->
        <p><b>Cuándo: jueves 21 de diciembre de 13:00 a 16:00 horas.</b></p>
        <br>
        <p><b>Dónde: 791 Truslow Road, Fredericksburg, VA. 22406</b></p>
        <br>
        <p>
            Háganos saber si su estudiante podrá asistir a la fiesta navideña. Los horarios de recogida y entrega del
            transporte se distribuirán el 18 de diciembre. Se proporcionarán comidas. <br><br>
            Envíe un correo electrónico a <b>crice@staffordjunction.org</b> con cualquier pregunta que pueda tener.
        </p>
        <hr>
        <br>

        <!-- Required question indicator -->
        <p><strong>* Indicates required question</strong></p>
    </div>
    <br><br>
    <div id="formatted_form">
        <form method="POST">
            <!-- Email -->
            <label for="email">Email - Correo Electrónico* </label>
            <input type="text" name="email" id="email" placeholder="Email - Correo Electrónico" required>

            <!-- Child's First Name and Last Name -->
            <label for="child_first_name">Registered Brain Builder Student First Name - Nombre del estudiante *</label>
            <input type="text" name="child_first_name" id="child_first_name" placeholder="First Name / Nombre" required>

            <label for="child_last_name">Registered Brain Builder Student Last Name - Apellido del estudiante * </label>
            <input type="text" name="child_last_name" id="child_last_name" placeholder="Last Name / Apellido" required><br><br>

            <!-- Attendance Section -->
            <div>
                <p><strong>Will your student be attending? * ¿Asistirá su estudiante?</strong></p>

                <!-- Option for "Yes" -->
                <label>
                    <input type="radio" name="isAttending" value="1" required> Yes / Sí
                </label>
                <br><br>

                <!-- Option for "No" -->
                <label>
                    <input type="radio" name="isAttending" value="0" required> No
                </label>
            </div>
            <br><br>

            <!-- Transportation Section -->
            <div>
                <p><strong>Transportation * Transporte</strong></p>

                <!-- Option for providing own transportation -->
                <label>
                    <input type="radio" name="transportation" value="provide_own" required> I will provide transportation for my
                    student. / Proporcionaré transporte para mi estudiante.
                </label>
                <br><br>

                <!-- Option for needing Stafford Junction transportation -->
                <label>
                    <input type="radio" name="transportation" value="stafford_junction" required> My student will need Stafford
                    Junction to provide transportation. / Mi estudiante necesitará Stafford Junction para proporcionar
                    transporte.
                </label>
            </div>
            <br><br>

            <!-- Neighborhood Pickup Section -->
            <div>
                <p><strong>Which neighborhood will your student be picked up from? * ¿De qué vecindario recogerán a su
                        estudiante?</strong></p>

                <!-- Option for "Olde Forge" -->
                <label>
                    <input type="radio" name="neighborhood" value="olde_forge" required> Olde Forge
                </label>
                <br><br>
                
                <!-- Option for "Jefferson Place" -->
                <label>
                    <input type="radio" name="neighborhood" value="jefferson_place" required> Jefferson Place
                </label>
                <br><br>

                <!-- Option for "Foxwood" -->
                <label>
                    <input type="radio" name="neighborhood" value="foxwood" required> Foxwood
                </label>
                <br><br>

                <!-- Option for "England Run" -->
                <label>
                    <input type="radio" name="neighborhood" value="england_run" required> England Run
                </label>
                <br><br>

                <!-- Option for "Other" with text input for specifying neighborhood -->
                <label>
                    <input type="radio" name="neighborhood" value="other" required> Other:
                    <input type="text" name="neighborhood" placeholder="Specify other neighborhood">
                </label>

            </div>
            <br><br>

            <!-- Additional Information Section -->
            <div>
                <p><strong>Question or Comments: Pregunta o comentarios:</strong></p>

                <!-- Large text area for additional comments or information -->
                <label>
                    <textarea name="question_comments" rows="6" cols="50"
                        placeholder="Enter any additional information here / Ingrese cualquier información adicional aquí"></textarea>
                </label>
            </div>
            <br><br>

            <!-- Submit and Cancel Buttons -->
            <button type="submit">Submit</button>
            <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>

            <?php //If the user is an admin or staff, the message should appear at index.php
            if(isset($successMessage) && $accessLevel > 1){
                echo '<script>document.location = "index.php?formSubmitSuccess";</script>';
            }else if(isset($successMessage) && $accessLevel == 1){ //If the user is a family, the success message should apprear at family dashboard
                echo '<script>document.location = "familyAccountDashboard.php?formSubmitSuccess";</script>';
            }
            ?>
            </div>
        </form>
    </div>
    
</body>

</html>