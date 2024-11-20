<?php

/**
 * Inserts a new childcare waiver form into the database.
 *
 * @param array $form An associative array containing form data.
 * @return int|null The ID of the inserted form or null on failure.
 */
function createChildCareForm($form) {
    
    // Extract child data
    $child_data = explode("_", $form['child_name']); // Assuming 'child_name' contains ID and Name combined

    // Extract child ID
    $child_id = $child_data[0]; 

    // Check if form is already complete for this child
    if (isChildCareFormComplete($child_id)) {
        return; // Exit if form is already completed
    }

    $connection = connect();

    // Parse form data
    $child_id = $form["child_id"]; // Foreign key to the child in dbChildren
    $child_first_name = $form["child_first_name"];
    $child_last_name = $form["child_last_name"];
    $birth_date = $form["child_dob"]; // Match table column name
    $gender = $form["child_gender"];
    $child_address = $form["child_address"];
    $child_city = $form["child_city"];
    $child_state = $form["child_state"];
    $child_zip = $form["child_zip"];

    $medical_issues = $form["medical_issues"];
    if (empty($form["medical_issues"])) {
        $medical_issues = null;
    }

    $religious_foods = $form["religious_foods"];
    if (empty($form["religious_foods"])) {
    $religious_foods = null;
    }

    $parent_1 = $form["parent_1"]; // Reference to parent in another table
    $parent1_first_name = $form["parent1_first_name"];
    $parent1_last_name = $form["parent1_last_name"];
    $parent1_address = $form["parent1_address"];
    $parent1_city = $form["parent1_city"];
    $parent1_state = $form["parent1_state"];
    $parent1_zip_code = $form["parent1_zip"]; // Match table column name
    $parent1_email = $form["parent1_email"];
    $parent1_cell_phone = $form["parent1_cell_phone"];

    $parent1_home_phone = $form["parent1_home_phone"];

    if (empty($form["parent1_home_phone"])) {
        $parent1_home_phone = null;
    }

    $parent1_work_phone = $form["parent1_work_phone"];
    if (empty($form["parent1_work_phone"])) {
        $parent1_work_phone = null;
    }

    $parent_2 = $form["parent_2"];
    if (empty($form["parent_2"])) {
        $parent_2 = null;
    }

    $parent2_first_name = $form["parent2_first_name"];
    if (empty($form["parent2_first_name"])) {
        $parent2_first_name = null;
    }

    $parent2_last_name = $form["parent2_last_name"];
    if (empty($form["parent2_last_name"])) {
        $parent2_last_name = null;
    }

    $parent2_address = $form["parent2_address"];
    if (empty($form["parent2_address"])) {
        $parent2_address = null;
    }

    $parent2_city = $form["parent2_city"];
    if (empty($form["parent2_city"])) {
        $parent2_city = null;
    }

    $parent2_state = $form["parent2_state"];
    if (empty($form["parent2_state"])) {
        $parent2_state = null;
    }

    $parent2_zip_code = $form["parent2_zip"];
    if (empty($form["parent2_zip"])) {
        $parent2_zip_code = null;
    }

    $parent2_email = $form["parent2_email"];
    if (empty($form["parent2_email"])) {
        $parent2_email = null;
    }

    $parent2_cell_phone = $form["parent2_cell_phone"];
    if (empty($form["parent2_cell_phone"])) {
        $parent2_cell_phone = null;
    }

    $parent2_home_phone = $form["parent2_home_phone"];
    if (empty($form["parent2_home_phone"])) {
        $parent2_home_phone = null;
    }

    $parent2_work_phone = $form["parent2_work_phone"];
    if (empty($form["parent2_work_phone"])) {
        $parent2_work_phone = null;
    }


    $guardian_signature = $form["guardian_signature"];
    $signature_date = $form["signature_date"];
    
    // Insert query for childcare form
    $query = "
        INSERT INTO dbChildCareWaiverForm (
            child_id, child_first_name, child_last_name, birth_date, gender, child_address, child_city, child_state, child_zip, 
            medical_issues, religious_foods, parent_1, parent1_first_name, parent1_last_name, parent1_address, parent1_city, 
            parent1_state, parent1_zip_code, parent1_email, parent1_cell_phone, parent1_home_phone, parent1_work_phone, 
            parent_2, parent2_first_name, parent2_last_name, parent2_address, parent2_city, parent2_state, parent2_zip_code, 
            parent2_email, parent2_cell_phone, parent2_home_phone, parent2_work_phone, 
            parent_guardian_signature, signature_date
        ) VALUES (
            '$child_id', '$child_first_name', '$child_last_name', '$birth_date', '$gender', '$child_address', '$child_city', 
            '$child_state', '$child_zip', '$medical_issues', '$religious_foods', '$parent_1', '$parent1_first_name', 
            '$parent1_last_name', '$parent1_address', '$parent1_city', '$parent1_state', '$parent1_zip_code', '$parent1_email', 
            '$parent1_cell_phone', '$parent1_home_phone', '$parent1_work_phone', '$parent_2', '$parent2_first_name', 
            '$parent2_last_name', '$parent2_address', '$parent2_city', '$parent2_state', '$parent2_zip_code', '$parent2_email', 
            '$parent2_cell_phone', '$parent2_home_phone', '$parent2_work_phone', '$guardian_signature', '$signature_date'
        );
    ";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        // Handle errors if query fails
        error_log("Error inserting form data: " . mysqli_error($connection));
        return null;
    }

    mysqli_commit($connection);
    mysqli_close($connection);

    return mysqli_insert_id($connection);
}


/**
 * Checks if a childcare waiver form is already completed for a specific child.
 *
 * @param int $childID The unique ID of the child in the `dbChildren` table.
 * @return bool True if a form exists for the child, false otherwise.
 */
function isChildCareFormComplete($childID) {
    $connection = connect();

    // Query to check if the form exists for this child
    $query = "
        SELECT * FROM dbChildCareWaiverForm 
        INNER JOIN dbChildren ON dbChildCareWaiverForm.child_id = dbChildren.id 
        WHERE dbChildren.id = $childID
    ";

    $result = mysqli_query($connection, $query);

    if (!$result->num_rows > 0) {
        mysqli_close($connection);
        return false; // Form has not been completed
    } else {
        mysqli_close($connection);
        return true; // Form has been completed
    }
}

?>
