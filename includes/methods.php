<?php

include_once "db_connect.php"

/**
*
*/
class Admin
{
    public $error_msg = '';
    private $navn = filter_input(INPUT_POST, 'navn', FILTER_SANITIZE_STRING);
    private $klasse = sfilter_input(INPUT_POST, 'klasse', FILTER_SANITIZE_STRING);
    private $pwd = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

    public function __construct()
    {
        if (strlen($pwd) != 128) {
            $error_msg .= 'Invalid password configuration.';
        }

        if (empty($error_msg)) {
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), true));
            $password = hash('sha512', $password . $random_salt);

            $stmt = $mysqli-prepare('INSERT INTO admins (navn, klasse, password, salt) VALUES (?, ?, ?, ?)');
            if (!stmt || !stmt->bind_param('ssss', $navn, $klasse, $password, $random_salt) || !stmt->execute()) {
                throw new \Exception('Database error: ' . (!$stmt ? $mysqli->error : $stmt->error));
            } else {
                $stmt-close();
                header("Location: ../register/error.php?error=" . $error_msg);
                exit();
            }
            $stmt->close();
            header("Location: ../register/index.php?succes=true");
            exit();
        }
    }
}
