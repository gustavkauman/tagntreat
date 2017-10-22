<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/game.inc.php';

if (isset($_GET['refresh'])) {
    $refresh = intval($_GET['refresh']);
    if ($refresh < 1) {
        show_error('The "refresh" parameter was incorrectly set!');
    }
    header("Refresh: $refresh");
}


$players = get_players(true);
$players = array_filter($players, function($val) {
    return $val['points'] !== null;
});
uasort($players, function($a, $b) {
    return $a['points'] < $b['points'];
});
$players_with_points = array_filter($players, function($val) {
    return $val['points'];
});
$players_top_10 = array_slice($players_with_points, 0, 10);
$players_alive = array_filter($players, function($val) {
    return !$val['is_dead'];
});

?><!DOCTYPE html>
<html lang="dk">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/resources/css/style.css">
    <title>Tag 'n Treat :: Traditionsudvalget</title>
</head>
<body>
<div class="row logo">
    <img src="resources/img/traditionsudvalg_logo.png">
</div>
<div class="row">
    <h2 class="thing">Tag and Treat</h2>
</div>
<div class="tables row">
    <div class="col span_1_of_2">
        <table class="top10">
            <tr><td class="table-title"><h2>Top<?php echo (count($players_with_points) >= 10) ? ' 10' : '' ?></h2></td></tr>
            <?php if (count($players_with_points) > 0):?>
            <?php foreach ($players_top_10 as $player) {?>
            <tr><td><?php echo $player['name'];?>, <?php echo $player['classroom'];?> - <?php echo $player['points'];?></td></tr>
            <?php }?>
            <?php else:?>
            <tr><td>Ingen spillere endnu</td></tr>
            <?php endif;?>
        </table>
    </div>
<?php if (count($players_alive) > 1):?>
    <div class="col span_1_of_2">
        <table class="players">
            <tr><td class="table-title"><h2>Levende Spillere</h2></td></tr>
            <?php foreach ($players_alive as $player) {?>
            <tr><td><?php echo $player['name'];?>, <?php echo $player['classroom'];?></td></tr>
            <?php }?>
        </table>
    </div>
<?php else:?>
    Todo: A congratz to the winner &amp; last man standing
<?php endif;?>
</div>

<?php html_footer();
