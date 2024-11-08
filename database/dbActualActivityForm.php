<?php
/**
 * Function that takes actual activity form item and adds it into database
 */
function createActualActivityForm($form, $connection) {
	mysqli_begin_transaction($connection);
    
    $activity = $form["activity"];
    $date = $form["date"];
    $program = $form["program"];
	$start_time = $form["start_time"];
    $end_time = $form["end_time"];
    $start_mile = $form["start_mile"];
    $end_mile = $form["end_mile"];
    $address = $form["address"];
	$attend_num = $form["attend_num"];
    $volstaff_num = $form["volstaff_num"];
    $materials_used = $form["materials_used"];
	$meal_info = $form["meal_info"];
    $act_costs = $form["act_costs"];
    $act_benefits = $form["act_benefits"];
    $attendees = $form["attendees[]"];
    
    $query = "
        INSERT INTO dbActualActivity (activity, date, program, start_time, end_time, start_mile, end_mile, address, 
        attend_num, volstaff_num, materials_used, meal_info, act_costs, act_benefits)
        VALUES ('$activity', '$date', '$program', '$start_time', '$end_time', '$start_mile', '$end_mile', '$address', 
        '$attend_num', '$volstaff_num', '$materials_used', '$meal_info', '$act_costs', '$act_benefits')
    ";
    
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_rollback($connection);
        mysqli_close($connection);
        return null;
    }
    
    $activityID = mysqli_insert_id($connection);
    
    $attendeeIDs = createAttendees($attendees, $connection);
    if ($attendeeIDs === null) {
        mysqli_rollback($connection);
        mysqli_close($connection);
        return null;
    }

    foreach ($attendeeIDs as $attendeeID) {
        $insert_query = "
            INSERT INTO dbActivityAttendees (activityID, attendeeID)
            VALUES ('$activityID', '$attendeeID')
        ";
        $insert_result = mysqli_query($connection, $insert_query);
        if (!$insert_result) {
            mysqli_rollback($connection);
            mysqli_close($connection);
            return null;
        }
    }

    mysqli_commit($connection);
    mysqli_close($connection);
    return $activityID;
}

/**
 * Function that takes attendees array item and adds it into database
 */
function createAttendees($attendees, $connection) {
    mysqli_begin_transaction($connection);
    $ids = [];

    foreach ($attendees as $attendee) {
        $name = trim($attendee);
        if ($name != '') {
            $insert_query = "INSERT INTO dbAttendees (name) VALUES ('$name')";
            $insert_result = mysqli_query($connection, $insert_query);
            if (!$insert_result) {
                mysqli_rollback($connection);
                mysqli_close($connection);
                return null;
            }
            $ids[] = mysqli_insert_id($connection);
        }
    }

    mysqli_commit($connection);
    return $ids;
}