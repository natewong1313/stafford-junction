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
            <p>Placeholder...</p>
            <input type="text" id="answer" name="answer" placeholder="Answer" required>
            <input type="submit" id="submit" name="submit" value="Submit">
        </form>
        </main>
    </body>
</html>