<?php

function createProgramInterestForm($form) {
    $connection = connect();

    $family_id = $_SESSION['_id'];
    $first_name = $form["first_name"];
    $last_name = $form["last_name"];
    $address = $form["address"];
	$city = $form["city"];
    $neighborhood = $form["neighborhood"];
    $state = $form["state"];
    $zip = $form["zip"];
    $cell_phone = $form["cell_phone"];
    $home_phone = $form["home_phone"];
    $email = $form["email"];
    $child_num = $form["child_num"];
    $child_ages = $form["child_ages"];
    $adult_num = $form["adult_num"];
	
    // First query inserts basic information in dbProgramInterestForm
    $query = "
        INSERT INTO dbProgramInterestForm (family_id, first_name, last_name, address, city, neighborhood, state, zip, cell_phone, home_phone, email, child_num, 
        child_ages, adult_num)
        values ('$family_id', '$first_name', '$last_name', '$address', '$city', '$neighborhood', '$state', '$zip', '$cell_phone', '$home_phone', '$email', '$child_num', 
        '$child_ages', '$adult_num');
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);

    // Insert program interests
    if (isset($args['programs'])) {
        $programs = $args['programs'];
        insertProgramInterests($programs, $id);
    }
    // Insert topic interests
    if (isset($args['topics'])) {
        $topics = $args['topics'];
        insertProgramInterests($topics, $id);
    }
    // insert availability
    $days = $form['days'];
    insertAvailability($days, $id);
    return $id;
}

function insertProgramInterests($programs, $form_id) {
    $connection = connect();
	
    // Second query inserts ids in dbProgramInterestsForm_ProgramInterests junction table
    $query = "INSERT INTO dbProgramInterestsForm_ProgramInterests (form_id, interest_id) values ";
    $last = end($programs);
    foreach ($programs as $program) {
        // Add value to query
        $query .= "($form_id, (SELECT id FROM dbProgramInterests WHERE interest = '$program'))";
        if ($program != $last) {
            $query .= ", ";
        } else {
            $query .= ";";
        }
    }
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}

function insertTopicInterests($topics, $form_id) {
    $connection = connect();
	
    // Third query inserts ids in dbProgramInterestsForm_TopicInterests junction table
    $query = "INSERT INTO dbProgramInterestsForm_TopicInterests (form_id, interest_id) values ";
    $last = end($topics);
    foreach ($topics as $topic) {
        // Check if topic exists in database, if not then insert new topic in dbTopicInterests
        $result = mysqli_query($connection, "SELECT * FROM dbTopicInterests WHERE interest = '$topic';");
        if (mysqli_num_rows($result) <= 0) {
            mysqli_query($connection, "INSERT INTO dbTopicInterests (interest) values ('$topic');");
        }
        // Add value to query
        $query .= "($form_id, (SELECT id FROM dbTopicInterests WHERE interest = '$topic'))";
        if ($topic != $last) {
            $query .= ", ";
        } else {
            $query .= ";";
        }
    }
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}

function insertAvailability($days, $form_id) {
    $connection = connect();
	
    // Third query inserts family availability data in dbAvalability
    $query = "INSERT INTO dbAvailability (form_id, day, morning, afternoon, evening, specific_time) values ";
    $last = array_key_last($days);
    // Go through days array
    foreach ($days as $key => $val) {
        // Get values from day
        $day_name = $key;
        $morning = (int) $val["morning"];
        $afternoon = (int) $val["afternoon"];
        $evening = (int) $val["evening"];
        $specific_time = $val["specific_time"];
        // If there was no input for specific time, set as N/A
        if (strlen($specific_time) <= 0) {
            $specific_time = "N/A";
        }
        // Add values to query
        $query .= "('$form_id', '$day_name', '$morning', '$afternoon', '$evening', '$specific_time')";
        if ($key != $last) {
            $query .= ", ";
        } else {
            $query .= ";";
        }
    }
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;

}
?>