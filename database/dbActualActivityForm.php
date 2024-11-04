<?php
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/actualActivityForm.php');

/**
 * Function that takes actualActivity item and adds it to database
 */
function create_actualAct($actualAct) {
    $connection = connect();
	$activity = $actualAct["activity"];
    $program = $actualAct["program"];
	$start_time = $actualAct["start_time"];
    $end_time = $actualAct["end_time"];
    $start_mile = $actualAct["start_mile"];
    $end_mile = $actualAct["end_mile"];
    $address = $actualAct["address"];
	$attend_num = $actualAct["attend_num"];
    $volstaff_num = $actualAct["volstaff_num"];
    $materials_used = $actualAct["materials_used"];
	$mealinfo = $actualAct["mealinfo"];
    $act_costs = $actualAct["act_costs"];
    $act_benefits = $actualAct["act_benefits"];
    $attendance[] = $actualAct["attendance[]"];
    
    $query = "
        INSERT INTO dbActualActivityForm (odhs_id, name, breed, age, gender, notes, spay_neuter_done, spay_neuter_date, rabies_given_date, rabies_due_date, heartworm_given_date, heartworm_due_date, distemper1_given_date, distemper1_due_date, distemper2_given_date, distemper2_due_date, distemper3_given_date, distemper3_due_date, microchip_done, archived)
        values ('$odhsid','$name', '$breed', '$age', '$gender', '$notes', '$spay_neuter_done', '$spay_neuter_date', '$rabies_given_date', '$rabies_due_date', '$heartworm_given_date', '$heartworm_due_date', '$distemper1_given_date', '$distemper1_due_date', '$distemper2_given_date', '$distemper2_due_date', '$distemper3_given_date', '$distemper3_due_date', '$microchip_done', 'no')
    ";
    
    //EDIT: needs to get actualAct primary key
    //EDIT: might need to be wrapped in if else clause in case data is not put into dbActualActivityForm
    $last_id = $conn->insert_id;
       
    //EDIT: insert each attendee in attendance array into Attendees table, if attendee name is not blank
    foreach ($attendance as $attendee) {
        //sanitize the attendee name
        if ($attendee != '') {
            $sanAttendee = trim($attendee);
            $query = "
                INSERT INTO Attendees (name, activity_id) values ('$sanAttendee', '$lastid')
            ";
        }
    }

    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}