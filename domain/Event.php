<?php
/**
 * Encapsulated version of a dbEvents entry.
 */
class Event {
    private $id;
    private $name;
    private $abbrevName;
    private $date;
    private $startTime;
    private $endTime;
    private $description;
    private $location;
    private $capacity;
    private $trainingMedia;
    private $postMedia;
    private $animalId;

    function __construct($id, $name, $abbrevName, $date, $startTime, $endTime, $description, $location, $capacity, $trainingMedia, $postMedia, $animalId) {
        $this->id = $id;
        $this->name = $name;
        $this->abbrevName = $abbrevName;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->trainingMedia = $trainingMedia;
        $this->postMedia = $postMedia;
        $this->animalId = $animalId;
    }

    function getID() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getAbbreviatedName() {
        return $this->abbrevName;
    }

    function getDate() {
        return $this->date;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getEndTime() {
        return $this->endTime;
    }

    function getDescription() {
        return $this->description;
    }

    function getLocation() {
        return $this->location;
    }

    function getCapacity() {
        return $this->capacity;
    }


    function getTrainingMedia() {
        return $trainingMedia;
    }

    function getPostMedia() {
        return $postMedia;
    }

    function getAnimalId() {
        return $animalId;
    }
}