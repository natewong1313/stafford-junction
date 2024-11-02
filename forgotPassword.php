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

    if (isset($_SESSION['familyEmail']) && isset($_SESSION['familyVerified'])) {
        $userID = $_SESSION['familyEmail'];
    } else {
        header('Location: login.php');
        die();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!wereRequiredFieldsSubmitted($_POST, array('new-password'))) {
            echo "Args missing";
            die();
        }

        $newPassword = $_POST['new-password'];
        $user = retrieve_family_by_email($userID);
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        change_family_password($userID, $hash);
        header('Location: index.php?pcSuccess');
        die();
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
            <form id="password-change" method="post">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>
                <label for="reenter-new-password">New Password</label>
                <input type="password" id="new-password-reenter" placeholder="Re-enter new password" required>
                <p id="password-match-error" class="error hidden">Passwords must match!</p>
                <input type="submit" id="submit" name="submit" value="Change Password">
            </form>
        </main>
    </body>
</html>