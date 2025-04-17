<?
if (!$forum_k['background']) {
    echo '<span></span>';
} else {
    echo '<style> 
:root { --back-image: url(' . $forum_k['background'] . '); }
</style>';
}
