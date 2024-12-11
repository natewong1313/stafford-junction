<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
    $success = false;
}else {
    header("Location: login.php");
}


if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once("database/dbFamily.php");
    require_once("database/dbChildren.php");
    
    //retreive all the children in POST variable
    $children = $_POST['children'];

    //cycle through each child, creating a new child object and adding that child to the database
    foreach($children as $child){
        $newChild = make_a_child_from_sign_up($child);
        $success = add_child($newChild, $userID); //success is a boolean; it will remain true so long as children are being added correctly to the database
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Add Child</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Child</h1>


        <form id="formatted_form" action="" method="POST">
            <fieldset>
                <p>Add child details below. Click "+ Add Child" to add more children.</p>
                <div id="children-container"></div>
                <button type="button" style="background-color: #006400;" onclick="addChildForm()">+ Add Child</button>
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
                            <input type="text" id="child_city_${childCount}" name="children[${childCount}][city]" required placeholder="Enter child's city">

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

                        <button type="button" style="background-color: red"; onclick="removeChildForm(${childCount})">Remove Child</button>

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

            <button style="margin-top: 10px" type="input" name="add">Submit</button>
            <a class="button cancel button_style" href="familyAccountDashboard.php" style="margin-top: 3rem;">Return to Dashboard</a>
            <?php
                if($success){
                    echo '<script>document.location = "familyAccountDashboard.php?addChildSuccess";</script>';
                }
            ?>
        </form>
    
        

    </body>
</html>

