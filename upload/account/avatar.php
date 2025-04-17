<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

if (empty($user['id'])) exit(header('Location: ' . homeLink()));

echo '<title>Смена аватарки - ' . $LF_name . '</title>';
echo '<div class="title">Сменить аватарку</div>';

//--Удаление авки--//
if (isset($_REQUEST['delava'])) {
	unlink ($_SERVER['DOCUMENT_ROOT'] . "/files/ava/" . $user['avatar']);
	dbquery("UPDATE `users` SET `avatar` = 'net.png' WHERE `id` = '" . $user['id'] . "'");
	header('Location: ' . homeLink() . '/account/avatar');
	exit();
}

echo '<div class="menu_nb">';
echo (empty($user['avatar']) ? '<center><img src="' . homeLink() . '/files/ava/net.png" alt="*" style="border-radius: 100px; width: 100px; height: 100px;"></center>' : '<center><img src="' . homeLink() . '/files/ava/' . $user['avatar'] . '" alt="*" style="border-radius: 100px; width: 100px; height: 100px;"></center>');
echo '</div>';

echo '<center><div class="menu_t">';
echo '<a class="button" data-bs-toggle="modal" data-bs-target="#load_file">Изменить аватар</a> ';
if ($user['avatar'] == 'net.png' or empty($user['avatar'])) {
	echo '<span></span>';
} else {
	echo '<a class="button dark" href="' . homeLink() . '/account/avatar/delete" style="display: inline-block;"><span class="icon"><i class="far fa-trash-alt"></i></span> Удалить аватар</a>';
}
echo '</div></center>';
file_upload('avatar');

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>