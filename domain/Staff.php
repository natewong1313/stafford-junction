<?php

class Staff {
    private $id;
    private $firstName;
    private $lastName;
    private $birthdate;
    private $address;
    private $email;
    private $phone;
    private $econtactName;
    private $econtactPhone;
    private $jobTitle;
    private $password;
    private $securityQuestion;
    private $securityAnswer;

    public function __construct($id, $firstName, $lastName, $birthdate, $address, $email, $phone, $econtactName, $econtactPhone, $jobTitle, $password, $securityQuestion, $securityAnswer){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthdate = $birthdate;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
        $this->econtactName = $econtactName;
        $this->econtactPhone = $econtactPhone;
        $this->jobTitle = $jobTitle;
        $this->password = $password;
        $this->securityQuestion = $securityQuestion;
        $this->securityAnswer = $securityAnswer;
    }

    //Getters

    public function getId(){
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

    public function getEmail(){
        return $this->email;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function getEContactName(){
        return $this->econtactName;
    }

    public function getEContactPhone(){
        return $this->econtactPhone;
    }

    public function getJobTitle(){
        return $this->jobTitle;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getSecurityQuestion(){
        return $this->securityQuestion;
    }

    public function getSecurityAnswer(){
        return $this->securityAnswer;
    }

}