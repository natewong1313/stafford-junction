<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;

// Search criteria variables
// Search criteria variables
$last_name = null;
$email = null;
$neighborhood = null;
$address = null;
$city = null;
$zip = null;
$income = null;
$assistance = null;
$is_archived = 0;

require_once("database/dbFamily.php");
require_once("domain/Family.php");
// Get all families if no criteria inputted in search
$family = find_families($last_name, $email, $neighborhood, $address, $city, $zip, $income, $assistance, $is_archived);

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
    require_once("include/input-validation.php");
    $args = sanitize($_POST, null);
    // Get criteria if set
    if (isset($args['last-name'])) {
        $last_name = $args['last-name'];
    }
    if (isset($args['email'])) {
        $email = $args['email'];
    }
    if (isset($args['neighborhood'])) {
        $neighborhood = $args['neighborhood'];
    }
    if (isset($args['address'])) {
        $address = $args['address'];
    }
    if (isset($args['city'])) {
        $city = $args['city'];
    }
    if (isset($args['zip'])) {
        $zip = $args['zip'];
    }
    if (isset($args['income'])) {
        $income = $args['income'];
    }
    if (isset($args['assistance'])) {
        if ($args['assistance'] != "") {
            $assistance = preg_split("/\s*,\s*/", $args['assistance']);
        }
    }
    if (isset($args['is-archived'])) {
        $is_archived = $args['is-archived'];
    }
    // Find families based on set criteria
    $family = find_families($last_name, $email, $neighborhood, $address, $city, $zip, $income, $assistance, $is_archived);
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
        <label>Select any criteria to search for or fill none to view all families</label>
            <!-- Search Criteria Fields -->
            <div class="search-container">
                <div class="search-label">
                <label>Last Name:</label>
                </div>
                <div>
                <input type="text" id="last-name" name='last-name'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Email:</label>
                </div>
                <div>
                <input type="text" id="email" name='email'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Neighborhood:</label>
                </div>
                <div>
                <input type="text" id="neighborhood" name='neighborhood'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Address:</label>
                </div>
                <div>
                <input type="text" id="address" name='address'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>City:</label>
                </div>
                <div>
                <input type="text" id="city" name='city'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Zip:</label>
                </div>
                <div>
                <input type="number" id="zip" name='zip'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Current Assistance:</label>
                </div>
                <div>
                <input type="text" id="assistance" name='assistance'>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Estimated Household Income:</label>
                </div>
                <div>
                <select id="income" name="income[]" multiple>
                        <option value="Under $15,0000">Under 20,000</option>
                        <option value="$15,000 - $24,999">20,000 - 40,000</option>
                        <option value="$25,000 - $34,999">40,001 - 60,000</option>
                        <option value="$35,000 - $49,999">60,001 - 80,000</option>
                        <option value="$100,000 and above">Over 80,000</option>
                    </select>
                </div>
            </div>
            <div class="search-container">
                <div class="search-label">
                <label>Archived:</label>
                </div>
                <div>
                <input type="checkbox" id="is-archived" name='is-archived' value=1>
                </div>
            </div>
            
            <button type="submit" class="button_style">Search</button>

            <?php
            if(isset($family)){
                echo '<h3>Account Summary</h3>';
                echo '<p>Click on an Account ID to view or edit that family\'s account.</p>';
                echo '
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Account ID</th>
                                <th>Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Email</th>
                                <th>Phone</th>';
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
                            echo '<tr>';
                            
                        }
                        
                echo '
                        </tbody>
                    </table>
                </div>';
            }

            ?>
        </form>
     
        <a class="button cancel button_style"  href="index.php"">Return to Dashboard</a>
     

    </body>
</html>