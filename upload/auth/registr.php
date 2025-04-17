<?
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');

if(isset($user['id'])){
header('Location: /');
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

echo '<center><div class="icon_window">';

CoreLogo();

echo '</div></center><br />';
echo '<center><div class="name_window">Регистрация</div></center>';

echo '<title>Регистрация - '.$LF_name.'</title>';
echo '<meta name="description" content="Регистрация - '.$LF_name.'">';

$sql = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

if($sql['reg_on'] == 1){
    
echo '<div class="menu"><center><span style="font-size: 50px;" class="icon"><i class="fas fa-exclamation-triangle"></i></span></br>Извините</br>Регистрация временно недоступна</center></div>';

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Если жмут submit(кнопку)-----//
if(isset($_REQUEST['reg'])) {
//-----Фильтрируем перемменые-----//
$login = LFS($_POST['login']);
//-----Проверка на ввод логина-----//

if(empty($login)){
echo err('<center>Вы не ввели логин!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверка на символы-----//
if (!preg_match('|^[a-z0-9\-]+$|i', $login)){
echo err('<center>Кириллица запрещена в логине!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверяем длину ввода-----//
if(mb_strlen($login) > 13 or mb_strlen($login) < 3){
echo err('<center>Введите логин от 3 до 13 символов!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверка на занятость логина-----//
$sql = dbquery("SELECT COUNT(`id`) FROM `users` WHERE `login` = '".$login."'"); 
if(msres($sql, 0) > 0){
echo '<div class="err" style="border-bottom: 0px; margin-top: 20px;"><center>Такой логин уже существует!</center></div>';
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Фильтрируем перемменые-----//
$pass = LFS($_POST['pass']);
//-----Проверка на ввод логина-----//
if(empty($pass)){
echo err('<center>Вы не ввели свой пароль!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверка на символы-----//
if (!preg_match('|^[a-z0-9\-]+$|i', $pass)){
echo err('<center>Кириллица запрещена в пароле!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверяем длину ввода-----//
if(mb_strlen($pass) > 25 or mb_strlen($pass) < 5){
echo err('<center>Введите пароль от 5 до 25 символов!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Фильтрируем перемменые-----//
$r_pass = LFS($_POST['r_pass']);
//-----Если не одинаковые пароли-----//
if($pass != $r_pass){
echo err('<center>Пароли не одинаковые!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

$email = LFS($_POST['email']);

//-----Проверяем правильность ввода e-mail-----//
if (!preg_match('/[0-9a-z_\-]+@[0-9a-z_\-^\.]+\.[a-z]{2,6}/i', $email)) {
echo err('<center>Формат email введён не верно!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Проверяем e-mail на занятость-----//
$sqlemail = dbquery("SELECT COUNT(`id`) FROM `users` WHERE `email` = '".$email."'"); 
if (msres($sqlemail, 0) > 0) {
echo err('<center>Такой email уже существует!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

//-----Если всё нормально-----//
dbquery("INSERT INTO `users` SET `login` = '".$login."', `pass` = '".PassCryptor($pass)."', `email` = '".$email."', `datareg` = '".time()."', `avatar` = 'net.png', `level_us` = '1', `max_us` = '15', `prev` = '0'");
//-----Вычесляем id-----//
$uid = getLastInstId();
//-----Если id 1 то ставим level 2-----//
if($uid == 1){
dbquery("UPDATE `users` SET `level_us` = '2' WHERE `id` = '1'");
}
		
header('Location:'.homeLink().'/autolog/?ulog='.$login.'&upas='.$pass.'');
session_start();
$_SESSION['newEl'] = 'value';

if(!empty($_POST)){
$_SESSION['login'] = $_POST['login'];
}

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
exit();
}

//-----Форма ввода-----//
echo '<div class="menu_nb" style="padding-bottom: 0px;">';
echo '<form method="POST" action="">
<input placeholder="Логин" type="text" name="login" maxlength="13" /><br />
<input placeholder="Email" type="text" name="email" maxlength="40" /><br />
<input placeholder="Пароль" type="password" name="pass" maxlength="25" /><br />
<input placeholder="Повторите пароль" type="password" name="r_pass" maxlength="25" /><br /><br />
<center><input type="submit" name="reg" value="Создать аккаунт" style="margin-left: 3px; font-color: #d2d2d2; width: 175px; margin-top: 10px; margin-bottom: 0px; border-bottom: 0px;" /></center>
</form>';
echo '</div>';

//-----Подключаем низ-----//

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');

?>