<?php

require_once("dbinfo.php");
require_once("dbFamily.php");

/**
 * Inserts a new childcare waiver form into the database.
 *
 * @param array $form An associative array containing form data.
 * @return int|null The ID of the inserted form or null on failure.
 */
function createChildCareForm($form) {
     // Establish a connection to the database
     $connection = connect();

     $child_data = explode("_", $form['name']);
    
     // Map form data directly to variables
     $child_id = $child_data[0];
     $child_name = $child_data[1];
    
    // Check if form is already complete for the child, if so then return
    if (isChildCareWaiverFormComplete($child_id)) {
        return;
    }

    // Extract form fields
    //$child_first_name = $form["child_first_name"];
    //$child_last_name = $form["child_last_name"];
    $birth_date = $form["child_dob"]; // Match table column name
    $gender = $form["child_gender"];
    $child_address = $form["child_address"];
    $child_city = $form["child_city"];
    $child_state = $form["child_state"];
    $child_zip = $form["child_zip"];
    $medical_issues = $form["medical_issues"];
    $religious_foods = $form["religious_foods"];

    // parent 1 information
    $parent1_first_name = $form["parent1_first_name"];
    $parent1_last_name = $form["parent1_last_name"];
    $parent1_address = $form["parent1_address"];
    $parent1_city = $form["parent1_city"];
    $parent1_state = $form["parent1_state"];
    $parent1_zip_code = $form["parent1_zip"]; // Match table column name
    $parent1_email = $form["parent1_email"];
    $parent1_cell_phone = $form["parent1_cell_phone"];
    $parent1_home_phone = $form["parent1_home_phone"];
    $parent1_work_phone = $form["parent1_work_phone"];

    // parent 2 information
    $parent2_first_name = $form["parent2_first_name"];
    $parent2_last_name = $form["parent2_last_name"];
    $parent2_address = $form["parent2_address"];
    $parent2_city = $form["parent2_city"];
    $parent2_state = $form["parent2_state"];
    $parent2_zip_code = $form["parent2_zip"];
    $parent2_email = $form["parent2_email"];
    $parent2_cell_phone = $form["parent2_cell_phone"];
    $parent2_home_phone = $form["parent2_home_phone"];
    $parent2_work_phone = $form["parent2_work_phone"];
   
    // signature and date
    $guardian_signature = $form["guardian_signature"];
    $signature_date = $form["signature_date"];
    
    // Insert query for childcare form
    $query = "
        INSERT INTO dbChildCareWaiverForm (
            child_id, child_name, birth_date, gender, child_address, child_city, child_state, child_zip, 
            parent1_first_name, parent1_last_name, parent1_address, parent1_city, 
            parent1_state, parent1_zip_code, parent1_email, parent1_cell_phone, parent1_home_phone, parent1_work_phone, 
            parent2_first_name, parent2_last_name, parent2_address, parent2_city, parent2_state, parent2_zip_code, 
            parent2_email, parent2_cell_phone, parent2_home_phone, parent2_work_phone, 
            parent_guardian_signature, signature_date
        ) VALUES (
            '$child_id', '$child_name', '$birth_date', '$gender', '$child_address', '$child_city', 
            '$child_state', '$child_zip', '$parent1_first_name', 
            '$parent1_last_name', '$parent1_address', '$parent1_city', '$parent1_state', '$parent1_zip_code', '$parent1_email', 
            '$parent1_cell_phone', '$parent1_home_phone', '$parent1_work_phone', '$parent2_first_name', 
            '$parent2_last_name', '$parent2_address', '$parent2_city', '$parent2_state', '$parent2_zip_code', '$parent2_email', 
            '$parent2_cell_phone', '$parent2_home_phone', '$parent2_work_phone', '$guardian_signature', '$signature_date'
        );
    ";
    


    // Execute query
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return null; // Query failed
    }

    $id = mysqli_insert_id($connection); // Get the inserted record's ID
    mysqli_commit($connection); // Commit transaction
    mysqli_close($connection); // Close connection
    
    // Establish a connection to the database
    $connection = connect();

    // Update query for dbChildren
    $query = "
        UPDATE dbChildren
        SET medical_notes = '$medical_issues'
        WHERE id = '$child_id';
    ";  

    // Execute query
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return null; // Query failed
    }

    $id = mysqli_insert_id($connection); // Get the inserted record's ID
    mysqli_commit($connection); // Commit transaction
    mysqli_close($connection); // Close connection
    
    // Establish a connection to the database
    $connection = connect();

    $query = "
        INSERT INTO dbUnallowedFoods (id, name)
        VALUES ('$child_id', '$religious_foods');
    ";

    // Execute query
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return null; // Query failed
    }

    $id = mysqli_insert_id($connection); // Get the inserted record's ID
    mysqli_commit($connection); // Commit transaction
    mysqli_close($connection); // Close connection

    return $id; // Return the ID of the inserted form
}







/**
 * Checks if a childcare waiver form is already completed for a specific child.
 *
 * @param int $childID The unique ID of the child in the `dbChildren` table.
 * @return bool True if a form exists for the child, false otherwise.
 */
// Function to check if a child has already completed the waiver form
function isChildCareWaiverFormComplete($childID) {
    $connection = connect();

    $query = "
        SELECT * FROM dbChildCareWaiverForm
        WHERE child_id = $childID
    ";

    $result = mysqli_query($connection, $query);

    if (!$result || $result->num_rows === 0) {
        mysqli_close($connection);
        return false; // No existing entry, form is not complete
    } else {
        mysqli_close($connection);
        return true; // Entry found, form is complete
    }
}

//Function that retrieves the data from the child care waiver for children on the family
function getChildCareWaiverData($id){
    $conn = connect();
    $query = "SELECT * FROM dbChildCareWaiverForm INNER JOIN dbChildren ON dbChildren.id =  dbChildCareWaiverForm.child_id WHERE dbChildren.family_id = $id";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 0 || $res == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        return $row; //return the data as an associative array;
    }
}

function getChildCareWaiverSubmissions() {
    $conn = connect();
    $query = "SELECT * FROM dbChildCareWaiverForm;";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}

function getChildCareWaiverSubmissionsFromFamily($familyId) {
    require_once("dbChildren.php");
    $children = retrieve_children_by_family_id($familyId);
    if (!$children){
        return [];
    }
    $childrenIds = array_map(function($child) {
        return $child->getId();
    }, $children);
    $joinedIds = join(",",$childrenIds);
    $conn = connect();
    $query = "SELECT * FROM dbChildCareWaiverForm JOIN dbChildren ON dbChildCareWaiverForm.child_id = dbChildren.id WHERE dbChildCareWaiverForm.child_id IN ($joinedIds)";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}

?>
