<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Family.php');

function make_a_family($result_row){
    $family = new Family(
        $result_row['first-name'],
        $result_row['last-name'],
        $result_row['birthdate'],
        $result_row['address'],
        $result_row['city'],
        $result_row['state'],
        $result_row['zip'],
        $result_row['email'],
        $result_row['phone'],
        $result_row['phone-type'],
        $result_row['secondary-phone'],
        $result_row['secondary-phone-type'],
        $result_row['first-name2'],
        $result_row['last-name2'],
        $result_row['birthdate2'],
        $result_row['address2'],
        $result_row['city2'],
        $result_row['state2'],
        $result_row['zip2'],
        $result_row['email2'],
        $result_row['phone2'],
        $result_row['phone-type2'],
        $result_row['secondary-phone2'],
        $result_row['secondary-phone-type2'],
        $result_row['econtact-first-name'],
        $result_row['econtact-last-name'],
        $result_row['econtact-phone'],
        $result_row['econtact-relation'],
        $result_row['password'],
        $result_row['question'],
        $result_row['answer'],
        'family',
        0
    );

    return $family;
}