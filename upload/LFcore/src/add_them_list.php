<?
echo '<div class="thems" id="thems-kat">';
while ($a = mfa($forum)) {
    require ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/div-link-thems-info.php');
}
echo '</div>';
?>