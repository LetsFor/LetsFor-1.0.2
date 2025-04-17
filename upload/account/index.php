<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<title>Персональная информация</title>';

echo '<div class="title">Персональная информация</div>';

$settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

if (isset($_REQUEST['submit'])) {

    $name = LFS($_POST['name']);
    $strana = LFS($_POST['strana']);
    $gorod = LFS($_POST['gorod']);
    $stat = LFS($_POST['stat']);
    $skype = LFS($_POST['skype']);
    $icq = LFS($_POST['icq']);
    $url = LFS($_POST['url']);
    $sex = abs(intval($_POST['sex']));
    $name = LFS($_POST['name']);

    dbquery("UPDATE `users` SET `name` = '" . $name . "', `strana` = '" . $strana . "', `gorod` = '" . $gorod . "', `stat` = '" . $stat . "', `skype` = '" . $skype . "', `icq` = '" . $icq . "', `url` = '" . $url . "', `sex` = '" . $sex . "' WHERE `id` = '" . $user['id'] . "'");
    showAlert('Настройки успешно сохранены!', 'info', 3000);
}
echo '<div class="settings">';
echo '<div class="ank_setting">
<form action="" method="POST">
<div class="menu_nb">
<div>
<div class="setting_punkt">Ник: <span class="nickname_user">' . nick($user['id']) . '</span></div>';

if ($prv_us['free_set_name'] == 1) {
    echo '<a class="button" href="' . homeLink() . '/user/us/new_nick.php" style="margin-bottom: 10px;">Изменить ник</a>';
} else {
    echo '<a class="button" href="' . homeLink() . '/user/us/new_nick.php" style="margin-bottom: 10px;">Изменить ник за ' . $settings['nick_cena'] . ' <b>₽</b></a>';
}

echo '<div style="color: #777; margin-bottom: 10px;">Ник отображается в сообщениях и профиле, видим для всех, является логином для входа на сайт.</div>';
echo '</div>';

if ($prv_us['free_set_des_name'] == 1) {
    echo '<a class="button" href="' . homeLink() . '/user/us/color_nick.php" style="margin-bottom: 10px;">Сменить цвет ника</a>';
} else {
    echo '<a class="button" href="' . homeLink() . '/user/us/color_nick.php" style="margin-bottom: 10px;">Сменить цвет ника за ' . $settings['color_nick_cena'] . ' <b>₽</b></a>';
}
echo '</div>';

echo '<div class="menu_t">
<div class="btn-group">
<span class="fontawesome_in_menu">
<img src="' . homeLink() . '/files/ava/' . $user['avatar'] . '" style="border-radius: 100px; width: 70px; height: 70px; margin-right: 15px;">
<div class="ava-set-block">
<div class="setting_punkt">Фото профиля:</div>
<a class="button" href="' . homeLink() . '/account/avatar" style="margin-bottom: 10px;">Изменить аватар</a>
</div>
</span>
</div>
</div>';

echo '</div>';

echo '<div class="ank_setting">

<div class="menu_t">
<div class="setting_punkt">Имя:</div>
<input type="text" name="name" value="' . $user['name'] . '">
</div>

<div class="menu_t">
<div class="setting_punkt">Статус:</div>
<input type="text" name="stat" value="' . $user['stat'] . '">
</div>

<div class="menu_t">
<div class="setting_punkt">Страна:</div>
<input type="text" name="strana" value="' . $user['strana'] . '">
</div>

<div class="menu_t">
<div class="setting_punkt">Город:</div>
<input type="text" name="gorod" value="' . $user['gorod'] . '">
</div>

<div class="menu_t">
<div class="setting_punkt">Сайт:</div>
<input type="text" name="url" value="' . $user['url'] . '">
</div>

<div class="menu_t">
<div class="setting_punkt">Пол:</div>
<div class="gt-select"><select name="sex">';
$dat = array('Мужской' => '1', 'Женский' => '2');
foreach ($dat as $key => $value) {
    echo ' <option value="' . $value . '"' . ($value == $up_us['sex'] ? ' selected="selected"' : '') . '>' . $key . '</option>';
}
echo '</select>
</div>
</div>
</div>';

echo '<div class="setting-footer">';
echo '<div class="menu_t">';
echo '<input type="submit" name="submit" value="Сохранить изменения" style="width: 175px; margin-bottom: 0px; border-bottom: 0px;">';
echo '</div>';
echo '</div>';
echo '</form>';
echo '</div>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
