<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start();

if (login_check($mysqli) != true) {
    echo("You're not an admin. Go away!");
    exit();
}

$stmt = $mysqli->prepare('SELECT `ID` FROM `players`');
if (!$stmt || !$stmt->execute()) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

$killer_ids = array();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $killer_ids[] = $row['ID'];
}
$res->free();
$stmt->close();

$id_count = count($killer_ids);
if ($id_count < 2) {
    throw new \Exception('The amount of people in the database should be at least two!');
}


shuffle($killer_ids); // Not cryptographically suitable :'(

/*
Example:
killer_ids = [0, 1, 2, 3, 4]
shift_amount = 3

Result:
victim_ids = [3, 4, 0, 1, 2]
*/
$shift_amount = rand(1, $id_count - 1);
$victim_ids = array_merge(array_slice($killer_ids, $shift_amount), array_slice($killer_ids, 0, $shift_amount));


$ins_stmt = $mysqli->prepare('INSERT INTO `games` (`KillerID`, `VictimID`) VALUES (?, ?)');
if (!$ins_stmt || !$ins_stmt->bind_param('ii', $killer_id, $victim_id)) {
    throw new \Exception('Database error: ' . (!$ins_stmt ? $mysqli->error : $ins_stmt->error));
}

for ($i=0; $i < $id_count; $i++) {
    $killer_id = $killer_ids[$i];
    $victim_id = $victim_ids[$i];
    $ins_stmt->execute();
}
$ins_stmt->close();

header('Location: /succes.php');
