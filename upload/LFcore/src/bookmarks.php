<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

// Получаем ID темы
$id = abs(intval($_GET['id'] ?? 0)); // Проверка и обработка ID темы

if (!$id) {
    // Если ID темы не указан, возвращаем ошибку
    echo 'Ошибка: ID темы не указан!';
    exit();
}

// Если запрос - получение состояния закладки
if (isset($_GET['getState']) && $_GET['getState'] === 'true') {
    // Проверяем, находится ли тема в закладках
    $bookmark = mfa(dbquery("SELECT * FROM forum_zaklad WHERE tema = '$id' AND us = '" . $user['id'] . "'"));
    echo $bookmark ? '1' : '0'; // 1 - в закладках, 0 - нет
    exit();
}

// Если запрос - POST для добавления/удаления закладки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; // Получаем действие: "add" или "remove"
    
    // Проверяем, есть ли тема уже в закладках
    $bookmark = mfa(dbquery("SELECT * FROM forum_zaklad WHERE tema = '$id' AND us = '" . $user['id'] . "'"));

    if ($action === 'add' && !$bookmark) {
        // Добавляем в закладки
        dbquery("INSERT INTO forum_zaklad SET 
            tema = '$id',
            us = '" . $user['id'] . "'");
        echo '1'; // Успешно добавлено
    } elseif ($action === 'remove' && $bookmark) {
        // Удаляем из закладок
        dbquery("DELETE FROM forum_zaklad WHERE tema = '$id' AND us = '" . $user['id'] . "'");
        echo '0'; // Успешно удалено
    } else {
        echo 'Ошибка: Неверное действие или состояние!';
    }
    exit();
}
?>