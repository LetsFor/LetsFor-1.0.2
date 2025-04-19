<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


echo '<title>Все пользователи</title>';

$act = isset($_GET['act']) ? $_GET['act'] : null;

switch ($act) {
    default:
        echo '<div class="title">Все пользователи';
		echo '<div class="dropdown" style="float: right">
		<a class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-ellipsis-h" style="font-size: 20px;"></i></a>
		<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/online">Пользователи в сети</a></li>';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/administration">Администрация</a></li>';
		echo '</ui>';

		echo '</div>';
		echo '</div>';
		
		echo '<div class="menu_cont">';

        $users = dbquery("SELECT * FROM `users` ORDER BY `id` DESC LIMIT 1");

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `users`"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;

        $users = dbquery("SELECT * FROM `users` ORDER BY `id` DESC LIMIT $start, $max");
        while ($ank = mfa($users)) {
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id' . $ank['id'] . '">';
            echo '<td style="width: 65px;">';
            echo UserAvatar($ank, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
            echo '<span class="nick_user" style="pointer-events: none;">' . nick($ank['id']) . '</span></br>';
            $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '".$ank['id']."'"), 0);
            echo 'Постов в форуме: <span class="num-indi">' . $p_forum . '</span></br>';
            $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '".$ank['id']."'"), 0);
            echo 'Тем в форуме: <span class="num-indi">' . $t_forum . '</span>';
            echo '</td>';
            echo '</table>';
        }

        if ($k_page > 1) echo str('/users?', $k_page, $page); // Вывод страниц
		echo '</div>';
		
        break;
    case 'online':

        echo '<div class="title">Онлайн';
		echo '<div class="dropdown" style="float: right">
		<a class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-ellipsis-h" style="font-size: 20px;"></i></a>
		<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/users">Все пользователи</a></li>';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/administration">Администрация</a></li>';
		echo '</ui>';

		echo '</div>';
		echo '</div>';
		echo '<div class="menu_cont">';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `users` WHERE `viz` = '" . time() . "'"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;


        $users = dbquery("SELECT * FROM `users` where `viz` = '" . time() . "' ORDER BY `viz` DESC LIMIT $start, $max");
        while ($ank = mfa($users)) {
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id' . $ank['id'] . '">';
            echo '<td style="width: 65px;">';
            echo UserAvatar($ank, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
            echo '<span class="nick_user" style="pointer-events: none;">' . nick($ank['id']) . '</span></br>';
            $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '".$ank['id']."'"), 0);
            echo 'Постов в форуме: <span class="num-indi">' . $p_forum . '</span></br>';
            $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '".$ank['id']."'"), 0);
            echo 'Тем в форуме: <span class="num-indi">' . $t_forum . '</span>';
            echo '</td>';
            echo '</table>';
        }

        if ($k_page > 1) echo str('/online?', $k_page, $page); // Вывод страниц
		
		echo '</div>';

        break;
    case 'adm':
	
	    echo '<title>Администрация</title>';
        echo '<div class="title">Администрация';
		echo '<div class="dropdown" style="float: right">
		<a class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-ellipsis-h" style="font-size: 20px;"></i></a>
		<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/users">Все пользователи</a></li>';
			echo '<li><a class="dropdown-item" href="' . homeLink() . '/online">Пользователи в сети</a></li>';
		echo '</ui>';

		echo '</div>';
		echo '</div>';
		echo '<div class="menu_cont">';

        if (empty($user['max_us'])) $user['max_us'] = 10;
        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(*) FROM `users` WHERE `level_us` > '1' "), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;

        $users = dbquery("SELECT * FROM `users` where `level_us` > '1' ORDER BY `viz` DESC LIMIT $start, $max");
        while ($ank = mfa($users)) {
            echo '<table class="div-link" role="link" id="cont_tem" cellspacing="0" cellpadding="0" data-href="id' . $ank['id'] . '">';
            echo '<td style="width: 65px;">';
            echo UserAvatar($ank, 50, 50);
            echo '</td>';
            echo '<td class="block_content">';
            echo '<span class="nick_user" style="pointer-events: none;">' . nick($ank['id']) . '</span></br>';
            $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '".$ank['id']."'"), 0);
            echo 'Постов в форуме: <span class="num-indi">' . $p_forum . '</span></br>';
            $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '".$ank['id']."'"), 0);
            echo 'Тем в форуме: <span class="num-indi">' . $t_forum . '</span>';
            echo '</td>';
            echo '</table>';
        }


        if ($k_page > 1) echo str('/administration?', $k_page, $page); // Вывод страниц
		
		echo '</div>';

        break;
}

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
