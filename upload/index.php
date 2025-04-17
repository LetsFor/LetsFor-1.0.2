<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');

syncSmileFoldersWithDatabase();

##Выводим форум
$t_f = msres(dbquery('select count(`id`) from `forum_tema`'),0);
$p_f = msres(dbquery('select count(`id`) from `forum_post`'),0);
$ntf = msres(dbquery('select count(`id`) from `forum_tema` where `time_up` > "'.(time()-((60*60)*24)).'"'),0);

##последние темы форума
if (empty($user['id'])){
require_once ($_SERVER['DOCUMENT_ROOT'].'/LFcore/thems_core.php'); 
} else {
if ($user['new_tem'] == 0)
require_once ($_SERVER['DOCUMENT_ROOT'].'/LFcore/thems_core.php');
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>
