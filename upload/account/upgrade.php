<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

echo '<title>Редактирование повышения</title>';
if (empty($user['id'])) exit(header('Location: ' . homeLink()));

if ($prv_us['set_new_icon'] == 1 | $prv_us['set_new_color'] == 1) {

    if (isset($_REQUEST['upgrade'])) {
        $icon_prev = LFS($_POST['icon_prev']);
        $color_prev = LFS($_POST['color_prev']);

        dbquery("UPDATE `users` SET `icon_prev` = '" . $icon_prev . "', `color_prev` = '" . $color_prev . "' WHERE `id` = '" . $user['id'] . "'");
        setcookie(time() + 60 * 60 * 24 * 14);
        showAlert('Настройки успешно сохранены!', 'info', 3000);
    }

    echo '<div class="title">Редактировать повышение</div>';
    echo '<form action="" method="POST">';
    if ($prv_us['set_new_icon'] == 1) {
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Иконка повышения:</div><textarea type="text" name="icon_prev" placeholder="<svg..." style="height: 100px;"">' . $user['icon_prev'] . '</textarea>';
		echo '</div>';
    }

    if ($prv_us['set_new_color'] == 1) {
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Стиль лычки:</div><textarea type="text" name="color_prev" placeholder="css..." style="height: 100px;">' . $user['color_prev'] . '</textarea>';
		echo '</div>';
    }
	
	echo '<div class="setting-footer">';
	echo '<div class="menu_t">';
    echo '<input type="submit" name="upgrade" value="Сохранить">';
	echo '</div>';
	echo '</div>';

    echo '</form>';
} else {
    exit(header('Location: ' . homeLink()));
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
