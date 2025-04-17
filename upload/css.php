<?php
header("Content-type: text/css");

$cssFiles = isset($_GET['css']) ? explode(',', $_GET['css']) : [];
$direction = isset($_GET['dir']) ? $_GET['dir'] : 'LTR';
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';

$baseCssDirectory = $_SERVER['DOCUMENT_ROOT'] . '/design/style/';

function requireCssFile($filePath)
{
    if (file_exists($filePath)) {
        readfile($filePath);
    } else {
        echo "/* Файл $filePath не найден */\n";
    }
}

foreach ($cssFiles as $cssFile) {
    $safeCssFile = str_replace(['..', './', '\\'], '', $cssFile);
    $filePath = $baseCssDirectory . $folder . '/' . $safeCssFile;
    requireCssFile($filePath);
}

echo "\n\nbody {\n";
echo "    direction: " . ($direction === 'RTL' ? 'rtl' : 'ltr') . ";\n";
echo "}\n";
