<?php
/**
 * Encapsulated version of a dbAppointments entry.
 */
class Appointment {
    private $id;
    private $name;
    private $abbrevName;
    private $date;
    private $startTime;
    private $description;
    private $location;
    private $postMedia;
    private $animal;

    function __construct($id, $name, $abbrevName, $date, $startTime, $description, $location, $postMedia, $animal) {
        $this->id = $id;
        $this->name = $name;
        $this->abbrevName = $abbrevName;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->description = $description;
        $this->location = $location;
        $this->postMedia = $postMedia;
        $this->animal = $animal;
    }

    function getID() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getAbbrevName() {
        return $this->abbrevName;
    }

    function getDate() {
        return $this->date;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getDescription() {
        return $this->description;
    }

    function getLocation() {
        return $this->location;
    }

    function getPostMedia() {
        return $postMedia;
    }

    function getAnimal() {
        return $animal;
    }
}