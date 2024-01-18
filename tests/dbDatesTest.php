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
include_once(dirname(__FILE__).'/../database/dbDates.php');
include_once(dirname(__FILE__).'/../domain/Shift.php');
include_once(dirname(__FILE__).'/../domain/RMHDate.php');
class dbDatesTest extends TestCase {
    function testdbDatesModule() {
		$my_shifts = array();
		$my_shifts[] = new Shift("10-02-28:9-1", "portland", 1, array(), array(), null ,"");
		$my_shifts[] = new Shift("10-02-28:1-5", "portland", 1, array(), array(), null ,"");
		$my_date = new RMHdate("10-02-28","house",$my_shifts,"");
		$this->assertTrue(insert_dbDates($my_date));
		$this->assertTrue(delete_dbDates($my_date));
		
		echo("testdbDates complete");
      }
}
?>