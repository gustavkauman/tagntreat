<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $stmt = $mysqli->prepare("SELECT `ID` FROM `players`");
    if (!$stmt || !$stmt->execute() || !$stmt->store_result()) {
        throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
    } else {
        while ($row = $stmt->get_result()) {
            if ($ins_stmt = $mysqli->prepare('INSERT INTO `game` (`killerID`, `victimID`) VALUES (?, 0)')) {
                if (!$ins_stmt->bind_param('i', $row['ID']) || !$ins_stmt->execute()) {
                    throw new \Exception("Can't bind and execute");
                } else {
                    $ins_stmt->close();
                    header("Location: succes.php");
                }
            } else {
                throw new \Exception('Could not insert into game table');
            }
            $stmt->close();
        }
    }
} else {
    echo("You're not an admin. Go away!");
}
