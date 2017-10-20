<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/game_control.inc.php';

# Verify that no games are running
$stmt = $mysqli->prepare('SELECT `ID` FROM `games`');
if (!$stmt || !$stmt->execute() || !$stmt->store_result()) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

if ($stmt->num_rows !== 0) {
    show_error('The game is already going! Please reset it first!');
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

for ($i=0; $i < $id_count; $i++) {
	game_create($killer_ids[$i], $victim_ids[$i]);
}

show_success('Successfully started game!', '/game');
