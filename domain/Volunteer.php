<?php

class Volunteer
{
    private $id;
    private $email;
    private $password;
    private $securityQuestion;
    private $securityAnswer;
    private $firstName;
    private $middleInitial;
    private $lastName;
    private $address;
    private $city;
    private $state;
    private $zip;
    private $homePhone;
    private $cellPhone;
    private $age;
    private $birthDate;
    private $hasDriversLicense;
    private $transportation;
    private $emergencyContact1Name;
    private $emergencyContact1Relation;
    private $emergencyContact1Phone;
    private $emergencyContact2Name;
    private $emergencyContact2Relation;
    private $emergencyContact2Phone;
    private $allergies;
    private $sunStart;
    private $sunEnd;
    private $monStart;
    private $monEnd;
    private $tueStart;
    private $tueEnd;
    private $wedStart;
    private $wedEnd;
    private $thurStart;
    private $thurEnd;
    private $friStart;
    private $friEnd;
    private $satStart;
    private $satEnd;
    private $dateAvailable;
    private $minHours;
    private $maxHours;

    public function __construct(
        $id, $email, $password, $securityQuestion, $securityAnswer, $firstName, $middleInitial, $lastName, $address, $city, $state, $zip, $homePhone, $cellPhone, $age, $birthDate, $hasDriversLicense, $transportation, $emergencyContact1Name, $emergencyContact1Relation, $emergencyContact1Phone, $emergencyContact2Name, $emergencyContact2Relation, $emergencyContact2Phone, $allergies, $sunStart, $sunEnd, $monStart, $monEnd, $tueStart, $tueEnd, $wedStart, $wedEnd, $thurStart, $thurEnd, $friStart, $friEnd, $satStart, $satEnd, $dateAvailable, $minHours, $maxHours
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->securityQuestion = $securityQuestion;
        $this->securityAnswer = $securityAnswer;
        $this->firstName = $firstName;
        $this->middleInitial = $middleInitial;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->homePhone = $homePhone;
        $this->cellPhone = $cellPhone;
        $this->age = $age;
        $this->birthDate = $birthDate;
        $this->hasDriversLicense = $hasDriversLicense;
        $this->transportation = $transportation;
        $this->emergencyContact1Name = $emergencyContact1Name;
        $this->emergencyContact1Relation = $emergencyContact1Relation;
        $this->emergencyContact1Phone = $emergencyContact1Phone;
        $this->emergencyContact2Name = $emergencyContact2Name;
        $this->emergencyContact2Relation = $emergencyContact2Relation;
        $this->emergencyContact2Phone = $emergencyContact2Phone;
        $this->allergies = $allergies;
        $this->sunStart = $sunStart;
        $this->sunEnd = $sunEnd;
        $this->monStart = $monStart;
        $this->monEnd = $monEnd;
        $this->tueStart = $tueStart;
        $this->tueEnd = $tueEnd;
        $this->wedStart = $wedStart;
        $this->wedEnd = $wedEnd;
        $this->thurStart = $thurStart;
        $this->thurEnd = $thurEnd;
        $this->friStart = $friStart;
        $this->friEnd = $friEnd;
        $this->satStart = $satStart;
        $this->satEnd = $satEnd;
        $this->dateAvailable = $dateAvailable;
        $this->minHours = $minHours;
        $this->maxHours = $maxHours;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSecurityQuestion()
    {
        return $this->securityQuestion;
    }

    public function setSecurityQuestion($securityQuestion)
    {
        $this->securityQuestion = $securityQuestion;
    }

    public function getSecurityAnswer()
    {
        return $this->securityAnswer;
    }

    public function setSecurityAnswer($securityAnswer)
    {
        $this->securityAnswer = $securityAnswer;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getMiddleInitial()
    {
        return $this->middleInitial;
    }

    public function setMiddleInitial($middleInitial)
    {
        $this->middleInitial = $middleInitial;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getHomePhone()
    {
        return $this->homePhone;
    }

    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;
    }

    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function getHasDriversLicense()
    {
        return $this->hasDriversLicense;
    }

    public function setHasDriversLicense($hasDriversLicense)
    {
        $this->hasDriversLicense = $hasDriversLicense;
    }

    public function getTransportation()
    {
        return $this->transportation;
    }

    public function setTransportation($transportation)
    {
        $this->transportation = $transportation;
    }

    public function getEmergencyContact1Name()
    {
        return $this->emergencyContact1Name;
    }

    public function setEmergencyContact1Name($emergencyContact1Name)
    {
        $this->emergencyContact1Name = $emergencyContact1Name;
    }

    public function getEmergencyContact1Relation()
    {
        return $this->emergencyContact1Relation;
    }

    public function setEmergencyContact1Relation($emergencyContact1Relation)
    {
        $this->emergencyContact1Relation = $emergencyContact1Relation;
    }

    public function getEmergencyContact1Phone()
    {
        return $this->emergencyContact1Phone;
    }

    public function setEmergencyContact1Phone($emergencyContact1Phone)
    {
        $this->emergencyContact1Phone = $emergencyContact1Phone;
    }

    public function getEmergencyContact2Name()
    {
        return $this->emergencyContact2Name;
    }

    public function setEmergencyContact2Name($emergencyContact2Name)
    {
        $this->emergencyContact2Name = $emergencyContact2Name;
    }

    public function getEmergencyContact2Relation()
    {
        return $this->emergencyContact2Relation;
    }

    public function setEmergencyContact2Relation($emergencyContact2Relation)
    {
        $this->emergencyContact2Relation = $emergencyContact2Relation;
    }

    public function getEmergencyContact2Phone()
    {
        return $this->emergencyContact2Phone;
    }

    public function setEmergencyContact2Phone($emergencyContact2Phone)
    {
        $this->emergencyContact2Phone = $emergencyContact2Phone;
    }

    public function getAllergies()
    {
        return $this->allergies;
    }

    public function setAllergies($allergies)
    {
        $this->allergies = $allergies;
    }

    public function getSunStart()
    {
        return $this->sunStart;
    }

    public function setSunStart($sunStart)
    {
        $this->sunStart = $sunStart;
    }

    public function getSunEnd()
    {
        return $this->sunEnd;
    }

    public function setSunEnd($sunEnd)
    {
        $this->sunEnd = $sunEnd;
    }

    public function getMonStart()
    {
        return $this->monStart;
    }

    public function setMonStart($monStart)
    {
        $this->monStart = $monStart;
    }

    public function getMonEnd()
    {
        return $this->monEnd;
    }

    public function setMonEnd($monEnd)
    {
        $this->monEnd = $monEnd;
    }

    public function getTueStart()
    {
        return $this->tueStart;
    }

    public function setTueStart($tueStart)
    {
        $this->tueStart = $tueStart;
    }

    public function getTueEnd()
    {
        return $this->tueEnd;
    }

    public function setTueEnd($tueEnd)
    {
        $this->tueEnd = $tueEnd;
    }

    public function getWedStart()
    {
        return $this->wedStart;
    }

    public function setWedStart($wedStart)
    {
        $this->wedStart = $wedStart;
    }

    public function getWedEnd()
    {
        return $this->wedEnd;
    }

    public function setWedEnd($wedEnd)
    {
        $this->wedEnd = $wedEnd;
    }

    public function getThuStart()
    {
        return $this->thurStart;
    }

    public function setThuStart($thurStart)
    {
        $this->thurStart = $thurStart;
    }

    public function getThuEnd()
    {
        return $this->thurEnd;
    }

    public function setThuEnd($thurEnd)
    {
        $this->thurEnd = $thurEnd;
    }

    public function getFriStart()
    {
        return $this->friStart;
    }

    public function setFriStart($friStart)
    {
        $this->friStart = $friStart;
    }

    public function getFriEnd()
    {
        return $this->friEnd;
    }

    public function setFriEnd($friEnd)
    {
        $this->friEnd = $friEnd;
    }

    public function getSatStart()
    {
        return $this->satStart;
    }

    public function setSatStart($satStart)
    {
        $this->satStart = $satStart;
    }

    public function getSatEnd()
    {
        return $this->satEnd;
    }

    public function setSatEnd($satEnd)
    {
        $this->satEnd = $satEnd;
    }

    public function getDateAvailable()
    {
        return $this->dateAvailable;
    }

    public function setDateAvailable($dateAvailable)
    {
        $this->dateAvailable = $dateAvailable;
    }

    public function getMinHours()
    {
        return $this->minHours;
    }

    public function setMinHours($minHours)
    {
        $this->minHours = $minHours;
    }

    public function getMaxHours()
    {
        return $this->maxHours;
    }

    public function setMaxHours($maxHours)
    {
        $this->maxHours = $maxHours;
    }
}