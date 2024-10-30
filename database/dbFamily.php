<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Family.php');


/**
 * Simply prints var_dump results in a more readable fashion
 */
function prettyPrint($val){
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
    die();
}


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
        password_hash($result_row['password'], PASSWORD_BCRYPT), //$result_row['password'],
        $result_row['question'],
        $result_row['answer'],
        'family',
        'false'
    );

    return $family;
}

/**Same constructor as above, but this one constructs a family object using the fields from the database (i.e firstName instead of first-name). will change later so there not two functions that do the same thing */
function make_a_family2($result_row){
    $family = new Family(
        $result_row['firstName'],
        $result_row['lastName'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['phoneType'],
        $result_row['secondaryPhone'],
        $result_row['secondaryPhoneType'],
        $result_row['firstName2'] ?? null,
        $result_row['lastName2'] ?? null,
        $result_row['birthdate2'] ?? null,
        $result_row['address2'] ?? null,
        $result_row['city2'] ?? null,
        $result_row['state2'] ?? null,
        $result_row['zip2'] ?? null,
        $result_row['email2'] ?? null,
        $result_row['phone2'] ?? null,
        $result_row['phoneType2'] ?? null,
        $result_row['secondaryPhone2'] ?? null,
        $result_row['secondaryPhoneType2'] ?? null,
        $result_row['econtactFirstName'],
        $result_row['econtactLastName'],
        $result_row['econtactPhone'],
        $result_row['econtactRelation'],
        //password_hash($result_row['password'], PASSWORD_BCRYPT),
        $result_row['password'],
        $result_row['securityQuestion'],
        $result_row['securityAnswer'],
        $result_row['accountType'],
        $result_row['isArchived']
        //'family',
        //'false'
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
        econtactFirstName, econtactLastName, econtactPhone, econtactRelation, isArchived, person_id) VALUES(" ' .
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
        "false" . '","' .
        $family->getPersonId() .
        '");'
    );						
        mysqli_close($conn);
        return true;
        
    }
    mysqli_close($conn);
    return false;
}

/**
 * Retrieves family data, constructs a family object, and returns the family object based on passed in array
 */
function retrieve_family($args){
    $conn = connect();
    //$query = 'SELECT * FROM dbFamily WHERE email = "' . $email . ';"';
    $query = "SELECT * FROM dbFamily WHERE email = '" . $args['email'] . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        echo "User not found";
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row);
        mysqli_close($conn);
        return $acct;
    }

    return null;
}

function get_family_by_person_id($id){
    $conn = connect();
    $query = "SELECT * FROM dbFamily WHERE person_id = '" . $id . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        echo "User not found";
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row);
        mysqli_close($conn);
        return $acct;
    }

    return null;
}

/**
 * Retrieves family data, constructs a family object, and returns the family object based on passed in email
 */
function retrieve_family_by_email($email){
    $conn = connect();
    //$query = 'SELECT * FROM dbFamily WHERE email = "' . $email . ';"';
    $query = "SELECT * FROM dbFamily WHERE email = '" . $email . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row);
        mysqli_close($conn);
        return $acct;
    }

    return null;
    
}

/**
 * Retrieves family data, constructs a family object, and returns the family object based on passed in email
 */
function retrieve_family_by_email($email){
    $conn = connect();
    //$query = 'SELECT * FROM dbFamily WHERE email = "' . $email . ';"';
    $query = "SELECT * FROM dbFamily WHERE email = '" . $email . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row);
        mysqli_close($conn);
        return $acct;
    }

    return null;
    
}

function retrieve_id_by_email($email){
    $con = connect();
    $query = "SELECT * FROM dbFamily WHERE email = '" . $email . "';";
    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        return $id;
    }

    return null;
    
}