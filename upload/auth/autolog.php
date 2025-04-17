<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($_GET['ulog']) and empty($_GET['upas'])) {
    $login = LFS($_POST['login']); //фильтрируем
    $pass = PassCryptor(LFS($_POST['pass'])); //фильтрируем
} else {
    $login = LFS($_GET['ulog']);
    $pass = PassCryptor(LFS($_GET['upas']));
}

$sql = dbquery("SELECT `login` FROM `users` WHERE `login` = '" . $login . "' and `pass` = '" . $pass . "' LIMIT 1");
$dbsql = mfar(dbquery("SELECT `login`,`pass` FROM `users` WHERE `login` = '" . $login . "' and `pass`='" . $pass . "' LIMIT 1"));

if (msnumrows($sql)) {
    setcookie('uslog', $dbsql['login'], time() + 86400 * 365, '/');
    setcookie('uspass', $pass, time() + 86400 * 365, '/');
    header('location: ' . homeLink());
    exit();
} else {

    echo '<title>Авторизация</title>';
    echo '<div class="title">Авторизация</div>';

    if (empty($login)) {
        echo err('Вы не ввели логин!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (mb_strlen($login) > 20 or mb_strlen($login) < 3) {
        echo err('Введите логин от 3 до 20 символов!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!preg_match('|^[a-z0-9\-]+$|i', $login)) {
        echo err('Кириллица запрещена в логине!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    //-----Проверяем на ввод пароля-----//
    if (empty($pass)) {
        echo err('Вы не ввели свой пароль!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (mb_strlen($pass) < 5) {
        echo err('Введите пароль от 5 символов!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    //-----Проверка на символы-----//
    if (!preg_match('|^[a-z0-9\-]+$|i', $pass)) {
        echo err('Кириллица запрещена в пароле!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!empty($login) && !empty($pass)) if ($dbsql == 0) {
        echo err('Такого пользователя не существует!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }
}
