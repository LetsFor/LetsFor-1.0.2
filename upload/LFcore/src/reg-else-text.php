<?
if (empty($user['id'])) {
    echo '<span class="c_lock"><span class="lock_t">Рекомендуем вам зарегистрироваться или войти под своим именем чтобы начать обсуждение</span></span>';
} else {
    echo '<div class="menu_tad">';
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/bbcode.php');
    echo '<form action="" name="message" method="POST" id="myFormThem" style="display: flex; align-items: center; padding: 0 5px;">';
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/textarea.php');
    echo '</form>';
    echo '</div>';
}
