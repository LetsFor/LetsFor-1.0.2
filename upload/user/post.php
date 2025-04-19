<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

$act = isset($_GET['act']) ? LFS($_GET['act']) : "";
$id = isset($_GET['id']) ? abs(intval($_GET['id'])) : "";
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

switch ($act) {
    default:

        if ($ank == 0) {
            header('Location: ' . homeLink() . '/post' . $user['id']);
            exit;
        }

        if ($id == $user['id']) echo '<div class="title">Мои посты</div>';
        else echo '<div class="title"><a href="' . homeLink() . '/id' . $id . '">Анкета ' . $ank['login'] . '</a> | Посты</div>';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '" . $ank['id'] . "' "), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $post = dbquery("SELECT * FROM `forum_post` WHERE `us` = '" . $ank['id'] . "' ORDER BY `id` DESC LIMIT $start, $max");

        while ($a = mfa($post)) {

            echo '<title>Посты</title>';

            echo '<table class="menu" cellspacing="0" cellpadding="0">';

            echo '<td class="block_avatar">';
            $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us'] . "'"));
            echo UserAvatar($ank, $width, $height);
            echo '</td>';
            echo '<td class="block_content">';
            echo '' . nick($a['us']) . ' <span class="time">' . vremja($a['time_up']) . '</span>';
            echo '<div class="block_msg">' . nl2br(smile(bb($a['text_col']))) . '</div>
			<a class="button" href="' . homeLink() . '/tema' . $a['tema'] . '">Перейти в тему</a>';
            echo '</td>';
            echo '</table>';
        }

        if ($k_post < 1) echo '<div class="menu_nb">Пользователь еще не оставлял постов</div>';
        if ($k_page > 1) echo str('' . homeLink() . '/post' . $id . '?', $k_page, $page); // Вывод страниц

        break;
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
