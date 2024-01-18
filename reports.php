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
 * reports page for RMH homebase.
 * @author Jerrick Hoang
 * @version 11/5/2013
 */
session_cache_expire(30);
session_start();

include_once('database/dbinfo.php');
include_once('database/dbPersons.php');
include_once('domain/Person.php');
include_once('database/dbEvents.php');

include_once('database/dbShifts.php');
include_once('domain/Shift.php');
?>

<html>
<head>
<!-- <link rel="stylesheet" href="lib\bootstrap\css\bootstrap.css" type="text/css" /> -->
<!-- <link rel="stylesheet" href="styles.css" type="text/css" /> -->
<!-- <link rel="stylesheet" href="lib/jquery-ui.css" /> -->
<script type="text/javascript" src="lib/jquery-1.9.1.js"></script>
<script src="lib/jquery-ui.js"></script>
<script>
$(function() {
	$( "#from" ).datepicker({dateFormat: 'y-mm-dd',changeMonth:true,changeYear:true});
	$( "#to" ).datepicker({dateFormat: 'y-mm-dd',changeMonth:true,changeYear:true});

	$(document).on("keyup", ".volunteer-name", function() {
		var str = $(this).val();
		var target = $(this);
		$.ajax({
			type: 'get',
			url: 'reportsCompute.php?q='+str,
			success: function (response) {
				var suggestions = $.parseJSON(response);
				console.log(target);
				target.autocomplete({
					source: suggestions
				});
			}
		});
	});

	$("input[name='date']").change(function() {
		if ($("input[name='date']:checked").val() == 'date-range') {
			$("#fromto").show();
		} else {
			$("#fromto").hide();
		}
	});

	$("#report-submit").on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: 'post',
			url: 'reportsCompute.php',
			data: $('#search-fields').serialize(),
			success: function (response) {
				$("#outputs").html(response);
			}
		});
	} );
	
});
</script>
	<?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Reports</title>
        <style>
            .report_select{
                display: flex;
                flex-direction: column;
                gap: .5rem;
                padding: 0 0 4rem 0;
            }
            @media only screen and (min-width: 1024px) {
                .report_select {
                    width: 50%;
                }
                main.reportSelection {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
            }
        </style>
</head>
<body>
 	<?php require_once('header.php');
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["go_to_reports_page"]) && isset($_POST["report_types"])) {
		$type = $_POST['report_type'];
		header("Location: /gwyneth/reportsPage.php?report_type=$type");
	}
	?>
        <h1>Business and Operational Reports</h1>
        <main class="reportSelection">
            <form class="report_select" method="post">
        <?php
	
        <?PHP include('footer.inc'); ?>

</body>
