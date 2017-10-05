<?php
if (isset($_GET['type'])) {
        $type = $_GET['type'];
} else {
        $type = '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tag 'n Treat :: Register</title>
</head>
<body>
<?php if ($type == "admin") : ?>
    <script type="text/javascript" src="../resources/js/forms.js"></script>
    <script type="text/javascript" src="../resources/js/sha512.js"></script>
    <div class="row">
        <h4>Registrer ny administrator</h4>
    </div>
    <div class="row">
        <form action="regAdmin.php" method="POST">
            <div class="row">
                Navn: <input type="text" name="navn" id="navn">
            </div>
            <div class="row">
                Klasse: <input type="text" name="klasse" id="klasse">
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
                    this.form.navn,
                    this.form.klasse,
                    this.form.password,
                    this.form.confpwd);">
            </div>
        </form>
    </div>
<?php else : ?>
    <div class="row">
        <h4>Registrer ny spiller</h4>
    </div>
    <div class="row">
        <form action="regSpiller.php" method="POST">
            <div class="row">
                Navn: <input type="text" name="name">
            </div>
            <div class="row">
                Klasse: <input type="text" name="class">
            </div>
            <div class="row">
                <input type="submit" name="submit" value="Submit!">
            </div>
        </form>
    </div>
<?php endif; ?>
</body>
</html>
