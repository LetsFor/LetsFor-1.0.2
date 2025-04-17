<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

$act = isset($_GET['act']) ? LFS($_GET['act']) : null;
switch ($act) {
    default:
        echo '<title>Уведомления</title>';
        echo '<div class="title">Уведомления</div>';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `lenta` WHERE `komy` = '" . $user['id'] . "'"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $lenta = dbquery("SELECT * FROM `lenta` WHERE `komy` = '" . $user['id'] . "' ORDER BY `time_up` DESC LIMIT $start, $max");
        while ($l = mfa($lenta)) {

            echo '<table class="menu_nbr" cellspacing="0" cellpadding="0">';

            echo '<td class="block_avatar">';
            $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $l['kto'] . "'"));
            echo UserAvatar($ank, $width, $height);
            echo '</td>';
            echo '<td class="block_content">';

            echo '<span class="nick_lenta">' . nick($l['kto']) . '</span><div class="block_msg" style="font-size: 13px;">' . nl2br(bb($l['text_col'])) . '</div>';
            echo '<div class="time2"><span class="time-in-readlen">' . vremja($l['time_up']) . '';
            if ($l['readlen'] == 0) {
                echo '<span class="readlen"><img src="' . homeLink() . '/images/readlen.png"></span>';
            }
            echo '</span></div>';

            echo '</td>';
            echo '</table>';

            dbquery("UPDATE `lenta` SET `readlen` = '1' WHERE `id`='" . $l['id'] . "' limit 1");
        }
		
		if($k_post > 0) {
			echo '<div class="menu_cont">';
			echo '<a class="link" href="/lenta/dellenta/"><span class="icon"><i class="far fa-trash-alt"></i></span> Очистить</a>';
			echo '</div>';
		} else {
			echo '<div class="menu_nb"><center><b>Уведомлений нет</b></center></div>';
		}

        if ($k_page > 1) echo str('' . homeLink() . '/lenta/?', $k_page, $page); // Вывод страниц

        break;
    case 'dellenta':

        if (isset($_REQUEST['okda'])) {
            dbquery("DELETE FROM `lenta` WHERE `komy` = '" . $user['id'] . "'");
            header('Location: ' . homeLink() . '/lenta');
            exit();
        }

        echo '<div class="title"><a href="' . homeLink() . '/lenta/">Удалить всё</div>
		<div class="menu_nb">Вы действительно хотите удалить все уведомления?</div>
		<div class="menu_nb">
		<a class="button" href="' . homeLink() . '/lenta/dellenta/?okda">Удалить</a> 
		<a class="button" href="' . homeLink() . '/lenta">Отмена</a>
		</div>';

        break;
}

//-----Подключаем вверх-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
