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
        $result_row['econtact'],
        $result_row['jobTitle'],
        password_hash($result_row['password'], PASSWORD_BCRYPT),
        $result_row['securityQuestion'],
        password_hash($result_row['securityAnswer'], PASSWORD_BCRYPT)
    );
}

public function add_staff($staff){
    if(!$staff instanceof Staff){
        die("Add staff mismatch");
    }
    $conn = connect();
    $query = "SELECT * FROM dbStaff WHERE email = '" . $staff->getEmail() . "';";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 1 || $res == null){
        mysqli_query($conn,'INSERT INTO dbStaff (firstName, lastName, birthdate, address, email,
        phone, econtact, password, securityQuestion, securityAnswer) VALUES(" ' .
        $staff->getFirstName() . '","' .
        $staff->getLastName() . '","' .
        $staff->getBirthdate() . '","' .
        $staff->getAddress() . '","' .
        $staff->getEmail() . '","' . 
        $staff->getPhone() . '","' .
        $staff->getEContact() . '","' .
        $staff->jobTitle() . '","' . 
        $staff->getPassword() . '","' .
        $staff->getSecurityQuestion() . '","' . 
        $staff->getSecurityAnswer() . '");'
    );						
        mysqli_close($conn);
        return true;
    }
    
}

