<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/db_connect.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.inc.php';

sec_session_start();

if (isset($_GET['type'])) {
        $type = $_GET['type'];
} else {
        $type = '';
}

?><!DOCTYPE html>
<html>
<head>
    <title>Tag 'n Treat :: Register</title>
</head>
<body>
<?php if ($type == "admin") : ?>
    <div class="row">
        <h4>Registrer ny administrator</h4>
    </div>
    <div class="row">
        <form action="/register/reg_admin.php" method="POST">
            <div class="row">
                Brugernavn: <input type="text" name="u_name" id="u_name">
            </div>
            <div class="row">
                Navn: <input type="text" name="navn" id="navn">
            </div>
            <div class="row">
                Klasse: <input type="text" name="klasse" id="klasse">
            </div>
            <div class="row">
                Email: <input type="email" name="email" id="email">
            </div>
            <div class="row">
                Kodeord: <input type="password" name="password" id="password">
            </div>
            <div class="row">
                Bekræft kodeord: <input type="password" name="confpwd" id="confpwd">
            </div>
            <div class="row">
                <input type="button" name="btn-submit" id="btn-submit" value="Submit!" onclick="return regformhash(
                    this.form,
                    this.form.u_name,
                    this.form.navn,
                    this.form.klasse,
                    this.form.email,
                    this.form.password,
                    this.form.confpwd);">
            </div>
        </form>
    </div>
<?php else : ?>
<?php if (login_check($mysqli) == true) : ?>
    <div class="row">
        <h4>Registrer ny spiller</h4>
    </div>
    <div class="row">
        <form action="reg_spiller.php" method="POST">
            <div class="row">
                Navn: <input type="text" name="name">
            </div>
            <div class="row">
                Klasse: <input type="text" name="klasse">
            </div>
            <div class="row">
                <input type="submit" name="submit" value="Submit!">
            </div>
        </form>
    </div>
<?php else : ?>
<p>Du bør ikke være her! Log ind <a href="/login/">her</a>.</p>
<?php endif; ?>
<?php endif; ?>
<script type="text/javascript" src="../resources/js/forms.js"></script>
<script type="text/javascript" src="../resources/js/sha512.js"></script>
</body>
</html>
