<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<div class="title">Смена никнейма</div>';

$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

if (isset($user) & $prv_us['free_set_name'] != 1 & $user['money_col'] < $settings['nick_cena']) {
    echo '<div class="err">Извините, но у вас не хватает денег для смены ника, нужно <b>' . $settings['nick_cena'] . '</b> ₽<br/>У вас <b>' . $user['money_col'] . '</b> ₽</div>';
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
    exit();
}

if (isset($_POST['submit'], $_POST['login'])) {
    $login = LFS($_POST['login']);

    $sql = dbquery("SELECT COUNT(`id`) FROM `users` WHERE `login` = '" . $login . "'");
    if (msres($sql, 0)) {
        echo err('Этот ник уже занят!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if (!preg_match('|^[a-z0-9\-]+$|i', $login)) {
        echo err('Кириллица запрещена в логине!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if ($prv_us['free_set_name'] == 1) {
        dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] - 0) . "', `login` = '" . $login . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
    } else {
        dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] - $settings['nick_cena']) . "', `login` = '" . $login . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
    }

    if (isset($_COOKIE['uslog']) and isset($_COOKIE['uspass']))
        setcookie('uslog', $login, time() + 86400 * 365, '/');

    if ($prv_us['free_set_name'] == 1) {
        echo '<div class="ok">Ваш ник успешно изменен, теперь он будет использоваться для входа на сайт.</div>';
    } else {
        echo '<div class="ok">Ваш ник успешно изменен, теперь он будет использоваться для входа на сайт. С вас списано ' . $settings['nick_cena'] . ' ₽. <br /></div>';
    }
}

if ($prv_us['free_set_name'] == 1) {
    echo '<div class="menu_nbr">Вы можете бесплатно сменить ник. Кириллица запрещена.</div>';
} else {
    echo '<div class="menu_nbr">Вы можете сменить ник за <b>' . $settings['nick_cena'] . '</b> ₽, Кириллица запрещена.</div>';
}

echo '<div class="menu_nb"><form action="" method="POST">
<input placeholder="Новый ник" type="text" maxlength="13" name="login" value="' . $user['login'] . '"/>
<center><input type="submit" name="submit" value="Сменить" style="width: 150px; margin-top: 15px; margin-bottom: 0px; border-bottom: 0px;" /></center>
</form></div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
