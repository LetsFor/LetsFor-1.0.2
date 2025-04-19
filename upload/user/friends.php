<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

$act = isset($_GET['act']) ? LFS($_GET['act']) : "";
$id = isset($_GET['id']) ? abs(intval($_GET['id'])) : "";
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

switch ($act) {
    default:

        if ($ank == 0) {
            header('Location: ' . homeLink() . '/friends' . $user['id']);
            exit;
        }

        if ($id == $user['id']) echo '<div class="title">Мои друзья</div>';
        else echo '<div class="title">Друзья ' . $ank['login'] . '</div>';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `friends` WHERE `us_a` = '" . $user['id'] . "' AND `status` = '1'"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;

        $q = dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $id . "' AND `status` = '1' ORDER BY `time_up` DESC LIMIT $start,$max");

        while ($a = mfa($q)) {
            $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us_b'] . "'"));
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id' . $ank['id'] . '">';
            echo '<td style="width: 65px;">';
            echo UserAvatar($ank, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
            echo '<span class="nick_user" style="pointer-events: none;">' . nick($ank['id']) . '</span></br>';
            $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '$ank[id]'"), 0);
            echo 'Постов в форуме: <span class="num-indi">' . $p_forum . '</span></br>';
            $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '$ank[id]'"), 0);
            echo 'Тем в форуме: <span class="num-indi">' . $t_forum . '</span>';
            echo '</td>';
            echo '</table>';
        }

        if ($k_post < 1) {
            echo '<div class="menu_nb">Друзей пока нет</div>';
        }

        if ($k_page > 1) {
            echo str(homeLink() . '/friends' . $id, $k_page, $page); // Вывод страниц
        }

        break;
    case 'add':

        if ($ank == 0) {
            echo err($title, 'Такого пользователя не существует!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        if ($user['id'] == $ank['id']) {
            echo err($title, 'Вы не можете добавить в друзья самого себе!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        $tim = dbquery("SELECT * FROM `friends` WHERE `us_b`='" . $user['id'] . "' ORDER BY `time_up` DESC");
        while ($ncm2 = mfa($tim)) {
            $news_antispam = mfa(dbquery("SELECT * FROM `antispam`"));
            $ncm_timeout = $ncm2['time_up'];
            if ((time() - $ncm_timeout) < $news_antispam['friends']) {
                echo err($title, 'Добавлять друзей можно не чаще чем раз в ' . $news_antispam['friends'] . ' секунд!');
                require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }
        }

        $bid = mfa(dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $id . "' AND `status` = '0'"));

        if ($bid != 0) {
            echo err($title, 'Заявка отправлена. Ожидайте!');
            echo '<div class="links"><a href="' . homeLink() . '/id' . $id . '">Перейти в анкету</a></div>';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        $s = mfa(dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $id . "' AND `us_b` = '" . $user['id'] . "'"));

        if ($s['status'] == 1) {
            echo err($title, 'Пользователь уже находится у Вас в друзьях!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        echo '<div class="title">Добавить ' . $ank['login'] . ' в друзья</div>';

        if (isset($_GET['da'])) {
            dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $ank['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'хочет добавить Вас в [url=" . homeLink() . "/friends/bid]друзья[/url]'");
            dbquery("INSERT INTO `friends` SET `us_a` = '" . $id . "', `us_b` = '" . $user['id'] . "', `status` = '0', `time_up` = '" . time() . "'");

            echo '<div class="menu_nbr">Заявка отправлена</div>';
            echo '<div class="link"><a href="' . homeLink() . '/id' . $id . '">Перейти в анкету</a></div>';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        break;
    case 'bid':

        echo '<div class="title">Заявки в друзья</div>';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `friends` WHERE `us_a` = '" . $user['id'] . "' AND `status` = '0'"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;

        $q = dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $user['id'] . "' AND `status` = '0' ORDER BY `time_up` DESC LIMIT $start,$max");
        while ($as = mfa($q)) {

            echo '<table class="menu" cellspacing="0" cellpadding="0">';

            echo '<td class="block_avatar">';
            $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $as['us_b'] . "'"));
            echo UserAvatar($ank, $width, $height);
            echo '</td>';
            echo '<td class="block_content">';

            echo '' . nick($as['us_b']) . ' <span class="time">' . vremja($as['time_up']) . '</span></br>';
            echo '<a class="button" href="' . homeLink() . '/friends/da' . $as['us_b'] . '/' . $as['id'] . '">Принять заявку</a>';

            echo '</td>';
            echo '</table>';
        }

        if ($k_post < 1) {
            echo '<div class="menu_nbr">Заявок нет</div>';
        }

        if ($k_page > 1) {
            echo str(homeLink() . '/friends/bid', $k_page, $page); // Вывод страниц
        }

        break;
    case 'da':

        $fid = abs(intval($_GET['fid']));
        $bid = mfa(dbquery("SELECT * FROM `friends` WHERE `id` = '" . $fid . "' AND `status` = '0'"));

        if ($ank == 0) {
            echo err($title, 'Такого пользователя не существует!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        if ($bid == 0) {
            echo err($title, 'Такой заявки не существует!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        dbquery("INSERT INTO `friends` SET `us_a` = '" . $ank['id'] . "', `us_b` = '" . $user['id'] . "', `status` = '1', `time_up` = '" . time() . "'");
        dbquery("UPDATE `friends` SET `status` = '1' WHERE `id` = '" . $fid . "'");
        ##Оповещаем юзера
        dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $ank['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'добавил Вас в [url=" . homeLink() . "/friends/]друзья[/url]'");

        header('Location: /friends/bid');
        exit;

        break;
    case 'delete':

        if ($ank == 0) {
            echo err($title, 'Такого пользователя не существует!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        $q = mfa(dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $id . "' AND `us_b` = '" . $user['id'] . "' AND `status` = '1'"));

        if ($q == 0) {
            echo err($title, 'У Вас в друзьях нет такого пользователя!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        dbquery("DELETE FROM `friends` WHERE `us_a` = '" . $id . "' AND `us_b` = '" . $user['id'] . "'");
        dbquery("DELETE FROM `friends` WHERE `us_b` = '" . $id . "' AND `us_a` = '" . $user['id'] . "'");

        header('Location: ' . homeLink() . '/friends' . $user['id']);
        exit;

        break;
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
