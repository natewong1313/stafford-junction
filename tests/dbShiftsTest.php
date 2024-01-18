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
 * Created on Feb 24, 2008
 * @author max
 */
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../database/dbShifts.php');
include_once(dirname(__FILE__).'/../database/dbDates.php');
class dbShiftsTest extends TestCase {
  function testdbShifts() {
      
      // Setup step
	$s1=new Shift("08-02-25:1-5","portland", 3, array(), array(), "", "");
	$s2=new Shift("08-02-25:9-1","portland", 3, array(), array(), "", "");
	$this->assertTrue(insert_dbShifts($s1));  // Create test
	$this->assertTrue(insert_dbShifts($s2));
	
	// Test step
	$this->assertEquals(select_dbShifts($s2->get_id())->get_vacancies(), 3);  // Retrieve 
	$s2=new Shift("08-02-25:9-1","portland",2, array(), array(), "", "");
	$this->assertTrue(update_dbShifts($s2));  // Update
	
	// Teardown step
	$this->assertTrue(delete_dbShifts($s1));  // Delete test; leave database unchanged
	$this->assertTrue(delete_dbShifts($s2));
	echo ("testdbShifts complete\n");
  }
}
?>
