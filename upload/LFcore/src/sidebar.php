<?
echo '<nav>';
echo '<div class="side_at">';
$forum_kat = mfa(dbquery("SELECT * FROM `forum_kat` ORDER BY `id` ASC"));
if (empty($user['id'])) {
    echo '<div class="s_menu">';
    echo '<center><a class="button" data-bs-toggle="modal" data-bs-target="#login" style="width: -webkit-fill-available;">Войти</a></center>';
    echo '</div>';
} else {
    if (empty($forum_kat['id'])) {
        echo '<span></span>';
    } else {
        echo '<div class="s_menu">';
        echo '<center><a class="button" data-bs-toggle="modal" data-bs-target="#nt" style="width: -webkit-fill-available;">Создать тему</a></center>';
        echo '</div>';
    }
}

//Дополнения
echo '<a class="link side" href="' . homeLink() . '"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar"><i class="fas fa-house" style="margin-right: 5px;"></i></div></span></span><span class="name_kat_bar">Главная</span></a>';
if (empty($user['id'])) {
    echo '<span></span>';
} else {
    echo '<a class="link side" href="' . homeLink() . '/id' . $user['id'] . '"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar"><i class="far fa-user-circle" style="margin-right: 5px;"></i></div></span></span><span class="name_kat_bar">Профиль</span></a>';
}
echo '<a class="link side" href="' . homeLink() . '/newp"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar"><i class="fas fa-newspaper" style="margin-right: 7px;"></i></div></span></span><span class="name_kat_bar">Новые посты</span></a>';
if (empty($user['id'])) {
    echo '<span></span>';
} else {
    echo '<a class="link side" href="' . homeLink() . '/zakl"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar"><i class="far fa-star" style="margin-right: 9px;"></i></div></span></span><span class="name_kat_bar">Закладки</span></a>';
}

echo '</div>';

