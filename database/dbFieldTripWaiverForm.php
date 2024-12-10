<?php

require_once("dbinfo.php");
require_once("dbFamily.php");

function createFieldTripWaiverForm($form) {
    // Parse the child_name to get the child_id and full name
    $child_data = explode("_", $form['child_name']);
    $child_id = $child_data[0];
    $child_name = $child_data[1]; // Assuming this combines first and last names


    // Split contact names
    // $contact1_name = explode(" ", $form['emergency_contact_name_1']);
    // $contact2_name = explode(" ", $form['emergency_contact_name_2']);

    // Check if form is already complete for the child, if so then return
    if (isFieldTripWaiverFormComplete($child_id)) {
        return;
    }

    // Establish a connection to the database
    $connection = connect();

    // Extract form fields
    $email = $form["parent_email"];
    $gender = $form["child_gender"];
    $birth_date = $form["child_birthdate"];
    $neighborhood = $form["child_neighborhood"];
    $school = $form["child_school"];
    $child_address = $form["child_address"];
    $child_city = $form["child_city"];
    $child_state = $form["child_state"];
    $child_zip = $form["child_zip"];

    // Emergency contact 1
    $emgcy_contact_name_1 = $form['emergency_contact_name_1'];
    // $emgcy_contact1_last_name = $contact1_name[1];
    $emgcy_contact1_rship = $form["emergency_contact_relationship_1"];
    $emgcy_contact1_phone = $form["emergency_contact_phone_1"];

    // Emergency contact 2
    $emgcy_contact_name_2 = $form['emergency_contact_name_1'];
    // $emgcy_contact2_last_name = $contact2_name[1] ?? null;
    $emgcy_contact2_rship = $form["emergency_contact_relationship_2"];
    $emgcy_contact2_phone = $form["emergency_contact_phone_2"];

    // Insurance info
    $medical_insurance_company = $form["insurance_company"];
    $policy_number = $form["policy_number"];

    // Photo waiver
    $photo_waiver_signature = $form["parent_signature"];
    $photo_waiver_date = $form["signature_date"];

    // Prepare query
    $query = "
        INSERT INTO dbFieldTripWaiverForm (
            child_id, child_name, gender, birth_date, neighborhood, school,
            child_address, child_city, child_state, child_zip, parent_email, emgcy_contact_name_1,
            emgcy_contact1_rship, emgcy_contact1_phone, emgcy_contact_name_2, 
            emgcy_contact2_rship, emgcy_contact2_phone,
            medical_insurance_company, policy_number, photo_waiver_signature, photo_waiver_date
        )
        VALUES (
            '$child_id', '$child_name', '$gender', '$birth_date', '$neighborhood', '$school',
            '$child_address', '$child_city', '$child_state', '$child_zip', '$email', ' $emgcy_contact_name_1', '$emgcy_contact1_rship', '$emgcy_contact1_phone',
            " . ($emgcy_contact_name_2 !== null ? "'$emgcy_contact_name_2'" : "NULL") . ",
            " . ($emgcy_contact2_rship !== null ? "'$emgcy_contact2_rship'" : "NULL") . ",
            " . ($emgcy_contact2_phone !== null ? "'$emgcy_contact2_phone'" : "NULL") . ",
            '$medical_insurance_company', '$policy_number', '$photo_waiver_signature', '$photo_waiver_date'
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

    return $id; // Return the ID of the inserted form
}

// Function to check if a child has already completed the waiver form
function isFieldTripWaiverFormComplete($childID) {
    $connection = connect();

    $query = "
        SELECT * FROM dbFieldTripWaiverForm
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

//Function that retrieves the data from the field trip waiver for children on the family
function getFielTripWaiverData($id){
    $conn = connect();
    $query = "SELECT * FROM dbFieldTripWaiverForm INNER JOIN dbChildren ON dbChildren.id =  dbFieldTripWaiverForm.child_id WHERE dbChildren.family_id = $id";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 0 || $res == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        return $row; //return the data as an associative array;
    }
}

function getFieldTripWaiverSubmissions() {
    $conn = connect();
    $query = "SELECT * FROM dbFieldTripWaiverForm INNER JOIN dbChildren ON dbChildren.id =  dbFieldTripWaiverForm.child_id;";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}

function getFieldTripWaiverSubmissionsFromFamily($familyId) {
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
    $query = "SELECT * FROM dbFieldTripWaiverForm JOIN dbChildren ON dbFieldTripWaiverForm.child_id = dbChildren.id WHERE dbFieldTripWaiverForm.child_id IN ($joinedIds)";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}
?>

