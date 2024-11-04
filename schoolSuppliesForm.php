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

include_once("database/dbFamily.php");
$family = retrieve_family_by_id($_SESSION["_id"]);
$family_email = $family->getEmail();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('include/input-validation.php');
    //require_once('database/dbSchoolSupplies.php');
    $args = sanitize($_POST, null);
    $required = array("email", "name", "grade", "school", "community", "need_backpack");
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
        <title>School Supplies Form</title>
    </head>
    <body>

        
        <h1>Stafford Junction School Supplies / Utiles Escolares</h1>
        <div id="formatted_form">
            <p>Stafford Junction is holding a Back-to-School Community Day on August 10. This form will guarantee that your child will have a premade
            backpack before Community Day that can be picked up during the event. Please submit a form for each child
            </p>

            <p>Stafford Junction llevará a cabo un Día de la Comunidad de Regreso a la Escuela el 10 deagosto. Este formulario garantizará que su hijo tendrá una mochila con 
                útiles escolaresantes del Día de la Comunidad que se puede recoger durante el evento. Porfavor, envíe un formulario para cada niño.</p>
            
            <br>
            
            <span>* Indicates required</span><br><br>

            <form id="suppliesForm" action="" method="post">
                <!--email-->
                <label for="email">1. Email*</label><br><br>
                <input type="text" name="email" id="email" placeholder="Email" required value="<?php echo htmlspecialchars($family_email); ?>"><br><br>
                
                <!--Child Name-->
                <label for="name">2. Child Name / Nombre del Estudiante*</label><br><br>
                <input type="text" name="name" id="name" placeholder="Name/Nombre" required><br><br>

                <!--Grade-->
                <label for="grade">3. Grade / Grado*</label><br><br>
                <input type="text" name="grade" id="grade" placeholder="Grade/Grado" required><br><br>

                <!--School-->
                <label for="school">4. School / Escuela*</label><br><br>
                <input type="text" name="school" id="school" placeholder="School/Escuela" required><br><br>

                <!--Community Bag Info--->
                <label>5. Will you pick up the bag during Community Day or need it brought to you? / ¿Recogerás la
                bolsa durante el Día de la Comunidad o necesitarás que te la traigan?</label><br><br>

                <p>Mark only one oval</p><br>
                <input type="radio" id="choice_1" name="community" value="pick_up" required>
                <label for="choice_1">I will pick up the bag on Community day (August 10, 12-3pm). / Recogere la mochila durante el
                Dia de Comunidad (10 de Agosto, 12PM - 3PM)</label><br><br>

                <input type="radio" id="choice_2" name="community" value="no_pick_up" required>
                <label for="choice_2">I will not be able to attend Community Day, I will need the bag brought to me. / No puedo
                atender el Dia de Comunidad, necesito que me traigan la mochila.</label><br><br>

                <input type="radio" id="choice_3" name="community" value="other" required>
                <label for="choice_3">Other</label>
                <input type="text" name="other" id="other" disabled>

                <!--ChatGPT code below, repurposed for this form-->

                <!--Javascript code that makes sure that the user can only fill in the text field if they select the 'other' radio button-->
                <script>
                //Grabs each radio button by id
                const choice1 = document.getElementById('choice_1');
                const choice2 = document.getElementById('choice_2');
                const choice3 = document.getElementById('choice_3');
                
                //gets the text field by id
                const myInput = document.getElementById('other'); 

                //checks to see if the radio button for 'other' is clicked
                choice3.addEventListener('change', () => {
                    if (choice3.checked) {
                        //if it is clicked, then the disable property of the input field is turned off
                        myInput.disabled = false;
                    }
                });

                //checks to see if choice 1 or 2 were clicked, if they were, disable the text input associated with choice 3
                choice1.addEventListener('change', () => {
                    if (choice1.checked) {
                        myInput.disabled = true;
                    }
                });

                choice2.addEventListener('change', () => {
                    if (choice2.checked) {
                        myInput.disabled = true;
                    }
                });

                </script>

                <!--Backpack-->
                <br><br>

                <label>6. Will you need a backpack? / ¿Necesitarás una mochila?* </label><br><br>
                <p>Mark only one oval</p><br>
                <input type="radio" id="choice_a" name="need_backpack" value="have_backpack_already">
                <label for="choice_a">I already have a backpack, I just need school supplies. / Ya tengo mochila, solo necesito útiles
                escolares.</label><br><br>
                <input type="radio" id="choice_b" name="need_backpack" value="need_backpack">
                <label for="choice_b">I need a backpack. / Necesito una mochila.</label><br><br>
                <br><br>

                <button type="submit" id="submit">Submit</button>
                <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
            </form>
        </div>
        
    </body>
</html>