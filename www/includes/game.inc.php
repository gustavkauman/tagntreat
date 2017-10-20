<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

$get_points_stmt = $mysqli->prepare('SELECT `Status` FROM `games` WHERE `KillerID` = ?');
if (!$get_points_stmt || !$get_points_stmt->bind_param('i', $_killer_id) || !$get_points_stmt->bind_result($_status)) {
    throw_error($get_points_stmt, $mysqli);
}

$is_dead_stmt = $mysqli->prepare('SELECT `ID` FROM `games` WHERE `KillerID` = ? AND `Status` = "UNCOMPLETED"');
if (!$is_dead_stmt || !$is_dead_stmt->bind_param('i', $_killer_id)) {
    throw_error($is_dead_stmt, $mysqli);
}

function get_points($id) {
    global $mysqli;
    global $get_points_stmt;
    global $_killer_id;
    global $_status;
    $_killer_id = $id;
    $get_points_stmt->execute();
    $get_points_stmt->store_result();
    if ($get_points_stmt->num_rows === 0) {
        return null;
    }
    $points = 0;
    while ($get_points_stmt->fetch()) {
        switch ($_status) {
            case 'PICTURE':
                $points += 1;
                break;
            case 'VIDEO':
                $points += 2;
                break;
            default:
                break;
        }
    }
    return $points;
}


function is_dead($id) {
    global $mysqli;
    global $is_dead_stmt;
    global $_killer_id;
    $_killer_id = $id;
    $is_dead_stmt->execute();
    $is_dead_stmt->store_result();
    if ($is_dead_stmt->num_rows > 0) {
        return true;
    }
    return false;
}


function get_players($more_data) {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT * FROM `players`');
    if (!$stmt || !$stmt->bind_result($id, $name, $classroom) || !$stmt->execute() || !$stmt->store_result()) {
        throw_error($stmt, $mysqli);
    }
    $players = array();
    while ($stmt->fetch()) {
        $players[$id] = array(
            'id' => $id,
            'name' => $name,
            'classroom' => $classroom
        );
        if ($more_data) {
            $players[$id]['points'] = get_points($id);
            $players[$id]['is_dead'] = is_dead($id);
        }
    }
    $stmt->close();
    return $players;
}
