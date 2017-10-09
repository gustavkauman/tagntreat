<?php
    include_once "../includes/db_connect.php";
    include_once "../includes/functions.php";

if (login_check($mysqli) == true) {
    $navn = filter_input(INPUT_POST, 'navn', FILTER_SANITIZE_STRING);
    $klasse = filter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);
}
