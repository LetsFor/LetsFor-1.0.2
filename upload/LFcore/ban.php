<?php
session_start();
$_SESSION['access'] = TRUE;

if (isset($user['id'])) {

	$bl = mfa(dbquery("SELECT * FROM `ban_list` WHERE `kto` = '" . $user['id'] . "' LIMIT 1")); //которые уже освободились
	$ban_list = mfa(dbquery("SELECT * FROM `ban_list` WHERE `kto` = '" . $user['id'] . "' && `time_end` > '" . time() . "' LIMIT 1")); //еще в бане

	if ($ban_list != 0) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/LFcore/head.php';
		echo '<style>
		.content {
			padding: 40px 0 0;
		}
		@media all and (min-width: 800px){ 
		#sidebar {
			display: none;
		}
		</style>';

		echo '<div class="menu_nbr">
		<center><div class="icon_window_lock" style="margin-bottom: 20px;"><img src="' . homeLink() . '/images/lock.png" style="width: 100px; height: 100px;"></div></center>
		<center><div class="name_window" style="font-size: 19px; font-weight: 600;">Доступ к сайту запрещён</div></center>
		<div class="menu_nb"><center>Ваш аккаунт был заблокирован по причине:</center></div>
		<div class="menu_nb"><center><b><span class="block--info">' . smile(bb($ban_list['about'])) . '</span></b></center></div>
		<div class="menu_nb"><center>Дата разблокировки: <b>' . date('d.m.Y в H:i', $ban_list['time_end']) . '</center></b></div>
		</div>';

		echo '<div class="menu_nbr">
		<div class="name_window" style="font-size: 19px; font-weight: 600;"><center>Считаете что это ошибка?</center></div>
		<div class="menu_nb"><center>Узнать подробности или обжаловать блокировку можно нажав на кнопку ниже:</center></div>
		<div class="menu_nb"><center><a class="button" href="mailto:' . $CG_help_mail . '" >Связаться с нами</a></center></div>
		</div>';

		echo '<div class="menu_nb">
		<center>Забанил: ' . nick($ban_list['add_ban']) . '</center>
		</div>';

		if (isset($_REQUEST['ok'])) {
			$about = LFS($_POST['about']);

			/* Антиспам */
			$pr = msres(dbquery("SELECT COUNT(id) FROM `jalob_ba` WHERE `avtor` = '" . $user['id'] . "'"), 0);
			if ($pr != 0) {
				echo '<div class="menu_nb"><center><b>Вы уже своё сказали!</b></center></div>';
				require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
				exit();
			}

			dbquery("INSERT INTO `jalob_ba` SET `about` = '" . $about . "', `avtor` = '" . $user['id'] . "', `komy` = '1', `time_up` = '" . time() . "'");
			header('Location: ' . homeLink() . '');
			exit();
		}

		$pr = msres(dbquery("SELECT COUNT(id) FROM `jalob_ba` WHERE `avtor` = '" . $user['id'] . "'"), 0);

		if ($pr == 0) {
			echo '<div></form></div>';
		} else {
			$list = dbquery("SELECT * FROM `jalob_ba` WHERE `avtor` = '" . $user['id'] . "' ORDER bY `id` DESC ");
			while ($ank = mfa($list)) {
				echo '<div class="links">' . nick($ank['avtor']) . ' (' . vremja($ank['time_up']) . ')</div><div class="podmenu">' . smile(bb($ank['about'])) . '</div>';
			}

			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
			exit();
		}

		$chet = msres(dbquery("SELECT COUNT(id) FROM `jalob_ba` WHERE `avtor` = '" . $user['id'] . "'"), 0);
		if ($chet == 0)

			require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
		exit();
	} else {
		if ($bl['time_end'] < time()) {
			dbquery("DELETE FROM `ban_list` WHERE `kto` = '" . $bl['kto'] . "'");
		}
	}
}
?>