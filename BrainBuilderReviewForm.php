<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <title>Brain Builders Review Form</title>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $tutor_name = $_POST['tutor_name'] ?? '';
    $student_name = $_POST['student_name'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $attendance = $_POST['attendance'] ?? '';
    $session_length = $_POST['session_length'] ?? '';
    $work = $_POST['work'] ?? [];
    $extra_help = $_POST['extra_help'] ?? '';
    $specific_help = $_POST['specific_help'] ?? '';
    $explanation = $_POST['explanation'] ?? '';
    $issues = $_POST['issues'] ?? '';
    $materials = $_POST['materials'] ?? '';
    $story = $_POST['story'] ?? '';
    $comments = $_POST['comments'] ?? '';

    // Validate required fields
    $errors = [];
    if (empty($tutor_name)) $errors[] = "Tutor Name is required.";
    if (empty($student_name)) $errors[] = "Student Name(s) is required.";
    if (empty($grade)) $errors[] = "Grade selection is required.";
    if (empty($attendance)) $errors[] = "Attendance status is required.";
    if (empty($session_length)) $errors[] = "Length of session is required.";
    if (empty($work)) $errors[] = "Please select at least one work area.";
    if (empty($extra_help)) $errors[] = "Please answer if additional help is needed.";
    if (empty($specific_help)) $errors[] = "Please answer if extra help is needed for a specific subject.";

    // If no errors, display success message
    if (empty($errors)) {
        echo "<h3>Form submitted successfully!</h3>";
        echo "Tutor Name: $tutor_name<br>";
        echo "Student Name(s): $student_name<br>";
        echo "Grade: $grade<br>";
        echo "Attendance: $attendance<br>";
        echo "Session Length: $session_length minutes<br>";
        echo "Work Areas: " . implode(', ', $work) . "<br>";
        echo "Additional Help on Grade Level Material: $extra_help<br>";
        echo "Specific Help Needed on Subject: $specific_help<br>";
        if (!empty($explanation)) echo "Explanation: $explanation<br>";
        if (!empty($issues)) echo "Issues: $issues<br>";
        if (!empty($materials)) echo "Materials Needed: $materials<br>";
        if (!empty($story)) echo "Story: $story<br>";
        if (!empty($comments)) echo "Comments: $comments<br>";
    } else {
        // Display errors
        echo "<h3 style='color: red;'>Please correct the following errors:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>

<h1>Brain Builders Review Form</h1>
    <div id="formatted_form">
<p>After each session, please take a few minutes to fill out this form. Thank you!</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <!-- 1. Tutor Name -->
    <label for="tutor_name">1. Tutor Name *</label><br>
    <input type="text" id="tutor_name" name="tutor_name" required><br><br>

    <!-- 2. Student Name(s) -->
    <label for="student_name">2. Student Name(s) *</label><br>
    <input type="text" id="student_name" name="student_name" required><br>
    <small>Place a comma between each name, if you are listing multiple names.</small><br><br>

    <!-- 3. Grade -->
    <p>3. Grade *</p>
    <input type="radio" id="grade_1st" name="grade" value="1st" required>
    <label for="grade_1st">1st</label><br>
    <input type="radio" id="grade_2nd" name="grade" value="2nd" required>
    <label for="grade_2nd">2nd</label><br>
    <input type="radio" id="grade_3rd" name="grade" value="3rd" required>
    <label for="grade_3rd">3rd</label><br>
    <input type="radio" id="grade_4th" name="grade" value="4th" required>
    <label for="grade_4th">4th</label><br>
    <input type="radio" id="grade_5th" name="grade" value="5th" required>
    <label for="grade_5th">5th</label><br><br>

    <!-- 4. Attendance -->
    <p>4. Attendance *</p>
    <input type="radio" id="attendance_on_time" name="attendance" value="On-time" required>
    <label for="attendance_on_time">On-time</label><br>
    <input type="radio" id="attendance_late" name="attendance" value="Late" required>
    <label for="attendance_late">Late</label><br><br>

    <!-- 5. Length of session -->
    <label for="session_length">5. Length of session (in minutes): *</label><br>
    <input type="number" id="session_length" name="session_length" required><br><br>

    <!-- 6. What did you work on today? -->
    <p>6. What did you work on today? *</p>
    <input type="checkbox" id="work_math" name="work[]" value="Math">
    <label for="work_math">Math</label><br>
    <input type="checkbox" id="work_reading" name="work[]" value="Reading">
    <label for="work_reading">Reading</label><br>
    <input type="checkbox" id="work_writing" name="work[]" value="Writing">
    <label for="work_writing">Writing</label><br>
    <input type="checkbox" id="work_science" name="work[]" value="Science">
    <label for="work_science">Science</label><br>
    <input type="checkbox" id="work_geography" name="work[]" value="Geography">
    <label for="work_geography">Geography</label><br>
    <input type="checkbox" id="work_other" name="work[]" value="Other">
    <label for="work_other">Other:</label>
    <input type="text" name="work_other"><br><br>

    <!-- 7. Additional help on grade level material -->
    <p>7. Do they need additional help on their grade level material? *</p>
    <input type="radio" id="extra_help_yes" name="extra_help" value="Yes" required>
    <label for="extra_help_yes">Yes, they need extra help, the student is behind the current grade level</label><br>
    <input type="radio" id="extra_help_no" name="extra_help" value="No" required>
    <label for="extra_help_no">No, they are on target or ahead of current grade level</label><br><br>

    <!-- 8. Extra help needed on specific subject -->
    <p>8. Is there any extra help needed on a specific subject that cannot be completed during a tutoring session? *</p>
    <input type="radio" id="specific_help_yes" name="specific_help" value="Yes" required>
    <label for="specific_help_yes">Yes, they need extra help</label><br>
    <input type="radio" id="specific_help_no" name="specific_help" value="No" required>
    <label for="specific_help_no">No</label><br><br>

    <!-- 9. Explanation if extra help needed -->
    <label for="explanation">9. If yes to one or both of the questions above, please explain:</label><br>
    <textarea id="explanation" name="explanation" rows="4" cols="50"></textarea><br><br>

    <!-- 10. Issues during session -->
    <label for="issues">10. Any issues experienced during your session?</label><br>
    <textarea id="issues" name="issues" rows="4" cols="50"></textarea><br><br>

    <!-- 11. Materials needed -->
    <label for="materials">11. Any materials that you or your student can benefit from?</label><br>
    <textarea id="materials" name="materials" rows="4" cols="50"></textarea><br><br>

    <!-- 12. Story -->
    <label for="story">12. Story</label><br>
    <textarea id="story" name="story" rows="4" cols="50"></textarea><br>
    <small>Any moment or conversation that occurred during this session that you feel like sharing :)</small><br><br>

    <!-- 13. Comments -->
    <label for="comments">13. Comments, questions, or anything extra you would like to say before submitting?</label><br>
    <textarea id="comments" name="comments" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Submit</button>
    <a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>
</form>
    </div>
</body>
</html>

