<?php

function createFieldTripWaiverForm($form) {
    // Parse the child_name to get the child_id and full name
    $child_data = explode("_", $form['child_name']);
    $child_id = $child_data[0];
    
    // Check if form is already complete for child, if so then return
    if (isFieldTripWaiverFormComplete($child_id)) {
        return;
    }

    // Establish a connection to the database
    $connection = connect();
    
    // Extract form fields
    $email = $form["email"];
    $child_first_name = $child_data[1]; // Assuming this is how the name is split
    $child_last_name = $form["child_last_name"];
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
    $contact_2 = $form["contact_2"];
    $emgcy_contact2_first_name = $form["emgcy_contact2_first_name"];
    $emgcy_contact2_last_name = $form["emgcy_contact2_last_name"];
    $emgcy_contact2_rship = $form["emgcy_contact2_rship"];
    $emgcy_contact2_phone = $form["emgcy_contact2_phone"];
    
    // Insurance info
    $medical_insurance_company = $form["medical_insurance_company"];
    $policy_number = $form["policy_number"];
    
    // Photo waiver
    $photo_waiver_signature = $form["photo_waiver_signature"];
    $photo_waiver_date = $form["photo_waiver_date"];
    
    // Insert into the database
    $query = "
        INSERT INTO dbFieldTrpWaiverForm 
            (child_id, child_first_name, child_last_name, gender, birth_date, neighborhood, school, 
             child_address, child_city, child_state, child_zip, parent_email, 
             contact_1, emgcy_contact1_first_name, emgcy_contact1_last_name, emgcy_contact1_rship, emgcy_contact1_phone,
             contact_2, emgcy_contact2_first_name, emgcy_contact2_last_name, emgcy_contact2_rship, emgcy_contact2_phone,
             medical_insurance_company, policy_number, photo_waiver_signature, photo_waiver_date)
        VALUES 
            ('$child_id', '$child_first_name', '$child_last_name', '$gender', '$birth_date', '$neighborhood', '$school', 
             '$child_address', '$child_city', '$child_state', '$child_zip', '$email', 
             '$contact_1', '$emgcy_contact1_first_name', '$emgcy_contact1_last_name', '$emgcy_contact1_rship', '$emgcy_contact1_phone',
             '$contact_2', '$emgcy_contact2_first_name', '$emgcy_contact2_last_name', '$emgcy_contact2_rship', '$emgcy_contact2_phone',
             '$medical_insurance_company', '$policy_number', '$photo_waiver_signature', '$photo_waiver_date');
    ";
    
    // Execute the query
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        return null; // Query failed
    }
    
    $id = mysqli_insert_id($connection); // Get the inserted record's ID
    mysqli_commit($connection); // Commit transaction
    mysqli_close($connection); // Close the connection
    
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
    
    if (!$result->num_rows > 0) {
        mysqli_close($connection);
        return false; // No existing entry, form is not complete
    } else {
        mysqli_close($connection);
        return true; // Entry found, form is complete
    }
}
?>

