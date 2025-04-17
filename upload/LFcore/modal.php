<?
echo '<div class="modal fade" id="exit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Выход</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
          <span>Вы действительно хотите покинуть сайт?</span>
        </div>
        <div class="modal-footer">
          <a class="button" style="float: right; margin-left: 5px;" href="' . homeLink() . '/exit.php?okda">Выйти</a>
          <a class="button" style="float: right;" data-bs-dismiss="modal">Отмена</a>
        </div>
      </div>
    </div>
  </div>';

echo '<div class="modal fade" id="help" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Помощь</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
          <span>Если у вас появились вопросы, связаться с нами вы можете при помощи следующих сервисов:</span>
        </div>
        <div class="modal-footer">
          <a class="link" href="' . $LF_help_vk . '" style="margin: 0px;"><span class="icon"><i class="fab fa-vk"></i></span> Вконтакте</a>
		  <a class="link" href="' . $LF_help_tg . '" style="margin: 0px;"><span class="icon"><i class="fab fa-telegram"></i></span> Телеграм</a>
		  <a class="link" href="mailto:' . $LF_help_mail . '" style="margin: 0px;"><span class="icon"><i class="fas fa-envelope-open"></i></span> Почта</a>
		   <div class="drop-bottom-link">
	        <center><a class="drag_menu_name" href="' . homeLink() . '/privacy">Политика конфиденциальности</a></center>
	       </div>
        </div>
      </div>
    </div>
  </div>';

$id = abs(intval($_GET['id']));
$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
echo '<div class="modal fade" id="delete_them" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Удаление темы</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
          <span>Вы действительно хотите удалить тему ' . $forum_t['name'] . '?</span>
        </div>
        <div class="modal-footer">
          <a class="button" href="' . homeLink() . '/forum/tema_del' . $id . '">Да</a>
	      <a class="button" data-bs-dismiss="modal">Отмена</a>
        </div>
      </div>
    </div>
  </div>';

if ($prv_us['set_background_user'] != 1 && $prv_us['set-background-head-user'] != 1) {
  echo '<div class="modal fade" id="reset_headbar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Упс...</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
          <span>К сожалению в данный момент этот раздел вам не доступен. Преобретите соответствующее повышение чтобы воспользоваться сменой фона.</span>
        </div>
      </div>
    </div>
  </div>';
} else {

  echo '<div class="modal fade" id="reset_headbar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Смена фона</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
          <form name="form" action="/id' . $user['id'] . '" method="post">';
  if ($prv_us['set_background_user'] == 1) {
    echo '<div class="text-in-modal" style="margin-left: 5px;">Фон</div>
		  <center><input type="text" name="okback" style="margin-top: 5px; margin-bottom: 0px; border-bottom: 0px;" value="' . $user['background'] . '" placeholder="URL картинки..." /></center><br />';
  }
  if ($prv_us['set_intercolor_user'] == 1) {
    echo '<div class="text-in-modal" style="margin-left: 5px;">Акцент</div>
		  <center><input type="text" name="oksetit" style="margin-top: 5px; margin-bottom: 0px; border-bottom: 0px;" value="' . $user['interface_color'] . '" placeholder="#000" /></center><br />';
  }
  echo '<input type="submit" name="okset" value="Сохранить" style="margin: 0 5px 0 0;margin-bottom: 0px;border-bottom: 0px;width: auto;" />
		  <a class="button" data-bs-dismiss="modal">Отмена</a>
          </form>
          </div>
      </div>
    </div>
  </div>';
}

$ank = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));
echo '<div class="modal fade" id="add_friend" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Добавить в друзья?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
          <span>Вы действительно хотите добавить ' . $ank['login'] . ' в друзья ?</span>
        </div>
        <div class="modal-footer">
          <a class="button" href="' . homeLink() . '/friends/add' . $id . '?da">Да</a>
	      <a class="button" data-bs-dismiss="modal">Отмена</a>
        </div>
      </div>
    </div>
  </div>';
  



