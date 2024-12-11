<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHP-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<header>

    <?PHP
    //Log-in security
    //If they aren't logged in, display our log-in form.
    $showing_login = false;
    if (!isset($_SESSION['logged_in'])) {
        echo '
        <nav>
            <span id="nav-top">
                <span class="logo">
                    <img src="images/staffordjunction.png">
                    <span id="vms-logo">  </span>
                </span>
                <img id="menu-toggle" src="images/menu.png">
            </span>
            <ul>
                <li><a href="login.php">Log in</a></li>
            </ul>
        </nav>';
        //      <li><a href="register.php">Register</a></li>     was at line 35
    } else if ($_SESSION['logged_in']) {

        /*         * Set our permission array.
         * anything a guest can do, a volunteer and manager can also do
         * anything a volunteer can do, a manager can do.
         *
         * If a page is not specified in the permission array, anyone logged into the system
         * can view it. If someone logged into the system attempts to access a page above their
         * permission level, they will be sent back to the home page.
         */
        //pages guests are allowed to view
        $permission_array['index.php'] = 0;
        $permission_array['about.php'] = 0;
        $permission_array['apply.php'] = 0;
        $permission_array['logout.php'] = 0;
        $permission_array['register.php'] = 0;
        //pages family can view
        $permission_array['help.php'] = 1;
        $permission_array['dashboard.php'] = 1;
        $permission_array['calendar.php'] = 1;
        $permission_array['eventsearch.php'] = 1;
        $permission_array['changepassword.php'] = 1;
        $permission_array['editprofile.php'] = 1;
        $permission_array['inbox.php'] = 1;
        $permission_array['date.php'] = 1;
        $permission_array['event.php'] = 1;
        $permission_array['viewprofile.php'] = 1;
        $permission_array['viewnotification.php'] = 1;
        $permission_array['volunteerreport.php'] = 1;
        $permission_array['fillform.php'] = 1;
        $permission_array['familyaccountdashboard.php'] = 1;
        $permission_array['familyview.php'] = 1;
        $permission_array['childrenview.php'] = 1;
        $permission_array['childreninaccount.php'] = 1;
        $permission_array['childaccount.php'] = 1;
        $permission_array['completedforms.php'] = 1;
        $permission_array['holidaymealbagcomplete.php'] = 1;
        $permission_array['addchild.php'] = 1;
        $permission_array['forgotpassword.php'] = 1;
        //pages only staff can view
        $permission_array['personsearch.php'] = 2;
        $permission_array['personedit.php'] = 0; // changed to 0 so that applicants can apply
        $permission_array['viewschedule.php'] = 2;
        $permission_array['addweek.php'] = 2;
        $permission_array['log.php'] = 2;
        $permission_array['reports.php'] = 2;
        $permission_array['eventedit.php'] = 2;
        $permission_array['modifyuserrole.php'] = 2;
        $permission_array['addevent.php'] = 2;
        $permission_array['editevent.php'] = 2;
        $permission_array['roster.php'] = 2;
        $permission_array['report.php'] = 2;
        $permission_array['reportspage.php'] = 2;
        $permission_array['resetpassword.php'] = 2;
        $permission_array['addappointment.php'] = 2;
        $permission_array['addlocation.php'] = 2;
        $permission_array['viewservice.php'] = 2;
        $permission_array['viewlocation.php'] = 2;
        $permission_array['findfamily.php'] = 2;
        $permission_array['findchildren.php'] = 2;
        $permission_array['formsearch.php'] = 2;
        $permission_array['formsearchresult.php'] = 2;
        $permission_array['fillformstaff.php'] = 2;
        $permission_array['familysignupstaff.php'] = 2;
        //pages only admin can view
        $permission_array['createstaffaccount.php'] = 3;


        //Check if they're at a valid page for their access level.
        $current_page = strtolower(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1));
        $current_page = substr($current_page, strpos($current_page,"/"));
        
        if($permission_array[$current_page]>$_SESSION['access_level']){
            //in this case, the user doesn't have permission to view this page.
            //we redirect them to the index page.
            echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
            //note: if javascript is disabled for a user's browser, it would still show the page.
            //so we die().
            die();
        }
        //This line gives us the path to the html pages in question, useful if the server isn't installed @ root.
        //$path = /stafford-junction/
        $path = strrev(substr(strrev($_SERVER['SCRIPT_NAME']), strpos(strrev($_SERVER['SCRIPT_NAME']), '/')));
		$venues = array("portland"=>"RMH Portland");
        
        //they're logged in and session variables are set.
        if ($_SESSION['venue'] =="") { 
        	//echo(' <a href="' . $path . 'personEdit.php?id=' . 'new' . '">Apply</a>');
        	echo(' | <a href="' . $path . 'logout.php">Logout</a><br>');
        }
        else {
            //if the access level is superadmin, show the following navbar
            if($_SESSION['account_type'] == 'admin' || $_SESSION['account_type'] == 'staff'){
                echo('<nav>');
                echo('<span id="nav-top"><span class="logo"><a class="navbar-brand" href="' . $path . 'index.php"><img src="images/staffordjunction.png"></a>');
                echo('<a class="navbar-brand" id="vms-logo"></a></span><img id="menu-toggle" src="images/menu.png"></span>');
                echo('<ul>');
                //echo " <br><b>"."Gwyneth's Gift Homebase"."</b>|"; //changed: 'Homebase' to 'Gwyneth's Gift Homebase'

                echo('<li><a class="nav-link active" aria-current="page" href="' . $path . 'index.php">Home</a></li>');
                //echo('<span class="nav-divider">|</span>');

                echo('<li class="nav-item dropdown">');
                echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Families</a>');
                echo('<div class="dropdown-menu">');
                echo('<a class="dropdown-item" href="' . $path . 'findFamily.php">Search Family</a>');
                echo('<a class="dropdown-item" href="' . $path . 'familySignUpStaff.php">Add Family Account</a>');
                echo('<a class="dropdown-item" href="' . $path . 'formSearch.php">Reports</a>');

                if($_SESSION['access_level'] > 2){
                    echo('<li class="nav-item dropdown">');
                    echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Staff</a>');
                    echo('<div class="dropdown-menu" aria-labelledby="navbarDropdown">');
                    echo('<a class="dropdown-item" href="' . $path . 'createStaffAccount.php">Create Staff Account</a>');
                }
                echo('</div>');
                echo('</li>');

                //echo('<span class="nav-divider">|</span>');
                echo('<li class="nav-item dropdown">');
                echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Volunteers</a>');
                echo('<div class="dropdown-menu">');
                echo('<a class="dropdown-item" href="' . $path . '#">Search</a>
                    <a class="dropdown-item" href="#">Add</a>');
                echo('</div>');
                echo('</li>');

                echo('</div>');
                echo('</li>');

                //echo('<span class="nav-divider">|</span>');
                echo('<li class="nav-item dropdown">');
                echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Others</a>');
                echo('<div class="dropdown-menu">');
                echo('<a class="dropdown-item" href="' . $path . 'changePassword.php">Change Password</a>');

                echo('</div>');
                echo('</li>');
                echo('<li><a class="nav-link active" aria-current="page" href="' . $path . 'logout.php">Log out</a></li>');
                echo '</ul></nav>';
            //if the account type is a family account, show the following navbar
            }else if($_SESSION['account_type'] == 'family'){
                echo('<nav>');
                echo('<span id="nav-top"><span class="logo"><a class="navbar-brand" href="' . $path . 'familyAccountDashboard.php"><img src="images/staffordjunction.png"></a>');
                echo('<a class="navbar-brand" id="vms-logo"></a></span><img id="menu-toggle" src="images/menu.png"></span>');

                echo('<ul>');
                //Home page nav item
                echo('<li><a class="nav-link active" aria-current="page" href="' . $path . 'familyAccountDashboard.php">Home</a></li>');

                //view account
                echo('<li><a class="nav-link" aria-current="page" href="' . $path . 'familyView.php">View Account</a></li>');

                //view and add children to account
                echo('<li class="nav-item dropdown">');
                echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Children</a>');
                echo('<div class="dropdown-menu" aria-labelledby="navbarDropdown">');
                    echo('<a class="dropdown-item" href="' . $path . 'childrenInAccount.php">View Children</a>');
                    echo('<a class="dropdown-item" href="' . $path . 'addChild.php">Add Child</a>');
                echo('</div>');

                //enroll in program
                echo('<li class="nav-item dropdown">');
                echo('<a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Forms</a>');
                echo('<div class="dropdown-menu" aria-labelledby="navbarDropdown">');
                    echo('<a class="dropdown-item" href="' . $path . 'completedForms.php">View Completed Forms</a>');
                    echo('<a class="dropdown-item" href="' . $path . 'fillForm.php">Enroll In Program</a>');
                    //echo('<a class="dropdown-item" href="' . $path . '#">Fill Out waiver</a>');
                echo('</div>');
                echo('</li>');


                //logout
                echo('<li><a class="nav-link active" aria-current="page" href="' . $path . 'logout.php">Log out</a></li>');
                echo '</ul></nav>';

            }
        }
        
    }
    ?>
</header>
