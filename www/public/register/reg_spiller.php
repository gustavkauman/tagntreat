<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

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
            header("Location: ../register");
            exit();
        }
    } else {
        echo("Missing info!");
    }
} else {
    echo("Login fail");
}
