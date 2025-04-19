<?
$perm = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $user['level_us'] . "'"));

echo '<div class="head" id="form">';
echo '<header>';
echo '<nav>';
echo '<div class="head-menu">';

echo '<span class="left-items">                                
<span class="mobile"><span id="hamburger">

<div id="nav-icon">
    <span></span>
    <span></span>
    <span></span>
</div>

</span></span>

<span id="alltop" style="display: inline-block; padding-top: 0px; cursor: pointer;">
<span id="allthems" class="back_but_link">
<span class="back_but_span" style="display: inline-block;"><i class=" fa fa-chevron-left"></i></span></span></span>

<a class="logotip" href="' . homeLink() . '" style="margin-left: 8px;"></a>
<a class="full_logo" href="' . homeLink() . '"></a>

<b><span class="drag__menu">
<a class="punkt_head_menu" href="' . homeLink() . '/rules" style="margin-left: 15px; background: none;"><span class="drag_menu_name">Правила форума</span></a>
<a class="punkt_head_menu" href="' . homeLink() . '/faq" style="margin-left: 15px; background: none;"><span class="drag_menu_name">F.A.Q </span></a>
<a class="punkt_head_menu" data-bs-toggle="modal" data-bs-target="#help" style="margin-left: 15px; background: none;"><span class="drag_menu_name">Помощь</span></a>
</span></b>
</span>';

echo '<span class="right-items">';
if (isset($user['id'])) {
    if (!isset($user['avatar'])) {
        dbquery("UPDATE `users` SET `avatar` = 'net.png' WHERE `id` = '" . $user['id'] . "'");
    }
}

if (empty($user['id'])) {
    echo '<span class="reg-button">';
    echo '<div class="auth_but"><a class="auth_button" data-bs-toggle="modal" data-bs-target="#login">Войти</a></div>';
    echo '<div class="auth_but"><a class="auth_but-link" data-bs-toggle="modal" data-bs-target="#registr">Регистрация</a></div>';
    echo '</span>';
}


