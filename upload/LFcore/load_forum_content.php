<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;

$forum_home = dbquery("SELECT * FROM `forum_tema` ORDER BY `up` DESC LIMIT $offset, $limit");
while ($a = mfa($forum_home)) {
    require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/div-link-thems-info.php');
}
?>