<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<title>Смена почты</title>';
echo '<div class="title">Смена почты</div>';

if (isset($_REQUEST['upsemail'])) {
    $email = LFS($_POST['email']);
    $nee = LFS($_POST['nee']);
    $hp = PassCryptor(LFS($_POST['hp']));

    if (empty($hp) || empty($email)) {
        echo err('Введите новую почту и пароль!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if ($email != $nee) {
        echo err('Новая почта подтверждена неверно!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo err('Некорректный формат почты!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!preg_match('|^[a-z0-9\-]+$|i', $hp)) {
        echo err('Кириллица запрещена в пароле!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    $sql = mfa(dbquery("SELECT `pass` FROM `users` WHERE `id` = '" . $user['id'] . "'"));
    if ($sql['pass'] != $hp) {
        echo err('Неверно введён пароль!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    dbquery("UPDATE `users` SET `email` = '" . $email . "' WHERE `id` = '" . $user['id'] . "' ");

    showAlert('Почта успешно изменена!', 'info', 3000);
}

echo '<div class="menu_nb">
<form action="" method="POST">
<input placeholder="Новый email" type="text" name="email" maxlength="50" />
<input placeholder="Подтвердите email" type="text" name="nee" maxlength="50" />
<input placeholder="Пароль" type="password" name="hp" maxlength="25" />
<input type="submit" name="upsemail" value="Изменить" style="width: 150px; margin-top: 15px; margin-bottom: 0px; border-bottom: 0px;"/>
</form>
</div></div>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
