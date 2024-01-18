<?php
/*
 * Copyright 2015 by Adrienne Beebe, Yonah Biers-Ariel, Connor Hargus, Phuong Le, 
 * Xun Wang, and Allen Tucker. This program is part of RMHP-Homebase, which is free 
 * software.  It comes with absolutely no warranty. You can redistribute and/or 
 * modify it under the terms of the GNU General Public License as published by the 
 * Free Software Foundation (see <http://www.gnu.org/licenses/ for more information).
 */
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Week.php');
class WeekTest extends TestCase {
      function testWeek() {
      	 $days = array();
      	 for($i=6;$i<13;$i++) {
      	 	$days[] = new RMHDate(date('y-m-d',mktime(0,0,0,2,$i,2012)),"portland",array(),"");
      	 }
         $aweek = new Week($days,"portland","archived");
         $this->assertEquals($aweek->get_name(), "February 6, 2012 to February 12, 2012");
		 $this->assertEquals($aweek->get_id(), "12-02-06:portland");
		 $this->assertTrue(sizeof($aweek->get_dates()) == 7);
		 $this->assertEquals($aweek->get_venue(), "portland");
		 $this->assertEquals($aweek->get_status(), "archived");
		 $this->assertEquals($aweek->get_end(), mktime(23,59,59,2,12,2012));
  	  }
}

?>
