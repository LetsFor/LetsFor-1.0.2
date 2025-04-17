<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/install/core/head.php');
echo '<div class="content">
<div class="menu">
<div class="title">Почти готово!</div>
<div class="pod_title">
LetsFor успешно установлен.
</div>
</div>
</div>';

echo '<div class="box">';
echo '<div class="image">';
echo '<img src="' . homeLink() . '/install/css/images/ok.png"/>';
echo '</div>';
echo '<div class="content" style="width: -webkit-fill-available; margin: 0;">
<div class="menu">
<div class="title">Это еще не все</div>
<div class="pod_title">Для окончания установки зарегистрируйте администратора.</div>
<a class="button" href="' . homeLink() . '/install/?act=reg">Продолжить</a>
</div>
</div>';
echo '</div>';
require_once($_SERVER['DOCUMENT_ROOT'] . '/install/core/footer.php');
