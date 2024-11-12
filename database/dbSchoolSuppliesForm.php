<?php

function createBackToSchoolForm($form) {
    $connection = connect();
    
    // Map form data directly to variables
    $email = $form["email"];
    $child_name = $form["name"];
    $grade = $form["grade"];
    $school = $form["school"];
    $bag_pickup_method = $form["community"];
    $need_backpack = ($form["need_backpack"] === "need_backpack") ? 1 : 0; // Convert to 1 or 0

    // Prepare and execute the insert query
    $query = "
        INSERT INTO dbSchoolSuppliesForm (email, child_name, grade, school, bag_pickup_method, need_backpack)
        VALUES ('$email', '$child_name', '$grade', '$school', '$bag_pickup_method', '$need_backpack');
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

