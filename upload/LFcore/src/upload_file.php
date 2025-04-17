<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['type'])) {
	$id = abs(intval($_GET['id']));
    $type = htmlspecialchars($_POST['type']); // Тип файла
    $fileName = basename($_FILES['file']['name']);

    switch ($type) {
        case 'avatar':
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/files/ava/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Создание директории
            }
            $filePath = $uploadDir . $fileName;
			if ($user['avatar'] != 'net.png' && isset($user['avatar'])) {
				unlink ($_SERVER['DOCUMENT_ROOT'] . "/files/ava/" . $user['avatar']);
			}
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                // Запрос для аватаров
                $query = "UPDATE `users` SET `avatar` = '" . $fileName . "' WHERE `id` = '" . $user['id'] . "'";
                if (dbquery($query)) {
                    echo "Аватар успешно загружен и зарегистрирован!";
                } else {
                    echo "Ошибка регистрации аватара";
                }
            } else {
                echo "Ошибка загрузки аватара.";
            }
            break;

        case 'forum-document':
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/files/forum/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Создание директории
            }
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                // Запрос для документов
                $query = "INSERT INTO `forum_file` SET `post_id` = '" . $id . "', `name_file` = '" . $fileName . "'";
                if (dbquery($query)) {
                    echo "Документ успешно загружен и зарегистрирован!";
                } else {
                    echo "Ошибка регистрации документа";
                }
            } else {
                echo "Ошибка загрузки документа.";
            }
            break;

        default:
            echo "Неизвестный тип файла!";
            break;
    }
} else {
    echo "Файл или тип файла не указан.";
}
?>