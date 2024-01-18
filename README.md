# Old Dominion Humane Society (ODHS) Medicine Tracker
## Purpose
This project is the result of a semesters' worth of collaboration among UMW students. The goal of the project was to create a web application that the Old Dominion Humane Society organization could utilize to make it easier to manage animals and the medicines/medical treatments/vaccines that these animals recieve at the ODHS. At-a-glance features include a web-based calendar of events, event sign up, volunteer registration & login system, reporting system, basic notification system, animal registration/archiving/report generation, and location and service creation.

## Authors
The ODHS Medicine Tracker is based on an old open source project named "Homebase". [Homebase](https://a.link.will.go.here/) was originally developed for the Ronald McDonald Houses in Maine and Rhode Island by Oliver Radwan, Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker.

Modifications to the original Homebase code were made by the Fall 2022 semester's group of students. That team consisted of Jeremy Buechler, Rebecca Daniel, Luke Gentry, Christopher Herriott, Ryan Persinger, and Jennifer Wells.

A major overhaul to the existing system took place during the Spring 2023 semester, throwing out and restructuring many of the existing database tables. Very little original Homebase code remains. This team consisted of Lauren Knight, Zack Burnley, Matt Nguyen, Rishi Shankar, Alip Yalikun, and Tamra Arant. Every page and feature of the app was changed by this team.

The Gwyneth's Gifts VMS code was modified in the Fall of 2023, revamping the code into the present ODHS Medicine Tracker code. Many of the existing database tables were reused, and many other tables were added. Some portions of the software's functionality were reused from the Gwyneth's Gifts VMS code. Other functions were created to fill the needs of the ODHS Medicine Tracker. The team that made these modifications and changes consisted of Garrett Moore, Artis Hart, Riley Tugeau, Julia Barnes, Ryan Warren, and Collin Rugless.

## User Types
There are two types of users (also referred to as 'roles') within the ODHS.
* Admins
* SuperAdmins

SuperAdmins have the ability to manage users, generate reports, assign users to events, reset user passwords, and modify a user's status.

Admins have all of the abilities that SuperAdmins have, but they cannot modify other users information.

Users of any type can have their status changed by SuperAdmins to Inactive to prevent them from signing up for events. Inactive users will also stop appearing in the list of volunteers available to be assigned. Additionally, the reports page allows staff members to filter out inactive users.

There is also a root admin account with username 'vmsroot'. The default password for this account is 'vmsroot', but it must be changed upon initial log in. This account has hardcoded SuperAdmin privileges but cannot be assigned to events and does not have a user profile. It is crucial that this account be given a strong password and that the password be easily remembered, as it cannot easily be reset. This account should be used for system administration purposes only.

## Features
Below is an in-depth list of features that were implemented within the system
* User registration and log in
* Dashboard
* User Management
  * Change own password
  * View volunteer hours (print-friendly)
  * Modify profile
  * Modify user status
  * Modify user role (AKA access level) (SuperAdmin only)
  * Reset password
  * User search
* Appointments and Appointment Management
  * Calendar with appointment listings
  * Calendar day view with appointment listings
  * Appointment search
  * Appointment details page
  * Volunteer event sign up
  * Assign Volunteer to event
  * Attach event training media (links, pictures, videos)
  * Attach post-event media (Admin/SuperAdmin only)
  * View Appointment Roster (print-friendly)
  * Modify appointment details
  * Create new appointment
  * Delete appointment
  * Complete appointment
* Reports (print-friendly)
  * General Animal Reports
* Notification system, with notifications generated when
  * A user signs up for an event (sent to all staff members)
  * A user is assigned to an event by a staff member (sent to that volunteer)
  * A new event is created by a staff member (sent to all users)
  * An appointment is close
  * An appointment is due today
  * An appointment is overdue
* Animal Management
  * Create Animals
  * Modify Animals
  * Delete Animals
  * Archive Animals
  * Search Animals in the database
* Services
  * Create Service
  * Modify Service
  * Delete Service
* Locations
  * Create Location
  * Modify Location
  * Delete Location

## Design Documentation
Several types of diagrams describing the design of the ODHS Medicine Tracker, including sequence diagrams and use case diagrams, are available. Please contact Dr. Polack for access.

## "localhost" Installation
Below are the steps required to run the project on your local machine for development and/or testing purposes.
1. [Download and install XAMPP](https://www.apachefriends.org/download.html)
2. Open a terminal/command prompt and change directory to your XAMPP install's htdocs folder
  * For Mac, the htdocs path is `/Applications/XAMPP/xamppfiles/htdocs`
  * For Ubuntu, the htdocs path is `/opt/lampp/htdocs/`
  * For Windows, the htdocs path is `C:\xampp\htdocs`
3. Clone the ODHS Medicine Tracker repo by running the following command: 'https://github.com/crugless54/ODHS-Animal.git'
4. Start the XAMPP MySQL server and Apache server
5. Open the PHPMyAdmin console by navigating to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
6. Create a new database named `homebasedb`. With the database created, navigate to it by clicking on it in the lefthand pane
7. Import the `vms.sql` file located in `htdocs/ODHS-Animal/sql` into this new database
8. Create a new user by navigating to `Privileges -> New -> Add user account`
9. Enter the following credentials for the new user:
  * Name: `homebasedb`
  * Hostname: `Local`
  * Password: `homebasedb`
  * Leave everything else untouched
10. Navigate to [http://localhost/ODHS-Animal/](http://localhost/ODHS-Animal/) 
11. Log into the root user account using the username `vmsroot` with password `vmsroot`
12. Change the root user password to a strong password

Installation is now complete.

## Reset root user credentials
In the event of being locked out of the root user, the following steps will allow resetting the root user's login credentials:
1. Using the PHPMyAdmin console, delete the `vmsroot` user row from the `dbPersons` table
2. Clear the SiteGround dynamic cache [using the steps outlined below](#clearing-the-siteground-cache)
3. Navigate to gwyneth/insertAdmin.php. You should see a message that says `ROOT USER CREATION SUCCESS`
4. You may now log in with the username and password `vmsroot`

## Platform
Dr. Polack chose SiteGround as the platform on which to host the project. Below are some guides on how to manage the live project.

### SiteGround Dashboard
Access to the SiteGround Dashboard requires a SiteGround account with access. Access is managed by Dr. Polack.

### Localhost to Siteground
Follow these steps to transfter your localhost version of the ODHS Medicine Tracker code to Siteground. For a video tutorial on how to complete these steps, contact Dr. Polack.
1. Create an FTP Account on Siteground, giving you the necessary FTP credentials. (Hostname, Username, Password, Port)
2. Use FTP File Transfer Software (Filezilla, etc.) to transfer the files from your localhost folders to your siteground folders using the FTP credentials from step 1.
3. Create the following database-related credentials on Siteground under the MySQL tab:
  - Database - Create the database for the siteground version under the Databases tab in the MySQL Manager by selecting the 'Create Database' button. Database name is auto-generated and can be changed if you like.
  - User - Create a user for the database by either selecting the 'Create User' button under the Users tab, or by selecting the 'Add New User' button from the newly created database under the Databases tab. User name is auto-generated and can be changed  if you like.
  - Password - Created when user is created. Password is auto generated and can be changed if you like.
4. Access the newly created database by navigating to the PHPMyAdmin tab and selecting the 'Access PHPMyAdmin' button. This will redirect you to the PHPMyAdmin page for the database you just created. Navigate to the new database by selecting it from the database list on the left side of the page.
5. Select the 'Import' option from the database options at the top of the page. Select the 'Choose File' button and impor the "vms.sql" file from your software files.
  - Ensure that you're keeping your .sql file up to date in order to reduce errors in your Siteground code. Keep in mind that Siteground is case-sensitive, and your database names in the Siteground files must be identical to the database names in the database.
6. Navigate to the 'dbInfo.php' page in your Siteground files. Inside the connect() function, you will see a series of PHP variables. ($host, $database, $user, $pass) Change the server name in the 'if' statement to the name of your server, and change the $database, $user, and $pass variables to the database name, user name, and password that you created in step 3. 

### Clearing the SiteGround cache
There may occasionally be a hiccup if the caching system provided by SiteGround decides to cache one of the application's pages in an erroneous way. The cache can be cleared via the Dashboard by navigating to Speed -> Caching on the lefthand side of the control panel, choosing the DYNAMIC CACHE option in the center of the screen, and then clicking the Flush Cache option with a small broom icon under Actions.

## External Libraries and APIs
The only outside library utilized by the ODHS Medicine Tracker is the jQuery library. The version of jQuery used by the system is stored locally within the repo, within the lib folder. jQuery was used to implement form validation and the hiding/showing of certain page elements.

## Potential Improvements
Below is a list of improvements that could be made to the system in subsequent semesters.
* The system could generate emails and send them to users (would require access to an @odhs.com email address)
  * For user email verification
  * For password reset
  * For nofications/messages received (see below)
* The notification system could be turned into a full-fledged messaging system
  * The existing dbMessages table is set up to allow this
* Reports
  * Additional reports could be added
  * Visual components could be added (graphs)
* If a better webhosting option was chosen, file upload for pictures and documents would be better than having to use outside resources such as Google Docs or imgur for file upload

## License
The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).

## Acknowledgements
Thank you to Dr. Polack for the chance to work on this exciting project. A lot of love went into making it!
