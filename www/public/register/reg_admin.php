<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';


$error_msg = "";
$u_name = filter_input(INPUT_POST, 'u_name', FILTER_SANITIZE_STRING);
$navn = filter_input(INPUT_POST, 'navn', FILTER_SANITIZE_STRING);
$klasse = filter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

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

    $stmt = $mysqli->prepare('INSERT INTO `admins` (`Username`, `Name`, `Classroom`, `Password`, `Salt`) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt || !$stmt->bind_param('sssss', $u_name, $navn, $klasse, $password, $random_salt) || !$stmt->execute()) {
        throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
    } else {
        $stmt->close();
        header("Location: /login/index.php?succes=true");
        exit();
    }
} else {
    header("Location: /register/error.php?error=$error_msg");
    exit();
}
