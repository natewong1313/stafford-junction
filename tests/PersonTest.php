<?php
/*
 * Copyright 2015 by Adrienne Beebe, Yonah Biers-Ariel, Connor Hargus, Phuong Le, 
 * Xun Wang, and Allen Tucker. This program is part of RMHP-Homebase, which is free 
 * software.  It comes with absolutely no warranty. You can redistribute and/or 
 * modify it under the terms of the GNU General Public License as published by the 
 * Free Software Foundation (see <http://www.gnu.org/licenses/ for more information).
 */

/**
 * Test suite for Person
 * @author Taylor Talmage, Phuong Le, and Allen Tucker
 * @version on Feb 27, 2008 (v1), Jan 30, 2015 (v2), and Mar 3, 2015 (v3, last modified)
 */

 use PHPUnit\Framework\TestCase;
 include_once(dirname(__FILE__).'/../domain/Person.php');
 class PersonTest extends TestCase {
      function testPerson() {

 $myPerson = new Person("Susan","L","portland","928 SU","Portland", "ME",04011,
      2074415902,"home",2072654046,"cell", "susanl@aol.com", "volunteer",
      "","","active", "USM","student",3,"semester","I like helping out","cooking","",
      "Mon:9-12:portland,Sun:evening:portland", "", "", "89-02-19", "08-03-14", "internet",
      "this is a note","");
 
 $this->assertTrue($myPerson->get_first_name()=="Susan");
 $this->assertTrue($myPerson->get_type()==array("volunteer"));
 $this->assertTrue($myPerson->get_status()=="active");
 $this->assertTrue($myPerson->get_city()=="Portland");
 $this->assertTrue($myPerson->get_phone1()==2074415902);
 $this->assertTrue($myPerson->get_employer()=="USM");
 $this->assertEquals($myPerson->get_availability(),array("Mon:9-12:portland","Sun:evening:portland"));
 $this->assertTrue($myPerson->get_last_name() !== "notMyLastName");
 $this->assertTrue($myPerson->get_phone1type()!=="work");
 $this->assertTrue($myPerson->get_howdidyouhear()=="internet");
 $this->assertTrue($myPerson->get_birthday()=="89-02-19");
      }
 }

?>
