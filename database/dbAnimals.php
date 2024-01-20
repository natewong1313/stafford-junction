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

/**
 * @version March 1, 2012
 * @author Oliver Radwan and Allen Tucker
 */

/* 
 * Created for Gwyneth's Gift in 2022 using original Homebase code as a guide
 */


include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Event.php');
include_once(dirname(__FILE__).'/../domain/Animal.php');

/*
 * add an event to dbEvents table: if already there, return false
 */

function add_Animal($animal) {
    if (!$animal instanceof Animal)
        die("Error: add_event type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbAnimals WHERE id = '" . $animal->get_id() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbEvents VALUES("' .
                $event->get_id() . '","' .
                $event->get_event_date() . '","' .
                $event->get_venue() . '","' .
                $event->get_event_name() . '","' . 
                $event->get_description() . '","' .
                $event->get_event_id() .            
                '");');							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

/*
 * remove an event from dbEvents table.  If already there, return false
 */

function remove_event($id) {
    $con=connect();
    $query = 'SELECT * FROM dbEvents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbEvents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}


/*
 * @return an Event from dbEvents table matching a particular id.
 * if not in table, return false
 */

function retrieve_event($id) {
    $con=connect();
    $query = "SELECT * FROM dbEvents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $theEvent = make_an_event($result_row);
//    mysqli_close($con);
    return $theEvent;
}

// not in use, may be useful for future iterations in changing how events are edited (i.e. change the remove and create new event process)
function update_event_date($id, $new_event_date) {
	$con=connect();
	$query = 'UPDATE dbEvents SET event_date = "' . $new_event_date . '" WHERE id = "' . $id . '"';
	$result = mysqli_query($con,$query);
	mysqli_close($con);
	return $result;
}

function make_an_event($result_row) {
	/*
	 ($en, $v, $sd, $description, $ev))
	 */
    $theEvent = new Event(
                    $result_row['event_name'],
                    $result_row['venue'],                   
                    $result_row['event_date'],
                    $result_row['description'],
                    $result_row['event_id']);  
    return $theEvent;
}


// retrieve only those events that match the criteria given in the arguments
function getonlythose_dbEvents($name, $day, $venue) {
   $con=connect();
   $query = "SELECT * FROM dbEvents WHERE event_name LIKE '%" . $event_name . "%'" .
           " AND event_name LIKE '%" . $name . "%'" .
           " AND venue = '" . $venue . "'" . 
           " ORDER BY event_name";
   $result = mysqli_query($con,$query);
   $theEvents = array();
   while ($result_row = mysqli_fetch_assoc($result)) {
       $theEvent = make_an_event($result_row);
       $theEvents[] = $theEvent;
   }
   mysqli_close($con);
   return $theEvents;
}

function fetch_events_in_date_range($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbEvents
              where date >= '$start_date' and date <= '$end_date'
              order by startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $key = $result_row['date'];
        if (isset($events[$key])) {
            $events[$key] []= hsc($result_row);
        } else {
            $events[$key] = array(hsc($result_row));
        }
    }
    mysqli_close($connection);
    return $events;
}

function fetch_events_on_date($date) {
    $connection = connect();
    $date = mysqli_real_escape_string($connection, $date);
    $query = "select * from dbEvents
              where date = '$date' order by startTime asc";
    $results = mysqli_query($connection, $query);
    if (!$results) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = [];
    foreach ($results as $row) {
        $events []= hsc($row);
    }
    mysqli_close($connection);
    return $events;
}

function fetch_event_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select * from dbEvents where id = '$id'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    if ($event) {
        require_once('include/output.php');
        $event = hsc($event);
        mysqli_close($connection);
        return $event;
    }
    mysqli_close($connection);
    return null;
}

function fetch_animal_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select * from dbAnimals where id = '$id'";
    $result = mysqli_query($connection, $query);
    $animal = mysqli_fetch_assoc($result);
    if ($animal) {
        require_once('include/output.php');
        $animal = hsc($animal);
        mysqli_close($connection);
        return $animal;
    }
    mysqli_close($connection);
    return null;
}

