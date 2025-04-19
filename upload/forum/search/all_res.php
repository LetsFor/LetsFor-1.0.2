<?php
echo '<div class="search_res">';
if (isset($_POST['tema']))
{
    $tema = LFS($_POST['tema']);
    if (empty($tema) || mb_strlen($tema) > 20)
	{
        echo 'Некорректный запрос';
        exit;
    }

    // Поиск тем
    $k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `name` LIKE '%" . $tema . "%'"), 0);
    if ($k_post == 0)
	{
        echo '<span></span>';
    } else {
		echo '<div class="menu_nb">';
		echo '<b style="font-size: 14px;">Темы:</b>';
		echo '</div>';
        $q = dbquery("SELECT * FROM `forum_tema` WHERE `name` LIKE '%" . $tema . "%' ORDER BY `time_up` DESC");
        while ($a = mfar($q)) {
            $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '".$a['us']."' LIMIT 1"));
            require_once ($_SERVER['DOCUMENT_ROOT'].'/LFcore/div-link-thems-info.php');
        }
    }

    // Поиск пользователей
    $k_us = msres(dbquery("SELECT COUNT(*) FROM `users` WHERE `login` LIKE '%" . $tema . "%'"), 0);
    if ($k_us == 0)
	{
        echo '<span></span>';
    } else {
		echo '<div class="menu_nb">';
		echo '<b style="font-size: 14px;">Пользователи:</b>';
		echo '</div>';
        $b = dbquery("SELECT * FROM `users` WHERE `login` like '%" . $tema . "%' ORDER BY `id` DESC");
		
		echo '<div class="menu_cont">';
        while ($ank = mfa($b)) {
			echo '<div id="user">';
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id'.$ank['id'].'">';
            echo '<td style="width: 65px;">';
            echo UserAvatar($ank, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
            echo '<span class="nick_user" style="pointer-events: none;">'.nick($ank['id']).'</span></br>';
            $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '".$ank['id']."'"),0);
            echo 'Постов в форуме: <span class="num-indi">'.$p_forum.'</span></br>';
			$t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '".$ank['id']."'"),0);
			echo 'Тем в форуме: <span class="num-indi">'.$t_forum.'</span>';
			echo '</td>';
			echo '</table>';
			echo '</div>';
        }
		echo '</div>';
    }
	
	if ($k_post == 0 & $k_us == 0)
	{
		echo '<div class="menu_cont">';
        echo '<div class="err"><center>По запросу #' . $tema . ' ничего не найдено</center></div>';
		echo '</div>';
    }
}
echo '</div>';
?>