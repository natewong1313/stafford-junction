<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Change Password</title>
    </head>
    <body>
        <?php
            // Get email
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                // Next two lines is used for testing, add a way to check if user submits a valid email before going to next page
                header('Location: securityQuestions.php');
                die();
            }
        ?>

        <?php require_once('header.php') ?>
        <h1>Change Password</h1>
        <main class="login">
        <form id="get-email" method="post">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>
            <input type="submit" id="submit" name="submit" value="Submit">
        </form>
        </main>
    </body>
</html>