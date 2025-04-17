<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:
        if ($perm['edit_auth'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }
        echo '<title>Авторизация через соц. сети</title>';
        echo '<div class="title">Авторизация через соц. сети</div>';
		echo '<div class="menu_cont">';
        echo '<a class="link" href="' . homeLink() . '/admin/auth/vk"><span class="icon"><i class="fab fa-vk"></i></span>ВКонтакте</a>';
		echo '</div>';
        break;

    case 'vk':
        if ($perm['edit_auth'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }
        echo '<title>Авторизация через ВКонтакте</title>';
        echo '<div class="title">Авторизация через ВКонтакте</div>';
        $vkauth = dbquery("SELECT * FROM `vkauth` ORDER BY `id` ASC");
        while ($vk = mfa($vkauth)) {
            echo '<div class="menu_nbr">';
            echo '' . $vk['client_id'] . ' ';
            echo ' ' . $vk['client_secret'] . '';
            echo '<span class="but-right" style="float: right;"><a class="link-btn-group" href="' . homeLink() . '/admin/auth/vk/edit-vk' . $vk['id'] . '">Изменить</a></span>';
            echo '</div>';
        }

        $count_vk = mfa(dbquery("SELECT * FROM `vkauth` WHERE `id`"));
        if (!$count_vk['id']) {
			echo '<div class="menu_cont">';
            echo '<a class="link" href="' . homeLink() . '/admin/auth/vk/set-vk"><span class="icon"><i class="fas fa-plus"></i></span>Добавить</a>';
			echo '</div>';
        }
        break;


    case 'set-vk':
        if ($perm['edit_auth'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }
        echo '<title>Авторизация через ВКонтакте</title>';
        echo '<div class="title">Авторизация через ВКонтакте</div>';

        if (isset($_REQUEST['setvk'])) {
            $client_id = LFS($_POST['client_id']);
            $client_sec = LFS($_POST['client_sec']);

            dbquery("INSERT INTO `vkauth` SET `client_id` = '" . $client_id . "', `client_secret` = '" . $client_sec . "'");
            header('Location: ' . homeLink() . '/admin/auth/vk');
        }

        echo '<div class="menu_nb">Ваш Redirect_url: ' . homeLink() . '/auth/redvk.php</div>';
        echo '<form action="" method="POST">
		
		<div class="menu_t">
		<div class="setting_punkt">ID:</div><input type="text" name="client_id">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Client secret:</div><input type="text" name="client_sec">
		</div>
		
		<div class="setting-footer"><div class="menu_t">
		<input type="submit" name="setvk" value="Сохранить">
		</div></div>
		
		</form>';
        break;


    case 'edit-vk':
        if ($perm['edit_auth'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }
        $id = abs(intval($_GET['id']));
        $vkauth = mfa(dbquery("SELECT * FROM `vkauth` WHERE `id` = '" . $id . "'"));
        echo '<title>Авторизация через ВКонтакте</title>';
        echo '<div class="title">Авторизация через ВКонтакте</div>';

        if (isset($_REQUEST['setvk'])) {
            $client_id = LFS($_POST['client_id']);
            $client_sec = LFS($_POST['client_sec']);

            dbquery("UPDATE `vkauth` SET `client_id` = '" . $client_id . "', `client_secret` = '" . $client_sec . "' WHERE `id` = '" . $id . "'");
            header('Location: ' . homeLink() . '/admin/auth/vk');
        }

        if (isset($_REQUEST['delvk'])) {
            dbquery("DELETE FROM `vkauth` WHERE `id` = '" . $id . "'");
            header('Location: ' . homeLink() . '/admin/auth/vk');
        }

        echo '<div class="menu_nb">Ваш Redirect_url: ' . homeLink() . '/auth/redvk.php</div>';
        echo '<form action="" method="POST">
		
		<div class="menu_t">
		<div class="setting_punkt">ID:</div><input type="text" name="client_id" value="' . $vkauth['client_id'] . '" />
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Client secret:</div><input type="text" name="client_sec" value="' . $vkauth['client_secret'] . '" />
		</div>
		
		<div class="setting-footer"><div class="menu_t">
		<input type="submit" name="setvk" value="Сохранить" />
		<input type="submit" name="delvk" value="Удалить" />
		</div></div>
		
		</form>';
        break;
}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
