<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<div class="title">Личные настройки</div>';

if (isset($_REQUEST['ok'])) {
    $max = LFS($_POST['max']);
    $nt = LFS($_POST['new_tem']);

    dbquery("UPDATE `users` SET `max_us` = '" . $max . "', `new_tem` = '" . $nt . "' WHERE `id` = '" . $user['id'] . "'");
    setcookie(time() + 60 * 60 * 24 * 14);
    showAlert('Настройки успешно сохранены!', 'info', 3000);
}

echo '<form name="form" action="" method="post">';

echo '<div class="menu_nbr">';
echo '<div class="setting_punkt">Пунктов на страницу:</div>
<div class="gt-select"><select name="max" style="width: 100%; margin-left: 0px; margin-right: 0px; border-radius: 8px;">';
$dat = array('15' => '15', '20' => '20', '30' => '30', '40' => '40', '50' => '50');
foreach ($dat as $key => $value) {
    echo ' <option value="' . $value . '"' . ($value == $user['max_us'] ? ' selected="selected"' : '') . '>' . $key . '</option>';
}
echo '</select></div>';
echo '</div>';

echo '<div class="menu_nb">';
echo $select->updateSelectField('new_tem', 'Скрывать темы на главной', $user['new_tem']);
echo '</div>';

echo '<div class="setting-footer">';
echo '<div class="menu_t">';
echo '<input type="submit" name="ok" value="Изменить" style="width: 150px; margin-bottom: 0px; border-bottom: 0px;">';
echo '</div>';
echo '</div>';

echo '</form>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
