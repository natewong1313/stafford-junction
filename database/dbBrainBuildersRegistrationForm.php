<?php

function get_data_by_family_id($id){
    $connection = connect();
    $query = "SELECT * FROM dbHolidayMealBagForm WHERE family_id = '" . $id . "';";
    $result = mysqli_query($connection, $query);

    if(mysqli_num_rows($result) < 0 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        return $row; //return the data as an associative array;
    }

}

function get_data_by_child_id($id){
    $connection = connect();
    $query = "SELECT * FROM dbHolidayMealBagForm WHERE child_id = '" . $id . "';";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) < 0 || $result == null){
        return null;
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row; //return the data as an associative array;
    }

}

function createBrainBuildersRegistrationForm($form) {
    $connection = connect();

    // Enable exception handling for MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $child_id = $form['child_id'];
    $child_first_name = $form["child_first_name"];
    $child_last_name = $form["child_last_name"];
    $gender = $form["gender"];
    $school_name = $form["school_name"];
    $grade = $form["grade"];
    $birthdate = $form["birthdate"];
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
    $needs_transportation = $form["needs_transportation"];
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
                child_id, child_first_name, child_last_name, gender, school_name, grade, birthdate,
                child_address, child_city, child_state, child_zip, child_medical_allergies, child_food_avoidances,
                parent1_name, parent1_phone, parent1_address, parent1_city, parent1_state, parent1_zip, 
                parent1_email, parent1_altPhone, parent2_name, parent2_phone, parent2_address, parent2_city, 
                parent2_state, parent2_zip, parent2_email, parent2_altPhone, emergency_name1, emergency_relationship1, 
                emergency_phone1, emergency_name2, emergency_relationship2, emergency_phone2, authorized_pu, 
                not_authorized_pu, primary_language, hispanic_latino_spanish, race, num_unemployed, num_retired, 
                num_unemployed_student, num_employed_fulltime, num_employed_parttime, num_employed_student, 
                income, other_programs, lunch, needs_transportation, participation, parent_initials, signature, 
                signature_date, waiver_child_name, waiver_dob, waiver_parent_name, waiver_provider_name, 
                waiver_provider_address, waiver_phone_and_fax, waiver_signature, waiver_date
            ) 
            VALUES (
                '$child_id', '$child_first_name', '$child_last_name', '$gender', '$school_name', '$grade', '$birthdate',
                '$child_address', '$child_city', '$child_state', '$child_zip', '$child_medical_allergies', '$child_food_avoidances',
                '$parent1_name', '$parent1_phone', '$parent1_address', '$parent1_city', '$parent1_state', '$parent1_zip', 
                '$parent1_email', '$parent1_altPhone', '$parent2_name', '$parent2_phone', '$parent2_address', '$parent2_city', 
                '$parent2_state', '$parent2_zip', '$parent2_email', '$parent2_altPhone', '$emergency_name1', '$emergency_relationship1', 
                '$emergency_phone1', '$emergency_name2', '$emergency_relationship2', '$emergency_phone2', '$authorized_pu', 
                '$not_authorized_pu', '$primary_language', '$hispanic_latino_spanish', '$race', '$num_unemployed', '$num_retired', 
                '$num_unemployed_student', '$num_employed_fulltime', '$num_employed_parttime', '$num_employed_student', 
                '$income', '$other_programs', '$lunch', '$needs_transportation', '$participation', '$parent_initials', '$signature', 
                '$signature_date', '$waiver_child_name', '$waiver_dob', '$waiver_parent_name', '$waiver_provider_name', 
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

    $query = "SELECT * FROM dbBrainBuildersRegistrationForm INNER JOIN dbChildren ON dbBrainBuildersRegistrationForm.child_id = dbChildren.id WHERE dbChildren.id = $childID";
    $result = mysqli_query($connection, $query);
    if (!$result->num_rows > 0) {
        mysqli_close($connection);
        return false;
    } else {
        mysqli_close($connection);
        return true;
    }
}
    
    
?>