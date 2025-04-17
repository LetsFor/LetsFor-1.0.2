<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

$act = isset($_GET['act']) ? LFS($_GET['act']) : "";
$id = isset($_GET['id']) ? abs(intval($_GET['id'])) : "";
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

switch ($act) {
    default:

        if ($ank == 0) {
            header('Location: ' . homeLink() . '/them' . $user['id']);
            exit;
        }

        if ($id == $user['id']) {
			echo '<div class="title">Мои темы</div>';
		} else {
			echo '<div class="title"><a href="' . homeLink() . '/id' . $id . '">Анкета ' . $ank['login'] . '</a> | Темы</div>';
		}

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '" . $ank['id'] . "' "), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $forum = dbquery("SELECT * FROM `forum_tema` WHERE `us` = '" . $ank['id'] . "' ORDER BY `id` DESC LIMIT $start, $max");

        echo '<title>Мои темы</title>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/add_them_list.php');

        if ($k_post < 1) echo '<div class="menu_nb">Пользователь еще не создавал тем</div>';
        if ($k_page > 1) echo str('' . homeLink() . '/them' . $id . '?', $k_page, $page); // Вывод страниц

        break;
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
