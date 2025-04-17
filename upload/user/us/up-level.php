<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<title>Повышение прав</title>';
echo '<div class="title">Повышение прав</div>';

echo '<div class="preves">';

$prev = dbquery("SELECT * FROM `user_prevs` ORDER BY `cena_prev`");
while ($prv = mfa($prev)) {
    echo '<div class="menu_nbr" style="font-weight: bold;">';
    echo '<div class="text_pod_but" style="font-size: 20px; margin-bottom: 10px; display: inline-block;">' . $prv['name'] . '</div><div class="cena" style="float: right; display: inline-block; font-size: 20px;">' . $prv['cena_prev'] . ' ₽</div>';
    echo '<div class="min_text_pod_but" style="margin-bottom: 10px;"><span class="pev_text_title">Преимушества:</span><br />';
    echo '<span class="pev_text">';
    require ($_SERVER['DOCUMENT_ROOT'] . '/user/us/upgrade/groups.php');
    echo '</span>';
    echo '</div>';

    if ($user['money_col'] >= $prv['cena_prev'] && $prv['id'] != $user['prev']) {
		echo '<a class="button" href="' . homeLink() . '/upgrade/pay-upgrade' . $prv['id'] . '">Купить</a>';
	} else {
		echo '<a class="button-dissable">Недоступно</a>';
	}

    echo '</div>';
}

echo '</div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>