<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $mes_us = htmlspecialchars($_POST['messear']);

    if (strlen($mes_us) < 1)
	{
        echo '<div class="menu_nbr"><center><b>Минимальная длина запроса 1 символ!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
        exit();
    }

    $messr = msres(dbquery("SELECT COUNT(*) FROM `message` where `text_col` LIKE '%" . $mes_us . "%' and `komy` = '" . $user['id'] . "' or `text_col` LIKE '%" . $mes_us . "%' and `kto` = '" . $user['id'] . "'"), 0);

    if ($messr == 0)
	{
		echo '<div class="menu_cont">';
        echo '<div class="err"><center>По запросу #' . $_POST['messear'] . ' ничего не найдено!</center></div>';
		echo '</div>';
    } else {
		echo '<div class="menu_nb"><b style="font-size: 14px;">Сообщения:</b></div>';
		$dialog = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `del` = '0' ORDER BY `posl_time` DESC"));
		$mes_fs = dbquery("SELECT * FROM `message` where `text_col` LIKE '%" . $mes_us . "%' and `komy` = '" . $user['id'] . "' or `text_col` LIKE '%" . $mes_us . "%' and `kto` = '" . $user['id'] . "' ORDER BY `time_up` DESC");
		
		echo '<div class="menu_cont">';
        while($ank = mfa($mes_fs)){
            echo '<div id="user">';
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="' . homeLink() . '/mes/dialog' . $dialog['kogo'].'">';
			echo '<td style="width: 65px;">';
            echo UserAvatar($dialog, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
			echo '<span class="nick_user" style="pointer-events: none;">'.nick($dialog['kogo']).'</span></br>';
            echo 'Сообщение: <span class="num-indi">' . bb(smile($ank['text_col'])) . '</span><br />';
            echo '</td>';
            echo '</table>';
            echo '</div>';
        }
		echo '</div>';
    }
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>