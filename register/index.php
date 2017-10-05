<!DOCTYPE html>
<html>
<head>
    <title>Tag 'n Treat :: Register</title>
</head>
<body>
<?php if ($_GET['type'] == "admin") : ?>
    <div class="row">
        <h4>Registrer ny administrator</h4>
    </div>
    <div class="row">
        <form action="regAdmin.php" method="POST">
            <div class="row">
                Navn: <input type="text" name="name" id="name">
            </div>
            <div class="row">
                Klasse: <input type="text" name="class" id="class">
            </div>
            <div class="row">
                Kodeord: <input type="password" name="password" id="password">
            </div>
            <div class="row">
                Bekræft kodeord: <input type="password" name="conf" id="conf">
            </div>
            <div class="row">
                <input type="button" name="btn-submit" id="btn-submit" value="Submit!" onclick="return regformhash(this.form,
                    this.name,
                    this.class,
                    this.password,
                    this.conf);">
            </div>
        </form>
    </div>
    <script type="text/javascript" src="../resources/js/forms.js"></script>
    <script type="text/javascript" src="../resources/js/sha512.js"></script>
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
                <input type="submit" name="submit" value="submit">
            </div>
        </form>
    </div>
<?php endif; ?>
</body>
</html>
