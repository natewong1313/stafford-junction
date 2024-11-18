<?php

class Family {
    private $id;
    private $firstName;
    private $lastName;
    private $birthdate;
    private $address;
    private $neighborhood;
    private $city;
    private $state;
    private $zip;
    private $email;
    private $phone;
    private $phoneType;
    private $secondaryPhone;
    private $secondaryPhoneType;
    private $isHispanic;
    private $race;
    private $income;
    private $firstName2;
    private $lastName2;
    private $birthdate2;
    private $address2;
    private $neighborhood2;
    private $city2;
    private $state2;
    private $zip2;
    private $email2;
    private $phone2;
    private $phoneType2;
    private $secondaryPhone2;
    private $secondaryPhoneType2;
    private $isHispanic2;
    private $race2;
    private $econtactFirstName;
    private $econtactLastName;
    private $econtactPhone;
    private $econtactRelation;
    private $password;
    private $question;
    private $answer;
    private $accountType;
    private $isArchived;

    public function __construct($id, $firstName, $lastName, $birthdate, $address, $neighborhood, $city, $state,
        $zip, $email, $phone, $phoneType, $secondaryPhone, $secondaryPhoneType, $isHispanic, $race, $income, $firstName2, $lastName2, $birthdate2,
        $address2, $neighborhood2, $city2, $state2, $zip2, $email2, $phone2, $phoneType2, $secondaryPhone2, $secondaryPhoneType2,
        $isHispanic2, $race2, $econtactFirstName, $econtactLastName, $econtactPhone, $econtactRelation, $password, $question, $answer, $isArchived
    
    ){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthdate = $birthdate;
        $this->address = $address;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->email = $email;
        $this->phone = $phone;
        $this->phoneType = $phoneType;
        $this->secondaryPhone = $secondaryPhone;
        $this->secondaryPhoneType = $secondaryPhoneType;
        $this->isHispanic = $isHispanic;
        $this->race = $race;
        $this->income = $income;
        $this->firstName2 = $firstName2;
        $this->lastName2 = $lastName2;
        $this->birthdate2 = $birthdate2;
        $this->address2 = $address2;
        $this->neighborhood2 = $neighborhood2;
        $this->city2 = $city2;
        $this->state2 = $state2;
        $this->zip2 = $zip2;
        $this->email2 = $email2;
        $this->phone2 = $phone2;
        $this->phoneType2 = $phoneType2;
        $this->secondaryPhone2 = $secondaryPhone2;
        $this->secondaryPhoneType2 = $secondaryPhoneType2;
        $this->isHispanic2 = $isHispanic2;
        $this->race2 = $race2;
        $this->econtactFirstName = $econtactFirstName;
        $this->econtactLastName = $econtactLastName;
        $this->econtactPhone = $econtactPhone;
        $this->econtactRelation = $econtactRelation;
        $this->password = $password;
        $this->question = $question;
        $this->answer = $answer;
        $this->isArchived = $isArchived;
    }

    /** 
    public function getID(){
        return $this->id;
    }
    */

    public function getId() {
        return $this->id;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getBirthDate(){
        return $this->birthdate;
    }

    public function getNeighborhood(){
        return $this->neighborhood;
    }

    public function getAddress(){
        return $this->address;
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

    public function getEmail(){
        return $this->email;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function getPhoneType(){
        return $this->phoneType;
    }

    public function getSecondaryPhone(){
        return $this->secondaryPhone;
    }

    public function getSecondaryPhoneType(){
        return $this->secondaryPhoneType;
    }

    public function isHispanic(){
        return $this->isHispanic;
    }

    public function getRace(){
        return $this->race;
    }

    public function getIncome(){
        return $this->income;
    }

    //
    public function getFirstName2(){
        return $this->firstName2;
    }

    public function getLastName2(){
        return $this->lastName2;
    }

    public function getBirthDate2(){
        return $this->birthdate2;
    }

    public function getNeighborhood2(){
        return $this->neighborhood2;
    }

    public function getAddress2(){
        return $this->address2;
    }

    public function getCity2(){
        return $this->city2;
    }

    public function getState2(){
        return $this->state2;
    }

    public function getZip2(){
        return $this->zip2;
    }

    public function getEmail2(){
        return $this->email2;
    }

    public function getPhone2(){
        return $this->phone2;
    }

    public function getPhoneType2(){
        return $this->phoneType2;
    }

    public function getSecondaryPhone2(){
        return $this->secondaryPhone2;
    }

    public function getSecondaryPhoneType2(){
        return $this->secondaryPhoneType2;
    }

    public function isHispanic2(){
        return $this->isHispanic2;
    }

    public function getRace2(){
        return $this->race2;
    }

    public function getEContactFirstName(){
        return $this->econtactFirstName;
    }

    public function getEContactLastName(){
        return $this->econtactLastName;
    }

    public function getEContactPhone(){
        return $this->econtactPhone;
    }

    public function getEContactRelation(){
        return $this->econtactRelation;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getSecurityQuestion(){
        return $this->question;
    }

    public function getSecurityAnswer(){
        return $this->answer;
    }

    public function isArchived(){
        return $this->isArchived;
    }

    
}

