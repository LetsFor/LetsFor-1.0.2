<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) {
    header('Location: /index.php');
    exit();
}

switch ($_GET['act']) {
    default:

        echo '<div class="title">Смайлы</div>';
		
		echo '<div class="menu_cont">';
        if ($perm['panel'] > 0) {
            echo '<a class="link" href="' . homeLink() . '/admin/smile/addpapka"><span class="icon"><i class="fas fa-plus"></i></span> Новый раздел</a>';
        }

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `smile_p`"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $s_p = dbquery("SELECT * FROM `smile_p` ORDER BY `id` DESC LIMIT $start, $max");
        while ($s = mfa($s_p)) {
            echo '<a class="link" href="' . homeLink() . '/admin/smile/razdel_' . $s['id'] . '"><span class="icon"><i class="far fa-smile"></i></span> ' . $s['name'] . ' <span class="c">' . msres(dbquery("SELECT COUNT(id) FROM `smile` WHERE `papka` = '" . $s['id'] . "'"), 0) . '</span></a>';
        }
		echo '</div>';

        if ($k_post < 1) {
            echo '<div class="menu_nb">Разделов пока нет</div>';
        }

        if ($k_page > 1) {
            echo str('/admin/smile/?', $k_page, $page); // Вывод страниц
        }

        break;
    case 'addpapka':

        if ($perm['panel'] < 1) {
            header('Location: ' . homeLink() . '/admin/smile');
            exit();
        }

        echo '<div class="title">Новый раздел</div>';
		
        echo '<form action="" method="POST">
		<div class="menu_nb">
		<div class="setting_punkt">Название раздела:</div>
		<input type="text" name="name" maxlength="30" placeholder="Название..."/><br />
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" name="ok" value="Создать" />
		</div>
		</div>
		
		</form>';
		
		if (isset($_REQUEST['ok'])) {

            $name = LFS($_POST['name']);

            if (empty($name)) {
                echo '<div class="menu">Введите название папки</div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            if (mb_strlen($name) > 30 or mb_strlen($name) < 3) {
                echo '<div class="menu"><center><b>Введите название раздела от 3-х до 30-ти символов!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            $ttte = mfar(dbquery('select * from `smile_p` where `name` = "' . $name . '"'));
            if ($ttte != 0) {
                echo '<div class="menu_nb"><center><b>Такой раздел уже существует!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            mkdir('' . $_SERVER['DOCUMENT_ROOT'] . '/files/smile/' . $name . '');
            dbquery("INSERT INTO `smile_p` SET `name` = '" . $name . "'");
            showAlert('Раздел успешно создан!', 'info', 3000);
        }

        break;
    case 'razdel':

        $id = abs(intval($_GET['id']));
        $smile = mfa(dbquery("SELECT * FROM `smile_p` WHERE `id` = '" . $id . "'"));

        if ($smile == 0) {
            echo '<div class="title">Смайлы| Ошибка</div>
			<div class="menu"><center><b>Такого раздела не существует!</b></center></div>';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit();
        }

        echo '<div class="title"><a href="' . homeLink() . '/admin/smile.php">Смайлы</a> | ' . $smile['name'] . '';

        echo '<div class="dropdown" style="float: right;">
		<span class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></span>
		<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
        echo ' <li><a class="dropdown-item" href="' . homeLink() . '/admin/smile/delrazdel_' . $smile['id'] . '" style="float: right">Удалить раздел</a></li>';
        echo '</ui>
		</div>';

        echo '</div>';

        if ($perm['panel'] > 0) {
			echo '<div class="menu_cont">';
            echo '<a class="link" href="' . homeLink() . '/admin/smile/newsmile_' . $smile['id'] . '"><span class="icon"><i class="fas fa-plus"></i></span> Добавить смайл</a>';
			echo '</div>';
        }
        $sm = dbquery("SELECT * FROM `smile` WHERE `papka` = '" . $smile['id'] . "' ORDER BY `id` DESC");
        while ($s = mfa($sm)) {

            $sql_papka = "SELECT name FROM smile_p WHERE id = '" . $s['papka'] . "'";
            $result_papka = dbquery($sql_papka);
            $row_papka = mfa($result_papka);

            echo '<div class="menu_t">' . $s['name'] . ' - <img src="' . homeLink() . '/files/smile/' . $row_papka['name'] . '/' . $s['icon'] . '" alt="' . $s['icon'] . '" />';

            echo '<div class="dropdown" style="float: right;">
			<span class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></span>
			<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
            echo ' <li><a class="dropdown-item" href="' . homeLink() . '/admin/smile/delsmile_' . $s['id'] . '" style="float: right">Удалить</a></li>';
            echo '</ui>
			</div>';
            echo '</div>';
        }

        break;
    case 'newsmile':

        $id = abs(intval($_GET['id']));
        $smile = mfa(dbquery("SELECT * FROM `smile_p` WHERE `id` = '" . $id . "'"));

        if ($smile == 0) {
            echo '<div class="title">Смайлы| Ошибка</div>
			<div class="menu"><center><b>Такой папки не существует!</b></center></div>';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit();
        }

        if ($perm['panel'] < 1) {
            header('Location: ' . homeLink() . '/admin/smile/');
            exit();
        }

        echo '<div class="title">Новый смайл</div>';

        if (isset($_REQUEST['ok'])) {
            $maxsize = 1; // Максимальный размер файла,в мегабайтах 
            $size = $_FILES['filename']['size']; // Вес файла

            /* Если не выбрали файл */
            if (!@file_exists($_FILES['filename']['tmp_name'])) {
                echo '<div class="menu"><center><b>Вы не выбрали файл!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            /* Максимальный размер 1мб */
            if ($size > (1048576 * $maxsize)) {
                echo '<div class="menu"><center><b>Максимальный размер файла ' . $maxsize . 'мб!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            /* Тип файлов которые можно загружать */
            $filetype = array('jpg', 'gif', 'png', 'jpeg', 'bmp');
            $upfiletype = substr($_FILES['filename']['name'],  strrpos($_FILES['filename']['name'], ".") + 1);

            /* Если тип файла не подходит */
            if (!in_array($upfiletype, $filetype)) {
                echo '<div class="menu"><center><b>К загрузке разрешены файлы форматом JPG,GIF,PNG,JPEG,BMP!</b></center></div>';
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit();
            }

            /* Если все окей,заливаем файл в папу и делаем запрос */
            $files = 'smile_' . rand(1234, 5678) . '_' . rand(1234, 5678) . '_' . $_FILES['filename']['name'];

            /* Заливаем */
            move_uploaded_file($_FILES['filename']['tmp_name'], "" . $_SERVER['DOCUMENT_ROOT'] . "/files/smile/" . $smile['name'] . "/" . $files . "");

            echo '<div class="menu"><center><b>Новый смайл добавлен!</b></center></div>';
        }

        echo '<div class="menu_nb">К загрузке допускаются фотографии форматом JPG,GIF,PNG,JPEG,BMP!</div>
		<form action="" method="post" enctype="multipart/form-data">
		
		<div class="menu_t">
		<div class="setting_punkt">Выберите файл:</div><input type="file" name="filename">
		</div>
		
		<div class="setting-footer">
		<div class="menu_t">
		<input type="submit" value="Загрузить" name="ok"> 
		</div>
		</div>
		
		</form>';
        break;


    case 'delsmile':
        $id = abs(intval($_GET['id']));
        $smile = mfa(dbquery("SELECT * FROM `smile` WHERE `id` = '" . $id . "'"));
        unlink('' . $_SERVER['DOCUMENT_ROOT'] . '/files/smile/' . $smile['icon'] . '');
        dbquery("DELETE FROM `smile` WHERE `id` = '" . $id . "'");
        header('Location: ' . homeLink() . '/admin/smile/razdel_' . $smile['papka'] . '');
        break;


    case 'delrazdel':
        $id = abs(intval($_GET['id']));
        $smile_p = mfa(dbquery("SELECT * FROM `smile_p` WHERE `id` = '" . $id . "'"));
        $smile_icons = mfar(dbquery("SELECT * FROM `smile` WHERE `papka` = '" . $id . "'"));

        deleteDirectory('' . $_SERVER['DOCUMENT_ROOT'] . '/files/smile/' . $smile_p['name'] . '');
        header('Location: ' . homeLink() . '/admin/smile');
        break;
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