echo '<div class="side_st">';
$forum_r = dbquery("SELECT * FROM `forum_razdel` ORDER BY `id` ASC");
while ($a = mfa($forum_r)) {
    echo '<div class="node-razdel">';
    echo '<a class="link_razdel" href="' . homeLink() . '/razdel' . $a['id'] . '">' . $a['name'] . '</a>';
    $count_kat = msres(dbquery("SELECT COUNT(*) FROM `forum_kat` WHERE `razdel` = '" . $a['id'] . "'"), 0);
    if ($count_kat < 1) {
        if ($perm['create_kat'] == 1) {
            echo '<a class="link side" href="' . homeLink() . '/forum/new-kat-' . $a['id'] . '"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar"><center><i class="fas fa-plus"></i></senter></div></span></span><span class="name_kat_bar">Добавить категорию</span></a>';
        } else {
            echo '<div class="money_block" style="padding: 5px 10px;"><span class="us_in_block">Категории отсутствуют...</span></div>';
        }
    }

    $forum_k = dbquery("SELECT * FROM `forum_kat` WHERE `razdel` = '" . intval($a['id']) . "' ORDER BY `id` ASC");
    while ($s = mfa($forum_k)) {
        echo '<a class="link side" href="' . homeLink() . '/kat' . intval($s['id']) . '" style="border-bottom: 0px;"><span style="color:#525050;" class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar">';

        if (empty($s['icon'])) {
            echo '<svg id="svg977" viewBox="0 0 6.3499999 6.3500002" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" fill="var(--font-color)"><g id="layer1" transform="translate(0 -290.65)"><path id="path944" d="m9.0039062 2.5175781c-4.4064494-.0000378-8.0019531 3.5955154-8.0019531 8.0019529 0 1.833109.5914272 3.577802 1.6933594 4.933594l-1.6621094 4.701172a1.0001001 1.0001001 0 0 0 1.3066407 1.261719l5.0195312-1.957032a1.0001001 1.0001001 0 0 0 .021484-.0078l2.265625-.929688h4.363282a1.0002331 1.0002331 0 0 0 .01563 0h.996093c4.406451.000076 8.001953-3.595515 8.001954-8.001953 0-4.4064375-3.595503-8.0019907-8.001954-8.0019529h-.996093a1.000252 1.000252 0 0 0 -.01563 0h-4.001954zm0 2.0019531h1.0039058 4.001954a1.000252 1.000252 0 0 0 .01563 0h.996093c3.325557-.0000377 6.001954 2.6744316 6.001954 5.9999998 0 3.325569-2.676397 6.001991-6.001954 6.001953h-.970703a1.0002331 1.0002331 0 0 0 -.04101 0h-4.5625104a1.0001001 1.0001001 0 0 0 -.3769531.07422l-2.4433594 1.003906-2.9882812 1.166016 1.1132812-3.144531a1.0001001 1.0001001 0 0 0 .058594-.433594 1.0001001 1.0001001 0 0 0 0-.002 1.0024193 1.0024193 0 0 0 -.2792971-.671829c-.9648696-1.000857-1.5273438-2.428169-1.5273438-3.994141 0-3.3255682 2.674444-6.0000375 6-5.9999998zm-.0058593 2.4804688a1.000252 1.000252 0 1 0 0 2h6.0019531a1.000252 1.000252 0 1 0 0-2zm-.095703 4a1.0021196 1.0021196 0 0 0 .095703 2.001953h6.0019531a1.0009765 1.0009765 0 0 0 0-2.001953h-6.0019531a1.0001001 1.0001001 0 0 0 -.095703 0z" transform="matrix(.265 0 0 .265 0 290.65)" font-variant-ligatures="normal" font-variant-position="normal" font-variant-caps="normal" font-variant-numeric="normal" font-variant-alternates="normal" font-feature-settings="normal" text-indent="0" text-align="start" text-decoration-line="none" text-decoration-style="solid" text-decoration-color="rgb(0,0,0)" text-transform="none" text-orientation="mixed" white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal" solid-color="rgb(0,0,0)" solid-opacity="1" vector-effect="none" paint-order="normal"></path></g></svg>';
        } else {
            echo '' . html_entity_decode($s['icon'], ENT_QUOTES, 'UTF-8') . '';
        }

        echo '</div></span></span><span class="name_kat_bar">' . $s['name'] . '</span></a>';
    }
    echo '</div>';
}

$count = msres(dbquery("SELECT COUNT(*) FROM `forum_razdel` "), 0);
if (isset($perm['create_razdel'])) {
    if ($count < 1 & $perm['create_razdel'] == 1) {
        echo '<a class="link side" href="' . homeLink() . '/forum/nr"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar" style="display: inline-block; width: 20px;"><center><i class="fas fa-plus"></i></senter></div></span></span><span class="name_kat_bar">Добавить раздел</span></a>';
    }
}
echo '</div>';
echo '</nav>';


if (empty($user['id'])) {
    echo '<span></span>';
} else {
    echo '<div class="side_st1">';

    echo '<b style="font-size: 14px;">Мой баланс: </b><br />';
    echo '<div class="balance"">';

    echo '<b style="font-size: 13px;">';
    echo '<b>' . $user['money_col'] . '</b>';
    echo '</b>';

    echo '<span class="rub" style="color: #8b8b8b;"> ₽</span>';

    echo '</div>';

    echo '</div>';
}

echo '<div class="side_st1">';
echo '<a class="name_rzd" style="font-size: 14px; cursor: pointer;" href="' . homeLink() . '/online"><b>Пользователей в сети:<span class="icon"><span class="num_viz_user"> ' . msres(dbquery("SELECT COUNT(*) FROM `users` WHERE `viz` > '" . (time() - 100) . "'"), 0) . '</span></span></b></a>';

echo '<div class="us_in_block" style="margin-top: 15px; margin-bottom: 5px;">';

$count_us = msres(dbquery("SELECT COUNT(*) FROM `users` WHERE `viz` > '" . (time() - 100) . "'"), 0);
if ($count_us < 1) {
    echo '<span>Нет пользователей в сети...</span>';
}

