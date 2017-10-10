<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Tag 'n Treat :: Login</title>
</head>
<body>
    <div class="row">
        <h4>Administrator Login</h4>
    </div>
    <div class="row">
        <form action="../includes/process_login.php" method="POST">
            <div class="row">
                Brugernavn: <input type="text" name="u_name" id="u_name">
            </div>
            <div class="row">
                Kodeord: <input type="password" name="password" id="password">
            </div>
            <div class="row">
                <input type="button" value="Login!" onclick="formhash(this.form, this.form.password);">
            </div>
        </form>
    </div>
<script type="text/javascript" src="/resources/js/forms.js"></script>
<script type="text/javascript" src="/resources/js/sha512.js"></script>
</body>
</html>
