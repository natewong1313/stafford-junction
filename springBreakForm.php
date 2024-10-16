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
    $required = array("email", "name", "school_date", "isAttending", "hasWaiver", "questions_comments");
    if(!wereRequiredFieldsSubmitted($args, $required)){
        echo "Not all fields complete";
        die();
    }else {
        foreach($args as $key => $val){
            echo "{$key}:" . " " . "{$val}" . "<br>";
        }
    }
}


?>

<html>

<head>
    <?php include_once("universal.inc")?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stafford Junction | Spring Break Form</title>
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #ffffff;
    color: #333;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

h1 {
    background-color: #7e0d07;
    color: white;
    padding: 20px;
    text-align: center;
    margin: 0;
    font-size: 2.5em;
}

#formatted_form {
    max-width: 800px;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

p {
    margin-bottom: 20px;
}

.info {
    background-color: #f9f9f9;
    padding: 10px;
    border-left: 5px solid #7e0d07;
}

.pickup-times {
    background-color: #fcdd2b;
    padding: 10px;
    border-left: 5px solid #7e0d07;
}

.pickup-times p {
    margin-bottom: 5px;
}

.pickup-times strong {
    color: #7e0d07;
}

@media (max-width: 600px) {
    h1 {
        font-size: 1.8em;
        padding: 15px;
    }

    #formatted_form {
        padding: 15px;
    }
}
</style>
</head>

