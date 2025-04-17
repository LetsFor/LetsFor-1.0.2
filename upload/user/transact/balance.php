<?
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');
$trs = new Transact;
echo '<title>Мой баланс</title>';
echo '<style> .content { padding: 0px; } </style>';
echo '<center><div class="headank" style="background: none;">';
echo '<div class="money" style="font-weight: 600; font-size: 30px; margin-bottom: 10px;"><span style="margin-left: 25px;">' . $user['money_col'] . '</span><span class="rub" style="font-size: 27px; font-weight: 100; position: relative; bottom: 1px;"> ₽</span></div>';
echo '<a class="button" href="' . homeLink() . '/balance/perevod">Перевести</a>';
echo '</div></center>';
	
echo '<div class="menu_nb" style="padding: 15px 20px;">';
$user = $user['id'];
$result = dbquery("SELECT * FROM transactions WHERE from_us = '$user' OR to_us = '$user' ORDER BY timestamp_col DESC");
echo '<div><b style="font-size: 14px;">История транзакций: </b></div>';
while($row = mfa($result)) {
	echo '<div class="menu_nbr" style="padding: 10px 0;"><b>' . nick($row['from_us']) . ' ➜ ' . nick($row['to_us']) . '</b><br />
	Сумма: <b>' . $row['amount'] . ' ₽</b><br />
	Тип транзакции: <b>' . $trs->ts_type($row['type_col']) . '</b><br />
	Дата: <b>' . vremja($row['timestamp_col']) . '</b><br />
	Номер транзакции: <b>' . $row['id'] . '</b><br />
	Статус: <b>' . $trs->ts_status($row['status']) . '</b>';
	echo '</div>';
}
echo '</div>';
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>