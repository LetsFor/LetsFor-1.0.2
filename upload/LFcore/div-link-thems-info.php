<?
if ($a['status'] == 1) {
    $icon = '<span style="color: #abb15b; margin-right: 7px;" title="Тема закрыта для обсуждений"><i class="fas fa-lock"></i></span>';
} else {
	$icon = '<span></span>';
}

echo '<div id="tema">';
echo '<div class="div-link" role="link" id="cont_tem" data-href="/tema' . $a['id'] . '">';

$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us'] . "'"));
$fdk = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $a['kat'] . "'"));
$perm = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $ank['level_us'] . "'"));
$prev_user = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $ank['prev'] . "'"));
$post_maxid = mfa(dbquery("SELECT MAX(`id`) as `max_id` FROM `forum_post` WHERE `tema` = '" . $a['id'] . "'"));
$usup = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $post_maxid['max_id'] . "'"));
echo '<span class="flex_tem">';

if ($a['select_them'] == 1) {
    $them_color = $a['color'];
} else {
    $them_color = 'var(--font-color)';
}

echo '<div class="them_home_block">';
echo '<span class="t_n_for">' . $icon . ' <h1 class="them_title_home" style="' . $them_color . '">' . $a['name'] . '</h1><span class="rzd-them">' . $fdk['name'] . '</span></span>';

echo '<span class="threadTitle--prefixGroup">';
$result_like = dbquery("SELECT count(*) as `total` from `forum_like` where `tema` = '" . $a['id'] . "'");
$data_like = mfa($result_like);

echo '<div class="mobile_com">';
require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/prefix.php');
echo '<span class="nick_st_us">' . nick($a['us']) . '</span>';
if(empty($usup['us'])) {
	echo '<span></span>';
} else {
	echo '<span class="nick_st_usup">' . nick($usup['us']) . '</span><span class="info-separator"></span>';
}
echo '<span class="time-in-them_mobile--threadTitle">' . vremja($a['time_up']) . '</span>
<span class="time-in-them--threadTitle">' . vremja($a['up']) . '</span>
<span class="com">
<i class="fas fa-comment-alt" style="color: #949494; font-size: 12px; margin-right: 6px;"></i>
' . msres(dbquery('select count(`id`) from `forum_post` WHERE `tema` = "' . $a['id'] . '"'), 0) . '
<i class="fas fa-heart" style="color: #949494; font-size: 12px; margin: 0 6px;"></i>' . $data_like['total'] . '
</span>';

echo '</div>';

echo '<span class="com_lite">
<i class="fas fa-comment-alt" style="font-size: 13px; margin-right: 6px; margin-top: 1px; position: relative; display: inline;"></i>
<b>' . msres(dbquery('select count(`id`) from `forum_post` WHERE `tema` = "' . $a['id'] . '"'), 0) . '</b>
<i class="fas fa-heart" style="color: #949494; font-size: 12px; margin: 0 6px;"></i>
<b>' . $data_like['total'] . '</b>';
if(empty($usup['us'])) {
	echo '<span></span>';
} else {
	echo '<i class="fas fa-reply fa-rotate-180" style="margin-left: 8px;"></i><span class="nick-st-com-usup-in-them--com_lite"><b>' . nick($usup['us']) . '</b></span>';
}
echo '<span class="time_up-in-them--com_lite"><i class="fas fa-caret-right" style="margin-right: 8px;"></i><b>' . vremja($a['up']) . '</b></span>';
echo '</span>';

echo '</span>';
echo '</div>';

echo '<div class="block_ava">';
echo UserAvatar($ank, 40, 40);
echo '<span class="block_info" style="float: left;">';
echo '<span class="nickname-in-user--ava-block">';
echo nick($a['us']);
echo '</span>';
echo '<br />';
echo '<span class="time-in-user--ava-block">' . vremja($a['time_up']) . '</span>';
echo '</span>';
echo '</div>';

echo '</span>';

echo '</div>';
echo '</div>';
