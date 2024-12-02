<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Family.php');
include_once('dbChildren.php');


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
 *
 * At this point, the account id hasn't been created yet because that will come from the auto_incremented value in dbFamily database.
 * The is why for now set the first field (id) to null
 */
function make_a_family($result_row){
    $family = new Family(
        null,
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['neighborhood'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['phone-type'],
        $result_row['secondary-phone'],
        $result_row['secondary-phone-type'],
        $result_row['isHispanic'],
        $result_row['race'],
        $result_row['income'],
        $result_row['first-name2'] ?? null,
        $result_row['last-name2'] ?? null,
        $result_row['birthdate2'] ?? null,
        $result_row['address2'] ?? null,
        $result_row['neighborhood2'] ?? null,
        $result_row['city2'] ?? null,
        $result_row['state2'] ?? null,
        $result_row['zip2'] ?? null,
        $result_row['email2'] ?? null,
        $result_row['phone2'] ?? null,
        $result_row['phone-type2'] ?? null,
        $result_row['secondary-phone2'] ?? null,
        $result_row['secondary-phone-type2'] ?? null,
        $result_row['isHispanic2'] ?? null,
        $result_row['race2'] ?? null,
        $result_row['econtact-first-name'],
        $result_row['econtact-last-name'],
        $result_row['econtact-phone'],
        $result_row['econtact-relation'],
        password_hash($result_row['password'], PASSWORD_BCRYPT),
        $result_row['question'],
        password_hash($result_row['answer'], PASSWORD_BCRYPT),
        0
    );

    return $family;
}

/**Same constructor as above, but this one constructs a family object using the fields from the database (i.e firstName instead of first-name). will change later so there not two functions that do the same thing */
function make_a_family2($result_row){
    $family = new Family(
        $result_row['id'],
        $result_row['firstName'],
        $result_row['lastName'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['neighborhood'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['phoneType'],
        $result_row['secondaryPhone'],
        $result_row['secondaryPhoneType'],
        $result_row['isHispanic'],
        $result_row['race'],
        $result_row['income'],
        $result_row['firstName2'] ?? null,
        $result_row['lastName2'] ?? null,
        $result_row['birthdate2'] ?? null,
        $result_row['address2'] ?? null,
        $result_row['neighborhood2'],
        $result_row['city2'] ?? null,
        $result_row['state2'] ?? null,
        $result_row['zip2'] ?? null,
        $result_row['email2'] ?? null,
        $result_row['phone2'] ?? null,
        $result_row['phoneType2'] ?? null,
        $result_row['secondaryPhone2'] ?? null,
        $result_row['secondaryPhoneType2'] ?? null,
        $result_row['isHispanic2'] ?? null,
        $result_row['race2'] ?? null,
        $result_row['econtactFirstName'],
        $result_row['econtactLastName'],
        $result_row['econtactPhone'],
        $result_row['econtactRelation'],
        $result_row['password'], // we dont need to hash the password because its coming from the db and already hashed
        $result_row['securityQuestion'],
        $result_row['securityAnswer'],
        $result_row['isArchived']
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
    if($result == null || mysqli_num_rows($result) == 0){
        mysqli_query($conn,'INSERT INTO dbFamily (firstName, lastName, birthdate, address, city,
        state, zip, email, phone, phoneType, secondaryPhone, secondaryPhoneType, firstName2, lastName2, 
        birthdate2, address2, city2, state2, zip2, email2, phone2, phoneType2, secondaryPhone2, secondaryPhoneType2, 
        econtactFirstName, econtactLastName, econtactPhone, econtactRelation, password, 
        securityQuestion, securityAnswer, isArchived, neighborhood, isHispanic, race,  income, neighborhood2,
        isHispanic2, race2) VALUES(" ' .
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
        $family->isArchived() . '","' . 
        $family->getNeighborhood() . '","' .
        $family->isHispanic() . '","' .
        $family->getRace() . '","' .
        $family->getIncome() . '","' .
        $family->getNeighborhood2() . '","' .
        $family->isHispanic2() . '","' .
        $family->getRace2() . 
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

/**
 * Retrieves family data, constructs a family object, and returns the family object based on passed in email
 *
 * This function returns an array because an array of family object(s) is needed for findFamily to loop through and display
 */
function retrieve_family_by_email_to_display($email){
    $conn = connect();
    $query = "SELECT * FROM dbFamily WHERE email = '" . $email . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row);
        mysqli_close($conn);
        return [$acct];
    }

    return null;

}

/**
 * Retrieves family data, constructs a family object, and returns the family object based on passed in email
 
function retrieve_family_by_id($id){
    $conn = connect();
    $query = "SELECT * FROM dbFamily WHERE id = '" . $id . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row); //here we call make_a_family2 instead of make_a_family because we now have the id from the database that can be included in the instantiation of the family object
        mysqli_close($conn);
        return [$acct]; //need to return an array with the family object because familyView.php needs to cycle through an array to display data
    }

    return null;
    
}
*/

function retrieve_family_by_email($email){
    $conn = connect();
    $query = "SELECT * FROM dbFamily WHERE email = '" . $email . "';";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row); //here we call make_a_family2 instead of make_a_family because we now have the id from the database that can be included in the instantiation of the family object
        mysqli_close($conn);
        return $acct;
    }

    return null;
}

/**
 * Function that retrieves all the families with the specified last name, turns them into Family ohjects, and
 * returns and array of those family objects
 */
function retrieve_family_by_lastName($lastName){
    $conn = connect();
    $query = "SELECT * FROM dbFamily WHERE lastName = '" . $lastName . "';";
    $result = mysqli_query($conn, $query);
    $families = []; //will store family objects
    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else if(mysqli_num_rows($result) > 1){ //case where there are multiple families with the same last name
        foreach($result as $fam){
            $families[] = make_a_family2($fam);
        }

        return $families;

    }else { //case where there is only one family with the specified last name
        $row = mysqli_fetch_assoc($result);
        //$acct = make_a_family2($row);
        $families[] = make_a_family2($row);
        mysqli_close($conn);
        return $families;
    }
}

function retrieve_family_by_id($id){
    $conn = connect();
    $query = "SELECT * FROM dbFamily WHERE id = '" . $id . "';";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) < 1 || $result == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($result);
        $acct = make_a_family2($row);
        mysqli_close($conn);
        return $acct;
    }
}

/**Function that gets all the children assoicated with a particular family */
function getChildren($family_id){
    $children = [];
    $conn = connect();
    $query = "SELECT dbChildren.* FROM dbFamily INNER JOIN dbChildren ON
        dbFamily.id = dbChildren.family_id WHERE dbFamily.id = '" . $family_id . "';" ;
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 0 || $result == null){
        return null;
    }else {
        foreach($result as $child){
            $children[] = make_a_child_from_database($child);
        }

        return $children;
    }
}


