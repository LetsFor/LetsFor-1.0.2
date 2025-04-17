<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');
$id = abs(intval($_GET['id']));
if (empty($user['max_us'])) $user['max_us'] = 10;
$max = $user['max_us'];
$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `kat` = '" . $id . "' "), 0);
$k_page = k_page($k_post, $max);
$page = page($k_page);
$start = $max * $page - $max;
$forum = dbquery("SELECT * FROM `forum_tema` WHERE `kat` = '" . $id . "' ORDER BY `top_them` DESC LIMIT $start, $max");

while ($a = mfa($forum)) {
    require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/div-link-thems-info.php');
}
?>