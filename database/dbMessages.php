<?php

require_once('database/dbinfo.php');
date_default_timezone_set("America/New_York");

function get_user_messages($userID) {
    $query = "select * from dbMessages
              where recipientID='$userID'
              order by prioritylevel desc";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            $message[$key] = htmlspecialchars($value);
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_unread_count($userID) {
    $query = "select count(*) from dbMessages 
        where recipientID='$userID' and wasRead=0";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }

    $row = mysqli_fetch_row($result);
    mysqli_close($connection);
    return intval($row[0]);
}

function get_message_by_id($id) {
    $query = "select * from dbMessages where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_close($connection);
    if ($row == null) {
        return null;
    }
    foreach ($row as $key => $value) {
        $row[$key] = htmlspecialchars($value);
    }
    $row['body'] = str_replace("\r\n", "<br>", $row['body']);
    return $row;
}

function send_message($from, $to, $title, $body) {
    $time = date('Y-m-d-H:i');
    $connection = connect();
    $title = mysqli_real_escape_string($connection, $title);
    $body = mysqli_real_escape_string($connection, $body);
    $query = "insert into dbMessages
        (senderID, recipientID, title, body, time)
        values ('$from', '$to', '$title', '$body', '$time')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_close($connection);
    return $id; // get row id
}

function send_system_message($to, $title, $body) {
    send_message('vmsroot', $to, $title, $body);
}

function mark_read($id) {
    $query = "update dbMessages set wasRead=1
              where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return false;
    }
    mysqli_close($connection);
    return true;
}

function message_all_users_of_types($from, $types, $title, $body) {
    $types = implode(', ', $types);
    $time = date('Y-m-d-H:i');
    $query = "select id from dbPersons where type in ($types)";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_NUM);
    foreach ($rows as $row) {
        $to = $row[0];
        $query = "insert into dbMessages (senderID, recipientID, title, body, time)
                  values ('$from', '$to', '$title', '$body', '$time')";
        $result = mysqli_query($connection, $query);
    }
    mysqli_close($connection);    
    return true;
}

function message_all_volunteers($from, $title, $body) {
    return message_all_users_of_types($from, ['"volunteer"'], $title, $body);
}

function system_message_all_volunteers($title, $body) {
    return message_all_users_of_types('vmsroot', ['"volunteer"'], $title, $body);
}

function message_all_admins($from, $title, $body) {
    return message_all_users_of_types($from, ['"admin"', '"superadmin"'], $title, $body);
}

function system_message_all_admins($title, $body) {
    return message_all_users_of_types('vmsroot', ['"admin"', '"superadmin"'], $title, $body);
}

function system_message_all_users_except($except, $title, $body) {
    $time = date('Y-m-d-H:i');
    $query = "select id from dbPersons where id!='$except'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_NUM);
    foreach ($rows as $row) {
        $to = $row[0];
        $query = "insert into dbMessages (senderID, recipientID, title, body, time)
                  values ('vmsroot', '$to', '$title', '$body', '$time')";
        $result = mysqli_query($connection, $query);
    }
    mysqli_close($connection);    
    return true;
}

//function to go through all users within the database of user accounts and send them a notification given a title and body 
function message_all_users($from, $title, $body) {
    $time = date('Y-m-d-H:i');
    $query = "select id from dbPersons where id!='$from'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_NUM); //get all the users in the database dbPersons
    foreach ($rows as $row) { //for every user in db person, generate a notification
        $to = json_encode($row); //converting the array of users into strings to put into the database of messages
        $to = substr($to,2,-2); //getting rid of the brackets and quotes in the string: ie - ["user"]
        $query = "insert into dbMessages (senderID, recipientID, title, body, time)
                  values ('$from', '$to', '$title', '$body', '$time')"; //inserting the notification in that users inbox
        $result = mysqli_query($connection, $query); 
    }
    mysqli_close($connection);    
    return true;
}

