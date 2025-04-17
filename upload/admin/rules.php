<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:

        if ($perm['edit_rules'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }
        echo '<title>Редактировать правила форума</title>';
        echo '<div class="title">Редактировать правила форума</div>';
        $rules = dbquery("SELECT * FROM `rules` ORDER BY `id` ASC");
        while ($rul = mfa($rules)) {
            echo '<div class="menu_nbr">' . $rul['kat'] . '';
            if ($perm['edit_rules'] > 0) {
                echo '<span class="but-right" style="float: right;"><a class="link-btn-group" href="' . homeLink() . '/admin/rules/edit-rules' . $rul['id'] . '">Изменить </a></span>';
            }
            echo '</div>';
        }
		echo '<div class="menu_cont">';
        echo '<a class="link" href="' . homeLink() . '/admin/rules/new-rules"><span class="icon"><i class="fas fa-plus"></i></span> Добавить правило</a>';
		echo '</div>';
        break;


    case 'new-rules':
        if ($perm['edit_rules'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        echo '<title>Добавление правил</title>';
        echo '<div class="title">Добавление правил</div>';

        if (isset($_REQUEST['setrule'])) {

            $kat = LFS($_POST['kat']);
            $text = LFS($_POST['text']);

            if (empty($text)) {
                echo '<div class="menu_nb"><center><b>Введите правила форума</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            if (empty($kat)) {
                echo '<div class="menu_nb"><center><b>Введите категорию правил</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            dbquery("INSERT INTO `rules` SET `kat` = '" . $kat . "', `text_col` = '" . $text . "'");
            header('Location: ' . homeLink() . '/admin/rules');
        }

        echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Категория правил:</div><input type="text" name="kat">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Правила:</div><textarea name="text" style="height: 200px;"></textarea>
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="setrule" value="Сохранить">
		</div>
		</div>
		
		</form>';
        break;


    case 'edit-rules':
        $id = abs(intval($_GET['id']));
        $rule = mfa(dbquery("SELECT * FROM `rules` WHERE `id` = '" . $id . "'"));

        if ($perm['edit_rules'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        echo '<title>Настройки правил</title>';
        echo '<div class="title">Настройки правил</div>';

        if (isset($_REQUEST['uprule'])) {

            $kat = LFS($_POST['kat']);
            $text = LFS($_POST['text']);

            if (empty($text)) {
                echo '<div class="menu_nb"><center><b>Введите правила форума</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            if (empty($kat)) {
                echo '<div class="menu_nb"><center><b>Введите категорию правил</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            dbquery("UPDATE `rules` SET `kat` = '" . $kat . "', `text_col` = '" . $text . "'");
            showAlert('Изменения успешно сохранены!', 'info', 3000);
        }

        if (isset($_REQUEST['delrule'])) {
            dbquery("DELETE FROM `rules` WHERE `id` = '" . $id . "'");
            header('Location: ' . homeLink() . '/admin/rules');
        }

        echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Категория правил:</div><input name="kat" type="text" value="' . $rule['kat'] . '">
		</div>
		
		<div class="menu_t">
		<div class="setting_punkt">Правила:</div><textarea name="text" style="height: 200px;">' . $rule['text_col'] . '</textarea>
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="uprule" value="Сохранить">
		<input type="submit" name="delrule" value="Удалить">
		</div>
		</div>
		
		</form>';
        break;
}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
