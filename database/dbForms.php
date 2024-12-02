<?php



function getFormSubmissions($formName){
    switch ($formName) {
    case "Holiday Meal Bag":
        require_once("dbHolidayMealBag.php");
        return getSubmissions();
    case "School Supplies":
        require_once("dbSchoolSuppliesForm.php");
        return getSubmissions();
    case "Spring Break":
        require_once("dbSpringBreakCampForm.php");
        return getSubmissions();
    case "Angel Gifts Wish List":
        require_once("dbAngelGiftForm.php");
        return getSubmissions();
    case "Child Care Waiver":
        require_once("dbChildCareForm.php");
        return getSubmissions();
    case "Field Trip Waiver":
        require_once("dbFieldTripWaiverForm.php");
        return getSubmissions();
    case "Program Interest":
        require_once("dbProgramInterestForm.php");
        return getSubmissions();
    // case "Brain Builders Student Registration":
    //     require_once(".php");
    //     return getSubmissions();
    // case "Brain Builders Holiday Party":
    //     require_once(".php");
    //     return getSubmissions();
    // case "Summer Junction Registration":
    //     require_once(".php");
    //     return getSubmissions();
    // case "Bus Monitor Attendance":
    //     require_once(".php");
    //     return getSubmissions();
    case "Actual Activity":
        require_once("dbActualActivityForm.php");
        return getSubmissions();
    default:
    }
}