<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$id = abs(intval($_GET['id']));
$prv = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $id . "'"));
if (isset($_REQUEST['ok'])) {
    dbquery("UPDATE `users` SET `prev` = '" . $prv['id'] . "', `money_col` = '" . ($user['money_col'] - $prv['cena_prev']) . "' WHERE `id` = '$user[id]' LIMIT 1");
    showAlert('Вы успешно приобрели повышение!', 'info', 3000);
}
echo '<div class="title">Покупка ' . $prv['name'] . '</div>';
echo '<div class="menu_nb">';
echo '<div class="pev_text_title" style="font-size: 20px; margin-bottom: 10px">При покупке <span class="up_us_lich" style="' . $prv['color_icon_prev'] . '">' . $prv['name'] . '</span> вы получите:</div>';
echo '<div class="pev_text" style="line-height: 1.7">';
require_once '' . $_SERVER['DOCUMENT_ROOT'] . '/user/us/upgrade/groups.php';
echo '<span style="color: #999; margin-top: 7px; display: block;">Иконка приобретаемого ресурса: <span class="ico_cen_bar">' . html_entity_decode($prv['icon_prev'], ENT_QUOTES, 'UTF-8') . '</span></span>';
echo '</div>';
if ($prv['id'] != $user['prev']) {
    echo '<form name="form" action="" method="post">
	<center style="margin-top: 10px; display: table;"><input type="submit" name="ok" value="Подтвердить покупку" style="width: auto; margin-right: 5px; margin-top: 0px; margin-bottom: 0px; border-bottom: 0px;"><a class="button" href="' . homeLink() . '/upgrade">Отказаться</a></center>
	</form>';
} else {
    echo '<br /><a class="button-dissable" style="margin-top: 10px;">Присутствует</a>';
}
echo '</div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
