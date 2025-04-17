<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

if (isset($_GET['id'])) {
    $id = abs(intval($_GET['id']));

    // Получаем последний id для заданной темы
    $result = dbquery("SELECT MAX(id) as max_id FROM forum_post WHERE tema = ?", [$id]);
	$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = ?", [$id]));

    if ($result) {
        $row = mfa($result);
        if ($row && isset($row['max_id'])) {
            $last_post_id = $row['max_id'];

            // Получаем данные последнего поста
            $post_result = dbquery("SELECT * FROM forum_post WHERE id = ? AND tema = ?", [$last_post_id, $id]);

            if ($post_result) {
                while ($a = mfa($post_result)) {
                    require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/comment.php');
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