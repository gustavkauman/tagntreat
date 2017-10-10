<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start();

if (login_check($mysqli) != true) {
    echo("You're not an admin. Go away!");
    exit();
}

$stmt = $mysqli->prepare("SELECT `ID` FROM `players`");
if (!$stmt || !$stmt->execute() || !$stmt->store_result()) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

$ins_stmt = $mysqli->prepare('INSERT INTO `games` (`KillerID`, `VictimID`) VALUES (?, 0)');
if (!$ins_stmt || !$ins_stmt->bind_param('i', $row['ID'])) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

while ($row = $stmt->get_result()) {
    $ins_stmt->execute();
}

$ins_stmt->close();
$stmt->close();

header("Location: succes.php");
