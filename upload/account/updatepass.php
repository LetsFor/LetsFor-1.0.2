<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<title>Смена пароля</title>';
echo '<div class="title">Смена пароля</div>';

if (isset($_REQUEST['upspass'])) {

    $np = LFS($_POST['np']);
    $npp = LFS($_POST['npp']);
    $hp = PassCryptor(LFS($_POST['hp']));

    if (empty($hp) or empty($np)) {
        echo err('Введите старый и новый пароль!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if ($np != $npp) {
        echo err('Новый пароль подтвержден неверно!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (mb_strlen($hp) < 3 or mb_strlen($np) < 3) {
        echo err('Введите старый и новый пароль от 3 символов!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!preg_match('|^[a-z0-9\-]+$|i', $np)) {
        echo err('Кириллица запрещена в новом пароле!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!preg_match('|^[a-z0-9\-]+$|i', $hp)) {
        echo err('Кириллица запрещена в старом пароле!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    $sql = mfa(dbquery("SELECT `pass` FROM `users` WHERE `id` = '" . $user['id'] . "'"));
    if ($sql['pass'] != $hp) {
        echo err('Неверно введён старый пароль!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    dbquery("UPDATE `users` SET `pass` = '" . PassCryptor($np) . "' WHERE `id` = '" . $user['id'] . "'");

    if (isset($_COOKIE['uslog']) and isset($_COOKIE['uspass']))
        setcookie('uspass', PassCryptor($np), time() + 86400 * 31, '/');

    showAlert('Пароль успешно изменен!', 'info', 3000);
}

echo '<div class="menu_nb">
<form action="" method="POST">
<input placeholder="Старый пароль" type="text" name="hp" maxlength="25" />
<input placeholder="Новый пароль" type="text" name="np" maxlength="25" />
<input placeholder="Повторите новый пароль" type="text" name="npp" maxlength="25" />
<input type="submit" name="upspass" value="Изменить" style="width: 150px; margin-top: 15px; margin-bottom: 0px; border-bottom: 0px;"/>
</form>
</div></div>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
