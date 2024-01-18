<?php
/*
 * Copyright 2015 by Adrienne Beebe, Connor Hargus, Phuong Le, 
 * Xun Wang, and Allen Tucker. This program is part of RMHP-Homebase, which is free 
 * software.  It comes with absolutely no warranty. You can redistribute and/or 
 * modify it under the terms of the GNU General Public License as published by the 
 * Free Software Foundation (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/*
 * Created on Feb 24, 2008, modified February 14 2015
 * @author Adrienne Beebe, max
 */
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../database/dbWeeks.php');
include_once(dirname(__FILE__).'/../domain/Week.php');
include_once(dirname(__FILE__).'/../domain/RMHdate.php.php');
include_once(dirname(__FILE__).'/../database/dbDates.php');

class dbWeeksTest extends TestCase {
  function testdbWeeks() {
		//Create two weeks and add them to the database
		$days1 = array();
      	for($i=9;$i<16;$i++) {
      		$days1[] = new RMHDate(date('y-m-d',mktime(0,0,0,2,$i,2015)),"house",array(),"");
      	}
      	$days2 = array();
      	for($i=16;$i<23;$i++) {
      		$days2[] = new RMHDate(date('y-m-d',mktime(0,0,0,2,$i,2015)),"fam",array(),"");
      	}
        $w1 = new Week($days1,"portland","archived");
        $w2 = new Week($days2,"bangor","unpublished");
  		$this->assertTrue(insert_dbWeeks($w1));
  		$this->assertTrue(insert_dbWeeks($w2));
  		
  		//retrieve the first Week and check its fields
  		$w = get_dbWeeks("02-09-15:portland");
  		$this->assertTrue($w!==false);
  		$this->assertTrue($w->get_status()=="archived");
  		$this->assertTrue($w->get_venue()=="portland");
  		
  		$a = get_all_dbWeeks("portland");
  		$this->assertEqual($a[0], $w1);
  		
  		//update the second Week by a change of status
  		$w2 = new Week($days2,"bangor","published");
  		$this->assertTrue(update_dbWeeks($w2));
  		$this->assertEquals(get_dbWeeks($w2->get_id())->get_status(),"published");
  		
  		//Remove the Weeks from the database
  		$this->assertTrue(delete_dbWeeks($w1));
  		$this->assertTrue(delete_dbWeeks($w2));
  	
	echo "testdbWeeks complete\n";
  }
}
?>
