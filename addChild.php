<!--This file is has the front-end and back-end for adding new children to account after the account has already been made-->
<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
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
        $success = add_child($newChild, $userID); //success is a boolean; it will remain true so long as children as being added correctly to the database
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

                        <label for="child_gender_${childCount}">Child's Gender</label>
                        <select id="child_gender_${childCount}" name="children[${childCount}][gender]" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                        <label for="child_medical_notes_${childCount}">Medical Notes</label>
                        <input type="text" id="child_medical_notes_${childCount}" name="children[${childCount}][last-child_medical_notes_]" required placeholder="Allergies, medications, etc.">

                        <label for="child_additional_notes_${childCount}">Additional Notes</label>
                        <input type="text" id="child_additional_notes_${childCount}" name="children[${childCount}][child_additional_notes_-name]" required placeholder="Anything else we should know?">

                        <button type="button" onclick="removeChildForm(${childCount})">Remove Child</button>

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
            <?php
                if($success){
                    echo '<script>document.location = "familyAccountDashboard.php?addChildSuccess";</script>';
                }
            ?>
        </form>
    
        

    </body>
</html>

