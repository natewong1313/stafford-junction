<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Staff.php');

//Function that creates a new staff object from staff sign up form
function make_staff_from_signup($result_row){
    $staff = new Staff(
        null,
        $result_row['firstName'],
        $result_row['lastName'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['econtactName'],
        $result_row['econtactPhone'],
        $result_row['jobTitle'],
        password_hash($result_row['password'], PASSWORD_BCRYPT),
        $result_row['securityQuestion'],
        password_hash($result_row['securityAnswer'], PASSWORD_BCRYPT)
    );

    return $staff;
}

//Function that gets all the info of a staff user from dbStaff and constructs a staff object from that data
function make_staff_from_db($result_row){
    $staff = new Staff(
        $result_row['id'],
        $result_row['firstName'],
        $result_row['lastName'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['econtactName'],
        $result_row['econtactPhone'],
        $result_row['jobTitle'],
        $result_row['password'],
        $result_row['securityQuestion'],
        $result_row['securityAnswer']
    );

    return $staff;
}

//function that inserts staff into dbStaff
function add_staff($staff){
    if(!$staff instanceof Staff){
        die("Add staff mismatch");
    }
    $conn = connect();
    $query = "SELECT * FROM dbStaff WHERE email = '" . $staff->getEmail() . "';";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 1 || $res == null){
        mysqli_query($conn,'INSERT INTO dbStaff (firstName, lastName, birthdate, address, email,
        phone, econtactName, econtactPhone, jobTitle, password, securityQuestion, securityAnswer) VALUES(" ' .
        $staff->getFirstName() . '","' .
        $staff->getLastName() . '","' .
        $staff->getBirthdate() . '","' .
        $staff->getAddress() . '","' .
        $staff->getEmail() . '","' . 
        $staff->getPhone() . '","' .
        $staff->getEContactName() . '","' .
        $staff->getEContactPhone() . '","' .
        $staff->getJobTitle() . '","' . 
        $staff->getPassword() . '","' .
        $staff->getSecurityQuestion() . '","' . 
        $staff->getSecurityAnswer() . '");'
    );						
        mysqli_close($conn);
        return true;
    }
    
}

//Function that retrieves staff member from dbStaff by email
function retrieve_staff($email){
    $conn = connect();
    $query = "SELECT * FROM dbStaff where email = '" . $email . "';";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 1 || $res == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        $staff = make_staff_from_db($row);
        return $staff;
    }
}

//Function that retrieves staff member from dbStaff by id
function retrieve_staff_by_id($id){
    $conn = connect();
    $query = "SELECT * FROM dbStaff where id = '" . $id . "';";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 1 || $res == null){
        mysqli_close($conn);
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        $staff = make_staff_from_db($row);
        mysqli_close($conn);
        return $staff;
    }
}

function change_staff_password($id, $newPass) {
    $con=connect();
    $query = 'UPDATE dbStaff SET password = "' . $newPass . '" WHERE id = "' . $id . '"';
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}