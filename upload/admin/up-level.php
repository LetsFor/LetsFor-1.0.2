<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:
		if ($perm['set_prev'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }
		
		echo '<title>Повышение прав</title>';
		echo '<div class="title">Повышение прав</div>';
		
		echo '<div class="preves">';
		
		$prev = dbquery("SELECT * FROM `user_prevs` ORDER BY `cena_prev`");
		
		while ($prv = mfa($prev)) {
			echo '<div class="menu_nbr" style="font-weight: bold;">';
			echo '<div class="text_pod_but" style="font-size: 20px; margin-bottom: 10px; display: inline-block;">' . $prv['name'] . '</div><div class="cena" style="float: right; display: inline-block; font-size: 20px;">' . $prv['cena_prev'] . ' ₽</div>';
			echo '<div class="min_text_pod_but" style="margin-bottom: 10px;"><span class="pev_text_title">Преимушества:</span><br />';
			echo '<span class="pev_text">';
			require ($_SERVER['DOCUMENT_ROOT'] . '/user/us/upgrade/groups.php');
			echo '</span>';
			echo '</div>';
			
			if ($perm['set_prev'] < 1) {
				echo '<span></span>';
			} else {
				echo '<a class="button" href="' . homeLink() . '/admin/up-level/edit-upgrade' . $prv['id'] . '">Редактировать</a>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
		
		if ($perm['set_prev'] < 1) {
			echo '<span></span>';
		} else {
			echo '<div class="menu_cont">';
			echo '<a class="link" href="' . homeLink() . '/admin/up-level/new-upgrade"><span class="icon"><i class="fas fa-plus"></i></span> Создать повышение</a>';
			echo '</div>';
		}
		
	break;
	case 'new-prev':

        if ($perm['set_prev'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        if (isset($_REQUEST['saveprev'])) {
            $prev_name = LFS($_POST['prev_name']);
            $free_set_name = LFS($_POST['free_set_name']);
            $free_set_des_name = LFS($_POST['free_set_des_name']);
            $set_gif_ava = LFS($_POST['set_gif_ava']);
            $set_background_user = LFS($_POST['set_background_user']);
            $set_background_head_them = LFS($_POST['set_background_head_them']);
            $color_them_title = LFS($_POST['color_them_title']);
            $style_them_title = LFS($_POST['style_them_title']);
            $icon_prev = LFS($_POST['icon_prev']);
            $color_icon_prev = LFS($_POST['color_icon_prev']);
            $set_inter_color = LFS($_POST['set_intercolor_user']);
            $set_new_icon = LFS($_POST['set_new_icon']);
            $set_new_color = LFS($_POST['set_new_color']);
            $cena_prev = LFS($_POST['cena_prev']);


            dbquery("INSERT INTO `user_prevs` SET `name` = '" . $prev_name . "', `free_set_name` = '" . $free_set_name . "', `free_set_des_name` = '" . $free_set_des_name . "', `set_gif_ava` = '" . $set_gif_ava . "', `set_background_user` = '" . $set_background_user . "', `set_background_head_them` = '" . $set_background_head_them . "', `color_them_title` = '" . $color_them_title . "', `style_them_title` = '" . $style_them_title . "', `icon_prev` = '" . $icon_prev . "', `color_icon_prev` = '" . $color_icon_prev . "', `set_intercolor_user` = '" . $set_inter_color . "', `set_new_icon` = '" . $set_new_icon . "', `set_new_color` = '" . $set_new_color . "', `cena_prev` = '" . $cena_prev . "'");

            header('Location: ' . homeLink() . '/admin/up-level');
            exit();
        }

        echo '<title>Новое повышение</title>';
        echo '<div class="title">Новое повышение</div>';
        echo '<form action="" method="POST">';
		
		echo '<div class="menu_nb">';
        echo '<div class="setting_punkt">Название повышения:</div><input type="text" name="prev_name">';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('free_set_name', 'Бесплатная смена никнейма:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('free_set_des_name', 'Бесплатная смена дизайна никнейма:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('set_gif_ava', 'Возможность установить GIF аватар:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('set_background_user', 'Возможность установить фон профиля:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('set_intercolor_user', 'Возможность менять акцент профиля:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('set_background_head_them', 'Возможность установить фон шапки темы:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('color_them_title', 'Выделение темы на главной:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('set_new_icon', 'Возможность менять иконку повышения:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->createSelectField('set_new_color', 'Возможность менять цвет лычки:');
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Цвет выделения темы на главной:</div><textarea type="text" name="style_them_title" placeholder="css..." style="height: 100px;"></textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Иконка возле никнейма:</div><textarea type="text" name="icon_prev" placeholder="<svg..." style="height: 100px;">' . $prv['icon_prev'] . '</textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Стиль лычки:</div><textarea type="text" name="color_icon_prev" placeholder="css..." style="height: 100px;"></textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Цена повышения:</div><input type="text" name="cena_prev">';
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
        echo '<input type="submit" name="saveprev" value="Создать" />';
		echo '</div>';
		echo '</div>';
		
        echo '</form>';
		
        break;
    case 'up-prev':
	
        $id = abs(intval($_GET['id']));
        $prv = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $id . "'"));

        if ($perm['set_prev'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        if (isset($_REQUEST['upprev'])) {
            $prev_name = LFS($_POST['prev_name']);
            $free_set_name = LFS($_POST['free_set_name']);
            $free_set_des_name = LFS($_POST['free_set_des_name']);
            $set_gif_ava = LFS($_POST['set_gif_ava']);
            $set_background_user = LFS($_POST['set_background_user']);
            $set_background_head_them = LFS($_POST['set_background_head_them']);
            $color_them_title = LFS($_POST['color_them_title']);
            $style_them_title = LFS($_POST['style_them_title']);
            $icon_prev = LFS($_POST['icon_prev']);
            $color_icon_prev = LFS($_POST['color_icon_prev']);
            $set_inter_color = LFS($_POST['set_intercolor_user']);
            $set_new_icon = LFS($_POST['set_new_icon']);
            $set_new_color = LFS($_POST['set_new_color']);
            $cena_prev = LFS($_POST['cena_prev']);


            dbquery("UPDATE `user_prevs` SET `name` = '" . $prev_name . "', `free_set_name` = '" . $free_set_name . "', `free_set_des_name` = '" . $free_set_des_name . "', `set_gif_ava` = '" . $set_gif_ava . "', `set_background_user` = '" . $set_background_user . "', `set_background_head_them` = '" . $set_background_head_them . "', `color_them_title` = '" . $color_them_title . "', `style_them_title` = '" . $style_them_title . "', `icon_prev` = '" . $icon_prev . "', `color_icon_prev` = '" . $color_icon_prev . "', `set_intercolor_user` = '" . $set_inter_color . "', `set_new_icon` = '" . $set_new_icon . "', `set_new_color` = '" . $set_new_color . "', `cena_prev` = '" . $cena_prev . "' WHERE `id` = '" . $id . "' LIMIT 1");

            showAlert('Повышение успешно отредактировано!', 'info', 3000);
        }

        if (isset($_REQUEST['delprev'])) {
            dbquery("DELETE FROM `user_prevs` WHERE `id` = '" . $id . "'");
            header('Location: ' . homeLink() . '/admin/up-level');
            exit();
        }
		
		echo '<title>Настройки повышения</title>';
        echo '<div class="title">Настройки повышения</div>';
        echo '<form action="" method="POST">';
		
		echo '<div class="menu_nb">';
        echo '<div class="setting_punkt">Название повышения:</div><input type="text" name="prev_name" value="' . $prv['name'] . '">';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('free_set_name', 'Бесплатная смена никнейма:', $prv['free_set_name']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('free_set_des_name', 'Бесплатная смена дизайна никнейма:', $prv['free_set_des_name']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('set_gif_ava', 'Возможность установить GIF аватар:', $prv['set_gif_ava']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('set_background_user', 'Возможность установить фон профиля:', $prv['set_background_user']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('set_intercolor_user', 'Возможность менять акцент профиля:', $prv['set_intercolor_user']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('set_background_head_them', 'Возможность установить фон шапки темы:', $prv['set_background_head_them']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('color_them_title', 'Выделение темы на главной:', $prv['color_them_title']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('set_new_icon', 'Возможность менять иконку повышения:', $prv['set_new_icon']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo $select->updateSelectField('set_new_color', 'Возможность менять цвет лычки:', $prv['set_new_color']);
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Цвет выделения темы на главной:</div><textarea type="text" name="style_them_title" placeholder="css..." style="height: 100px;">' . $prv['style_them_title'] . '</textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Иконка возле никнейма:</div><textarea type="text" name="icon_prev" placeholder="<svg..." style="height: 100px;"">' . $prv['icon_prev'] . '</textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Стиль лычки:</div><textarea type="text" name="color_icon_prev" placeholder="css..." style="height: 100px;">' . $prv['color_icon_prev'] . '</textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Цена повышения:</div><input type="text" name="cena_prev" value="' . $prv['cena_prev'] . '">';
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
        echo '<input type="submit" name="upprev" value="Сохранить">';
        echo '<input type="submit" name="delprev" value="Удалить">';
		echo '</div>';
		echo '</div>';
		
        echo '</form>';
        break;
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>