<body>

    <h1>Stafford Junction Spring Break Camp 2024</h1>

    <div id="formatted_form">
        <div class="info">
            <p>Each day of Spring Break, a different group of Brain Builders students are invited to join us for games
                and outdoor activities. If weather permits, we'll head to Pratt Park. In case of inclement weather,
                we'll be at the Stafford Junction Building at 791 Truslow Rd. Meals, drinks, and water will be provided,
                as well as transportation via the Stafford Junction Van.</p>
            <p>Please dress appropriately for outdoor activities and wear closed-toe shoes (no slides or flip-flops). We
                ask that all students have a current 2024 Stafford Junction Field Trip Waiver. If your student does not
                have a waiver, they will not be able to attend. Camp will run from 12:00 pm - 3:00 pm.</p>

            <p>Cada día de las vacaciones de primavera, se invita a un grupo diferente de estudiantes de Brain Builders
                a participar en juegos y actividades al aire libre. Si el clima lo permite, iremos a Pratt Park. Si hay
                mal tiempo, estaremos en el edificio Stafford Junction en 791 Truslow Rd. Se proporcionarán comidas,
                bebidas y transporte en la furgoneta de Stafford Junction.</p>
            <p>Vístase adecuadamente para actividades al aire libre y use zapatos cerrados (sin chanclas ni chanclas).
                Solicitamos que todos los estudiantes tengan un formulario de excursión a Stafford Junction actualizado
                de 2024. Si no lo tiene, su estudiante no podrá asistir. El campamento se desarrollará de 12:00 a 15:00
                horas.</p>
        </div>

        <div class="pickup-times">
            <h2>Days/Pickup Times:</h2>
            <p><strong>Monday, March 11</strong> - Anne Moncure Elementary School, 11:40 am at the Mailboxes</p>
            <p><strong>Tuesday, March 12</strong> - Conway Elementary School, 11:40 am at Anvil Rd at Playground</p>
            <p><strong>Wednesday, March 13</strong> - Falmouth Elementary School, 11:40 am at Thomas Jefferson Place at
                James Madison Circle</p>
            <p><strong>Thursday, March 14</strong> - Rocky Run Elementary School, 11:40 am at the corner of Lyons Blvd &
                Cynthia's Place</p>
        </div>

        <p>* Indicates required question</p>
    </div>

    <span>* Indicates required</span><br><br>

    <form id="springBreakForm" action="" method="post">

        <!--Email-->
        <label for="email">1. Email*</label><br><br>
        <input type="text" name="email" id="email" placeholder="Email/Electrónico" required><br><br>
        <!-- Add extra space here before question 2 -->
        <br><br><br>

        <!--Name-->
        <label for="name">2. Registered Brain Builder Student Name - Nombre del estudiante *</label><br><br>
        <input type="text" name="name" id="name" placeholder="Name/Nombre" required><br><br>
        <!-- Add extra space here before question 3 -->
        <br><br><br>


        <!--School/Date-->
        <label for="school_date">3. Brain Builders School and Date for Spring Break Camp: * Escuela Brain Builders y
            fecha para el campamento de vacaciones de primavera:</label><br><br>
        <p>Mark only one oval.</p><br>

        <input type="radio" id="anne_moncure" name="school_date"
            value="Anne Moncure (Monday March 11 / Lunes 11 de marzo)" required>
        <label for="anne_moncure">Anne Moncure (Monday March 11 / Lunes 11 de marzo)</label><br><br>

        <input type="radio" id="conway" name="school_date" value="Conway (Tuesday March 12 / Martes 12 de marzo)"
            required>
        <label for="conway">Conway (Tuesday March 12 / Martes 12 de marzo)</label><br><br>

        <input type="radio" id="falmouth" name="school_date"
            value="Falmouth (Wednesday March 13 / Miércoles 13 de marzo)" required>
        <label for="falmouth">Falmouth (Wednesday March 13 / Miércoles 13 de marzo)</label><br><br>

        <input type="radio" id="rocky_run" name="school_date" value="Rocky Run (Thursday March 14 / Jueves 14 de marzo)"
            required>
        <label for="rocky_run">Rocky Run (Thursday March 14 / Jueves 14 de marzo)</label><br><br>
        <!-- Add extra space here before question 4 -->
        <br><br><br>

        <!--isAttending-->
        <label for="isAttending">4. Will your student be attending? * ¿Asistirá su estudiante?</label><br><br>
        <p>Mark only one oval</p><br>
        <input type="radio" id="choice_1" name="isAttending" value="yes" required>
        <label for="choice_1">Yes / Sí</label><br><br>

        <input type="radio" id="choice_2" name="isAttending" value="no" required>
        <label for="choice_2">No.</label><br><br>
        <!-- Add extra space here before question 5 -->
        <br><br><br>

        <!--hasWaiver-->
        <label for="hasWaiver">5. Have you filled out a 2024 Stafford Junction Field Trip Waiver? * ¿Ha completado un
            formulario de
            excursión a Stafford Junction para 2024?</label><br><br>

        <p>Mark only one oval</p><br>
        <input type="radio" id="waiver_yes" name="hasWaiver" value="yes" required>
        <label for="waiver_yes">Yes / Sí</label><br><br>

        <input type="radio" id="waiver_no" name="hasWaiver" value="no" required>
        <label for="waiver_no">No.</label><br><br>
        <!-- Add extra space here before question 5 -->
        <br><br><br>

        <!--questions_comments-->
        <label for="questions_comments">6. Questions or Comments: Pregunta o comentarios:</label><br><br>
        <textarea id="questions_comments" name="questions_comments" rows="6" cols="50"
            placeholder="Type your questions or comments here... / Escriba sus preguntas o comentarios aquí..."></textarea><br><br>
    
        <button type="submit" id="submit">Submit</button>
        </form>


    <!--ChatGPT code below, repurposed for this form-->

    <!--Javascript code that makes sure that the user can only fill in the text field if they select the 'other' radio button-->
    <script>
    const choice1 = document.getElementById('choice_1');
    const choice2 = document.getElementById('choice_2');
    const otherChoice = document.getElementById('choice_3');
    const otherInput = document.getElementById('other');

    otherChoice.addEventListener('change', () => {
        if (otherChoice.checked) {
            otherInput.disabled = false;
        }
    });

    choice1.addEventListener('change', () => {
        if (choice1.checked) {
            otherInput.disabled = true;
        }
    });

    choice2.addEventListener('change', () => {
        if (choice2.checked) {
            otherInput.disabled = true;
        }
    });
    </script>

</body>

</html>