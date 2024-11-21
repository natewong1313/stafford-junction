<?php
/**
 * Function that takes actual activity form and adds data into correct data tables
 */
function createActualActivityForm($form) {
	$connection = connect();
    
    $activity = mysqli_real_escape_string($connection, $form["activity"]);
    $date = $form["date"];
    $program = mysqli_real_escape_string($connection, $form["program"]);
	$start_time = $form["start_time"];
    $end_time = $form["end_time"];
    $start_mile = $form["start_mile"];
    $end_mile = $form["end_mile"];
    $address = mysqli_real_escape_string($connection, $form["address"]);
	$attend_num = $form["attend_num"];
    $volstaff_num = $form["volstaff_num"];
    $materials_used = mysqli_real_escape_string($connection, $form["materials_used"]);
	$meal_info = $form["meal_info"];
    $act_costs = mysqli_real_escape_string($connection, $form["act_costs"]);
    $act_benefits = mysqli_real_escape_string($connection, $form["act_benefits"]);
    $attendance = $form["attendance"];
    
    mysqli_begin_transaction($connection);
    $activityID = null;
    try {
        //insert information into database for Actual Activity Form
        $query = "
            INSERT INTO dbActualActivityForm (activity, date, program, start_time, end_time, start_mile, end_mile, address, 
            attend_num, volstaff_num, materials_used, meal_info, act_costs, act_benefits)
            VALUES ('$activity', '$date', '$program', '$start_time', '$end_time', '$start_mile', '$end_mile', '$address', 
            '$attend_num', '$volstaff_num', '$materials_used', '$meal_info', '$act_costs', '$act_benefits')
        ";
        
        $result = mysqli_query($connection, $query);
        if (!$result) {
            throw new Exception("Error in query: " . mysqli_error($connection));
        }
        
        $activityID = mysqli_insert_id($connection);
        
        //insert attendance into database for Actual Activity Attendees
        if (isset($attendance)) {
            $attendeeIDs = createActualActivityAttendees($attendance, $connection);
            if (empty($attendeeIDs)) {
                throw new Exception("Error in data insert (dbActualActivityAttendees): " . mysqli_error($connection));
            }
        } else {
            throw new Exception("Error: No attendance variable transfered from form.");
        }

        //insert into junction table
        $activityAttendeeID = createActivityAttendees($activityID, $attendeeIDs, $connection);
        if (empty($activityAttendeeID)) {
            throw new Exception("Error in data insert (dbActvityAttendees): " . mysqli_error($connection));
        }

        mysqli_commit($connection);
    } catch (Exception $e) {
        echo $e->getMessage();
        mysqli_rollback($connection);
        mysqli_close($connection);
        return null;
    }

    mysqli_close($connection);
    return $activityID;
}

/**
 * Function that takes attendees array and adds each attendee into database table
 */
function createActualActivityAttendees($attendance, $connection) {
    mysqli_begin_transaction($connection);
    $ids = [];

    foreach ($attendance as $attendee) {
        $name = trim($attendee);
        $name = mysqli_real_escape_string($connection, $name);
        if ($name != '') {
            try {
                $insert_query = "
                INSERT INTO dbActualActivityAttendees (name) VALUES ('$name')
            ";
            $insert_result = mysqli_query($connection, $insert_query);
                if (!$insert_result) {
                    throw new Exception("Error in query: " . mysqli_error($connection));
                }
            } catch (Exception $e) {
                echo $e->getMessage();
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

/**
 * Function that takes actual activity form ID and attendees IDs array and adds it into junction table
 */
function createActivityAttendees($activityID, $attendeeIDs, $connection) {
    mysqli_begin_transaction($connection);

    foreach ($attendeeIDs as $attendeeID) {
        try {
        $insert_query = "
            INSERT INTO dbActivityAttendees (activityID, attendeeID)
            VALUES ('$activityID', '$attendeeID')
        ";
        $insert_result = mysqli_query($connection, $insert_query);
            if (!$insert_result) {
                throw new Exception("Error in query: " . mysqli_error($connection));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            mysqli_rollback($connection);
            mysqli_close($connection);
            return null;
        }
    }

    mysqli_commit($connection);
    return true;  // Return success as true
}