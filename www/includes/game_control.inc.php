<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';
only_admins($mysqli);

# Stmt is not closed, but php will do that for us
$game_start_stmt = $mysqli->prepare('INSERT INTO `games` (`KillerID`, `VictimID`) VALUES (?, ?)');
if (!$game_start_stmt || !$game_start_stmt->bind_param('ii', $k_id, $v_id)) {
    throw_error($game_start_stmt, $mysqli);
}


function get_status($killer_id, $victim_id) {
	global $mysqli;
	$stmt = $mysqli->prepare('SELECT `Status` FROM `games` WHERE `KillerID` = ? AND `VictimID` = ?');
    if (!$stmt || !$stmt->bind_param('ii', $killer_id, $victim_id) || !$stmt->bind_result($status) || !$stmt->execute() || !$stmt->store_result()) {
    	throw_error($stmt, $mysqli);
    }
    if ($stmt->num_rows !== 1) {
    	return null;
    }
    $stmt->fetch();
    return $status;
}


function set_status($killer_id, $victim_id, $status) {
	global $mysqli;
	$stmt = $mysqli->prepare('UPDATE `games` SET `Status` = ? WHERE `KillerID` = ? AND `VictimID` = ?');
	if (!$stmt || !$stmt->bind_param('sii', $status, $killer_id, $victim_id) || !$stmt->execute()) {
    	throw_error($stmt, $mysqli);
	}
}


function get_current_victim($killer_id) {
	global $mysqli;
	$stmt = $mysqli->prepare('SELECT `VictimID` FROM `games` WHERE `KillerID` = ? AND `Status` = "PENDING"');
    if (!$stmt || !$stmt->bind_param('i', $killer_id) || !$stmt->bind_result($victim_id) || !$stmt->execute() || !$stmt->store_result()) {
    	throw_error($stmt, $mysqli);
    }
    if ($stmt->num_rows !== 1) {
    	return null;
    }
    $stmt->fetch();
    return $victim_id;
}


function get_current_killer($victim_id) {
	global $mysqli;
	$stmt = $mysqli->prepare('SELECT `VictimID` FROM `games` WHERE `KillerID` = ? AND `Status` = "PENDING"');
    if (!$stmt || !$stmt->bind_param('i', $victim_id) || !$stmt->bind_result($killer_id) || !$stmt->execute() || !$stmt->store_result()) {
    	throw_error($stmt, $mysqli);
    }
    if ($stmt->num_rows !== 1) {
    	return null;
    }
    $stmt->fetch();
    return $killer_id;
}


function game_create($killer_id, $victim_id) {
	global $mysqli;
	global $game_start_stmt;
	global $k_id;
	global $v_id;
	$k_id = $killer_id;
	$v_id = $victim_id;
	if (!$game_start_stmt->execute()) {
		throw_error($game_start_stmt, $mysqli);
	}
}


function game_remove($killer_id, $victim_id) {
	global $mysqli;
	$stmt = $mysqli->prepare('DELETE FROM `games` WHERE `KillerID` = ? AND `VictimID` = ?');
	if (!$stmt || !$stmt->bind_param('ii', $killer_id, $victim_id) || !$stmt->execute()) {
    	throw_error($stmt, $mysqli);
	}
}
