<?
if($prv['free_set_name'] == 1) { echo 'Бесплатная смена никнейма<br />'; }
if($prv['free_set_des_name'] == 1) { echo 'Бесплатная смена дизайна никнейма<br />'; }
if($prv['set_gif_ava'] == 1) { echo 'Возможность установить анимированный аватар<br />'; }
if($prv['set_background_user'] == 1) { echo 'Возможность установить фон профиля<br />'; }
if($prv['set_background_head_them'] == 1) { echo 'Фон шапки темы как в профиле<br />'; }
if($prv['color_them_title'] == 1) { echo 'Выделение темы на главной<br />'; }
if($prv['set_intercolor_user'] == 1) { echo 'Возможность смены акцента в профиле<br />'; }
if($prv['set_new_icon'] == 1) { echo 'Возможность смены иконки повышения<br />'; }
if($prv['set_new_color'] == 1) { echo 'Возможность смены цвета лычки<br />'; }
if($prv['icon_prev']) { echo 'Значек возле никнейма<br />'; }
?>