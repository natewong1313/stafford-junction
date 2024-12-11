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

    require_once("database/dbForms.php");
    // SEARCHABLE_FORMS
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
        <div class="formSearch">
            <p>Search for a specific form, a specific family, or both</p>
            <form id="formSearch" method="get" action="formSearchResult.php">
                <label for="searchByForm"><input type="checkbox" autocomplete="off" id="searchByForm" name="searchByForm" value="searchByForm"> Form Name</label>
                <select id="formName" name="formName" disabled>
                    <?php
                        foreach(SEARCHABLE_FORMS as $form){
                            if($selectedFormName == $form){
                                echo '<option value="'.$form.'" selected>'.$form.'</option>';
                            }else{
                                echo '<option value="'.$form.'">'.$form.'</option>';
                            }
                        }
                    ?>
                </select>
                <label for="searchByFamily"><input type="checkbox" autocomplete="off" id="searchByFamily" name="searchByFamily" value="searchByFamily"> Family Account</label>
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
                document.getElementById("searchByForm").addEventListener("change", (e) => {
                    const selectBox = document.getElementById("formName");
                    if (e.currentTarget.checked){
                        selectBox.removeAttribute("disabled");
                    }else{
                        selectBox.selectedIndex = 0;
                        selectBox.setAttribute("disabled", "disabled");
                    }
                })
                document.getElementById("searchByFamily").addEventListener("change", (e) => {
                    const selectBox = document.getElementById("familyAccount");
                    if (e.currentTarget.checked){
                        selectBox.removeAttribute("disabled")
                    }else{
                        selectBox.selectedIndex = 0;
                        selectBox.setAttribute("disabled", "disabled");
                    }
                })
            </script>
        </div>
    </body>
</html>