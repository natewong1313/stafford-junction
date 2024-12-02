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

    // columns we wont show in the table/exported csv
    $excludedColumns = array("id", "family_id", "securityQuestion", "securityAnswer", "password", "child_id", "form_id");

    $hasSearched = isset($_GET['searchByForm']);
    $selectedFormName = $hasSearched ? $_GET['formName'] : "";

    $noResults = true;
    $searchingByForm = false;
    require_once("database/dbForms.php");
    // if we're searching by form or by form and family
    if(isset($_GET['searchByForm'])){
        $searchingByForm = true;
        $familyId = null;
        if (isset($_GET['familyAccount'])){
            $familyId = $_GET['familyAccount'];
        }
        $submissions = getFormSubmissions($_GET['formName'], $familyId);
        $noResults = count($submissions) == 0;
        if(!$noResults){
            $columnNames = array_keys($submissions[0]);
        }
    }else if(isset($_GET['searchByFamily'])){
        $familyId = $_GET['familyAccount'];
        $family = retrieve_family_by_id($familyId);
        $formNames = getFormsByFamily($familyId);
        $noResults = count($formNames) == 0;
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
            <?php if(!$noResults && $searchingByForm): ?>
                <button class="button" id="downloadButton">Download Results (.csv)</button>
            <?php endif; ?>
            <span style="margin-left: 10px;">Viewing results for: <?php echo ($searchingByForm) ? $_GET['formName'] . ' Form' : $family->getFirstName() . " " . $family->getLastName(); ?> </span>
        </form>
        <?php if(!$noResults): ?>
            <script>
                // This segment handles all the csv stuff. this could be done in php but i dont like php
                const resultsData = <?php echo json_encode($submissions); ?>;
                const columns = <?php echo json_encode($columnNames); ?>;
                const excludedColumns = <?php echo json_encode($excludedColumns); ?>;
                // creates a 2d array with the first item being an array of column names. we also strip excluded columns
                const csvHeaderRow = columns.filter(col => !excludedColumns.includes(col))
                const rows = [csvHeaderRow]
                resultsData.forEach(result => {
                    excludedColumns.forEach((col) => delete result[col])
                    rows.push(Object.values(result))
                })
                // on button click, we'll generate the csv
                document.getElementById("downloadButton").addEventListener("click", (e) => {
                    let csvContent = "data:text/csv;charset=utf-8,";
                    rows.forEach(row => csvContent += row.join(",") + "\r\n");
                    window.open(encodeURI(csvContent));
                })
            </script>
        <?php endif; ?>
        <div class="form-search-results">
            <?php if(!$noResults): ?>
                <!-- we'll change the table type depending on what data we're displaying -->
                <table class="general form-search-results-table">
                <?php if($searchingByForm): ?>
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
                <?php else: ?>
                    <thead>
                        <tr>
                            <th>Form Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="standout">
                        <?php
                            foreach($formNames as $formName){
                                echo '<tr>';
                                echo "<td>" . $formName . "</td>";
                                echo "<td><a href='./formSearchResult.php?searchByForm=searchByForm&formName=" . $formName . "&familyAccount=" . $familyId . "'>View Submissions</a></td>"; 
                                echo '<tr>';
                            }
                        ?>
                    </tbody>
                <?php endif; ?>
                </table>
            <?php else: ?>
                <p style="text-align: center;">No results found.</p>
            <?php endif; ?>
        </div>
    </body>
</html>