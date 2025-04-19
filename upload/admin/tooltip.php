<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:
        echo '<title>Редактировать ключевые слова</title>';
        echo '<div class="title">Редактировать ключевые слова</div>';
        $tooltips = dbquery("SELECT * FROM `forum_tooltips` ORDER BY `id` ASC");
        while ($tt = mfa($tooltips)) {
            echo '<div class="menu_nbr">' . $tt['definition'] . '';
            if ($perm['edit_tooltip'] > 0) {
                echo '<span class="but-right" style="float: right;"><a class="link-btn-group" href="' . homeLink() . '/admin/tooltips/edit-tooltip' . $tt['id'] . '">Изменить </a></span>';
            }
            echo '</div>';
        }
		echo '<div class="menu_cont">';
        echo '<a class="link" href="' . homeLink() . '/admin/tooltips/new-tooltip"><span class="icon"><i class="fas fa-plus"></i></span> Добавить ключевое слово</a>';
		echo '</div>';
        break;
    case 'new-tooltip':
        echo '<title>Новое ключевое слово</title>';
        if ($perm['edit_tooltip'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        if (isset($_REQUEST['settooltip'])) {

            $word = LFS($_POST['word']);
            $tooltip = LFS($_POST['tooltip']);
            $definition = LFS($_POST['definition']);

            dbquery("INSERT INTO `forum_tooltips` SET `word` = '" . $word . "', `tooltip` = '" . $tooltip . "', `definition` = '" . $definition . "'");

            echo '<div class="menu_nbr"><center><b>Ключевое слово добавлено!</b></center></div>';
            echo '<div class="menu_nbr">» <a href="' . homeLink() . 'admin/tooltips/">Вернуться назад</a></div>';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        echo '<div class="title">Новое ключевое слово</div>';
		
        echo '<form action="" method="POST">';
		echo '<div class="menu_nb">';
        echo '<div class="setting_punkt">Понятие:</div><input type="text" name="definition">';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Ключевые слова:</div><textarea name="word" style="height: 200px;" placeholder="text, text, text..."></textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Описание:</div><textarea name="tooltip" style="height: 200px;" placeholder="Описание..."></textarea>';
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
        echo '<input type="submit" name="settooltip" value="Сохранить">';
		echo '</div>';
		echo '</div>';
		
        echo '</form>';

        break;

    case 'edit-tooltip':
        $id = abs(intval($_GET['id']));
        $tooltip = mfa(dbquery("SELECT * FROM `forum_tooltips` WHERE `id` = '" . $id . "'"));

        echo '<title>Редактировать ключевое слово ' . $tooltip['definition'] . '</title>';

        if ($perm['edit_tooltip'] < 1) {
            echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        if (isset($_REQUEST['uptooltip'])) {

            $word = LFS($_POST['word']);
            $tooltip = LFS($_POST['tooltip']);
            $definition = LFS($_POST['definition']);

            dbquery("UPDATE `forum_tooltips` SET `word` = '" . $word . "', `tooltip` = '" . $tooltip . "', `definition` = '" . $definition . "' WHERE `id` = '" . $id . "'");

            showAlert('Изменения успешно сохранены!', 'info', 3000);
        }

        if (isset($_REQUEST['deltooltip'])) {
            dbquery("DELETE FROM `forum_tooltips` WHERE `id` = '" . $id . "'");
            header('Location: ' . homeLink() . '/admin/tooltips');
        }

        echo '<div class="title">Редактировать ключевое слово ' . $tooltip['definition'] . '</div>';
        echo '<form action="" method="POST">';
		echo '<div class="menu_nb">';
        echo '<div class="setting_punkt">Понятие:</div><input type="text" name="definition" value="' . $tooltip['definition'] . '">';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Ключевые слова:</div><textarea name="word" style="height: 200px;" placeholder="text, text, text...">' . $tooltip['word'] . '</textarea>';
		echo '</div>';
		
		echo '<div class="menu_t">';
        echo '<div class="setting_punkt">Описание:</div><textarea name="tooltip" style="height: 200px;" placeholder="Описание...">' . $tooltip['tooltip'] . '</textarea>';
		echo '</div>';
		
		echo '<div class="setting-footer">';
		echo '<div class="menu_t">';
        echo '<input type="submit" name="uptooltip" value="Сохранить">';
        echo '<input type="submit" name="deltooltip" value="Удалить">';
		echo '</div>';
		echo '</div>';
		
        echo '</form>';
        break;
}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
