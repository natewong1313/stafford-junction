<?php

class Child implements JsonSerializable {
    private $id;
    private $firstName;
    private $lastName;
    private $birthdate;
    private $address;
    private $neighborhood;
    private $city;
    private $state;
    private $zip;
    private $gender;
    private $school;
    private $grade;
    private $isHispanic;
    private $race;
    private $medicalNotes;
    private $notes;


    public function __construct($id, $firstName, $lastName, $birthdate, $address, $neighborhood, $city, $state, $zip, $gender, $school, $grade,
        $isHispanic, $race, $medicalNotes, $notes) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthdate = $birthdate;
        $this->address = $address;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->gender = $gender;
        $this->school = $school;
        $this->grade = $grade;
        $this->race = $race;
        $this->isHispanic = $isHispanic;
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

    public function getAddress(){
        return $this->address;
    }

    public function getNeighborhood(){
        return $this->neighborhood;
    }    

    public function getCity(){
        return $this->city;
    }

    public function getState(){
        return $this->state;
    }

    public function getZip(){
        return $this->zip;
    }

    public function getGender(){
        return $this->gender;
    }

    public function getSchool(){
        return $this->school;
    }

    public function getGrade(){
        return $this->grade;
    }

    public function getRace() {
        return $this->race;
    }

    public function isHispanic() {
        return $this->isHispanic;
    }

    public function getMedicalNotes(){
        return $this->medicalNotes;
    }

    public function getNotes(){
        return $this->notes;
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }
}