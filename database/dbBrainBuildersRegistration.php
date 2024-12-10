<?php

include_once('dbinfo.php');

function register($args, $childID){
    $conn = connect();
    $query = "SELECT * FROM dbBrainBuildersRegistrationForm where child_id = '$childID'";
    $res = mysqli_query($conn, $query);
    if($res == null || mysqli_num_rows($res) == 0){
        $nameOfChild = explode(" ", $args['name']);
        $fn = $nameOfChild[0];
        $ln = $nameOfChild[1];
        mysqli_query($conn, 'INSERT INTO dbBrainBuildersRegistrationForm (child_id, child_first_name, child_last_name, gender, school_name, grade, 
        birthdate, child_address, child_city, child_state, child_zip, child_medical_allergies, child_food_avoidances, parent1_name, parent1_phone,
        parent1_address, parent1_city, parent1_state, parent1_zip, parent1_email, parent1_altPhone, parent2_name, parent2_phone,
        parent2_address, parent2_city, parent2_state, parent2_zip, parent2_email, parent2_altPhone, emergency_name1, emergency_relationship1,
        emergency_phone1, emergency_name2, emergency_relationship2, emergency_phone2, authorized_pu, not_authorized_pu, primary_language, hispanic_latino_spanish,
        race, num_unemployed, num_retired, num_unemployed_student, num_employed_fulltime, num_employed_parttime, num_employed_student, income, other_programs,
        lunch, needs_transportation, participation, parent_initials, signature, signature_date, waiver_child_name, waiver_dob, waiver_parent_name, waiver_provider_name,
        waiver_provider_address, waiver_phone_and_fax, waiver_signature, waiver_date) VALUES (" ' . 
        $childID . '","' .
        $fn . '","' .
        $ln . '","' .
        $args['gender'] . '","' .
        $args['school-name'] . '","' .
        $args['grade'] . '","' .
        $args['birthdate'] . '","' .
        $args['child-address'] . '","' .
        $args['child-city'] . '","' .
        $args['child-state'] . '","' .
        $args['child-zip'] . '","' .
        $args['child-medical-allergies'] . '","' .
        $args['child-food-avoidances'] . '","' .
        $args['parent1-name'] . '","' .
        $args['parent1-phone'] . '","' .
        $args['parent1-address'] . '","' .
        $args['parent1-city'] . '","' .
        $args['parent1-state'] . '","' .
        $args['parent1-zip'] . '","' .
        $args['parent1-email'] . '","' .
        $args['parent1-altPhone'] . '","' .
        $args['parent2-name'] . '","' .
        $args['parent2-phone'] . '","' .
        $args['parent2-address'] . '","' .
        $args['parent2-city'] . '","' .
        $args['parent2-state'] . '","' .
        $args['parent2-zip'] . '","' .
        $args['parent2-email'] . '","' .
        $args['parent2-altPhone'] . '","' .
        $args['emergency-name1'] . '","' .
        $args['emergency-relationship1'] . '","' .
        $args['emergency-phone1'] . '","' .
        $args['emergency-name2'] . '","' .
        $args['emergency-relationship2'] . '","' .
        $args['emergency-phone2'] . '","' .
        $args['authorized-pu'] . '","' .
        $args['not-authorized-pu'] . '","' .
        $args['primary-language'] . '","' .
        $args['hispanic-latino-spanish'] . '","' .
        $args['race'] . '","' .
        $args['num-unemployed'] . '","' .
        $args['num-retired'] . '","' .
        $args['num-unemployed-student'] . '","' .
        $args['num-employed-fulltime'] . '","' .
        $args['num-employed-parttime'] . '","' .
        $args['num-employed-student'] . '","' .
        $args['income'] . '","' .
        $args['other-programs'] . '","' .
        $args['lunch'] . '","' .
        $args['needs-transportation'] . '","' .
        $args['participation'] . '","' .
        $args['parent-initials'] . '","' .
        $args['signature'] . '","' .
        $args['signature-date'] . '","' .
        $args['waiver-child-name'] . '","' .
        $args['waiver-dob'] . '","' .
        $args['waiver-parent-name'] . '","' .
        $args['waiver-provider-name'] . '","' .
        $args['waiver-provider-address'] . '","' .
        $args['waiver-phone-and-fax'] . '","' .
        $args['waiver-signature'] . '","' .
        $args['waiver-date'] . 
        '");'
    );
        mysqli_close($conn);
        return true;

    }
    mysqli_close($conn);
    return false;
}

function isBrainBuildersRegistrationComplete($childId){
    $conn = connect();
    $query = "SELECT * FROM dbBrainBuildersRegistrationForm where child_id = $childId";
    $res = mysqli_query($conn, $query);

    $complete = $res && mysqli_num_rows($res) > 0;
    mysqli_close($conn);
    return $complete;
}