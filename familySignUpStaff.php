<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

if(isset($_SESSION['_id'])){
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('include/input-validation.php');
    require_once('database/dbFamily.php');
    require_once('database/dbChildren.php');

    $children = null;
    // grab children data if any were added
    if (isset($_POST['children'])) {
        $children = $_POST['children'];
        // Sanitize children input
        foreach ($children as $child) {
            $child = sanitize($child);
        }
    }
    unset($_POST['children']); //need to unset, otherwise sanitize breaks 

    $args = sanitize($_POST, null);

    //creates family object
    $family = make_a_family($args);

    //inserts family into database
    $success = add_family($family);
    
    if($success){
        //need to retrieve the family we just inserted because we need the family id primary key via getID()
        $fam = retrieve_family($args);
        // Insert family languages
        insert_family_languages($args['languages'], $fam->getId());
        // Insert current asisatnce if any were added
        if (isset($args['assistance'])) {
            insert_family_assistance($args['assistance'], $fam->getId());
        }
        // If any children were added, loop through children and make child objects
        if ($children != null) {
            foreach($children as $child){
                $child_obj = make_a_child_from_sign_up($child); //construct child object from form data

                //insert child into dbChildren, passing in family id that which will be the foreign key for dbChildren
                add_child($child_obj, $fam->getID());
            }
        }
    }else {
        echo '<script>document.location = "index.php?failedAccountCreate";</script>';
    }

}

