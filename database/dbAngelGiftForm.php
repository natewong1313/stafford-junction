<?php
function createAngelGiftForm($form) {
    
    $child_data = explode("_", $form['child_name']);

	$child_id = $child_data[0];
    // Check if form is already complete for child, if so then return
    if (isAngelGiftFormComplete($child_id)) {
        return;
    }
    $connection = connect();
    $email = $form["email"];
    $parent_name = $form["parent_name"];
    $phone = $form["phone"];
    $child_name = $child_data[1];
	$gender = $form["gender"];
    $age = $form["age"];
    $pants_size = $form["pants_size"];
    if (empty($form["pants_size"])) {
        $pants_size = null;
    }
    $shirt_size = $form["shirt_size"];
    if (empty($form["shirt_size"])) {
        $shirt_size = null;
    }
    $shoe_size = $form["shoe_size"];
    if (empty($form["shoe_size"])) {
        $shoe_size = null;
    }
    $coat_size = $form["coat_size"];
    if (empty($form["coat_size"])) {
        $coat_size = null;
    }
    $underwear_size = $form["underwear_size"];
    if (empty($form["underwear_size"])) {
        $underwear_size = null;
    }
    $sock_size = $form["sock_size"];
    if (empty($form["sock_size"])) {
        $sock_size = null;
    }
    $wants = $form["wants"];
    $interests = $form["interests"];
    $photo_release = $form["photo_release"];
	
    $query = "
        INSERT INTO dbAngelGiftForm (child_id, email, parent_name, phone, child_name, gender, age, pants_size, shirt_size, shoe_size, coat_size, 
        underwear_size, sock_size, wants, interests, photo_release)
        values ('$child_id', '$email', '$parent_name', '$phone', '$child_name', '$gender', '$age', '$pants_size', '$shirt_size', '$shoe_size', '$coat_size', 
        '$underwear_size', '$sock_size', '$wants', '$interests', '$photo_release');
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

// Function checks if a child has already completed the form
function isAngelGiftFormComplete($childID) {
    $connection = connect();

    $query = "SELECT * FROM dbAngelGiftForm INNER JOIN dbChildren ON dbAngelGiftForm.child_id = dbChildren.id WHERE dbChildren.id = $childID";
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