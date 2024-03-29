<?php

/*
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/psl-config.inc.php';

function html_header($title) {
    echo <<<EOD
<!DOCTYPE html>
<html lang="dk">
<head>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" type="text/css" href="/resources/css/style.css">-->
    <title>Tag 'n Treat :: $title</title>
</head>
<body>
EOD;
}

function html_footer() {
    echo <<<EOD
</body>
</html>
EOD;
}

function sec_session_start()
{
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === false) {
        show_error('Could not initiate a safe session (ini_set)');
    }

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], $secure, $httponly);

    // Sets the session name to the one set above.
    session_name($session_name);

    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
}

function login($u_name, $password, $mysqli)
{
    $u_name = strtolower($u_name);
    // Using prepared statements means that SQL injection is not possible.
    if ($stmt = $mysqli->prepare("SELECT `ID`, `Username`, `Password`, `Salt` FROM `admins` WHERE `Username` = ? LIMIT 1")) {
        $stmt->bind_param('s', $u_name);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($id, $u_name, $db_password, $salt);
        $stmt->fetch();

        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts
            if (checkbrute($id, $mysqli) == true) {
                // Account is locked
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];

                    // XSS protection as we might print this value
                    $id = preg_replace("/[^0-9]+/", "", $id);
                    $_SESSION['id'] = $id;

                    // XSS protection as we might print this value
                    $u_name = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $u_name);

                    $_SESSION['u_name'] = $u_name;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);

                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    if (!$mysqli->query("INSERT INTO `login_attempts` (`UserID`, `Time`) VALUES ('$id', '$now')")) {
                        show_error("Database error: login_attempts");
                    }

                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    } else {
        // Could not create a prepared statement
        throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
    }
}

function checkbrute($id, $mysqli)
{
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT `Time` FROM `login_attempts` WHERE `UserID` = ? AND `Time` > '$valid_attempts'")) {
        $stmt->bind_param('i', $id);

        // Execute the prepared query.
        $stmt->execute();
        $stmt->store_result();

        // If there have been more than 5 failed logins
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    } else {
        throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
    }
}

function login_check($mysqli)
{
    // Check if all session variables are set
    if (isset($_SESSION['id'], $_SESSION['u_name'], $_SESSION['login_string'])) {
        $id = $_SESSION['id'];
        $login_string = $_SESSION['login_string'];
        $u_name = $_SESSION['u_name'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT `Password` FROM `admins` WHERE `ID` = ? LIMIT 1")) {
            // Bind "$user_id" to parameter.
            $stmt->bind_param('i', $id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    return false;
                }
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Could not prepare statement
            show_error('Database error: cannot prepare statement');
        }
    } else {
        // Not logged in
        return false;
    }
}

function only_admins($mysqli) {
    sec_session_start();

    if (login_check($mysqli) != true) {
        show_error('You\'re not logged in. Please do so <a href="/login/">here</a>');
    }
}

function throw_error($stmt, $mysqli) {
    throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
}

function show_error($msg) {
    echo("<p style='color:red'>$msg</p>");
    # This function MUST exit as it's last thing!!!
    exit();
}

function show_success($msg, $url) {
    if ($url) {
        header("Refresh: 1;$url");
    }
    echo("<p style='color:green'>$msg</p>");
    # This function MUST exit as it's last thing!!!
    exit();
}
