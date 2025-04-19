<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

if (isset($_GET['id'])) {
    $id = abs(intval($_GET['id']));

    // Получаем последний id для заданной темы
	$mess = mfa(dbquery("SELECT * FROM `users` WHERE `id` = ?", [$id]));
    $result = dbquery("SELECT MAX(id) as max_id FROM `message` WHERE `kto` = ? and `komy` = ? or `kto` = ? and `komy` = ?", [$user['id'], $mess['id'], $mess['id'], $user['id']]);

    if ($result) {
        $row = mfa($result);
        if ($row && isset($row['max_id'])) {
            $last_post_id = $row['max_id'];

            // Получаем данные последнего поста
            $post_result = dbquery("SELECT * FROM `message` WHERE `id` = ?", [$last_post_id]);

            if ($post_result) {
                while ($m = mfa($post_result)) {
                    require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/user-message.php');
                }
            } else {
                echo "Ошибка при получении данных последнего поста.";
            }
        } else {
            echo "Посты для данной темы не найдены.";
        }
    } else {
        echo "Ошибка при выполнении запроса.";
    }
} else {
    echo "ID темы не указан.";
}
?>