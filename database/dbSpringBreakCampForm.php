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

function createAngelGiftForm($form)
{
    $child_data = explode("_", $form['name']);

	$child_id = $child_data[0];
    // Check if form is already complete for child, if so then return
    if (isAngelGiftFormComplete($child_id)) {
        return;
    }

    $connection = connect();

    $email = $form["email"];
    $email = $form["name"];
    $email = $form["school_date"];
    $email = $form["isAttending"];
    $email = $form["hasWaiver"];
    $notes = $form["questions_comments"];
    if (empty($form["questions_comments"])) {
        $notes = null;
    }
}
?>