<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

echo '<style>
@media all and (min-width: 800px){ 
.side_st {
	display: none;
  }
.side_at {
	border-radius: var(--all-border-radius) var(--all-border-radius) var(--all-border-radius) var(--all-border-radius);
  }
}
</style>';

if (empty($user['id']) or $perm['panel'] < 1) {
	echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
	exit;
}

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
	default:

		echo '<title>Панель управления</title>';

		echo '<div class="title">Панель управления</div>';


		if (phpversion() < 8.0) {
			echo '<div class="block--info" style="margin-top: 10px;"><center>Ваша версия PHP устарела, рекомендуем вам перейти на версию 8.1 для более стабильной работы сервера!</center></div>';
		}
		
		echo '<div class="menu_nb" style="padding: 10px;">';
		echo '<div class="panel__block">';

		echo '<a class="link_adm" href="'.homeLink().'/admin/users"><span class="icon_adm"><i class="fas fa-users"></i></span>Пользователи</a>
		<a class="link_adm" href="'.homeLink().'/admin/ras"><span class="icon_adm"><i class="fas fa-bullhorn"></i></span>Рассылка</a>
		<a class="link_adm" href="'.homeLink().'/admin/complaints"><span class="icon_adm"><i class="fas fa-flag"></i></span>Жалобы</a>
		<a class="link_adm" href="'.homeLink().'/admin/bonus"><span class="icon_adm"><i class="fas fa-dollar-sign"></i></span>Бонусы</a>
		<a class="link_adm" href="'.homeLink().'/admin/aspam"><span class="icon_adm"><i class="fa-solid fa-wand-magic-sparkles"></i></span>Антиспам</a>
		<a class="link_adm" href="'.homeLink().'/admin/search-ip"><span class="icon_adm"><i class="fas fa-search"></i></span>Поиск по IP</a>
		<a class="link_adm" href="'.homeLink().'/admin/ban"><span class="icon_adm"><i class="fas fa-ban"></i></span>Бан лист</a>
		<a class="link_adm" href="'.homeLink().'/forum"><span class="icon_adm"><i class="fas fa-bars"></i></span>Узлы</a>
		<a class="link_adm" href="'.homeLink().'/admin/rek"><span class="icon_adm"><i class="fas fa-rectangle-ad"></i></span>Реклама</a>
		<a class="link_adm" href="'.homeLink().'/admin/smile"><span class="icon_adm"><i class="fas fa-smile"></i></span>Смайлы</a>
		<a class="link_adm" href="'.homeLink().'/admin/prefix"><span class="icon_adm"><i class="fab fa-autoprefixer"></i></span>Префиксы тем</a>
		<a class="link_adm" href="'.homeLink().'/admin/style"><span class="icon_adm"><i class="fas fa-palette"></i></span>Оформление</a>
		<a class="link_adm" href="'.homeLink().'/admin/groups"><span class="icon_adm"><i class="fas fa-user-tie"></i></span>Группы пользователей</a>
		<a class="link_adm" href="'.homeLink().'/admin/up-level"><span class="icon_adm"><i class="fas fa-user-tie"></i></span>Повышение прав</a>
		<a class="link_adm" href="'.homeLink().'/admin/faq"><span class="icon_adm"><i class="fab fa-question"></i></span>F.A.Q</a>
		<a class="link_adm" href="'.homeLink().'/admin/settings"><span class="icon_adm"><i class="fas fa-gear"></i></span>Настройки сайта</a>';

		echo '</div>';
		
		echo '<div class="panel__info">';
		echo '<a class="link" href="' . homeLink() . '/admin/tooltips"><span class="icon"><i class="fas fa-quote-right"></i></span> Ключевые слова</a>';
		echo '<a class="link" href="' . homeLink() . '/admin/rules"><span class="icon"><i class="fas fa-book"></i></span> Правила форума</a>';
		echo '<a class="link" href="' . homeLink() . '/admin/auth"><span class="icon"><i class="fas fa-key"></i></span> Авторизвция через соц. сети</a>';
		echo '<a class="link" data-bs-toggle="modal" data-bs-target="#version"><span class="icon"><i class="fas fa-circle-info"></i></span> Подробнее о версии</a>';
		echo '</div>';

		$count_vis = dbquery("SELECT COUNT(DISTINCT ip_address) AS total_visitors FROM visitors");
		$total_visitors = mfa($count_vis)['total_visitors'];
		
		echo '<div class="panel__info">';
		echo '<b style="font-size: 14px;">Общая статистика форума</b><br />';
		echo '<div class="block_stolb_info--gt">';
		echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Темы: </span><span class="stolb_info-num-stat-gt">' . $f_thems . '</span></span>';
		echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Сообщения: </span><span class="stolb_info-num-stat-gt">' . $f_posts . '</span></span>';
		echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Пользователеи: </span><span class="stolb_info-num-stat-gt">' . $f_users . '</span></span>';
		echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Псетители: </span><span class="stolb_info-num-stat-gt">' . $total_visitors . '</span></span>';
		echo '</div>';
		echo '</div>';
		
		echo '<div class="panel__info">';
		echo '<b style="font-size: 14px;">Информация о сервере</b><br />';
		echo '<div class="block_stolb_info--gt">';

		echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">PHP: </span><span class="stolb_info-num-stat-gt">' . phpversion() . '</span></span>';
		echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">MySQL: </span><span class="stolb_info-num-stat-gt">' . getMySQLVersion() . '</span></span>';
		echo '</div>';
		echo '</div>';
		
		echo '</div>';

		break;
	case 'ban':

		if ($perm['ban_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Бан лист</div>';
		
		echo '<div class="menu_cont">
		<a class="link" href="' . homeLink() . '/admin/ban/list">Список забаненых</a>
		<a class="link" href="' . homeLink() . '/admin/ban/jalob">Жалобы на бан</a>
		</div>';

		break;
	case 'ban_list':

		if ($perm['ban_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Список забаненых</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `ban_list`"), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;

		$ban = dbquery("SELECT * FROM `ban_list` ORDER BY `time_play` DESC LIMIT $start,$max");

		while ($ank = mfa($ban)) {
			echo '<div class="menu_nbr">' . nick($ank['kto']) . '<br />
			Дата бана: <span class="stolb_info-num-stat-gt">' . vremja($ank['time_play']) . '</span><br />
			Причина: <span class="stolb_info-num-stat-gt">' . smile(bb($ank['about'])) . '</span><br />
			Дата освобождения: <span class="stolb_info-num-stat-gt">' . date('d.m.Y в H:i', $ank['time_end']) . '</span><br />
			<a class="button" href="' . homeLink() . '/admin/ban/list/updateban' . $ank['kto'] . '" style="margin-top: 10px;">разбанить</a><br />
			</div>';
		}

		if ($k_post < 1) {
			echo '<div class="menu_nbr"><center><b>Бан лист пуст!</b></center></div>';
		}

		if ($k_page > 1) {
			echo str('' . homeLink() . '/admin/ban/list/', $k_page, $page); // Вывод страниц
		}


		break;
	case 'style':

		if ($perm['edit_style'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<title>Настройки оформления</title>';

		echo '<div class="title">Настройки оформления</div>';

		$style = dbquery("SELECT * FROM `themes` ORDER BY `id`");
		
		echo '<div class="menu_cont">';
		while ($s = mfa($style)) {
			echo '<div class="div-link" id="cont_tem"><span class="icon" style="margin-right: 10px;"><i class="fas fa-palette"></i></span>' . $s['folder'] . '';
			echo '<span class="but-right" style="float: right;">';

			if ($s['folder'] != $index['style']) {
				echo '<a class="link-btn-group" href="' . homeLink() . '/admin/set-style-' . $s['id'] . '">Установить </a>';
			}

			if ($s['id'] != 1 && $s['folder'] != $index['style']) {
				echo '<a class="link-btn-group" data-bs-toggle="modal" data-bs-target="#remstyle' . $s['id'] . '"> Удалить</a>';
			}

			echo '</span>';
			echo '</div>';

			echo '<div class="modal fade" id="remstyle' . $s['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Удаление стиля</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
			</div>
			<div class="modal-body">
			<span>Вы действительно хотите удалить стиль ' . $s['folder'] . '?</span>
			</div>
			<div class="modal-footer">
			<a class="button" style="float: right; margin-left: 5px;" href="' . homeLink() . '/admin/rem-style-' . $s['id'] . '">Удалить</a>
			<a class="button" style="float: right;" data-bs-dismiss="modal">Отмена</a>
			</div>
			</div>
			</div>
			</div>';
		}
		echo '</div>';

		break;
	case 'set-style':

		$id = abs(intval($_GET['id']));

		if ($perm['edit_style'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$style_list = mfa(dbquery("SELECT * FROM `themes` WHERE `id` = '" . $id . "'"));

		if (empty($style_list)) {
			header('Location: ' . homeLink() . '/admin/style?selection=top');
		} else {
			dbquery("UPDATE `settings` SET `style` = '" . $style_list['folder'] . "'");
			header('Location: ' . homeLink() . '/admin/style?selection=top');
			exit();
		}

		break;
	case 'rem-style':

		$id = abs(intval($_GET['id']));

		if ($perm['edit_style'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$style_list = mfa(dbquery("SELECT * FROM `themes` WHERE `id` = '" . $id . "'"));

		if (empty($style_list)) {
			header('Location: ' . homeLink() . '/admin/style?selection=top');
		} else {
			deleteDirectory('' . $_SERVER['DOCUMENT_ROOT'] . '/design/style/' . $style_list['folder'] . '');
			header('Location: ' . homeLink() . '/admin/style?selection=top');
			exit();
		}

		break;
	case 'addban':

		$id = abs(intval($_GET['id']));
		$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

		if ($user['id'] == $id or $id == 1 or $perm['ban_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Забанить пользователя ' . $ank['login'] . '</div>';

		if (isset($_REQUEST['ok'])) {
			$about = LFS($_POST['about']);
			$time_end = LFS($_POST['time_end']);

			if (empty($about) or mb_strlen($about) < 3) {
				echo '<div class="menu_nbr"><center><b>Ошибка ввода ,минимум 3 символа!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("INSERT INTO `ban_list` SET `about` = '" . $about . "', `time_end` = '" . (time() + ($time_end * (60 * 60))) . "', `add_ban` = '" . $user['id'] . "', `kto` = '" . $id . "', `time_play` = '" . time() . "'");
			header('Location: ' . homeLink() . '/admin/ban/list');
			exit();
		}

		$sql = mfa(dbquery("SELECT * FROM `ban_list` WHERE `kto` = '" . $id . "'"));

		if ($sql == 0) {
			echo '<div class="menu_nb">Вы желаете дать бан ' . nick($id) . ' ?</div>
			<form action="" method="POST">
			<div class="menu_t">
			<div class="setting_punkt">Причина:</div><textarea name="about"></textarea>
			</div>
			
			<div class="menu_t">
			<div class="setting_punkt">Срок:</div><div class="gt-select"><select name="time_end"><option value="3">3 часа</option><option value="24">Сутки</option><option value="72">3 дня</option><option value="168">Неделя</option><option value="720">Месяц</option><option value="8640">Год</option><option value="99999999999">Навсегда</option></select></div>
			</div>
			
			<div class="setting-footer">
			<div class="menu_t">
			<input type="submit" name="ok" value="Банить" />
			</div>
			</div>
			</form>';
		} else {
			echo '<div class="menu_nbr"><center><b>Этот пользователь уже забанен!!</b></center></div>';
		}

		break;
	case 'updateban':

		if ($perm['ban_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$id = abs(intval($_GET['id']));
		$pardon = mfa(dbquery("SELECT * FROM `ban_list` WHERE `kto` = '" . $id . "'"));
		$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $pardon['kto'] . "'"));

		echo '<div class="title">Разбан пользователя ' . $ank['login'] . '</div>';

		if ($pardon['id'] == 0) {
			echo '<div class="menu_nbr"><center><b>Этот пользователь не в бане!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if (isset($_REQUEST['okda'])) {
			dbquery("DELETE FROM `ban_list` WHERE `kto` = '" . $id . "'");
			header('Location: ' . homeLink() . '/admin/ban/list');
			exit();
		}

		echo '<div class="menu_nb">Вы действительно хотите разбанить ' . nick($ank['id']) . '?
		<br />
		<br />
		<a class="button" href="' . homeLink() . '/admin/ban/list/updateban' . $id . '?okda">Разбанить</a> <a class="button" href="' . homeLink() . '/admin/ban/list">Отмена</a></div>';

		break;
	case 'jalob_ban':

		if ($perm['ban_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Жалобы на бан</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `jalob_ba`"), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;

		$jal = dbquery("SELECT * FROM `jalob_ba` ORDER BY `time_up` DESC LIMIT $start,$max");
		while ($ank = mfa($jal)) {
			echo '<div class="menu_nbr">' . smile(bb($ank['about'])) . ' (' . nick($ank['avtor']) . ')</div>';
		}

		if ($k_post < 1) {
			echo '<div class="menu_nbr"><center><b>Жалоб не поступало!</b></center></div>';
		}

		if ($k_page > 1) {
			echo str('' . homeLink() . '/admin/ban/jalob/', $k_page, $page); // Вывод страниц
		}

		break;
	case 'aspam':

		if ($perm['panel'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}
		echo '<div class="title">Антиспам</div>';

		if (isset($_REQUEST['ok'])) {

			$down = LFS($_POST['down']);
			$mes = LFS($_POST['mes']);
			$blog = LFS($_POST['blog']);
			$news = LFS($_POST['news']);
			$stena = LFS($_POST['stena']);
			$gust = LFS($_POST['gust']);
			$chat = LFS($_POST['chat']);
			$forum_tema = LFS($_POST['forum_tema']);
			$forum_post = LFS($_POST['forum_post']);
			$repa = LFS($_POST['repa']);
			$friends = LFS($_POST['friends']);

			/* Друзья */
			if (empty($friends)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама в друзьях!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($friends)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифра!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Репутация */
			if (empty($repa)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама в репутациях!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($repa)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифра!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Репутация */

			/* Сообщения */
			if (empty($mes)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама в сообщениях!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($mes)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Сообщения */

			/* Загрузки комментарии */
			if (empty($down)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама в загрузках!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($down)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Загрузки комментарии */

			/* Новости комментарии */
			if (empty($news)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама в новостях!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($news)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Новости комментарии */

			/* Стена пользователя */
			if (empty($stena)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама сообщений на стене!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($stena)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Стена пользователя */

			/* Сообщения в чате */
			if (empty($chat)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама сообщений в мини чате!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($chat)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Сообщения в чате */

			/* Форум создание темы */
			if (empty($forum_tema)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама созданий темы!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($forum_tema)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Форум создание темы */

			/* Форум написание поста */
			if (empty($forum_post)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама написания сообщений в теме!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($forum_post)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Форум написание поста */

			/* Блоги(комментарии) */
			if (empty($blog)) {
				echo '<div class="menu_nbr"><center><b>Вы не ввели время антиспама написания комментарий в блогах!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (!is_numeric($blog)) {
				echo '<div class="menu_nbr"><center><b>Вводить можно только цифры!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}
			/* Блоги(комментарии) */

			dbquery("UPDATE `antispam` SET `repa` = '" . $repa . "',`mes` = '" . $mes . "',`down` = '" . $down . "',`guest` = '" . $gust . "',`blog` = '" . $blog . "',`chat` = '" . $chat . "', `news` = '" . $news . "', `stena` = '" . $stena . "', `forum_tema` = '" . $forum_tema . "', `forum_post` = '" . $forum_post . "',`friends` = '" . $friends . "' WHERE `id` = '1'");
			showAlert('Изменения успешно сохранены!', 'info', 3000);
		}
		/* Переменная вывода настроек сайта */
		$sql = mfa(dbquery("SELECT * FROM `antispam` "));
		echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Писать в новостях комментарии можно раз в: сек.</div>
		<input type="text" name="news" value="' . $sql['news'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Оставлять в чате сообщения можно раз в: сек.</div>
		<input type="text" name="chat" value="' . $sql['chat'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Оставлять сообщения на стенах можно раз в: сек.</div>
		<input type="text" name="stena" value="' . $sql['stena'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Создавать темы в форуме можно раз в: сек.</div>
		<input type="text" name="forum_tema" value="' . $sql['forum_tema'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Писать сообщения в теме можно раз в: сек.</div>
		<input type="text" name="forum_post" value="' . $sql['forum_post'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Писать комментарии в блогах можно раз в: сек.</div>
		<input type="text" name="blog" value="' . $sql['blog'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Писать сообщения в гостевой можно раз в: сек.</div>
		<input type="text" name="gust" value="' . $sql['guest'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Писать комментарии в загрузках можно раз в: сек.</div>
		<input type="text" name="down" value="' . $sql['down'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Писать сообщения можно раз в: сек.</div>
		<input type="text" name="mes" value="' . $sql['mes'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Добавлять друзей можно раз в: сек.</div>
		<input type="text" name="friends" value="' . $sql['friends'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Изменять репутацию можно раз в: дн.</div>
		<input type="text" name="repa" value="' . $sql['repa'] . '">
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="ok" value="Установить" />
		</div>
		</div>
		
		</form>';

		break;
	case 'us':

		echo '<div class="title">Управление пользователями</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `users`"), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$k_post = $start + 1;

		$us = dbquery("SELECT * FROM `users` ORDER BY `id` LIMIT $start, $max");
		while ($ank = mfa($us)) {
			echo '<div class="menu_nbr"><div class="setting_punkt">' . $k_post++ . ') ' . nick($ank['id']) . ' ID: ' . $ank['id'] . '

			<div class="dropdown" style="float: right">
			<span class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></span>
			<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/up_us_' . $ank['id'] . '">Редактировать</a></li>';
			$ban = mfa(dbquery("SELECT * FROM `ban_list` WHERE `kto` = '" . $ank['id'] . "'"));
			if ($ank['id'] != 1) {
				if (empty($ban)) {
					echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/ban/list/addban' . $ank['id'] . '">забанить</a></li>';
				} else {
					echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/ban/list/updateban' . $ank['id'] . '">разбанить</a></li>';
				}
			}
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/delete_us_' . $ank['id'] . '">Удалить</a></li>';
			echo '</ui>
			</div>
			</div>';

			echo 'Дата реги.: ' . vremja($ank['datareg']) . '<br />
			IP: ' . $ank['ip'] . '<br />
			Обновлялся: ' . vremja($ank['viz']) . '
			</div>';
		}

		if ($k_page > 1) {
			echo str('' . homeLink() . '/admin/users?', $k_page, $page); // Вывод страниц
		}

		break;
	case 'deleteus':

		if ($perm['edit_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}
		$id = abs(intval($_GET['id']));
		$del_us = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));
		$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

		echo '<div class="title">Удалить пользователя ' . $ank['login'] . '</div>';

		if ($del_us == 0 or $id == 1) {
			echo '<div class="menu_nbr"><center><b>Ошибка!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if (isset($_REQUEST['okda'])) {
			dbquery("DELETE FROM `users` where `id` = '" . $id . "'");
			header('Location: /panel/users');
			exit();
		}

		echo '<div class="menu_nb">Вы действительно хотите удалить пользователя ' . $ank['login'] . '?</div>
		<div class="menu_nb"><a class="button" href="' . homeLink() . '/admin/delete_us_' . $id . '?okda" style="margin-right: 5px;">Удалить</a><a class="button" href="' . homeLink() . '/admin/users">Отмена</a></div>
		</div>';

		break;

	case 'upus':

		if (isset($_POST['verified']) && ($_POST['verified'] == 1 || $_POST['verified'] == 0)) {
			$ank['verified'] = $_POST['verified'];
			dbquery("UPDATE `users` SET `verified` = '$ank[verified]' WHERE `id` = '$ank[id]' LIMIT 1");
		} else $err = 'Ошибка режима пользователя';

		$id = abs(intval($_GET['id']));
		$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

		$perm = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $user['level_us'] . "'"));
		if ($perm['edit_users'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		if ($ank == 0 or $id == 1 || $ank['id'] == $user['id']) {
			if ($id != $user['id']) {
				echo err($title, 'Доступ закрыт!');
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit;
			}
		}

		echo '<div class="title">Редактировать пользователя</div>';

		if (isset($_REQUEST['ok'])) {

			$login = LFS($_POST['login']);
			$name = LFS($_POST['name']);
			$strana = LFS($_POST['strana']);
			$gorod = LFS($_POST['gorod']);
			$stat = LFS($_POST['stat']);
			$level = LFS($_POST['level']);
			$email = LFS($_POST['email']);
			$money = LFS($_POST['money']);
			$url = LFS($_POST['url']);
			$verified = abs(intval($_POST['verified']));
			$sex = abs(intval($_POST['sex']));


			if (!preg_match('/[0-9a-z_\-]+@[0-9a-z_\-^\.]+\.[a-z]{2,6}/i', $email)) {
				echo '<div class="menu_nbr"><center><b>Формат e-mail введён не верно!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("UPDATE `users` SET `login` = '" . $login . "', `name` = '" . $name . "', `strana` = '" . $strana . "', `gorod` = '" . $gorod . "', `stat` = '" . $stat . "', `level_us` = '" . $level . "', `verified` = '" . $verified . "', `email` = '" . $email . "', `money_col` = '" . $money . "', `url` = '" . $url . "', `sex` = '" . $sex . "'WHERE `id` = '" . $id . "'");
			showAlert('Пользователь отредактирован!', 'info', 3000);
		}

		echo '<div class="menu_nb">Редактирование пользователя:  ' . nick($ank['id']) . '</div>
		<form action="" method="POST">
		<div class="menu_t">
		<div class="setting_punkt">Ник:</div><input type="text" name="login" maxlength="25" value="' . $ank['login'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Имя:</div><input type="text" name="name" maxlength="45" value="' . $ank['name'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Страна:</div><input type="text" name="strana" maxlength="40" value="' . $ank['strana'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Город:</div><input type="text" name="gorod" maxlength="40" value="' . $ank['gorod'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Статус:</div><input type="text" name="stat" maxlength="100" value="' . $ank['stat'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Сайт:</div><input type="text" name="url" maxlength="20" value="' . $ank['url'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Деньги:</div><input type="text" name="money" maxlength="1000" value="' . $ank['money_col'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">E-MAIL:</div><input type="text" name="email" value="' . $ank['email'] . '" maxlength="50">
		</div>';
		
		echo '<div class="menu_t">';
		echo '<div class="setting_punkt">Пол:</div>';
		echo '<div class="gt-select"><select name="sex">';
		$dat = array('Мужской' => '1', 'Женский' => '2');
		foreach ($dat as $key => $value) {
			echo ' <option value="' . $value . '"' . ($value == $ank['sex'] ? ' selected="selected"' : '') . '>' . $key . '</option>';
		}
		echo '</select></div>';
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo '<div class="setting_punkt">Верификация:</div>';
		echo '<div class="gt-select"><select name="verified">';
		$dat = array('Мошенническая' => '1', 'Пользовательская' => '0');
		foreach ($dat as $key => $value) {
			echo ' <option value="' . $value . '"' . ($value == $ank['verified'] ? ' selected="selected"' : '') . '>' . $key . '</option>';
		}
		echo '</select></div>';
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo '<div class="setting_punkt">Повышение:</div>';
		echo '<div class="gt-select"><select name="level">';
		$perm = dbquery("SELECT * FROM `admin_perm` ORDER BY `id` LIMIT 999");
		while ($prm = mfa($perm)) {
			echo '<option value="' . $prm['id'] . '"' . ($prm['id'] == $ank['level_us'] ? ' selected="selected"' : '') . '>' . $prm['name'] . '</option>';
		}
		echo '</select></div>';
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
		echo '<input type="submit" name="ok" value="Сохранить">';
		echo '</div>';
		echo '</div>';
		
		echo '</form>';

		echo '<div class="menu_nbr" style="font-size: 15px;">Сменить пароль</div>';

		if (isset($_REQUEST['retpass'])) {

			$np = LFS($_POST['np']);

			if (empty($np)) {
				echo err($title, 'Введите новый пароль!');
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit;
			}

			if (mb_strlen($np) < 3) {
				echo err($title, 'Минимум 3 символа');
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit;
			}

			if (!preg_match('|^[A-Za-z0-9@._-]+$|i', $np)) {
				echo err($title, 'Кириллица запрещена в новом пароле!');
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit;
			}

			dbquery("UPDATE `users` SET `pass` = '" . PassCryptor($np) . "' WHERE `id` = '" . $id . "'");
			showAlert('Пароль успешно изменен!', 'info', 3000);
		}

		echo '<form action="" method="POST">
		<div class="menu_nbr">
		<div class="setting_punkt">Новый пароль:</div><input type="text" name="np" maxlength="25">
		</div>
		
		<div class="setting-footer">
		<div class="menu_nbr">
		<input type="submit" name="retpass" value="Сменить">
		</div>
		</div>
		
		</form>';

		break;

	case 'bonus':

		if ($perm['panel'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Бонусы</div>';

		if (isset($_REQUEST['ok'])) {

			$forum_post_m = abs(intval($_POST['forum_post_m']));
			$forum_tem_m = abs(intval($_POST['forum_tem_m']));

			dbquery("UPDATE `settings` SET `forum_post_m` = '" . $forum_post_m . "', `forum_tem_m` = '" . $forum_tem_m . "' WHERE `id` = '1'");
			showAlert('Изменения успешно сохранены!', 'info', 3000);
		}

		$sql = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

		echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">За тему на форуме начислять:</div><input type="text" name="forum_post_m" value="' . $sql['forum_post_m'] . '" maxlength="5">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">За пост на форуме начислять:</div><input type="text" name="forum_tem_m" value="' . $sql['forum_tem_m'] . '" maxlength="5">
		</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
		echo '<input type="submit" name="ok" value="Сохранить" />';
		echo '</div>';
		echo '</div>';
		
		echo '</form>';

		break;

	case 'prefix-them':

		$id = abs(intval($_GET['id']));

		if ($perm['panel'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$pref = dbquery("SELECT * FROM `forum_prefix` ORDER BY `id` LIMIT 999");
		echo '<div class="menu_nbr"><b style="font-size: 14px;">Префиксы:</b></div>';
		while ($p1 = mfa($pref)) {
			echo '<div class="menu_nbr"><span class="prefix_for_them" style="' . $p1['style'] . '">' . $p1['name'] . '</span><a href="' . homeLink() . '/admin/?act=edit_prefix&id=' . $p1['id'] . '" style="float: right">Редактировать</a></div>';
		}
		echo '<div class="menu_cont">';
		echo '<a class="link" href="?act=new_prefix"><i class="fas fa-plus"> </i> Создать префикс</a>';
		echo '</div>';
		break;


	case 'edit_prefix':

		$id = abs(intval($_GET['id']));
		$forum_pref = mfa(dbquery("SELECT * FROM `forum_prefix` WHERE `id` = '" . $id . "'"));


		if (isset($_REQUEST['ok_pref'])) {

			$forum_pref_name = LFS($_POST['forum_pref_name']);
			$forum_pref_style = LFS($_POST['forum_pref_style']);

			dbquery("UPDATE `forum_prefix` SET `name` = '" . $forum_pref_name . "', `style` = '" . $forum_pref_style . "' WHERE `id` = '" . $forum_pref['id'] . "'");
			showAlert('Изменения успешно сохранены!', 'info', 3000);
		}

		if (isset($_REQUEST['pref_del'])) {
			dbquery("DELETE FROM `forum_prefix` WHERE `id` = '" . $forum_pref['id'] . "'");
			header('Location: ' . homeLink() . '/admin/?act=prefix-them');
			exit();
		}

		echo '<div class="menu_nb" style="border-bottom: 0px; padding-bottom: 0px;">';
		echo '<span class="prefix_t" style="' . $forum_pref['style'] . '; margin-bottom: 15px">' . $forum_pref['name'] . '</span>';
		echo '</div>';

		echo '<form action="" method="POST">
		<div class="menu_t">
		<div class="setting_punkt">Префикс:</div><input type="text" name="forum_pref_name" value="' . $forum_pref['name'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Стиль префикса:</div><textarea name="forum_pref_style">' . $forum_pref['style'] . '</textarea>
		</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
		echo '<input type="submit" name="ok_pref" value="Сохранить">';
		echo '<input type="submit" name="pref_del" value="Удалить">';
		echo '</div>';
		echo '</div>';

		echo '</form>';
		break;

	case 'new_prefix':

		if (isset($_REQUEST['set_prefix'])) {

			$new_pref_name = LFS($_POST['new_pref_name']);
			$new_pref_style = LFS($_POST['new_pref_style']);

			dbquery("INSERT INTO `forum_prefix` SET `name` = '" . $new_pref_name . "',`style` = '" . $new_pref_style . "'");
			header('Location: ' . homeLink() . '/admin/prefix');
		}

		echo '<div class="menu_nb" style="border-bottom: 0px; padding-bottom: 0px;">';
		echo '<span class="prefix_t" style="background: var(--buttons-dark-color); color: var(--buttons-dark-hover); margin-bottom: 15px; width: 50px;"><center><i class="fas fa-plus"></i></center></span>';
		echo '</div>';
		
		echo '<form action="" method="POST">
		<div class="menu_t">
		<div class="setting_punkt">Префикс:</div><input type="text" name="new_pref_name" placeholder="Форум">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Стиль префикса:</div><textarea name="new_pref_style" placeholder="CSS..."></textarea>
		</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
		echo '<input type="submit" name="set_prefix" value="Сохранить">';
		echo '</div>';
		echo '</div>';

		echo '</form>';

		break;
	case 'faq':
		
		echo '<title>F.A.Q</title>';
	
		if ($perm['set_faq'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">F.A.Q</div>';

		$faq = dbquery("SELECT * FROM `faq` ORDER BY `id` ASC");
		echo '<div class="menu_cont">';
		while ($fq = mfa($faq)) {
			echo '<a class="link" href="/admin/faq/edit-faq' . $fq['id'] . '">' . $fq['name'] . '</a>';
		}
		echo '<a class="link" href="' . homeLink() . '/admin/faq/new-faq"><i class="fas fa-plus"> </i> Добавить ответ</a>';
		echo '</div>';
		break;


	case 'new-faq':
		
		echo '<title>Новый ответ</title>';
		
		if ($perm['set_faq'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}
		
		echo '<div class="title">Новый ответ</div>';

		/* Если нажали кнопку */
		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$text = LFS($_POST['text']);

			if (mb_strlen($text, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($text)) $err = 'Введите описание раздела!';
			if (mb_strlen($name, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($name)) $err = 'Введите название раздела!';

			if ($err) {
				echo '<div class="menu_cont"><div class="block--info"><center><b>' . $err . '</b></center></div></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("INSERT INTO `faq` SET `name` = '" . $name . "',`text_col` = '" . $text . "'");
			header('Location: ' . homeLink() . '/admin/faq');
		}

		echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Вопрос:</div><input type="text" name="name" value="">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Ответ:</div><textarea name="text" style="height: 200px;"></textarea>
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Добавить">
		</div>
		</div>
		
		</form>';
		break;


	case 'edit-faq':
		
		echo '<title>Редактирование ответа</title>';
		
		$id = abs(intval($_GET['id']));
		$answer = mfa(dbquery("SELECT * FROM `faq` WHERE `id` = '" . $id . "'"));
		if ($perm['set_faq'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Редактирование ответа</div>';

		/* Если нажали кнопку */
		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$text = LFS($_POST['text']);

			if (mb_strlen($text, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($text)) $err = 'Введите описание раздела!';
			if (mb_strlen($name, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($name)) $err = 'Введите название раздела!';

			if ($err) {
				echo '<div class="menu_cont"><div class="block--info"><center><b>' . $err . '</b></center></div></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("UPDATE `faq` SET `name` = '" . $name . "',`text_col` = '" . $text . "' WHERE `id` = '" . $id . "'");
			showAlert('Изменения успешно сохранены!', 'info', 3000);
		}

		if (isset($_REQUEST['delfaq'])) {
			dbquery("DELETE FROM `faq` WHERE `id` = '" . $id . "'");
			header('Location: ' . homeLink() . '/admin/faq');
		}

		echo '<form action="" method="POST"> 
		<div class="menu_nb">
		<div class="setting_punkt">Вопрос:</div><input type="text" name="name" value="' . $answer['name'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Ответ:</div><textarea name="text" style="height: 200px;">' . $answer['text_col'] . '</textarea>
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Изменить">
		<input type="submit" name="delfaq" value="Удалить">
		</div>
		</div>
		
		</form>';
		
		break;
	case 'user-group':

		echo '<title>Группы пользователей</title>';
		if ($perm['set_group'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Группы пользователей</div>';

		$perm = dbquery("SELECT * FROM `admin_perm` ORDER BY `id` LIMIT 9999999999999");
		while ($prm = mfa($perm)) {
			echo '<div class="menu_nbr"><span class="prefix_for_them" style="' . $prm['color_prefix'] . '">' . $prm['name'] . '</span><span class="but-right" style="float: right;"><a class="link-btn-group" href="' . homeLink() . '/admin/groups/edit-group/' . $prm['id'] . '">Изменить </a></span></div>';
		}
		echo '<div class="menu_cont">';
		echo '<a class="link" href="' . homeLink() . '/admin/groups/new-group"><span class="icon"><i class="fas fa-plus"></i></span> Создать группу</a>';
		echo '</div>';

		break;

	case 'set-user-group':
		echo '<title>Создать группу пользователя</title>';
		if ($perm['set_group'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		if (isset($_REQUEST['saveperm'])) {

			$perm_name = LFS($_POST['perm_name']);
			$color_group = LFS($_POST['color_group']);
			$edit_razdel = LFS($_POST['edit_razdel']);
			$edit_kat = LFS($_POST['edit_kat']);
			$del_razdel = LFS($_POST['del_razdel']);
			$del_kat = LFS($_POST['del_kat']);
			$create_razdel = LFS($_POST['create_razdel']);
			$create_kat = LFS($_POST['create_kat']);
			$edit_users = LFS($_POST['edit_users']);
			$ban_users = LFS($_POST['ban_users']);
			$del_them = LFS($_POST['del_them']);
			$top_them = LFS($_POST['top_them']);
			$close_them = LFS($_POST['close_them']);
			$del_post = LFS($_POST['del_post']);
			$edit_post = LFS($_POST['edit_post']);
			$move_them = LFS($_POST['move_them']);
			$add_ras = LFS($_POST['add_ras']);
			$add_ads = LFS($_POST['add_ads']);
			$set_group = LFS($_POST['set_group']);
			$set_prev = LFS($_POST['set_prev']);
			$set_faq = LFS($_POST['set_faq']);
			$edit_tooltip = LFS($_POST['edit_tooltip']);
			$edit_rules = LFS($_POST['edit_rules']);
			$edit_auth = LFS($_POST['edit_auth']);
			$edit_style = LFS($_POST['edit_style']);
			$panel = LFS($_POST['panel']);


			dbquery("INSERT INTO `admin_perm` SET `name` = '" . $perm_name . "', `color_prefix` = '" . $color_group . "', `edit_razdel` = '" . $edit_razdel . "', `edit_kat` = '" . $edit_kat . "', `del_razdel` = '" . $del_razdel . "', `del_kat` = '" . $del_kat . "', `create_razdel` = '" . $create_razdel . "', `create_kat` = '" . $create_kat . "', `edit_users` = '" . $edit_users . "', `ban_users` = '" . $ban_users . "', `del_them` = '" . $del_them . "', `top_them` = '" . $top_them . "', `close_them` = '" . $close_them . "', `del_post` = '" . $del_post . "', `edit_post` = '" . $edit_post . "', `move_them` = '" . $move_them . "', `add_ads` = '" . $add_ads . "', `add_ras` = '" . $add_ras . "', `set_group` = '" . $set_group . "', `set_prev` = '" . $set_prev . "', `set_faq` = '" . $set_faq . "', `edit_tooltip` = '" . $edit_tooltip . "', `edit_rules` = '" . $edit_rules . "', `edit_auth` = '" . $edit_auth . "', `edit_style` = '" . $edit_style . "', `panel` = '" . $panel . "'");

			echo '<div class="menu_nbr"><center><b>Группа создана!</b></center></div>';
			echo '<div class="menu_nbr">» <a href="?act=user-group">Вернуться назад</a></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Новая группа пользователей</div>';
		echo '<form action="" method="POST">';
		
		echo '<div class="menu_nb">';
		echo '<div class="setting_punkt">Название группы:</div><input type="text" name="perm_name"/><br />';
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo '<div class="setting_punkt">Стиль лычки группы:</div><textarea name="color_group" style="height: 200px;" placeholder="css..."></textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_razdel', 'Редактирование разделов');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_kat', 'Редактирование категорий');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('del_razdel', 'Удаление разделов');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('del_kat', 'Удаление категорий');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('create_razdel', 'Создание разделов:');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('create_kat', 'Создание категорий');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('del_them', 'Удаление тем');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('top_them', 'Поднятие тем');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('close_them', 'Закрытие тем');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('move_them', 'Перемещение тем');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_post', 'Редактирование постов');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('del_post', 'Удаление постов');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_users', 'Редактирование пользователей');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('ban_users', 'Бан пользователей');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('add_ads', 'Добавление рекламы');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('add_ras', 'Создание рассылок');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('set_group', 'Добавление групп пользователей');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('set_prev', 'Побавление повышений пользователей');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('set_faq', 'Добавление ответов F.A.Q');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_tooltip', 'Управление ключевыми словами');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_rules', 'Управление правилами');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_auth', 'Управление авторизацией');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('edit_style', 'Управление дизайном');
		echo '</div>';
		
		echo '<div class="menu_t">';
		echo $select->createSelectField('panel', 'Доступ к панели управления');
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
		echo '<input type="submit" name="saveperm" value="Создать" />';
		echo '</div>';
		echo '</div>';
		
		echo '</form>';

		break;


	case 'up-user-group':

		if ($perm['set_group'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$id = abs(intval($_GET['id']));
		$perm = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $id . "'"));

		if (isset($_REQUEST['delgroup'])) {
			dbquery("DELETE FROM `admin_perm` WHERE `id` = '" . $id . "'");
			header('Location: ' . homeLink() . '/admin/?act=user-group');
			exit();
		}

		if (isset($_REQUEST['upperm'])) {

			$perm_name = LFS($_POST['perm_name']);
			$perm_icon = LFS($_POST['icon']);
			$color_group = LFS($_POST['color_group']);
			$edit_razdel = LFS($_POST['edit_razdel']);
			$edit_kat = LFS($_POST['edit_kat']);
			$del_razdel = LFS($_POST['del_razdel']);
			$del_kat = LFS($_POST['del_kat']);
			$create_razdel = LFS($_POST['create_razdel']);
			$create_kat = LFS($_POST['create_kat']);
			$edit_users = LFS($_POST['edit_users']);
			$ban_users = LFS($_POST['ban_users']);
			$del_them = LFS($_POST['del_them']);
			$top_them = LFS($_POST['top_them']);
			$close_them = LFS($_POST['close_them']);
			$del_post = LFS($_POST['del_post']);
			$edit_post = LFS($_POST['edit_post']);
			$move_them = LFS($_POST['move_them']);
			$add_ras = LFS($_POST['add_ras']);
			$add_ads = LFS($_POST['add_ads']);
			$set_group = LFS($_POST['set_group']);
			$set_prev = LFS($_POST['set_prev']);
			$set_faq = LFS($_POST['set_faq']);
			$edit_tooltip = LFS($_POST['edit_tooltip']);
			$edit_rules = LFS($_POST['edit_rules']);
			$edit_auth = LFS($_POST['edit_auth']);
			$edit_style = LFS($_POST['edit_style']);
			$panel = LFS($_POST['panel']);



			dbquery("UPDATE `admin_perm` SET `name` = '" . $perm_name . "', `color_prefix` = '" . $color_group . "', `edit_razdel` = '" . $edit_razdel . "', `edit_kat` = '" . $edit_kat . "', `del_razdel` = '" . $del_razdel . "', `del_kat` = '" . $del_kat . "', `create_razdel` = '" . $create_razdel . "', `create_kat` = '" . $create_kat . "', `edit_users` = '" . $edit_users . "', `ban_users` = '" . $ban_users . "', `del_them` = '" . $del_them . "', `top_them` = '" . $top_them . "', `close_them` = '" . $close_them . "', `del_post` = '" . $del_post . "', `edit_post` = '" . $edit_post . "', `move_them` = '" . $move_them . "', `add_ads` = '" . $add_ads . "', `add_ras` = '" . $add_ras . "', `set_group` = '" . $set_group . "', `set_prev` = '" . $set_prev . "', `set_faq` = '" . $set_faq . "', `icon` = '" . $perm_icon . "', `edit_rules` = '" . $edit_rules . "', `edit_tooltip` = '" . $edit_tooltip . "', `edit_auth` = '" . $edit_auth . "', `edit_style` = '" . $edit_style . "', `panel` = '" . $panel . "' WHERE `id` = '" . $id . "'");

			echo '<div class="menu_nbr"><center><b>Группа обновлена!</b></center></div>';
			echo '<div class="menu_nbr">» <a href="?act=user-group">Вернуться назад</a></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}


		if (isset($_REQUEST['upperm2'])) {

			$perm_name = LFS($_POST['perm_name']);
			$perm_icon = LFS($_POST['icon']);
			$color_group = LFS($_POST['color_group']);

			dbquery("UPDATE `admin_perm` SET `name` = '" . $perm_name . "', `color_prefix` = '" . $color_group . "', `icon` = '" . $perm_icon . "' WHERE `id` = '2'");

			echo '<div class="menu_nbr"><center><b>Группа обновлена!</b></center></div>';
			echo '<div class="menu_nbr">» <a href="?act=user-group">Вернуться назад</a></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<div class="title">Изменение группы пользователей</div>';
		echo '<form action="" method="POST">';

		if ($perm['id'] == 2) {
			echo '<div class="menu_nb">';
			echo '<div class="setting_punkt">Название группы:</div><input type="text" name="perm_name" value="' . $perm['name'] . '">';
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo '<div class="setting_punkt">Стиль лычки группы:</div><textarea name="color_group" style="height: 200px;">' . $perm['color_prefix'] . '</textarea>';
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo '<div class="setting_punkt">Иконка группы:</div><textarea type="text" name="icon" style="height: 200px;"></textarea>';
			echo '</div>';
			
			echo '<div class="setting-footer">';
			echo '<div class="menu_t">';
			echo '<input type="submit" name="upperm2" value="Сохранить" />';
			echo '</div>';
			echo '</div>';
		} else {
			echo '<div class="menu_nb">';
			echo '<div class="setting_punkt">Название группы:</div><input type="text" name="perm_name" value="' . $perm['name'] . '">';
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo '<div class="setting_punkt">Стиль лычки группы:</div><textarea name="color_group" style="height: 200px;">' . $perm['color_prefix'] . '</textarea>';
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo '<div class="setting_punkt">Иконка группы:</div><textarea type="text" name="icon" style="height: 200px;"></textarea>';
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_razdel', 'Редактирование разделов', $perm['edit_razdel']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_kat', 'Редактирование категорий', $perm['edit_kat']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('del_razdel', 'Удаление разделов', $perm['del_razdel']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('del_kat', 'Удаление категорий', $perm['del_kat']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('create_razdel', 'Создание разделов:', $perm['create_razdel']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('create_kat', 'Создание категорий', $perm['create_kat']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('del_them', 'Удаление тем', $perm['del_them']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('top_them', 'Поднятие тем', $perm['top_them']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('close_them', 'Закрытие тем', $perm['close_them']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('move_them', 'Перемещение тем', $perm['move_them']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_post', 'Редактирование постов', $perm['edit_post']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('del_post', 'Удаление постов', $perm['del_post']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_users', 'Редактирование пользователей', $perm['edit_users']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('ban_users', 'Бан пользователей', $perm['ban_users']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('add_ads', 'Добавление рекламы', $perm['add_ads']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('add_ras', 'Создание рассылок', $perm['add_ras']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('set_group', 'Добавление групп пользователей', $perm['set_group']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('set_prev', 'Побавление повышений пользователей', $perm['set_prev']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('set_faq', 'Добавление ответов F.A.Q', $perm['set_faq']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_tooltip', 'Управление ключевыми словами', $perm['edit_tooltip']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_rules', 'Управление правилами', $perm['edit_rules']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_auth', 'Управление авторизацией', $perm['edit_auth']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('edit_style', 'Управление дизайном', $perm['edit_style']);
			echo '</div>';
			
			echo '<div class="menu_t">';
			echo $select->updateSelectField('panel', 'Доступ к панели управления', $perm['panel']);
			echo '</div>';
			
			echo '<div class="setting-footer">';
			echo '<div class="menu_t">';
			echo '<input type="submit" name="upperm" value="Сохранить">';
			if ($perm['id'] < 3) {
				echo '<span></span>';
			} else {
				echo '<input type="submit" name="delgroup" value="Удалить" />';
			}
			echo '</div>';
			echo '</div>';
		}

		echo '</form>';

		break;
}
//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
