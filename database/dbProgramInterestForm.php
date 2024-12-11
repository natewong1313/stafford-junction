<?php

require_once("dbinfo.php");
require_once("dbFamily.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    session_start();
    $family_id = $_GET['id'] ?? $_SESSION['_id'];
    if (deleteProgramInterestForm($family_id)) {
        //redirect to fillForm after successful deletion
        if (isset($_GET['id'])) {
            header("Location: fillForm.php?status=deleted&id=" . $_GET['id']);
        } else {
            header("Location: fillForm.php?status=deleted");
        }
        exit;
    } else {
        if (isset($_GET['id'])) {
            header("Location: fillForm.php?status=errord&id=" . $_GET['id']);
        } else {
            header("Location: fillForm.php?error=deleted");
        }
        exit;
    }
}


function deleteProgramInterestForm($family_id) {
    $connection = connect();
    mysqli_begin_transaction($connection);

    try {
        // Get the form ID for the family
        $formQuery = "SELECT id FROM dbProgramInterestForm WHERE family_id = $family_id";
        $formResult = mysqli_query($connection, $formQuery);
        if (!$formResult || mysqli_num_rows($formResult) <= 0) {
            throw new Exception("Form not found for family ID: $family_id");
        }
        $formRow = mysqli_fetch_assoc($formResult);
        $form_id = $formRow['id'];

        // Delete from related tables
        $deleteProgramInterests = "DELETE FROM dbProgramInterestsForm_ProgramInterests WHERE form_id = $form_id";
        $deleteTopicInterests = "DELETE FROM dbProgramInterestsForm_TopicInterests WHERE form_id = $form_id";
        $deleteAvailability = "DELETE FROM dbAvailability WHERE form_id = $form_id";

        mysqli_query($connection, $deleteProgramInterests);
        mysqli_query($connection, $deleteTopicInterests);
        mysqli_query($connection, $deleteAvailability);

        // Delete the main form record
        $deleteForm = "DELETE FROM dbProgramInterestForm WHERE id = $form_id";
        mysqli_query($connection, $deleteForm);

        // Commit the transaction
        mysqli_commit($connection);
        return true;
    } catch (Exception $e) {
        // Rollback if any query fails
        mysqli_rollback($connection);
        return false;
    } finally {
        mysqli_close($connection);
    }
}

function createProgramInterestForm($form) {
    $connection = connect();

    $family_id = $_GET['id'] ?? $_SESSION['_id'];
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
	$id = null;

    // Begin transaction so if there are any errors with any queries, they are all rolled back and no data is inserted into database
    // This is done so if one query is succesful and another isn't, it doesn't just insert the successful one which would result in incomplete data in the database
    mysqli_begin_transaction($connection);
    try {
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

        // Insert program interests
        if (isset($form['programs'])) {
            $programs = $form['programs'];
            insertProgramInterests($programs, $id, $connection);
        }
        // Insert topic interests
        if (isset($form['topics'])) {
            $topics = $form['topics'];
            insertTopicInterests($topics, $id, $connection);
        }
        // insert availability
        $days = $form['days'];
        insertAvailability($days, $id, $connection);
        // Commit transaction if there were no problems inserting data
        mysqli_commit($connection);
    } catch (Exception $e) {
        // If there was a problem inserting data roll back and return null
        mysqli_rollback($connection);
        return null;
    }
    mysqli_close($connection);
    return $id;
}

function insertProgramInterests($programs, $form_id, $connection) {
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
    return $id;
}

function insertTopicInterests($topics, $form_id, $connection) {
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
    return $id;
}

function insertAvailability($days, $form_id, $connection) {
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
            $specific_time = "";
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
    return $id;
}

// Function that gets the program interest form data from a family
function getProgramInterestFormData($family_id){
    $conn = connect();
    $query = "SELECT dbProgramInterestForm.* FROM dbFamily INNER JOIN dbProgramInterestForm ON dbFamily.id = dbProgramInterestForm.family_id WHERE dbFamily.id = $family_id";
    $res = mysqli_query($conn, $query);
    mysqli_close($conn);
    if(mysqli_num_rows($res) <= 0 || $res == null){
        return null;
    } else {
        $row = mysqli_fetch_assoc($res);
        return $row;
    }
}

// Function that gets the program interest data from a family
function getProgramInterestData($family_id) {
    $conn = connect();
    $query = "SELECT dbProgramInterests.interest FROM dbProgramInterests INNER JOIN dbProgramInterestsForm_ProgramInterests ON 
        dbProgramInterests.id = dbProgramInterestsForm_ProgramInterests.interest_id INNER JOIN dbProgramInterestForm ON 
        dbProgramInterestsForm_ProgramInterests.form_id = dbProgramInterestForm.id WHERE dbProgramInterestForm.family_id = $family_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return null;
    }
    $programs = [];
    foreach ($result as $row) {
        $programs[] = $row['interest'];
    }
    mysqli_close($conn);
    return $programs;
}

