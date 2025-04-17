<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if ($perm['add_ras'] < 1) {
    echo err($title, 'У вас не достаточно прав для просмотра данной страницы!');
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
    exit;
}

echo '<div class="title">Рассылка писем</div>';
if (isset($_REQUEST['ok'])) {

    $text = LFS($_POST['text']);
    $group = LFS($_POST['group']);

    if ($group == 1) {
        $query = dbquery("SELECT * FROM `users` WHERE `level_us` > '1'");
        while ($fp = mfar($query)) {
            dbquery("INSERT INTO `lenta` SET `kto` = '" . $rootUser['id'] . "', `komy` = '" . $fp['id'] . "', `readlen` = '0', `text_col` = '" . $text . "', `time_up` = '" . time() . "'");
            $i++;
        }
    }

    if ($group == 2) {
        $query = dbquery("SELECT * FROM `users` WHERE `level_us` < '2'");
        while ($fp = mfar($query)) {
            dbquery("INSERT INTO `lenta` SET `kto` = '" . $rootUser['id'] . "', `komy` = '" . $fp['id'] . "', `readlen` = '0', `text_col` = '" . $text . "', `time_up` = '" . time() . "'");
            $i++;
        }
    }

    showAlert('Рассылка отправлена!', 'info', 3000);
}

echo '<form action="" method="post" enctype="multipart/form-data"> 
<div class="menu_nb">
<div class="setting_punkt">Текст рассылки:</div><textarea name="text" style="height: 200px;"></textarea>
</div>

<div class="menu_t">
<div class="setting_punkt">Кому:</div>
<div class="gt-select"><select name="group" style="margin: 0px;">
<option value="1">Админам</option>
<option value="2">Пользователям</option>
</select></div>
</div>

<div class="setting-footer">
<div class="menu_t">
<input type="submit" name="ok" value="Отправить" />
</div>
</div>

</form>';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
