<?
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');

echo '<title>Восстановление пароля</title>';

if(isset($user['id'])){
header('Location: /');
exit();
}
echo '<style>
.content {
margin: 60px 10px 0px 10px;
padding: 50px 40px;
max-width: 350px;
box-sizing: border-box;
}
@media all and (min-width: 368px){ 
#sidebar {
	display: none;
}
.content {
margin: 60px auto;
}
</style>';

if(isset($_REQUEST['true']))
{
$login = LFS($_POST['login']);
if(empty($login))
{
echo err('<center>Вы не ввели логин!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}
if(mb_strlen($login) > 20 or mb_strlen($login) < 3)
{
echo err('<center>Введите логин от 3 до 20 символов!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}
if (!preg_match('|^[a-z0-9\-]+$|i', $login))
{
echo err('<center>Кириллица запрещена в логине!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}

$email = LFS($_POST['email']);
if(empty($email))
{
echo err('<center>Вы не ввели свой e-mail!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}
//-----Проверяем правильность ввода e-mail-----//
if (!preg_match('/[0-9a-z_\-]+@[0-9a-z_\-^\.]+\.[a-z]{2,6}/i', $email)) {
echo err('<center>Формат e-mail введён не верно!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}
$sqldb = mfar(dbquery("SELECT `login`,`email` FROM `users` WHERE `login` = '".$login."' and `email`='".$email."' LIMIT 1"));
if(!empty($login) && !empty($email)) if($sqldb == 0){
echo err('<center>Введенные данные не верны!</center>');
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php'); exit;
}
$rou = rand(1000000000,5000000000);
dbquery("UPDATE `users` SET `pass` = '".PassCryptor($rou)."' WHERE `login` = '".$login."'");

echo '<center><div class="icon_window">';

CoreLogo();

echo '</div></center><br />';
echo '<center><div class="name_window">Восстановление</div></center>';
echo '<div class="menu_nb">';
echo '<center><div class="menu_nb">На ваш e-mail<br /><span class="eml">'.$email.'</span><br />было отправлено письмо с вашими регистрациоными данными!</div></center>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
$title = $_SERVER['HTTP_HOST'];

$msg = '
<html>
<body>
<div class="menu_nb" style="margin: 0 auto; max-width: 600px">
<div class="content">
<div class="menu_nbr">
<span style="padding-bottom: 5px; font-size: 16px;">Здравствуйте '.$login.'!</span>
</div>
<div class="menu_nbr">
Вами (или нет) была произведена операция по восстановлению пароля на паблике <b>'.$LF_name.'</b><br />
Ниже приведены данные для входа в аккаунт.<br />
</div>
<div class="menu_nbr">
<b>Логин: '.$login.' </b><br />
<b>Пароль: '.$rou.' </b>
</div>
<div class="menu_nb">
Пароль сгенерирован автоматически, просим вас сменить его после авторизации.
</div>
</div>
</div>
</body>
</html>';
$mail.= "no-reply.com";
$headers.= "MIME-version: 1.0\n";
$headers.= "Content-type: text/html; charset= iso-8859-1\n";
$sets = 'info@'.$mail;
$adds = "From: <" . $sets . ">\r\n";
$adds .= "Content-Type: text/html; charset=\"utf-8\"\r\n";

mail($email, '=?utf-8?B?'.base64_encode('Восстановление пароля').'?=', $msg, $adds);
session_destroy();
exit();
}
//-----Форма ввода-----//
echo '<center><div class="icon_window">';

require_once ''.$_SERVER['DOCUMENT_ROOT'].'/LFcore/min-window-icon.gt';

echo '</div></center><br />';
echo '<center><div class="name_window">Восстановление</div></center>';
echo '<div class="menu_nb">';
echo '<form action="" method="POST">
<center><input placeholder="Логин" type="text" name="login" maxlength="20" /></center>
<center><input placeholder="Email" type="text" name="email" maxlength="40" /></center><br />
<center><input type="submit" name="true" value="Продолжить" style="margin-left: 3px; font-color: #d2d2d2; width: 175px; margin-top: 10px; margin-bottom: 0px; border-bottom: 0px;" /></center>
</form>';
echo '</div>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');

?>