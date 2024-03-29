<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $klasse = filter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);

    if (isset($name, $klasse)) {
        $stmt = $mysqli->prepare('INSERT INTO `players` (`Name`, `Classroom`) VALUES (?, ?)');
        if (!$stmt || !$stmt->bind_param('ss', $name, $klasse) || !$stmt->execute()) {
            throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
        } else {
            $stmt->close();
            show_success('Successfully registered user!', '/register/');
            exit();
        }
    } else {
        show_error('Missing info!');
    }
} else {
    show_error("Login fail");
}