echo '<div class="modal fade" id="nt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
echo '<div class="modal-dialog" style="max-width: 1000px;">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<h5 class="modal-title" id="exampleModalLabel">Выберите раздел</h5>';
echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>';
echo '</div>';
echo '<div class="modal-body">';


echo '<div class="tems" style="display: block;">';
echo '<div class="new_tem_menu" style="justify-content: space-between; flex-wrap: wrap;">';

$forum_r = dbquery("SELECT * FROM `forum_razdel` ORDER BY `id` ASC");
while ($a = mfa($forum_r)) {
  echo '<div class="razdel-nt">';
  echo '<a class="r_name" style="font-weight: bold; font-size: 14px; color: #949494; border-bottom: 0px; border-radius: 7px; padding: 7px; pointer-events: none; display: flex; max-width: 500px; margin-top: 10px; width: 220px;">' . $a['name'] . '</a>';

  $forum_k = dbquery("SELECT * FROM `forum_kat` WHERE `razdel` = '" . intval($a['id']) . "' ORDER BY `id` LIMIT 30");
  while ($s = mfa($forum_k)) {
    echo '<a class="link side" href="' . homeLink() . '/new-them-' . intval($s['id']) . '"><span style="color:#525050;" class="icon_s_bar"><span class="icon_mm"><span class="ico_cen_bar">';

    if (!$s['icon']) {
      echo '<center><i class="far fa-message"></i></center>';
    } else {
      echo '<center>' . html_entity_decode($s['icon'], ENT_QUOTES, 'UTF-8') . '</center>';
    }

    echo '</span></span></span><span class="name_kat_bar">' . $s['name'] . '</span></a>';
  }
  echo '</div>';
}
echo '</div>';


echo '</div>';
echo '<div class="modal-footer">';
echo '<span></span>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';


echo '<div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" style="max-width: 330px;">
<div class="modal-content">';
echo '<div class="login" style="padding: 40px 30px;">';

echo '<center><div class="icon_window">';
CoreLogo();
echo '</div></center><br />';
echo '<center><div class="name_window">Авторизация</div></center>';
echo '<div class="menu_nb" style="padding-bottom: 0px;">';
echo '<form action="' . homeLink() . '/login/?act=true" method="POST">
<input placeholder="Логин" type="text" name="login" maxlength="20" autocomplete="login"/>
<div class="password-container">
<input placeholder="Пароль" type="password" name="pass" maxlength="25" id="password" autocomplete="pass"/><i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
</div><br />
<center><input type="submit" value="Войти" style="margin-left: 3px; font-color: #d2d2d2; width: 175px; margin-top: 10px; margin-bottom: 0px; border-bottom: 0px;"></center><br />
<center><a class="pass_rec_but" href="' . homeLink() . '/repass" style="color: #c33929;">Забыли пароль</a></center><br />';


if (isset($auth_vk['client_id'])) {
  echo '<center><a href="https://oauth.vk.com/authorize?client_id=' . $auth_vk['client_id'] . '&display=page&redirect_uri=' . homeLink() . '/auth/redvk.php&scope=email&response_type=code&v=5.131"><img src="' . homeLink() . '/images/vk.png" style="width: 30px; margin-top: 30px;"></a></center>';
}

echo '</form>';
echo '</div>';

echo '</div>';
echo '<center><a class="reg-link" href="' . homeLink() . '/registr">Создать аккаунт</a></center>';
echo '</div>
</div>
</div>';


echo '<div class="modal fade" id="registr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" style="max-width: 330px;">
<div class="modal-content">';
echo '<div class="login" style="padding: 40px 30px;">';

echo '<center><div class="icon_window">';
CoreLogo();
echo '</div></center><br />';
echo '<center><div class="name_window">Регистрация</div></center>';

echo '<div class="menu_nb" style="padding-bottom: 0px;">';
echo '<form action="' . homeLink() . '/registr" method="POST">
<input placeholder="Логин" type="text" name="login" maxlength="13" /><br />
<input placeholder="Email" type="text" name="email" maxlength="40" /><br />
<input placeholder="Пароль" type="password" name="pass" maxlength="25" /><br />
<input placeholder="Повторите пароль" type="password" name="r_pass" maxlength="25" /><br /><br />
<center><input type="submit" name="reg" value="Создать аккаунт" style="margin-left: 3px; font-color: #d2d2d2; width: 175px; margin-top: 10px; margin-bottom: 0px; border-bottom: 0px;" /></center>
</form>';
echo '</div>';

