<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

if (login_check($mysqli) == true) {
    $navn = filter_input(INPUT_POST, 'navn', FILTER_SANITIZE_STRING);
    $klasse = filter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);
}
