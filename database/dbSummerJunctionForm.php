<?php

require_once("dbinfo.php");

function isSummerJunctionFormComplete($childID) {
    $connection = connect();

    $query = "SELECT * FROM dbSummerJunctionRegistrationForm INNER JOIN dbChildren ON dbSummerJunctionRegistrationForm.child_id = dbChildren.id WHERE dbChildren.id = $childID";
    $result = mysqli_query($connection, $query);
    if (!$result->num_rows > 0) {
        mysqli_close($connection);
        return false;
    } else {
        mysqli_close($connection);
        return true;
    }
}

function createSummerJunctionForm($form) {
    $connection = connect();

    $child_data = explode("_", $form['child-name']);
    $child_id = $child_data[0];

    $steam = !empty($form['steam']) ? 1 : 0;
    $summer_camp = !empty($form['summer-camp']) ? 1 : 0;
    $child_first_name = $form['child-first-name'];
    $child_last_name = $form['child-last-name'];
    $birthdate = $form['birthdate'];
    $grade_completed = $form['grade'];
    $gender = $form['gender'];
    $shirt_size = $form['shirt-size'];
    $neighborhood = $form['neighborhood'];
    $child_address = $form['child-address'];
    $child_city = $form['child-city'];
    $child_state = $form['child-state'];
    $child_zip = $form['child-zip'];
    $child_medical_allergies = !empty($form['child-medical-allergies']) ? $form['child-medical-allergies'] : null;
    $child_food_avoidances = !empty($form['child-food-avoidances']) ? $form['child-food-avoidances'] : null;
    $parent1_first_name = $form['parent1-first-name'];
    $parent1_last_name = $form['parent1-last-name'];
    $parent1_address = $form['parent1-address'];
    $parent1_city = $form['parent1-city'];
    $parent1_state = $form['parent1-state'];
    $parent1_zip = $form['parent1-zip'];
    $parent1_email = $form['parent1-email'];
    $parent1_cell_phone = $form['parent1-cell-phone'];
    $parent1_home_phone = !empty($form['parent1-home-phone']) ? $form['parent1-home-phone'] : null;
    $parent1_work_phone = !empty($form['parent1-work-phone']) ? $form['parent1-work-phone'] : null;
    $parent2_first_name = !empty($form['parent2-first-name']) ? $form['parent2-first-name'] : null;
    $parent2_last_name = !empty($form['parent2-last-name']) ? $form['parent2-last-name'] : null;
    $parent2_address = !empty($form['parent2-address']) ? $form['parent2-address'] : null;
    $parent2_city = !empty($form['parent2-city']) ? $form['parent2-city'] : null;
    $parent2_state = !empty($form['parent2-state']) ? $form['parent2-state'] : null;
    $parent2_zip = !empty($form['parent2-zip']) ? $form['parent2-zip'] : null;
    $parent2_email = !empty($form['parent2-email']) ? $form['parent2-email'] : null;
    $parent2_cell_phone = !empty($form['parent2-cell-phone']) ? $form['parent2-cell-phone'] : null;
    $parent2_home_phone = !empty($form['parent2-home-phone']) ? $form['parent2-home-phone'] : null;
    $parent2_work_phone = !empty($form['parent2-work-phone']) ? $form['parent2-work-phone'] : null;
    $emergency_contact1_name = $form['emergency-name1'];
    $emergency_contact1_relationship = $form['emergency-relationship1'];
    $emergency_contact1_phone = $form['emergency-phone1'];
    $emergency_contact2_name = !empty($form['emergency-name2']) ? $form['emergency-name2'] : null;
    $emergency_contact2_relationship = !empty($form['emergency-relationship2']) ? $form['emergency-relationship2'] : null;
    $emergency_contact2_phone = !empty($form['emergency-phone2']) ? $form['emergency-phone2'] : null;
    $primary_language = $form['primary-language'];
    $hispanic_latino_spanish = $form['hispanic-latino-spanish'];
    $race = $form['race'];
    $num_unemployed = !empty($form['num-unemployed']) ? $form['num-unemployed'] : -1;
    $num_retired = !empty($form['num-retired']) ? $form['num-retired'] : -1;
    $num_unemployed_students = !empty($form['num-unemployed-student']) ? $form['num-unemployed-student'] : -1;
    $num_employed_fulltime = !empty($form['num-employed-fulltime']) ? $form['num-employed-fulltime'] : -1;
    $num_employed_parttime = !empty($form['num-employed-parttime']) ? $form['num-employed-parttime'] : -1;
    $num_employed_students = !empty($form['num-employed-student']) ? $form['num-employed-student'] : -1;
    $income = $form['income'];
    $other_programs = $form['other-programs'];
    $lunch = $form['lunch'];
    $insurance = $form['insurance'];
    $policy_num = $form['policy-num'];
    $signature = $form['signature'];
    $signature_date = $form['signature-date'];

    $query = "
        INSERT INTO dbSummerJunctionRegistrationForm (
            child_id, steam, summer_camp, child_first_name, child_last_name, birthdate, grade_completed, gender, 
            shirt_size, neighborhood, child_address, child_city, child_state, child_zip, child_medical_allergies,
            child_food_avoidances, parent1_first_name, parent1_last_name, parent1_address, parent1_city, 
            parent1_state, parent1_zip, parent1_email, parent1_cell_phone, parent1_home_phone, parent1_work_phone, 
            parent2_first_name, parent2_last_name, parent2_address, parent2_city, parent2_state, parent2_zip, 
            parent2_email, parent2_cell_phone, parent2_home_phone, parent2_work_phone, emergency_contact1_name,
            emergency_contact1_relationship, emergency_contact1_phone, emergency_contact2_name, emergency_contact2_relationship,
            emergency_contact2_phone, primary_language, hispanic_latino_spanish, race, num_unemployed, num_retired,
            num_unemployed_students, num_employed_fulltime, num_employed_parttime, num_employed_students, income,
            other_programs, lunch, insurance, policy_num, signature, signature_date
        ) VALUES (
            '$child_id', '$steam', '$summer_camp', '$child_first_name', '$child_last_name', '$birthdate', '$grade_completed', '$gender',
            '$shirt_size', '$neighborhood', '$child_address', '$child_city', '$child_state', '$child_zip', '$child_medical_allergies',
            '$child_food_avoidances', '$parent1_first_name', '$parent1_last_name', '$parent1_address', '$parent1_city',
            '$parent1_state', '$parent1_zip', '$parent1_email', '$parent1_cell_phone', '$parent1_home_phone', '$parent1_work_phone',
            '$parent2_first_name', '$parent2_last_name', '$parent2_address', '$parent2_city', '$parent2_state', '$parent2_zip',
            '$parent2_email', '$parent2_cell_phone', '$parent2_home_phone', '$parent2_work_phone', '$emergency_contact1_name',
            '$emergency_contact1_relationship', '$emergency_contact1_phone', '$emergency_contact2_name', '$emergency_contact2_relationship',
            '$emergency_contact2_phone', '$primary_language', '$hispanic_latino_spanish', '$race', '$num_unemployed', '$num_retired',
            '$num_unemployed_students', '$num_employed_fulltime', '$num_employed_parttime', '$num_employed_students', '$income',
            '$other_programs', '$lunch', '$insurance', '$policy_num', '$signature', '$signature_date'
        );
    ";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return null; // Query failed
    }  
    $id = mysqli_insert_id($connection); // Get the inserted record's ID
    mysqli_commit($connection); // Commit transaction
    mysqli_close($connection); // Close connection
    return $id;
}