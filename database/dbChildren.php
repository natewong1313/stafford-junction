<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Children.php');

function make_a_child($result_row){
    $child = new Child (
        $result_row['id'],
        $result_row['first_name'],
        $result_row['last_name'],
        $result_row['dob'],
        $result_row['gender'],
        $result_row['medical_notes'],
        $result_row['notes']
    );
    return $child;
}

function retrieve_children_by_id($id){
    $conn = connect();
    $query = "SELECT dbchildren.* FROM dbchildren INNER JOIN dbfamily ON dbchildren.family_id = dbfamily.id WHERE dbfamily.id = '" . $id . "';";
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


?>