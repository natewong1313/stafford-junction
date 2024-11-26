<?php
    session_cache_expire(30);
    session_start();
    //import family files
    require_once("domain/Family.php");
    require_once("database/dbFamily.php");
    require_once('include/input-validation.php');
    $user = null;

    // Get user security question
    $question = null;
    if (isset($_SESSION['familyID'])) {
        $user = retrieve_family_by_id($_SESSION['familyID']);
        // Variable to hold security question
        $question = $user->getSecurityQuestion();
    } else {
        header('Location: login.php');
        die();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $args = sanitize($_POST, null);
        $required = array('answer');
        if ($user) {
            // Get answers
            $answer = $user->getSecurityAnswer();
            $enteredAnswer = $_POST['answer'];
            // Check if answer user submitted matches answer in database
            if (password_verify($enteredAnswer, $answer)) {
                // Set familyVerified session variable which is used to access the forgotPassword.php page
                $_SESSION['familyVerified'] = true;
                header('Location: forgotPassword.php');
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
        <form id="security-questions" method="post">
            <p>Security Question</p>
            <?php
                // Display security question
                echo "<p>$question</p>";
            ?>
            <input type="text" id="answer" name="answer" placeholder="Answer" required>
            <input type="submit" id="submit" name="submit" value="Submit">
        </form>
        </main>
    </body>
</html>