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
 * Modified March 2012
 * @Author Taylor and Allen
 */
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../database/dbPersons.php');
include_once(dirname(__FILE__).'/../domain/Person.php');

class dbPersonsTest extends TestCase {
      function testdbPersons() {
      	// add two people to the database
      
        $m = new Person("Gabrielle","Booth", "bangor","14 Way St", "Harpswell", "ME", 04079,
		1112345679, "cell",7778889999,"work","ted@bowdoin.edu","volunteer","","","active", 
		"National Semiconductor","VP","","year","a motivation","a specialty", "",
		"Mon:9-12:bangor,Sun:morning:bangor","","15-01-05:0930-1300:bangor:3.5","89-02-19", "08-03-14","friend","Some notes","");
		$this->assertTrue(add_person($m));
		
		$m2 = new Person("Fred","Wilson", "portland","14 Boyer Ave", "Portland", "ME", 04503,
		5093456789, "home",5098889999,"cell","alfred@whitman.edu","volunteer","","","active", 
		"McDonalds","manager","","year","a motivation2","First Aid", "",
		"Wed:9-12:portland,Sun:afternoon:portland","","15-02-27:1730-2100:portland:3.5","91-09-25","07-04-14","other","Some notes","");
		$this->assertTrue(add_person($m2));

		// retrieve the person and test the fields
		$p = retrieve_person("Gabrielle1112345679");
		$this->assertTrue($p!==false);
		$this->assertTrue($p->get_status() == "active");
		$this->assertTrue($p->get_email() == "ted@bowdoin.edu");
		$this->assertEquals($p->get_type(), array("volunteer"));
		$this->assertEquals($p->get_hours(), array("15-01-05:0930-1300:bangor:3.5"));
		$this->assertTrue($p->get_birthday() == "89-02-19");
		
		$p2 = retrieve_person("Fred5093456789");
		$this->assertTrue($p2!==false);
		$this->assertTrue($p2->get_status() == "active");
		$this->assertTrue($p2->get_email() == "alfred@whitman.edu");
		$this->assertEquals($p2->get_type(), array("volunteer"));
		$this->assertEquals($p2->get_hours(), array("15-02-27:1730-2100:portland:3.5"));
		$this->assertTrue($p2->get_birthday() == "91-09-25");
		
		// remove the person
		$this->assertTrue(remove_person("Gabrielle1112345679"));
		$this->assertTrue(remove_person("Fred5093456789"));
		
		
		echo("testdbPersons complete\n");

      }
}


?>
