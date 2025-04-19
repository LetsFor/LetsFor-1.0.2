<?
if (!$index['name'] & $user['id'] == 1) {
    echo '<div class="content" style="margin-bottom: 15px; background: var(--theme-color); padding: 5px 10px 10px 10px;">';
    echo '<h1 style="padding: 5px; color: var(--buttons-color); font-size: 16px;">Добро пожаловать!</h1>';
    echo '<div style="padding: 0 5px;">Поздравляем с окончанием установки!</div>';
    echo '<div style="padding: 0 5px;">Так как вы только-что установили форум, требуется заполнить основную информацию сайта - <a class="name_in-ank--user" href="' . homeLink() . '/admin/settings" style="color: var(--buttons-color);">Заполнить</a></div>';
    echo '</div>';
}
