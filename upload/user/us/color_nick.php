<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<div class="title">Смена дизайна никнейма</div>';

$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

if (isset($user) & $prv_us['free_set_des_name'] != 1 & $user['money_col'] < $settings['color_nick_cena']) {
    echo '<div class="err">Извините, но у вас не хватает денег для смены дизайна ника, нужно <b>' . $settings['color_nick_cena'] . '</b> ₽<br/>У вас <b>' . $user['money_col'] . '</b> ₽</div>';
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
    exit();
}

if (isset($_REQUEST['ok'])) {

    $color_nick = LFS($_POST['color_nick']);

    if ($prv_us['free_set_des_name'] == 1) {
        dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] - 0) . "', `color_nick` = '" . $color_nick . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
    } else {
        dbquery("UPDATE `users` SET `money_col` = '" . ($user['money_col'] - $settings['color_nick_cena']) . "', `color_nick` = '" . $color_nick . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
    }

    showAlert('Цвет ника успешно изменен!', 'info', 3000);
}

if ($prv_us['free_set_des_name'] == 1) {
    echo '<div class="menu_nb">Для вас стоимость смены дизайна ника составляет 0 ₽</div>';
} else {
    echo '<div class="menu_nbr">Стоимость смены дизайна ника ' . $settings['color_nick_cena'] . ' ₽</div>';
}

echo '<div class="menu_nb">';
echo '<form name="form" action="?act=set&amp;ok=1" method="post">

<center><textarea name="color_nick" placeholder="Введите css стиль" style="height: 150px;" id="color-nick-css">' . $user['color_nick'] . '</textarea></center>';

echo '<center><input type="submit" name="ok" value="Изменить" style="width: 150px; margin-top: 15px; margin-bottom: 0px; border-bottom: 0px;" /></center>
</form></div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
