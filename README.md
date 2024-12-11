# Stafford Junction Client Management Software

## Purpose

The purpose of this document is to outline the system to be used by Stafford Junction. This document shall serve as a reference for the system’s functionality, and outline all features and desired requirements as given by Stafford Junction. This will serve as a guide for Stafford Junction to understand how to navigate and use the software to its maximum capability.

## Authors

The Stafford Junction Client Management Software is based on an old open source project named "Homebase". [Homebase](https://a.link.will.go.here/) was originally developed for the Ronald McDonald Houses in Maine and Rhode Island by Oliver Radwan, Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker.

Modifications to the original Homebase code were made by the Fall 2022 semester's group of students. That team consisted of Jeremy Buechler, Rebecca Daniel, Luke Gentry, Christopher Herriott, Ryan Persinger, and Jennifer Wells.

A major overhaul to the existing system took place during the Spring 2023 semester, throwing out and restructuring many of the existing database tables. Very little original Homebase code remains. This team consisted of Lauren Knight, Zack Burnley, Matt Nguyen, Rishi Shankar, Alip Yalikun, and Tamra Arant. Every page and feature of the app was changed by this team.

The Gwyneth's Gifts VMS code was modified in the Fall of 2023, revamping the code into the present ODHS Medicine Tracker code. Many of the existing database tables were reused, and many other tables were added. Some portions of the software's functionality were reused from the Gwyneth's Gifts VMS code. Other functions were created to fill the needs of the ODHS Medicine Tracker. The team that made these modifications and changes consisted of Garrett Moore, Artis Hart, Riley Tugeau, Julia Barnes, Ryan Warren, and Collin Rugless.

The ODHS Medicine Tracker code underwent further updates and refinements to rebase the software into a client management system. The team responsible for these recent changes consisted of Selby Heyman, Ian MacLeod, Maria Peralta, Brandon Rogers, Anna Swann, Josh Williams, and Nathan Wong.

## User Types

There are four types of users (also referred to as 'roles') within Stafford Junction.

- Admins
- Staff
- Family
- Volunteer

Admins have the ability to perform all actions as a staff member, as well as create staff acccounts.

Staff have the ability to find, view, and edit family accounts and children information, as well as fill out forms for families, fill out staff-only forms, generate reports, manually create a family accounts, manually update a family's account information, and archive/unarchive family accounts.

Families have the ability to enroll in programs, fill out waivers and forms, view and edit their own family account, and add children to their account. 

Volunteer functionality is not currently in a working state.

There is also a root admin account with username 'vmsroot'. The default password for this account is 'vmsroot', but it must be changed upon initial log in. This account has hardcoded admin privileges but cannot be assigned to events and does not have a user profile. It is crucial that this account be given a strong password and that the password be easily remembered, as it cannot easily be reset. This account should be used for system administration purposes only.

## Features

Below is an in-depth list of features that were implemented within the system

- User registration and log in
- Dashboard
- User Management
  - Reset password
  - Modify profile
  - Modify user status
  - User search
  - Add children
- Reports (print-friendly)
  - General Event Program Reports
  - Exportable by CSV
- Forms
  - Holiday Meal Bag Form
  - School Supplies Form
  - Spring Break Form
  - Angel Gifts Wish Form
  - Child Care Waiver Form
  - Field Trip Waiver Form
  - Program Interest Form
  - Brain Builders Student Registration Form
  - Brain Builders Holiday Party Form
  - Summer Junction Registration Form


## Design Documentation

Several types of diagrams describing the design of the Stafford Junction Client Management Software, including sequence diagrams and use case diagrams, are available. Please contact Dr. Polack for access.

## "localhost" Installation

Below are the steps required to run the project on your local machine for development and/or testing purposes.

1. [Download and install XAMPP](https://www.apachefriends.org/download.html)
2. Open a terminal/command prompt and change directory to your XAMPP install's htdocs folder

- For Mac, the htdocs path is `/Applications/XAMPP/xamppfiles/htdocs`
- For Ubuntu, the htdocs path is `/opt/lampp/htdocs/`
- For Windows, the htdocs path is `C:\xampp\htdocs`

3. Clone the Stafford Junction Client Management Software repo by running the following command: 'git clone https://github.com/natewong1313/stafford-junction.git'
4. Start the XAMPP MySQL server and Apache server
5. Open the PHPMyAdmin console by navigating to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
6. Create a new database named `staffordjunctionmd`. With the database created, navigate to it by clicking on it in the lefthand pane
7. Import the `staffordjunctionmd.sql` file located in `htdocs/stafford-junction/sql` into this new database
8. Create a new user by navigating to `Privileges -> New -> Add user account`
9. Enter the following credentials for the new user:

- Name: `staffordjunctionmd`
- Hostname: `Local`
- Password: `staffordjunctionmd`
- Leave everything else untouched

10. Navigate to [http://localhost/ODHS-Animal/](http://localhost/stafford-junction/)
11. Log into the root user account using the username `vmsroot` with password `vmsroot`
12. Change the root user password to a strong password

Installation is now complete.

## Platform

Dr. Polack chose SiteGround as the platform on which to host the project. Below are some guides on how to manage the live project.

### SiteGround Dashboard

Access to the SiteGround Dashboard requires a SiteGround account with access. Access is managed by Dr. Polack.

### Localhost to Siteground

Follow these steps to transfer your localhost version of the Stafford Junction Client Management Software code to Siteground. For a video tutorial on how to complete these steps, contact Dr. Polack.

1. Create an FTP Account on Siteground, giving you the necessary FTP credentials. (Hostname, Username, Password, Port)
2. Use FTP File Transfer Software (Filezilla, etc.) to transfer the files from your localhost folders to your siteground folders using the FTP credentials from step 1.
3. Create the following database-related credentials on Siteground under the MySQL tab:

- Database - Create the database for the siteground version under the Databases tab in the MySQL Manager by selecting the 'Create Database' button. Database name is auto-generated and can be changed if you like.
- User - Create a user for the database by either selecting the 'Create User' button under the Users tab, or by selecting the 'Add New User' button from the newly created database under the Databases tab. User name is auto-generated and can be changed if you like.
- Password - Created when user is created. Password is auto generated and can be changed if you like.

4. Access the newly created database by navigating to the PHPMyAdmin tab and selecting the 'Access PHPMyAdmin' button. This will redirect you to the PHPMyAdmin page for the database you just created. Navigate to the new database by selecting it from the database list on the left side of the page.
5. Select the 'Import' option from the database options at the top of the page. Select the 'Choose File' button and import the "vms.sql" file from your software files.

- Ensure that you're keeping your .sql file up to date in order to reduce errors in your Siteground code. Keep in mind that Siteground is case-sensitive, and your database names in the Siteground files must be identical to the database names in the database.

6. Navigate to the 'dbInfo.php' page in your Siteground files. Inside the connect() function, you will see a series of PHP variables. ($host, $database, $user, $pass) Change the server name in the 'if' statement to the name of your server, and change the $database, $user, and $pass variables to the database name, user name, and password that you created in step 3.

### Clearing the SiteGround cache

There may occasionally be a hiccup if the caching system provided by SiteGround decides to cache one of the application's pages in an erroneous way. The cache can be cleared via the Dashboard by navigating to Speed -> Caching on the lefthand side of the control panel, choosing the DYNAMIC CACHE option in the center of the screen, and then clicking the Flush Cache option with a small broom icon under Actions.

## External Libraries and APIs

The only outside library utilized by the Stafford Junction Client Management Software is the jQuery library. The version of jQuery used by the system is stored locally within the repo, within the lib folder. jQuery was used to implement form validation and the hiding/showing of certain page elements.

## Potential Improvements

Below is a list of improvements that could be made to the system in subsequent semesters.

- The system could generate emails and send them to users
  - For user email verification
  - For password reset
  - For form submission confirmation
- Reports
  - Additional reports could be added
  - Visual components could be added (graphs)
- Volunteer management
- Forms
  - Finish Brain Builders Review Form
  - Unenroll from program
  - Viewing completed forms
  - Editing completed forms
  - Finish auto populate for all forms
  - Make some forms publishable only for a period of time

## License

The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).

## Acknowledgements

Thank you to Dr. Polack for the chance to work on this exciting project. A lot of love went into making it!
