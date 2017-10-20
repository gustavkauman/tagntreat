<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/game_control.inc.php';

# Clear games
$stmt = $mysqli->prepare('DELETE FROM `games`');
if (!$stmt || !$stmt->execute()) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

show_success('Successfully restarted game!', '/game');
