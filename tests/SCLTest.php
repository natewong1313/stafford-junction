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
include_once(dirname(__FILE__).'/../domain/SCL.php');
class SCLTest extends TestCase {
      function testSCL() {
         $p=array();
         $p[] = array("max123","max","palmer","123","456","1/1/08","LM","true");
         $p[] = array("oliver345","oliver","radwan","123","456","1/1/08","LM","false");
	     $s=new SCL("08-01-01-9-12",$p,"open",1,"11223344");
//	     print_r($s);
		 $this->assertTrue($s->get_id() == "08-01-01-9-12"); 
	     $p2=$s->get_persons();
	     $p2[]=array("max123","max","palmer","123","456","1/1/08","LM","true");
	     $s->set_persons($p2);
//	     echo count($s->get_persons());
         $this->assertTrue(count($s->get_persons())==3);
  	  }
}

?>
