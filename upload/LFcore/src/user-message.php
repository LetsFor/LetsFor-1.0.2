<?
echo '<table class="menu_nb" style="border-bottom: 0px;" cellspacing="0" cellpadding="0">';
echo '<td class="block_avatar">';
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $m['kto'] . "'"));
echo UserAvatar($ank, $width, $height);
echo '</td>';
echo '<td class="block_content_msg">';

if ($m['kto'] == $user['id']) echo nick($m['kto']) . ' ';

else echo nick($m['kto']) . ' ';

echo '<span class="time_mes">' . vremja($m['time_up']) . '</span>';

$sav = mfa(dbquery("SELECT * FROM `message_save` WHERE `uid_col` = '" . $m['id'] . "'"), 0);

echo '<div class="block_msg">' . bb(smile($m['text_col'])) . '';

if ($m['readlen'] == 0) {
     echo ' <span class="icon_mes">
	<svg xmlns="http://www.w3.org/2000/svg" width="22px" height="auto" viewBox="0 0 24 18" fill="none">
	<path d="M4 12.9L7.14286 16.5L15 7.5" stroke="var(--items-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
	</svg>
	</span> ';
} else {
	echo ' <span class="icon_mes">
	<svg xmlns="http://www.w3.org/2000/svg" width="22px" height="auto" viewBox="0 0 24 18" fill="none">
	<path d="M4 12.9L7.14286 16.5L15 7.5" stroke="var(--items-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
	<path d="M20 7.5625L11.4283 16.5625L11 16" stroke="var(--items-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
	</svg>
	</span> ';
}

if ($m['file_col'])

echo '<a href="../files/mes/' . $m['file_col'] . '">' . $m['file_col'] . '</a> [' . fsize('../files/mes/' . $m['file_col']) . ']';

if ($user['id'] == $m['komy']) {
	dbquery("UPDATE `message` SET `readlen` = '1' WHERE `id`='" . $m['id'] . "' limit 1");
}
echo '</div>';
echo '</td>';
echo '</table>';
?>