function message_all_users_prio($from, $title, $body, $prio) {
    $time = date('Y-m-d-H:i');
    $query = "select id from dbPersons where id!='$from'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_NUM); //get all the users in the database dbPersons
    foreach ($rows as $row) { //for every user in db person, generate a notification
        $to = json_encode($row); //converting the array of users into strings to put into the database of messages
        $to = substr($to,2,-2); //getting rid of the brackets and quotes in the string: ie - ["user"]
        $query = "insert into dbMessages (senderID, recipientID, title, body, time, prioritylevel)
                  values ('$from', '$to', '$title', '$body', '$time', '$prio')"; //inserting the notification in that users inbox
        $result = mysqli_query($connection, $query); 
    }
    mysqli_close($connection);    
    return true;
}
function delete_message($id) {
    $query = "delete from dbMessages where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function dateChecker(){
    $query = "select * from dbAnimals";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $twoWeeksAhead = date('Y-m-d', strtotime('+2 weeks'));
    $fivedaysAhead = date('Y-m-d', strtotime('+5 days'));
    $currentDate = date('Y-m-d');
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $rabies_due = $row['rabies_due_date'];
            $name = $row['name'];
            $heartworm_due = $row['heartworm_due_date'];
            $distemper1_due = $row['distemper1_due_date'];
            $distemper2_due = $row['distemper2_due_date'];
            $distemper3_due = $row['distemper3_due_date'];
            //The Logic for Two Weeks Out
            if($rabies_due >= $currentDate && $rabies_due <= $twoWeeksAhead){
                $title = $name . " Rabies shot is coming up in two weeks";
                $body = $name . " Rabies shot is due on " . $rabies_due;
                message_all_users_prio('vmsroot', $title, $body ,'1'); 
            }
            if($heartworm_due >= $currentDate && $heartworm_due <= $twoWeeksAhead){
                $title = $name . " Heartworm shot is coming up in two weeks";
                $body = $name . " Heartworm shot is due on " . $heartworm_due ;
                message_all_users_prio('vmsroot', $title, $body ,'1');  
            }
            if($distemper1_due >= $currentDate && $distemper1_due <= $twoWeeksAhead){
                $title = $name . " Distemper 1 shot is coming up in two weeks";
                $body = $name . " Distemper 1 shot is due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'1');  
            }
            if($distemper2_due >= $currentDate && $distemper2_due <= $twoWeeksAhead){
                $title = $name . " Distemper 2 shot is coming up in two weeks";
                $body = $name . " Distemper 2 shot is due on " . $distemper2_due ;
                message_all_users_prio('vmsroot', $title, $body ,'1');   
            }
            if($distemper3_due >= $currentDate && $distemper3_due <= $twoWeeksAhead){
                $title = $name . " Distemper 3 shot is coming up in two weeks";
                $body = $name . " Distemper 3 shot is due on " . $distemper3_due ;
                message_all_users_prio('vmsroot', $title, $body ,'1');   
            }
            //The Logic for 5 Days Out
            if($rabies_due >= $currentDate && $rabies_due <= $fivedaysAhead){
                $title = $name . " Rabies shot is coming up in 5 days";
                $body = $name . " Rabies shot is due on " . $rabies_due;
                message_all_users_prio('vmsroot', $title, $body ,'2');  
            }
            if($heartworm_due >= $currentDate && $heartworm_due <= $fivedaysAhead){
                $title = $name . " Heartworm shot is coming up in 5 days";
                $body = $name . " Heartworm shot is due on " . $heartworm_due ;
                message_all_users_prio('vmsroot', $title, $body ,'2');  
            }
            if($distemper1_due >= $currentDate && $distemper1_due <= $fivedaysAhead){
                $title = $name . " Distemper 1 shot is coming up in 5 days";
                $body = $name . " Distemper 1 shot is due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'2'); 
            }
            if($distemper2_due >= $currentDate && $distemper2_due <= $fivedaysAhead){
                $title = $name . " Distemper 2 shot is coming up in 5 days";
                $body = $name . " Distemper 2 shot is due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'2'); 
            }
            if($distemper3_due >= $currentDate && $distemper3_due <= $fivedaysAhead){
                $title = $name . " Distemper 3 shot is coming up in 5 days";
                $body = $name . " Distemper 3 shot is due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'2');  
            }
            //The Logic for It Being Late
            if($rabies_due <= $currentDate){
                $title = $name . " Rabies shot is LATE";
                $body = $name . " Rabies shot was due on " . $rabies_due;
                message_all_users_prio('vmsroot', $title, $body ,'3');  
            }
            if($heartworm_due  <= $currentDate){
                $title = $name . " Heartworm shot is LATE";
                $body = $name . " Heartworm shot was due on " . $heartworm_due ;
                message_all_users_prio('vmsroot', $title, $body ,'3');  
            }
            if($distemper1_due  <= $currentDate){
                $title = $name . " Distemper 1 shot is LATE";
                $body = $name . " Distemper 1 shot was due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'3');  
            }
            if($distemper2_due  <= $currentDate){
                $title = $name . " Distemper 2 shot is LATE";
                $body = $name . " Distemper 2 shot was due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'3');   
            }
            if($distemper3_due  <= $currentDate){
                $title = $name . " Distemper 3 shot is LATE";
                $body = $name . " Distemper 3 shot was due on " . $distemper1_due ;
                message_all_users_prio('vmsroot', $title, $body ,'3');   
            }
        }
    }
    mysqli_close($connection);    
    return true;
}

//dateChecker();
//Method Type 1: For Upcoming Appointments
//message_all_users('vmsroot', 'message all users test', "does this work?");
//send_message('vmsroot', 'rwarren@mail.umw.edu', 'I am a bad test """""!!ASDF', "helloAAA'''ffdf!!$$");

//Method Type 2: For Animal Updates 
//message_all_users_prio('vmsroot', 'Baxter needs his rabies shot in 2 weeks', "does this work?",'1');
//message_all_users_prio('vmsroot', 'Snuffles needs to get neutered in the next 3 days, she is a menace to society', "does this work?",'2');
//message_all_users_prio('vmsroot', 'PABLO IS LATE ON HIS HEARTWORM SHOT', "hi",'3');