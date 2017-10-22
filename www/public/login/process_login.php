<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['u_name'], $_POST['p'])) {
    $u_name = $_POST['u_name'];
    $password = $_POST['p']; // The hashed password.

    if (login($u_name, $password, $mysqli) == true) {
        // Login success
        show_success('Successfully logged in', '/game/');
    } else {
        // Login failed
        show_error('Login failed');
    }
}

show_error('Invalid request');
