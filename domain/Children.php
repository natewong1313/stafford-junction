<?php

class Child {
    private $id;
    private $firstName;
    private $lastName;
    private $birthdate;
    private $gender;
    private $medicalNotes;
    private $notes;


    public function __construct($id, $firstName, $lastName, $birthdate, $gender, $medicalNotes, $notes) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->medicalNotes = $medicalNotes;
        $this->notes = $notes;
    }

    public function getID(){
        return $this->id;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getBirthdate(){
        return $this->birthdate;
    }

    public function getGender(){
        return $this->gender;
    }

    public function getMedicalNotes(){
        return $this->medicalNotes;
    }

    public function getNotes(){
        return $this->notes;
    }
}