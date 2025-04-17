<?
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us'] . "'"));
$forum_like = mfa(dbquery("SELECT * FROM `forum_like` WHERE `post` = '" . $a['id'] . "' and `us` = '" . $user['id'] . "' "));
$result_like = dbquery("SELECT count(*) as `total` from `forum_like` where `post` = '" . $a['id'] . "'");
$data_like = mfa($result_like);
$count = msres(dbquery("SELECT COUNT(`id`) FROM `forum_file` WHERE `post_id` = '" . $a['id'] . "'"), 0);
	
echo '<div class="cnt">';
echo '<div class="menu_nbr" style="padding: 15px; display: flex; width: -webkit-fill-available;">';

echo UserAvatar($ank, $width, $height);
	
echo '<div class="block_content" style="margin: 0px; width: -webkit-fill-available;">';
echo '<div class="info_user">';
echo nick($ank['id']);
echo $prus->displayUserIcon($ank);

if ($a['us'] == $forum_t['us']) {
	echo '<span class="autor">Автор темы</span>';
}

echo '<div class="vremja_post">' . vremja($a['time_up']) . '</div><br />';
echo '</div>';

if (!$a['citata'] == NULL) echo '<div class="cit">' . nick($a['citata_us']) . ': ' . nl2br(smile(bb($a['citata']))) . '</div>';

echo '<div class="block_msg" id="text">' . nl2br(smile(bb($a['text_col']))) . '</div>';

if ($a['us'] == $forum_t['us'] & mb_strlen($a['text_col']) > 550) {
    if (empty($user['id'])) {
        echo '<div class="thankAuthorBox">
		<div class="thankAuthorTitle">Это сообщение оказалось полезным?</div>
		<div class="thankAuthorDiscrip">Вы можете отблагодарить автора темы путем перевода средств на баланс</div>
		<a class="btnThanksAuthor mn-15-0-0 OverlayTrigger" href="' . homeLink() . '/login">Авторизация</a>
		</div>';
	} else {
		echo '<div class="thankAuthorBox">
		<div class="thankAuthorTitle">Это сообщение оказалось полезным?</div>
		<div class="thankAuthorDiscrip">Вы можете отблагодарить автора темы путем перевода средств на баланс</div>
		<a class="btnThanksAuthor mn-15-0-0 OverlayTrigger" href="' . homeLink() . '/perevod' . $ank['id'] . '">
		<i class="fas fa-usd" style="color: var(--buttons-text-color); margin-right: 5px;"></i>Отблагодарить автора</a>
		</div>';
	}
}
	
if ($count > 0) {
    $load_s = dbquery("SELECT * FROM `forum_file` WHERE `post_id` = '" . $a['id'] . "'");
    while ($s = mfa($load_s)) {
        echo '<a class="block_file" href="' . homeLink() . '/files/forum/' . $s['name_file'] . '"><i class="fas fa-file" style="margin-right: 5px;"></i><span class="file_name">' . $s['name_file'] . '</span><br /><code>' . fsize('../files/forum/' . $s['name_file']) . '</code></a>';
    }
}
	
echo '<div class="action_them_elm">';
echo '<span class="action_teme_us_left">';
if ($a['us'] == $user['id'] or !$user['id']) {
	echo '<span class="likeElement" data-post-id="' . $a['id'] . '"><a class="dissable like"><span class="ficon_like" style="margin: 0 7px 0 0;"><i class="far fa-heart"></i></span></a><span class="r_name" style="display: inline-block;"><span class="likeCount">0</span></span></span>';
} else {
	echo '<span class="likeElement" data-post-id="' . $a['id'] . '"><a class="like"><span class="ficon_like" style="margin: 0 7px 0 0;"><i class="far fa-heart"></i></span></a><span class="r_name" style="display: inline-block;"><span class="likeCount">0</span></span></span>';
}
echo '</span>';

echo '<span class="action_teme_us">';
echo '<span class="right_post_elm">';
if ($user['id'] != $a['us']) echo ' <a href="' . homeLink() . '/forum/post_otvet' . $a['id'] . '" title="Ответить"><span class="ficon"><i class="fas fa-reply"></i></span></a> ';
if ($user['id'] != $a['us']) echo ' <a href="' . homeLink() . '/forum/post_citata' . $a['id'] . '" title="Ответить, цитируя это сообщение"><span class="ficon"><i class="fas fa-quote-right"></i></span></a> ';
if ($user['id'] == $a['us'] or $perm['edit_post'] == 1) echo '<a href="' . homeLink() . '/forum/post_red' . $a['id'] . '" title="Редактировать сообщение"><span class="ficon"><i class="fas fa-edit"></i></span></a>';
if ($user['id'] == $a['us'] or $perm['del_post'] == 1) echo '<a href="' . homeLink() . '/forum/post_del' . $a['id'] . '" title="Удалить сообщение"><span class="ficon"><i class="fas fa-trash"></i></span></a> ';
echo '</span>';
echo '</span>';
echo '</div>';

echo '</div>';
echo '</div>';
echo '</div>';
?>
