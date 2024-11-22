<?php

// Function checks if a child has already completed the form
function isSpringBreakCampFormComplete($childID) 
{
    $connection = connect();

    $query = "SELECT * FROM dbSpringBreakCampForm INNER JOIN dbChildren ON dbSpringBreakCampForm.child_id = dbChildren.id WHERE dbChildren.id = $childID";
    $result = mysqli_query($connection, $query);
    if (!$result->num_rows > 0) {
        mysqli_close($connection);
        return false;
    } else {
        mysqli_close($connection);
        return true;
    }
}

function createSpringBreakCampForm($form)
{
    $child_data = explode("_", $form['child_name']);

	$child_id = $child_data[0];

    $connection = connect();

    $email = $form["email"];
    $student_name = $form["child_name"];
    $school_choice = $form["school_date"];
    $isAttending = $form["isAttending"] ? 1 : 0;
    $waiver_completed = $form["hasWaiver"] ? 1 : 0;
    $notes = !empty($form["questions_comments"]) ? $form["questions_comments"] : null;

    $query = "
        INSERT INTO dbSpringBreakCampForm (email, school_choice, isAttending, waiver_completed, notes, child_id)
        values ('$email', '$school_choice', '$isAttending', '$waiver_completed', '$notes', '$child_id');
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
?>