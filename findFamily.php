<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
// admin-only access
if ($accessLevel < 2) {
    header('Location: index.php');
    die();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once("database/dbFamily.php");
    require_once("domain/Family.php");
    require_once("include/input-validation.php");

    $args = sanitize($_POST, null);

    if($args["search-method"] == "last-name"){
        //will either hold one or mutliple family objects depending on the last name
        $family = retrieve_family_by_lastName($args['search']);
    }else if($args['search-method'] == "email"){
        //retrieve family by email
        $family = retrieve_family_by_email($args['search']);
    }

    

}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Find Family Account</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Search Family Account</h1>

        <form id="formatted_form" method="POST">
            <label for="search-method" style="text-align: center;">Search By</label>
            <select name="search-method" style="width: 30%; margin-left: 35%; margin-bottom:10px;">
                <option value="last-name">Last Name</option>
                <option value="email">Email</option>
            </select>

            <input type="text" name="search" style="width: 30%; margin-left: 35%;" placeholder="Enter data to search for">
            
            <button type="submit" style="margin-bottom: 20px; width: 30%; margin-left: 35%;">Search</button>

            <?php
            if(isset($family)){
                echo '<h3>Account Summary</h3>';
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Acct ID</th>
                                <th>Name</th>
                                <th>Birthdate</th>
                                <th>Address</th>
                                <th>city</th>
                                <th>state</th>
                                <th>zip</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>Emergency Contact</th>
                                <th>Emergency Phone</th>';
                                //a href=familyView.php?id=' . $id . 
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        foreach($family as $acct){
                            echo '<tr>';
                            echo '<td><a href=familyView.php?id=' . $acct->getID() . '>' . $acct->getID() . '</a></td>';
                            echo '<td>' . $acct->getFirstName() . " " . $acct->getLastName() . '</td>';
                            echo '<td>' . $acct->getBirthDate() . '</td>';
                            echo '<td>' . $acct->getAddress() . '</td>';
                            echo '<td>' . $acct->getCity() . '</td>';
                            echo '<td>' . $acct->getState() . '</td>';
                            echo '<td>' . $acct->getZip() . '</td>';
                            echo '<td>' . $acct->getEmail() . '</td>';
                            echo '<td>' . $acct->getPhone() . '</td>';
                            echo '<td>' . $acct->getEContactFirstName() . " " . $acct->getEContactLastName() . '</td>';
                            echo '<td>' . $acct->getEContactPhone() . '</td>';
                            echo '<tr>';
                        }
                        
                echo '
                        </tbody>
                    </table>
                </div>';
            }

            ?>
        </form>
     
        <a class="button cancel" href="index.php"">Return to Dashboard</a>
     
        

        
        
    </body>
</html>