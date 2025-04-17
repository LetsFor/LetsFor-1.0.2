<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

$forum = dbquery("SELECT * FROM `forum_tema` ORDER BY `up` DESC LIMIT 50");
while ($a = mfa($forum)) {
	$post_maxid = mfa(dbquery("SELECT MAX(`id`) as `max_id` FROM `forum_post` WHERE `tema` = '" . $a['id'] . "'"));
	$usup = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $post_maxid['max_id'] . "'"));
    if ($a['status'] == 1) {
		$icon = '<span style="color: #abb15b; margin-right: 7px;" title="Тема закрыта для обсуждений"><i class="fas fa-lock"></i></span>';
	}
    $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us'] . "'"));

    echo '<div class="div-link" role="link" id="cont_tem" data-href="/tema' . $a['id'] . '">';

    echo '<span class="flex_tem">';

    echo '<div class="block_ava" role="link" id="cont_tem" style="min-width: 50px; padding: 0;" data-href="/id' . $a['us'] . '">';
	echo UserAvatar($ank, 50, 50);
    echo '</div>';
	
	echo '<div class="them_home_block" style="margin-left: 10px;">';
    echo '<span class="t_n_for" style="owerflow: hidden; ">' . $icon . ' <b style="' . $a['color'] . '">' . $a['name'] . '</b></span>';

    echo '<span class="threadTitle--prefixGroup">';
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
	echo '</span>';
	echo '</div>';
	
    echo '</div>';
}
