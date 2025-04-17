<?php

echo '<title>Список мошенников</title>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$scam = dbquery("SELECT * FROM `users` WHERE `verified` = '1' ORDER BY `id` DESC limit 15");
echo '<div class="title">Список мошенников</div>';

if (msnumrows($scam) > 0) {
	
	echo '<div class="menu_cont">';
    while ($ank = mfa($scam)) {
        echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id' . $ank['id'] . '">';
        echo '<td style="width: 65px;">';
        echo (empty($ank['avatar']) ? '<img class="avatar" src="/files/ava/net.png" style="height: 50px; width: 50px;">' : '<img class="avatar" src="/files/ava/' . $ank['avatar'] . '" style="height: 50px; width: 50px;">');
        echo '</td>';
        echo '<td class="block_content">';
        echo '<span class="nick_user" style="pointer-events: none;">' . nick($ank['id']) . '</span></br>';
        $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '$ank[id]'"), 0);
        echo 'Постов в форуме: <span class="num-indi">' . $p_forum . '</span></br>';
        $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '$ank[id]'"), 0);
        echo 'Тем в форуме: <span class="num-indi">' . $t_forum . '</span>';
        echo '</td>';
        echo '</table>';
    }
	echo '</div>';

    echo '</center></div>';
} else {
    echo '<div class="menu_cont"><div class="block--info">Список мошенников пуст</div></div>';
}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
