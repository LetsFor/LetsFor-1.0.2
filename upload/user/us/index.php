<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<div class="title">' . $title . '</div>';
echo '<a class="link" href="color_nick.php"><span class="icon"><i class="fas fa-palette"></i></span> Цвет ника</a>
<a class="link" href="new_nick.php"><span class="icon"><i class="fas fa-user-edit"></i></span> Смена ника</a>
<a class="link" href="vip.php"><span class="icon"><i class="fas fa-star"></i></span> Купить VIP</a>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
