<?php

session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php require ('universal.inc'); ?>
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Family Dashboard</h1>
        <?php echo "Hello there " . $_SESSION["f_name"];?>
    </body>
</html>