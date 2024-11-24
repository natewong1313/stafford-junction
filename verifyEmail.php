<?php
    session_cache_expire(30);
    session_start();
    //import family files
    require_once("domain/Family.php");
    require_once("database/dbFamily.php");
    require_once('include/input-validation.php');

    // Get email
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        $args = sanitize($_POST, null);
        $required = array('email');
        $email = $args['email'];
        if (wereRequiredFieldsSubmitted($args, $required)) {
            $email = strtolower($args['email']);
            $user = retrieve_family_by_email($email);
            if ($user) {
                // Set familyID session variable to hold the id of the account
                $_SESSION['familyID'] = $user->getID();
                header('Location: securityQuestions.php');
                die();
            }
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
        <form id="get-email" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <input type="submit" id="submit" name="submit" value="Submit">
        </form>
        </main>
    </body>
</html>