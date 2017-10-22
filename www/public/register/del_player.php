<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';
only_admins($mysqli);

if (!isset($_REQUEST['user_id'])) {
    show_error('Missing parameters!');
}

$user_id = intval($_REQUEST['user_id']);
if ($user_id <= 0) {
    show_error('Invalid parameters!');
}

$stmt = $mysqli->prepare('SELECT `ID` FROM `games`');
if (!$stmt || !$stmt->execute() || !$stmt->store_result()) {
    throw_error($stmt, $mysqli);
}

if ($stmt->num_rows !== 0) {
    show_error('The game has been started. Please stop it before attempting to remove players!');
}

$stmt = $mysqli->prepare('DELETE FROM `players` WHERE `ID` = ?');
if (!$stmt || !$stmt->bind_param('i', $user_id) || !$stmt->execute()) {
    throw_error($stmt, $mysqli);
}

show_success('Successfully deleted player', '/game/');
