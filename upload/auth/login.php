<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');


if(isset($user['id'])) {
header('Location: ');
exit();
}


echo '<style>
.content {
margin: 0px auto;
padding: 50px 40px;
max-width: 350px;
box-sizing: border-box;
}
@media all and (min-width: 368px){ 
#sidebar {
	display: none;
}
</style>';

echo '<div class="login">';
echo '<center><div class="icon_window">';

CoreLogo();

echo'</div></center><br />';
echo '<center><div class="name_window">Авторизация</div></center>';

##############################
####### Главная ###### #######
##############################
$act = isset($_GET['act']) ? $_GET['act'] : null;
switch($act) {
default:

echo '<div class="menu_nb" style="padding-bottom: 0px;">';
echo '<title>Авторизация - '.$LF_name.'</title>';
echo '<meta name="description" content="Авторизация - '.$LF_name.'">';
echo '<meta name="description" content="Страница авторизации пользователя">';
echo '
<form action="?act=true" method="POST">
<input placeholder="Логин" type="text" name="login" maxlength="20" autocomplete="login"/>
<div class="password-container">
<input placeholder="Пароль" type="password" name="pass" maxlength="25" id="password" autocomplete="pass"/><i class="fas fa-eye toggle-password" onclick="togglePassword()" autocomplete="password"></i>
</div><br />
<center><input type="submit" value="Войти" style="margin-left: 3px; font-color: #d2d2d2; width: 175px; margin-top: 10px; margin-bottom: 0px; border-bottom: 0px;"></center><br /><center><a class="pass_rec_but" href="'.homeLink().'/repass" style="color: #c33929;">Забыли пароль</a></center><br />
</form>';
echo '</div>';
echo '<center><a class="link" href="'.homeLink().'/registr" style="border: solid 1px #303030; border-radius: 7px; max-width: 155px;">Создать аккаунт</a></center>';

if(isset($auth_vk['client_id'])) {
echo '<center><a href="https://oauth.vk.com/authorize?client_id='.$auth_vk['client_id'].'&display=page&redirect_uri='.homeLink().'/auth/redvk.php&scope=email&response_type=code&v=5.131"><img src="'.homeLink().'/images/vk.png" style="width: 30px; margin-top: 30px;"></a></center>';
}

break;
##############################
####### Кейс проверки ########
##############################
case 'true':

//-----Фильтрируем переменную-----//
$onepass = LFS($_POST['pass']);
$login = LFS($_POST['login']);

if(empty($login)) {
echo err('<center>Вы не ввели логин!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

if(mb_strlen($login) > 20 or mb_strlen($login) < 3) {
echo err('<center>Введите логин от 3 до 20 символов!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверка на символы-----//
if (!preg_match('|^[a-z0-9\-]+$|i', $login)) {
echo err('<center>Кириллица запрещена в логине!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

$pass = PassCryptor(LFS($_POST['pass']));

//-----Проверяем на ввод пароля-----//
if(empty($pass)){
echo err('<center>Вы не ввели свой пароль!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

$dbsql_pass = mfar(dbquery("SELECT `pass` FROM `users` WHERE `pass`='".$pass."' LIMIT 1"));
if($dbsql_pass == 0) {
echo err('<center>Введен неверный пароль!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

if(mb_strlen($pass) < 5) {
echo err('<center>Введите пароль от 5 символов!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверка на символы-----//
if (!preg_match('|^[a-z0-9\-]+$|i', $pass)) {
echo err('<center>Кириллица запрещена в пароле!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

$dbsql = mfar(dbquery("SELECT `login`,`pass` FROM `users` WHERE `login` = '".$login."' LIMIT 1"));
if(!empty($login)) if($dbsql == 0) {
echo err('<center>Такого пользователя не существует!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

header('Location:'.homeLink().'/autolog/?ulog='.$login.'&upas='.$onepass.'');
session_start();
$_SESSION['newEl'] = 'value';

if(!empty($_POST)){
$_SESSION['login'] = $_POST['login'];
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
exit();

break;
}

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>