<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Family.php');

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
        $result_row['first-name2'],
        $result_row['last-name2'],
        $result_row['birthdate2'],
        $result_row['address2'],
        $result_row['city2'],
        $result_row['state2'],
        $result_row['zip2'],
        $result_row['email2'],
        $result_row['phone2'],
        $result_row['phone-type2'],
        $result_row['secondary-phone2'],
        $result_row['secondary-phone-type2'],
        $result_row['econtact-first-name'],
        $result_row['econtact-last-name'],
        $result_row['econtact-phone'],
        $result_row['econtact-relation'],
        $result_row['password'],
        $result_row['question'],
        $result_row['answer'],
        'family',
        0
    );

    return $family;
}

function add_family($family){
    if(!$family instanceof Family)
        die("add_family type mistach");

    $conn = connect();
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
