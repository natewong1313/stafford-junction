<?php
/*
 * Copyright 2015 by ... and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/**
* Unit test for MasterScheduleEntry
* Created on January 7, 2015
* @author Allen Tucker
*/

use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/MasterScheduleEntry.php');
class MasterScheduleEntryTest extends TestCase {
	
	function testMasterScheduleEntryModule() {
		
		$new_MasterScheduleEntry = new MasterScheduleEntry("house","Wed", "1st", "1-5", 2,
		"joe2071234567,sue2079876543", "This is a super fun shift.");
		
		//first assertion - check that a getter is working from the superconstructor's initialized data
		$this->assertTrue($new_MasterScheduleEntry->get_day()=="Wed");
		
		$this->assertTrue($new_MasterScheduleEntry->get_hours()=="1-5");
		$this->assertEquals($new_MasterScheduleEntry->get_week_no(), "1st");
		$this->assertTrue($new_MasterScheduleEntry->get_slots()==2);
		$this->assertTrue($new_MasterScheduleEntry->get_persons()==array("joe2071234567","sue2079876543"));
		$this->assertTrue($new_MasterScheduleEntry->get_notes()=="This is a super fun shift.");
		$this->assertTrue($new_MasterScheduleEntry->get_id()=="1st:Wed:1-5:house");
	}
}

?>
