<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../database/dbShifts.php');
include_once(dirname(__FILE__).'/../editShift.php');
class editShiftTest extends TestCase {
  function testeditShift() {
    // Setup - retrieve a shift and make a backup copy
    $myshift = select_dbShifts("18-09-21:12-3:portland");
    $myshiftcopy = $myshift;
    // test Add a Slot button
    $this->assertTrue(process_add_slot(
        array('_submit_add_slot'=>true), $myshift,'portland'));
    // test Move this Shift button
    $myshift = select_dbShifts("18-09-21:12-3:portland");
    $this->assertTrue(process_move_shift(
        array('_submit_move_shift'=>true), $myshift));
    // test Generate Sub Call List button
    $myshift = select_dbShifts("18-09-21:12-3:portland");
    $this->assertTrue(process_add_slot(
        array('_submit_generate_scl'=>true), $myshift, ""));
    // test Remove Person button
    $myshift = select_dbShifts("18-09-21:12-3:portland");
    $this->assertTrue(process_unfill_shift(
        array('_submit_filled_slot_0'=>true), $myshift, ""));
    // test Assign Volunteer button
    $myshift = select_dbShifts("18-09-21:12-3:portland");
    $this->assertFalse(process_add_volunteer(
        array('_submit_add_volunteer'=>true, 'all_vol'=>"0",
            'scheduled_vol'=>'rob2077291234+rob+jones'),$myshift,""));
    // test Remove Vacancy button
    $myshift = select_dbShifts("18-09-21:12-3:portland");
    $this->assertTrue(process_ignore_slot(
                array('_submit_ignore_vacancy'=>true), $myshift, ""));  
    
    // Teardown - restore original shift from the backup
    update_dbShifts($myshiftcopy); 
  }
}

?>