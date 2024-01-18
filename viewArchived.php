<?php

function displaySearchRow($animal){
    echo "
    <tr>
        <td><a href='animal.php?id=".$animal->get_id()."'>" . $animal->get_name() . "</a></td>
        <td>" . $animal->get_breed() . "</td>
        <td>" . $animal->get_age() . "</td>
        <td>" . $animal->get_gender() . "</td>
        <td>" . $animal->get_spay_neuter_done() . "</td>
        <td>" . $animal->get_microchip_done() . "</td>";
    echo "</tr>";
}

    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
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
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Archived Animals</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Archived Animals</h1>
        <form id="animal-search" class="general" method="get">
            <?php 
                require_once('include/input-validation.php');
                require_once('database/dbAnimals.php');
                    $animals = find_archived();
                    require_once('include/output.php');
                    if (count($animals) > 0) {
                        echo '
                        <div class="table-wrapper">
                            <table class="general">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Breed</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Spay/Neuter</th>
                                        <th>Microchipped</th>';
                                    echo '</tr>
                                </thead>
                                <tbody class="standout">';
                        $mailingList = '';
                        $notFirst = false;
                        foreach ($animals as $animal) {
                            displaySearchRow($animal);
                        }
                        echo '
                                </tbody>
                            </table>
                        </div>';
                    } else {
                        echo '<div class="error-toast">There are no archived animals.</div>';
                    }
            ?>
            <p></p>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </form>
    </body>
</html>