if (empty($user['max_us'])) $user['max_us'] = 10;
$max = $user['max_us'];
$k_post = msres(dbquery("SELECT COUNT(*) FROM `users` WHERE `viz` > '" . (time() - 100) . "'"), 0);
$k_page = k_page($k_post, $max);
$page = page($k_page);
$start = $max * $page - $max;

$users = dbquery("SELECT * FROM `users` where `viz` > '" . (time() - 100) . "' ORDER BY `viz` DESC LIMIT 10");

while ($ank = mfa($users)) {
    echo '<table class="menu" style="border-bottom: 0px; padding: 0px; margin-bottom: 18px; background: none;" cellspacing="0" cellpadding="0">';
    echo '<td style="width: 10px;">';
    echo UserAvatar($ank, 40, 40);
    echo '</td>';
    echo '<td style="border-right: 100px;">';
    echo '</td>';
    echo '<td class="block_content" style="background: none;">';
    echo '' . nick($ank['id']) . '</br>';
    $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '".$ank['id']."'"), 0);
    echo '<span class="podname" style="color: #888; margin-top: 5px; display: inline-block;">Тем в форуме: <span class="num_t_f">' . $t_forum . '</span></span>';
    echo '</td>';
    echo '</table>';
}
echo '</div>';
echo '</div>';

$major = dbquery("SELECT * FROM `users` WHERE `money_col` = (SELECT MAX(money_col) FROM users) LIMIT 1");
while ($ank = mfa($major)) {
	
	if (empty($ank['stat'])) {
		$maj_status = 'Статус не установлен';
	} else {
		$maj_status = $ank['stat'];
	}
	
    echo '<div class="side_st1">';
    echo '<b style="font-size: 14px;">Меценат форума</b><br />';

    echo '<div class="us_in_block" style="margin-top: 15px; margin-bottom: 5px;">';

    if ($ank['money_col'] < 1) {
        echo '<span>Меценат отсутствует...</span>';
    } else {
        echo '<table class="menu" style="border-bottom: 0px; padding: 0px; margin-bottom: 18px; background: none;" cellspacing="0" cellpadding="0">';
        echo '<td style="width: 10px;">';
        echo UserAvatar($ank, 40, 40);
        echo '<td style="border-right: 100px;">';
        echo '</td>';
        echo '<td class="block_content" style="background: none;">';
        echo '' . nick($ank['id']) . '</br>';
        echo '<span class="podname" style="color: #888; margin-top: 5px; display: inline-block;"><span class="num_t_m">' . $ank['money_col'] . ' ₽</span></span>';
        echo '</td>';
        echo '</table>';
		
        echo '<div class="podst"><div>' . $maj_status . '</div></div>';
    }

    echo '</div>';
    echo '</div>';
}

echo '<div class="side_st1">';

$f_thems = msres(dbquery("SELECT COUNT('forum_tema') FROM forum_tema"), 0);
$f_posts = msres(dbquery("SELECT COUNT('forum_post') FROM forum_post"), 0);
$f_users = msres(dbquery("SELECT COUNT('users') FROM users"), 0);

echo '<b style="font-size: 14px;">Статистика форума </b><br />';
echo '<div class="block_info--gt"">';

echo '<div class="block_stolb_info--gt">';
echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Темы: </span><span class="stolb_info-num-stat-gt">' . $f_thems . '</span></span>';
echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Сообщения: </span><span class="stolb_info-num-stat-gt">' . $f_posts . '</span></span>';
echo '<span class="stolb_info--gt"><span class="stolb_info-stat-gt">Пользователи: </span><span class="stolb_info-num-stat-gt">' . $f_users . '</span></span>';
echo '</div>';

echo '</div>';
echo '</div>';

echo '<div class="cpr">' . $aut . '<br />© 2024 - ' . date('Y') . ' ' . $eng . '</div>';
