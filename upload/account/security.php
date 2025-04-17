<?php

$description = 'Настройки безопасности анкеты | Халява, скрипты, схемы заработка, сливы, торговля, скам проекты, общение, чат, социальная инженерия, сливы LolzTeam. А также множество других услуг предоставлены только на нашем форуме GROVE!';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


echo '<title>Безопасность</title>';

# закрываем от гостей
if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<div class="title">Личный кабинет</div>';


echo '<form action="" method="POST">';
echo '<div class="menu_nb">
<div class="setting_punkt">Почта: <span class="mail_user">' . bb($user['email']) . '</span></div>
<a class="button" href="' . homeLink() . '/account/updatemail.php">Изменить почту</a>
</div>';

echo '<div class="menu_nb">
<div style="font-size: 13px;">Почта вводится при регистрации, используется для восстановления учётной изаписи если забыли пароль.</div>
</div>';

echo '<div class="menu_t">
<div class="setting_punkt">Пароль</div>
<a class="button" href="' . homeLink() . '/account/updatepass.php">Изменить пароль</a>
</div>';
echo '</form>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
