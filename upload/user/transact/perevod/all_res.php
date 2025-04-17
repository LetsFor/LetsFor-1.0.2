<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $pr_us = htmlspecialchars($_POST['sruser']);

    if (strlen($pr_us) < 1)
	{
		echo '<div class="menu_cont">';
        echo '<div class="err"><center><b>Минимальная длина запроса 1 символ!</b></center></div>';
		echo '</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
        exit();
    }

    $ussr = msres(dbquery("SELECT COUNT(*) FROM users WHERE login LIKE '%" . $pr_us . "%' or id LIKE '%" . $pr_us . "%'"), 0);

    if ($ussr == 0)
	{
		echo '<div class="menu_cont">';
        echo '<div class="err"><center>По запросу #' . $_POST['sruser'] . ' ничего не найдено!</center></div>';
		echo '</div>';
    } else {
		echo '<div class="menu_nb"><b style="font-size: 14px;">Пользователи:</b></div>';
		$ip_fs = dbquery("SELECT * FROM users WHERE login LIKE '%" . $pr_us . "%' or id LIKE '%" . $pr_us . "%' ORDER BY id DESC");
        while($ank = mfa($ip_fs)){
            echo '<div id="user">';
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="' . homeLink() . '/perevod'.$ank['id'].'">';
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
    }
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>