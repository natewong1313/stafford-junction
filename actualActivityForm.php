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

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('include/input-validation.php');
    require_once('database/dbActualActivityForm.php');
    $args = sanitize($_POST, null);
    $required = array("activity", "date", "program", "start_time", "end_time", "start_mile", "end_mile", "address",
        "attend_num", "volstaff_num", "materials_used", "meal_info", "act_costs", "act_benefits", "attendees[]");
    if(!wereRequiredFieldsSubmitted($args, $required)){
        echo "Error: Not all fields complete";
        die();
    } else {
        $activityID = createActualActivityForm($args, $connection);
        if ($activityID === null) {
            echo "Error: dbActualActivityForm insertion error.";
        }
    }
}

?>

<html>
    <head>
        <?php include_once("universal.inc")?>
        <title>Actual Activities Form</title>
        <link rel="stylesheet" href="base.css">
    </head>
    
    <body>
        <h1>Actual Activities Form</h1>
        <div id="formatted_form">
            
        <span>* Indicates required field</span><br><br>

        <form id="actualActivityForm" action="" method="post">
            <hr>
            <h2>General Information</h2>
            <br><br>

            <!-- 1. Activity -->
            <label for="activity">1. Activity*</label><br><br>
            <input type="text" name="activity" id="activity" placeholder="Activity" required><br><br>
            
            <!-- 2. Date -->
            <label for="date">2. Date*</label><br><br>
            <input type="date" name="date" id="date" placeholder="Date" required><br><br>

            <!-- 3. Program -->
            <label for="program">3. Program*</label><br><br>
            <input type="text" name="program" id="program" placeholder="Program" required><br><br>

            <!--4. Start Time -->
            <label for="start_time">4. Start Time*</label><br><br>
            <input type="time" name="start_time" id="start_time" placeholder="Start Time" required><br><br>

            <!--End Time-->
            <label for="end_time">5. End Time*</label><br><br>
            <input type="time" name="end_time" id="end_time" placeholder="End Time" required><br><br>
            
            <!--Vehicle Start Mileage-->
            <label for="start_mile">6. Vehicle Start Mileage*</label><br><br>
            <input type="number" name="start_mile" id="start_mile" placeholder="Vehicle Start Mileage" required><br><br>

            <!--Vehicle End Mileage-->
            <label for="end_mile">7. Vehicle End Mileage*</label><br><br>
            <input type="number" name="end_mile" id="end_mile" placeholder="Vehicle End Mileage" required><br><br>

            <!--Site Address-->
            <label for="address">8. Site Address*</label><br><br>
            <input type="text" name="address" id="address" placeholder="Site Address" required><br><br>

            <!--Actual Attendance Number-->
            <label for="attend_num">7. Actual Attendance Number*</label><br><br>
            <input type="number" name="attend_num" id="attend_num" placeholder="Actual Attendance Number" required><br><br>

            <!--Actual Volunteer/Staff Number-->
            <label for="volstaff_num">8. Actual Volunteer/Staff Number*</label><br><br>
            <input type="number" name="volstaff_num" id="volstaff_num" placeholder="Actual Volunteer/Staff Number" required><br><br><br>

            <hr>
            <h2>Materials/Costs</h2>
            <br><br>

            <!--Materials Used--->
            <label for="materials_used">9. Materials Used*</label><br><br>
            <input type="text" name="materials_used" id="materials_used" placeholder="Materials Used" required><br><br><br>

            <!--Meal Information--->
            <label for="meal_info">10. Was there a meal? If so, was it provided or paid?*</label><br><br>
            <input type="radio" id="choice_1" name="meal_info" value="meal_provided" required>
            <label for="choice_1">Meal: Provided</label><br><br>

            <input type="radio" id="choice_2" name="meal_info" value="meal_paid" required>
            <label for="choice_2">Meal: Paid</label><br><br>

            <input type="radio" id="choice_3" name="meal_info" value="no_meal" required>
            <label for="choice_3">No meal</label><br><br><br>

            <!--Activity Costs-->
            <label for="act_costs">11. Activity Costs (Please list and seperately provide PA's)*</label><br><br>
            <input type="text" name="act_costs" id="act_costs" placeholder="Actual Volunteer/Staff Number" required><br><br><br>
        
            <hr>
            <h2>Educational Benefits</h2>
            <br><br>

            <!--Benefits-->
            <label for="act_benefits">12. What actvities took place; what benefits do participants receive from these Activities?*</label><br><br>
            <input type="text" name="act_benefits" id="act_benefits" placeholder="Actual Volunteer/Staff Number" rows="5" required><br><br><br>

            <hr>
            <h2>Attendance</h2>
            <br><br>

            <!--Attendance-->
            <label for="attendance">13. Attendance (must already be in AllClients; Waiver and Emergency Contact Information should be on file and in binder)*</label><br>
            
            <button type="button" class= "addRemove-btn" onclick="addInput()">Add Person</button><br><br>
            <div id="attendeesContainer"></div>

            <script>
                function addInput() {
                    //creates a new div element for the inputGroup
                    var newInputGroup = document.createElement('div');
                    newInputGroup.className = 'input-group';

                    //creates new input element to add an attendee into attendance[] array
                    var newInput = document.createElement('input');
                    newInput.className = 'input-form';
                    newInput.type = 'text';
                    newInput.name = 'attendees[]';
                    newInput.placeholder = 'Name of Attendee';

                    //creates remove button
                    var removeButton = document.createElement('button');
                    removeButton.className = 'addRemove-btn';
                    removeButton.type = 'button';
                    removeButton.textContent = 'Remove Person';
                    removeButton.onclick = function() {
                        newInputGroup.remove();
                    };

                    //adds add and remove buttons to inputGroup parent
                    newInputGroup.appendChild(newInput);
                    newInputGroup.appendChild(removeButton);

                    //adds the new inputGroup to the attendeesContainer to display
                    document.getElementById("attendeesContainer").appendChild(newInputGroup);
                }
                </script>

                <br><hr>

                <button type="submit" id="submit">Submit</button>
                <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
            </form>
        </div>
    </body>
</html>