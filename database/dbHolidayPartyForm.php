<?php

include_once("dbinfo.php");

function insert_into_dbHolidayPartyForm($args, $child_id){
    $conn = connect();
    $email = $args['email'];
    $fn = $args['child_first_name'];
    $ln = $args['child_last_name'];
    $isAttending = $args['isAttending'];
    $transportation = $args['transportation'];
    $neighborhood = $args['neighborhood'];
    $comments = $args['question_comments'];

    //Find if child exists in dbHolidayPartForm first
    $query = "SELECT * FROM dbBrainBuildersHolidayPartyForm where child_first_name = '$fn' AND child_last_name = '$ln' AND child_id = '$child_id';";
    $result = mysqli_query($conn, $query);

    //If child doesn't exist
    if(mysqli_num_rows($result) == 0 || $result == null){
        mysqli_query($conn, "INSERT INTO dbBrainBuildersHolidayPartyForm (child_id, email, child_first_name, child_last_name,
        transportation, neighborhood, comments, isAttending) VALUES ('$child_id', '$email', '$fn', '$ln', '$transportation', '$neighborhood', '$comments', '$isAttending');");
        mysqli_close($conn);

        return true;
    }else { //if child already exists, don't insert
        mysqli_close($conn);

        return false;
    }
}