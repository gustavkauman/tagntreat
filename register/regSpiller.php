<?php
    include_once "../includes/db_connect.php";
    include_once "../includes/functions.php";
    include_once "../includes/methods.php";

if (login_check($mysqli) == true)
    $name = strip_tags($_POST['name']);
    $klasse = strip_tags($_POST['class']);
