<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Initialize family data variables
$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;
$family = null;
$family_email = null;
$family_first_name = null;
$family_last_name = null;
$family_phone = null;
$family_zip = null;
$family_city = null;
$family_address = null;
$family_neighborhood = null;
$family_state = null;
$children = null;
$children_count = null;
$children_ages = null;
$family_home_phone = null;
$family_cell_phone = null;

if(isset($_SESSION['_id'])){
    require_once('domain/Family.php');
    require_once('include/input-validation.php');
    require_once('database/dbProgramInterestForm.php');
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
} else {
    header('Location: login.php');
    die();
}

include_once("database/dbFamily.php");
include_once("database/dbChildren.php");

// Get family data for autopopulating form
$family = retrieve_family_by_id($_GET['id'] ?? $userID);
$family_email = $family->getEmail();
$family_first_name = $family->getFirstName();
$family_last_name = $family->getLastName();
$family_phone = $family->getPhone();
$family_zip = $family->getZip();
$family_city = $family->getCity();
$family_address = $family->getAddress();
$family_neighborhood = $family->getNeighborhood();
$family_state = $family->getState();
$children = retrieve_children_by_family_id($_GET['id'] ?? $userID);
$children_count = count($children);
$children_ages = getChildrenAges($children);

// Get home phone number
if ($family->getPhoneType() == "home") {
    $family_home_phone = $family->getPhone();
} else if ($family->getSecondaryPhoneType() == "home") {
    $family_home_phone = $family->getSecondaryPhone();
}
// Get cell phone number
if ($family->getPhoneType() == "cellphone") {
    $family_cell_phone = $family->getPhone();
} else if ($family->getSecondaryPhoneType() == "cellphone") {
    $family_cell_phone = $family->getSecondaryPhone();
}


// Gets the ages of each child and returns them as a string
function getChildrenAges($children) {
    $ages = "";
    $last = end($children);
    foreach ($children as $child) {
        // Get current time and child date of birth
        $current_time = strtotime(date("Y-m-d"));
        $dob = strtotime($child->getBirthdate());
        // Calculate age
        $age = floor((abs($dob - $current_time)) / (365 * 60 * 60 * 24));
        // Append it to end of ages string
        $ages .= $age;
        // If not at last child in children array, add a comma to string
        if ($child != $last) {
            $ages .= ", ";
        }
    }
    return $ages;
}

// program interests and topic interests both are stored in arrays called "programs" and "topics" within the POST array
// availability data is stored in a multidimentional array called "days" 
$data = getProgramInterestFormData($_GET['id'] ?? $userID);
$programData = null;
$topicData = null;
$availabilityData = null;

if (!$data) {
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        require_once('include/input-validation.php');
        // Ignore days array during sanitation as it will cause an error
        $ignoreList = array('days');
        $args = sanitize($_POST, $ignoreList);
        // Sanitize each day in days array individually
        foreach ($args['days'] as $day) {
            $day = sanitize($day, null);
        }
        $required = array("first_name", "last_name", "address", "city", "neighborhood", "state", "zip", "cell_phone",
            "home_phone", "email", "child_num", "child_ages", "adult_num");
        $args['cell_phone'] = validateAndFilterPhoneNumber($args['cell_phone']);
        $args['home_phone'] = validateAndFilterPhoneNumber($args['home_phone']);
        if(!wereRequiredFieldsSubmitted($args, $required)){
            echo "Not all fields complete";
            die();
        } else {
            $success = createProgramInterestForm($args);
        }
    }
} else {
    $programData = getProgramInterestData($_GET['id'] ?? $userID);
    $topicData = getTopicInterestData($_GET['id'] ?? $userID);
    $availabilityData = getAvailabilityData($_GET['id'] ?? $userID);
}
?>

