<?php

function createBackToSchoolForm($form) {
    $child_data = explode("_", $form['child_name']);
    $child_id = $child_data[0];

    // Check if form is already complete for the child, if so return    
    if (isBackToSchoolFormComplete($child_id)) {
        return;
    }

    $connection = connect();
    $email = $form["email"];
    $child_name = $child_data[1];
    $grade = $form["grade"];
    $school = $form["school"];
    $bag_pickup_method = !empty($form["bag_pickup_method"]) ? $form["bag_pickup_method"] : null;
    $need_backpack = !empty($form["need_backpack"]) ? $form["need_backpack"] : null;

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
