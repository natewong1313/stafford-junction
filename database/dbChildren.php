<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Children.php');

function make_a_child($result_row){
    $child = new Child (
        $result_row['id'],
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['gender'],
        $result_row['medical_notes'],
        $result_row['notes']
    );
    return $child;
}

/**Use this function whenever you need to make a child object from a row in the dbChildren database */
function make_a_child_from_database($result_row){
    $child = new Child (
        $result_row['id'],
        $result_row['first_name'],
        $result_row['last_name'],
        $result_row['dob'],
        $result_row['gender'],
        $result_row['medical_notes'],
        $result_row['notes']
    );
    return $child;
}

/**
 * Function that makes a child from the sign up page
 */
function make_a_child_from_sign_up($result_row){
    $child = new Child (
        $result_row['id'],
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['gender'],
        $result_row['last-child_medical_notes_'],
        $result_row['child_additional_notes_-name']
    );
    return $child;
}

function retrieve_children_by_email($email){
    $conn = connect();
    $query = "SELECT * FROM dbchildren INNER JOIN dbfamily ON dbchildren.family_id = dbfamily.id WHERE dbfamily.email = '" . $email . "';";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        echo "User not found";
        return null;
    } else {
        $children = [];
        $row = mysqli_fetch_assoc($result);
        while ($row != null) {
            $acct = make_a_child($row);
            array_push($children, $acct);
            $row = mysqli_fetch_assoc($result);
        }
        mysqli_close($conn);
        return $children;
    }

    return null;
    
}

//add child to database
function add_child($child, $fam_id){
    $conn = connect();
    mysqli_query($conn, 'INSERT INTO dbChildren (family_id, first_name, last_name, dob, gender, medical_notes, notes) VALUES (" ' .
        $fam_id . '","' .
        $child->getFirstName() . '","' .
        $child->getLastName() . '","' .
        $child->getBirthDate() . '","' .
        $child->getGender() . '","' .
        $child->getMedicalNotes() . '","' .
        $child->getNotes() . 
        '");'
    );
    mysqli_close($conn);

    return true;
}

function retrieve_child_by_id($id){
    $conn = connect();
    $query = "SELECT * FROM dbChildren WHERE id = '" . $id . "';";
    $res = mysqli_query($conn, $query);
    if(mysqli_num_rows($res) < 0 || $res == null){
        return null;
    }

    $row = mysqli_fetch_assoc($res);

    $child = make_a_child_from_database($row);
    mysqli_close($conn);

    return $child;
}


?>