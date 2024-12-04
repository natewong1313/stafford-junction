<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Volunteer.php');

// creates a volunteer instance from a db result
function create_volunteer_from_db($result_row)
{
    return new Volunteer(
        $result_row['id'],
        $result_row['email'],
        $result_row['password'],
        $result_row['securityQuestion'],
        $result_row['securityAnswer'],
        $result_row['firstName'],
        $result_row['middleInitial'],
        $result_row['lastName'],
        $result_row['address'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['homePhone'],
        $result_row['cellPhone'],
        $result_row['age'],
        $result_row['birthDate'],
        $result_row['hasDriversLicense'],
        $result_row['transportation'],
        $result_row['emergencyContact1Name'],
        $result_row['emergencyContact1Relation'],
        $result_row['emergencyContact1Phone'],
        $result_row['emergencyContact2Name'],
        $result_row['emergencyContact2Relation'],
        $result_row['emergencyContact2Phone'],
        $result_row['allergies'],
        $result_row['sunStart'],
        $result_row['sunEnd'],
        $result_row['monStart'],
        $result_row['monEnd'],
        $result_row['tueStart'],
        $result_row['tueEnd'],
        $result_row['wedStart'],
        $result_row['wedEnd'],
        $result_row['thurStart'],
        $result_row['thurEnd'],
        $result_row['friStart'],
        $result_row['friEnd'],
        $result_row['satStart'],
        $result_row['satEnd'],
        $result_row['dateAvailable'],
        $result_row['minHours'],
        $result_row['maxHours']
    );
}

// adds a volunteer instance into the db
function add_volunteer($volunteer)
{
    $conn = connect();
    $query = "SELECT * FROM dbVolunteers WHERE email = '" . $volunteer->getEmail() . "'";
    $result = mysqli_query($conn, $query);

    if ($result == null || mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO dbVolunteers (
            email, password, securityQuestion, securityAnswer, firstName, middleInitial, lastName, 
            address, city, state, zip, homePhone, cellPhone, age, birthDate, hasDriversLicense, 
            transportation, emergencyContact1Name, emergencyContact1Relation, emergencyContact1Phone, 
            emergencyContact2Name, emergencyContact2Relation, emergencyContact2Phone, allergies, 
            sunStart, sunEnd, monStart, monEnd, tueStart, tueEnd, wedStart, wedEnd, thurStart, 
            thurEnd, friStart, friEnd, satStart, satEnd, dateAvailable, minHours, maxHours
        ) VALUES (
            '" . $volunteer->getEmail() . "',
            '" . $volunteer->getPassword() . "',
            '" . $volunteer->getSecurityQuestion() . "',
            '" . $volunteer->getSecurityAnswer() . "',
            '" . $volunteer->getFirstName() . "',
            '" . $volunteer->getMiddleInitial() . "',
            '" . $volunteer->getLastName() . "',
            '" . $volunteer->getAddress() . "',
            '" . $volunteer->getCity() . "',
            '" . $volunteer->getState() . "',
            '" . $volunteer->getZip() . "',
            '" . $volunteer->getHomePhone() . "',
            '" . $volunteer->getCellPhone() . "',
            '" . $volunteer->getAge() . "',
            '" . $volunteer->getBirthDate() . "',
            '" . ($volunteer->getHasDriversLicense() ? 1 : 0) . "',
            '" . $volunteer->getTransportation() . "',
            '" . $volunteer->getEmergencyContact1Name() . "',
            '" . $volunteer->getEmergencyContact1Relation() . "',
            '" . $volunteer->getEmergencyContact1Phone() . "',
            '" . $volunteer->getEmergencyContact2Name() . "',
            '" . $volunteer->getEmergencyContact2Relation() . "',
            '" . $volunteer->getEmergencyContact2Phone() . "',
            '" . $volunteer->getAllergies() . "',
            '" . $volunteer->getSunStart() . "',
            '" . $volunteer->getSunEnd() . "',
            '" . $volunteer->getMonStart() . "',
            '" . $volunteer->getMonEnd() . "',
            '" . $volunteer->getTueStart() . "',
            '" . $volunteer->getTueEnd() . "',
            '" . $volunteer->getWedStart() . "',
            '" . $volunteer->getWedEnd() . "',
            '" . $volunteer->getThuStart() . "',
            '" . $volunteer->getThuEnd() . "',
            '" . $volunteer->getFriStart() . "',
            '" . $volunteer->getFriEnd() . "',
            '" . $volunteer->getSatStart() . "',
            '" . $volunteer->getSatEnd() . "',
            '" . $volunteer->getDateAvailable() . "',
            '" . $volunteer->getMinHours() . "',
            '" . $volunteer->getMaxHours() . "'
        );";

        mysqli_query($conn, $query);
        mysqli_close($conn);
        return true;
    }

    mysqli_close($conn);
    return false;
}

// get a volunteer by email
function retrieve_volunteer_by_email($email)
{
    $conn = connect();
    $query = "SELECT * FROM dbVolunteers WHERE email = '" . $email . "';";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) < 1 || $result == null) {
        mysqli_close($conn);
        return null;
    } else {
        $row = mysqli_fetch_assoc($result);
        $volunteer = create_volunteer_from_db($row);
        mysqli_close($conn);
        return $volunteer;
    }
}

// get a volunteer by id
function retrieve_volunteer_by_id($id)
{
    $conn = connect();
    $query = "SELECT * FROM dbVolunteers WHERE id = '" . $id . "';";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) < 1 || $result == null) {
        mysqli_close($conn);
        return null;
    } else {
        $row = mysqli_fetch_assoc($result);
        $volunteer = create_volunteer_from_db($row);
        mysqli_close($conn);
        return $volunteer;
    }
}

function change_volunteer_password($id, $newPass) {
    $con=connect();
    $query = 'UPDATE dbVolunteers SET password = "' . $newPass . '" WHERE id = "' . $id . '"';
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}