function create_animal($animal) {
    $connection = connect();
	$odhsid = $animal["odhsid"];
    $name = $animal["name"];
	$breed = $animal["breed"];
    $age = $animal["age"];
    $gender = $animal["gender"];
    $notes = $animal["notes"];
    $spay_neuter_done = $animal["spay_neuter_done"];
	$spay_neuter_date = $animal["spay_neuter_date"];
    if (empty($animal["spay_neuter_date"])) {
        $spay_neuter_date = '0000-00-00';
    }
    $rabies_given_date = $animal["rabies_given_date"];
    if (empty($animal["rabies_given_date"])) {
        $rabies_given_date = '0000-00-00';
    }
	$rabies_due_date = $animal["rabies_due_date"];
    if (empty($animal["rabies_due_date"])) {
        $rabies_due_date = '0000-00-00';
    }
    $heartworm_given_date = $animal["heartworm_given_date"];
    if (empty($animal["heartworm_given_date"])) {
        $heartworm_given_date = '0000-00-00';
    }
	$heartworm_due_date = $animal["heartworm_due_date"];
    if (empty($animal["heartworm_due_date"])) {
        $heartworm_due_date = '0000-00-00';
    }
	$distemper1_given_date = $animal["distemper1_given_date"];
    if (empty($animal["distemper1_given_date"])) {
        $distemper1_given_date = '0000-00-00';
    }
	$distemper1_due_date = $animal["distemper1_due_date"];
    if (empty($animal["distemper1_due_date"])) {
        $distemper1_due_date = '0000-00-00';
    }
	$distemper2_given_date = $animal["distemper2_given_date"];
    if (empty($animal["distemper2_given_date"])) {
        $distemper2_given_date = '0000-00-00';
    }
	$distemper2_due_date = $animal["distemper2_due_date"];
    if (empty($animal["distemper2_due_date"])) {
        $distemper2_due_date = '0000-00-00';
    }
	$distemper3_given_date = $animal["distemper3_given_date"];
    if (empty($animal["distemper3_given_date"])) {
        $distemper3_given_date = '0000-00-00';
    }
	$distemper3_due_date = $animal["distemper3_due_date"];
    if (empty($animal["distemper3_due_date"])) {
        $distemper3_due_date = '0000-00-00';
    }
	$microchip_done = $animal["microchip_done"];
    $query = "
        INSERT INTO dbAnimals (odhs_id, name, breed, age, gender, notes, spay_neuter_done, spay_neuter_date, rabies_given_date, rabies_due_date, heartworm_given_date, heartworm_due_date, distemper1_given_date, distemper1_due_date, distemper2_given_date, distemper2_due_date, distemper3_given_date, distemper3_due_date, microchip_done, archived)
        values ('$odhsid','$name', '$breed', '$age', '$gender', '$notes', '$spay_neuter_done', '$spay_neuter_date', '$rabies_given_date', '$rabies_due_date', '$heartworm_given_date', '$heartworm_due_date', '$distemper1_given_date', '$distemper1_due_date', '$distemper2_given_date', '$distemper2_due_date', '$distemper3_given_date', '$distemper3_due_date', '$microchip_done', 'no')
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}

