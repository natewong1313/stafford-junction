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

    $hasSearched = isset($_GET['searchByForm']);

    if(isset($_GET['searchByForm'])){
        require_once("database/dbForms.php");
        $submissions = getFormSubmissions($_GET['formName']);
        $noResults = count($submissions) == 0;
        if(!$noResults){
            $columnNames = array_keys( reset($submissions));
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
        <h1>View Form Submissions</h1>
        <main class="formSubmissions">
            <div class="formSearch">
                <p>Search for a specific form, a specific family, or both</p>
                <form id="formSearch" method="get">
                    <label for="searchByForm"><input type="checkbox" id="searchByForm" name="searchByForm" value="searchByForm"> Form Name</label>
                    <select id="formName" name="formName" disabled>
                        <?php
                            foreach($searchableForms as $form){
                                echo '<option value="'.$form.'">'.$form.'</option>';
                            }
                        ?>
                    </select>
                    <label for="searchByFamily"><input type="checkbox" id="searchByFamily" name="searchByFamily" value="searchByFamily"> Family Account</label>
                    <select id="familyAccount" name="familyAccount" disabled>
                        <?php
                            foreach($families as $fam){
                                $name = $fam->getFirstName() . " " . $fam->getLastName();
                                echo '<option value="'.$fam->getId().'">'.$name.'</option>';
                            }
                        ?>
                    </select>
                    <p id="password-match-error" class="error hidden">Passwords must match!</p>
                    <input type="submit" value="Search">
                    <a class="button cancel" href="index.php">Return to Dashboard</a>
                </form>
                <script>
                    const isSearchingByForm = <?php echo isset($_GET['searchByForm']) ? 'true' : 'false'; ?>;
                    document.getElementById("searchByForm").addEventListener("change", (e) => {
                        const selectBox = document.getElementById("formName");
                        if (e.currentTarget.checked){
                            selectBox.removeAttribute("disabled");
                        }else{
                            selectBox.selectedIndex = 0;
                            selectBox.setAttribute("disabled", "disabled");
                        }
                    })
                    if(isSearchingByForm) {
                        document.getElementById("searchByForm").click();
                    }
                    const isSearchingByName = <?php echo isset($_GET['searchByFamily']) ? 'true' : 'false'; ?>;
                    document.getElementById("searchByFamily").addEventListener("change", (e) => {
                        const selectBox = document.getElementById("familyAccount");
                        if (e.currentTarget.checked){
                            selectBox.removeAttribute("disabled")
                        }else{
                            selectBox.selectedIndex = 0;
                            selectBox.setAttribute("disabled", "disabled");
                        }
                    })
                    if(isSearchingByName) {
                        document.getElementById("searchByFamily").click();
                    }
                </script>
            </div>
            <div class="formSearchResults" style="display: <?php echo $hasSearched ? 'block' : 'none'; ?>;">
                <table class="general">
                    <thead>
                        <tr>
                            <?php
                                foreach($columnNames as $columnName){
                                    echo '<th>' . $columnName . '</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody class="standout">
                        <?php
                            foreach($submissions as $submission){
                                echo '<tr>';
                                foreach($submission as $column){
                                    echo "<td>" . $column . "</td>";
                                }
                                echo '<tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </body>
</html>