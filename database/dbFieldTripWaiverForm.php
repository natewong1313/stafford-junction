<?php

require_once("dbinfo.php");
require_once("dbFamily.php");

function createFieldTripWaiverForm($form) {
    // Parse the child_name to get the child_id and full name
    $child_data = explode("_", $form['child_name']);
    $child_id = $child_data[0];
    $child_name = $child_data[1]; // Assuming this combines first and last names

    // Check if form is already complete for the child, if so then return
    if (isFieldTripWaiverFormComplete($child_id)) {
        return;
    }

    // Establish a connection to the database
    $connection = connect();

    // Extract form fields
    $email = $form["email"];
    $gender = $form["gender"];
    $birth_date = $form["birth_date"];
    $neighborhood = $form["neighborhood"];
    $school = $form["school"];
    $child_address = $form["child_address"];
    $child_city = $form["child_city"];
    $child_state = $form["child_state"];
    $child_zip = $form["child_zip"];

    // Emergency contact 1
    $contact_1 = $form["contact_1"];
    $emgcy_contact1_first_name = $form["emgcy_contact1_first_name"];
    $emgcy_contact1_last_name = $form["emgcy_contact1_last_name"];
    $emgcy_contact1_rship = $form["emgcy_contact1_rship"];
    $emgcy_contact1_phone = $form["emgcy_contact1_phone"];

    // Emergency contact 2
    $contact_2 = $form["contact_2"] ?? null; // Handle nullable field
    $emgcy_contact2_first_name = $form["emgcy_contact2_first_name"] ?? null;
    $emgcy_contact2_last_name = $form["emgcy_contact2_last_name"] ?? null;
    $emgcy_contact2_rship = $form["emgcy_contact2_rship"] ?? null;
    $emgcy_contact2_phone = $form["emgcy_contact2_phone"] ?? null;

    // Insurance info
    $medical_insurance_company = $form["medical_insurance_company"];
    $policy_number = $form["policy_number"];

    // Photo waiver
    $photo_waiver_signature = $form["photo_waiver_signature"];
    $photo_waiver_date = $form["photo_waiver_date"];

    // Prepare query
    $query = "
        INSERT INTO dbFieldTrpWaiverForm (
            child_id, child_name, gender, birth_date, neighborhood, school,
            child_address, child_city, child_state, child_zip, parent_email,
            contact_1, emgcy_contact1_first_name, emgcy_contact1_last_name, emgcy_contact1_rship, emgcy_contact1_phone,
            contact_2, emgcy_contact2_first_name, emgcy_contact2_last_name, emgcy_contact2_rship, emgcy_contact2_phone,
            medical_insurance_company, policy_number, photo_waiver_signature, photo_waiver_date
        )
        VALUES (
            '$child_id', '$child_name', '$gender', '$birth_date', '$neighborhood', '$school',
            '$child_address', '$child_city', '$child_state', '$child_zip', '$email',
            '$contact_1', '$emgcy_contact1_first_name', '$emgcy_contact1_last_name', '$emgcy_contact1_rship', '$emgcy_contact1_phone',
            " . ($contact_2 !== null ? "'$contact_2'" : "NULL") . ", 
            " . ($emgcy_contact2_first_name !== null ? "'$emgcy_contact2_first_name'" : "NULL") . ",
            " . ($emgcy_contact2_last_name !== null ? "'$emgcy_contact2_last_name'" : "NULL") . ",
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
        SELECT * FROM dbFieldTrpWaiverForm
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
    $query = "SELECT * FROM dbFieldTrpWaiverForm INNER JOIN dbChildren ON dbChildren.id =  dbFieldTrpWaiverForm.child_id WHERE family_id = ". $id . "';";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 0 || $res == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        return $row; //return the data as an associative array;
    }
}
?>