function update_animal($animal) {
    $connection = connect();
    $id = $animal['id'];
	$odhsid = $animal["odhs_id"];
    $name = $animal["name"];
	$breed = $animal["breed"];
    $age = $animal["age"];
    $gender = $animal["gender"];
    $notes = $animal["notes"];
    $spay_neuter_done = $animal["spay_neuter_done"];
	$spay_neuter_date = $animal["spay_neuter_date"];
    if (empty($animal["spay_neuter_date"])) {
        $spay_neuter_date = '0000-00-00';
    }
    $rabies_given_date = $animal["rabies_given_date"];
    if (empty($animal["rabies_given_date"])) {
        $rabies_given_date = '0000-00-00';
    }
	$rabies_due_date = $animal["rabies_due_date"];
    if (empty($animal["rabies_due_date"])) {
        $rabies_due_date = '0000-00-00';
    }
    $heartworm_given_date = $animal["heartworm_given_date"];
    if (empty($animal["heartworm_given_date"])) {
        $heartworm_given_date = '0000-00-00';
    }
	$heartworm_due_date = $animal["heartworm_due_date"];
    if (empty($animal["heartworm_due_date"])) {
        $heartworm_due_date = '0000-00-00';
    }
	$distemper1_given_date = $animal["distemper1_given_date"];
    if (empty($animal["distemper1_given_date"])) {
        $distemper1_given_date = '0000-00-00';
    }
	$distemper1_due_date = $animal["distemper1_due_date"];
    if (empty($animal["distemper1_due_date"])) {
        $distemper1_due_date = '0000-00-00';
    }
	$distemper2_given_date = $animal["distemper2_given_date"];
    if (empty($animal["distemper2_given_date"])) {
        $distemper2_given_date = '0000-00-00';
    }
	$distemper2_due_date = $animal["distemper2_due_date"];
    if (empty($animal["distemper2_due_date"])) {
        $distemper2_due_date = '0000-00-00';
    }
	$distemper3_given_date = $animal["distemper3_given_date"];
    if (empty($animal["distemper3_given_date"])) {
        $distemper3_given_date = '0000-00-00';
    }
	$distemper3_due_date = $animal["distemper3_due_date"];
    if (empty($animal["distemper3_due_date"])) {
        $distemper3_due_date = '0000-00-00';
    }
	$microchip_done = $animal["microchip_done"];
    $query = "
        UPDATE dbAnimals set odhs_id='$odhsid', name='$name', breed='$breed', age='$age', gender='$gender', notes='$notes', spay_neuter_done='$spay_neuter_done', spay_neuter_date='$spay_neuter_date', rabies_given_date='$rabies_given_date', rabies_due_date='$rabies_due_date', heartworm_given_date='$heartworm_given_date', heartworm_due_date='$heartworm_due_date', distemper1_given_date='$distemper1_given_date', distemper1_due_date='$distemper1_due_date', distemper2_given_date='$distemper2_given_date', distemper2_due_date='$distemper2_due_date', distemper3_given_date='$distemper3_given_date', distemper3_due_date='$distemper3_due_date', microchip_done='$microchip_done'
        where id='$id'
        ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}

function update_event($eventID, $eventDetails) {
    $connection = connect();
    $name = $eventDetails["name"];
    $abbrevName = $eventDetails["abbrev-name"];
    $date = $eventDetails["date"];
    $startTime = $eventDetails["start-time"];
    $endTime = $eventDetails["end-time"];
    $description = $eventDetails["description"];
    $location = $eventDetails["location"];
    $capacity = $eventDetails["capacity"];
    $query = "
        UPDATE dbEvents set name='$name', abbrevName='$abbrevName', date='$date', startTime='$startTime', endTime='$endTime', description='$description', location='$location', capacity='$capacity'
        where id='$eventID'
    ";
    $result = mysqli_query($connection, $query);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $result;
}

function find_event($nameLike) {
    $connection = connect();
    $query = "
        select * from dbEvents
        where name like '%$nameLike%'
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $all = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $all;
}

