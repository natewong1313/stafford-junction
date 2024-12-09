<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    
    ini_set("display_errors",1);
    error_reporting(E_ALL);

    // clear familyEmail and familyVerifed session variables in case user comes back from the forgot password flow
    unset($_SESSION['familyEmail']);
    unset($_SESSION['familyVerified']);

    // redirect to account respective homepage
    if (isset($_SESSION['_id'])) { //if this session variable is set
        //if the account type is a family, redirect to family account dashboard
        if($_SESSION['account_type'] == 'Family'){
            header("Location: familyAccountDashboard.php");
            die();
        }else {
            //otherwise, redirect to admin/staff dashboard
            header('Location: index.php');
            die();
        }
    }
    $badLogin = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        $ignoreList = array('password');
        $args = sanitize($_POST, $ignoreList);
        $required = array('username', 'password');
        if (wereRequiredFieldsSubmitted($args, $required)) {
            //used for admin
            require_once('domain/Person.php');
            require_once('database/dbPersons.php');
            require_once('database/dbMessages.php');
            
            //import family files
            require_once("domain/Family.php");
            require_once("database/dbFamily.php");

            //import staff files
            require_once("domain/Staff.php");
            require_once("database/dbStaff.php");

            // import volunteer files
            require_once("domain/Volunteer.php");
            require_once("database/dbVolunteers.php");

            dateChecker();
            $username = strtolower($args['username']);
            $password = $args['password'];
            //If the user is admin; original login code contained in this block
            if($args['account'] == 'admin'){
                $user = retrieve_person($username);
                if (!$user) {
                    $badLogin = true;
                } else if (password_verify($password, $user->get_password())) {
                    $changePassword = false;
                    if ($user->is_password_change_required()) {
                        $changePassword = true;
                        $_SESSION['logged_in'] = false;
                    } else {
                        $_SESSION['logged_in'] = true;
                    }
                    $_SESSION['f_name'] = $user->get_first_name();
                    $_SESSION['l_name'] = $user->get_last_name();
                    $_SESSION['venue'] = $user->get_venue();
                    $_SESSION['type'] = $user->get_type();
                    $_SESSION['_id'] = $user->get_id();
                    $_SESSION['account_type'] = 'admin';
                    // hard code root privileges
                    if ($user->get_id() == 'vmsroot') {
                        $_SESSION['access_level'] = 3;
                    }
                    if ($changePassword) {
                        $_SESSION['access_level'] = 0;
                        $_SESSION['change-password'] = true;
                        header('Location: changePassword.php');
                        die();
                    } else {
                        header('Location: index.php');
                        die();
                    }
                    die();
                } else {
                    $badLogin = true;
                }
            //If the user is a family account
            }else if($args['account'] == 'family'){ 
                //retrieve user by their email (aka the username they filled in at the login page)
                $user = retrieve_family_by_email($username);
                if(!$user){
                    $badLogin = true;
                }else if(password_verify($password, $user->getPassword())) { 
                    //set session variables
                    $_SESSION['logged_in'] = true;
                    $_SESSION['access_level'] = 1; //access level for family == 1
                    $_SESSION['_id'] = $user->getId();
                    $_SESSION['f_name'] = $user->getFirstName();
                    $_SESSION['l_name'] = $user->getLastName();
                    $_SESSION['account_type'] = "family";
                    $_SESSION['venue'] = "-"; //this session variable needs to be set to anything other than "", or else the header.php file won't run
                   
                    //redirect user to familyAccountDashboard page; this is a seperate home page just for family accounts, so it will include everything a family user could do
                    header("Location: familyAccountDashboard.php");

                }else {
                    $badLogin = true;       
                }
            }else if($args['account'] == 'staff'){ //if the account is a staff account
                $user = retrieve_staff($username); //grab staff user
                if(!$user){
                    $badLogin = true;
                }else if(password_verify($password, $user->getPassword())) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['access_level'] = 2; //access level for staff == 2
                    $_SESSION['_id'] = $user->getId();
                    $_SESSION['f_name'] = $user->getFirstName();
                    $_SESSION['l_name'] = $user->getLastName();
                    $_SESSION['account_type'] = "staff";
                    $_SESSION['venue'] = "-"; //this session variable needs to be set to anything other than "", or else the header.php file won't run

                    header('Location: index.php');
                }else {
                    $badLogin = true;
                }
            }else if($args['account'] == 'volunteer'){ //if the account is a staff account
                $user = retrieve_volunteer_by_email($username); //grab staff user
                if(!$user){
                    $badLogin = true;
                }else if(password_verify($password, $user->getPassword())) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['access_level'] = 3; //access level for volunteers == 3
                    $_SESSION['_id'] = $user->getId();
                    $_SESSION['f_name'] = $user->getFirstName();
                    $_SESSION['l_name'] = $user->getLastName();
                    $_SESSION['account_type'] = "volunteer";
                    $_SESSION['venue'] = "-"; //this session variable needs to be set to anything other than "", or else the header.php file won't run

                    header('Location: index.php');
                }
            } 
            
        }
    }
    //<p>Or <a href="register.php">register as a new volunteer</a>!</p>
    //Had this line under login button, took user to register page
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | Log In</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <main class="login">
            <h1>Stafford Junction Login</h1>
            <?php if (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">
                    Your registration was successful! Please log in below.
                </div>
            <?php elseif (isset($_GET['failedAccountCreate'])): ?>
                <div class="happy-toast">
                    Unable to create account, account already in system
                </div>
            <?php else: ?>
            <p>Welcome! Please log in below.</p>
            <?php endif ?>
            <form method="post">
                <?php
                    if ($badLogin) {
                        echo '<span class="error">No login with that e-mail and password combination currently exists.</span>';
                    }
                ?>
                <label for="account">Select Account Type</label>
                <select name="account" id="account">
                    <option value="admin">Admin</option>
                    <option value="family">Family</option>
                    <option value="staff">Staff</option>
                    <option value="volunteer">Volunteer</option>
                </select>

                <label for="username">Username</label>
        		<input type="text" name="username" placeholder="Enter your e-mail address" required>
        		<label for="password">Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
                <input type="submit" name="login" value="Log in">

            </form>
            <p></p>
            <p><a href="verifyEmail.php">Forgot Password?</a></p>
            <p>Don't have an account? Sign up <a href="familyAccount.php">here</a></p>
            <p>Looking for <a href="https://staffordjunction.org/">Stafford Junction's website</a>?</p>
        </main>
    </body>
</html>
