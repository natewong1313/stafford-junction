<?php

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

function createBrainBuildersRegistrationForm($form) {
    $connection = connect();

    $family_id = $_SESSION['_id'];
    $first_name = $form["first_name"];
    $last_name = $form["last_name"];
    $address = $form["address"];
	$city = $form["city"];
    $neighborhood = $form["neighborhood"];
    $state = $form["state"];
    $zip = $form["zip"];
    $cell_phone = $form["cell_phone"];
    $home_phone = $form["home_phone"];
    $email = $form["email"];
    $child_num = $form["child_num"];
    $child_ages = $form["child_ages"];
    $adult_num = $form["adult_num"];
	$id = null;



}


    function isBrainBuildersRegistrationFormComplete($childID) {
        $connection = connect();
    
        $query = "SELECT * FROM dbAngelGiftForm INNER JOIN dbChildren ON dbAngelGiftForm.child_id = dbChildren.id WHERE dbChildren.id = $childID";
        $result = mysqli_query($connection, $query);
        if (!$result->num_rows > 0) {
            mysqli_close($connection);
            return false;
        } else {
            mysqli_close($connection);
            return true;
        }
    }
    ?>