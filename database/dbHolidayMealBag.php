<?php

require_once("dbinfo.php");
require_once("dbFamily.php");

//Function that retrieves the data from the holiday meal bag form for a particular family based on family id
function get_data_by_family_id($id){
    $conn = connect();
    $query = "SELECT * FROM dbHolidayMealBagForm WHERE family_id = '" . $id . "';";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) < 0 || $res == null){
        return null;
    }else {
        $row = mysqli_fetch_assoc($res);
        return $row; //return the data as an associative array;
    }

}

function getHolidayMealBagSubmissions() {
    $conn = connect();
    $query = "SELECT * FROM dbHolidayMealBagForm INNER JOIN dbFamily ON dbFamily.id = dbHolidayMealBagForm.family_id;";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    mysqli_close($conn);
    return [];
}

function getHolidayMealBagSubmissionsById($familyId) {
    $conn = connect();
    $query = "SELECT * FROM dbHolidayMealBagForm INNER JOIN dbFamily ON dbFamily.id = dbHolidayMealBagForm.family_id WHERE dbHolidayMealBagForm.family_id='" . $familyId . "';";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $submissions;
    }
    mysqli_close($conn);
    return [];
}