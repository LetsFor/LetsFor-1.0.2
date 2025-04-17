<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $ip_us = htmlspecialchars($_POST['ipsear']);

    if (strlen($ip_us) < 1)
	{
        echo '<div class="menu_nbr"><center><b>Минимальная длина запроса 1 символ!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
        exit();
    }

    $ipsr = msres(dbquery("SELECT COUNT(*) FROM users WHERE ip LIKE '%" . $ip_us . "%'"), 0);

    if ($ipsr == 0)
	{
		echo '<div class="menu_cont">';
        echo '<div class="err"><center>По запросу #' . $_POST['ipsear'] . ' ничего не найдено!</center></div>';
		echo '</div>';
    } else {
		echo '<div class="menu_nb"><b style="font-size: 14px;">Пользователи:</b></div>';
		$ip_fs = dbquery("SELECT * FROM users WHERE ip LIKE '%" . $ip_us . "%' ORDER BY id DESC");
		
		echo '<div class="menu_cont">';
        while($ank = mfa($ip_fs)){
            echo '<div id="user">';
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id'.$ank['id'].'">';
            echo '<td style="width: 65px;">';
            echo UserAvatar($ank, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
            echo '<span class="nick_user" style="pointer-events: none;">'.nick($ank['id']).'</span></br>';
            echo 'IP: <span class="num-indi">'.$ank['ip'].'</span><br />';
            echo '</td>';
            echo '</table>';
            echo '</div>';
        }
		echo '</div>';
    }
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>