function change_family_password($id, $newPass) {
        $con=connect();
        $query = 'UPDATE dbFamily SET password = "' . $newPass . '" WHERE id = "' . $id . '"';
        $result = mysqli_query($con, $query);
        mysqli_close($con);
        return $result;
}

/**
 * Function used to update the family profile
 */
function update_profile($form, $id) {
    $conn = connect();

    // Prepare the SQL statement
    $sql = "
        UPDATE `dbFamily` 
        SET 
            firstName = ?, lastName = ?, birthdate = ?, address = ?, city = ?, state = ?, zip = ?, email = ?, phone = ?, phoneType = ?, 
            secondaryPhone = ?, secondaryPhoneType = ?, firstName2 = ?, lastName2 = ?, birthdate2 = ?, address2 = ?, city2 = ?, 
            state2 = ?, zip2 = ?, email2 = ?, phone2 = ?, phoneType2 = ?, secondaryPhone2 = ?, secondaryPhoneType2 = ?, 
            econtactFirstName = ?, econtactLastName = ?, econtactPhone = ?, econtactRelation = ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);

    // Ensure all form fields are set, or provide a default (e.g., null)
    $firstName = $form['first-name'] ?? '';
    $lastName = $form['last-name'] ?? '';
    $birthdate = $form['birthdate'] ?? '';
    $address = $form['address'] ?? '';
    $city = $form['city'] ?? '';
    $state = $form['state'] ?? '';
    $zip = $form['zip'] ?? '';
    $email = $form['email'] ?? '';
    $phone = $form['phone'] ?? '';
    $phoneType = $form['phone-type'] ?? '';
    $secondaryPhone = $form['secondary-phone'] ?? '';
    $secondaryPhoneType = $form['secondary-phone-type'] ?? '';
    $firstName2 = $form['first-name2'] ?? '';
    $lastName2 = $form['last-name2'] ?? '';
    $birthdate2 = $form['birthdate2'] ?? '';
    $address2 = $form['address2'] ?? '';
    $city2 = $form['city2'] ?? '';
    $state2 = $form['state2'] ?? '';
    $zip2 = $form['zip2'] ?? '';
    $email2 = $form['email2'] ?? '';
    $phone2 = $form['phone2'] ?? '';
    $phoneType2 = $form['phone-type2'] ?? '';
    $secondaryPhone2 = $form['secondary-phone2'] ?? '';
    $secondaryPhoneType2 = $form['secondary-phone-type2'] ?? '';
    $econtactFirstName = $form['econtact-first-name'] ?? '';
    $econtactLastName = $form['econtact-last-name'] ?? '';
    $econtactPhone = $form['econtact-phone'] ?? '';
    $econtactRelation = $form['econtact-relation'] ?? '';

    // Bind parameters to the statement
    $stmt->bind_param(
        "ssssssssssssssssssssssssssssi",
        $firstName, $lastName, $birthdate, $address, $city, $state, $zip, $email, $phone, $phoneType, 
        $secondaryPhone, $secondaryPhoneType, $firstName2, $lastName2, $birthdate2, $address2, $city2, 
        $state2, $zip2, $email2, $phone2, $phoneType2, $secondaryPhone2, $secondaryPhoneType2, 
        $econtactFirstName, $econtactLastName, $econtactPhone, $econtactRelation, $id
    );

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();

    return $success;
}

