<?php

/*
 * Function that adds args from the html form into the database
 */
function createBrainBuildersRegistrationForm($form) {
    $connection = connect();

    // Enable exception handling for MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $child_id = $form['child_id'];
    $child_first_name = $form["child_first_name"];
    $child_last_name = $form["child_last_name"];
    $child_email = $form["child_email"];
    $child_gender = $form["child_gender"];
    $child_school_name = $form["child_school_name"];
    $child_grade = $form["child_grade"];
    $child_dob = $form["child_dob"];
    $child_address = $form["child_address"];
    $child_city = $form["child_city"];
    $child_state = $form["child_state"];
    $child_zip = $form["child_zip"];
    $child_medical_allergies = $form["child_medical_allergies"];
    $child_food_avoidances = $form["child_food_avoidances"];
    $parent1_name = $form["parent1_name"];
    $parent1_phone = $form["parent1_phone"];
    $parent1_address = $form["parent1_address"];
    $parent1_city = $form["parent1_city"];
    $parent1_state = $form["parent1_state"];
    $parent1_zip = $form["parent1_zip"];
    $parent1_email = $form["parent1_email"];
    $parent1_altPhone = $form["parent1_altPhone"];
    $parent2_name = $form["parent2_name"];
    $parent2_phone = $form["parent2_phone"];
    $parent2_address = $form["parent2_address"];
    $parent2_city = $form["parent2_city"];
    $parent2_state = $form["parent2_state"];
    $parent2_zip = $form["parent2_zip"];
    $parent2_email = $form["parent2_email"];
    $parent2_altPhone = $form["parent2_altPhone"];
    $emergency_name1 = $form["emergency_name1"];
    $emergency_relationship1 = $form["emergency_relationship1"];
    $emergency_phone1 = $form["emergency_phone1"];
    $emergency_name2 = $form["emergency_name2"];
    $emergency_relationship2 = $form["emergency_relationship2"];
    $emergency_phone2 = $form["emergency_phone2"];
    $authorized_pu = $form["authorized_pu"];
    $not_authorized_pu = $form["not_authorized_pu"];
    $primary_language = $form["primary_language"];
    $hispanic_latino_spanish = $form["hispanic_latino_spanish"];
    $race = $form["race"];
    $num_unemployed = $form["num_unemployed"];
    $num_retired = $form["num_retired"];
    $num_unemployed_student = $form["num_unemployed_student"];
    $num_employed_fulltime = $form["num_employed_fulltime"];
    $num_employed_parttime = $form["num_employed_parttime"];
    $num_employed_student = $form["num_employed_student"];
    $income = $form["income"];
    $other_programs = $form["other_programs"];
    $lunch = $form["lunch"];
    $transportation = $form["transportation"];
    $participation = $form["participation"];
    $parent_initials = $form["parent_initials"];
    $signature = $form["signature"];
    $signature_date = $form["signature_date"];
    $waiver_child_name = $form["waiver_child_name"];
    $waiver_dob = $form["waiver_dob"];
    $waiver_parent_name = $form["waiver_parent_name"];
    $waiver_provider_name = $form["waiver_provider_name"];
    $waiver_provider_address = $form["waiver_provider_address"];
    $waiver_phone_and_fax = $form["waiver_phone_and_fax"];
    $waiver_signature = $form["waiver_signature"];
    $waiver_date = $form["waiver_date"];

    mysqli_begin_transaction($connection);
    $bbID = null;
    try {
        $query = "
            INSERT INTO dbBrainBuildersRegistrationForm (
                child_id, child_first_name, child_last_name, child_email, child_gender, child_school_name, 
                child_grade, child_dob, child_address, child_city, child_state, child_zip, child_medical_allergies, child_food_avoidances, 
                parent1_name, parent1_phone, parent1_address, parent1_city, parent1_state, parent1_zip, parent1_email, parent1_altPhone, 
                parent2_name, parent2_phone, parent2_address, parent2_city, parent2_state, parent2_zip, parent2_email, parent2_altPhone, 
                emergency_name1, emergency_relationship1, emergency_phone1, emergency_name2, emergency_relationship2, emergency_phone2, 
                authorized_pu, not_authorized_pu, primary_language, hispanic_latino_spanish, race, num_unemployed, num_retired, 
                num_unemployed_student, num_employed_fulltime, num_employed_parttime, num_employed_student, income, 
                other_programs, lunch, transportation, participation, parent_initials, signature, signature_date,
                waiver_child_name, waiver_dob, waiver_parent_name, waiver_provider_name, 
                waiver_provider_address, waiver_phone_and_fax, waiver_signature, waiver_date
            ) 
            VALUES (
                '$child_id', '$child_first_name', '$child_last_name', '$child_email', '$child_gender', '$child_school_name', 
                '$child_grade', '$child_dob', '$child_address', '$child_city', '$child_state', '$child_zip', '$child_medical_allergies', '$child_food_avoidances', 
                '$parent1_name', '$parent1_phone', '$parent1_address', '$parent1_city', '$parent1_state', '$parent1_zip', '$parent1_email', '$parent1_altPhone', 
                '$parent2_name', '$parent2_phone', '$parent2_address', '$parent2_city', '$parent2_state', '$parent2_zip', '$parent2_email', '$parent2_altPhone', 
                '$emergency_name1', '$emergency_relationship1', '$emergency_phone1', '$emergency_name2', '$emergency_relationship2', '$emergency_phone2', 
                '$authorized_pu', '$not_authorized_pu', '$primary_language', '$hispanic_latino_spanish', '$race', '$num_unemployed', '$num_retired', 
                '$num_unemployed_student', '$num_employed_fulltime', '$num_employed_parttime', '$num_employed_student', '$income', 
                '$other_programs', '$lunch', '$transportation', '$participation', '$parent_initials', '$signature', '$signature_date', 
                '$waiver_child_name', '$waiver_dob', '$waiver_parent_name', '$waiver_provider_name', 
                '$waiver_provider_address', '$waiver_phone_and_fax', '$waiver_signature', '$waiver_date'
            )
        ";
            
        $result = mysqli_query($connection, $query);
        if (!$result) {
            throw new Exception("Error in query: " . mysqli_error($connection));
        }

        $bbID = mysqli_insert_id($connection);

        mysqli_commit($connection);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        mysqli_rollback($connection);
        mysqli_close($connection);
        return null;
    }

    mysqli_close($connection);
    return $bbID;
}


