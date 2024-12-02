<?php



function getFormSubmissions($formName){
    switch ($formName) {
    case "Holiday Meal Bag":
        require_once("dbHolidayMealBag.php");
        return getSubmissions();
    default:
        //code block
    }
}