<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/game.inc.php';
only_admins($mysqli);

html_header('Rediger data');

$players = get_players(false);



$stmt = $mysqli->prepare('SELECT `KillerID`, `VictimID` FROM `games` WHERE `Status` = "PENDING" ORDER BY `KillerID` ASC');
if (!$stmt || !$stmt->bind_result($killer_id, $victim_id) || !$stmt->execute() || !$stmt->store_result()) {
    throw_error($stmt, $mysqli);
}

$game_started = !($stmt->num_rows === 0);

if ($stmt->num_rows === 0) :?>
    <div class="row">
        <h4>Spillet er ikke blevet startet endnu. Start det <a href="/game/start.php">her</a></h4>
    </div>
<?php elseif ($stmt->num_rows === 1) : ?>
    <?php $stmt->fetch(); ?>
    <div class="row">
        <h4>Spillet er slut. Den sidste levende var <?php echo "{$players[$killer_id]['name']} fra {$players[$killer_id]['classroom']}"; ?>. Stop det <a href='/game/delete.php'>her</a></h4>
    </div>
<?php else: ?>
    <div class="row">
        <h4>Nutidige</h4>
    </div>
    <div class="holder">
        <?php while ($stmt->fetch()) {?>
        <div class="row">
            <form action="/game/set_status.php" method="POST">
                <input type="hidden" name="killer_id" value="<?php echo $players[$killer_id]['id']; ?>">
                <input type="hidden" name="victim_id" value="<?php echo $players[$victim_id]['id']; ?>">

                <span>Morder: <?php echo "{$players[$killer_id]['name']}, {$players[$killer_id]['classroom']}"; ?></span>
                <span>Offer: <?php echo "{$players[$victim_id]['name']}, {$players[$victim_id]['classroom']}"; ?></span>
                <select name="status">
                    <option value="PENDING" selected>Ikke udført</option>
                    <option value="PICTURE">Dræbt med billede</option>
                    <option value="VIDEO">Dræbt med video</option>
                </select>
                <input type="submit" value="Gem">
            </form>
        </div>
        <?php }?>
    </div>
<?php endif;$stmt->close();?>



<?php
$stmt = $mysqli->prepare('SELECT `KillerID`, `VictimID`, `Status` FROM `games` WHERE `Status` = "PICTURE" OR `Status` = "VIDEO" ORDER BY `ID` DESC');
if (!$stmt || !$stmt->bind_result($killer_id, $victim_id, $status) || !$stmt->execute() || !$stmt->store_result()) {
    throw_error($stmt, $mysqli);
}

$kills = array();
while ($stmt->fetch()) {
    if (!isset($kills[$killer_id])) {
        $kills[$killer_id] = array(
            'killer_id' => $killer_id,
            'victim_statuses' => array()
        );
    }
    $kills[$killer_id]['victim_statuses'][$victim_id] = $status;
}

if ($stmt->num_rows !== 0) :?>
    <div class="row">
        <h4>Tidligere</h4>
    </div>
    <div class="holder">
        <?php foreach ($kills as $killer_id => $kill) {?>
            <div class="row">
                <h5><?php echo "{$players[$killer_id]['name']}, {$players[$killer_id]['classroom']}";?>:</h5>
                <div class="holder">
                <?php foreach ($kill['victim_statuses'] as $victim_id => $status) {?>
                    <div class="row">
                    <form action="/game/set_status.php" method="POST">
                        <input type="hidden" name="killer_id" value="<?php echo $players[$killer_id]['id']; ?>">
                        <input type="hidden" name="victim_id" value="<?php echo $players[$victim_id]['id']; ?>">

                        <span><?php echo "{$players[$victim_id]['name']}, {$players[$victim_id]['classroom']}";?></span>
                        <select name="status">
                            <option value="PENDING">Ikke udført</option>
                            <option value="PICTURE"<?php echo ($status == 'PICTURE') ? ' selected': '';?>>Dræbt med billede</option>
                            <option value="VIDEO"<?php echo ($status == 'VIDEO') ? ' selected': '';?>>Dræbt med video</option>
                        </select>
                        <input type="submit" value="Gem">
                    </form>
                    </div>
                <?php }?>
                </div>
            </div>
        <?php }?>
    </div>
<?php endif;$stmt->close();?>

    <div class="row">
        <h4>Spillere:</h4>
    </div>
    <div class="holder">
        <?php foreach ($players as $player_id => $player) {?>
        <div class="row">
            <form action="/register/del_player.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $player_id; ?>">

                <span><?php echo "{$player['name']}, {$player['classroom']}"; ?></span>
                <input type="submit" value="Slet"<?php echo ($game_started) ? ' disabled="true"' : ''; ?>>
            </form>
        </div>
        <?php }?>
    </div>
    <a href="/register/">Tilføj nye spillere her</a>
<?php
$stmt = $mysqli->prepare('SELECT `Name`, `Classroom`, `Email` FROM `admins`');
if (!$stmt || !$stmt->bind_result($admin_name, $admin_classroom, $admin_email) || !$stmt->execute() || !$stmt->store_result()) {
    throw_error($stmt, $mysqli);
}
?>
    <div class="row">
        <h4>Admins:</h4>
    </div>
    <div class="holder">
        <?php while ($stmt->fetch()) {?>
        <div class="row">
            <span><?php echo "$admin_name, $admin_classroom, $admin_email"; ?></span>
        </div>
        <?php }?>
    </div>
    <br>
    <br>
    <a href="/login/logout.php">Log ud</a>

<?php html_footer();
