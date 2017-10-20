<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/game.inc.php';

if (isset($_GET['refresh'])) {
    $refresh = intval($_GET['refresh']);
    if ($refresh < 1) {
        show_error('The "refresh" parameter was incorrectly set!');
    }
    header("Refresh: $refresh");
}

html_header('Traditionsudvalget');

$players = get_players(true);
uasort($players, function($a, $b) {
    return $a['points'] < $b['points'];
});
$players_with_points = array_filter($players, function($val) {
    return $val['points'];
});
$players_alive = array_filter($players, function($val) {
    return !$val['is_dead'];
});
$players_top_10 = array_slice($players_with_points, 0, 10);
?>
<div class="row logo">
    <img src="resources/img/traditionsudvalg_logo.png">
</div>
<div class="row">
    <h2 class="thing">Tag and Treat</h2>
</div>
<div class="tables row">
    <div class="col span_1_of_2">
        <table class="top10">
            <tr><th class="table-title" colspan="3"><h2>Top<?php echo (count($players_with_points) >= 10) ? ' 10' : '' ?></h2></th></tr>
            <tr><th>Navn</th><th>Klasse</th><th>Point</th></tr>
            <?php foreach ($players_top_10 as $player) {?>
            <tr><td><?php echo $player['name'];?></td><td><?php echo $player['classroom'];?></td><td><?php echo $player['points'];?></td></tr>
            <?php }?>
        </table>
    </div>
    <div class="col span_1_of_2">
        <table class="players">
            <tr><th class="table-title" colspan="2"><h2>Levende spillere</h2></th></tr>
            <tr><th>Navn</th><th>Klasse</th></tr>
            <?php foreach ($players_alive as $player) {?>
            <tr><td><?php echo $player['name'];?></td><td><?php echo $player['classroom'];?></td></tr>
            <?php }?>
        </table>
    </div>
</div>

<?php html_footer();
