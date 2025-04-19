<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if ($perm['panel'] < 1) {
    echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
    exit;
}

echo '<title>Настройки сайта</title>';
echo '<div class="title">Настройки сайта</div>';

if (isset($_REQUEST['ok'])) {

    $key = LFS($_POST['key']);
    $des = LFS($_POST['des']);
    $cop = LFS($_POST['cop']);
    $vk = LFS($_POST['vk']);
    $tg = LFS($_POST['tg']);
    $eml = LFS($_POST['eml']);
    $name = LFS($_POST['name']);
    $podname = LFS($_POST['podname']);
    $nick_cena = LFS($_POST['nick_cena']);
    $color_nick_cena = LFS($_POST['color_nick_cena']);
    $reg_on = abs(intval($_POST['reg_on']));

    if (empty($name)) {
        echo '<div class="menu_nbr"><center><b>Вы не ввели название!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (mb_strlen($name) < 3 or mb_strlen($name) > 50) {
        echo '<div class="menu_nbr"><center><b>Введите название от 3 до 50 символов!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (empty($podname)) {
        echo '<div class="menu_nbr"><center><b>Вы не написали направление форума!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (mb_strlen($podname) < 3 or mb_strlen($podname) > 50) {
        echo '<div class="menu_nbr"><center><b>Напишите о чем форум от 3 до 50 символов!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (empty($key)) {
        echo '<div class="menu_nbr"><center><b>Вы не ввели Keywords!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (mb_strlen($key) < 3 or mb_strlen($key) > 500) {
        echo '<div class="menu_nbr"><center><b>Введите Keywords от 3 до 500 символов!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (empty($des)) {
        echo '<div class="menu_nbr"><center><b>Вы не ввели Description!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (mb_strlen($des) < 3 or mb_strlen($des) > 500) {
        echo '<div class="menu_nbr"><center><b>Введите Description от 3 до 500 символов!</b></center></div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    dbquery("UPDATE `settings` SET `name` = '" . $name . "', `podname` = '" . $podname . "', `key_col` = '" . $key . "', `des` = '" . $des . "', `reg_on` = '" . $reg_on . "', `help_vk` = '" . $vk . "', `help_tg` = '" . $tg . "', `help_email` = '" . $eml . "', `load_mod` = '" . $load_mod . "', `nick_cena` = '" . $nick_cena . "', `color_nick_cena` = '" . $color_nick_cena . "' WHERE `id` = '1'");
    showAlert('Изменения успешно сохранены!', 'info', 3000);
}

$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

echo '<form action="" method="POST">
<div class="menu_nb">
<div class="setting_punkt">Название форума:</div><input type="text" name="name" value="' . $settings['name'] . '" maxlength="500">
</div>

<div class="menu_t">
<div class="setting_punkt">Направление форума:</div><input type="text" name="podname" value="' . $settings['podname'] . '" maxlength="500">
</div>

<div class="menu_t">
<div class="setting_punkt">Keywords:</div><input type="text" name="key" value="' . $settings['key_col'] . '" maxlength="500" placeholder="игры, софт, разработка">
</div>

<div class="menu_t">
<div class="setting_punkt">Description:</div><textarea name="des">' . $settings['des'] . '</textarea>
</div>

<div class="menu_t">
<div class="setting_punkt">VK для обратной связи:</div><input type="text" name="vk" value="' . $settings['help_vk'] . '" maxlength="500" placeholder="https://...">
</div>

<div class="menu_t">
<div class="setting_punkt">Telegram для обратной связи:</div><input type="text" name="tg" value="' . $settings['help_tg'] . '" maxlength="500" placeholder="https://...">
</div>

<div class="menu_t">
<div class="setting_punkt">Email для обратной связи:</div><input type="text" name="eml" value="' . $settings['help_email'] . '" maxlength="500" placeholder="example@ex.ru">
</div>

<div class="menu_t">
<div class="setting_punkt">Цена смены ника:</div><input type="text" name="nick_cena" value="' . $settings['nick_cena'] . '" maxlength="500" placeholder="Цена за ник...">
</div>

<div class="menu_t">
<div class="setting_punkt">Цена цвета ника:</div><input type="text" name="color_nick_cena" value="' . $settings['color_nick_cena'] . '" maxlength="500" placeholder="Цена за цвет ника...">
</div>';

echo '<div class="menu_t">';
echo '<div class="setting_punkt">Настройки регистрации:</div><div class="gt-select"><select name="reg_on">';
$dat = array('Включена' => '0', 'Выключена' => '1');
foreach ($dat as $key => $value) {
    echo ' <option value="' . $value . '"' . ($value == $settings['reg_on'] ? ' selected="selected"' : '') . '>' . $key . '</option>';
}
echo '</select></div>';
echo '</div>';

echo '<div class="setting-footer">';
echo '<div class="menu_t">';
echo '<input type="submit" name="ok" value="Сохранить">';
echo '</div>';
echo '</div>';

echo '</form>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
