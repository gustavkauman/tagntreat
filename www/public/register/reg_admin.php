<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';
sec_session_start();

$stmt = $mysqli->prepare('SELECT `ID` FROM `admins`');
if (!$stmt || !$stmt->execute() || !$stmt->store_result()) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

// Allows the first admin to be created without credentials, but all subsequent admins must be created using another admin account!
if ($stmt->num_rows > 0 && login_check($mysqli) != true) {
    show_error("You're not an admin. Go away!");
}


$error_msg = "";
$u_name = filter_input(INPUT_POST, 'u_name', FILTER_SANITIZE_STRING);
$u_name = strtolower($u_name);
$navn = filter_input(INPUT_POST, 'navn', FILTER_SANITIZE_STRING);
$klasse = filter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
if (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_msg .= 'Email is not valid!';
}

if (strlen($password) != 128) {
    $error_msg .= 'Invalid password configuration.';
}

$prep_stmt = "SELECT `ID` FROM `admins` WHERE `Name` = ? AND `Classroom` = ? OR `Username` = ?";
$stmt = $mysqli->prepare($prep_stmt);
if ($stmt) {
    $stmt->bind_param('sss', $navn, $klasse, $u_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows != 0) {
        // User already exists
        $error_msg .= 'User already exists';
    }
}

if (empty($error_msg)) {
    $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), true));
    $password = hash('sha512', $password . $random_salt);

    $stmt = $mysqli->prepare('INSERT INTO `admins` (`Username`, `Name`, `email`, `Classroom`, `Password`, `Salt`) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$stmt || !$stmt->bind_param('ssssss', $u_name, $navn, $email, $klasse, $password, $random_salt) || !$stmt->execute()) {
        throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
    } else {
        $stmt->close();
        show_success('Registration successful', '/login/');
    }
} else {
    show_error($error_msg);
}
