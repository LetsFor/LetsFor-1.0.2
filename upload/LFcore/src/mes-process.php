<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = abs(intval($_GET['id']));
	$mess = mfa(dbquery("SELECT * FROM `users` WHERE `id` = ?", [$id]));

    if (isset($mess['id']) and $user['id'] == $id) {
        echo err($title, 'Ошибка!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    $di = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = ? and `kogo` = ? LIMIT 1", [$user['id'], $mess['id']]));
    $ignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = ? and `kogo` = ?", [$mess['id'], $user['id']]));
    $youignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = ? and `kogo` = ?", [$user['id'], $mess['id']]));
	
	$text = LFS($_POST['msg']);

    if ($ignor['ignor'] == 1) {
        echo err($title, 'Пользователь добавил Вас в игнор лист!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (empty($text) or mb_strlen($text) < 3) {
        echo err('Ошибка ввода ,минимум 3 символа!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    $tim = dbquery("SELECT * FROM `message` WHERE `kto` = ? ORDER BY `time_up` DESC", [$user['id']]);

    while ($ncm2 = mfa($tim)) {
        $news_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `mes`"));
        $ncm_timeout = $ncm2['time_up'];

        if ((time() - $ncm_timeout) < $news_antispam['mes']) {
            echo '<div class="menu_nb"><center><b>Пишите не чаще чем раз в ' . $news_antispam['mes'] . ' секунд!</b></center></div>';
            require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit();
        }
    }

    $files = null;

    if ($user['form_file'] == 1) {
        $maxsize = 25; // Максимальный размер файла,в мегабайтах 
        $size = $_FILES['filename']['size']; // Вес файла

        if (@file_exists($_FILES['filename']['tmp_name'])) {
            if ($size > (1048576 * $maxsize)) {
                echo err($title, 'Максимальный размер файла ' . $maxsize . 'мб!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            $filetype = array('jpg', 'gif', 'png', 'jpeg', 'bmp', 'zip', 'rar', 'mp4', 'mp3', 'amr', '3gp', 'avi', 'flv');
            $upfiletype = substr($_FILES['filename']['name'],  strrpos($_FILES['filename']['name'], ".") + 1);

            if (!in_array($upfiletype, $filetype)) {
                echo err($title, 'Такой формат запрещено загружать!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            $files = $_SERVER['HTTP_HOST'] . '_' . rand(1234, 5678) . '_' . rand(1234, 5678) . '_' . $_FILES['filename']['name'];
            move_uploaded_file($_FILES['filename']['tmp_name'], "../files/mes/" . $files . "");
        }
    }

    $con = msres(dbquery("SELECT COUNT(id) FROM `message_c` WHERE `kogo` = '" . $mess['id'] . "' and `kto` = '" . $user['id'] . "' LIMIT 1"), 0);

    if ($con == 0) {
        dbquery("INSERT INTO `message_c` SET `kto` = ?, `kogo` = ?, `time_up` = ?, `posl_time` = ?, del = '0'", [$user['id'], $mess['id'], time(), time()]);
        dbquery("INSERT INTO `message_c` SET `kto` = ?, `kogo` = ?, `time_up` = ?, `posl_time` = ?, del = '0'", [$mess['id'], $user['id'], time(), time()]);
    }

    $dels = msres(dbquery("SELECT COUNT(id) FROM `message_c` WHERE `kogo` = ? and `kto` = ? and `del` = '1' or `kogo` = ? and `kto` = ? and `del` = '1' LIMIT 2", [$mess['id'], $user['id'], $user['id'], $mess['id']]), 0);

    if ($dels >= 1) {
        dbquery("UPDATE `message_c` SET `del`='0' WHERE `kogo` = ? AND `kto` = ? limit 1", [$mess['id'], $user['id']]);
        dbquery("UPDATE `message_c` SET `del`='0' WHERE `kogo` = ? AND `kto` = ? limit 1", [$user['id'], $mess['id']]);
    }
	
    dbquery("INSERT INTO `forum_file` SET `post_id` = ?, `name_file` = ?", [$id, $files]);
    dbquery("UPDATE `message_c` SET `posl_time` = ? WHERE `kogo` = ? and `kto` = ? limit 1", [time(), $user['id'], $id]);
    dbquery("UPDATE `message_c` SET `posl_time` = ? WHERE `kto` = ? and `kogo` = ? limit 1", [time(), $user['id'], $id]);
    dbquery("INSERT INTO `message` SET `text_col` = ?, `kto` = ?, `komy` = ?, `time_up` = ?, `readlen` = '0', `file_col` = ?", [$text, $user['id'], $mess['id'], time(), $files]);
}
?>