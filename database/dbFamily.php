<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Family.php');

/**
 * function that takes the $_POST arguments from the sign up page as an assoc array
 * and instantiates a new Family object with that data
 */
function make_a_family($result_row){
    $family = new Family(
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['phone-type'],
        $result_row['secondary-phone'],
        $result_row['secondary-phone-type'],
        $result_row['first-name2'] ?? null,
        $result_row['last-name2'] ?? null,
        $result_row['birthdate2'] ?? null,
        $result_row['address2'] ?? null,
        $result_row['city2'] ?? null,
        $result_row['state2'] ?? null,
        $result_row['zip2'] ?? null,
        $result_row['email2'] ?? null,
        $result_row['phone2'] ?? null,
        $result_row['phone-type2'] ?? null,
        $result_row['secondary-phone2'] ?? null,
        $result_row['secondary-phone-type2'] ?? null,
        $result_row['econtact-first-name'],
        $result_row['econtact-last-name'],
        $result_row['econtact-phone'],
        $result_row['econtact-relation'],
        password_hash($result_row['password'], PASSWORD_BCRYPT),
        $result_row['question'],
        $result_row['answer'],
        'family',
        'false'
    );

    return $family;
}

/**
 * function that takes a family object and inserts it into the database
 */
function add_family($family){
    if(!$family instanceof Family)
        die("add_family type mistach");

    $conn = connect();
    //first checks to see if the family already exists by looking at email
    $query = "SELECT * FROM dbFamily WHERE email = '" . $family->getEmail() . "'";
    $result = mysqli_query($conn,$query);
    //var_dump($result);
    if($result == null || mysqli_num_rows($result) == 0){
        mysqli_query($conn,'INSERT INTO dbFamily (firstName, lastName, birthdate, address, city,
        state, zip, email, phone, phoneType, secondaryPhone, secondaryPhoneType, firstName2, lastName2, 
        birthdate2, address2, city2, state2, zip2, email2, phone2, phoneType2, secondaryPhone2, secondaryPhoneType2, 
        econtactFirstName, econtactLastName, econtactPhone, econtactRelation, password, securityQuestion, 
        securityAnswer, accountType, isArchived) VALUES(" ' .
        $family->getFirstName() . '","' .
        $family->getLastName() . '","' .
        $family->getBirthDate() . '","' .
        $family->getAddress() . '","' .
        $family->getCity() . '","' . 
        $family->getState() . '","' .
        $family->getZip() . '","' .
        $family->getEmail() . '","' . 
        $family->getPhone() . '","' .
        $family->getPhoneType() . '","' . 
        $family->getSecondaryPhone() . '","' .
        $family->getSecondaryPhoneType() . '","' .
        $family->getFirstName2() . '","' .
        $family->getLastName2() . '","' .
        $family->getBirthDate2() . '","' .
        $family->getAddress2() . '","' .
        $family->getCity2() . '","' .
        $family->getState2() . '","' .
        $family->getZip2() . '","' .
        $family->getEmail2() . '","' .
        $family->getPhone2() . '","' .
        $family->getPhoneType2() . '","' .
        $family->getSecondaryPhone2() . '","' .
        $family->getSecondaryPhoneType2() . '","' .
        $family->getEContactFirstName() . '","' .
        $family->getEContactLastName() . '","' .
        $family->getEContactPhone() . '","' .
        $family->getEContactRelation() . '","' .
        $family->getPassword() . '","' .
        $family->getSecurityQuestion() . '","' .
        $family->getSecurityAnswer() . '","' .
        $family->getAccountType() . '","' .
        "false" . 
        '");'
    );						
        mysqli_close($conn);
        return true;
        
    }
    mysqli_close($conn);
    return false;
}