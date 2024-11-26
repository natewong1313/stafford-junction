<?php

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = null;

if(isset($_SESSION['_id'])){
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
    $successMessage = "";

} else {
    header('Location: login.php');
    die();
}

//necessary files

//Retrive and initialize volunteer data

//check if form is submitted
if($_SERVER['REQUEST_METHOD'] == "POST"){
    //sanitize form input
    $args = sanitize($_POST, null);
    $required = array();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("universal.inc")?>
    <title>Bus Monitor Attendance Form</title>
    <link rel="stylesheet" href="base.css">
</head>

<body>
    <h1>Bus Monitor Attendance Form</h1>
    <?php 
        if (isset($_GET['formSubmitFail'])) {
            echo '<div class="happy-toast" style="margin-right: 30rem; margin-left: 30rem; text-align: center;">Error Submitting Form</div>';
        }
    ?>

    <div id="formatted_form">

    <form id="busMonitorForm" action="busMonitorAttendanceForm.php" method="post">

        <!-- Route Selection -->
        <h2>Which route?</h2>

            <label><input type="radio" name="route" value="north" required> North</label><br>
            <label><input type="radio" name="route" value="south"> South</label><br><br>

<hr>

        <!-- Monday/Wednesday North -->
        <h2>Monday/Wednesday North</h2><br>

            <h3>1. Volunteers Present</h3>
            <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

            <h3>2. Foxwood</h3>
            <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

<hr>

        <!-- Monday/Wednesday South -->
            <h2>Monday/Wednesday South</h2><br>

                <h3>1. Volunteers Present</h3>
                <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

                <h3>2. Meadows</h3>
                <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

                <h3>3. Jefferson Place</h3>
                <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

                <h3>4. Olde Forge</h3>
                <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

                <h3>5. England Run</h3>
                <p>Check all that apply:</p>
                    <label><input type="checkbox" name="england_run[]" value="add_attendees_here"> Add attendees here as needed</label><br>
                    <label><input type="checkbox" name="volunteers_south[    ]" value="other"> Other: <input type="text" name="volunteers_south_other"    ></label><br><br>

        <!-- Submit and Cancel buttons -->
        <button type="submit" id="submit">Submit</button>
        <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
    </form>
        <?php
//if registration successful, create pop up notification and direct user back to login
            if($success){
                echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
            }  
        ?>
    </div>
    </body>
</html>

