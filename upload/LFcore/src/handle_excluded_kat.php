<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

// Проверяем авторизацию пользователя
if (!isset($user['id'])) {
    die('Ошибка: Пользователь не авторизован.');
}

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($user['id']); // ID авторизованного пользователя

    if (isset($_POST['kats'])) {
        $selectedKats = $_POST['kats'];

        try {
            // Удаляем категории, которых больше нет в выбранных
            $katIds = implode(',', array_map('intval', $selectedKats));
            dbquery("DELETE FROM excludedKat WHERE us = $userId AND kat NOT IN ($katIds)");

            // Добавляем новые категории, которых ещё нет
            foreach ($selectedKats as $kat) {
                $kat = intval($kat);
                $exists = dbquery("SELECT COUNT(*) AS count FROM excludedKat WHERE us = $userId AND kat = $kat");
                if (mfa($exists)['count'] == 0) {
                    dbquery("INSERT INTO excludedKat (us, kat) VALUES ($userId, $kat)");
                }
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            die('Ошибка: Не удалось обработать данные.');
        }
    } else {
        // Если массив kats отсутствует, удаляем все категории для пользователя
        dbquery("DELETE FROM excludedKat WHERE us = $userId");
    }
} else {
    die('Ошибка: Некорректный запрос.');
}
?>