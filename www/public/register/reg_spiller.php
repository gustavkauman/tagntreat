<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

if (login_check($mysqli) == true) {
    $navn = filter_input(INPUT_POST, 'navn', FILTER_SANITIZE_STRING);
    $klasse = filter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);

    if (isset($navn, $klasse)) {
        $stmt = $mysqli->prepare('INSERT INTO players (navn, klasse) VALUES (?, ?)');
        if (!$stmt || !$stmt->bind_param('ss', $navn, $klasse) || !$stmt->execute()) {
            throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
        } else {
            $stmt->close();
            header("Location: ../register");
            exit();
        }
    }
} else {
    echo("Login fail");
}