// Function that gets the topic interest data from a family
function getTopicInterestData($family_id) {
    $conn = connect();
    $query = "SELECT dbTopicInterests.interest FROM dbTopicInterests INNER JOIN dbProgramInterestsForm_TopicInterests ON 
        dbTopicInterests.id = dbProgramInterestsForm_TopicInterests.interest_id INNER JOIN dbProgramInterestForm ON 
        dbProgramInterestsForm_TopicInterests.form_id = dbProgramInterestForm.id WHERE dbProgramInterestForm.family_id = $family_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return null;
    }
    $topics = [];
    foreach ($result as $row) {
        $topics[] = $row['interest'];
    }
    mysqli_close($conn);
    return $topics;
}

// Get any topics that a family entered in the other topics section as an array
function getOtherTopicInterestData($topics) {
    $other_topics = [];
    $ignore_topics = ["Legal Services", "Finance", "Tenant Rights", "Health/Wellness/Nutrition", "Continuing Education", "Parenting", "Mental Health",
            "Job/Career Guidance", "Citizenship Classes"];
    $size = sizeof($topics);
    for ($i = 0; $i < $size; $i++) {
        if (!in_array($topics[$i], $ignore_topics)) {
            $other_topics[] = $topics[$i];
        }
    }
    return $other_topics;
}

// Function that gets availability data from a familt
function getAvailabilityData($family_id) {
    $conn = connect();
    $query = "SELECT dbAvailability.* FROM dbAvailability INNER JOIN dbProgramInterestForm ON 
        dbAvailability.form_id = dbProgramInterestForm.id WHERE dbProgramInterestForm.family_id = $family_id";
    $result = mysqli_query($conn, $query);
    if (!$result ||  mysqli_num_rows($result) <= 0) {
        return null;
    }
    $availabilities = [];
    foreach ($result as $row) {
        $availabilities[$row['day']] = array(
            "morning" => $row["morning"],
            "afternoon" => $row["afternoon"],
            "evening" => $row["evening"],
            "specific_time" => $row["specific_time"],
        );
    }
    mysqli_close($conn);
    return $availabilities;
}

// Autofills text fields in the form, disables them if form was already filled and is being viewed
function showProgramInterestData($data, $value) {
    // If family hasn't filled form, set field to the value
    if (!isset($data)) {
        echo "value=\"$value\"";
    } else {
        // Else, set it to the data the user entered when filling the form and disable
        echo "disabled style='background-color: yellow; color: black;' value=\"$data\"";
    }
}

// Checks any checkboxes that the family pressed when filling form
function showProgramInterestCheckbox($data, $value) {
    if ($data != null) {
        // If value in topic array is same as the checkbox value, set it to checked
        if (in_array($value, $data)) {
            echo "style='pointer-events: none;' checked";
        } else {
            echo "style='pointer-events: none;'";
        }
    }
}

// Checks any checkboxes in the availability section that the family pressed when filling form
function showAvailabilityCheckbox($data) {
    // If available, set it to checked
    if ($data == 1) {
        echo "style='pointer-events: none;' checked";
    } else if ($data != null) {
        echo "disabled";
    }
}
?>

<?php
function getProgramInterestSubmissions() {
    $conn = connect();
    $query = "SELECT 
        dbProgramInterestForm.*, 
        GROUP_CONCAT(DISTINCT dbProgramInterests.interest) as program_interests,
        GROUP_CONCAT(DISTINCT dbTopicInterests.interest) as topic_interests
    FROM dbProgramInterestForm
    -- handles program interests
    LEFT JOIN dbProgramInterestsForm_ProgramInterests ON dbProgramInterestForm.id = dbProgramInterestsForm_ProgramInterests.form_id
    LEFT JOIN dbProgramInterests ON dbProgramInterestsForm_ProgramInterests.interest_id = dbProgramInterests.id
    -- handles topic interests
    LEFT JOIN dbProgramInterestsForm_TopicInterests ON dbProgramInterestForm.id = dbProgramInterestsForm_TopicInterests.form_id
    LEFT JOIN dbTopicInterests ON dbProgramInterestsForm_TopicInterests.interest_id = dbTopicInterests.id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}

function getProgramInterestSubmissionsFromFamily($familyId) {
    $conn = connect();
    $query = "SELECT 
        dbProgramInterestForm.*, 
        GROUP_CONCAT(DISTINCT dbProgramInterests.interest) as program_interests,
        GROUP_CONCAT(DISTINCT dbTopicInterests.interest) as topic_interests
    FROM dbProgramInterestForm
    -- handles program interests
    LEFT JOIN dbProgramInterestsForm_ProgramInterests ON dbProgramInterestForm.id = dbProgramInterestsForm_ProgramInterests.form_id
    LEFT JOIN dbProgramInterests ON dbProgramInterestsForm_ProgramInterests.interest_id = dbProgramInterests.id
    -- handles topic interests
    LEFT JOIN dbProgramInterestsForm_TopicInterests ON dbProgramInterestForm.id = dbProgramInterestsForm_TopicInterests.form_id
    LEFT JOIN dbTopicInterests ON dbProgramInterestsForm_TopicInterests.interest_id = dbTopicInterests.id
    WHERE dbProgramInterestForm.family_id = $familyId";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}
?>
