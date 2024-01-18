<?php 
/**
 * @version April 6, 2023
 * @author Alip Yalikun
 */


  session_cache_expire(30);
  session_start();
  ini_set("display_errors",1);
  error_reporting(E_ALL);
  $loggedIn = false;
  $accessLevel = 0;
  $userID = null;
  if (isset($_SESSION['_id'])) {
      $loggedIn = true;
      // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
      $accessLevel = $_SESSION['access_level'];
      $userID = $_SESSION['_id'];
  }

  require_once('include/input-validation.php');
  require_once('database/dbPersons.php');
  require_once('database/dbEvents.php');
  require_once('include/output.php');
  require_once('database/dbinfo.php');
  
  

  if(isset($_GET['animal'])){
    $selected_animal_name = $_GET['animal'];
    $connection = connect();
    $query = "select * from dbAnimals where name = '$selected_animal_name'";
    $result = mysqli_query($connection, $query);
    $animal_info = mysqli_fetch_assoc($result);
} else {
    echo "No animal selected!";
}
  
  // Is user authorized to view this page?
  if ($accessLevel < 2) {
      header('Location: index.php');
      die();
  }
  

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Report Result</title>
        <style>
            table {
                margin-top: 1rem;
                margin-left: auto;
                margin-right: auto;
                border-collapse: collapse;
                width: 80%;
            }
            td {
                border: 1px solid #333333;
                text-align: left;
                padding: 8px;
            }
            th {
                background-color: var(--main-color);
                color: var(--button-font-color);
                border: 1px solid #333333;
                text-align: left;
                padding: 8px;
		        font-weight: 500;
            }
          
            tr:nth-child(even) {
                background-color: #f0f0f0;
                /* color:var(--button-font-color); */
		
            }

            @media print {
                tr:nth-child(even) {
                    background-color: white;
                }

                button, header {
                    display: none;
                }

                :root {
                    font-size: 10pt;
                }

                label {
                    color: black;
                }

                table {
                    width: 100%;
                }

                a {
                    color: black;
                }
            }

            .theB{
                width: auto;
                font-size: 15px;
            }
	        .center_a {
                margin-top: 0;
		        margin-bottom: 3rem;
                margin-left:auto;
                margin-right:auto;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .8rem;
            }
            .center_b {
                margin-top: 3rem;
                display: flex;
                align-items: center;
                justify-content: center;
		        gap: .8rem;
            }
            #back-to-top-btn {
                bottom: 20px;
            }
            .back-to-top:visited {
                color: white; /* sets the color of the link when visited */  
            }
            .back-to-top {
                color: white; /* sets the color of the link when visited */  
            }
	    .intro {
                display: flex;
                flex-direction: column;
                gap: .5rem;
                padding: 0 0 0 0;
            }
	    @media only screen and (min-width: 1024px) {
                .intro{
                    width: 80%;
                }
                main.report {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
            }
        footer {
            margin-bottom: 2rem;
        }
    </style>

    </head>
    <body>
  	<?php require_once('header.php') ?>
    <?php
        $animal_name = $animal_info['name'];
        $animal_breed = $animal_info['breed'];
        $animal_age = $animal_info['age'];
        $animal_id = $animal_info['odhs_id'];
        $animal_gender = $animal_info['gender'];
        $animal_spay_neuter = $animal_info['spay_neuter_done'];
        $animal_microchip = $animal_info['microchip_done'];
        $animal_rabies = (($animal_info['rabies_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['rabies_given_date'])) : "");            
        $animal_rabies_due = (($animal_info['rabies_due_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['rabies_due_date'])) : "");            
        $animal_heartworm = (($animal_info['heartworm_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['heartworm_given_date'])) : "");            
        $animal_heartworm_due = (($animal_info['heartworm_due_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['heartworm_due_date'])) : "");            
        $animal_distemper1 = (($animal_info['distemper1_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper1_given_date'])) : "");
        $animal_distemper1_due = (($animal_info['distemper1_due_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper1_due_date'])) : "");
        $animal_distemper2 = (($animal_info['distemper2_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper2_given_date'])) : "");
        $animal_distemper2_due = (($animal_info['distemper2_due_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper2_due_date'])) : "");
        $animal_distemper3 = (($animal_info['distemper3_given_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper3_given_date'])) : "");
        $animal_distemper3_due = (($animal_info['distemper3_due_date'] != "0000-00-00") ? date('F j, Y', strtotime($animal_info['distemper3_due_date'])) : "");
        $animal_notes = $animal_info['notes'];
            ?> 
        <h1>Report Result</h1>
        <main class="report">
	   <div class="intro">
        <div>
            <label>Animal Name:</label>
            <span>
            <?php echo $animal_name; ?>
            </span>
            <label>Animal Age:</label>
            <span>
            <?php echo $animal_age; ?>
            </span>
            <label>Animal Breed:</label>
            <span>
            <?php echo $animal_breed; ?>
            </span>
            <label>Animal Gender:</label>
            <span>
            <?php echo $animal_gender; ?>
            </span>
            <label>Animal Notes:</label>
            <span>
            <?php echo $animal_notes; ?>
            </span>
        </div>

	<div>
             <label>Medical:</label>
             <span>
             <table align = 'left'>
             <tbody>
                    <tr>	
                        <td class="label">Spayed/Neutered </td>
                        <td><?php echo $animal_spay_neuter?></td>     		
                    </tr>
                    <tr>	
                        <td class="label">Microchipped </td>
                        <td><?php echo $animal_microchip?></td>
                    </tr>
                    <tr>	
                        <td class="label">Rabies given </td>
                        <td><?php echo $animal_rabies?></td>
                    </tr>
                    <tr>
                    <td class="label">Rabies due </td>
                    <td><?php echo $animal_rabies_due?></td>
                    </tr>
                    <tr>	
                        <td class="label">Heartworm test given</td>
                        <td><?php echo $animal_heartworm?></td>
                    </tr>
                    <tr>
                        <td class="label">Heartworm due</td>
                        <td><?php echo $animal_heartworm_due?></td>
                    </tr>
                    <tr>	
                        <td class="label">Distemper 1 given </td>
                        <td><?php echo $animal_distemper1?></td>
                    </tr>
                    <tr>
                        <td class="label">Distemper 1 due </td>
                        <td><?php echo $animal_distemper1_due?></td>
                    </tr>
                    <tr>	
                        <td class="label">Distemper 2 given </td>
                        <td><?php echo $animal_distemper2?></td>
                    </tr>
                    <tr>
                        <td class="label">Distemper 2 due </td>
                        <td><?php echo $animal_distemper2_due?></td>
                    </tr>
                    <tr>	
                        <td class="label">Distemper 3 given </td>
                        <td><?php echo $animal_distemper3?></td>
                    </tr>
                    <tr>
                        <td class="label">Distemper 3 due </td>
                        <td><?php echo $animal_distemper3_due?></td>
                    </tr>
                    </tbody>
            </table>   
             </span>
         </div>

	
    </main>
	<div class="center_a">
                <a href="report.php">
                <button class = "theB">New Report</button>
                </a>
                <a href="index.php">
                <button class = "theB">Home Page</button>
                </a>
	</div>
        </main>
    </body>
</html>
