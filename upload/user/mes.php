<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

$act = isset($_GET['act']) ? $_GET['act'] : null;

switch ($act) {
    default:

        echo '<title>Сообщения</title>';

        echo '<div class="title">Сообщения';
        echo '<div class="dropdown" style="float: right">
		<span class="btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><span class="ficon" style="margin-right: 5px;"><i class="fas fa-ellipsis-h" style="padding-left: 5px; padding-right: 0px; font-size: 20px;"></i></span></span>
		<ul class="dropdown-menu" style="inset: auto 0 auto auto;">';
        echo '<li><a class="dropdown-item" href="' . homeLink() . '/mes/search" style="float: right">Поиск</a></li>';
        echo '<li><a class="dropdown-item" href="' . homeLink() . '/mes/save/" style="float: right">Избранные</a></li>';
        echo '<li><a class="dropdown-item" href="' . homeLink() . '/mes/ignor_list" style="float: right">Черный список</a></li>';
        echo '</ui>
		</div>';
        echo '</div>';

        if (empty($user['max_us'])) $user['max_us'] = 10;

        $max = $user['max_us'];
        $k_post = msres(dbquery("SELECT COUNT(id) FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `del` = '0'"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $dialog = dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `del` = '0' ORDER BY `posl_time` DESC LIMIT $start, $max");
		
		if ($k_post > 0) {
			echo '<div class="menu_cont">';
			while ($d = mfa($dialog)) {
				echo '<table class="div-link" role="link" data-href="' . homeLink() . '/mes/dialog' . $d['kogo'] . '" cellspacing="0" cellpadding="0">';
				
				echo '<td class="block_avatar">';
				$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $d['kogo'] . "'"));
				echo (empty($ank['avatar']) ? '<img class="ava_mes" src="/files/ava/net.png">' : '<img class="ava_mes" src="/files/ava/' . $ank['avatar'] . '">');
				echo '</td>';
				echo '<td class="block_content">';
				
				echo '<div class="name_in-mes--user">' . nick($d['kogo']) . '</div> <span class="time">' . vremja($d['time_up']) . '</span>';
				
				$count = msres(dbquery("SELECT COUNT(id) FROM `message` WHERE `kto` = '" . $user['id'] . "' and `komy` = '" . $d['kogo'] . "' or `kto` = '" . $d['kogo'] . "' and `komy` = '" . $user['id'] . "'"), 0);
				$list = mfa(dbquery("SELECT * FROM `message` WHERE `kto` = '" . $user['id'] . "' and `komy` = '" . $d['kogo'] . "' or `kto` = '" . $d['kogo'] . "' and `komy` = '" . $user['id'] . "' ORDER BY `time_up` DESC LIMIT 1"));
				
				if ($count) {
					echo '<div class="msg_block_norep">' . bb(smile($list['text_col'])) . '';
				} else {
					echo 'Переписка еще не происходила';
				}
				
				if (!empty($list['id']) and $user['id'] != $list['kto'] and $list['readlen'] == 0) echo ' <span style="margin: 0 0 0 10px;" class="icon"><i class="fas fa-chevron-left" title="Не просмотренно"></i></span> ';
				
				echo '</div>';
				echo '</td>';
				echo '</table>';
			}
			echo '</div>';
		}

        if ($k_post < 1) echo '<div class="menu_nb">Список контактов пуст</div>';
        if ($k_page > 1) echo str('' . homeLink() . '/mes?', $k_page, $page); // Вывод страниц
        break;


    case 'del_c':
        $id = abs(intval($_GET['id']));
        $ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

        if ($ank == 0) {
            echo err($title, 'Такого пользователя не существует!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        echo '<div class="title">Удалить диалог</div>';

        if (isset($_GET['da'])) {
            $msg = dbquery("SELECT * FROM `message` WHERE `kto` = '" . $user['id'] . "' and `komy` = '" . $id . "' and `readlen` = '0' or `kto` = '" . $id . "' and `komy` = '" . $user['id'] . "' and `readlen` = '0' ORDER BY `time_up` DESC");

            while ($m = mfa($msg)) {
                if ($user['id'] == $m['komy']) {
                    dbquery("UPDATE `message` SET `readlen` = '1' WHERE `id`='" . $m['id'] . "' limit 1");
                }
            }

            dbquery("DELETE FROM `message` WHERE `kto` = '" . $user['id'] . "' and `komy` = '" . $id . "'");
            dbquery("UPDATE `message_c` SET `del` = '1' WHERE `kogo` = '" . $id . "' AND `kto` = '" . $user['id'] . "' limit 1");
            header('Location: ' . homeLink() . '/mes/');
            exit;
        }

        echo '<div class="menu_nb">Вы действительно хотите удалить диалог с ' . nick($id) . ' ? <br /><br /><a class="button" href="' . homeLink() . '/mes/del_c' . $id . '?da" style="margin-right: 5px;">Удалить</a><a class="button" href="' . homeLink() . '/mes">Отмена</a></div>';
        break;


    case 'dialog':
        $id = abs(intval($_GET['id']));
        $mess = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));

        if (isset($mess['id']) and $user['id'] == $id) {
            echo err($title, 'Ошибка!');
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit;
        }

        $di = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `kogo` = '" . $mess['id'] . "'  LIMIT 1"));

        $ignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $mess['id'] . "' and `kogo` = '" . $user['id'] . "'"));
        $youignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `kogo` = '" . $mess['id'] . "'"));

        echo '<div class="block--info" style="text-align: left"><img class="head_ava_us" src="' . homeLink() . '/files/ava/' . $mess['avatar'] . '" style="width: 40px; height: 40px; margin: -20px 10px 0 0;"></img><span class="user_talk_block" style="display: inline-block; position: relative;">' . nick($mess['id']) . '<br />Был в сети: <span class="time">' . vremja($mess['viz']) . '</span></span><div class="action_teme_us" style="margin: 7px 0 0 0;"><a href="' . homeLink() . '/mes/ignor' . $mess['id'] . '"><span class="ficon"><i class="fas fa-ban"></i></span></a><a href="' . homeLink() . '/mes/del_c' . $mess['id'] . '"><span class="ficon"><i class="fas fa-trash"></i></span></a></div></div>';

        $max = 10000;
        $k_post = msres(dbquery("SELECT COUNT(id) FROM `message` WHERE `kto` = '" . $user['id'] . "' and `komy` = '" . $mess['id'] . "' or `kto` = '" . $mess['id'] . "' and `komy` = '" . $user['id'] . "'"), 0);
        $k_page = k_page($k_post, $max);
        $page = page($k_page);
        $start = $max * $page - $max;
        $msg = dbquery("SELECT * FROM `message` WHERE `kto` = '" . $user['id'] . "' and `komy` = '" . $mess['id'] . "' or `kto` = '" . $mess['id'] . "' and `komy` = '" . $user['id'] . "' ORDER BY `time_up` LIMIT $start, $max");

        echo '<div class="message_block" id="us-messages">';
        while ($m = mfa($msg)) {
			require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/user-message.php');
		}
		echo '<div id="message_count"></div>';
        echo '</div>';
			
        if ($ignor['ignor'] != 1) {
            echo '<form action="" name="message" id="message_us" method="POST" enctype="multipart/form-data">';
            if ($user['form_file'] == 1) {
            }

            echo '<style>
			.content {
				padding: 8px 8px 0 8px;
			}
			</style>';

            echo '<title>Сообщения</title>';

            require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/bbcode.php');
            echo '<div class="menu_tad" style="display: flex;">';
            echo '<textarea id="content" type="pole" placeholder="Введите сообщение..." name="msg" class="amemenu-kamesan" style="margin: 0;"></textarea>';

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
            <button class="drop" href="#" onclick="$('.teg').toggle();return false;">
                <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="23px" viewBox="200 70 700 800" class="icon">
                    <path fill="" d="M572.235 205.282v600.365a30.118 30.118 0 11-60.235 0V205.282L292.382 438.633a28.913 28.913 0 01-42.646 0 33.43 33.43 0 010-45.236l271.058-288.045a28.913 28.913 0 0142.647 0L834.5 393.397a33.43 33.43 0 010 45.176 28.913 28.913 0 01-42.647 0l-219.618-233.23z"></path>
                </svg>
            </button><?
                        ?><button class="drop" type="submit" name="ok">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="30px" viewBox="0 0 28 28" version="1.1">
                    <g stroke="none" stroke-width="1" fill="" fill-rule="evenodd">
                        <g id="ic_fluent_send_28_filled" fill="" fill-rule="nonzero">
                            <path d="M3.78963301,2.77233335 L24.8609339,12.8499121 C25.4837277,13.1477699 25.7471402,13.8941055 25.4492823,14.5168992 C25.326107,14.7744476 25.1184823,14.9820723 24.8609339,15.1052476 L3.78963301,25.1828263 C3.16683929,25.4806842 2.42050372,25.2172716 2.12264586,24.5944779 C1.99321184,24.3238431 1.96542524,24.015685 2.04435886,23.7262618 L4.15190935,15.9983421 C4.204709,15.8047375 4.36814355,15.6614577 4.56699265,15.634447 L14.7775879,14.2474874 C14.8655834,14.2349166 14.938494,14.177091 14.9721837,14.0981464 L14.9897199,14.0353553 C15.0064567,13.9181981 14.9390703,13.8084248 14.8334007,13.7671556 L14.7775879,13.7525126 L4.57894108,12.3655968 C4.38011873,12.3385589 4.21671819,12.1952832 4.16392965,12.0016992 L2.04435886,4.22889788 C1.8627142,3.56286745 2.25538645,2.87569101 2.92141688,2.69404635 C3.21084015,2.61511273 3.51899823,2.64289932 3.78963301,2.77233335 Z"></path>
                        </g>
                    </g>
                </svg>
            </button>
			<?
			echo '</div>';
			echo '</div>';
        } else {
            echo '<div class="noti_mes">' . nick($mess['id']) . ' добавил вас в игнор лист</div>';
        }
        if ($k_post < 1) echo '<div class="menu_nb">Переписка с ' . nick($mess['id']) . ' еще не состоялась</div>';
        if ($k_page > 1) echo str('' . homeLink() . '/mes/dialog' . $mess['id'] . '?', $k_page, $page); // Вывод страниц
