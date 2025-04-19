<?
echo '<div class="side_ank">';
echo '<a class="link" href="' . homeLink() . '/account">Персональная информация</a>
<a class="link" href="' . homeLink() . '/account/settings">Личные настройки</a>
<a class="link" href="' . homeLink() . '/account/security">Безопасность</a>
<a class="link" href="' . homeLink() . '/account/avatar">Аватар</a>';

if ($prv_us['set_new_icon'] == 1 | $prv_us['set_new_color'] == 1) {
	echo '<a class="link" href="' . homeLink() . '/account/upgrade">Редактировать повышение</a>';
} else {
	echo '<span></span>';
}
echo '</div>';
