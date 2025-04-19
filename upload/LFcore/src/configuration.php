<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/info.php');

$index = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));

if (!$index['name']) {
    $LF_name = "LetsFor";
} else {
    $LF_name = $index['name'];
}

if (!$index['podname']) {
    $LF_info = "Ваш новый форум";
} else {
    $LF_info = $index['podname'];
}

$LF_keyw = $index['key_col'];
$LF_comm = "LetsFor";
$LF_fico = "" . homeLink() . "/favicon.ico";
$LF_mico = "" . homeLink() . "/design/img/logo/min-logo/min-logo.png";
$LF_bico = "" . homeLink() . "/design/img/logo/full-logo/full-logo.png";

$LF_help_vk = $index['help_vk'];
$LF_help_tg = $index['help_tg'];
$LF_help_mail = $index['help_email'];
