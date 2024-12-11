<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    include_once('domain/Staff.php');
    include_once('database/dbStaff.php');

    //if the $_SESSION _id variable is set, check what kind of account
    if (isset($_SESSION['_id'])) {
        //if the account type is admin, call retrieve_person to grab from dbPersons
        if($_SESSION['account_type'] == 'admin'){
            $person = retrieve_person($_SESSION['_id']);
            $notRoot = $person->get_id() != 'vmsroot'; //gets set to true if the user didn't log in as vmsroot
        }else if($_SESSION['account_type'] == 'Family'){ //if the account is a family account, simply redirect to the familyAccount dashboard page
            header("Location: familyAccountDashboard.php");
        }else if($_SESSION['account_type'] == 'staff'){
            //staff login
            $staff = retrieve_staff_by_id($_SESSION['_id']);
        }
    }

    

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <title>Stafford Junction | Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <?php $acct = '';
            if($_SESSION['account_type'] == 'staff'){
                $acct = 'Staff';
            }else {
                $acct = 'Admin';
            }
        ?>
        <h1>Stafford Junction <?php echo $acct ?> Dashboard</h1>
        <main class='dashboard'>
            <?php if (isset($_GET['addStaffSuccess'])): ?>
                <?php echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Staff account created!</div>';?>
            <?php elseif (isset($_GET['formSubmitSuccess'])): ?>
                <?php echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Form submitted successfully!</div>';?>
            <?php elseif (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php elseif (isset($_GET['familyRegisterSuccess'])): ?>
                <div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Family Account Registration Successful!</div>
            <?php elseif (isset($_GET['updateSuccess'])): ?>
                <div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Family Profile Updated!</div>
            <?php elseif (isset($_GET['failedAccountCreate'])): ?>
                <div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">
                    Unable to create account, account already in system!
                </div>
            <?php endif ?>
            
            <?php if(isset($staff)): ?>
            <p>Welcome back, <?php echo $staff->getFirstName();?>
            <?php endif ?>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
           
            <div id="dashboard">
                <!--New Dashboard items-->
                <?php if($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="findFamily.php">
                    <img src="images/person-search.svg">
                    <span>Find Family Account</span>
                </div>
                <?php endif ?>

                <?php if($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="findChildren.php">
                    <img src="images/person-search.svg">
                    <span>Find Children</span>
                </div>
                <?php endif ?>

                <?php if($_SESSION['access_level'] >= 2 || $_SESSION['account_type'] == 'admin'): ?>
                <div class="dashboard-item" data-link="formSearch.php">
                    <img src="images/form-dropdown-svgrepo-com.svg">
                    <span>View Form Submissions</span>
                </div>
                <?php endif ?>
              
                <?php if($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="fillFormStaff.php">
                    <img src="images/form-dropdown-svgrepo-com.svg">
                    <span>Fill Out Attendance Forms</span>
                </div>
                <?php endif ?>

                <!--Dashboard button that directs the admin user to staff account create page-->
                <?php if($_SESSION['account_type'] == 'admin'): ?>
                <div class="dashboard-item" data-link="createStaffAccount.php">
                    <img src="images/staffUsers.svg">
                    <span>Create Staff Account</span>
                </div>
                <?php endif ?>

                <?php if($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="familySignUpStaff.php">
                    <img src="images/family-svgrepo-com.svg">
                    <span>Create Family Account</span>
                </div>
                <?php endif ?>

                <div class="dashboard-item" data-link="changePassword.php">
                    <img src="images/change-password.svg">
                    <span>Change Password</span>
                </div>
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>
