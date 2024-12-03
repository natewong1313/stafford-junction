<?php

session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
$success = false;

if(isset($_SESSION['_id'])){
    require_once('domain/Children.php');
    require_once('database/dbChildren.php');
    require_once('include/input-validation.php');
    require_once('database/dbAngelGiftForm.php');
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
    $children = retrieve_children_by_family_id($_GET['id'] ?? $userID);
} else {
    header('Location: login.php');
    die();
}

include_once("database/dbFamily.php");
$family = retrieve_family_by_id($_GET['id'] ?? $userID);
$family_email = $family->getEmail();
$family_full_name = $family->getFirstName() . " " . $family->getLastName();
$family_phone = $family->getPhone();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $args = sanitize($_POST, null);
    $required = array("email", "parent_name", "phone", "child_name", "gender", "age", "wants", "interests", "photo_release");
    $args['phone'] = validateAndFilterPhoneNumber($args['phone']);
    if (!$args['phone']) {
        echo "phone number invalid";
    } else if(!wereRequiredFieldsSubmitted($args, $required)){
        echo "Not all fields complete";
        die();
    } else {
        $success = createAngelGiftForm($args);
    }
}


?>

<html>
    <head>
        <?php include_once("universal.inc")?>
        <title>Angel Gifts Wish Form</title>
    </head>
    <body>
        
        <h1>Angel Gifts Wish Form / Formulario de deseos de regalos de ángeles</h1>
        <div id="formatted_form">
            <p>Subject to availability / Sijeto a disponibilidad</p>

            <p>Based on donations, requests will be processed on a first come first served basis /
                Basado en donaciones, solicutudes seran procesadas en orden que sean recinidas
            </p>
            
            <br>
            
            <span>* Indicates required</span><br><br>

            <form id="angelGiftForm" action="" method="post">
                <h2>Email / Correo electrónico</h2>
                <br>

                <!-- 1. Email-->
                <label for="email">1. Email*</label><br><br>
                <input type="email" name="email" id="email" placeholder="Email/Correo electrónico" required value="<?php echo htmlspecialchars($family_email); ?>">
                <br><br><hr>

                <h2>Information / Información</h2>
                <br>
                
                <!-- 2. Parent Name-->
                <label for="parent_name">2. Mom or Dad Name / Nombre de mamá o papá*</label><br><br>
                <input type="text" name="parent_name" id="parent_name" placeholder="Name/Nombre" required value="<?php echo htmlspecialchars($family_full_name); ?>"><br><br>

                <!-- 3. Phone-->
                <label for="phone">3. Phone Number / Número de teléfono*</label><br><br>
                <input type="tel" name="phone" id="phone" placeholder="Phone Number/Número de teléfono" required value="<?php echo htmlspecialchars($family_phone); ?>"><br><br>

                <!--4. Child Name-->
                <label for="child_name">4. Name of Child / Nombre del niño\a*</label><br><br>
                <select name="child_name" id="child_name" required>
                    <option disabled selected>Select a child</option>
                <?php
                    require_once('domain/Children.php');
                    foreach ($children as $c){
                        $id = $c->getID();
                        // Check if form was already completed for the child
                        if (!isAngelGiftFormComplete($id)) {
                            $name = $c->getFirstName() . " " . $c->getLastName();
                            $value = $id . "_" . $name;
                            echo "<option value='$value'>$name</option>";
                        }
                    }
                ?>
                </select>
                <script>
                    const children = <?php echo json_encode($children); ?>;
                    document.getElementById("child_name").addEventListener("change", (e) => {
                        const childId = e.target.value.split("_")[0];
                        const childData = children.find(child => child.id === childId);
                        if(childData.gender === "male"){
                            document.getElementById("choice_1").click();
                        }else if (childData.gender === "female"){
                            document.getElementById("choice_2").click();
                        }
                        // https://stackoverflow.com/questions/4060004/calculate-age-given-the-birth-date-in-the-format-yyyymmdd
                        const today = new Date();
                        const birthDate = new Date(childData.birthdate);
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const month = today.getMonth() - birthDate.getMonth();
                        if(month < 0 || (month === 0 && today.getDate() < birthDate.getDate())){
                            age--;
                        }
                        document.getElementById("age").value = age;
                    })
                </script>
                <br><br>

                <!--5. Boy or Girl--->
                <label>5. Boy or Girl / Niño o Niña</label><br><br>
                <p>Mark only one oval</p><br>
                <input type="radio" id="choice_1" name="gender" value="boy" required>
                <label for="gender_boy">Boy / Niño</label><br><br>
                <input type="radio" id="choice_2" name="gender" value="girl" required>
                <label for="gender_girl">Girl / Niña</label><br><br>

                <!--6. Child Age -->
                <label for="age">6. Age / Cuántos años*</label><br><br>
                <input type="text" name="age" id="age" placeholder="Age/Cuántos años" required>
                <br><br><hr>

                <h2>Clothing sizes / Tallas de ropa</h2>
                <br>

                <!-- 7. Pants Size -->
                <label for="pants_size">Pants / Pantalones</label>
                <select id="pants_size" name="pants_size">
                    <option value="">--</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="XXXL">XXXL</option>
                </select>

                <!-- 8. Shirt Size -->
                <label for="shirt_size">Shirt / Camisa</label>
                <select id="shirt_size" name="shirt_size">
                    <option value="">--</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="XXXL">XXXL</option>
                </select>

                <!-- 9. Shoe Size -->
                <label for="shoe_size">Shoes / Zapatos</label>
                <select id="shoe_size" name="shoe_size">
                    <option value="">--</option>
                    <option value="5">5</option>
                    <option value="5.5">5.5</option>
                    <option value="6">6</option>
                    <option value="6.5">6.5</option>
                    <option value="7">7</option>
                    <option value="7.5">7.5</option>
                    <option value="8">8</option>
                    <option value="8.5">8.5</option>
                    <option value="9">9</option>
                    <option value="9.5">9.5</option>
                    <option value="10">10</option>
                    <option value="10.5">10.5</option>
                    <option value="11">11</option>
                    <option value="11.5">11.5</option>
                    <option value="12">12</option>
                    <option value="12.5">12.5</option>
                    <option value="13">13</option>
                    <option value="13.5">13.5</option>
                    <option value="14">14</option>
                    <option value="14.5">14.5</option>
                    <option value="15">15</option>
                    <option value="15.5">15.5</option>
                    <option value="16">16</option>
                </select>

                <!-- 10. Coat Size -->
                <label for="coat_size">Coat / Saco</label>
                <select id="coat_size" name="coat_size">
                    <option value="">--</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="XXXL">XXXL</option>
                </select>

                <!-- 11. Underwear Size -->
                <label for="underwear_size">Underwear / Calzones</label>
                <select id="underwear_size" name="underwear_size">
                    <option value="">--</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="XXXL">XXXL</option>
                </select>

                <!-- 12. Sock Size -->
                <label for="sock_size">Socks / Calcetines</label>
                <select id="sock_size" name="sock_size">
                    <option value="">--</option>
                    <option value="5">5</option>
                    <option value="5.5">5.5</option>
                    <option value="6">6</option>
                    <option value="6.5">6.5</option>
                    <option value="7">7</option>
                    <option value="7.5">7.5</option>
                    <option value="8">8</option>
                    <option value="8.5">8.5</option>
                    <option value="9">9</option>
                    <option value="9.5">9.5</option>
                    <option value="10">10</option>
                    <option value="10.5">10.5</option>
                    <option value="11">11</option>
                    <option value="11.5">11.5</option>
                    <option value="12">12</option>
                    <option value="12.5">12.5</option>
                    <option value="13">13</option>
                    <option value="13.5">13.5</option>
                    <option value="14">14</option>
                    <option value="14.5">14.5</option>
                    <option value="15">15</option>
                    <option value="15.5">15.5</option>
                    <option value="16">16</option>
                </select>
                <br><br><br><hr>

                <h2>Wish list / Lista de deseos</h2>
                <br>

                <!-- 13. Four Wants -->
                <label for="wants">13. List 4 Things You Want / Enumera 4 cosas que quieres *</label>
                <textarea name="wants" id="wants" rows="5" required></textarea><br><br>

                <!-- 14. Interests -->
                <label for="interests">14. Favorite Sports Teams / Equipos deportivos favoritos,
                     Favorite color / Color favorito,
                     Interests / Intereses *
                </label>
                <textarea name="interests" id="interests" rows = "5" required></textarea><br><br>
                <hr>

                <h2>Photo Release / Autorización de Prensa</h2>
                <br>

                <!-- 15. Photo Release -->
                <label for="photo_release">15. Stafford Junction Photo Release / Autorización de Prensa de Stafford Junction *</label>
                <br><br>
                <p>Stafford Junction Photo Release. I/We give to Stafford Junction, its successors or assigns,
                    the right to reproduce in any of its printed and online publications (such as newsletters,
                    annual reports, websites, social media posts and blog posts) all pictures that it has
                    produced of myself and/or my child(ren).
                </p>
                <p>
                    Autorización de Prensa de Stafford Junction. Entregamos a Stafford Junction, sus
                    sucesores o cesionarios, el derecho a reproducir en cualquiera de sus publicaciones
                    impresas y en línea (como boletines informativos, informes anuales, páginas web,
                    publicaciones en redes sociales y entradas de blog) todas las imágenes que ha producido
                    de mí y/o de mis hijos
                </p><br>
                <input type="radio" id="release_yes" name="photo_release" value="1" required>
                <label for="release_yes">Yes / Si</label><br><br>
                <input type="radio" id="release_no" name="photo_release" value="0" required>
                <label for="release_no">No / No</label><br><br>

                <button type="submit" id="submit">Submit</button>
                <?php 
                    if (isset($_GET['id'])) {
                        echo '<a class="button cancel" href="fillForm.php?id=' . $_GET['id'] . '" style="margin-top: .5rem">Cancel</a>';
                    } else {
                        echo '<a class="button cancel" href="fillForm.php" style="margin-top: .5rem">Cancel</a>';
                    }
                ?>
            </form>
        </div>
        <?php
           //if registration successful, create pop up notification and direct user back to login
           if($_SERVER['REQUEST_METHOD'] == "POST" && $success){
            if (isset($_GET['id'])) {
                echo '<script>document.location = "fillForm.php?formSubmitSuccess&id=' . $_GET['id'] . '";</script>';
            } else {
                echo '<script>document.location = "fillForm.php?formSubmitSuccess";</script>';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && !$success) {
            if (isset($_GET['id'])) {
                echo '<script>document.location = "fillForm.php?formSubmitFail&id=' . $_GET['id'] . '";</script>';
            } else {
                echo '<script>document.location = "fillForm.php?formSubmitFail";</script>';
            }
        }
        ?>
        
    </body>
</html>