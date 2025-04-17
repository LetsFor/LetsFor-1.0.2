<?php
// Подключение необходимых файлов
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/function.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/db-connect.php');

// Проверка авторизации пользователя
$user = checkCookie(); // Проверяем авторизацию

// Исключенные категории для авторизованных пользователей
$excludedKat = [];
if ($user) {
    $result = dbquery("SELECT kat FROM excludedKat WHERE us = '" . $user['id'] . "' ORDER BY id");
    while ($row = mfa($result)) {
        $excludedKat[] = $row['kat'];
    }
}

// Формируем строку для SQL-запроса
$katString = implode(',', $excludedKat);
$notin = empty($katString) ? "" : "NOT IN ($katString)";

// Параметры запроса
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 30;

// SQL-запрос для получения данных из базы
$query = "SELECT * FROM forum_tema WHERE kat $notin ORDER BY top_them DESC LIMIT $limit OFFSET $offset";
$forum = dbquery($query);

// Проверка успешности выполнения запроса
if (!$forum) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ошибка выполнения SQL-запроса']);
    exit();
}

// Формируем JSON-ответ, включая HTML из файла
$items = [];
while ($a = mfa($forum)) {
    ob_start(); // Начинаем буферизацию вывода
    require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/div-link-thems-info.php'); // Динамический HTML
    $html = ob_get_clean(); // Получаем HTML из буфера

    $items[] = [
        'id' => $a['id'], // ID темы
        'html' => $html // HTML-код, включенный в JSON
    ];
}

// Возвращаем данные в формате JSON
header('Content-Type: application/json');
echo json_encode([
    'items' => $items,
    'hasMore' => count($items) === $limit // Флаг наличия следующих элементов
]);
exit();