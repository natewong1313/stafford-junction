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


/**
* testdbMasterSchedule class for RMH Homebase
* @author Johnny Coster
* @version February 21, 2012
*/
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/MasterScheduleEntry.php');
include_once(dirname(__FILE__).'/../database/dbMasterSchedule.php');

class dbMasterScheduleTest extends TestCase {
	function testdbMasterSchedule() {
		
		//creates MasterScheduleEntries to insert to database
		$entry1 = new MasterScheduleEntry("portland","Wed", "odd", "1-5", 2,
												  "", "I do not know what Lin means");
		$entry2 = new MasterScheduleEntry("portland","Tue", "even", "1-5", 3, 
										  "", "Yay kitchen shift!");
		$entry3 = new MasterScheduleEntry("portland","Wed", "even", "1-5", 2,
												  "", "");
		$entry4 = new MasterScheduleEntry("portland","Fri", "odd", "1-5", 4,
										  "", "Best job ever.");
		
		//tests the insert function
		$this->assertTrue(insert_dbMasterSchedule($entry1));
		$this->assertTrue(insert_dbMasterSchedule($entry2));
		$this->assertTrue(insert_dbMasterSchedule($entry3));
		$this->assertTrue(insert_dbMasterSchedule($entry4));
		
		//tests the retrieve function
		$this->assertEquals(retrieve_dbMasterSchedule($entry2->get_id())->get_day(), $entry2->get_day());
		$this->assertEquals(retrieve_dbMasterSchedule($entry2->get_id())->get_hours(), $entry2->get_hours());
		$this->assertEquals(retrieve_dbMasterSchedule($entry2->get_id())->get_venue(), $entry2->get_venue());
		$this->assertEquals(retrieve_dbMasterSchedule($entry2->get_id())->get_week_no(), $entry2->get_week_no());
		$this->assertEquals(retrieve_dbMasterSchedule($entry2->get_id())->get_slots(), $entry2->get_slots());
		$this->assertEquals(retrieve_dbMasterSchedule($entry2->get_id())->get_id(), $entry2->get_id());
		
		//tests the update function
		$entry3->set_notes("This is a new note");
		$this->assertTrue(update_dbMasterSchedule($entry3));
		$this->assertEquals(retrieve_dbMasterSchedule($entry3->get_id())->get_notes(), "This is a new note");
		
		//tests the delete function
		$this->assertTrue(delete_dbMasterSchedule($entry1->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry2->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry3->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry4->get_id()));
		
		echo ("testdbMasterSchedule complete\n");
	}
}

?>