/**
 * Function that querys the dbHolidayMealBagForm database and retrieves the corresponding row for the family 
 */
function getHolidayMealBagData($family_id){
    $conn = connect();
    $query = "SELECT  dbHolidayMealBagForm.email, dbHolidayMealBagForm.household_size, dbHolidayMealBagForm.meal_bag, 
    dbHolidayMealBagForm.name, dbHolidayMealBagForm.address, dbHolidayMealBagForm.phone, dbHolidayMealBagForm.photo_release FROM dbFamily INNER JOIN dbHolidayMealBagForm ON dbFamily.id = dbHolidayMealBagForm.family_id WHERE dbFamily.id = '" . $family_id . "';" ;
    $res = mysqli_query($conn, $query);
    if(mysqli_num_rows($res) < 0 || $res == null){
        mysqli_close($conn);
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        return $row;
    }
}

// Sets a family to archived using thier id
function archive_family($id) {
    $query = "UPDATE dbFamily SET isArchived='1' WHERE id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

// Sets a family to unarchived using thier id
function unarchive_family($id) {
    $query = "UPDATE dbFamily SET isArchived='0' WHERE id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function find_all_families(){
    $query = "SELECT * FROM dbFamily ORDER BY lastName";
    $connection = connect();
    // Execute query
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    // Get family data
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $families = [];
    foreach ($raw as $row) {
        if ($row['id'] == 'vmsroot') {
            continue;
        }
        $families []= make_a_family2($row);
    }
    mysqli_close($connection);
    return $families;
}

// Find family gets families based in criteria in parameters, it builds the query based on what is in it and what isn't 
// More criteria can be added later
// Find family gets families based in criteria in parameters, it builds the query based on what is in it and what isn't 
// More criteria can be added later
function find_families($last_name, $email, $neighborhood, $address, $city, $zip, $income, $assistance, $archived){
    // Build query
    $where = 'WHERE ';
    $joins = '';
    $first = true;
    // Add last name
    if ($last_name) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "lastName LIKE '%$last_name%'";
        $first = false;
    }
    // Add email
    if ($email) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "email LIKE '%$email%'";
        $first = false;
    }
    // Add neighborhood
    if ($neighborhood) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "neighborhood LIKE '%$neighborhood%'";
        $first = false;
    }
    // Add address
    if ($address) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "address LIKE '%$address%'";
        $first = false;
    }
    // Add city
    if ($city) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "city LIKE '%$city%'";
        $first = false;
    }
    // Add income
    if ($income) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "(";
        $last = end($income);
        // Go through each income in the input
        foreach ($income as $i) {
            if ($i != $last) {
                $where .= "income LIKE '%$i%' OR ";
            } else {
                $where .= "income LIKE '%$i%'";
            }
        }
        $where .= ")";
        $first = false;
    }
    // Add current assistance
    if ($assistance) {
        $joins .= " INNER JOIN dbFamily_Assistance ON dbFamily.id = dbFamily_Assistance.family_id INNER JOIN 
        dbAssistance ON dbFamily_Assistance.assistance_id = dbassistance.id";
        $where .= "(";
        $last = end($assistance);
        // Go through each income in the input
        foreach ($assistance as $a) {
            if ($a != $last) {
                $where .= "assistance LIKE '%$a%' OR ";
            } else {
                $where .= "assistance LIKE '%$a%'";
            }
        }
        $where .= ")";
        if (!$first) {
            $where .= ' AND ';
        }
        $first = false;
    }
    // Add isArchived
    if ($archived) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= " isArchived='1'";
    } else {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= " isArchived='0'";
    }
    $query = "SELECT * FROM dbFamily $joins $where ORDER BY lastName";
    $connection = connect();
    // Execute query
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    // Get family data
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $families = [];
    foreach ($raw as $row) {
        if ($row['id'] == 'vmsroot') {
            continue;
        }
        $families []= make_a_family2($row);
    }
    mysqli_close($connection);
    return $families;
}

