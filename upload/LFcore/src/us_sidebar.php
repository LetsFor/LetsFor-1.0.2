<?
$id = abs(intval($_GET['id']));
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));
$perm_ank = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $ank['level_us'] . "'"));

echo '<div class="side_ava">';
echo '<center>';
echo '<img class="sidebar_ava" src="' . homeLink() . '/files/ava/' . $ank['avatar'] . '">';
if ($ank['id'] == $user['id']) {
    if ($prv_us['set_background_user'] != 1 && $prv_us['set_intercolor_user'] != 1) {
        echo '<span></span>';
    } else {
        echo '<a class="but_headbar_rez" data-bs-toggle="modal" data-bs-target="#reset_headbar">Сменить фон</a>';
    }
}
echo '</center>';

if ($ank['level_us'] < 2 & $ank['prev'] == 0) {
    echo '<style>.side_items { display: none; }</style>';
} else {
    echo '<div class="side_items">';
    echo $prus->displayPrefixForUser($ank);
    echo '</div>';
}

if (isset($user['id'])) {
    if ($user['id'] == $ank['id']) {
        echo '<center><a class="button dark" href="' . homeLink() . '/usedit" style="width: -webkit-fill-available; margin-top: 15px;">Редактировать</a></center>';
    } else {
        echo '<center>
		<div class="btn-group" style="margin-top: 15px;">
		<a class="button prm" href="' . homeLink() . '/mes/dialog' . $ank['id'] . '" style="width: -webkit-fill-available;">Сообщение</a>
		<a class="button plm minbtn" href="' . homeLink() . '/perevod' . $ank['id'] . '"><i class="fas fa-dollar" style="line-height: 34px; font-size: 17px;"></i></a>
		</div>
		</center>';

        echo '<center>
		<div class="btn-group" style="margin-top: 5px;">';
        $fri = mfa(dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $user['id'] . "' and `us_b` = '" . $ank['id'] . "'"));
        if ($fri['status'] != 1) {
            echo '<a class="button dark prm" data-bs-toggle="modal" data-bs-target="#add_friend" style="width: -webkit-fill-available;">Добавить в друзья</a>';
        } else {
            echo '<a class="button dark prm" href="' . homeLink() . '/friends/delete' . $ank['id'] . '" style="width: -webkit-fill-available;">Убрать из друзей</a>';
        }
        $ignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `kogo` = '" . $ank['id'] . "'"));
        if ($ignor['ignor'] != 1) {
            echo '<a class="button dark plm minbtn" href="' . homeLink() . '/mes/ignor' . $ank['id'] . '"><i class="fas fa-lock" style="line-height: 34px; font-size: 15px;"></i></a>';
        } else {
            echo '<a class="button dark plm minbtn" href="' . homeLink() . '/mes/ignor_up' . $ank['id'] . '"><i class="fas fa-key" style="line-height: 34px; font-size: 15px;"></i></a>';
        }
        echo '</div></center>';
    }
}

echo '</div>';

//Дополнения
echo '<div class="side_ank">';
echo '<a class="link side" href="' . homeLink() . '"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar" style="display: inline-block; width: 20px;"><center><i class="fas fa-house" style="margin-right: 5px;"></i></center></div></span></span><span class="name_kat_bar"> Главная</span></a>';
if (empty($user['id']) || $ank['id'] == $user['id']) {
    echo '<span></span>';
} else {
    echo '<a class="link side" href="' . homeLink() . '/id' . $user['id'] . '"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar" style="display: inline-block; width: 20px;"><center><i class="far fa-user-circle" style="margin-right: 5px;"></i></center></div></span></span><span class="name_kat_bar"> Профиль</span></a>';
}
echo '<a class="link side" href="' . homeLink() . '/newp"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar" style="display: inline-block; width: 20px;"><center><i class="fas fa-newspaper" style="margin-right: 7px;"></i></center></div></span></span><span class="name_kat_bar">Новые посты</span></a>';
if (empty($user['id'])) {
    echo '<span></span>';
} else {
    echo '<a class="link side" href="' . homeLink() . '/zakl"><span class="icon_s_bar"><span class="icon_mm"><div class="ico_cen_bar" style="display: inline-block; width: 20px;"><center><i class="far fa-star" style="margin-right: 9px;"></i></center></div></span></span><span class="name_kat_bar"> Закладки</span></a>';
}
echo '</div>';

if (empty($user['id'])) {
    echo '<span></span>';
} else {
    echo '<div class="side_ank">';

    echo '<div class="money_block" style="padding: 5px 10px;">';
    echo '<b style="font-size: 14px;">Баланс: </b><br />';
    echo '<div class="balance"">';

    echo '<b style="font-size: 13px;">';
    echo '<b>' . $ank['money_col'] . '</b>';
    echo '</b>';

    echo '<span class="rub" style="color: #8b8b8b;"> ₽</span>';

    echo '</div>';
    echo '</div>';

    echo '</div>';
}

echo '<div class="side_ank">';

echo '<div class="onli_block" style="padding: 5px 10px;">';
echo '<a class="name_rzd" style="font-size: 14px; cursor: pointer;" href="' . homeLink() . '/friends' . $ank['id'] . '"><b>Друзья:<span class="icon"><span class="num_viz_user"> ' . msres(dbquery("SELECT COUNT(*) FROM `friends` WHERE `us_a` = '" . $ank['id'] . "' AND `status` = '1'"), 0) . '</span></span></b></a>';

echo '<div class="us_in_block" style="margin-top: 15px; margin-bottom: 5px;">';

$count_us = msres(dbquery("SELECT COUNT(*) FROM `friends` WHERE `us_a` = '" . $ank['id'] . "' AND `status` = '1'"), 0);
if ($count_us < 1) {
    echo '<span>Нет друзей</span>';
}

$friends = dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $ank['id'] . "' AND `status` = '1' ORDER BY `time_up` DESC LIMIT 10");

while ($a = mfa($friends)) {
	$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['us_b'] . "'"));
    echo '<table class="menu" style="border-bottom: 0px; padding: 0px; margin-bottom: 18px; background: none;" cellspacing="0" cellpadding="0">';
    echo '<td style="width: 10px;">';
    echo UserAvatar($ank, 40, 40);
    echo '</td>';
    echo '<td style="border-right: 100px;">';
    echo '</td>';
    echo '<td class="block_content" style="background: none;">';
    echo '' . nick($ank['id']) . '</br>';
    $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '" . $ank['id'] . "'"), 0);
    echo '<span class="podname" style="color: #888; margin-top: 5px; display: inline-block;">Тем в форуме: <span class="num_t_f">' . $t_forum . '</span></span>';
    echo '</td>';
    echo '</table>';
}
echo '</div>';
echo '</div>';
echo '</div>';
