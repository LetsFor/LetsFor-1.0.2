<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) exit(header('Location: ' . homeLink()));

$id = abs(intval($_GET['id']));
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

if ($ank == 0) $err = '<b>Такого пользователя не существует!</b>';
if ($id == $user['id']) $err = '<b>Вы не можете самому себе перевести деньги</b>';
if ($user['money_col'] == 0) $err = '<b>Недостаточно средств на балансе</b>';

if ($err) {
    echo err($title, $err);
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
    exit;
}

echo '<title>Перевод</title>';

echo '<div class="title">Перевод пользователю ' . $ank['login'] . '</div>';

if (isset($_POST['summ'])) {

    $summ = abs(intval($_POST['summ']));
    if (empty($summ)) {
        echo err('Вы не ввели сумму перевода!');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }

    if ($user['money_col'] < $summ) {
        echo err('Недостаточно средств на балансе');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit;
    }
	
	
	try {
    dbquery("UPDATE `users` SET `money_col` = `money_col` + " . $summ . " WHERE `id` = '" . $id . "'");
    dbquery("UPDATE `users` SET  `money_col` =  `money_col` - " . $summ . " WHERE  `id` = '" . $user['id'] . "'");
	$status = 1;
	showAlert('Перевод успешно выполнен!', 'info', 3000);
	} catch (PDOException $e) {
		die("Ошибка запроса: " . $e->getMessage());
		$status = 2;
	}
	dbquery("INSERT INTO `transactions` SET  `amount` =  '" . $summ . "', `type_col` = '1', `timestamp_col` = '" . time() . "', `from_us` = '" . $user['id'] . "', `to_us` = '" . $id . "', `status` = '" . $status . "'");
}
echo '<div class="menu_nb">';
echo '<form action="" method="POST">
Сумма превода:<br/>
<input type="text" name="summ" /> <br />
<center><input type="submit" name="submit" value="Перевести" style="margin-left: 3px; font-color: #d2d2d2; width: 175px; margin-top: 10px; margin-bottom: 0px; border-bottom: 0px;"/></center>
</form>
</div>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
