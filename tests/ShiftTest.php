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

use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Shift.php');
class ShiftTest extends TestCase {
      function testShift() {
         $noonshift = new Shift("08-03-28:1-5", "house", 3, array(), array(), "", "");
         $this->assertEquals($noonshift->get_hours(), "1-5");
         $this->assertTrue($noonshift->get_id() == "08-03-28:1-5:house");
         $this->assertEquals($noonshift->get_yy_mm_dd(), "08-03-28");
         
// Test new function for resetting shift's start/end time
		 $this->assertTrue($noonshift->get_start_time()==13);
		 $this->assertEquals($noonshift->get_end_time(),17);
		 
// Be sure that invalid times are caught.
		 $this->assertFalse($noonshift->set_start_end_time(13,12));
		 $this->assertTrue($noonshift->get_id() == "08-03-28:1-5:house");
		 $this->assertTrue($noonshift->get_hours() == "1-5");

         $this->assertTrue($noonshift->num_vacancies() == 3);

         $this->assertTrue($noonshift->get_day() == "Fri");
		 $this->assertFalse($noonshift->has_sub_call_list());

         $persons = array();
		 $persons[] = "alex1234567890+alex+jones";
         $noonshift->assign_persons($persons);
         $noonshift->ignore_vacancy();
         $persons[] = "malcom1234567890+malcom+jones";
         $noonshift->assign_persons($persons);
         $noonshift->ignore_vacancy();
         $persons[] = "nat1234567890+nat+jones";
         $noonshift->assign_persons($persons);
         $noonshift->ignore_vacancy();
         $this->assertTrue($noonshift->num_vacancies() == 0);
         $noonshift->add_vacancy();
         $this->assertTrue($noonshift->num_slots() == 4);
         $noonshift->ignore_vacancy();
		 $this->assertTrue($noonshift->num_slots() == 3);

         $noonshift->set_notes("Hello 1-5 shift!");
         $this->assertTrue($noonshift->get_notes() == "Hello 1-5 shift!");
  	  }
}

?>
