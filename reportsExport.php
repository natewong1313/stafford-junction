<?php
/*
 * Copyright 2013 by Jerrick Hoang, Ivy Xing, Sam Roberts, James Cook, 
 * Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/*
 * dataSearch page for RMH homebase.
 * @author Johnny Coster
 * @version April 2, 2012
 */
session_start();
session_cache_expire(30);
?>
<html>
<head>
<title>Search for data objects</title>
<link rel="stylesheet" href="styles.css" type="text/css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

</head>
<body>
<div id="container"><?php include_once('header.php'); ?>
<div id="content"><?php
include_once('domain/Person.php');
include_once('database/dbPersons.php');

if ($_POST['_form_submit'] != 1 && $_POST['_form_submit'] != 2 && $_POST['_form_submit'] != 3)
include('dataSearch.inc.php'); // the form has not been submitted, so show it

process_form();
//pull_shift_data();
include('footer.inc');

function process_form() {

	if ($_POST['_form_submit'] == 1) {
		error_log("exporting data step 1");
		$select_all_regexp = ".";
		if(!isset($_POST['first_name']) || $_POST['first_name'] == "") $_SESSION['first_name'] = $select_all_regexp;
		else $_SESSION['first_name'] = $_POST['first_name'];

		if(!isset($_POST['last_name']) || $_POST['last_name'] == "") $_SESSION['last_name'] = $select_all_regexp;
		else $_SESSION['last_name'] = $_POST['last_name'];

		if(!isset($_POST['gender']) || $_POST['gender'] == "") $_SESSION['gender'] = $select_all_regexp;
		else $_SESSION['gender'] = $_POST['gender'];

		if(!isset($_POST['type'])) $_SESSION['type'] = array();
		else $_SESSION['type'] = $_POST['type'];

		if(!isset($_POST['status']) || $_POST['status'] == "") $_SESSION['status'] = $select_all_regexp;
		else $_SESSION['status'] = $_POST['status'];

		if(!isset($_POST['start_date']) || $_POST['start_date'] == "") $_SESSION['start_date'] = $select_all_regexp;
		else $_SESSION['start_date'] = $_POST['start_date'];

		if(!isset($_POST['city']) || $_POST['city'] == "") $_SESSION['city'] = $select_all_regexp;
		else $_SESSION['city'] = $_POST['city'];

		if(!isset($_POST['zip']) || $_POST['zip'] == "") $_SESSION['zip'] = $select_all_regexp;
		else $_SESSION['zip'] = $_POST['zip'];

		if(!isset($_POST['phone']) || $_POST['phone'] == "") $_SESSION['phone'] = $select_all_regexp;
		else $_SESSION['phone'] = $_POST['phone'];

		if(!isset($_POST['email']) || $_POST['email'] == "") $_SESSION['email'] = $select_all_regexp;
		else $_SESSION['email'] = $_POST['email'];

		error_log("first name = ".$_SESSION['first_name']);
		error_log("last name = ".$_SESSION['last_name']);
		error_log("gender = ".$_SESSION['gender']);
		foreach ($_SESSION['type'] as $t) error_log("type selected ".$t);
		error_log("status = ".$_SESSION['status']);
		error_log("start date = ".$_SESSION['start_date']);
		error_log("city = ".$_SESSION['city']);
		error_log("zip = ".$_SESSION['zip']);
		error_log("phone = ".$_SESSION['phone']);
		error_log("email = ".$_SESSION['email']);
		$result = get_people_for_export("*", $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['gender'], $_SESSION['type'],
		$_SESSION['status'], $_SESSION['start_date'], $_SESSION['city'], $_SESSION['zip'],
		$_SESSION['phone'], $_SESSION['email']);
		$returned_people = array();

		while ($result_row = mysqli_fetch_assoc($result)) {
			$person = make_a_person($result_row);
			$returned_people[] = $person;
		}
		$_SESSION['returned_people'] = serialize($returned_people);
		error_log("returns ".count($_SESSION['returned_people']). "people");
		include('dataResults.inc.php');
	} else if ($_POST['_form_submit'] == 2) {
		error_log("Exporting data step 2");
		$_SESSION['results'] = $_POST['results_list'];
		if ($_POST['all_export']) {
			$export_people = array();
			error_log("returns ".count(unserialize($_SESSION['returned_people'])). "people");
			foreach(unserialize($_SESSION['returned_people']) as $p) {
				$export_people[] = $p->get_id();
				error_log("Exporting data for " .$p->get_id());
			}
			error_log("Exporting all data.");
			$_SESSION['selected_people'] = $export_people;
			include('dataExport.inc.php');
		}
		else if ($_POST['b_export']) {
			error_log("Exporting selected data");
			if ($_POST['results_list']) {
				$_SESSION['selected_people'] = $_POST['results_list'];
				foreach ($_POST['results_list'] as $export_person) {
					$temp_dude = retrieve_person($export_person);
					error_log("Exporting data for ". $temp_dude->get_first_name() . " " . $temp_dude->get_last_name());
				}
			}
			include('dataExport.inc.php');
		}
	} else if ($_POST['_form_submit'] == 3) {
		error_log("Exporting data step 3");
		$_POST['export_attr'][] = 'id';
		$all_attrs_concat = implode(", ", $_POST['export_attr']);
		echo $all_attrs_concat;
		error_log("All attributes = " .$all_attrs_concat);
		foreach ($_POST['export_attr'] as $attr) { error_log("attr to be exported ".$attr); }
		
		$result = get_people_for_export($all_attrs_concat, $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['gender'],
										$_SESSION['type'], $_SESSION['status'], $_SESSION['start_date'], $_SESSION['city'], 
										$_SESSION['zip'], $_SESSION['phone'], $_SESSION['email']);
		
		$export_data = array();
		while ($result_row = mysqli_fetch_assoc($result)) {
			if (in_array($result_row['id'], $_SESSION['selected_people'])){
				$temp_person = array($result_row['id']);
				foreach($result_row as $row) {
					if (!isset($row) || $row == "") $row = "";
					$temp_person[] = $row;
				}
				$export_data[] = array_slice($temp_person,0,count($temp_person)-1);
			}
		}
		date_default_timezone_set('America/New_York');
        $current_time = array("Export date: " . date("F j, Y, g:i a"));
		export_data($current_time, array_merge(array("id"),$_POST['export_attr']), $export_data);
	}
}

function export_data($current_time, $search_attr, $export_data) {
	$filename = "dataexport.csv";
	$handle = fopen($filename, "w");
	fputcsv($handle, $current_time);
	fputcsv($handle, $search_attr, ',');
	foreach ($export_data as $person_data) 
	   if (count($person_data)>1 && $person_data[1]!="") // anything more than the id, export it, otherwise skip it
	       fputcsv($handle, $person_data, ',','"');
	if (in_array("history",$search_attr)) { // split history into several lines per person 
	   $people_in_past_shifts = get_all_peoples_histories();
	   foreach ($people_in_past_shifts as $p=>$history) 
	        fputcsv($handle, array($p,$history),',','"');  
	}
	fclose($handle);
}

?></div>
</div>
        <?PHP include('footer.inc'); ?>
</body>
</html>