function isBrainBuildersRegistrationFormComplete($childID) {
    $connection = connect();

    $query = "SELECT * FROM dbBrainBuildersRegistrationForm INNER JOIN dbChildren ON dbBrainBuildersRegistrationForm.child_id = dbChildren.id WHERE dbChildren.id = '" . $childID . "';";
    $result = mysqli_query($connection, $query);
    if (!$result->num_rows > 0) {
        mysqli_close($connection);
        return false;
    } else {
        mysqli_close($connection);
        return true;
    }
}

function isBBComplete($childID) {
    // Establish a connection to the database
    $connection = connect();  // Assuming 'connect()' is a function that connects to the database

    // Prepare the SQL query to avoid SQL injection
    $query = "SELECT * FROM dbBrainBuildersRegistrationForm 
              INNER JOIN dbChildren ON dbBrainBuildersRegistrationForm.child_id = dbChildren.id 
              WHERE dbChildren.id = ?"; // Use ? as a placeholder for childID

    // Prepare the statement
    if ($stmt = $connection->prepare($query)) {
        // Bind the integer $childID as a parameter
        $stmt->bind_param('i', $childID); // 'i' is for integer type

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // If the query returns any rows, that means the form is complete
        if ($result->num_rows > 0) {
            $stmt->close(); // Close the statement
            mysqli_close($connection); // Close the connection
            return true;
        } else {
            $stmt->close(); // Close the statement
            mysqli_close($connection); // Close the connection
            return false;
        }
    } else {
        // If the query preparation fails, handle the error
        echo "Error preparing the query: " . $connection->error;
        mysqli_close($connection); // Close the connection
        return false;
    }
}
    
    
?>