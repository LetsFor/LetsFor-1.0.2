<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) {
    header('Location: ' . homeLink());
    exit();
}

if (isset($_REQUEST['okda'])) {
    setcookie('uslog', '', time() - 86400 * 31);
    setcookie('uspass', '', time() - 86400 * 31);
    header('location: ' . homeLink());
}

echo '<div class="title">' . $title . '</div>';
echo '<div class="ok">Вы действительно хотите покинуть сайт?</div></br>';
echo '<table style="text-align:center;" cellspacing="0" cellpadding="0">';
echo '<td><a style="border-right:none;" class="link" href="/exit.php?okda">Да</a></td>';
echo '<td><a class="link" href="/index.php">Нет</a></td>';
echo '</table>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
