<?php
ob_start(); // Начинаем буферизацию вывода
require_once $_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php';
ob_end_clean(); // Очищаем буфер и отключаем буферизацию

error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/javascript; charset=UTF-8');

// Выполнение запроса к базе данных
$query = "SELECT word, tooltip, definition FROM forum_tooltips";
$result = dbquery($query);

$tooltips = [];
while ($row = mfa($result)) {
    // Разделяем слова по запятой и удаляем пробелы
    $words = array_map('trim', explode(',', $row['word']));
    foreach ($words as $word) {
        $tooltips[$word] = $row['definition'] . ' - ' . $row['tooltip'];
    }
}

// Преобразование массива в JavaScript-объект
echo 'var wordsWithTooltips = ' . json_encode($tooltips, JSON_UNESCAPED_UNICODE) . ';';
