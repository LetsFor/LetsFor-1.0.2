<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');


$clientId = $auth_vk['client_id'];
$clientSecret = $auth_vk['client_secret'];
$redirectUri = ''.homeLink().'/auth/redvk.php';

// Проверяем, есть ли код авторизации
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Запрос токена доступа
    $tokenUrl = "https://oauth.vk.com/access_token?client_id={$clientId}&client_secret={$clientSecret}&redirect_uri={$redirectUri}&code={$code}";
    $response = file_get_contents($tokenUrl);
    $data = json_decode($response, true);

    if (isset($data['access_token'])) {
        // Токен доступа получен
        $accessToken = $data['access_token'];

        // Запрос информации о пользователе
        $userInfoUrl = "https://api.vk.com/method/users.get?user_ids={$data['user_id']}&fields=photo_200&access_token={$accessToken}&v=5.131";
        $userInfoResponse = file_get_contents($userInfoUrl);
        $userInfo = json_decode($userInfoResponse, true);

        if (isset($userInfo['response'][0])) {
            $user = $userInfo['response'][0];
            $login = $user['id']; // Используем ID пользователя в качестве логина
			$name = $user['first_name'];
			$pass = sha1(md5(sha1($login))); // Используем ID как пароль

            // Проверяем, существует ли пользователь в базе данных
            $dbsql = mfar(dbquery("SELECT `login`,`pass` FROM `users` WHERE `vk` = '".$login."' LIMIT 1"));

            if (!$dbsql) {
                // Если пользователь не существует, добавляем его
                dbquery("INSERT INTO `users` SET `login` = '".$login."', `pass` = '".PassCryptor($pass)."', `email` = '', `datareg` = '".time()."', `avatar` = 'net.png', `name` = '".$name."', `level_us` = '1', `max_us` = '15', `prev` = '0', `vk` = '".$login."'");
            }

            // Перенаправляем пользователя после успешной авторизации
			header('Location:'.homeLink().'/autolog/?ulog='.$dbsql['login'].'&upas='.$pass.'');
			session_start();
			$_SESSION['newEl'] = 'value';
			
			if(!empty($_POST)){
				$_SESSION['login'] = $dbsql['login'];
			}
            exit();
        }
    }
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>