<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    ini_set("display_errors",1);
    error_reporting(E_ALL);

    if (!isset($_SESSION['_id'])) {
        header('Location: login.php');
        die();
    }


    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];

    require_once("database/dbFamily.php");
    require_once("domain/Family.php");
    $families = find_all_families();

    $searchableForms = array("Holiday Meal Bag", "School Supplies", "Spring Break", 
        "Angel Gifts Wish List", "Child Care Waiver", "Field Trip Waiver",
        "Program Interest", "Brain Builders Student Registration", "Brain Builders Holiday Party",
        "Summer Junction Registration", "Bus Monitor Attendance", "Actual Activity"
    );
    $excludedColumns = array("id", "family_id", "securityQuestion", "securityAnswer", "password");

    $hasSearched = isset($_GET['searchByForm']);
    $selectedFormName = $hasSearched ? $_GET['formName'] : "";

    if(isset($_GET['searchByForm'])){
        require_once("database/dbForms.php");
        $submissions = getFormSubmissions($_GET['formName']);
        $noResults = count($submissions) == 0;
        if(!$noResults){
            $columnNames = array_keys($submissions[0]);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Stafford Junction | View Form Submissions</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <form class="form-search-result-subheader" method="post">
            <a class="button cancel" href="formSearch.php">Back to Search</a>
            <?php if(!$noResults): ?>
                <button class="button" id="downloadButton">Download Results (.csv)</button>
            <?php endif; ?>
        </form>
        <?php if(!$noResults): ?>
            <script>
                const resultsData = <?php echo json_encode($submissions); ?>;
                const columns = <?php echo json_encode($columnNames); ?>;
                const excludedColumns = <?php echo json_encode($excludedColumns); ?>;
                // creates a 2d array with the first item being an array of column names
                const csvHeaderRow = columns.filter(col => !excludedColumns.includes(col))
                const rows = [csvHeaderRow]
                resultsData.forEach(result => {
                    excludedColumns.forEach((col) => delete result[col])
                    rows.push(Object.values(result))
                })
                document.getElementById("downloadButton").addEventListener("click", (e) => {
                    let csvContent = "data:text/csv;charset=utf-8,";
                    rows.forEach(row => csvContent += row.join(",") + "\r\n");
                    window.open(encodeURI(csvContent));
                })
            </script>
        <?php endif; ?>
        <div class="form-search-results">
            <?php if(!$noResults): ?>
            <table class="general form-search-results-table">
                <thead>
                    <tr>
                        <?php
                            foreach($columnNames as $columnName){
                                if(!in_array($columnName, $excludedColumns)){
                                    echo '<th>' . $columnName . '</th>';
                                }
                            }
                        ?>
                    </tr>
                </thead>
                <tbody class="standout">
                    <?php
                        foreach($submissions as $submission){
                            echo '<tr>';
                            foreach($submission as $columnName => $column){
                                if(!in_array($columnName, $excludedColumns)){
                                    echo "<td>" . $column . "</td>";
                                }
                            }
                            echo '<tr>';
                        }
                    ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center;">No results found.</p>
            <?php endif; ?>
        </div>
    </body>
</html>