?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Create Family Account</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Create Family Account</h1>
        <main class="signup-form">
            <form class="signup-form" method="post">
                <h2>Family Account Registration Form</h2>
                <p>Please fill out each section of the following form if you would like to become a member of Stafford Junction</p>
                <p>An asterisk (*) indicates a required field.</p>

                <h3>Primary Parent / Guardian</h3>
                <fieldset>
                    <legend>Personal Information</legend>
                    <p>The following information will help us identify you within our system.</p>
                    <label for="first-name" required>* First Name</label>
                    <input type="text" id="first-name" name="first-name" required placeholder="Enter your first name">

                    <label for="last-name" required>* Last Name</label>
                    <input type="text" id="last-name" name="last-name" required placeholder="Enter your last name">

                    <label for="birthdate" required>* Date of Birth</label>
                    <input type="date" id="birthdate" name="birthdate" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">

                    <label for="neighborhood" required>* Neighborhood</label>
                    <input type="text" id="address" name="neighborhood" required placeholder="Enter your neighborhood">

                    <label for="address" required>* Street Address</label>
                    <input type="text" id="address" name="address" required placeholder="Enter your street address">

                    <label for="city" required>* City</label>
                    <input type="text" id="city" name="city" required placeholder="Enter your city">

                    <label for="state" required>* State</label>
                    <select id="state" name="state" required>
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
                    </select>

                    <label for="zip" required>* Zip Code</label>
                    <input type="text" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code">

                    <label for="isHispanic">* Hispanic, Latino, or Spanish Origin</label>
                    <select id="isHispanic" name="isHispanic" required>
                        <option value="" disabled selected>Select Yes or No</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                

                    <label for="race" required>* Race</label>
                    <select id="race" name="race" required>
                        <option value="" disabled selected>Select Race</option>
                        <option value="Caucasian">Caucasian</option>
                        <option value="Black/African American">Black/African American</option>
                        <option value="Native Indian/Alaska Native">Native Indian/Alaska Native</option>
                        <option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander</option>
                        <option value="Asian">Asian</option>
                        <option value="Multiracial">Multiracial</option>
                        <option value="Other">Other</option>
                    </select>
                </fieldset>
                    

                <fieldset>
                    <legend>Contact Information</legend>
                    <p>The following information will help us determine the best way to contact you.</p>
                    <label for="email" required>* E-mail</label>
                    <p>This will also serve as your username when logging in.</p>
                    <input type="email" id="email" name="email" required placeholder="Enter your e-mail address">

                    <label for="phone" required>* Primary Phone Number</label>
                    <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555">

                    <label required>* Primary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="phone-type-cellphone" name="phone-type" value="cellphone" required><label for="phone-type-cellphone">Cell</label>
                        <input type="radio" id="phone-type-home" name="phone-type" value="home" required><label for="phone-type-home">Home</label>
                        <input type="radio" id="phone-type-work" name="phone-type" value="work" required><label for="phone-type-work">Work</label>
                    </div>

                    <label for="secondary-phone">* Secondary Phone Number</label>
                    <input type="tel" id="secondary-phone" name="secondary-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555">

                    <label>* Secondary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="secondary-phone-type-cellphone" name="secondary-phone-type" value="cellphone" required><label for="secondary-phone-type-cellphone">Cell</label>
                        <input type="radio" id="secondary-phone-type-home" name="secondary-phone-type" value="home" required><label for="secondary-phone-type-home">Home</label>
                        <input type="radio" id="secondary-phone-type-work" name="secondary-phone-type" value="work" required><label for="secondary-phone-type-work">Work</label>
                    </div>

                </fieldset>

                <h3>Secondary Parent / Guardian</h3>
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="first-name2">First Name</label>
                    <input type="text" id="first-name2" name="first-name2" placeholder="Enter your first name">

                    <label for="last-name2">Last Name</label>
                    <input type="text" id="last-name2" name="last-name2" placeholder="Enter your last name">

                    <label for="birthdate2">Date of Birth</label>
                    <input type="date" id="birthdate2" name="birthdate2" placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">

                    <label for="neighborhood2">Neighborhood</label>
                    <input type="text" id="neighborhood2" name="neighborhood2" placeholder="Enter your neighborhood">

                    <label for="address2">Street Address</label>
                    <input type="text" id="address2" name="address2" placeholder="Enter your street address">

                    <label for="city2">City</label>
                    <input type="text" id="city2" name="city2" placeholder="Enter your city">

                    <label for="state2">State</label>
                    <select id="state2" name="state2">
                        <option value="--">--</option>
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
                    </select>

                    <label for="zip2">Zip Code</label>
                    <input type="text" id="zip2" name="zip2" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code">

                    <label for="isHispanic2">Hispanic, Latino, or Spanish Origin</label>
                    <select id="isHispanic2" name="isHispanic2">
                        <!--<option value="" disabled selected></option>-->
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                  

                    <label for="race2" required>Race</label>
                    <select id="race2" name="race2">
                        <option value="" disabled selected>Select Race</option>
                        <option value="Caucasian">Caucasian</option>
                        <option value="Black/African American">Black/African American</option>
                        <option value="Native Indian/Alaska Native">Native Indian/Alaska Native</option>
                        <option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander</option>
                        <option value="Asian">Asian</option>
                        <option value="Multiracial">Multiracial</option>
                        <option value="Other">Other</option>
                    </select>

                </fieldset>
                <fieldset>
                    <legend>Contact Information</legend>
                    <label for="emai2l">E-mail</label>
                    <input type="email" id="email2" name="email2" placeholder="Enter your e-mail address">

                    <label for="phone2">Primary Phone Number</label>
                    <input type="tel" id="phone2" name="phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

                    <label>Primary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="phone-type-cellphone2" name="phone-type2" value="cellphone"><label for="phone-type-cellphone2">Cell</label>
                        <input type="radio" id="phone-type-home2" name="phone-type2" value="home"><label for="phone-type-home2">Home</label>
                        <input type="radio" id="phone-type-work2" name="phone-type2" value="work"><label for="phone-type-work2">Work</label>
                    </div>

                    <label for="secondary-phone2">Secondary Phone Number</label>
                    <input type="tel" id="secondary-phone2" name="secondary-phone2" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

                    <label>Secondary Phone Type</label>
                    <div class="radio-group">
                        <input type="radio" id="secondary-phone-type-cellphone2" name="secondary-phone-type2" value="cellphone"><label for="secondary-phone-type-cellphone2">Cell</label>
                        <input type="radio" id="secondary-phone-type-home2" name="secondary-phone-type2" value="home"><label for="secondary-phone-type-home2">Home</label>
                        <input type="radio" id="secondary-phone-type-work2" name="secondary-phone-type2" value="work"><label for="secondary-phone-type-work2">Work</label>
                    </div>
                    
                </fieldset>

                <h3>Children</h3>
                <fieldset>
                    <p>Add child details below. Click "+ Add Child" to add more children.</p>
                    <div id="children-container"></div>
                    <button type="button" onclick="addChildForm()">+ Add Child</button>
                </fieldset>

                <script>
                    let childCount = 0;
                    const children = [];

                    function addChildForm() {
                        childCount++;
                        const container = document.getElementById('children-container');
                        
                        const childDiv = document.createElement('div');
                        childDiv.className = 'child-form';
                        childDiv.id = `child-form-${childCount}`;
                        
                        childDiv.innerHTML = `
                            <h4>Child ${children.length + 1}</h4>

                            <label for="child_name_${childCount}">Child's First Name</label>
                            <input type="text" id="child_name_${childCount}" name="children[${childCount}][first-name]" required placeholder="Enter child's first name">

                            <label for="child_last_name_${childCount}">Child's Last Name</label>
                            <input type="text" id="child_last_name_${childCount}" name="children[${childCount}][last-name]" required placeholder="Enter child's last name">

                            <label for="child_birthdate_${childCount}">Child's Date of Birth</label>
                            <input type="date" id="child_birthdate_${childCount}" name="children[${childCount}][birthdate]" required>

                            <label for="neighborhood" required>Child's Neighborhood</label>
                            <input type="text" id="child_neighborhood_${childCount}" name="children[${childCount}][neighborhood]" required placeholder="Enter child's neighborhood">

                            <label for="address" required>Child's Street Address</label>
                            <input type="text" id="child_address_${childCount}" name="children[${childCount}][address]" required placeholder="Enter child's street address">

                            <label for="city" required>Child's City</label>
                            <input type="text" id="child_city_${childCount}" name="children[${childCount}][city]" required placeholder="Enter child's school">

                            <label for="state2">State</label>
                            <select id="child_state_${childCount}" name="children[${childCount}][state]">
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
                            </select>

                            <label for="zip" required>Child's Zip Code</label>
                            <input type="text" id="child_zip_${childCount}" pattern="[0-9]{5}" name="children[${childCount}][zip]" required placeholder="Enter child's zip">

                            <label for="child_gender_${childCount}">Child's Gender</label>
                            <select id="child_gender_${childCount}" name="children[${childCount}][gender]" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>

                            <label for="school" required>Child's School</label>
                            <input type="text" id="child_school_${childCount}" name="children[${childCount}][school]" required placeholder="Enter child's school">

                            <label for="grade" required>Child's Grade</label>
                            <select id="child_grade_${childCount}" name="children[${childCount}][grade]" required>
                                <option value="Kindergarten">Kindergarten</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="Graduated">Graduated</option>
                            </select>

                            <label for="child_hispanic_${childCount}">Hispanic, Latino, or Spanish Origin</label>
                            <select id="child_hispanic_${childCount}" name="children[${childCount}][is_hispanic]" required>
                                <option value="" disabled selected>Select Yes or No</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>

                            <label for="child_race_${childCount}" required>Race</label>
                            <select id="child_race_${childCount}" name="children[${childCount}][race]" required>
                                <option value="" disabled selected>Select Race</option>
                                <option value="Caucasian">Caucasian</option>
                                <option value="Black/African American">Black/African American</option>
                                <option value="Native Indian/Alaska Native">Native Indian/Alaska Native</option>
                                <option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander</option>
                                <option value="Asian">Asian</option>
                                <option value="Multiracial">Multiracial</option>
                                <option value="Other">Other</option>
                            </select>

                            <label for="child_medical_notes_${childCount}">Medical Notes</label>
                            <input type="text" id="child_medical_notes_${childCount}" name="children[${childCount}][last-child_medical_notes_]" required placeholder="Allergies, medications, etc.">

                            <label for="child_additional_notes_${childCount}">Additional Notes</label>
                            <input type="text" id="child_additional_notes_${childCount}" name="children[${childCount}][child_additional_notes_-name]" required placeholder="Anything else we should know?">

                            <button type="button" style="background-color: red" onclick="removeChildForm(${childCount})">Remove Child</button>

                            <hr>
                        `;
                        
                        container.appendChild(childDiv);
                        children.push(childDiv);
                        renumberChildren();
                    }

                    function removeChildForm(childId) {
                        // Find the child div to remove
                        const childDiv = document.getElementById(`child-form-${childId}`);
                        if (childDiv) {
                            childDiv.remove();  // Remove the specific child form

                            // Remove the corresponding child element from the array
                            const index = children.findIndex(child => child.id === `child-form-${childId}`);
                            if (index > -1) {
                                children.splice(index, 1);
                            }
                            
                            // Renumber the children after removal
                            renumberChildren();
                        }
                    }

                    function renumberChildren() {
                        // Iterate over each child form and update the displayed child number
                        children.forEach((child, index) => {
                            const childHeader = child.querySelector('h4');
                            childHeader.textContent = `Child ${index + 1}`;
                        });
                    }
                </script>

                <h3>Emergency Contact</h3>
                <fieldset>
                    <p>Please provide us with someone to contact on your behalf in case of an emergency.</p>
                    <label for="econtact-first-name" required>* Contact First Name</label>
                    <input type="text" id="econtact-first-name" name="econtact-first-name" required placeholder="Enter emergency contact first name">

                    <label for="econtact-last-name" required>* Contact Last Name</label>
                    <input type="text" id="econtact-last-name" name="econtact-last-name" required placeholder="Enter emergency contact last name">

                    <label for="econtact-phone" required>* Contact Phone Number</label>
                    <input type="tel" id="econtact-phone" name="econtact-phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Enter emergency contact phone number. Ex. (555) 555-5555">

                    <label for="econtact-name" required> Contact Relation to You</label>
                    <input type="text" id="econtact-relation" name="econtact-relation" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">
                </fieldset>

                <h3>Household Information</h3>
                <fieldset>
                <label for="income" required>* Estimated Household Income</label>
                    <select id="income" name="income" required>
                        <option value="Under $15,0000">Under 20,000</option>
                        <option value="$15,000 - $24,999">20,000 - 40,000</option>
                        <option value="$25,000 - $34,999">40,001 - 60,000</option>
                        <option value="$35,000 - $49,999">60,001 - 80,000</option>
                        <option value="$100,000 and above">Over 80,000</option>
                    </select>
                    <br><br>

                    <!-- Household Languages -->
                    <fieldset style="border: none;">
                        <label for="languages" required>* Languages Spoken in Household</label>
                        <input type="text" id="languages[]" name="languages[]" required placeholder="Enter Language">
                        <div id="language-container" ></div>
                        <button type="button" onclick="addLanguageForm()">+ Add Language</button>
                    </fieldset>
                    <script>
                        let languageCount = 0;

                        function addLanguageForm() {
                            languageCount++;
                            const container = document.getElementById('language-container');
                            
                            const languageDiv = document.createElement('div');
                            languageDiv.className = 'language-form';
                            languageDiv.id = `language-form-${languageCount}`;
                            
                            languageDiv.innerHTML = `
                                <div style="display: flex; flex: 1;">
                                <div><input type="text" id="other_language" name="languages[]" required placeholder="Enter Language" style="width: 57.8rem;"></div>
                                <div><button type="button" onclick="removeLanguageForm(${languageCount})" style="height: 2.55rem;">Remove</button></div>
                                </div>
                            `;
                            
                            container.appendChild(languageDiv);
                        }

                        function removeLanguageForm(languageId) {
                            // Find the topic div to remove
                            const languageDiv = document.getElementById(`language-form-${languageId}`);
                            if (languageDiv) {
                                languageDiv.remove();  // Remove the specific topic form
                                languageCount--;
                            }
                        }
                    </script>
                    <br>

                    <!-- Current Assistance -->
                    <fieldset style="border: none;">
                        <label for="languages" required>Do You Currently Receive Any Assistance? (WIC, SNAP, SSI, SSD, etc.)<label>
                        <div id="assistance-container" ></div>
                        <button type="button" onclick="addAssistanceForm()">+ Add Assistance</button>
                    </fieldset>
                    <script>
                        let assistanceCount = 0;

                        function addAssistanceForm() {
                            assistanceCount++;
                            const container = document.getElementById('assistance-container');
                            
                            const assistanceDiv = document.createElement('div');
                            assistanceDiv.className = 'assistance-form';
                            assistanceDiv.id = `assistance-form-${assistanceCount}`;
                            
                            assistanceDiv.innerHTML = `
                                <div style="display: flex; flex: 1;">
                                <div><input type="text" id="other_assistance" name="assistance[]" required placeholder="Enter Other Assistance" style="width: 57.8rem;"></div>
                                <div><button type="button" onclick="removeAssistanceForm(${assistanceCount})" style="height: 2.55rem;">Remove</button></div>
                                </div>
                            `;
                            
                            container.appendChild(assistanceDiv);
                        }

                        function removeAssistanceForm(assistanceId) {
                            // Find the assistance div to remove
                            const assistanceDiv = document.getElementById(`assistance-form-${assistanceId}`);
                            if (assistanceDiv) {
                                assistanceDiv.remove();  // Remove the specific topic form
                                assistanceCount--;
                            }
                        }
                    </script>
                </fieldset>
                <br><br>

                <h3>Login Credentials</h3>
                <fieldset>
                    <p>You will use the following information to log in to the system.</p>

                    <p><b>Your username is the primary email address entered above.</b></p>

                    <label for="password" required>* Password</label>
                    <p style="margin-bottom: 0;">Password must be eight or more characters in length and include least one special character (e.g., ?, !, @, #, $, &, %)</p>
                    <input type="password" id="password" name="password" pattern="^(?=.*[^a-zA-Z0-9].*).{8,}$" title="Password must be eight or more characters in length and include least one special character (e.g., ?, !, @, #, $, &, %)" placeholder="Enter a strong password" required>

                    <label for="password-reenter" required>* Re-enter Password</label>
                    <input type="password" id="password-reenter" name="password-reenter" placeholder="Re-enter password" required>
                    <p id="password-match-error" class="error hidden">Passwords do not match!</p>

                    <label for="question" required>* Enter Security Question</label>
                    <input type="text" id="question" name="question" placeholder="Security Question" required>

                    <label for="answer" required>* Enter Security Answer</label>
                    <input type="text" id="answer" name="answer" placeholder="Security Answer" required>
                </fieldset>

                <input type="submit" name="registration-form" value="Create Account">
                <?php

                //if registration successful, create pop up notification and direct user back to login
                if($success){
                    echo '<script>document.location = "index.php?familyRegisterSuccess";</script>';
                }
                ?>
            </form>
            <a class="button cancel" href="index.php" style="margin-top: .5rem">Cancel</a>
        </main>
    </body>
</html>

