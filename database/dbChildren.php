<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Children.php');

function make_a_child($result_row){
    $child = new Child (
        $result_row['id'],
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['neighborhood'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['gender'],
        $result_row['school'],
        $result_row['grade'],
        $result_row['is_hispanic'],
        $result_row['race'],
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
        $result_row['address'],
        $result_row['neighborhood'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['gender'],
        $result_row['school'],
        $result_row['grade'],
        $result_row['is_hispanic'],
        $result_row['race'],
        $result_row['medical_notes'],
        $result_row['notes']
    );
    return $child;
}

function retrieve_children_by_family_id($id){
    $conn = connect();
    $query = "SELECT dbChildren.* FROM dbChildren INNER JOIN dbFamily ON dbChildren.family_id = dbFamily.id WHERE dbFamily.id = '" . $id . "';";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) < 1 || $result == null){
        echo "User not found";
        return null;
    } else {
        $children = [];
        $row = mysqli_fetch_assoc($result);
        while ($row != null) {
            $acct = make_a_child_from_database($row);
            array_push($children, $acct);
            $row = mysqli_fetch_assoc($result);
        }
        //var_dump($children);
        mysqli_close($conn);
        return $children;
    }

    return null;
    
}

//add child to database
function add_child($child, $fam_id){
    $conn = connect();
    mysqli_query($conn, 'INSERT INTO dbChildren (family_id, first_name, last_name, dob, gender, medical_notes, notes, neighborhood, address, city, state, zip,
        school, grade, is_hispanic, race) VALUES (" ' .
        $fam_id . '","' .
        $child->getFirstName() . '","' .
        $child->getLastName() . '","' .
        $child->getBirthDate() . '","' .
        $child->getGender() . '","' .
        $child->getMedicalNotes() . '","' .
        $child->getNotes() . '","' .
        $child->getNeighborhood() . '","' .
        $child->getAddress() . '","' .
        $child->getCity() . '","' .
        $child->getState() . '","' .
        $child->getZip() . '","' .
        $child->getSchool() . '","' .
        $child->getGrade() . '","' .
        $child->isHispanic() . '","' .
        $child->getRace() .
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
  
/**
 * Function that makes a child from the sign up page
 */
function make_a_child_from_sign_up($result_row){
    $child = new Child (
        //$result_row['id'],
        null,
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['neighborhood'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['gender'],
        $result_row['school'],
        $result_row['grade'],
        $result_row['is_hispanic'],
        $result_row['race'],
        $result_row['last-child_medical_notes_'],
        $result_row['child_additional_notes_-name']
    );
    return $child;
}
  
// Find children gets children based in criteria in parameters, it builds the query based on what is in it and what isn't 
// More criteria can be added later
function find_children($last_name, $address, $city, $neighborhood, $school, $grade, $income, $isHispanic, $race) {
    // Build query
    $where = 'WHERE ';
    $first = true;
    // Add last name
    if ($last_name) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "dbChildren.last_name LIKE '%$last_name%'";
        $first = false;
    }
    // Add address
    if ($address) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "dbChildren.address LIKE '%$address%'";
        $first = false;
    }
    // Add city
    if ($city) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "dbChildren.city LIKE '%$city%'";
        $first = false;
    }
    // Add neighborhood
    if ($neighborhood) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "dbChildren.neighborhood LIKE '%$neighborhood%'";
        $first = false;
    }
    // Add school
    if ($school) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "school LIKE '%$school%'";
        $first = false;
    }
    // Add city
    if ($grade) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "grade LIKE '%$grade%'";
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
        $where .= ") ";
        $first = false;
    }
    // Add isHispanic
    if ($isHispanic) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "dbChildren.is_hispanic=1";
        $first = false;
    }
    // Add race
    if ($race) {
        if (!$first) {
            $where .= ' AND ';
        }
        $where .= "(";
        $last = end($race);
        // Go through each income in the input
        foreach ($race as $r) {
            if ($r != $last) {
                $where .= "dbChildren.race LIKE '%$r%' OR ";
            } else {
                $where .= "dbChildren.race LIKE '%$r%'";
            }
        }
        $where .= ")";
        $first = false;
    }
    if (!$first) {
        $where .= ' AND ';
    }
    $where .= " isArchived='0'";
    $query = "SELECT dbChildren.* from dbChildren INNER JOIN dbFamily ON dbChildren.family_id = dbFamily.id $where ORDER BY dbChildren.last_name";
    $connection = connect();
    // Execute query
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    // Get family data
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $children = [];
    foreach ($raw as $row) {
        if ($row['id'] == 'vmsroot') {
            continue;
        }
        $children []= make_a_child_from_database($row);
    }
    mysqli_close($connection);
    return $children;
}

//Function that retrieves a child from dbChildren based on first name, last name, and family id
function retrieve_child_by_firstName_lastName_famID($fn, $ln, $famID){
    $conn = connect();
    $query = "SELECT * FROM dbChildren WHERE first_name = '$fn' AND last_name = '$ln' AND family_id = '$famID';";
    $res = mysqli_query($conn, $query);
    if(mysqli_num_rows($res) < 0 || $res == null){
        mysqli_close($conn);
        return null;
    }else{
        $row = mysqli_fetch_assoc($res);
        return $row;
    } 
}
?>
