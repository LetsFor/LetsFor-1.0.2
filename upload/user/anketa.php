<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$act = isset($_GET['act']) ? $_GET['act'] : null;
$id = abs(intval($_GET['id']));
$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));
$perm_ank = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $ank['level_us'] . "'"));

echo '<title>' . $ank['login'] . ' -  ' . $LF_info . ' - ' . $LF_name . '</title>';
echo '<meta name="description" content="Профиль пользователя ' . $ank['login'] . '">';

switch ($act) {
    default:

        if ($ank == 0) {
            echo err($title, 'Такого пользователя не существует!');
            $title = 'Профиль';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        require_once ($_SERVER['DOCUMENT_ROOT'] . '/design/element-styles/anketa.php');

        echo '<div class="cont_ank">';

        require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/bg_user.php');

        $ban = mfa(dbquery("SELECT * FROM `ban_list` WHERE `kto` = '" . $id . "' && `time_end` > '" . time() . "' LIMIT 1"));
        if ($ban != 0) {
            echo '<div class="ban">';
            echo '<span class="ban-tag">Пользователь заблокирован</span>';

            echo '<span class="ban-info">';
            echo '<span class="ban-punct">Заблокировал: </span>' . nick($ban['add_ban']) . '<br/>';
            echo '<span class="ban-punct">Причина: </span>' . smile(bb($ban['about'])) . '<br/>';
            echo '<span class="ban-punct">Дата разблокировки: </span>' . date('d.m.Y в H:i', $ban['time_end']) . '';
            echo '</div>';
            echo '</span>';

            echo '<style>
			.ank-buttun--menu {
				display: none;
			}
			</style>';
        }

        echo '<head-ank--block>';

        echo '<div class="headank">';
        echo '<span class="nickname" style="font-size: 15px;"><b>' . nick($ank['id']) . '</b></span> ';

        $prev_user = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $ank['prev'] . "'"));

        if ($ank['viz'] > (time() - 100)) {
            echo '<span style="float:right;"><i class="fas fa-circle" style="color: #228a40; font-size: 10px; margin-right: 7px;"></i>Онлайн</span>';
        } else {
            echo '<span style="float:right;">Был ' . vremja($ank['viz']) . '</span>';
        }
        echo '</div>';

        echo '<div class="mobile_block">';
        echo '<div class="ank_block">';

        echo '<center><div class="menu_nbr" style="padding-bottom: 15px; border-bottom: 0px; background: none; box-shadow: none;">';
        echo '<td style="width: 90px;">';
        echo (empty($ank['avatar']) ? '<img src="files/ava/net.png" style="border-radius: 100px; width: 100px; height: 100px; margin-right: 0px;">' : '<img src="files/ava/' . $ank['avatar'] . '" style="border-radius: 100px; width: 100px; height: 100px;">');
        echo '</td>';
        echo '</div>';
        echo $prus->displayPrefixForUser($ank);
        echo '</center>';

        echo '<center><div class="menu_nbr" style="padding: 10px 15px; max-width: 900px; border-bottom: 0px; border-radius: 10px; background: none;">';
        echo '<div class="ank_status_text">';
        echo (empty($ank['stat']) ? 'Статус не установлен' : '' . bb($ank['stat']) . '');
        echo '</div>';
        echo '</div></center>';

        if (empty($user['id'])) {
            echo '<span></span>';
        } else {
            echo '<center><div class="ank-buttun--menu">';
            if ($user['id'] != $ank['id']) {
                echo '<table style="text-align: center; padding: 12px; border-top: 1px solid var(--all-border-color); border-bottom: 0px; border-radius: 0 0 10px 10px" class="menu" cellspacing="0" cellpadding="0">';
                echo '<td><a class="dev" href="' . homeLink() . '/mes/dialog' . $ank['id'] . '"><i class="far fa-comment" style="margin-right: 7px;"></i>Сообщение</a>';
                $fri = mfa(dbquery("SELECT * FROM `friends` WHERE `us_a` = '" . $user['id'] . "' and `us_b` = '" . $ank['id'] . "'"));
                if ($fri['status'] != 1) {
                    echo '<td><a class="dev" data-bs-toggle="modal" data-bs-target="#add_friend"><i class="far fa-user" style="margin-right: 7px;"></i>В друзья</a></td>';
                } else {
                    echo '<td><a class="dev" href="' . homeLink() . '/friends/delete' . $ank['id'] . '"><i class="far fa-user" style="margin-right: 7px;"></i>Из друзей</a></td>';
                }
                $ignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `kogo` = '" . $ank['id'] . "'"));
                if ($ignor['ignor'] != 1) {
                    echo '<td><a class="dev" href="' . homeLink() . '/mes/ignor' . $ank['id'] . '" style="margin: 0px;"><i class="fas fa-lock" style="margin-right: 7px;"></i>Заблокировать</a></td>';
                } else {
                    echo '<td><a class="dev" href="' . homeLink() . '/mes/ignor_up' . $ank['id'] . '" style="margin: 0px;"><i class="fas fa-unlock" style="margin-right: 7px;"></i>Разблокировать</a></td>';
                }
                echo '</table>';
            }
            echo '</div></center>';
        }

        echo '</div>';

        if ($ank['id'] == $user['id']) {

            if ($prv_us['set_background_user'] != 1 && $prv_us['set_intercolor_user'] != 1) {
                echo '<span></span>';
            } else {
                echo '<a class="but_headbar_rez" data-bs-toggle="modal" data-bs-target="#reset_headbar">Сменить фон</a>';
            }
        }

        $okback = LFS($_POST['okback']);
        $okitems = LFS($_POST['oksetit']);
        if (isset($_REQUEST['okset'])) {
            dbquery("UPDATE `users` SET `background` = '" . $okback . "', `interface_color` = '" . $okitems . "' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
        }


        $count_friends = msres(dbquery("SELECT COUNT(*) FROM `friends` WHERE `us_a` = '" . $ank['id'] . "' AND `status` = '1'"), 0);
        $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '" . $ank['id'] . "'"), 0);
        $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '" . $ank['id']. "'"), 0);
        $like_forum = msres(dbquery("SELECT count(*) as `total` from `forum_like` where `themus` = '" . $ank['id'] . "'"), 0);

        echo '<div class="menu" style="margin-top: 15px; border-radius: var(--all-border-radius) var(--all-border-radius) 0 0;">';

        echo '<div class="balance_user">Баланс: <span class="icon">' . bb($ank['money_col']) . ' ₽</span></div>';
        if (empty($user['id'])) {
            echo '<span></span>';
        } else {
            if ($id != $user['id']) {
                echo '<div class="plus_money">';
                echo '<a class="bot_bal" href="' . homeLink() . '/user/perevod.php?id=' . $ank['id'] . '">Перевести</a>';
                echo '</div>';
            }
        }
        echo '</div>';

        echo '</div>';


        echo '<div class="menu">';

        echo (empty($ank['datareg']) ? '<span></span>' : '<div class="block_init">
		<div class="object_ank_info_blc" style="display: inline-block; width: 150px; float: left;">
		<div class="pod_object" style="display: inline-block; float: left;">
		<div class="text_data_us">Регистрация:</div></div></div>
		
		<div class="object_init" style="display: inline-block;width: 100px;">
		<div class="pod_object" style="display: inline-block; float: left;">
		<div class="info_ank_blc_text">' . vremja($ank['datareg']) . '</div></div></div></div>');


        echo (empty($ank['name']) ? '<span></span>' : '<div class="block_init">
		<div class="object_ank_info_blc">
		<div class="pod_object">
		<div class="text_data_us">Имя:</div></div></div>
		
		<div class="object_init">
		<div class="pod_object">
		<div class="info_ank_blc_text">' . bb($ank['name']) . '</div></div></div></div>');


        echo (empty($ank['strana']) ? '<span></span>' : '<div class="block_init">
		<div class="object_ank_info_blc">
		<div class="pod_object">
		<div class="text_data_us">Страна:</div></div></div>
		
		<div class="object_init">
		<div class="pod_object">
		<div class="info_ank_blc_text">' . bb($ank['strana']) . '</div></div></div></div>');


        echo (empty($ank['gorod']) ? '<span></span>' : '<div class="block_init">
		<div class="object_ank_info_blc">
		<div class="pod_object">
		<div class="text_data_us">Город:</div></div></div>
		
		<div class="object_init">
		<div class="pod_object">
		<div class="info_ank_blc_text">' . bb($ank['gorod']) . '</div></div></div></div>');


        if ($ank['sex'] == 1) {
            echo (empty($ank['sex']) ? '<span></span>' : '<div class="block_init">
			<div class="object_ank_info_blc">
			<div class="pod_object">
			<div class="text_data_us">Пол:</div></div></div>
			
			<div class="object_init">
			<div class="pod_object">
			<div class="info_ank_blc_text">Мужской</div></div></div></div>');
        }

        if ($ank['sex'] == 2) {
            echo (empty($ank['sex']) ? '<span></span>' : '<div class="block_init">
			<div class="object_ank_info_blc">
			<div class="pod_object">
			<div class="text_data_us">Пол:</div></div></div>
			
			<div class="object_init">
			<div class="pod_object">
			<div class="info_ank_blc_text">Женский</div></div></div></div>');
        } else {
            echo '<span></span>';
        }


        echo (empty($ank['url']) ? '<span></span>' : '<div class="block_init">
		<div class="object_ank_info_blc">
		<div class="pod_object">
		<div class="text_data_us">Сайт:</div></div></div>
		
		<div class="object_init">
		<div class="pod_object">
		<div class="info_ank_blc_text">' . bb($ank['url']) . '</div></div></div></div>');

        echo '</span>';


        echo '<div class="anc_function_buttons">';
        echo '<a class="long-lite" href="/them' . $id . '"><span class="farier"><i class="far fa-comment-alt" style="margin: 0 8px 0px 6px; color: #525050; font-size: 15px; display: inline;"></i></span> Темы от ' . $ank['login'] . '</a>';

        if (empty($user['id'])) {
            echo '<span></span>';
        } else {
            echo '<a class="long-lite" href="/reputation' . $id . '"><span class="farier"><i class="far fa-arrow-alt-circle-up" style="margin: 0 8px 0px 6px; color: #525050; font-size: 15px; display: inline;"></i></span> Поднять репутацию</a>';
        }
        echo '</div>';

        echo '</div>';


        echo '<div class="menu" style="text-align:center; border-radius: 0px 0px var(--all-border-radius) var(--all-border-radius); margin-top: 0px; border-bottom: 0px;" class="menu" cellspacing="0" cellpadding="0">';
        echo '<center><div class="tablo-prof-ind" style="overflow-x: scroll; white-space: nowrap;">
		<a class="ank-indikation" href="/them' . $id . '"><span style="color: var(--items-color)">' . $t_forum . '</span></br>темы</a>
		<a class="ank-indikation" href="/reputation' . $id . '"><span style="color: var(--items-color)">' . msres(dbquery('select count(`id`) from `repa_user` where `komy` = "' . $ank['id'] . '" and `repa` = "+"'), 0) . '</span></br>репутация</a>
		<a class="ank-indikation"><span style="color: var(--items-color)">' . $like_forum . '</span></br>лайки</a>
		<a class="ank-indikation" href="/post' . $id . '"><span style="color: var(--items-color)">' . $p_forum . '</span></br>ответы</a>
		<a class="ank-indikation" href="/friends' . $id . '"><span style="color: var(--items-color)">' . $count_friends . '</span></br>друзья</a>
		</div></center>';

        echo '</div>';

        echo '</div>';


        /*** Стена в профиле ***/

        echo '<div class="stena">';
        if (isset($_REQUEST['ok'])) {

            $msg = LFS($_POST['msg']);
            if (empty($msg)) {
                echo err('Введите сообщение!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            if (mb_strlen($msg) < 3) {
                echo err('Введите сообщение минимум 3 символа!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            $ttte = mfar(dbquery('select * from `stena` where `avtor` = "' . $user['id'] . '" and `msg` = "' . $msg . '"'));
            if ($ttte != 0) {
                echo err('Вы такой пост уже писали!');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                exit;
            }

            $tim = dbquery("SELECT * FROM `stena` WHERE `avtor` = '" . $user['id'] . "' ORDER BY `time_up` DESC");
            while ($ncm2 = mfa($tim)) {
                $news_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `stena` "));
                $ncm_timeout = $ncm2['time_up'];
                if ((time() - $ncm_timeout) < $news_antispam['stena']) {
                    echo err('Пишите не чаще чем раз в ' . $news_antispam['stena'] . ' секунд!');
                    require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                    exit;
                }
            }
            dbquery("INSERT INTO `stena` SET `msg` = '" . $msg . "', `avtor` = '" . $user['id'] . "', `ukogo` = '" . $id . "', `time_up` = '" . time() . "'");

            if ($user['id'] != $ank['id']) {
                dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $ank['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'написал у вас на [url=" . homeLink() . "/id" . $anks['id'] . "]стене[/url]'");
            }
        }

        if (empty($user['id'])) {
            echo '<span></span>';
        } else {
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/bbcode.php');
            echo '<div class="menu_tad">';

            echo '<form action="" name="message" method="POST" style="display: flex; align-items: center;">';
            echo UserAvatar($user, 36, 36);

            echo '<textarea style="margin-top: 2px" id="content" type="pole" placeholder="Создать запись..." name="msg" class="amemenu-kamesan" style="border: 1px solid #303030; background: none;" required></textarea>';
            echo '<div class="block-icon">';

            echo '<div class="dropdown">';
            echo '<a class="drop btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true">
			<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="25px" viewBox="0 0 24 24" fill="none">
			<path d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16" stroke="" stroke-width="1.5" stroke-linecap="round" fill="none"></path>
			<path d="M16 10.5C16 11.3284 15.5523 12 15 12C14.4477 12 14 11.3284 14 10.5C14 9.67157 14.4477 9 15 9C15.5523 9 16 9.67157 16 10.5Z" stroke="none"></path>
			<ellipse cx="9" cy="10.5" rx="1" ry="1.5" stroke="none"></ellipse>
			<path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="" stroke-width="1.5" stroke-linecap="round" fill="none"></path>
			</svg>
			</a>
			<ul class="dropdown-menu" style="inset: auto auto 0 0;width: 280px;height: 300px;transform: translate( -199px, -46px);padding: 3px;">';
            echo '<div class="box" id="myBox">';
            echo '<div class="smile-block" style="padding: 9px">';
            $sm_p = dbquery("SELECT * FROM `smile_p` ORDER BY `id` ASC");
            while ($ap = mfa($sm_p)) {
                echo '<a style="font-weight: bold; font-size: 14px; color: #949494; border-bottom: 0px; padding: 10px 7px; pointer-events: none; display: block;">' . $ap['name'] . '</a>';

                echo '<div class="smiles">';
                $sm_s = dbquery("SELECT * FROM `smile` WHERE `papka` = '" . intval($ap['id']) . "' ORDER BY `id`");
                while ($s = mfa($sm_s)) {
                    $sql_papka = "SELECT name FROM smile_p WHERE id = '" . $s['papka'] . "'";
                    $result_papka = dbquery($sql_papka);
                    $row_papka = mfa($result_papka);
                    echo '<a class="smil" href="javascript:void(0);" onclick="DoSmilie(\' ' . $s['name'] . ' \');">
					<img src="/files/smile/' . $row_papka['name'] . '/' . $s['icon'] . '" style="margin: 0px; padding: 5px; width: 30px; height: 30px;">
					</a>';
                }
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</ui>';
            echo '</div>';
			
			?>
			<button class="drop" href="#" onclick="$('.teg').toggle();return false;"><svg xmlns="http://www.w3.org/2000/svg" width="25px" height="23px" viewBox="200 70 700 800" class="icon">
                    <path fill="" d="M572.235 205.282v600.365a30.118 30.118 0 11-60.235 0V205.282L292.382 438.633a28.913 28.913 0 01-42.646 0 33.43 33.43 0 010-45.236l271.058-288.045a28.913 28.913 0 0142.647 0L834.5 393.397a33.43 33.43 0 010 45.176 28.913 28.913 0 01-42.647 0l-219.618-233.23z"></path>
                </svg></button><?
                                ?><button class="drop" type="submit" name="ok"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="30px" viewBox="0 0 28 28" version="1.1">
                    <g stroke="none" stroke-width="1" fill="" fill-rule="evenodd">
                        <g id="ic_fluent_send_28_filled" fill="" fill-rule="nonzero">
                            <path d="M3.78963301,2.77233335 L24.8609339,12.8499121 C25.4837277,13.1477699 25.7471402,13.8941055 25.4492823,14.5168992 C25.326107,14.7744476 25.1184823,14.9820723 24.8609339,15.1052476 L3.78963301,25.1828263 C3.16683929,25.4806842 2.42050372,25.2172716 2.12264586,24.5944779 C1.99321184,24.3238431 1.96542524,24.015685 2.04435886,23.7262618 L4.15190935,15.9983421 C4.204709,15.8047375 4.36814355,15.6614577 4.56699265,15.634447 L14.7775879,14.2474874 C14.8655834,14.2349166 14.938494,14.177091 14.9721837,14.0981464 L14.9897199,14.0353553 C15.0064567,13.9181981 14.9390703,13.8084248 14.8334007,13.7671556 L14.7775879,13.7525126 L4.57894108,12.3655968 C4.38011873,12.3385589 4.21671819,12.1952832 4.16392965,12.0016992 L2.04435886,4.22889788 C1.8627142,3.56286745 2.25538645,2.87569101 2.92141688,2.69404635 C3.21084015,2.61511273 3.51899823,2.64289932 3.78963301,2.77233335 Z"></path>
                        </g>
                    </g>
                </svg></button>
				<?
                                echo '</div>';
                                echo '</form>';

                                echo '</div>';
            }


                            if (empty($user['max_us'])) $user['max_us'] = 10;
                            $max = $user['max_us'];
                            $k_post = msres(dbquery("SELECT COUNT(*) FROM `stena` WHERE `ukogo` = '" . $id . "'"), 0);
                            $k_page = k_page($k_post, $max);
                            $page = page($k_page);
                            $start = $max * $page - $max;

                            $stena = dbquery("SELECT * FROM `stena` WHERE `ukogo` = '" . $id . "' ORDER BY `time_up` DESC LIMIT $start, $max");
                            while ($st = mfa($stena)) {

                                echo '<div class="ms_block">';
                                echo '<table class="post_it_is" cellspacing="0" cellpadding="0">';

                                echo '<td class="block_content_us">';

                                echo '<div class="menu_nbr" style="background: none; padding: 0px 0px 10px 0px;">';

                                $ank_st = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $st['avtor'] . "'"));
                                echo '<div class="head_post">';
                                echo UserAvatar($ank_st, $width, $height);
                                echo '<span class="ava_init_block;" style="margin-left: 5px; position: relative; display: inline-block; margin-bottom: -10px;">' . nick($st['avtor']) . ' <br /> <span class="time" style="display: inline-block; margin-left: 0px; margin-top: 4px;">' . vremja($st['time_up']) . '</span></span>';
                                echo '</div>';

                                if ($ank['id'] == $user['id'] or $perm['edit_users'] == 1 or $perm['edit_post'] == 1) {
                                    echo '<div class="dropdown" style="float: right">
									<span class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></span>
									<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
                                    if ($ank['id'] == $user['id'] or $perm['edit_users'] == 1 or $perm['edit_post'] == 1) {
                                        echo ' <li><a class="dropdown-item" style="float: right;" href="' . homeLink() . '/delmsg_' . $st['id'] . '"><span class="fontawesome_in_menu"><i class="fas fa-trash"></i><span class="text_in_menu">Удалить</span></span></a></li>';
                                    }
                                    echo '</ui></div>';
                                } else {
                                    echo '<span></span>';
                                }

                                echo '</div>';

                                echo '<div class="block_msg" style="margin-top: 5px;">' . smile(bb($st['msg'])) . '</div>';

                                echo '</td>';
                                echo '</table>';

                                echo '</div>';
                            }

                            if ($k_post < 1) {
                                echo '<div class="st_title"><center>Постов нет</center></div>';
                            }

                            echo '</div>';


                            /*** Функции для админа ***/
							if (isset($user['id'])) {
							echo '<div class="adm_block" style="margin-top: 15px;">';
                            if ($perm['edit_users'] > 0) {
                                echo '<div class="menu_nb" style="font-size: 12px;">
								» IP: ' . $ank['ip'] . '
								</div>';


                                if ($perm['ban_users'] > 0) {
                                    if ($ban != 0) {
                                        echo '<a class="link" href="' . homeLink() . '/admin/ban/list/updateban' . $id . '"><span class="icon"><i class="fas fa-shield-alt"></i></span>Разблокировать</a>';
                                    } else {
                                        echo '<a class="link" href="' . homeLink() . '/admin/ban/list/addban' . $id . '"><span class="icon"><i class="fas fa-shield-alt"></i></span>Заблокировать</a>';
                                    }
                                } else {
                                    echo '<span></span>';
                                }

                                if ($perm['edit_users'] > 0) {
                                    echo '<a class="link" href="' . homeLink() . '/admin/up_us_' . $id . '"><span class="icon"><i class="fas fa-edit"></i></span>Редактировать</a>';
                                } else {
                                    echo '<span></span>';
                                }
                            }
							if($ank['id'] != $user['id']) {
								echo '<a class="link" href="' . homeLink() . '/complaint"><span class="icon"><i class="fas fa-flag"></i></span>Пожаловаться</a>';
							}
                            echo '</div>';
							}
							echo '</div>';

                            break;

                            ##СТЕНА

                        case 'stena':

                            if ($ank == 0) {
                                echo err($title, 'Такого пользователя не существует!');
                                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                exit;
                            }

                            echo '<div class="title"><a href="' . homeLink() . '/id' . $id . '">Анкета ' . $ank['login'] . '</a> > Стена</div>';

                            if (isset($_REQUEST['ok'])) {

                                $msg = LFS($_POST['msg']);
                                if (empty($msg)) {
                                    echo err('Введите сообщение!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                if (mb_strlen($msg) < 3) {
                                    echo err('Введите сообщение минимум 3 символа!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                $ttte = mfar(dbquery('select * from `stena` where `avtor` = "' . $user['id'] . '" and `msg` = "' . $msg . '"'));
                                if ($ttte != 0) {
                                    echo err('Вы такой пост уже писали!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                $tim = dbquery("SELECT * FROM `stena` WHERE `avtor` = '" . $user['id'] . "' ORDER BY `time_up` DESC");
                                while ($ncm2 = mfa($tim)) {
                                    $news_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `stena` "));
                                    $ncm_timeout = $ncm2['time_up'];
                                    if ((time() - $ncm_timeout) < $news_antispam['stena']) {
                                        echo err('Пишите не чаще чем раз в ' . $news_antispam['stena'] . ' секунд!');
                                        require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                        exit;
                                    }
                                }
                                dbquery("INSERT INTO `stena` SET `msg` = '" . $msg . "', `avtor` = '" . $user['id'] . "', `ukogo` = '" . $id . "', `time_up` = '" . time() . "'");

                                if ($user['id'] != $ank['id']) {
                                    dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $ank['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'написал у вас на [url=" . homeLink() . "/id" . $ank['id'] . "?selection=top]стене[/url]'");
                                }

                                header('Location: ' . homeLink() . '/stena_id_' . $id . '');
                            }

                            echo '<div class="menu_nbr">
							<form action="" name="message" method="POST">
							<textarea name="msg" placeholder="Введите сообщение..."></textarea><br />
							<input type="submit" name="ok" value="Написать" />
							</form></div>';

                            if (empty($user['max_us'])) $user['max_us'] = 10;
                            $max = $user['max_us'];
                            $k_post = msres(dbquery("SELECT COUNT(*) FROM `stena` WHERE `ukogo` = '" . $id . "'"), 0);
                            $k_page = k_page($k_post, $max);
                            $page = page($k_page);
                            $start = $max * $page - $max;

                            $stena = dbquery("SELECT * FROM `stena` WHERE `ukogo` = '" . $id . "' ORDER BY `time_up` DESC LIMIT $start, $max");
                            while ($st = mfa($stena)) {

                                echo '<table class="menu_nbr" cellspacing="0" cellpadding="0">';

                                echo '<td class="block_avatar">';
                                $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $st['avtor'] . "'"));
                                echo UserAvatar($ank, $width, $height);
                                echo '</td>';
                                echo '<td class="block_content">';

                                echo '' . nick($st['avtor']) . ' <span class="time">' . vremja($st['time_up']) . '</span>';
                                if ($id == $user['id'] or $perm['edit_users'] > 0) {
                                    echo '[<a href="' . homeLink() . '/delmsg_' . $st['id'] . '">удалить</a>]';
                                }
                                echo '<div class="block_msg">' . smile(bb($st['msg'])) . '</div>';

                                echo '</td>';
                                echo '</table>';
                            }

                            if ($k_post < 1) {
                                echo '<div class="menu_nbr">Сообщений нет</div>';
                            }


                            break;
                        case 'delmsg':

                            $id = abs(intval($_GET['id']));
                            $stenka = mfa(dbquery("SELECT * FROM `stena` WHERE `id` = '" . $id . "'"));
                            $anks = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $stenka['ukogo'] . "'"));

                            if ($stenka == 0) {
                                echo err($title, 'Такого комментария не существует!');
                                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                exit;
                            }

                            if ($anks['id'] == $user['id'] or $perm['edit_users'] > 0) {
                                dbquery("DELETE FROM `stena` WHERE `id` = '" . $id . "'");
                                header('Location: ' . homeLink() . '/id' . $anks['id'] . '');
                                exit();
                            } else {
                                header('Location: ' . homeLink() . '/id' . $anks['id'] . '');
                                exit();
                            }

                            break;

                            ##РЕПУТАЦИЯ

                        case 'repa':

                            echo '<div class="func-rep">';

                            if ($ank == 0) {
                                echo err($title, 'Такого пользователя не существует!');
                                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                exit;
                            }

                            echo '<div class="menu_nbr"><a href="' . homeLink() . '/id' . $id . '">Анкета ' . $ank['login'] . '</a> | Репутация</div>';

                            if (isset($_REQUEST['ok'])) {

                                $text = LFS($_POST['msg']);
                                $repa = abs(intval($_POST['repa']));

                                if ($user['id'] == $ank['id']) {
                                    echo err('Вы не можете изменять себе репутацию!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                if (empty($text)) {
                                    echo err('Введите текст!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                if ($repa != '0' && $repa != '1') {
                                    echo err('Можно ставить только + или -');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                if (mb_strlen($text, 'UTF-8') < 3) {
                                    echo err('Введите сообщение минимум 3 символа!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }

                                $sql = mfar(dbquery('select * from `repa_user` where `kto` = "' . $user['id'] . '" and `text_col` = "' . $text . '"'));
                                if ($sql != 0) {
                                    echo err('Ошибка!');
                                    require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                    exit;
                                }
                                if ($repa == '0') $repa = '+';
                                elseif ($repa == '1') $repa = '-';

                                $tim = dbquery("SELECT * FROM `repa_user` WHERE `komy` = '" . $id . "' ORDER BY `time_up` DESC");
                                while ($ncm2 = mfa($tim)) {
                                    $news_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `repa` "));
                                    $ncm_timeout = $ncm2['time_up'];
                                    $vremja = 0 * $news_antispam['repa'];
                                    if ((time() - $ncm_timeout) < $vremja) {
                                        echo err('Репутацию можно изменять раз в ' . $news_antispam['repa'] . ' Минут! ');
                                        require ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                        exit;
                                    }
                                }

                                $settings = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
                                dbquery("INSERT INTO `repa_user` SET `text_col` = '" . $text . "', `kto` = '" . $user['id'] . "', `komy` = '" . $id . "', `time_up` = '" . time() . "', `repa` = '" . $repa . "'");

                                if ($repa == '+') {
                                    ##добавляем юзеру рейтинг
                                    dbquery("UPDATE `users` SET `rating` = '" . ($user['rating'] + 0.05) . "' WHERE `id` = '" . $id . "' LIMIT 1");
                                    ##Оповещаем юзера
                                    dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $ank['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'повысил вашу репутацию'");
                                } elseif ($repa == '-') {
                                    ##отнимаем юзеру рейтинг
                                    dbquery("UPDATE `users` SET `rating` = '" . ($user['rating'] - 0.02) . "' WHERE `id` = '" . $id . "' LIMIT 1");
                                    ##Оповещаем юзера
                                    dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = '" . time() . "', `komy` = '" . $ank['id'] . "', `kto` = '" . $user['id'] . "', `text_col` = 'понизил вашу репутацию'");
                                }

                                header('Location: ' . homeLink() . '/reputation' . $id . '');
                            }

                            if ($user['id'] != $ank['id']) {

                                echo '<div class="menu_nbr"><form action="" name="message" method="POST">
								<center><textarea name="msg" placeholder="Введите сообщение..."></textarea></center>
								<div class="gt-select"><select name="repa" style=" margin-right: 0px; margin-left: 1px;">
								<option value="0">Плюс</option>
								<option value="1">Минус</option>
								</select></div>
								<center><input type="submit" name="ok" value="Изменить репутацию" style="width: 180px;" /></center>
								</form></div>';

                                echo '<div class="menu_nbr"><center>Обязательно указывайте за что вы изменяете репутацию данного юзера</center></div>';
                            }

                            if (empty($user['max_us'])) $user['max_us'] = 10;
                            $max = $user['max_us'];
                            $k_post = msres(dbquery("SELECT COUNT(*) FROM `repa_user` WHERE `komy` = '" . $id . "'"), 0);
                            $k_page = k_page($k_post, $max);
                            $page = page($k_page);
                            $start = $max * $page - $max;

                            $repa = dbquery("SELECT * FROM `repa_user` WHERE `komy` = '" . $id . "' ORDER BY `time_up` DESC LIMIT $start, $max");
                            while ($a = mfa($repa)) {

                                echo '<div class="ms_r_block">';
                                echo '<table class="menu_nbr" cellspacing="0" cellpadding="0">';

                                echo '<td class="block_avatar">';
                                $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $a['kto'] . "'"));
                                echo UserAvatar($ank, $width, $height);
                                echo '</td>';
                                echo '<td class="block_content">';

                                echo '' . nick($a['kto']) . ' <span class="time">' . vremja($a['time_up']) . '</span>';
                                if ($perm['edit_users'] > 0) {

                                    echo '<div class="dropdown" style="float: right">
									<span class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></span>
									<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
                                    echo ' <li><a class="dropdown-item" href="' . homeLink() . '/del_repa_' . $a['id'] . '" style="float: right">Удалить</a></li>';
                                }
                                echo '</ui></div>';

                                if ($a['repa'] == '+') {
                                    echo '<div class="repa_block_plus">' . smile(bb($a['text_col'])) . '</div>';
                                } else {
                                    echo '<div class="repa_block_minus">' . smile(bb($a['text_col'])) . '</div>';
                                }

                                echo '</td>';
                                echo '</table>';

                                echo '</div>';
                            }

                            if ($k_post < 1) {
                                echo '<div class="menu_nb"><center>Репутацию не изменяли</center></div>';
                            }

                            if ($k_page > 1) {
                                echo str('reputation' . $id . '?', $k_page, $page); // Вывод страниц
                            }

                            break;
                        case 'del_repa':

                            $id = abs(intval($_GET['id']));
                            $repa = mfa(dbquery("SELECT * FROM `repa_user` WHERE `id` = '" . $id . "'"));
                            $anks = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $repa['komy'] . "'"));

                            if ($repa == 0) {
                                echo err($title, 'Данный ID в базе не найден!');
                                require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                                exit;
                            }

                            if ($perm['edit_users'] > 0) {
                                dbquery("DELETE FROM `repa_user` WHERE `id` = '" . $id . "'");
                                header('Location: ' . homeLink() . '/reputation' . $anks['id'] . '');
                                exit();
                            } else {
                                header('Location: ' . homeLink() . '/reputation' . $anks['id'] . '');
                                exit();
                            }

                            echo '</div>';

                            break;
                    }

                    //-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>