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

// Function to retrieve completed School Supplies forms by family ID
function get_school_supplies_form_by_family_id($familyID) {
    $connection = connect();

    // Query to join children table and retrieve forms based on family ID
    $query = "
        SELECT ssf.*
        FROM dbSchoolSuppliesForm ssf
        INNER JOIN dbChildren c ON ssf.child_id = c.id
        WHERE c.family_id = $familyID
    ";

    $result = mysqli_query($connection, $query);
    $forms = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $forms[] = $row;
        }
    }

    mysqli_close($connection);

    return $forms; // Return an array of forms or an empty array if none found
}

function getSchoolSuppliesFormById($form_id) {
    $conn = connect();
    $query = "SELECT * FROM dbSchoolSuppliesForm WHERE id = $form_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $form_data = mysqli_fetch_assoc($result);
        mysqli_close($conn);
        return $form_data;
    }
    mysqli_close($conn);
    return null;
}

// Check for delete action and process the deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    session_start();
    
    // Get the form ID to delete from the POST request
    $form_id = $_POST['form_id']; // Ensure form_id is passed from the form

    if (deleteSchoolSuppliesForm($form_id)) {
        // Redirect to the appropriate page after successful deletion
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

// Function to delete a specific school supplies form
function deleteSchoolSuppliesForm($form_id) {
    $connection = connect();
    mysqli_begin_transaction($connection);

    try {
        // Delete the main form record
        $deleteForm = "DELETE FROM dbSchoolSuppliesForm WHERE id = $form_id";
        if (mysqli_query($connection, $deleteForm)) {
            // Commit the transaction
            mysqli_commit($connection);
            return true;
        } else {
            throw new Exception("Error deleting form with ID: $form_id");
        }
    } catch (Exception $e) {
        // Rollback if any query fails
        mysqli_rollback($connection);
        return false;
    } finally {
        mysqli_close($connection);
    }
}

function getSchoolSuppliesSubmissions() {
    $conn = connect();
    $query = "SELECT * FROM dbSchoolSuppliesForm INNER JOIN dbChildren ON dbChildren.id = dbSchoolSuppliesForm.child_id;";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    mysqli_close($conn);
    return [];
}

function getSchoolSuppliesSubmissionsFromFamily($familyId) {
    require_once("dbChildren.php");
    $children = retrieve_children_by_family_id($familyId);
    if (!$children){
        return [];
    }
    $childrenIds = array_map(function($child) {
        return $child->getId();
    }, $children);
    $joinedIds = join(",",$childrenIds);
    $conn = connect();
    $query = "SELECT * FROM dbSchoolSuppliesForm INNER JOIN dbChildren ON dbSchoolSuppliesForm.child_id = dbChildren.id WHERE dbSchoolSuppliesForm.child_id IN ($joinedIds)";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    return [];
}
?>

