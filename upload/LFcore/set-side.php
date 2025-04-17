<?php
$currentFile = LFS($_SERVER['SCRIPT_NAME']);
echo '<div id="sidebar">';

$userPages = [
    "/user/anketa.php",
    "/user/them.php",
    "/user/post.php",
    "/user/friends.php"
];

$accountPages = [
    "/account/index.php",
    "/account/security.php",
    "/account/settings.php",
    "/account/upgrade.php",
    "/account/updatepass.php",
    "/account/updatemail.php",
	"/account/avatar.php"
];

$helpPages = [
    "/help/faq.php",
    "/help/doska.php",
    "/help/privacy.php",
    "/help/tooltips.php"
];

if (in_array($currentFile, $userPages)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/us_sidebar.php';
} elseif (in_array($currentFile, $accountPages)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/sett_sidebar.php';
} elseif (in_array($currentFile, $helpPages)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/help_sidebar.php';
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/sidebar.php';
}

echo '</div>';
