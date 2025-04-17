<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) {
	echo err($title, 'Уважаемый посетитель, Вы зашли на сайт как незарегистрированный пользователь.<br/>Мы рекомендуем Вам зарегистрироваться либо войти на сайт под своим именем.');
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
	exit;
}

if (isset($_REQUEST['submit'])) {
	$complaint = LFS($_POST['complaint']);
	$opis = LFS($_POST['opis']);
	
	dbquery("INSERT INTO `complaints` SET `us` = '" . $user['id'] . "', `time_up` = '" . time() . "', `complaint` = '" . $complaint . "', `opis` = '" . $opis . "', `status` = '0'");
	showAlert('Жалоба успешно отправлена!', 'info', 3000);
}

echo '<title>Жалоба</title>';
echo '<div class="title">Жалоба</div>';
echo '<form action="" method="POST">';

echo '<div class="menu_nb">
<div class="setting_punkt">Ссылка на страницу с нарушением:</div>
<input type="text" name="complaint" placeholder="https://...">
</div>';
 
echo '<div class="menu_t">
<div class="setting_punkt">Описание нарушения:</div>
<textarea name="opis" placeholder="Опишите нарушение..."></textarea>
</div>';

echo '<div class="setting-footer">';
echo '<div class="menu_t">';
echo '<input type="submit" name="submit" value="Отправить жалобу" style="width: 175px; margin-bottom: 0px; border-bottom: 0px;">';
echo '</div>';
echo '</div>';
echo '</form>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>