if (isset($user['id'])) {

    if ($perm['panel'] > 0) {
        echo '<a class="head_button" href="' . homeLink() . '/admin"><svg width="22" height="22" viewBox="0 -1 22 21" xmlns="http://www.w3.org/2000/svg"><g xmlns="http://www.w3.org/2000/svg" id="layer1"><path d="M 9.9980469 0 L 9.328125 0.0234375 L 8.6621094 0.08984375 L 8 0.203125 L 8 2.2539062 L 7.4628906 2.4121094 L 6.9375 2.609375 L 6.4277344 2.8398438 L 5.9375 3.1074219 L 4.4863281 1.6582031 L 3.9375 2.046875 L 3.4199219 2.4707031 L 2.9296875 2.9296875 L 2.4726562 3.4179688 L 2.046875 3.9394531 L 1.6582031 4.484375 L 3.1074219 5.9375 L 2.8417969 6.4296875 L 2.609375 6.9394531 L 2.4140625 7.4628906 L 2.2539062 8 L 0.203125 8 L 0.091796875 8.6621094 L 0.0234375 9.3300781 L 0 10 L 0.0234375 10.669922 L 0.091796875 11.339844 L 0.203125 12 L 2.2539062 12 L 2.4140625 12.539062 L 2.609375 13.060547 L 2.8417969 13.570312 L 3.1074219 14.064453 L 1.6582031 15.515625 L 2.046875 16.060547 L 2.4726562 16.582031 L 2.9296875 17.070312 L 3.4199219 17.529297 L 3.9375 17.953125 L 4.4863281 18.341797 L 5.9375 16.892578 L 6.4277344 17.160156 L 6.9375 17.390625 L 7.4628906 17.587891 L 8 17.746094 L 8 19.796875 L 8.6621094 19.910156 L 9.328125 19.978516 L 9.9980469 20 L 10.671875 19.978516 L 11.337891 19.910156 L 12 19.796875 L 12 17.746094 L 12.537109 17.587891 L 13.0625 17.390625 L 13.572266 17.160156 L 14.0625 16.892578 L 15.513672 18.341797 L 16.058594 17.953125 L 16.580078 17.529297 L 17.070312 17.070312 L 17.527344 16.582031 L 17.953125 16.060547 L 18.341797 15.515625 L 16.888672 14.064453 L 17.158203 13.570312 L 17.390625 13.060547 L 17.585938 12.539062 L 17.746094 12 L 19.796875 12 L 19.908203 11.339844 L 19.976562 10.669922 L 20 10 L 19.976562 9.3300781 L 19.908203 8.6621094 L 19.796875 8 L 17.746094 8 L 17.585938 7.4628906 L 17.390625 6.9394531 L 17.158203 6.4296875 L 16.888672 5.9375 L 18.341797 4.484375 L 17.953125 3.9394531 L 17.527344 3.4179688 L 17.070312 2.9296875 L 16.580078 2.4707031 L 16.058594 2.046875 L 15.513672 1.6582031 L 14.0625 3.1074219 L 13.572266 2.8398438 L 13.0625 2.609375 L 12.537109 2.4121094 L 12 2.2539062 L 12 0.203125 L 11.337891 0.08984375 L 10.671875 0.0234375 L 9.9980469 0 z M 9.6640625 1.0058594 L 10.333984 1.0058594 L 11 1.0566406 L 11 3.0722656 L 11.572266 3.1796875 L 12.130859 3.3320312 L 12.677734 3.5332031 L 13.207031 3.7773438 L 13.710938 4.0644531 L 14.191406 4.3925781 L 15.617188 2.96875 L 16.123047 3.4042969 L 16.595703 3.875 L 17.03125 4.3828125 L 15.605469 5.8085938 L 15.933594 6.2871094 L 16.222656 6.7949219 L 16.466797 7.3222656 L 16.666016 7.8671875 L 16.820312 8.4296875 L 16.925781 8.9980469 L 18.943359 8.9980469 L 18.994141 9.6660156 L 18.994141 10.333984 L 18.943359 11.001953 L 16.925781 11.001953 L 16.820312 11.570312 L 16.666016 12.132812 L 16.466797 12.679688 L 16.222656 13.208984 L 15.933594 13.712891 L 15.605469 14.193359 L 17.03125 15.617188 L 16.595703 16.125 L 16.123047 16.597656 L 15.617188 17.03125 L 14.191406 15.607422 L 13.710938 15.935547 L 13.207031 16.222656 L 12.677734 16.46875 L 12.130859 16.667969 L 11.572266 16.820312 L 11 16.927734 L 11 18.943359 L 10.333984 18.994141 L 9.6640625 18.994141 L 9 18.943359 L 9 16.927734 L 8.4277344 16.820312 L 7.8671875 16.667969 L 7.3222656 16.46875 L 6.7929688 16.222656 L 6.2890625 15.935547 L 5.8085938 15.607422 L 4.3828125 17.03125 L 3.8769531 16.597656 L 3.4042969 16.125 L 2.96875 15.617188 L 4.3945312 14.193359 L 4.0664062 13.712891 L 3.7773438 13.208984 L 3.5332031 12.679688 L 3.3339844 12.132812 L 3.1796875 11.570312 L 3.0703125 11.001953 L 1.0566406 11.001953 L 1.0058594 10.333984 L 1.0058594 9.6660156 L 1.0566406 8.9980469 L 3.0703125 8.9980469 L 3.1796875 8.4296875 L 3.3339844 7.8671875 L 3.5332031 7.3222656 L 3.7773438 6.7949219 L 4.0664062 6.2871094 L 4.3945312 5.8085938 L 2.96875 4.3828125 L 3.4042969 3.875 L 3.8769531 3.4042969 L 4.3828125 2.96875 L 5.8085938 4.3925781 L 6.2890625 4.0644531 L 6.7929688 3.7773438 L 7.3222656 3.5332031 L 7.8671875 3.3320312 L 8.4277344 3.1796875 L 9 3.0722656 L 9 1.0566406 L 9.6640625 1.0058594 z M 9.9980469 6.0019531 L 9.5175781 6.0292969 L 9.0429688 6.1171875 L 8.5820312 6.2617188 L 8.140625 6.4589844 L 7.7285156 6.7070312 L 7.3476562 7.0078125 L 7.0058594 7.3496094 L 6.7070312 7.7265625 L 6.4570312 8.1425781 L 6.2597656 8.5820312 L 6.1152344 9.0429688 L 6.0292969 9.5195312 L 6 10 L 6.0292969 10.484375 L 6.1152344 10.957031 L 6.2597656 11.417969 L 6.4570312 11.859375 L 6.7070312 12.273438 L 7.0058594 12.654297 L 7.3476562 12.996094 L 7.7285156 13.292969 L 8.140625 13.541016 L 8.5820312 13.742188 L 9.0429688 13.882812 L 9.5175781 13.970703 L 9.9980469 14.001953 L 10.482422 13.970703 L 10.957031 13.882812 L 11.417969 13.742188 L 11.859375 13.541016 L 12.271484 13.292969 L 12.652344 12.996094 L 12.994141 12.654297 L 13.291016 12.273438 L 13.542969 11.859375 L 13.740234 11.417969 L 13.884766 10.957031 L 13.970703 10.484375 L 14 10 L 13.970703 9.5195312 L 13.884766 9.0429688 L 13.740234 8.5820312 L 13.542969 8.1425781 L 13.291016 7.7265625 L 12.994141 7.3496094 L 12.652344 7.0078125 L 12.271484 6.7070312 L 11.859375 6.4589844 L 11.417969 6.2617188 L 10.957031 6.1171875 L 10.482422 6.0292969 L 9.9980469 6.0019531 z M 9.796875 7.0078125 L 10.203125 7.0078125 L 10.611328 7.0625 L 11.003906 7.1738281 L 11.380859 7.3359375 L 11.730469 7.5488281 L 12.046875 7.8085938 L 12.326172 8.1054688 L 12.5625 8.4414062 L 12.751953 8.8046875 L 12.888672 9.1914062 L 12.972656 9.59375 L 12.998047 10 L 12.972656 10.410156 L 12.888672 10.808594 L 12.751953 11.195312 L 12.5625 11.558594 L 12.326172 11.894531 L 12.046875 12.193359 L 11.730469 12.451172 L 11.380859 12.664062 L 11.003906 12.828125 L 10.611328 12.9375 L 10.203125 12.992188 L 9.796875 12.992188 L 9.3886719 12.9375 L 8.9941406 12.828125 L 8.6191406 12.664062 L 8.2695312 12.451172 L 7.9511719 12.193359 L 7.6738281 11.894531 L 7.4375 11.558594 L 7.2480469 11.195312 L 7.1113281 10.808594 L 7.0273438 10.410156 L 7.0019531 10 L 7.0273438 9.59375 L 7.1113281 9.1914062 L 7.2480469 8.8046875 L 7.4375 8.4414062 L 7.6738281 8.1054688 L 7.9511719 7.8085938 L 8.2695312 7.5488281 L 8.6191406 7.3359375 L 8.9941406 7.1738281 L 9.3886719 7.0625 L 9.796875 7.0078125 z " fill="currentColor" fill-opacity="1" style="stroke:none; stroke-width:0px;"/></g></svg></a>';
    } else {
        echo '<span></span>';
    }

    echo '<a class="head_button" href="' . homeLink() . '/mes"><svg width="24" height="24" viewBox="0 -1 21 21" xmlns="http://www.w3.org/2000/svg"><g id="message_outline_20__Page-2" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="message_outline_20__message_outline_20"><path id="message_outline_20__Shape" opacity=".4" d="M0 0h20v20H0z"></path><path d="M6.83 15.75c.2-.23.53-.31.82-.2.81.3 1.7.45 2.6.45 3.77 0 6.75-2.7 6.75-6s-2.98-6-6.75-6S3.5 6.7 3.5 10c0 1.21.4 2.37 1.14 3.35.1.14.16.31.15.49-.04.76-.4 1.78-1.08 3.13 1.48-.11 2.5-.53 3.12-1.22ZM3.24 18.5a1.2 1.2 0 0 1-1.1-1.77A10.77 10.77 0 0 0 3.26 14 7 7 0 0 1 2 10c0-4.17 3.68-7.5 8.25-7.5S18.5 5.83 18.5 10s-3.68 7.5-8.25 7.5c-.92 0-1.81-.13-2.66-.4-1 .89-2.46 1.34-4.35 1.4Z" id="message_outline_20__Icon-Color" fill="currentColor" fill-rule="nonzero"></path></g></g></svg><span id="message-count"></span></a>';
    echo '<a class="head_button" href="' . homeLink() . '/lenta"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 -3 24 25"><path d="M12 2.1c4.02 0 6.9 3.28 6.9 7.53v1.6c0 .23.2.53.72 1.08l.27.27c1.08 1.1 1.51 1.73 1.51 2.75 0 .44-.05.79-.27 1.2-.45.88-1.42 1.37-2.87 1.37h-1.9c-.64 2.33-2.14 3.6-4.36 3.6-2.25 0-3.75-1.3-4.37-3.67l.02.07H5.74c-1.5 0-2.47-.5-2.9-1.41-.2-.4-.24-.72-.24-1.16 0-1.02.43-1.65 1.51-2.75l.27-.27c.53-.55.72-.85.72-1.08v-1.6C5.1 5.38 7.99 2.1 12 2.1Zm2.47 15.8H9.53c.46 1.25 1.25 1.8 2.47 1.8 1.22 0 2.01-.55 2.47-1.8ZM12 3.9c-2.96 0-5.1 2.43-5.1 5.73v1.6c0 .85-.39 1.46-1.23 2.33l-.28.29c-.75.75-.99 1.11-.99 1.48 0 .19.01.29.06.38.1.22.43.39 1.28.39h12.52c.82 0 1.16-.17 1.28-.4.05-.1.06-.2.06-.37 0-.37-.24-.73-.99-1.48l-.28-.29c-.84-.87-1.23-1.48-1.23-2.33v-1.6c0-3.3-2.13-5.73-5.1-5.73Z"></path></svg><span id="lenta-count"></span></a>';

    echo '<div class="dropdown">
<span class="head_button btn btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true" style="display: flex; align-items: center; height: 28px;"><span class="name_in-ank--user-nick">' . nick($user['id']) . '</span><img class="head_ava_us" src="' . homeLink() . '/files/ava/' . $user['avatar'] . '" /></span>
<ul class="dropdown-menu" style="inset: auto 0 auto auto; margin: 13px 2px;">
  
<li><a class="dropdown-item" href="' . homeLink() . '/id' . $user['id'] . '">Профиль<span class="icon-menu"><i class="fas fa-user"></i></span></a></li>
<li><a class="dropdown-item" href="' . homeLink() . '/balance" style="font-weight: 600; font-size: 16px;">' . $user['money_col'] . ' ₽</a></li>
<li><a class="dropdown-item" href="' . homeLink() . '/account">Ред. профиль<span class="icon-menu"><i class="fas fa-user-pen"></i></span></a></li>
<li><a class="dropdown-item" href="' . homeLink() . '/upgrade">Повышение прав<span class="icon-menu"><i class="fas fa-arrow-up-wide-short"></i></span></a></li>
<li><a class="dropdown-item" href="' . homeLink() . '/search">Поиск<span class="icon-menu"><i class="fas fa-search"></i></span></a></li>
<li><a class="dropdown-item" href="' . homeLink() . '/users">Люди<span class="icon-menu"><i class="fas fa-users"></i></span></a></li>
<li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#help">Помощь<span class="icon-menu"><i class="fas fa-question"></i></span></a></li>
<li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exit" style="color: #ea4c3e;">Выход<span class="icon-menu"><i class="fas fa-right-from-bracket"></i></span></a></li>

</ui>
</div>';
}

echo '</span>';

echo '</div>';
echo '</nav>';
echo '</header>';
echo '</div>';
?>