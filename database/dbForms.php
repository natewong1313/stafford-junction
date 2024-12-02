<?php

// constant of all form names
const SEARCHABLE_FORMS = array("Holiday Meal Bag", "School Supplies", "Spring Break", 
        "Angel Gifts Wish List", "Child Care Waiver", "Field Trip Waiver",
        "Program Interest", "Brain Builders Student Registration", "Brain Builders Holiday Party",
        "Summer Junction Registration", "Bus Monitor Attendance", "Actual Activity"
    );

function getFormSubmissions($formName, $familyId){
    switch ($formName) {
    case "Holiday Meal Bag":
        require_once("dbHolidayMealBag.php");
        if ($familyId){
            return getHolidayMealBagSubmissionsById($familyId);
        }
        return getHolidayMealBagSubmissions();
    case "School Supplies":
        require_once("dbSchoolSuppliesForm.php");
        if ($familyId){
            return getSchoolSuppliesSubmissionsFromFamily($familyId);
        }
        return getSchoolSuppliesSubmissions();
    case "Spring Break":
        require_once("dbSpringBreakCampForm.php");
        if ($familyId) {
            return getSpringBreakCampSubmissionsFromFamily($familyId);
        }
        return getSpringBreakCampSubmissions();
    case "Angel Gifts Wish List":
        require_once("dbAngelGiftForm.php");
        if ($familyId) {
            return getAngelGiftSubmissionsFromFamily($familyId);
        }
        return getAngelGiftSubmissions();
    case "Child Care Waiver":
        require_once("dbChildCareForm.php");
        if ($familyId) {
            return getChildCareWaiverSubmissionsFromFamily($familyId);
        }
        return getChildCareWaiverSubmissions();
    case "Field Trip Waiver":
        require_once("dbFieldTripWaiverForm.php");
        if ($familyId) {
            return getFieldTripWaiverSubmissionsFromFamily($familyId);
        }
        return getFieldTripWaiverSubmissions();
    case "Program Interest":
        require_once("dbProgramInterestForm.php");
        if ($familyId) {
            return getProgramInterestSubmissionsFromFamily($familyId);
        }
        return getProgramInterestSubmissions();
    
    // These need completed backends first
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
        // case "Actual Activity":
        //     require_once("dbActualActivityForm.php");
        //     return getActualActivitySubmissions();
    default:
    }
}

function getFormsByFamily($familyId){
    // Names of all forms the family has completed
    $completedFormNames = array();
    foreach (SEARCHABLE_FORMS as $formName) {
        // get the form submissions, which is an array of objects. each object contains completed form data
        $results = getFormSubmissions($formName, $familyId);
        if(!$results){
            continue;
        }
        foreach ($results as $_){
            array_push($completedFormNames, $formName);
        }
    }
    return $completedFormNames;
}

