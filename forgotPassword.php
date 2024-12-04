<?php
    session_cache_expire(30);
    session_start();
    require_once('include/api.php');
    //import family files
    require_once("domain/Family.php");
    require_once("database/dbFamily.php");
    require_once('include/input-validation.php');
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    $accessLevel = 0;
    $userID = null;

    // If familyID and familyVerified session variables are set, get id and continue
    if (isset($_SESSION['familyID']) && isset($_SESSION['familyVerified'])) {
        $userID = $_SESSION['familyID'];
    } else if (isset($_GET['id']) && (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2)) {
        $userID = $_GET['id'];
    } else {
        // Else go back to login page
        header('Location: login.php');
        die();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!wereRequiredFieldsSubmitted($_POST, array('new-password'))) {
            echo "Args missing";
            die();
        }

        $newPassword = $_POST['new-password'];
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        change_family_password($userID, $hash);
        // If logged in as staff, go to family view page
        if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
            header('Location: familyView.php?pcSuccess&id=' . $_GET['id']);
            die();
        } else {
            // Else, go to index page
            header('Location: index.php?pcSuccess');
            die();
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Change Password</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Change Password</h1>
        <main class="login">
            <p>New password must be eight or more characters in length and include least one special character (e.g., ?, !, @, #, $, &, %)</p>
            <form id="password-change" method="post">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" pattern="^(?=.*[^a-zA-Z0-9].*).{8,}$" title="Password must be eight or more characters in length and include least one special character (e.g., ?, !, @, #, $, &, %)" placeholder="Enter new password" required>
                <label for="reenter-new-password">New Password</label>
                <input type="password" id="new-password-reenter" placeholder="Re-enter new password" required>
                <p id="password-match-error" class="error hidden">Passwords must match!</p>
                <input type="submit" id="submit" name="submit" value="Change Password">
                <?php
                    // Show an extra button that takes the user to the family view page if they are logged in as staff
                    if (isset($_GET['id']) && (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2)) {
                        echo '<a class="button cancel button_stlye" href="familyView.php?id=' . $_GET['id'] . '"  style="margin-top: 1rem;">Return to Family View</a>';
                    }
                ?>
            </form>
        </main>
    </body>
</html>