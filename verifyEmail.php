<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Change Password</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Change Password</h1>
        <main class="login">
        <form id="get-email" method="post">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>
            <input type="submit" id="submit" name="submit" value="Email">
        </form>
        </main>
    </body>
</html>