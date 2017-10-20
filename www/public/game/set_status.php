<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/game_control.inc.php';


# Verify parameters
if (!isset($_GET['killer_id'], $_GET['victim_id'], $_GET['status'])) {
    show_error('Missing parameters!');
}

$killer_id = intval($_GET['killer_id']);
$victim_id = intval($_GET['victim_id']);
$status = strtoupper($_GET['status']);

if ($killer_id <= 0 || $victim_id <= 0 || !in_array($_GET['status'], array('PENDING', 'PICTURE', 'VIDEO'))) {
    show_error('Invalid parameters!');
}

$old_status = get_status($killer_id, $victim_id);
if ($old_status === $status) {
    show_error('Status already set!');
}
if ($old_status === null || $old_status === 'UNCOMPLETED') {
    show_error('Invalid game!');
}

# Revert starting the new game
if ($status === 'PENDING') {
    $new_victim_id = get_current_victim($killer_id);
    if ($new_victim_id === null) {
        show_error('Invalid game!');
    }
    if (get_status($victim_id, $new_victim_id) !== 'UNCOMPLETED') {
        show_error('Cannot revert game, because another game is blocking it!');
    }
    # Preferably, these should form an atomic operation. Because this is PHP, that is unfeasible
    set_status($killer_id, $victim_id, $status);
    set_status($victim_id, $new_victim_id, 'PENDING');
    game_remove($killer_id, $new_victim_id);


# Start a new game
} else {
    if ($old_status !== 'PENDING') {
        # Change from 'PICTURE' to 'VIDEO'
        set_status($killer_id, $victim_id, $status);
        show_success('Successfully changed status');
    }
    $new_victim_id = get_current_victim($victim_id);
    if ($new_victim_id === null) {
        show_error('Invalid game!');
    }
    # These should also preferably form an atomic operation
    set_status($killer_id, $victim_id, $status);
    set_status($victim_id, $new_victim_id, 'UNCOMPLETED');
    game_create($killer_id, $new_victim_id);
}

show_success('Successfully changed status');
