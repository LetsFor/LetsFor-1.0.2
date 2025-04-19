<?php

/****** Создаем переменную адреса ******/

$DOMAIN = $_SERVER['HTTP_HOST'];
header("Content-Type: text/html; charset=utf-8");

/****** Пропускаем требования php 8 ******/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);

/****** Запускаем сессии ******/

session_start();
ob_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/function.php');

echo '<!DOCTYPE html>';

$aut = 'Forum software by LetsFor™';

////////////////////////////////
//////// Подключаем БД ////////
//////////////////////////////

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/db-connect.php');
echo $css->LFstyleLink();

$index = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
$auth_vk = mfa(dbquery("SELECT * FROM `vkauth` WHERE `id`"));
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/configuration.php');

////////////////////////////////
///// Проверяем сылку гет /////
//////////////////////////////

foreach ($_GET as $key => $value) {
	if (!is_string($value) || !preg_match('#^(?:[a-z0-9_\-/]+|\.+(?!/))*$#i', $value)) {
		header('Location: ' . homeLink());
		exit;
	}
}

//////////////////////////////////
///// Отслеживаем посещения /////
////////////////////////////////

$ip_address = $_SERVER['REMOTE_ADDR'];
$visit_date = date('Y-m-d');
$res_visit = dbquery("SELECT * FROM `visitors` WHERE `ip_address` = '$ip_address' AND `visit_date` = '$visit_date'");
if (msnumrows($res_visit) == 0) {
	dbquery("INSERT INTO visitors (ip_address, visit_date) VALUES ('$ip_address', '$visit_date')");
}

///////////////////////////////
//////////// Куки ////////////
/////////////////////////////

if (isset($_COOKIE['uslog']) && isset($_COOKIE['uspass'])) {
    $uslog = LFS($_COOKIE['uslog']);
    $uspass = LFS($_COOKIE['uspass']);

    // Получаем пользователя из базы данных
    $user = checkCookie();

    if (isset($user['id'])) {
        // Проверяем, совпадают ли логин и пароль
        if ($user['login'] !== $uslog || $user['pass'] !== $uspass) {
            setcookie('uslog', '', time() - 86400 * 31, '/');
            setcookie('uspass', '', time() - 86400 * 31, '/');
        }
    }

    // Получаем конфигурацию
    $config = mfa(dbquery("SELECT * FROM config WHERE id = '1'"));

    // Получаем пользователя еще раз (возможно, это избыточно)
    $users = checkCookie();

    // Обновляем информацию о пользователе
    dbquery(
        "UPDATE users SET viz = :time, ip = :ip, gde = :gde, gdeon = :gdeon WHERE id = :id",
        [
            ':time' => time(),
            ':ip' => LFS($_SERVER['REMOTE_ADDR']),
            ':gde' => LFS($_SERVER['REQUEST_URI']),
            ':gdeon' => LFS($_SERVER['SCRIPT_NAME']),
            ':id' => $users['id']
        ]
    );

    // Проверяем, что 'viz' и 'online_us' определены и являются числами
    if (isset($users['viz']) && is_numeric($users['viz']) && isset($user['online_us']) && is_numeric($user['online_us'])) {
        $vremja = time() - $users['viz'];
        if ($vremja < 120) {
            $newtime = $user['online_us'] + $vremja;
            dbquery("UPDATE users SET online = :newtime WHERE id = :id", [
                ':newtime' => $newtime,
                ':id' => $users['id']
            ]);
        }
    }

    // Проверяем, совпадают ли логин и пароль еще раз
    if (isset($user['id']) && ($users['login'] !== $uslog || $users['pass'] !== $uspass)) {
        setcookie('uslog', '', time() - 86400 * 31, '/');
        setcookie('uspass', '', time() - 86400 * 31, '/');
    }
}

$eng = 'LetsFor Community';
$config = mfa(dbquery("SELECT * FROM `config` WHERE `id` = '1'"));


$prv_us = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $user['prev'] . "'"));

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/gt-reg.php');

////////////////////////////////////////
///// Добавляем root пользователя /////
//////////////////////////////////////

$rootUserId = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '1'"));
if (isset($rootUserId['id'])) {
	$rootUser = mfa(dbquery("SELECT * FROM `users` WHERE `login` = 'root' LIMIT 1"));
	if (empty($rootUser['id'])) {
		dbquery("INSERT INTO `users` SET `login` = 'root', `pass` = '" . PassCryptor(mt_rand()) . "', `name` = '" . $name . "', `datareg` = '" . time() . "', `stat` = 'Автоматически созданный профиль " . $LF_name . "', `color_nick` = 'color: #c92d2d; font-weight: 600;', `avatar` = 'root.png', `level_us` = '2', `max_us` = '15', `prev` = '0'");
	} else {
		echo '<span></span>';
	}
}

/////////////////////////////
//////////// Бан ///////////
///////////////////////////

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/ban.php');

/////////////////////////////////
//////////// Плагины ///////////
///////////////////////////////

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/PluginLoader.php');

// Создаем экземпляр загрузчика плагинов
$pluginLoader = new PluginLoader();

// Загрузка всех плагинов из директории plugins
$pluginLoader->loadPluginsFromDirectory($_SERVER['DOCUMENT_ROOT'] . '/plugins/');

// Загружаем и инициализируем все зарегистрированные плагины
$pluginLoader->load();
