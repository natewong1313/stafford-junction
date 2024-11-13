<?php

function createBackToSchoolForm($form) {
    $connection = connect();
    $child_data = explode("_", $form['name']);
    
    // Map form data directly to variables
    $child_id = $child_data[0];
    $child_name = $child_data[1];
    $email = $form["email"];
    $grade = $form["grade"];
    $school = $form["school"];
    $bag_pickup_method = $form["community"];
    $need_backpack = ($form["need_backpack"] === "need_backpack") ? 1 : 0; // Convert to 1 or 0

    // Prepare and execute the insert query
    $query = "
        INSERT INTO dbSchoolSuppliesForm (child_id, email, child_name, grade, school, bag_pickup_method, need_backpack)
        VALUES ('$child_id', '$email', '$child_name', '$grade', '$school', '$bag_pickup_method', '$need_backpack');
    ";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        return null;
    }

    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);

    return $id;
}

// Function to check if the Back-to-School form has already been completed for the child
function isBackToSchoolFormComplete($childID) {
    $connection = connect();
    $query = "SELECT * FROM dbSchoolSuppliesForm WHERE child_id = $childID";
    $result = mysqli_query($connection, $query);
    
    $complete = $result && mysqli_num_rows($result) > 0;
    mysqli_close($connection);
    return $complete;
}

?>

