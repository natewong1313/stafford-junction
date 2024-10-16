<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Change Password</title>
    </head>
    <body>
        <?php
            // Variable to hold security question, add way to retrieve the question from dbPersons
            $question = null;
            // Get answer
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $answer = $_POST['answer'];
                // Next two lines is used for testing, add a way to check if user submits the correct answer before going to next page
                header('Location: forgotPassword.php');
                die();
            }
        ?>

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