echo '</div>';
echo '</div>
</div>
</div>';


echo '<div class="modal fade" id="new_kat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
echo '<div class="modal-dialog">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<h5 class="modal-title" id="exampleModalLabel">Выберите раздел</h5>';
echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>';
echo '</div>';
echo '<div class="modal-body">';

$forum_r = dbquery("SELECT * FROM `forum_razdel` ORDER BY `id` ASC");
while ($a = mfa($forum_r)) {
  echo '<div class="razdel-nt">';
  echo '<div class="div-link" role="link" id="cont_tem" data-href="' . homeLink() . '/forum/new-kat-' . $a['id'] . '"><span class="icon" style="margin-right: 10px;"><i class="fas fa-bars"></i></span>' . $a['name'] . '</div>';
  echo '</div>';
}

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';


echo '<div class="modal fade" id="version" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" style="max-width: 235px;">
<div class="modal-content" style="padding: 10px 0;">';

echo '<div class="menu_nb"><center><img src="' . homeLink() . '/admin/images/logo.png" style="width: 80px; height 80px;"></center></div>';
echo '<center><img src="' . homeLink() . '/admin/images/full-logo.png" style="width: 130px; height 80px;"></center>';
echo '<center><div class="menu_nb"><div class="adm_info_block" style="display: inline-block; width: 160px; text-align: left;">
<span class="stolb_info--gt">
<span class="stolb_info-stat-gt" style="width: 60px; display: inline-block;">Версия: </span><span class="stolb_info-num-stat-gt">' . $ver . '</span><br />
<span class="stolb_info-stat-gt" style="width: 60px; display: inline-block;">Стадия: </span><span class="stolb_info-num-stat-gt">' . $stade . '</span><br />
<span class="stolb_info-stat-gt" style="width: 60px; display: inline-block;">Сборка: </span><span class="stolb_info-num-stat-gt">' . $build . '</span>
</span>
</div>
<a class="button" style="margin-top: 10px; width: 190px;" href="' . homeLink() . '/install/update">Обновить</a></center>
</div>';

echo '</div>
</div>
</div>';


echo '<div class="modal fade" id="offkat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Настройка ленты</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
<i class="fas fa-xmark"></i>
</button>
</div>
<form id="excludedKatForm" action="" method="POST">
<div class="modal-body">
<div>Выберите ненужные категории</div>';

// Получаем исключённые категории пользователя (один раз)
$result = dbquery("SELECT kat FROM excludedKat WHERE us = '" . intval($user['id']) . "' ORDER BY id");
$excludedKat = [];
while ($row = mfa($result)) {
    $excludedKat[] = $row['kat'];
}

$forum_r = dbquery("SELECT * FROM forum_razdel ORDER BY id ASC");
while ($a = mfa($forum_r)) {
    echo '<div class="razdel-nt">';
    echo '<a class="r_name" style="font-weight: bold; font-size: 14px; color: #949494; border-bottom: 0px; border-radius: 7px; padding: 7px 23px; pointer-events: none; display: flex; max-width: 500px; margin-top: 10px; width: 220px;">' . $a['name'] . '</a>';
    $forum_k = dbquery("SELECT * FROM forum_kat WHERE razdel = '" . intval($a['id']) . "' ORDER BY id LIMIT 30");
    while ($s = mfa($forum_k)) {
        $isChecked = in_array($s['id'], $excludedKat) ? 'checked' : '';
        echo '<input type="checkbox" name="kats[]" value="' . intval($s['id']) . '" ' . $isChecked . '>' . $s['name'] . '<br>';
    }
	
    echo '</div>';
}

echo '</div>
<div class="modal-footer">
<a class="button" id="saveButton">Сохранить</a>
<a class="button" data-bs-dismiss="modal">Отмена</a>
</div>
</form>
</div>
</div>
</div>';
?>
