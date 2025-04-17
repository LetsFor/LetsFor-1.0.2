<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');
echo '<title>Жалобы</title>';

if($perm['close_them'] != 1 or $perm['del_them'] != 1) {
	exit(header('Location: ' . homeLink()));
}
$complaint = dbquery("SELECT * FROM `complaints` ORDER BY `time_up` DESC");
echo '<div class="title">Жалобы</div>';
while ($a = mfa($complaint)) {
	if (isset($_REQUEST['submit'])) {
		dbquery("UPDATE `complaints` SET `status` = '1' WHERE `id` = '" . $a['id'] . "'");
		showAlert('Жалоба успешно рассмотрена!', 'info', 3000);
	}
	echo '<form action="" method="POST">';
	echo '<div class="menu_nbr">
	Жалоба от ' . nick($a['us']) . '<br />
	Страница с нарушением: <a href="' . $a['complaint'] . '"><span class="drag_menu_name"><b>' . $a['complaint'] . '</b></span></a><br />
	Причина жалобы: <b>' . $a['opis'] . '</b><br />
	Время отправки жалобы: <b>' . vremja($a['time_up']) . '</b><br />
	Статус жалобы: <b>' . complaint_status($a['status']) . '</b><br />';
	if ($a['status'] == 0) {
		echo '<input type="submit" name="submit" class="button min" value="Отметить рассмотренной" style="margin-top: 10px;">';
	}
	echo '</div>';
	echo '</form>';
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>