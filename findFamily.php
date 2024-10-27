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

    $family = retrieve_family($args); //retrieves family by email for now (may change late)

}
?>

!<!DOCTYPE html>
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
            <label for="email">Account Email</label>
            <input type="text" name="email" required placeholder="Email">
            <button type="submit" style="margin-bottom: 20px">Search</button>

            <?php
            if(isset($family)){
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Birthdate</th>
                                <th>Address</th>
                                <th>city</th>
                                <th>state</th>
                                <th>zip</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>phoneType</th>
                                <th>Emergency Contact</th>
                                <th>Emergency Contact Phone</th>
                                <th>accountType</th>';
                            echo '</tr>
                        </thead>
                        <tbody class="standout">';
                        echo '<tr>';
                        echo '<td>' . $family->getFirstName() . '</td>';
                        
                        

                echo '
                        </tbody>
                    </table>
                </div>';
            }

            ?>
        </form>

        
        
    </body>
</html>