// Inserts family and language ids in junction table
function insert_family_languages($languages, $family_id) {
    $connection = connect();

    // Query inserts ids in dbFamily_Languages junction table
    $query = "INSERT INTO dbFamily_Languages (family_id, language_id) values ";
    $last = end($languages);
    foreach ($languages as $language) {
       // Check if topic exists in database, if not then insert new topic in dbTopicInterests
       $result = mysqli_query($connection, "SELECT * FROM dbLanguages WHERE language = '$language';");
       if (mysqli_num_rows($result) <= 0) {
           mysqli_query($connection, "INSERT INTO dbLanguages (language) values ('$language');");
       }
       // Add value to query
       $query .= "($family_id, (SELECT id FROM dbLanguages WHERE language = '$language'))";
       if ($language != $last) {
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
   mysqli_close($connection);
   return $id;
}

// Retrieves all langauges associated with a family
function retrieve_family_langauges($family_id) {
    $connection = connect();

    // Query selects all languages that a family has
    $query = "SELECT dbLanguages.language FROM dbLanguages INNER JOIN dbFamily_Languages ON dbLanguages.id = dbFamily_Languages.language_id 
    INNER JOIN dbFamily ON dbFamily.id = dbFamily_Languages.family_id WHERE dbFamily.id = $family_id;";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $languages = [];
    foreach ($result as $row) {
        $languages[] = $row['language'];
    }
    mysqli_close($connection);
    return $languages;
}

// Inserts family and assistance ids in junction table
function insert_family_assistance($assistance, $family_id) {
    $connection = connect();

    // Query inserts ids in dbFamily_Assistance junction table
    $query = "INSERT INTO dbFamily_Assistance (family_id, assistance_id) values ";
    $last = end($assistance);
    foreach ($assistance as $a) {
       // Check if assiatnce exists in database, if not then insert data in dbAssistance
       $result = mysqli_query($connection, "SELECT * FROM dbAssistance WHERE assistance = '$a';");
       if (mysqli_num_rows($result) <= 0) {
           mysqli_query($connection, "INSERT INTO dbAssistance (assistance) values ('$a');");
       }
       // Add value to query
       $query .= "($family_id, (SELECT id FROM dbAssistance WHERE assistance = '$a'))";
       if ($a != $last) {
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
   mysqli_close($connection);
   return $id;
}

// Retrieves all assistance associated with a family
function retrieve_family_assistance($family_id) {
    $connection = connect();

    // Query selects all languages that a family has
    $query = "SELECT dbAssistance.assistance FROM dbAssistance INNER JOIN dbFamily_Assistance ON dbAssistance.id = dbFamily_Assistance.assistance_id 
    INNER JOIN dbFamily ON dbFamily.id = dbFamily_Assistance.family_id WHERE dbFamily.id = $family_id;";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $assistance = [];
    foreach ($result as $row) {
        $assistance[] = $row['assistance'];
    }
    mysqli_close($connection);
    return $assistance;
}
?>