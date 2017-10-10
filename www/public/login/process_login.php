<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['u_name'], $_POST['p'])) {
    $email = $_POST['u_name'];
    $password = $_POST['p']; // The hashed password.

    if (login($u_name, $password, $mysqli) == true) {
        // Login success
        header('Location: ../register');
    } else {
        // Login failed
        header('Location: ../index.php?error=1');
    }
} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}