function fetch_events_in_date_range_as_array($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbEvents
              where date >= '$start_date' and date <= '$end_date'
              order by date, startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function get_media($id, $type) {
    $connection = connect();
    $query = "select * from dbEventMedia
              where eventID='$id' and type='$type'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $media = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $media;
}

function get_event_training_media($id) {
    return get_media($id, 'training');
}

function get_post_event_media($id) {
    return get_media($id, 'post');
}

function attach_media($eventID, $type, $url, $format, $description) {
    $query = "insert into dbEventMedia
              (eventID, type, url, format, description)
              values ('$eventID', '$type', '$url', '$format', '$description')";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    mysqli_close($connection);
    if (!$result) {
        return false;
    }
    return true;
}

function attach_event_training_media($eventID, $url, $format, $description) {
    return attach_media($eventID, 'training', $url, $format, $description);
}

function attach_post_event_media($eventID, $url, $format, $description) {
    return attach_media($eventID, 'post', $url, $format, $description);
}

function detach_media($mediaID) {
    $query = "delete from dbEventMedia where id='$mediaID'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    mysqli_close($connection);
    if ($result) {
        return true;
    }
    return false;
}

function delete_event($id) {
    $query = "delete from dbEvents where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function delete_animal($id) {
    $query = "delete from dbAnimals where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function archive_animal($id) {
    $query = "UPDATE dbAnimals set archived='yes' where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function unarchive_animal($id) {
    $query = "UPDATE dbAnimals set archived='no' where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function find_archived() {
    $query = "select * from dbAnimals where archived='yes' order by name";

    $connection = connect();

    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $animals = [];
    foreach ($raw as $row) {
        $animals []= make_an_animal($row);
    }
    mysqli_close($connection);
    return $animals;

}

function find_animal($name, $breed, $age1, $age2, $gender, $spay_neuter_done, $microchip_done, $needs_attention, $other){
    $where = 'where ';
    if (!($name || $breed || $age1 || $age2 || $gender || $spay_neuter_done || $microchip_done || $needs_attention || $other)){
        return [];
    }
    $first = true;
    if ($name) {
        if (strpos($name, ' ')) {
            $name = explode(' ', $name, 2);
            $first = $name[0];
            $last = $name[1];
            $where .= "name like '%$first%'";
        } else {
            $where .= "(name like '%$name%')";
        }
        $first = false;
    }
    if ($breed) {
        if (!$first) {
            $where .= ' and ';
        }
        $where .= "breed like '%$breed%'";
        $first = false;
    }
    if ($age1 || $age2) {
        if (!$first) {
            $where .= ' and ';
        }
        $where .= "age >= $age1 and age <= $age2";
        $first = false;
    }
    if ($gender) {
        if (!$first) {
            $where .= ' and ';
        }
        $where .= "gender = '$gender'";
        $first = false;
    }
    if ($spay_neuter_done) {
        if (!$first) {
            $where .= ' and ';
        }
        $where .= "spay_neuter_done ='$spay_neuter_done'";
        $first = false;
    }
    if ($microchip_done) {
        if (!$first) {
            $where .= ' and ';
        }
        $where .= "microchip_done ='$microchip_done'";
        $first = false;
    }
    if ($needs_attention || $other) {
        if ($first) {
            $maxAge = "99";
            $where .= " age <= '$maxAge'";
        }
    }
    $where .= " and archived='no'";

    $query = "select * from dbAnimals $where order by name";
    // echo $query;
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $animals = [];
    foreach ($raw as $row) {
        if ($row['id'] == 'vmsroot') {
            continue;
        }
        $animals []= make_an_animal($row);
    }
    mysqli_close($connection);
    return $animals;
}

function make_an_animal($result_row) {
    $theAnimal = new Animal(
                    $result_row['id'],
                    $result_row['name'],
                    $result_row['breed'],
                    $result_row['age'],
                    $result_row['gender'],
                    $result_row['spay_neuter_done'],
                    $result_row['microchip_done'],

                    $result_row['rabies_given_date'],
                    $result_row['rabies_due_date'],
                    $result_row['heartworm_given_date'],
                    $result_row['heartworm_due_date'],
                    $result_row['distemper1_given_date'],
                    $result_row['distemper1_due_date'],
                    $result_row['distemper2_given_date'],
                    $result_row['distemper2_due_date'],
                    $result_row['distemper3_given_date'],
                    $result_row['distemper3_due_date']
                );   
    return $theAnimal;
}

/**
 * Get all appointments that exist for an animal
 */
function find_animal_appointments($animal_id) {
    $connection = connect();
    //$current_date = date("Y-m-d");
    $query = "select * from dbEvents
              where animalID='$animal_id' 
              ORDER BY date";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}
?>