<html>
    <head>
        <?php include_once("universal.inc")?>
        <title>Program Interest Form</title>
    </head>
    <body>

        
        <h1>Program Interest Form / formulario de interés del programa</h1>
        <?php 
            if (isset($_GET['formSubmitFail'])) {
                echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Error Submitting Form</div>';
            }
        ?>
        <div id="formatted_form">

            <p>Please fill out this survey to help us better understand the needs of the community and schedule future classes
                and workshops. Please return to Stafford Junction at 791 Truslow Road, Fredericksburg, VA 22406, or email us
                at info@staffordjunction.org. Questions? Please call us at 540-368-0081.
                <br><br>
                Complete esta encuesta para ayudarnos a comprender mejor las necesidades de la comunidad y programar clases futuras.
                y talleres. Regrese a Stafford Junction en 791 Truslow Road, Fredericksburg, VA 22406, o envíenos un correo electrónico.
                en info@staffordjunction.org. ¿Preguntas? Por favor llámenos al 540-368-0081.
            </p>
            
            <br>
            
            <span>* Indicates required</span><br><br>

            <form id="programInterestForm" action="" method="post">
                <h2>General Information / Información general</h2>
                <br>

                <!-- 1. First Name-->
                <label for="first_name">* First Name / Nombre</label><br><br>
                <input type="text" name="first_name" id="first_name" placeholder="First Name/Nombre" required <?php showProgramInterestData($data['first_name'] ?? null, $family_first_name)?>>
                <br><br>

                <!-- 2. Last Name-->
                <label for="last_name">* Last Name / Apellido</label><br><br>
                <input type="text" name="last_name" id="last_name" placeholder="Last Name/Apellido " required <?php showProgramInterestData($data['last_name'] ?? null, $family_last_name)?>>
                <br><br>

                <!-- 3. Address-->
                <label for="address">* Address / Dirección</label><br><br>
                <input type="text" name="address" id="address" placeholder="Address/Dirección" required <?php showProgramInterestData($data['address'] ?? null, $family_address)?>>
                <br><br>

                <!-- 4. Neighborhood-->
                <label for="neighborhood">* Neighborhood / Vecindario</label><br><br>
                <input type="text" name="neighborhood" id="neighborhood" placeholder="Neighborhood/Vecindario" required <?php showProgramInterestData($data['neighborhood'] ?? null, $family_neighborhood)?>>
                <br><br>

                <!-- 4. City-->
                <label for="city">* City / Ciudad</label><br><br>
                <input type="text" name="city" id="city" placeholder="City/Ciudad" required <?php showProgramInterestData($data['city'] ?? null, $family_city)?>>
                <br><br>

                <!-- 4. State-->
                <label for="state">* State / Estado</label><br><br>
                <select id="state" name="state">
                        <option value="">--</option>
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
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                </select><br><br>

                <script>
                    // Get state value from dbProgramInterestForm if family already submitted the form
                    var data_state = '<?php echo $data['state'] ?? '';?>';
                    // Get state value from dbFamily table
                    var family_state = '<?php echo $family_state ?? '';?>';
                    
                    if (data_state !== '') {
                        // If viewing completed form, show value and disable input
                        element = document.getElementById("state");
                        option = element.querySelector("option[value = " + data_state + "]");
                        option.selected = true;
                        element.disabled = true;
                        element.style.backgroundColor = 'yellow';
                        element.style.color = 'black';
                    } else if (family_state !== '') {
                        // Else, autopopulate using the state value from dbFamily
                        element = document.getElementById("state");
                        option = element.querySelector("option[value = " + family_state + "]");
                        option.selected = true;
                    }
                </script>

                <!-- 5. Zip Code-->
                <label for="zip">* Zip Code / Código postal</label><br><br>
                <input type="text" name="zip" id="zip" placeholder="Zip Code/Código postal" required <?php showProgramInterestData($data['zip'] ?? null, $family_zip)?>>
                <br><br>

                <!-- 6. Cell Phone-->
                <label for="cell_phone">* Cell Phone / Teléfono móvil</label><br><br>
                <input type="text" name="cell_phone" id="cell_phone" placeholder="Cell Phone/Teléfono móvil" required <?php showProgramInterestData($data['cell_phone'] ?? null, $family_cell_phone)?>>
                <br><br>

                <!-- 7. Home Phone-->
                <label for="home_phone">* Home Phone / Teléfono residencial</label><br><br>
                <input type="text" name="home_phone" id="home_phone" placeholder="Home Phone/Teléfono residencial" required <?php showProgramInterestData($data['home_phone'] ?? null, $family_home_phone)?>>
                <br><br>

                <!-- 8. Email-->
                <label for="email">* Email / Correo electrónico</label><br><br>
                <input type="email" name="email" id="email" placeholder="Email/Correo electrónico" required <?php showProgramInterestData($data['email'] ?? null, $family_email)?>>
                <br><br>

                <!-- 7. Number of Children in Household-->
                <label for="child_num">* How Many Children in Household? / ¿Cuántos niños hay en el hogar?</label><br><br>
                <input type="number" oninput="getChildNum()" pattern="[0-9]*" name="child_num" id="child_num" placeholder="Number of Children/Número de niños" required <?php showProgramInterestData($data['child_num'] ?? null, $children_count)?>>
                <br><br>

                <!-- 8. Ages of Children in Household-->
                <label for="child_ages">* What ages? / ¿Cuántos años?</label><br><br>
                <input type="text" name="child_ages" id="child_ages" placeholder="Ages/Años" required <?php showProgramInterestData($data['child_ages'] ?? null, $children_ages)?>>
                <br><br>

                <!-- 9. Number of Adults in Household-->
                <label for="adult_num">* How Many Adult in Household? / ¿Cuántos adultos hay en el hogar?</label><br><br>
                <input type="number" name="adult_num" id="adult_num" required placeholder="Number of Adults/Número de adultos" <?php showProgramInterestData($data['adult_num'] ?? null, null)?>>
                <br><br>

                <h2>Programs of Interest / Programas de interés</h2>
                <br>
                <p>If you are interested in any programs listed below, please mark your choices \
                Si está interesado en alguno de los programas a continuación, por favor marque sus opciones
                </p><br>

                <!-- 10. Programs of Interest-->
                <input type="checkbox" id="brain_builders" name="programs[]" value="Brain Builders" <?php if ($data) echo showProgramInterestCheckbox($programData, "Brain Builders");?>>
                <label>  Brain Builders (Tutoring Program for grades K – 12)</label><br>
                <input type="checkbox" id="camp_junction" name="programs[]" value="Camp Junction" <?php showProgramInterestCheckbox($programData, "Camp Junction")?>>
                <label> Camp Junction (Camp Program for grades K – 5)</label><br>
                <input type="checkbox" id="sports_camp" name="programs[]" value="Stafford County Sheriff’s Office Sports Camp" <?php showProgramInterestCheckbox($programData, "Stafford County Sheriff’s Office Sports Camp")?>>
                <label> Stafford County Sheriff’s Office Sports Camp (grades K – 12)</label><br> 
                <input type="checkbox" id="steam" name="programs[]" value="STEAM" <?php showProgramInterestCheckbox($programData, "STEAM")?>>
                <label> STEAM  (Science, Technology, Engineering, Arts, Math) Camp (grades 6 – 12)</label><br>
                <input type="checkbox" id="ymca" name="programs[]" value="YMCA" <?php showProgramInterestCheckbox($programData, "YMCA")?>>
                <label> YMCA (Membership, Activities) (All Ages)</label><br>
                <input type="checkbox" id="tide" name="programs[]" value="Tide Me Over Bags" <?php showProgramInterestCheckbox($programData, "Tide Me Over Bags")?>>
                <label> Tide Me Over Bags (Shelf Stable Meal) / Produce </label><br> 
                <input type="checkbox" id="english_classes" name="programs[]" value="English Language Conversation Classes" <?php showProgramInterestCheckbox($programData, "English Language Conversation Classes")?>>
                <label> English Language Conversation Classes (Adults) </label><br><br>

                <h2>Topics of Interest / Temas de interés</h2>
                <br>
                <p>We want to know what topics you are interested in learning more about /
                Queremos saber qué temas le interesan
                </p><br>

                <!-- 11. Topics of Interest-->
                <input type="checkbox" id="legal_services" name="topics[]" value="Legal Services" <?php showProgramInterestCheckbox($topicData, "Legal Services")?>>
                <label> Legal Services</label><br> 
                <input type="checkbox" id="finance" name="topics[]" value="Finance" <?php showProgramInterestCheckbox($topicData, "Brain Finance")?>>
                <label> Finances</label><br>
                <input type="checkbox" id="tenant_rights" name="topics[]" value="Tenant Rights" <?php showProgramInterestCheckbox($topicData, "Tenant Rights")?>>
                <label> Tenant Rights</label><br>
                <input type="checkbox" id="health" name="topics[]" value="Health/Wellness/Nutrition" <?php showProgramInterestCheckbox($topicData, "Health/Wellness/Nutrition")?>>
                <label> Health/Wellness/Nutrition </label><br> 
                <input type="checkbox" id="continuing_education" name="topics[]" value="Continuing Education" <?php showProgramInterestCheckbox($topicData, "Continuing Education")?>>
                <label> Continuing Education  </label><br>
                <input type="checkbox" id="parenting" name="topics[]" value="Parenting" <?php showProgramInterestCheckbox($topicData, "Parenting")?>>
                <label> Parenting</label><br>
                <input type="checkbox" id="mental_health" name="topics[]" value="Mental Health" <?php showProgramInterestCheckbox($topicData, "Mental Health")?>>
                <label> Mental Health</label><br>
                <input type="checkbox" id="job_guidance" name="topics[]" value="Job/Career Guidance" <?php showProgramInterestCheckbox($topicData, "Job/Career Guidance")?>>
                <label> Job/Career Guidance </label><br> 
                <input type="checkbox" id="citizenship_classes" name="topics[]" value="Citizenship Classes" <?php showProgramInterestCheckbox($topicData, "Citizenship Classes")?>>
                <label> Citizenship Classes  </label><br><br>

                <label for="other_topics">Are there any other topics not listed you might be interested in?</label><br><br>

                <!-- Repurposed add child code from familyAccount.php -->
                <fieldset style="border: none;">
                    <div id="topic-container" ></div>
                    <!-- Check if form is being viewed, if so, display other topic interests in text box instead of showing javascript code below -->
                    <?php if (!$data) {
                        echo '<button type="button" onclick="addTopicForm()" style="width: 35.12rem;">+ Add Topic</button>';
                    } else {
                        $other_topics = implode(', ', getOtherTopicInterestData($topicData));
                        echo '<input type="text" disabled style="background-color: yellow; color: black;" value="' . $other_topics . '">';
                    }
                    ?>
                    
                </fieldset>
                <script>
                    let topicCount = 0;

                    function addTopicForm() {
                        topicCount++;
                        const container = document.getElementById('topic-container');
                        
                        const topicDiv = document.createElement('div');
                        topicDiv.className = 'topic-form';
                        topicDiv.id = `topic-form-${topicCount}`;
                        
                        topicDiv.innerHTML = `
                            <div style="display: flex; flex: 1;">
                            <div><input type="text" id="other_topic" name="topics[]" required placeholder="Topic/Temas" style="width: 25rem;"></div>
                            <div><button type="button" onclick="removeTopicForm(${topicCount})" style="height: 2.33rem;">Remove Topic</button></div>
                            </div>
                        `;
                        
                        container.appendChild(topicDiv);
                    }

                    function removeTopicForm(topicId) {
                        // Find the topic div to remove
                        const topicDiv = document.getElementById(`topic-form-${topicId}`);
                        if (topicDiv) {
                            topicDiv.remove();  // Remove the specific topic form
                            topicCount--;
                        }
                    }
                </script>
                <br><br>

                <h2>Availability</h2>
                <br>
                <p>What days/times work best for you? / ¿Qué días/horas funcionan mejor?</p>
                <br>
                <!-- 12. Availability Times-->
                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Monday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Monday][morning]' value='0'>
                    <input type="checkbox" id="monday_morning" name='days[Monday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Monday']['morning'] ?? null)?>>
                    <label for="monday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Monday][afternoon]' value='no'>
                    <input type="checkbox" id="monday_afternoon" name='days[Monday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Monday']['afternoon'] ?? null)?>>
                    <label for="monday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Monday][evening]' value='no'>
                    <input type="checkbox" id="monday_evening" name='days[Monday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Monday']['evening'] ?? null)?>>
                    <label for="monday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Monday][specific_time]' id="monday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Monday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br>

                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Tuesday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Tuesday][morning]' value='0'>
                    <input type="checkbox" id="tuesday_morning" name='days[Tuesday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Tuesday']['morning'] ?? null)?>>
                    <label for="tuesday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Tuesday][afternoon]' value='no'>
                    <input type="checkbox" id="tuesday_afternoon" name='days[Tuesday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Tuesday']['afternoon'] ?? null)?>>
                    <label for="tuesday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Tuesday][evening]' value='no'>
                    <input type="checkbox" id="tuesday_evening" name='days[Tuesday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Tuesday']['evening'] ?? null)?>>
                    <label for="tuesday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Tuesday][specific_time]' id="tuesday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Tuesday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br>

                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Wednesday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Wednesday][morning]' value='0'>
                    <input type="checkbox" id="wednesday_morning" name='days[Wednesday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Wednesday']['morning'] ?? null)?>>
                    <label for="wednesday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Wednesday][afternoon]' value='no'>
                    <input type="checkbox" id="wednesday_afternoon" name='days[Wednesday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Wednesday']['afternoon'] ?? null)?>>
                    <label for="wednesday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Wednesday][evening]' value='no'>
                    <input type="checkbox" id="wednesday_evening" name='days[Wednesday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Wednesday']['evening'] ?? null)?>>
                    <label for="wednesday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Wednesday][specific_time]' id="wednesday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Wednesday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br>

                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Thursday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Thursday][morning]' value='0'>
                    <input type="checkbox" id="thursday_morning" name='days[Thursday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Thursday']['morning'] ?? null)?>>
                    <label for="thursday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Thursday][afternoon]' value='no'>
                    <input type="checkbox" id="thursday_afternoon" name='days[Thursday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Thursday']['afternoon'] ?? null)?>>
                    <label for="thursday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Thursday][evening]' value='no'>
                    <input type="checkbox" id="thursday_evening" name='days[Thursday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Thursday']['evening'] ?? null)?>>
                    <label for="thursday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Thursday][specific_time]' id="thursday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Thursday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br><br><br>

                <p>Are there any other days or times that would work best for you? / ¿Hay otro horario que funcione mejor?</p><br><br>

                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Friday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Friday][morning]' value='0'>
                    <input type="checkbox" id="friday_morning" name='days[Friday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Friday']['morning'] ?? null)?>>
                    <label for="friday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Friday][afternoon]' value='no'>
                    <input type="checkbox" id="friday_afternoon" name='days[Friday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Friday']['afternoon'] ?? null)?>>
                    <label for="friday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Friday][evening]' value='no'>
                    <input type="checkbox" id="friday_evening" name='days[Friday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Friday']['evening'] ?? null)?>>
                    <label for="friday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Friday][specific_time]' id="friday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Friday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br>

                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Saturday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Saturday][morning]' value='0'>
                    <input type="checkbox" id="saturday_morning" name='days[Saturday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Saturday']['morning'] ?? null)?>>
                    <label for="saturday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Saturday][afternoon]' value='no'>
                    <input type="checkbox" id="saturday_afternoon" name='days[Saturday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Saturday']['afternoon'] ?? null)?>>
                    <label for="saturday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Saturday][evening]' value='no'>
                    <input type="checkbox" id="saturday_evening" name='days[Saturday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Saturday']['evening'] ?? null)?>>
                    <label for="saturday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Saturday][specific_time]' id="saturday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Saturday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br>

                <div class="availability-day-form">
                    <div class="day-label">
                    <label>Sunday:</label>
                    </div>
                    <div>
                    <input type='hidden'name='days[Sunday][morning]' value='0'>
                    <input type="checkbox" id="sunday_morning" name='days[Sunday][morning]' value='1' <?php showAvailabilityCheckbox($availabilityData['Sunday']['morning'] ?? null)?>>
                    <label for="sunday_morning"> Morning</label><br> 
                    </div>
                    <div>
                    <input type='hidden'name='days[Sunday][afternoon]' value='no'>
                    <input type="checkbox" id="sunday_afternoon" name='days[Sunday][afternoon]' value='1' <?php showAvailabilityCheckbox($availabilityData['Sunday']['afternoon'] ?? null)?>>
                    <label for="sunday_afternoon"> Afternoon</label><br>
                    </div>
                    <div>
                    <input type='hidden'name='days[Sunday][evening]' value='no'>
                    <input type="checkbox" id="sunday_evening" name='days[Sunday][evening]' value='1' <?php showAvailabilityCheckbox($availabilityData['Sunday']['evening'] ?? null)?>>
                    <label for="sunday_evening"> Evening</label><br>
                    </div>
                    <div>
                    <label>Only Specific Times:</label><br>
                    </div>
                    <div>
                    <input type="text" name='days[Sunday][specific_time]' id="sunday_times" placeholder="Time/Horas" <?php showProgramInterestData($availabilityData['Sunday']['specific_time'] ?? null, null)?>>
                    </div>
                </div>
                <br><br>

<!-- Submit and Cancel Buttons (displayed only when form is not submitted) -->
<?php if (!$programData): ?>
    <button type="submit" id="submit">Submit</button>

    <?php
        // Cancel button redirects appropriately based on the presence of an ID in the URL
        if (isset($_GET['id'])) {
            echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel</a>';
        } else {
            echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>';
        }
    ?>
<?php endif; ?>

<!-- Buttons for submitted form -->
<?php if ($programData): ?>
    <!-- Delete Button -->
    <form method="POST" action="database/dbProgramInterestForm.php">
        <input type="hidden" name="action" value="delete">
        <button type="submit" name="deleteForm" value="delete">Delete</button>
    </form>

    <!-- Return to Dashboard Button -->
        <?php if($accessLevel > 1):?> <!--If staff or admin, return back to index.php-->
                <a class="button cancel" href="index.php">Return to Dashboard</a>
            <?php else: ?> <!--If family, return back to family home page-->
                <a class="button cancel" href="familyAccountDashboard.php">Return to Dashboard</a>
        <?php endif ?>    
<?php endif; ?>

        <?php
            // if submission successful, create pop up notification and direct user back to fill form page
            // if fail, notify user on program interest form page
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