?>
<script>
$(document).ready(function() {
    // Создаем MutationObserver для отслеживания изменений в контейнере
    const targetNode = document.getElementById('message_count');
    const config = { childList: true, subtree: false }; // Отслеживаем только добавление новых дочерних элементов

    const callback = function(mutationsList) {
        mutationsList.forEach((mutation) => {
            if (mutation.type === 'childList') {
                // Применяем анимацию к новым элементам
                $(mutation.addedNodes).addClass('animated-comment');
            }
        });
    };

    const observer = new MutationObserver(callback);
    observer.observe(targetNode, config); // Запускаем наблюдение за контейнером

    $('#message_us').submit(function(event) {
        event.preventDefault();

        // Показываем лоадер
        $('#loading').show();

        $.ajax({
            type: 'POST',
            url: '/LFcore/src/mes-process.php?id=' + <?php echo $id; ?>, // Проверяем значение $id
            data: $(this).serialize(),
            success: function(response) {
                // Создаем временный контейнер для обработки ответа
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = response;

                // Удаляем подключенные CSS-стили
                const cssLinks = tempDiv.querySelectorAll('link[rel="stylesheet"]');
                cssLinks.forEach(link => link.remove());

                // Скрываем лоадер
                $('#loading').hide();

                // Добавляем очищенный контент с анимацией
                const newElement = $('<div></div>').html(tempDiv.innerHTML).addClass('animated-comment');
                $('#message_count').append(newElement);

                $.get('/LFcore/src/mes-results.php?id=' + <?php echo $id; ?>, function(data) {
                    // Создаем временный контейнер для обработки ответа
                    const tempDiv2 = document.createElement('div');
                    tempDiv2.innerHTML = data;

                    // Удаляем подключенные CSS-стили
                    const cssLinks2 = tempDiv2.querySelectorAll('link[rel="stylesheet"]');
                    cssLinks2.forEach(link => link.remove());

                    // Добавляем очищенный контент с анимацией
                    const newData = $('<div></div>').html(tempDiv2.innerHTML).addClass('animated-comment');
                    $('#message_count').append(newData);
                });

                $('#message_us')[0].reset(); // Сбрасываем форму
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Скрываем лоадер при ошибке
                $('#loading').hide();
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    const container = $('#us-messages');

    // Прокрутка вниз при загрузке страницы
    container.scrollTop(container.prop('scrollHeight'));

    // Прокрутка вниз при добавлении нового элемента
    const observer = new MutationObserver(() => {
        container.stop().animate({ scrollTop: container.prop('scrollHeight') }, 300); // Плавная прокрутка
    });

    observer.observe(container[0], { childList: true, subtree: true }); // Наблюдаем за добавлением новых элементов
});
</script>
<?
                    break;


                case 'ignor':
                    $id = abs(intval($_GET['id']));
                    $mess = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "' LIMIT 1"));
                    $ig = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `kogo` = '" . $mess['id'] . "' LIMIT 1"));

                    if (isset($mess['id']) and $user['id'] != $mess['id'] and $ig['ignor'] != 1) {
                        echo '<div class="title"><a href="' . homeLink() . '/mes/">Сообщения</a> | Игнорировать  ' . $mess['login'] . '</div>';

                        if (isset($_REQUEST['okda'])) {
                            dbquery("UPDATE `message_c` SET `ignor` = '1' WHERE `kogo` = '" . $mess['id'] . "' and `kto` = '" . $user['id'] . "'");
                            header('Location: ' . homeLink() . '/mes/dialog' . $mess['id'] . '');
                            exit();
                        }

                        echo '<div class="menu_nb">Вы действительно хотите добавить ' . nick($id) . ' в игнор лист? <br /><br /><a class="button" href="' . homeLink() . '/mes/ignor' . $mess['id'] . '?okda" style="margin-right: 5px;">Добавить</a><a class="button" href="' . homeLink() . '/mes">Отмена</a></div>';
                    } else {
                        echo '<div class="title"><a href="' . homeLink() . '/mes/">Сообщения</a> | Ошибка</div>
						<div class="menu_nb"><center><b>Ошибка</b></center></div>';
                        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                        exit();
                    }
                    break;


                case 'ignor_list':
                    echo '<div class="title">Игнор лист</div>';

                    if (empty($user['max_us'])) $user['max_us'] = 100;
                    $max = $user['max_us'];
                    $k_post = msres(dbquery("SELECT COUNT(id) FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `ignor` = '1'"), 0);
                    $k_page = k_page($k_post, $max);
                    $page = page($k_page);
                    $start = $max * $page - $max;
                    $ignor = dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `ignor` = '1' ORDER BY `id` DESC LIMIT $start, $max");

                    while ($i = mfa($ignor)) {
                        echo '<div class="menu_nb">' . nick($i['kogo']) . ' [<a href="' . homeLink() . '/mes/ignor_up' . $i['kogo'] . '">убрать с игнора</a>]</div>';
                    }

                    if ($k_post < 1) echo '<div class="menu_nb">Игнор-лист пуст</div>';
                    if ($k_page > 1) echo str('' . homeLink() . '/mes/ignor_list/?', $k_page, $page); // Вывод страниц
                    break;

                case 'ignor_up':
                    $id = abs(intval($_GET['id']));
                    $youignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = '" . $user['id'] . "' and `kogo` = '" . $id . "'"));

                    if ($youignor['ignor'] == 1) {
                        echo '<div class="title">Убрать с игнор листа</div>';

                        if (isset($_REQUEST['okda'])) {
                            dbquery("UPDATE `message_c` SET `ignor` = '0' WHERE `kogo` = '" . $youignor['kogo'] . "' and `kto` = '" . $user['id'] . "'");
                            header('Location: ' . homeLink() . '/mes/ignor_list/');
                            exit();
                        }

                        echo '<div class="menu_nb">Вы действительно хотите убрать ' . nick($youignor['kogo']) . ' с вашего игнор листа?<br /><br /><a class="button" href="' . homeLink() . '/mes/ignor_up' . $id . '?okda" style="margin-right: 5px;">Убрать</a><a class="button" href="' . homeLink() . '/mes">Отмена</a></div>';
                    } else {
                        echo '<div class="title">Ошибка</div>';
                        echo '<div class="menu_nb">Ошибка,этот пользователь не в игнор листе</div>';
                    }
                    break;

                case 'save_mes':
                    $id = abs(intval($_GET['id']));
                    $save = mfa(dbquery("SELECT * FROM `message` WHERE `id` = '" . $id . "' LIMIT 1"));

                    if (isset($save['id']) and $save['komy'] == $user['id'] or $save['kto'] == $user['id']) {
                        echo '<div class="title"><a href="' . homeLink() . '/mes/">Сообщения</a> | Добавить в избранное </div>';

                        if (isset($_REQUEST['okda'])) {
                            dbquery("INSERT INTO `message_save` SET `uid_col` = '" . $id . "', `kto` = '" . $user['id'] . "', `text_col` = '" . $save['text_col'] . "', `ktoavtor` = '" . $save['kto'] . "', `time_up` = '" . $save['time_up'] . "'");
                            header('Location: ' . homeLink() . '/mes/save/');
                            exit();
                        }

                        echo '<div class="menu_nb">Вы действительно хотите сохранить это письмо?<br /><a href="' . homeLink() . '/mes/save_mes' . $id . '?okda">Сохранить</a></div>';
                    } else {
                        echo '<div class="title"><a href="' . homeLink() . '/mes/">Сообщения</a> | Ошибка</div>
						<div class="menu_nb"><center><b>Такого сообщения не существует!<b></center></div>';
                        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
                        exit();
                    }
                    break;

                case 'save':
                    echo '<div class="title">Избранные сообщения</div>';

                    if (empty($user['max_us'])) $user['max_us'] = 10;
                    $max = $user['max_us'];
                    $k_post = msres(dbquery("SELECT COUNT(id) FROM `message_save` WHERE `kto` = '" . $user['id'] . "'"), 0);
                    $k_page = k_page($k_post, $max);
                    $page = page($k_page);
                    $start = $max * $page - $max;
                    $sa = dbquery("SELECT * FROM `message_save` WHERE `kto` = '" . $user['id'] . "'");

                    while ($s = mfa($sa)) {
                        echo '<div class="menu_nb">Сообщение от: ' . nick($s['ktoavtor']) . ' (' . vremja($s['time_up']) . ')</div><div class="menu_nb">' . bb(smile($s['text_col'])) . '<br />[<a href="' . homeLink() . '/mes/delsave' . $s['id'] . '">убрать с избранных</a>]</div>';
                    }

                    if ($k_post < 1) echo '<div class="menu_nb">Избранных сообщений пока нету</div>';
                    if ($k_page > 1) echo str('' . homeLink() . '/mes/save/?', $k_page, $page); // Вывод страниц
                    break;

                case 'delsave':
                    $id = abs(intval($_GET['id']));
                    $save = mfa(dbquery("SELECT * FROM `message_save` WHERE `id` = '" . $id . "' LIMIT 1"));

                    if (isset($save['id']) and $save['kto'] == $user['id']) {
                        if (isset($_REQUEST['okda'])) {
                            dbquery("DELETE FROM `message_save` WHERE `id` = '" . $id . "'");
                            header('Location: ' . homeLink() . '/mes/save/');
                            exit();
                        }

                        echo '<div class="title">Удалить избранное сообщение</div>';
                        echo '<div class="menu_nb">Вы действительно хотите удалить это сообщение с избранных?<br /><a class="button" href="' . homeLink() . '/mes/delsave' . $id . '?okda">Удалить</a></div>';
                    } else {
                        echo '<div class="title">Удалить избранное сообщение</div>';
                        echo '<div class="menu_nb">Такого сообения не существует!</div>';
                    }
                    break;
            }

            //-----Подключаем низ-----//

            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');

?>