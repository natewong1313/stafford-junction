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
    // Get date?
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <title>Stafford Junction | Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Stafford Junction Dashboard</h1>
        <main class='dashboard'>
            <?php if (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['deleteService'])): ?>
                <div class="happy-toast">Service successfully removed!</div>
            <?php elseif (isset($_GET['serviceAdded'])): ?>
                <div class="happy-toast">Service successfully added!</div>
            <?php elseif (isset($_GET['animalRemoved'])): ?>
                <div class="happy-toast">Animal successfully removed!</div>
            <?php elseif (isset($_GET['locationAdded'])): ?>
                <div class="happy-toast">Location successfully added!</div>
            <?php elseif (isset($_GET['deleteLocation'])): ?>
                <div class="happy-toast">Location successfully removed!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php endif ?>
            <p>Welcome back, <?php echo $person->get_first_name() ?>!</p>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <?php
                    require_once('database/dbMessages.php');
                    $unreadMessageCount = get_user_unread_count($person->get_id());
                    $inboxIcon = 'inbox.svg';
                    if ($unreadMessageCount) {
                        $inboxIcon = 'inbox-unread.svg';
                    }
                ?>
                <div class="dashboard-item" data-link="inbox.php">
                    <img src="images/<?php echo $inboxIcon ?>">
                    <span>Notifications<?php 
                        if ($unreadMessageCount > 0) {
                            echo ' (' . $unreadMessageCount . ')';
                        }
                    ?></span>
                </div>
                <div class="dashboard-item" data-link="calendar.php">
                    <img src="images/view-calendar.svg">
                    <span>View Calendar</span>
                </div>
                <?php if ($_SESSION['access_level'] >= 2): ?>
                    <div class="dashboard-item" data-link="addEvent.php">
                        <img src="images/new-event.svg">
                        <span>Add Appointment</span>
                    </div>
                <?php endif ?>
				<div class="dashboard-item" data-link="addAnimal.php">
                    <img src="images/settings.png">
                    <span>Add Animal</span>
                </div>
				<div class="dashboard-item" data-link="addService.php">
                    <img src="images/settings.png">
                    <span>Add Service</span>
                </div>
				<div class="dashboard-item" data-link="addLocation.php">
                    <img src="images/settings.png">
                    <span>Add Location</span>
                </div>
                <div class="dashboard-item" data-link="findAnimal.php">
                        <img src="images/person-search.svg">
                        <span>Find Animal</span>
                </div>
                <!-- Commenting out because volunteers won't be searching events
                <div class="dashboard-item" data-link="eventSearch.php">
                    <img src="images/search.svg">
                    <span>Find Event</span>
                </div>
                -->
                <?php if ($_SESSION['access_level'] >= 2): ?>
                    <div class="dashboard-item" data-link="personSearch.php">
                        <img src="images/person-search.svg">
                        <span>Find Volunteer</span>
                    </div>
                    <div class="dashboard-item" data-link="register.php">
                        <img src="images/add-person.svg">
                        <span>Register Volunteer</span>
                    </div>
                    <div class="dashboard-item" data-link="viewArchived.php">
                        <img src="images/person-search.svg">
                        <span>Archived Animals</span>
                    </div>
                    <div class="dashboard-item" data-link="report.php">
                        <img src="images/create-report.svg">
                        <span>Create Report</span>
                    </div>
                <?php endif ?>
                <?php if ($notRoot) : ?>
                    <div class="dashboard-item" data-link="viewProfile.php">
                        <img src="images/view-profile.svg">
                        <span>View Profile</span>
                    </div>
                    <div class="dashboard-item" data-link="editProfile.php">
                        <img src="images/manage-account.svg">
                        <span>Edit Profile</span>
                    </div>
                <?php endif ?>
                <?php if ($notRoot) : ?>
                    <div class="dashboard-item" data-link="volunteerReport.php">
                        <img src="images/volunteer-history.svg">
                        <span>View My Hours</span>
                    </div>
                <?php endif ?>

                <!--New Dashboard items-->
                <?php if($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="findAnimal.php">
                        <img src="images/person-search.svg">
                        <span>Find Family Account</span>
                </div>
                <?php endif ?>

                <!--Dashboard button that directs the user to the forms page-->
                <div class="dashboard-item" data-link="fillForm.php">
                    <img src="images/form-dropdown-svgrepo-com.svg">
                    <span>Fill Out Form</span>
                </div>
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