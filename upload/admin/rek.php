<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if ($perm['add_ads'] < 1) {
    exit(header('Location: ' . homeLink()));
}

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:

        echo '<div class="title">Реклама';
		if (empty($user['id'])) {
			echo '<span></span>';
		} else {
			if ($perm['add_ads'] > 0) {
				echo '<div class="btn-group" style="float: right;">
				<a style="position: relative;" class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></a>
				<ul class="dropdown-menu" style="margin: 30px -150px 0; inset: auto;">';
				
				echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/rek/add_ads"><span class="fontawesome_in_menu"><i class="fas fa-plus"></i><span class="text_in_menu">Добавить ссылку</span></span></a></li>';
			}

			echo '</ul></div>';
		}
		echo '</div>';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `ads`"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $qwerty = dbquery("SELECT * FROM `ads` ORDER BY `id` DESC LIMIT $start, $max");

        while ($adlink = mfa($qwerty)) {
            echo '<div class="menu_nbr">';
			if (empty($user['id'])) {
			echo '<span></span>';
			} else {
				if ($perm['add_ads'] > 0) {
					echo '<div class="btn-group" style="float: right;">
					<a style="position: relative;" class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></a>
					<ul class="dropdown-menu" style="margin: 30px -150px 0; inset: auto;">';
					
					echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/rek.php?act=set&id=' . $adlink['id'] . '"><span class="fontawesome_in_menu"><i class="fas fa-edit"></i><span class="text_in_menu">Редактировать</span></span></a></li>';
					echo '<li><a class="dropdown-item" href="' . homeLink() . '/admin/rek.php?act=del&id=' . $adlink['id'] . '" style="color: #ea4c3e;"><span class="fontawesome_in_menu"><i class="fas fa-trash"></i><span class="text_in_menu">Удалить</span></span></a></li>';
					}
					
					echo '</ul></div>';
			}
            echo '<b>Название ссылки: ' . $adlink['name'] . '</b>';
            echo '<br />';
            echo '<b>Ссылка (url): <a class="drag_menu_name" href="' . $adlink['url'] . '">' . $adlink['url'] . '</a></b><br/ >';
            echo '<b>Время добавления: ' . vremja($adlink['kogda']) . '</b><br />';

            if ($adlink['time_srok'] > time()) {
                echo '<b>Истекает: ' . vremja($adlink['time_srok']) . '</b>';
            } else {
                echo '<b>Истекло: ' . vremja($adlink['time_srok']) . '</b>';
            }
            echo '</div>';
        }

        if ($k_post < 1) echo '<div class="menu_nb"><center><b>Пусто!</b></center></div>';
        if ($k_page > 1) echo str('' . homeLink() . '/admin/rek.php', $k_page, $page); // Вывод страниц

        break;
    case 'add_rek':

        echo '<div class="title">Новая реклама</div>';

        if (isset($_REQUEST['submit'])) {

            $name = LFS($_POST['name']);
            $url = LFS($_POST['url']);
            $time_srok = LFS($_POST['time_srok']);

            $times = $time_srok * 86400;
            $time_end = time() + $times;

            if (empty($name)) {
                echo err('Введите название рекламной ссылки!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            if (empty($url)) {
                echo err('Введите ссылку!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            if (empty($time_srok)) {
                echo err('Введите время активности ссылки!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            if (!is_numeric($time_srok)) {
                echo err('Вводить можно только цифры!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            if (mb_strlen($name, 'UTF-8') < 3 or mb_strlen($name, 'UTF-8') > 30) {
                echo err('Введите название рекламной ссылки от 3 до 30 символов!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            $adlink = mfar(dbquery('select * from `ads` where  `name` = "' . $name . '" and `url` = "' . $url . '"'));
            if ($adlink != 0) {
                echo err('Такая рекламная ссылка уже есть!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            dbquery("INSERT INTO `ads` SET `name` = '" . $name . "',`url` = '" . $url . "',`time_srok` = '" . $time_end . "',`kogda` = '" . time() . "'");
            showAlert('Рекламная ссылка добавлена!', 'info', 3000);
        }

        echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Название ссылки:</div><input type="text" name="name" maxlength="30">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Ссылка (с http://):</div><input type="text" name="url" maxlength="25">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Время активности (в днях):</div><input type="text" name="time_srok" maxlength="3">
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="submit" value="Добавить">
		</div>
		</div>
		
		</form>';

        break;
    case 'set':

        $id = abs(intval($_GET['id']));
        $rek = mfa(dbquery("SELECT * FROM `ads` WHERE `id` = '" . $id . "'"));

        if ($rek == 0) {
            header('Location: ' . homeLink() . '/admin/rek.php?act=rek_set');
            exit();
        }

        echo '<div class="title">Редактировать рекламу</div>';

        if (isset($_REQUEST['submit'])) {

            $name = LFS($_POST['name']);
            $url = LFS($_POST['url']);
            $time_srok = LFS($_POST['time_srok']);

            $times = $time_srok * 86400;
            $time_end = time() + $times;

            if (empty($name)) {
                echo '<div class="podmenu"><center><b>Введите название рекламной ссылки!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            if (empty($url)) {
                echo '<div class="podmenu"><center><b>Введите ссылку!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            if (mb_strlen($name) < 3 or mb_strlen($name) > 30) {
                echo '<div class="podmenu"><center><b>Введите название рекламной ссылки от 3 до 30 символов!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }


            dbquery("UPDATE `ads` SET `name` = '" . $name . "', `url` = '" . $url . "', `time_srok` = '" . $time_end . "' WHERE `id` = '" . $id . "'");
            echo '<div class="podmenu"><center><b>Информация обновлена!</b></center></div>';
        }


        $tm = ceil(($rek['time_srok'] - time()) / 86400);

        echo '<div class="menu_nb"><form action="" method="POST">
		Название ссылки: <br /> <input type="text" name="name" value="' . $rek['name'] . '" maxlength="30" /><br />
		Ссылка (url сайта): <br /> <input type="text" name="url" value="' . $rek['url'] . '" maxlength="25" /><br />
		Время активности (в днях): <br /> <input type="text" name="time_srok" value="' . $tm . '" maxlength="3" /><br />
		<input type="submit" name="submit" value="Добавить">
		</form></div>';

        break;
    case 'del':

        $id = abs(intval($_GET['id']));
        $rek = mfa(dbquery("SELECT * FROM `ads` WHERE `id` = '" . $id . "'"));

        if ($rek == 0) {
            header('Location: ' . homeLink() . '/admin/rek.php?act=rek_set');
            exit();
        }

        echo '<div class="title">Удалить рекламную ссылку</div>';

        if (isset($_REQUEST['del'])) {
            dbquery("DELETE FROM `ads` WHERE `id` = '" . $id . "'");
            header('Location: ' . homeLink() . '/admin/rek.php?act=rek_set');
            exit();
        }

        echo '<div class="menu_nb">Вы действительно хотите удалить эту рекламную ссылку?</div>';
		echo '<div class="menu_nb">';
		echo '<a class="button" href="' . homeLink() . '/admin/rek.php?act=del&id=' . $id . '&del" style="margin-right: 5px;">Удалить</a>';
		echo '<a class="button" href="' . homeLink() . '/admin/rek">Отмена</a>';
		echo '</div>';
		echo '</div>';
        break;
}

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
