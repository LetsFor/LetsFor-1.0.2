<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) {
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/design/element-styles/them-style-guest.php');
}

##############################
########## Главная ###########
##############################
$perm = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $user['level_us'] . "'"));

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
	default:

		echo '<title>Разделы и категории -  ' . $LF_info . ' - ' . $LF_name . '</title>';

		echo '<div class="menu_nb">';
		echo '<h1>' . $LF_name . '<div class="dropdown" style="float: right">
		<span class="button min btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-plus" style="margin-right: 7px;"></i>Добавить узел</span>
		<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
		if ($perm['create_razdel'] > 0) {
			echo ' <li><a class="dropdown-item" href="' . homeLink() . '/forum/nr">Добавить раздел</a></li>';
		}
		if ($perm['create_kat'] > 0) {
			echo ' <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#new_kat">Добавить категорию</a></li>';
		}
		echo '</ui></h1>';

		echo '<h2 class="kat_opisanie">Узлы форума</h2>';
		echo '</div>';

		$forum_r = dbquery("SELECT * FROM `forum_razdel` ORDER BY `id` ASC");
		echo '<div class="menu_cont">';
		while ($a = mfa($forum_r)) {
			echo '<div class="lazdel-uz">';
			echo '<div class="div-link" role="link" id="cont_tem" data-href="/razdel' . $a['id'] . '"><span class="icon" style="margin-right: 10px;"><i class="fas fa-bars"></i></span>' . $a['name'] . '';
			echo '<span class="but-right" style="float: right;">';
			if ($perm['edit_razdel'] > 0) {
				echo '<a class="link-btn-group" href="' . homeLink() . '/forum/red_razdel' . $a['id'] . '">Изменить </a>';
			}
			if ($perm['del_razdel'] > 0) {
				echo '<a class="link-btn-group" href="' . homeLink() . '/forum/del_razdel' . $a['id'] . '"> Удалить</a>';
			}
			echo '</span>';
			echo '</div>';

			$forum_k = dbquery("SELECT * FROM `forum_kat` WHERE `razdel` = '" . intval($a['id']) . "' ORDER BY `id` ASC");
			while ($s = mfa($forum_k)) {
				echo '<div class="div-link" role="link" id="cont_tem" data-href="/kat' . $s['id'] . '"><span class="icon" style="color: #999; font-size: 14px; margin-right: 10px;"><i class="far fa-comments"></i></span>' . $s['name'] . '';
				echo '<span class="but-right" style="float: right;">';
				if ($perm['edit_kat'] > 0) {
					echo '<a class="link-btn-group" href="' . homeLink() . '/forum/red_kat' . $s['id'] . '">Изменить </a>';
				}
				if ($perm['del_kat'] > 0) {
					echo '<a class="link-btn-group" href="' . homeLink() . '/forum/del_kat' . $s['id'] . '"> Удалить</a>';
				}
				echo '</span>';
				echo '</div>';
			}
			echo '</div>';
		}
		echo '</div>';



		$count = msres(dbquery("SELECT COUNT(*) FROM `forum_razdel` "), 0);
		if ($count < 1) echo '<div class="menu_nb">Разделов пока что нет</div>';

		require_once('footers.php');

		break;
		##############################
		### Редактирование раздела ###
		##############################
	case 'red_razdel':

		$id = abs(intval($_GET['id']));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $id . "'"));

		if ($forum_r == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такого раздела не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($perm['edit_razdel'] < 1) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>У вас не достаточно прав для просмотра данной страницы!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="title">Редактирование раздела ' . $forum_r['name'] . '</div>';

		/* Если нажали кнопку */
		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$opis = LFS($_POST['opis']);

			if (mb_strlen($opis, 'UTF-8') < 5) $err = 'Минимум для ввода 5 символов!';
			if (empty($opis)) $err = 'Введите описание раздела!';
			if (mb_strlen($name, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($name)) $err = 'Введите название раздела!';

			if ($err) {
				echo '<div class="menu_nb"><center><b>' . $err . '</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			/* Делаем запрос */
			dbquery("UPDATE `forum_razdel` SET `name` = '" . $name . "',`opis` = '" . $opis . "' WHERE `id` = '" . $id . "'");
			showAlert('Успешно!', 'info', 3000);
		}

		echo '<form action="" method="POST"> 
		<div class="menu_nb">
		<div class="setting_punkt">Название раздела:</div><input type="text" placeholder="Название" name="name" value="' . $forum_r['name'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Описание раздела:</div><textarea name="opis" placeholder="Описание">' . $forum_r['opis'] . '</textarea>
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Редактировать">
		</div>
		</div>
		
		</form>';

		break;
		##############################
		## Редактирование категории ##
		##############################
	case 'red_kat':

		$id = abs(intval($_GET['id']));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_k['razdel'] . "'"));

		if ($forum_k == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такого подраздела не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($perm['edit_kat'] < 1) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>У вас не достаточно прав для просмотра данной страницы!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="title">Редактирование категории ' . $forum_k['name'] . '</div>';

		/* Если нажали кнопку */
		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$opisanie = LFS($_POST['opisanie']);
			$icon = LFS($_POST['icon']);
			$background = LFS($_POST['background']);

			if (mb_strlen($name, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($name)) $err = 'Введите название категории!';

			if ($err) {
				echo '<div class="menu_nb"><center><b>' . $err . '</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			/* Делаем запрос */
			dbquery("UPDATE `forum_kat` SET `name` = '" . $name . "', `opisanie` = '" . $opisanie . "', `icon` = '" . $icon . "', `background` = '" . $background . "' WHERE `id` = '" . $id . "'");
			showAlert('Успешно!', 'info', 3000);
		}

		echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Название категории:</div><input type="text" name="name" value="' . $forum_k['name'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Описание категории:</div><textarea placeholder="Описание" name="opisanie">' . $forum_k['opisanie'] . '</textarea>
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Иконка категории:</div><textarea name="icon" placeholder="<svg..." style="height: 100px;">' . $forum_k['icon'] . '</textarea>
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Фон категории:</div><input type="text" name="background" value="' . $forum_k['background'] . '">
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Редактировать">
		</div>
		</div>
		
		</form>';


		break;
		##############################
		##### Удаление категории #####
		##############################
	case 'del_kat':

		echo '<meta name="robots" content="noindex">';

		$id = abs(intval($_GET['id']));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $id . "'"));

		if ($forum_k == 0) {
			echo '<div class="menu_nb"><a href="' . homeLink() . '/forum/">Форум</a> | Ошибка</div><div class="menu_nb"><center><b>Такого подраздела не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($perm['del_kat'] < 1) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>У вас не достаточно прав для просмотра данной страницы!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if (isset($_REQUEST['ok'])) {
			dbquery("DELETE FROM `forum_kat` where `id` = '" . $id . "'");
			dbquery("DELETE FROM `forum_tema` where `kat` = '" . $id . "'");
			dbquery("DELETE FROM `forum_post` where `kat` = '" . $id . "'");
			header('Location: ' . homeLink() . '/razdel' . $forum_k['razdel'] . '');
			exit();
		}

		echo '<div class="title">Удаление категории ' . $forum_k['name'] . '</div>
		<div class="menu_nb">Вы действительно хотите удалить эту категорию?</div>
		<div class="menu_nb"><a class="button" href="' . homeLink() . '/forum/del_kat' . $id . '?ok" style="margin-right: 5px;">Удалить</a><a class="button" href="' . homeLink() . '/forum">Отмена</a></div>';

		break;
		##############################
		###### Удаление раздела ######
		##############################
	case 'del_razdel':

		echo '<meta name="robots" content="noindex">';

		$id = abs(intval($_GET['id']));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $id . "'"));

		if ($forum_r == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такого раздела не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($perm['del_razdel'] < 1) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>У вас не достаточно прав для просмотра данной страницы!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if (isset($_REQUEST['ok'])) {
			dbquery("DELETE FROM `forum_razdel` where `id` = '" . $id . "'");
			dbquery("DELETE FROM `forum_kat` where `razdel` = '" . $id . "'");
			dbquery("DELETE FROM `forum_tema` where `razdel` = '" . $id . "'");
			dbquery("DELETE FROM `forum_post` where `razdel` = '" . $id . "'");
			header('Location: ' . homeLink() . '/forum');
			exit();
		}

		echo '<div class="title">Удаление раздела ' . $forum_r['name'] . '</div>
		<div class="menu_nb">Вы действительно хотите удалить этот раздел?</div>
		<div class="menu_nb"><a class="button" href="' . homeLink() . '/forum/del_razdel' . $id . '?ok" style="margin-right: 5px;">Удалить</a><a class="button" href="' . homeLink() . '/forum">Отмена</a></div>';

		break;
		##############################
		######## Новый раздел ########
		##############################
	case 'nr':

		echo '<title>Новый раздел</title>';
		if ($perm['create_razdel'] < 1) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>У вас не достаточно прав для просмотра данной страницы!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="title">Новый раздел</div>';

		/* Если нажали кнопку */
		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$opis = LFS($_POST['opis']);

			if (mb_strlen($opis, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($opis)) $err = 'Введите описание раздела!';
			if (mb_strlen($name, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($name)) $err = 'Введите название раздела!';

			if ($err) {
				echo '<div class="menu_nb"><center><b>' . $err . '</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("INSERT INTO `forum_razdel` SET `name` = '" . $name . "',`opis` = '" . $opis . "'");
			header('Location: ' . homeLink() . '/forum');
		}

		echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Название раздела:</div><input type="text" placeholder="Название" name="name" value="">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Описание раздела:</div><textarea name="opis" placeholder="Описание"></textarea>
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Создать">
		</div>
		</div>
		
		</form>';

		break;
		##############################
		######## Вывод раздела #######
		##############################
	case 'razdel':

		$id = abs(intval($_GET['id']));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $id . "'"));

		if ($forum_r == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такого раздела не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<title>' . $forum_r['name'] . ' -  ' . $LF_info . ' - ' . $LF_name . '</title>';

		echo '<div class="menu_nb">
		<h1>' . $forum_r['name'] . '</h1>
		<h2 class="kat_opisanie">' . $forum_r['opis'] . '</h2>
		</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_kat` WHERE `razdel` = '" . $id . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$forum_k = dbquery("SELECT * FROM `forum_kat` WHERE `razdel` = '" . $id . "' ORDER BY `id` ASC LIMIT $start, $max");
		
		echo '<div class="menu_cont">';
		while ($a = mfa($forum_k)) {
			echo '<div class="div-link" role="link" id="cont_tem" data-href="' . homeLink() . '/kat' . $a['id'] . '"><span class="icon" style="color: #999; font-size: 14px; margin-right: 10px;"><i class="far fa-comments"></i></span>' . $a['name'] . '';
				echo '<span class="but-right" style="float: right;">';
				if ($perm['edit_kat'] > 0) {
					echo '<a class="link-btn-group" href="' . homeLink() . '/forum/red_kat' . $a['id'] . '">Изменить </a>';
				}
				if ($perm['del_kat'] > 0) {
					echo '<a class="link-btn-group" href="' . homeLink() . '/forum/del_kat' . $a['id'] . '"> Удалить</a>';
				}
				echo '</span>';
				echo '</div>';
		}
		echo '</div>';

		if ($k_post < 1) {
			echo '<div class="block--info" style="margin-top: 10px;">В данном разделе нет категорий</div>';
		}

		require_once('footers.php');

		if ($k_page > 1) echo str('' . homeLink() . '/razdel' . $id . '?', $k_page, $page); // Вывод страниц

		break;
		##############################
		####### Вывод категории ######
		##############################
	case 'kat':

		$id = abs(intval($_GET['id']));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_k['razdel'] . "'"));

		if ($forum_k == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Данная категория не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<title>' . $forum_k['name'] . ' -  ' . $LF_info . ' - ' . $LF_name . '</title>';

		require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/bg_kat.php');

		echo '<div class="menu_nb" style="border-bottom: 0px; font-size: 20px;">
		<span class="kat_name"><h1>' . $forum_k['name'] . '</h1><h2 class="kat_opisanie">' . $forum_k['opisanie'] . '</h2></span>
		</div>';

		echo '<div class="menu_nb">';
		echo '<a class="long" id="updateThemsButton"><i class="fas fa-sharp fa-regular fa-arrow-rotate-right fa-fw"></i></a>';
		if (empty($user['id'])) {
			echo '<a class="button" href="' . homeLink() . '/login" style="float: right;">Авторизация</a>';
		} else {
			echo '<a class="button" href="' . homeLink() . '/new-them-' . $id . '" style="float: right;"><i class="fas fa-plus"></i>Добавить тему</a>';
		}
		echo '</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `kat` = '" . $id . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$forum = dbquery("SELECT * FROM `forum_tema` WHERE `kat` = '" . $id . "' ORDER BY `top_them` DESC LIMIT $start, $max");
		
		if ($k_post > 0) {
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/add_them_list.php');
		} else {
			echo '<div class="menu_cont"><div class="block--info"><center>В данной категории нет тем!</center></div></div>';
		}
		if ($k_page > 1) {
			echo '<div class="setting-footer"><div class="menu_nb">'; str('' . homeLink() . '/kat' . $id . '?', $k_page, $page); echo '</div></div>';
		}

		require_once('footers.php');
		?>
		<script>
		document.getElementById('updateThemsButton').addEventListener('click', function() {
			const xhr = new XMLHttpRequest();
			const loader = document.getElementById('loading');
			const block = document.getElementById('thems-kat');
			
			// Показываем затемнение и лоадер
			loader.style.display = 'block';
			block.style.opacity = '0';
			
			xhr.open('GET', '/LFcore/src/kat-update-thems.php?id=<? echo $id ?>', true); // Путь к вашему обработчику
			xhr.onload = function() {
				// Скрываем затемнение и лоадер
				loader.style.display = 'none';
				block.style.opacity = '1';
				
				if (xhr.status === 200) {
					// Обновляем содержимое блока
					document.getElementById('thems-kat').innerHTML = xhr.responseText;
					
					// Убираем параметр ?selection из адресной строки
					const newUrl = window.location.pathname;
					window.history.replaceState(null, '', newUrl);
				} else {
					console.error('Ошибка загрузки данных:', xhr.statusText);
				}
			};
			xhr.onerror = function() {
				
				// Скрываем затемнение и лоадер, даже если запрос не удался
				loader.style.display = 'none';
				block.style.opacity = '1';
				console.error('Ошибка запроса');
			};
			xhr.send();
		});
		</script>
		<?
		break;
		##############################
		######### Новая тема #########
		##############################
	case 'nt':

		$id = abs(intval($_GET['id']));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_k['razdel'] . "'"));
		$forum_p = mfa(dbquery("SELECT * FROM `forum_prefix` WHERE `id` = '" . $id . "'"));

		if (empty($user['id'])) {
			header('Location: ' . homeLink() . '');
			exit();
		}

		if ($forum_k == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такой категории не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		require_once ($_SERVER['DOCUMENT_ROOT'] . '/design/element-styles/new-them-style.php');

		echo '<div class="menu_nbr" style="border-radius: 10px 10px 0 0;"><a href="' . homeLink() . '">Главная</a> | <a href="' . homeLink() . '/kat' . $forum_k['id'] . '">' . $forum_k['name'] . '</a> | Новая тема</div>';

		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$text = LFS($_POST['msg']);
			$level = LFS($_POST['level']);
			$select_them = LFS($_POST['select_them']);
			$prefix_array = $_POST['prefix_ids_list'];

			if (mb_strlen($name, 'UTF-8') > 500) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Слишком длинное название темы</br>(Максимум 500 символов)</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (empty($name)) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Вы не ввели название темы</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (empty($text)) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Вы не ввели текст темы</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (empty($user['id'])) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Авторизуйтесь чтобы создать тему</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			$tema_spam = mfar(dbquery('select * from `forum_tema` where `us` = "' . $user['id'] . '" and `name` = "' . $name . '"'));
			if ($tema_spam != 0) {
				echo '<div class="menu_nb"><center><b>Вы такую тему уже создавали!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			$time = dbquery("SELECT * FROM `forum_tema` WHERE `us`='" . $user['id'] . "' ORDER BY `time_up` DESC");
			while ($t = mfa($time)) {
				$forum_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `forum_tema` "));
				$timeout = $t['time_up'];
				if ((time() - $timeout) < $forum_antispam['forum_tema']) {
					echo '<div class="menu_cont">';
					echo '<div class="err">Создавать тему можно раз в ' . $forum_antispam['forum_tema'] . ' секунд</div>';
					echo '</div>';
					require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
					exit();
				}
			}

			dbquery("INSERT INTO `forum_tema` SET `name` = '" . $name . "',`razdel` = '" . $forum_r['id'] . "',`kat` = '" . $id . "',`text_col` = '" . $text . "',`status` = '0',`us` = '" . $user['id'] . "',`time_up` = '" . time() . "',`up` = '" . time() . "',`top_them` = '" . time() . "',`color` = '" . $prv_us['style_them_title'] . "',`select_them` = '" . $select_them . "',`prosm` = '0'");

			$sql = getLastInstId();

			insertMultiple('forum_them_prefix', 'prefix_id', 'them_id', $sql);
			dbquery("INSERT INTO `forum_post` SET `kat` = '" . $id . "',`text_col` = '" . $text . "',`us` = '" . $user['id'] . "',`time_up` = '" . time() . "',`tema` = '" . $sql . "',`razdel` = '" . $forum_r['id'] . "'");

			$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
			if ($settings['forum_tem_m'] > 0) {
			dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] + $settings['forum_tem_m']) . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
			dbquery("INSERT INTO `transactions` SET `amount` =  '" . $settings['forum_tem_m'] . "', `type_col` = '2', `timestamp_col` = '" . time() . "', `from_us` = '2', `to_us` = '" . $user['id'] . "', `status` = '1'");
			}
			header('Location: /tema' . $sql . '');
			exit();
		}

		echo '<title>Создать тему в разделе ' . $forum_k['name'] . '</title>';
		echo '<form autocomplete="off" action="" name="message" method="POST" id="new-them">';
		echo '<div class="menu_nb">
		<div class="setting_punkt"><div class="label" style="width: -webkit-fill-available; display: block;">Заголовок</div></div>
		<center><input placeholder="О чем ваша тема?" type="text_nt" name="name" value=""></center>';
		echo '</div>';
		
		echo '<div class="menu_t" style="border-bottom: 0px; padding-top: 18px;">';
		echo '<div class="selections">';
		echo '<div class="setting_punkt"><span class="label">Префикс:</span></div>';
		echo '<div class="example">';
		echo '<select id="multiple_label_example" limit="3" class="chosen-select" name="prefix_ids_list[]" multiple>';
		$prefix = dbquery("SELECT * FROM `forum_prefix` ORDER BY `id` LIMIT 999");
		while ($pr = mfa($prefix)) {
			echo '<option value="' . $pr['id'] . '">' . $pr['name'] . '</option>';
		}
		echo '</select>';
		echo '</div>';
		
		echo '</div>';
		echo '</div>';
		
		if ($prv_us['color_them_title'] == 1) {
			echo '<div class="menu_t">';
			echo '<div class="setting_punkt"><div class="label" style="width: -webkit-fill-available; display: block;">Выделение темы на главной:</div></div>
			<div class="gt-select"><select name="select_them" style="width: -webkit-fill-available">';
			$data = array('Нет' => '0', 'Да' => '1');
			foreach ($data as $key => $value) {
				echo ' <option value="' . $value . '">' . $key . '</option>';
			}
			echo '</select></div>';
			echo '</div>';
		} else {
			echo '<span></span>';
		}
		
		echo '<div class="menu_t">';
		require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/bbcode.php');
		echo '</div>';
		echo '<div class="menu_nb">';
		echo '<textarea style="margin-bottom: 0px; height: 200px;" placeholder="Сообщение..." name="msg"></textarea>';
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
		echo '<input type="submit" style="margin: 0px;" name="submit" value="Создать тему" />';
		echo '</div>';
		echo '</div>';
		
		echo '</form>';

		require_once('footers.php');

		break;

		##############################
		####### Новая категория ######
		##############################
	case 'nk':

		echo '<title>Новая категория</title>';
		$id = abs(intval($_GET['id']));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $id . "'"));

		if ($forum_r == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такого раздела не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($perm['create_kat'] < 1) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>У вас не достаточно прав для просмотра данной страницы!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="title">Новая категория</div>';

		/* Если нажали кнопку */
		if (isset($_REQUEST['submit'])) {

			$name = LFS($_POST['name']);
			$opisanie = LFS($_POST['opisanie']);
			$icon = LFS($_POST['icon']);
			$background = LFS($_POST['background']);

			if (mb_strlen($name, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($name)) $err = 'Введите название категории!';

			if ($err) {
				echo err($err);
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit;
			}
			dbquery("INSERT INTO `forum_kat` SET `razdel` = '" . $id . "',`name` = '" . $name . "',`opisanie` = '" . $opisanie . "',`icon` = '" . $icon . "',`background` = '" . $background . "'");

			header('Location: ' . homeLink() . '/forum');
		}

		echo '<form action="" method="POST"> 
		<div class="menu_nb">
		<div class="setting_punkt">Название категории:</div><input type="text" placeholder="Название" name="name" value="">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Описание категории:</div><textarea placeholder="Описание" name="opisanie"></textarea>
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Иконка категории:</div><textarea name="icon" placeholder="<svg..." style="height: 100px;"></textarea>
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Фон категории:</div><input type="text" placeholder="Фон" name="background" value="">
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Создать">
		</div>
		</div>
		
		</form>';

		break;
		##############################
		######### Вывод темы #########
		##############################
	case 'tema':

		$id = abs(intval($_GET['id']));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_t['razdel'] . "'"));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $forum_t['kat'] . "'"));
		$forum_zaklad = mfa(dbquery("SELECT * FROM `forum_zaklad` WHERE `tema` = '" . $id . "' and `us` = '" . $user['id'] . "' "));
		$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $forum_t['us'] . "'"));
		$perm_ank = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $ank['level_us'] . "'"));
		$prv_us_t = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $ank['prev'] . "'"));

		if ($forum_t == 0) {
			echo '<div class="menu_nb">Форум | Ошибка</div><div class="menu_nb"><center><b>Такой темы не существует!</b></center></div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<style>
		.content {
			padding: 0px;
			background: none;
			margin: 0px;
		}
		@media all and (min-width: 800px){
			#sidebar {
				display: none;
			}
		.back_but_link {
			display: inline-block;
			}
		.content {
			margin: 0px;
			padding: 0px;
			background: none;
			}
		}
		</style>';

		echo '<title>' . $forum_t['name'] . ' -  ' . $LF_info . ' - ' . $LF_name . '</title>';

		if ($prv_us_t['set_background_head_them'] < 1) {
			echo '<div class="them_title">';
		} else {
			echo '<div class="them_title" style="background-image: linear-gradient(rgba(var(--blackout-them-head)), rgba(var(--blackout-them-head))), url(' . $ank['background'] . ');">';
		}
		echo '<span class="prefix_them">';

		$prefixes = dbquery("SELECT * FROM `forum_them_prefix` WHERE `them_id`='" . $id . "' ORDER BY `id`");
		while ($pr = mfa($prefixes)) {
			$prefix_them = dbquery("SELECT * FROM `forum_prefix` WHERE `id`='" . $pr['prefix_id'] . "'");
			while ($pr_t = mfa($prefix_them)) {
				echo '<span class="prefix_for" style="display: inline-block;"><div class="prefix_t" style="' . $pr_t['style'] . '"><b>' . $pr_t['name'] . '</b></div></span>';
			}
		}

		echo '</span>';
		
		
		echo '<div class="name_teme_tree"><h1 style="display: inline;">' . $forum_t['name'] . '</h1></div><br />';
		echo '<div class="dod_block">
		<span class="dodname_forum">Тема в разделе </span><a class="highlight_dodname_forum" href="/kat' . $forum_t['kat'] . '">' . $forum_k['name'] . '</a>
		<span class="dodname_forum"> создана </span><span class="highlight_dodname_forum">' . vremja($forum_t['time_up']) . '</span>
		<span class="dodname_forum"> пользователем</span> ' . nick($forum_t['us']) . '';

		if ($forum_t['top_them'] == $forum_t['time_up']) {
			echo '<span></span>';
		} else {
			echo '<span class="info-separator"></span>
			<span class="dodname_forum">поднята </span><span class="highlight_dodname_forum">' . vremja($forum_t['top_them']) . '</span>';
		}

		$vievNumRes = $view->handleForumViews($forum_t['id']);

		echo '<span class="info-separator"></span>
		<span class="dodname_forum">просмотров: </span><span class="highlight_dodname_forum">' . $vievNumRes . '</span>
		</div>';
		echo '</div>';

		if ($forum_t['status'] == 1) {
			echo '<center><div class="t_lock"> <i class="fas fa-lock" style="padding-right: 5px; color: #abb15b"></i> Тема закрыта для обсуждений</div></center>';
		}
		
		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `tema` = '" . $id . "' and `kat` = '" . $forum_t['kat'] . "' and `razdel` = '" . $forum_t['razdel'] . "'"), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$k_post = $start + 1;
		$post = dbquery("SELECT * FROM `forum_post` WHERE `tema` = '" . $id . "' ORDER BY `id` LIMIT $start,$max");

		echo '<div class="head_bar_menu_pages">';
		$gde = '/tema' . $id . '';
		if ($k_page > 1) {
			echo str('' . homeLink() . '/tema' . $id . '?', $k_page, $page);
		} else {
			echo '<style>.head_bar_menu_pages { display: none; }</style>';
		} 
		echo '</div>';

		echo '<div class="head_bar_menu">';

		if (empty($user['id'])) {
			echo '<span></span>';
		} else {
			echo '<div class="btn-group">
			<span><a style="position: relative;" class="btn btn-secondary dropdown-toggle bar_items" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-ellipsis-h" style="font-size: 20px;"></i></a>
			<ul class="dropdown-menu" style="inset: auto;">';

			if ($forum_t['status'] == 1) {
				if ($user['id'] == $forum_t['us'] or $perm['close_them'] == 1) {
					echo '<li><a class="dropdown-item" href="' . homeLink() . '/forum/tema_close' . $id . '"><span class="fontawesome_in_menu"><i class="fas fa-unlock-alt"></i><span class="text_in_menu">Открыть</span></span></a></li>';
				}
			} else {
				if ($user['id'] == $forum_t['us'] or $perm['close_them'] == 1) {
					echo '<li><a class="dropdown-item" href="' . homeLink() . '/forum/tema_close' . $id . '"><span class="fontawesome_in_menu"><i class="fas fa-lock"></i><span class="text_in_menu">Закрыть</span></span></a></li>';
				}
			}

			if ($perm['move_them'] > 0) {
				echo '<li><a class="dropdown-item" href="' . homeLink() . '' . homeLink() . '/forum/index.php?act=move&id=' . $id . '"><span class="fontawesome_in_menu"><i class="fas fa-arrow-up-from-bracket"></i><span class="text_in_menu">Переместить</span></span></a></li>';
			}

			if (time() - $forum_t['top_them'] >= (3 * 60 * 60) & $perm['top_them'] == 1) {
				echo '<li><a class="dropdown-item" href="' . homeLink() . '/forum/tema_top' . $id . '"><span class="fontawesome_in_menu"><i class="fa-solid fa-arrow-up"></i><span class="text_in_menu">Поднять</span></span></a></li>';
			}
			
			if ($forum_t['us'] != $user['id'])  {
				echo '<li><a class="dropdown-item" href="' . homeLink() . '/complaint"><span class="fontawesome_in_menu"><i class="fas fa-trash"></i><span class="text_in_menu">Пожаловаться</span></span></a></li>';
			}

			if ($perm['del_them'] > 0 or $forum_t['us'] == $user['id']) {
				echo '<li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#delete_them" style="color: #ea4c3e;"><span class="fontawesome_in_menu"><i class="fas fa-trash"></i><span class="text_in_menu">Удалить</span></span></a></li>';
			}

			echo '</ul></span>';
			
			echo '<div class="right-them-items">';
			echo '<a class="bookmarkElement bar_items" data-them-id="' . $id . '"><span class="fontawesome_in_menu"><span class="bookmark"></span></span></a>';
			echo '</div>';
			
			echo '</div>';
		}

		echo '</div>';

		echo '<div class="tema_vis">';
		echo '<div class="post_list" id="load-post">';
		require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/post.php');
		echo '</div>';
		
		echo '<div id="post-results"></div>';

		if ($forum_t['status'] == 1) {
			echo '<span class="c_lock"><span class="lock_t">Тема закрыта для обсуждений</span></span>';
		} else {
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/reg-else-text.php');
		}
		echo '</div>';
		
?>
<script>
$(document).ready(function() {
    // Проверяем параметр "selection" в URL
    const urlParams = new URLSearchParams(window.location.search);
    const selection = urlParams.get('selection');

    // Создаем MutationObserver для отслеживания изменений в контейнере
    const targetNode = document.getElementById('post-results');
    const config = { childList: true, subtree: false }; // Отслеживаем только добавление новых дочерних элементов

    const callback = function(mutationsList) {
        mutationsList.forEach((mutation) => {
            if (mutation.type === 'childList') {
                // Если параметр "selection" существует и не равен "top", добавляем элемент .non-top
                if (selection && selection !== 'top' && !$('.non-top').length) {
                    const nonTopElement = '<div class="non-top animated-comment menu_nbr">Посмотрите предыдущие сообщения. <a class="punkt_head_menu" href="?selection=top"><span class="drag_menu_name"><b>Посмотреть?</b></span></a></div>';
                    $('#post-results').prepend(nonTopElement); // Добавляем элемент с анимацией
                }

                // Применяем анимацию к новым элементам
                $(mutation.addedNodes).addClass('animated-comment');
            }
        });
    };

    const observer = new MutationObserver(callback);
    observer.observe(targetNode, config); // Запускаем наблюдение за контейнером

    $('#myFormThem').submit(function(event) {
        event.preventDefault();

        // Показываем лоадер
        $('#loading').show();

        $.ajax({
            type: 'POST',
            url: '/LFcore/src/post-process.php?id=' + <?php echo $id; ?>, // Проверяем значение $id
            data: $(this).serialize(),
            success: function(response) {
                // Создаем временный контейнер для обработки ответа
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = response;

                // Удаляем подключенные CSS-стили
                const cssLinks = tempDiv.querySelectorAll('link[rel="stylesheet"]');
                cssLinks.forEach(link => link.remove());

                // Скрываем лоадер
                $('#loading').hide();

                // Добавляем очищенный контент с анимацией
                const newElement = $('<div></div>').html(tempDiv.innerHTML).addClass('animated-comment');
                $('#post-results').append(newElement);

                $.get('/LFcore/src/post-results.php?id=' + <?php echo $id; ?>, function(data) {
                    // Создаем временный контейнер для обработки ответа
                    const tempDiv2 = document.createElement('div');
                    tempDiv2.innerHTML = data;

                    // Удаляем подключенные CSS-стили
                    const cssLinks2 = tempDiv2.querySelectorAll('link[rel="stylesheet"]');
                    cssLinks2.forEach(link => link.remove());

                    // Добавляем очищенный контент с анимацией
                    const newData = $('<div></div>').html(tempDiv2.innerHTML).addClass('animated-comment');
                    $('#post-results').append(newData);
                });

                $('#myFormThem')[0].reset(); // Сбрасываем форму
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Скрываем лоадер при ошибке
                $('#loading').hide();
            }
        });
    });
});

</script>
<?

		//-----------------------------------------------------------------------------------------------------------------------------------------//

		echo '<div class="head_bar_menu_pages" style="margin-top: 15px;">';

		$gde = '/tema' . $id . '';
		if ($k_page > 1) {
			echo str('' . homeLink() . '/tema' . $id . '?', $k_page, $page);
		} else {
			echo '<style>.head_bar_menu_pages { display: none; }</style>';
		} // Вывод страниц

		echo '</div>';

		echo '<div class="kto_tut">';
		echo '<span class="title_kt"><b>Кто в теме:</b></span><br />';
		
		$id = abs(intval($_GET['id']));
		$tema = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$gde = '/tema' . $tema['id'] . '';
		
		$who_num = mfa(dbquery("SELECT COUNT(*) as count FROM `users` WHERE `gde` LIKE '%" . $gde . "%' and `viz` > '" . (time() - 60) . "' ORDER BY `viz` DESC"))['count'];
		$who = dbquery("SELECT * FROM `users` WHERE `gde` LIKE '%" . $gde . "%' and `viz` > '" . (time() - 60) . "' ORDER BY `viz` DESC");
		
		if ($who_num < 1) {
			echo '<span>В теме никого нет...</span>';
		} else {
			while ($ank = mfa($who)) {
				echo '<span class="user_gde">' . nick($ank['id']) . '</span>';
			}
		}
		
		echo '</div>';

		break;
	case 'zaklad':

		$id = abs(intval($_GET['id']));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$forum_zaklad = mfa(dbquery("SELECT * FROM `forum_zaklad` WHERE `tema` = '" . $id . "' and `us` = '" . $user['id'] . "' "));

		if ($forum_t == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такой темы не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if (empty($user['id'])) {
			echo '<div class="err">Авторизуйтесь чтобы добавить в закладки</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_zaklad == 0) {
			dbquery("INSERT INTO `forum_zaklad` SET `tema` = '" . $id . "',`us` = '" . $user['id'] . "' ");
			header('Location: ' . homeLink() . '/tema' . $id . '?selection=top');
			exit();
		} else {
			dbquery("DELETE FROM `forum_zaklad` where `id` = '" . $forum_zaklad['id'] . "'");
			header('Location: ' . homeLink() . '/tema' . $id . '?selection=top');
			exit();
		}
		break;

	case 'tema_top':
		$id = abs(intval($_GET['id']));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_t['razdel'] . "'"));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $forum_t['kat'] . "'"));

		if ($perm['top_them'] < 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">У вас не достаточно прав для просмотра данной страницы</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такой темы не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t['status'] == 0) {
			dbquery("UPDATE `forum_tema` SET `status` = '2', `top_them` = '" . time() . "' WHERE `id` = '" . $id . "'");
			header('Location: /tema' . $id . '?selection=top');
			exit();
		} else {
			dbquery("UPDATE `forum_tema` SET `status` = '0', `top_them` = '" . time() . "' WHERE `id` = '" . $id . "'");
			header('Location: /tema' . $id . '?selection=top');
			exit();
		}
		break;


	case 'tema_close':
		$id = abs(intval($_GET['id']));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_t['razdel'] . "'"));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $forum_t['kat'] . "'"));

		if ($user['id'] != $forum_t['us'] && $perm['close_them'] < 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">У вас не достаточно прав для просмотра данной страницы</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t == 0) {
			echo '<div class="menu_nb">Форум</div><div class="err">Такой темы не существует</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}


		if ($forum_t['status'] == 1) {
			dbquery("UPDATE `forum_tema` SET `status` = '0' WHERE `id` = '" . $id . "'");
			header('Location: /tema' . $id . '?selection=top');
			exit();
		}


		if (isset($_REQUEST['submit'])) {
			dbquery("UPDATE `forum_tema` SET `status` = '1' WHERE `id` = '" . $id . "'");
			header('Location: /tema' . $id . '?selection=top');
			exit();
		}

		echo '<div class="title">Закрытие темы: ' . $forum_t['name'] . '</div>
		<div class="menu_nb"><center><b>Закрыть тему?</b></center></div>';

		echo '<div class="menu_nb"><form action="" name="message" method="POST"> ';
		if ($user['bb_panel'] == 1) {
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/bbcode.php');
		}
		echo '<input type="submit" name="submit" value="Закрыть" />
		</form></div>';
		break;


	case 'tema_del':
		echo '<meta name="robots" content="noindex">';
		$id = abs(intval($_GET['id']));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_t['razdel'] . "'"));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $forum_t['kat'] . "'"));

		if ($perm['del_them'] < 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">У вас не достаточно прав для просмотра данной страницы</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такой темы не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		dbquery("DELETE FROM `forum_tema` where `id` = '" . $id . "'");
		dbquery("DELETE FROM `forum_post` where `tema` = '" . $id . "'");
		dbquery("DELETE FROM `forum_like` where `tema` = '" . $id . "'");
		header('Location: ' . homeLink() . '/kat' . $forum_k['id'] . '');
		exit();
		break;


	case 'post_del':
		if (empty($user['id'])) {
			header('Location: ' . homeLink() . '');
			exit();
		}

		$id = abs(intval($_GET['id']));
		$forum_p = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $id . "'"));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $forum_p['tema'] . "'"));

		if ($forum_t['status'] == 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Тема закрыта или ее не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($user['id'] != $forum_p['us'] && $perm['del_post'] < 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">У вас не достаточно прав для просмотра данной страницы</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_p == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такого поста не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		dbquery("DELETE FROM `forum_post` where `id` = '" . $id . "'");
		dbquery("DELETE FROM `forum_like` where `post` = '" . $id . "'");
		header('Location: ' . homeLink() . '/tema' . $forum_p['tema'] . '?selection=top');
		exit();
		break;


	case 'post_otvet':
		echo '<meta name="robots" content="noindex">';

		$id = abs(intval($_GET['id']));
		$forum_p = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $id . "'"));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $forum_p['tema'] . "'"));
		$us = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $forum_p['us'] . "'"));

		if (empty($user['id'])) {
			header('Location: ' . homeLink() . '');
			exit();
		}

		if ($forum_p == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такого поста не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t['status'] == 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Тема закрыта</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($user['id'] == $forum_p['us']) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Самому себе отвечать нельзя</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<style>
		.content {
			padding: 0px 10px 0px 10px;
		}
		</style>';

		echo '<div class="menu_nb"><a href="' . homeLink() . '/tema' . $forum_t['id'] . '">' . $forum_t['name'] . '</a></div>';

		if (isset($_REQUEST['submit'])) {

			$text = LFS($_POST['msg']);

			if (empty($text)) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Введите текст сообщения</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (mb_strlen($text, 'UTF-8') < 3) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Минимум для ввода 3 символа</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (empty($user['id'])) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Авторизуйтесь чтобы ответить</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			$time = dbquery("SELECT * FROM `forum_post` WHERE `us`='" . $user['id'] . "' ORDER BY `time_up` DESC");
			while ($t = mfa($time)) {
				$forum_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `forum_post` "));
				$timeout = $t['time_up'];
				if ((time() - $timeout) < $forum_antispam['forum_post']) {
					echo '<div class="menu_cont">';
					echo '<div class="err">Пишите не чаще чем раз в ' . $forum_antispam['forum_post'] . ' секунд!</div>';
					echo '</div>';
					require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
					exit();
				}
			}

			if ($user['form_file'] == 1) {
				$maxsize = 25; // Максимальный размер файла,в мегабайтах 
				$size = $_FILES['filename']['size']; // Вес файла

				if (@file_exists($_FILES['filename']['tmp_name'])) {

					if ($size > (1048576 * $maxsize)) {
						echo err($title, 'Максимальный размер файла ' . $maxsize . 'мб!');
						require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
						exit;
					}

					$filetype = array('jpg', 'gif', 'png', 'jpeg', 'bmp', 'zip', 'rar', 'mp4', 'mp3', 'amr', '3gp', 'avi', 'flv', 'jar', 'jad', 'apk', 'sis', 'sisx', 'ipa');
					$upfiletype = substr($_FILES['filename']['name'],  strrpos($_FILES['filename']['name'], ".") + 1);

					if (!in_array($upfiletype, $filetype)) {
						echo err($title, 'Такой формат запрещено загружать!');
						require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
						exit;
					}

					$files = $_SERVER['HTTP_HOST'] . '_' . rand(1234, 5678) . '_' . rand(1234, 5678) . '_' . $_FILES['filename']['name'];
					move_uploaded_file($_FILES['filename']['tmp_name'], "../files/forum/" . $files . "");
					dbquery("INSERT INTO `forum_file` SET `post_id` = '0', `name_file` = '" . $files . "'");
					$f_id = getLastInstId();
				}
			}


			dbquery("INSERT INTO `forum_post` SET `kat` = '" . $forum_t['kat'] . "',`text_col` = '" . nickLink($us['id']) . ", " . $text . "',`us` = '" . $user['id'] . "',`time_up` = '" . time() . "',`tema` = '" . $forum_t['id'] . "'");
			if ($user['form_file'] == 1) {
				$p_id = getLastInstId();
				dbquery("UPDATE `forum_file` SET `post_id` = '" . $p_id . "' WHERE `id` = '" . $f_id . "' LIMIT 1");
			}
			$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
			if ($settings['forum_post_m'] > 0) {
			dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] + $settings['forum_post_m']) . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
			dbquery("INSERT INTO `transactions` SET `amount` =  '" . $settings['forum_post_m'] . "', `type_col` = '2', `timestamp_col` = '" . time() . "', `from_us` = '2', `to_us` = '" . $user['id'] . "', `status` = '1'");
			}
			dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $forum_p['us'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'ответил на ваш пост[url=" . homeLink() . "/tema" . $forum_t['id'] . "?selection=top] в теме[/url]'");
			header('Location: /tema' . $forum_t['id'] . '?selection=top');
			exit();
		}

		echo '<div class="title" style="border-bottom: 0px;">Ответ для: ' . nick($forum_p['us']) . '</div>';

		require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/reg-else-text.php');
		break;


	case 'post_red':
		$id = abs(intval($_GET['id']));
		$forum_p = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $id . "'"));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $forum_p['tema'] . "'"));
		$count = msres(dbquery("SELECT COUNT(`id`) FROM `forum_file` WHERE `post_id` = '" . $id . "'"), 0);

		if ($forum_p == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такого поста не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($user['id'] != $forum_p['us'] && $perm['edit_post'] < 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">У вас не достаточно прав для просмотра данной страницы</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t['status'] == 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Тема закрыта</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="menu_nb"><span class="kat_name"><h1>Редактирование поста</h1><h2 class="kat_opisanie">Пост пользователя ' . nick($forum_p['us']) . '</h2></span></div>';

		if (isset($_REQUEST['submit'])) {

			$text = LFS($_POST['msg']);

			if (mb_strlen($text, 'UTF-8') < 3) $err = 'Минимум для ввода 3 символа!';
			if (empty($text)) $err = 'Введите текст сообщения!';

			if ($err) {
				echo '<div class="menu_cont">';
				echo '<div class="err">' . $err . '</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("UPDATE `forum_post` SET `text_col` = '" . $text . "' WHERE `id` = '" . $id . "'");
			header('Location: /tema' . $forum_t['id'] . '?selection=top');
			exit();
		}

		echo '<div class="menu_nb">
		<form action="" name="message" method="POST"> ';
		echo '<center><textarea style="height:200px;" name="msg">' . $forum_p['text_col'] . '</textarea></center><br />
		<center><input type="submit" name="submit" value="Изменить" style="width: 175px; margin-top: 15px; margin-bottom: 0px; border-bottom: 0px;"/></center>
		</form></div>';

		echo '<div class="title">Файлы [' . $count . ']</div>';
		
		if ($count < 1) {
			echo '<div class="menu_nb">Файлов нет</div>';
		} else {
			$ff = dbquery("SELECT * FROM `forum_file` WHERE `post_id`='" . $id . "'");

			while ($a = mfar($ff)) {
				echo '<div class="menu_nb">
				<a href="' . homeLink() . '/files/forum/' . $a['name_file'] . '">' . $a['name_file'] . '</a> | 
				<a href="/forum/delfile' . $id . '/' . $a['id'] . '">Удалить</a></div>';
			}
		}
		
		if ($user['form_file'] == 1) {
			echo '<div class="menu_t">';
			echo '<a class="button" data-bs-toggle="modal" data-bs-target="#load_file">Добавить файл</a>';
			echo '</div>';
			file_upload('forum-document');
		}
		break;


	case 'delfile':
		$id = abs(intval($_GET['id']));
		$id_file = abs(intval($_GET['id_file']));

		$f_post = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $id . "'"));

		if (!isset($f_post['id'])) {
			echo err($title, 'Нет такого файла');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		if ($user['id'] != $f_post['us'] && $user['level_us'] != 3) {
			echo err($title, 'Нет доступа!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $f_post['tema'] . "'"));
		$fil = mfar(dbquery("SELECT * FROM `forum_file` WHERE `id`='" . $id_file . "'"));
		echo '<div class="title">Удаление файла ' . $fil['name_file'] . '</div>';

		if (isset($_REQUEST['da'])) {

			unlink('../files/forum/' . $fil['name_file']);

			dbquery("DELETE FROM `forum_file` WHERE `id` = '" . $id_file . "' LIMIT 1");

			header('Location: ' . homeLink() . '/forum/post_red' . $id);
			exit;
		}
		echo '<div class="menu_nb">Вы действительно хотите удалить файл ' . $fil['name_file'] . '?</div>';
		echo '<div class="menu_nb">
		<a class="button" href="' . homeLink() . '/forum/delfile' . $id . '/' . $id_file . '?da">Удалить</a> <a class="button" href="' . htmlspecialchars(getenv("HTTP_REFERER")) . '">Отмена</a>
		</div>';
		break;

		##############################
		############ Цитаты ##########
		##############################
	case 'post_citata':
		echo '<meta name="robots" content="noindex">';

		$id = abs(intval($_GET['id']));
		$forum_p = mfa(dbquery("SELECT * FROM `forum_post` WHERE `id` = '" . $id . "'"));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $forum_p['tema'] . "'"));
		$us = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $forum_p['us'] . "'"));

		if (empty($user['id'])) {
			header('Location: ' . homeLink() . '');
			exit();
		}

		if ($forum_p == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такого поста не существует</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($forum_t['status'] == 1) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Тема закрыта</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		if ($user['id'] == $forum_p['us']) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="err">Цитировать самого себя нельзя</div>';
			echo '</div>';
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="menu_nb"><a href="' . homeLink() . '/forum/">' . $title . '</a> | <a href="' . homeLink() . '/tema' . $forum_t['id'] . '">Тема ' . $forum_t['name'] . '</a> </div>';

		if (isset($_REQUEST['submit'])) {

			$text = LFS($_POST['msg']);

			if (empty($text)) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Введите текст сообщения</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (mb_strlen($text, 'UTF-8') < 3) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Минимум для ввода 3 символа</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			if (empty($user['id'])) {
				echo '<div class="menu_cont">';
				echo '<div class="err">Авторизуйтесь чтобы ответить</div>';
				echo '</div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			$time = dbquery("SELECT * FROM `forum_post` WHERE `us`='" . $user['id'] . "' ORDER BY `time_up` DESC");
			while ($t = mfa($time)) {
				$forum_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `forum_post` "));
				$timeout = $t['time_up'];
				if ((time() - $timeout) < $forum_antispam['forum_post']) {
					echo '<div class="menu_cont">';
					echo '<div class="err">Пишите не чаще чем раз в ' . $forum_antispam['forum_post'] . ' секунд</div>';
					echo '</div>';
					require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
					exit();
				}
			}

			if ($user['form_file'] == 1) {
				$maxsize = 2048; // Максимальный размер файла,в мегабайтах 
				$size = $_FILES['filename']['size']; // Вес файла

				if (@file_exists($_FILES['filename']['tmp_name'])) {

					if ($size > (1048576 * $maxsize)) {
						echo err($title, 'Максимальный размер файла ' . $maxsize . 'мб!');
						require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
						exit;
					}

					$filetype = array('jpg', 'gif', 'png', 'jpeg', 'bmp', 'zip', 'rar', 'mp4', 'mp3', 'amr', '3gp', 'avi', 'flv', 'jar', 'jad', 'apk', 'sis', 'sisx', 'ipa');
					$upfiletype = substr($_FILES['filename']['name'],  strrpos($_FILES['filename']['name'], ".") + 1);

					if (!in_array($upfiletype, $filetype)) {
						echo err($title, 'Такой формат запрещено загружать!');
						require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
						exit;
					}

					$files = $_SERVER['HTTP_HOST'] . '_' . rand(1234, 5678) . '_' . rand(1234, 5678) . '_' . $_FILES['filename']['name'];
					move_uploaded_file($_FILES['filename']['tmp_name'], "../files/forum/" . $files . "");
					dbquery("INSERT INTO `forum_file` SET `post_id` = '0', `name_file` = '" . $files . "'");
					$f_id = getLastInstId();
				}
			}

			dbquery("INSERT INTO `forum_post` SET `citata` = '" . $forum_p['text_col'] . "...',`citata_us` = '" . $forum_p['us'] . "',`kat` = '" . $forum_t['kat'] . "',`text_col` = '" . $text . "',`us` = '" . $user['id'] . "',`time_up` = '" . time() . "',`tema` = '" . $forum_t['id'] . "',`razdel` = '" . $forum_t['razdel'] . "'");
			if ($user['form_file'] == 1) {
				$p_id = getLastInstId();
				dbquery("UPDATE `forum_file` SET `post_id` = '" . $p_id . "' WHERE `id` = '" . $f_id . "' LIMIT 1");
			}

			$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
			if ($settings['forum_post_m'] > 0) {
			dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] + $settings['forum_post_m']) . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
			dbquery("INSERT INTO `transactions` SET `amount` =  '" . $settings['forum_post_m'] . "', `type_col` = '2', `timestamp_col` = '" . time() . "', `from_us` = '2', `to_us` = '" . $user['id'] . "', `status` = '1'");
			}
			dbquery("UPDATE `forum_tema` SET `up` = '" . time() . "' WHERE `id` = '" . $forum_t['id'] . "'");
			dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $us['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'Процитировал ваш пост[url=" . homeLink() . "/tema" . $forum_t['id'] . "?selection=top] в теме[/url]'");
			header('Location: /tema' . $forum_t['id'] . '?selection=top');
			exit();
		}

		echo '<div class="menu_nb">
		<div class="cit">' . nick($us['id']) . ': ' . nl2br(smile(bb($forum_p['text_col']))) . '</div>
		</div>';

		require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/reg-else-text.php');
		break;

		##############################
		####### Кто на форуме ########
		##############################
	case 'who_forum':

		echo '<div class="title">Кто на форуме</div>';

		$gde = '/forum';
		$who = dbquery('SELECT * FROM `users` WHERE `gde` LIKE "%' . $gde . '%" and `viz` > "' . (time() - 60) . '" ORDER BY `viz` DESC');
		
		while ($ank = mfa($who)) {
			echo '<table class="menu" cellspacing="0" cellpadding="0">';
			echo '<td style="width: 75px;">';
			$perm_ank = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $ank['level_us'] . "'"));
			$prv_us_t = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $ank['prev'] . "'"));
			echo UserAvatar($ank, $width, $height);
			echo '</td>';
			echo '<td class="block_content">';
			echo '' . nick($ank['id']) . ' <span class="time">' . vremja($ank['viz']) . '</span></br>';
			echo '<div class="block_msg">На форуме</div>';
			echo '</td>';
			echo '</table>';
		}

		break;
		##############################
		######### Кто в теме #########
		##############################
	case 'who_tema':

		$id = abs(intval($_GET['id']));
		$tema = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$gde = '/tema' . $tema['id'] . '';

		if ($tema == 0) {
			echo '<div class="menu_nb">Форум</div>';
			echo '<div class="menu_cont">';
			echo '<div class="err">Такой темы не существует</div>';
			echo '</div>';
			require_once ('' . $_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		echo '<div class="title">Кто в теме</div>';

		$who = dbquery('SELECT * FROM `users` WHERE `gde` LIKE "%' . $gde . '%" and `viz` > "' . (time() - 60) . '" ORDER BY `viz` DESC');
		while ($ank = mfa($who)) {
			echo '<div class="menu_nb">' . nick($ank['id']) . ' (' . vremja($ank['viz']) . ')</div>';
		}

		echo '<div class="links">» <a href="' . homeLink() . '/tema' . $tema['id'] . '">Назад</a></div>';

		break;
		##############################
		########### Мои темы #########
		##############################
	case 'my_tem':

		echo '<div class="title">Мои темы</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '" . $user['id'] . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$tema = dbquery("SELECT * FROM `forum_tema` WHERE `us` = '" . $user['id'] . "' ORDER BY `id` DESC LIMIT $start, $max");

		while ($a = mfa($tema)) {
			if ($a['status'] == 0) {
				$icon = 'tem';
			} else {
				$icon = 'close';
			}
			echo '<div class="menu_nb"><img src="/images/' . $icon . '.png" alt="*"> <a href="/tema' . $a['id'] . '">' . $a['name'] . '</a> (' . msres(dbquery('select count(`id`) from `forum_post` where `tema` = "' . $a['id'] . '"'), 0) . ') <a href="/tema' . $a['id'] . '?selection=top">>></a></div>';
		}

		if ($k_post < 1) echo '<div class="menu_nb">Вы еще не создавали тем</div>';
		if ($k_page > 1) echo str('' . homeLink() . '/forum/myt' . $id . '?', $k_page, $page); // Вывод страниц

		break;
	case 'my_post':

		echo '<div class="title"><a href="' . homeLink() . '/forum/">Мои посты</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '" . $user['id'] . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$post = dbquery("SELECT * FROM `forum_post` WHERE `us` = '" . $user['id'] . "' ORDER BY `id` DESC LIMIT $start, $max");

		while ($a = mfa($post)) {
			echo '<div class="links">' . nick($a['us']) . ' (' . vremja($a['time_up']) . ')</div>
			<div class="menu_nb">' . nl2br(smile(bb($a['text_col']))) . '
			<br />
			<a href="/tema' . $a['tema'] . '?selection=top" style="background-color: #228e5d;">Перейти в тему</a>
			</div>';
		}

		if ($k_post < 1) echo '<div class="menu_nb">Вы еще не оставляли постов</div>';
		if ($k_page > 1) echo str('' . homeLink() . '/forum/myp' . $id . '?', $k_page, $page); // Вывод страниц

		break;
	case 'new_tem':

		echo '<title>Новые темы</title>';

		echo '<div class="title">Новые темы</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `time_up` > '" . (time() - 86400) . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$tem = dbquery("SELECT * FROM `forum_tema` WHERE `time_up` > '" . (time() - 86400) . "' ORDER BY `id` DESC LIMIT $start, $max");

		while ($a = mfa($tem)) {
			require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/div-link-thems-info.php');
		}

		if ($k_post < 1) echo '<div class="menu_nb">За 24 часа новых тем нету</div>';
		if ($k_page > 1) echo str('' . homeLink() . '/forum/newt?', $k_page, $page); // Вывод страниц

		break;
	case 'new_post':

		echo '<title>Новые посты -  ' . $LF_info . ' - ' . $LF_name . '</title>';
		echo '<div class="title">Новые посты</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `time_up` > '" . (time() - 86400) . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$post = dbquery("SELECT * FROM `forum_post` WHERE `time_up` > '" . (time() - 86400) . "' ORDER BY `id` DESC LIMIT $start, $max");

		while ($a = mfa($post)) {
			echo '<div class="post_block">';
			echo '<table class="menu" cellspacing="0" cellpadding="0" style="background: none; padding: 15px;">';

			echo '<td class="block_avatar" style="margin: 0px;">';
			$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us'] . "'"));
			echo UserAvatar($ank, $width, $height);

			echo '</td>';
			echo '<td class="block_content" style="margin: 0px;">';
			echo nick($a['us']);
			echo $prus->displayUserIcon($ank);

			echo '<div class="vremja_post">' . vremja($a['time_up']) . '</div><br />';

			if (!$a['citata'] == NULL) echo '<div class="cit">' . nick($a['citata_us']) . ': ' . nl2br(smile(bb($a['citata']))) . '</div>';

			echo '<div class="block_msg" id="text">' . nl2br(smile(bb($a['text_col']))) . '</div>';
			
			$count = msres(dbquery("SELECT COUNT(`id`) FROM `forum_file` WHERE `post_id` = '" . $a['id'] . "'"), 0);
			if ($count) {
				$load_s = dbquery("SELECT * FROM `forum_file` WHERE `post_id`='" . $a['id'] . "'");
				while ($a = mfar($load_s)) {
					echo '<a class="block_file" href="' . homeLink() . '/files/forum/' . $a['name_file'] . '"><i class="fas fa-file" style="margin-right: 5px;"></i><span class="file_name">' . $a['name_file'] . '</span><br /><code>' . fsize('../files/forum/' . $a['name_file']) . '</code></a>';
				}
			}

			$forum_like = mfa(dbquery("SELECT * FROM `forum_like` WHERE `post` = '" . $a['id'] . "' and `us` = '" . $user['id'] . "' "));
			$result_like = dbquery("SELECT count(*) as `total` from `forum_like` where `post` = '" . $a['id'] . "'");
			$data_like = mfa($result_like);

			echo '<span class="action_teme_us" style="margin: 0;"><a class="button" href="/tema' . $a['tema'] . '?selection=top">Перейти в тему</a></span>';

			echo '</td>';
			echo '</table>';
			echo '</div>';
		}

		if ($k_post < 1) echo '<div class="menu_nb">За 24 часа новых постов нету</div>';
		if ($k_page > 1) echo str('' . homeLink() . '/forum/newp' . $id . '?', $k_page, $page); // Вывод страниц

		break;

		######################
		###### Закладки ######
		######################

	case 'my_zakl':

		if (empty($user['id'])) {
			echo err($title, 'Уважаемый посетитель, Вы зашли на сайт как незарегистрированный пользователь.<br/>Мы рекомендуем Вам зарегистрироваться либо войти на сайт под своим именем.');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		echo '<title>Закладки -  ' . $LF_info . ' - ' . $LF_name . '</title>';
		echo '<meta name="robots" content="noindex">';
		echo '<meta name="description" content="Ваши темы в закладках">';
		echo '<div class="title">Мои закладки</div>';

		if (empty($user['max_us'])) $user['max_us'] = 10;
		$max = $user['max_us'];
		$k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_zaklad` WHERE `us` = '" . $user['id'] . "' "), 0);
		$k_page = k_page($k_post, $max);
		$page = page($k_page);
		$start = $max * $page - $max;
		$z = dbquery("SELECT * FROM `forum_zaklad` WHERE `us` = '" . $user['id'] . "' ORDER BY `id` DESC LIMIT $start, $max");

		while ($a = mfa($z)) {
			$a = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $a['tema'] . "'"));

			if ($a['status'] == 0) {
				$icon = 'tem';
			} else {
				$icon = 'close';
			}

			echo '<div class="zakl_link_tem">';

			require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/div-link-thems-info.php');

			echo '<a href="/tema' . $a['id'] . '?selection=top"></a>';
			echo '</div>';
		}

		if ($k_post < 1) echo '<div class="menu_nb"><b><center>У вас нет закладок!</center></b></div>';
		if ($k_page > 1) echo str('' . homeLink() . '/forum/zakl' . $id . '?', $k_page, $page); // Вывод страниц

		break;

		##############################
		###### Переместить тему ######
		##############################

	case 'move':
		$id = abs(intval($_GET['id']));

		if ($perm['move_them'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$forum_r = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));

		if ($forum_r == 0) {
			echo err($title, 'Такой темы не существует!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$r2 = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_r['razdel'] . "'"));
		$k2 = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $forum_r['kat'] . "'"));

		echo '<title>Перемещение темы</title>';

		echo '<div class="title">Перемещение темы: <a href="' . homeLink() . '/tema' . $forum_r['id'] . '">' . $forum_r['name'] . '</a></div>';
		echo '<div class="menu_nbr">Переместить тему из <b><a href="' . homeLink() . '/razdel' . $r2['id'] . '">' . $r2['name'] . '</a></b> / <b><a href="' . homeLink() . '/kat' . $k2['id'] . '">' . $k2['name'] . '</a></b> в:</div>';

		$forum_r = dbquery("SELECT * FROM `forum_razdel` ORDER BY `id` DESC");

		while ($a = mfa($forum_r)) {
			echo '<a class="link_razdel" href="' . homeLink() . '/razdel' . $a['id'] . '" style="margin-top: 15px;">' . $a['name'] . '</a>';
			$forum_k = dbquery("SELECT * FROM `forum_kat` WHERE `razdel` = '" . $a['id'] . "' ORDER BY `id` DESC");

			while ($a = mfa($forum_k)) {
				echo '<a class="link" href="' . homeLink() . '/forum/index.php?act=move_tem&id=' . $id . '&kat=' . $a['id'] . '&razdel=' . $a['razdel'] . '"><span class="icon" style="color: #999; font-size: 14px; margin-right: 10px;"><i class="far fa-comments"></i></span>' . $a['name'] . '</a>';
			}
		}
		break;
		
	case 'move_tem':
		$id = abs(intval($_GET['id']));
		$razdel = abs(intval($_GET['razdel']));
		$kat = abs(intval($_GET['kat']));

		if ($perm['move_them'] < 1) {
			echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		$forum_r = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $razdel . "'"));
		$k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $kat . "'"));


		if ($forum_r == 0) {
			echo err($title, 'Такой темы не существует!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		if ($r == 0) {
			echo err($title, 'Такого раздела не существует!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}

		if ($k == 0) {
			echo err($title, 'Такая категория не существует!');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit;
		}


		$r2 = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $forum_r['razdel'] . "'"));
		$k2 = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $forum_r['kat'] . "'"));

		echo '<div class="title">Перемещение темы: <a href="' . homeLink() . '/tema' . $forum_r['id'] . '">' . $forum_r['name'] . '</a></div>';

		if (isset($_REQUEST['okda'])) {
			/* Делаем запрос */
			dbquery("UPDATE `forum_tema` SET `razdel` = '" . $razdel . "', `kat` = '" . $kat . "' WHERE `id` = '" . $id . "'");

			dbquery("INSERT INTO `forum_post` SET `kat` = '" . $kat . "',`text_col` = 'Перенес тему из [b]" . $r2['name'] . "[/b] / [b]" . $k2['name'] . "[/b] в [b]" . $r['name'] . "[/b] / [b]" . $k['name'] . "[/b] :)',`us` = '" . $user['id'] . "',`time_up` = '" . time() . "',`tema` = '" . $id . "',`razdel` = '" . $razdel . "'");

			header('Location: ' . homeLink() . '/tema' . $id . '');
			exit;
		}

		echo '<div class="menu_nb">Вы действительно хотите переместить тему из <b><a href="' . homeLink() . '/razdel' . $r2['id'] . '">' . $r2['name'] . '</a></b> / <b><a href="' . homeLink() . '/kat' . $k2['id'] . '">' . $k2['name'] . '</a></b> в <b><a href="' . homeLink() . '/razdel' . $r['id'] . '">' . $r['name'] . '</a></b> / <b><a href="' . homeLink() . '/kat' . $k['id'] . '">' . $k['name'] . '</a></b><br /><br />
		<a class="button" href="' . homeLink() . '/forum/index.php?act=move_tem&id=' . $id . '&kat=' . $kat . '&razdel=' . $razdel . '&okda" style="margin-right: 5px;">Переместить</a><a class="button" href="' . htmlspecialchars(getenv("HTTP_REFERER")) . '">Отмена</a></div>';
		break;
}

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>