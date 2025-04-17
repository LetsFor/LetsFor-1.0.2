<?
echo '</main>';
echo '<div id="allthem-open" style="padding: 10px;"><div style="padding: 0 2px 0 2px;"></div>';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/new_tem_lite.php');
echo '</div>';

echo '</div>';
echo '<div id="loader" style="display: none;"><center>';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/loader.php');
echo '</center></div>';
echo '</div>';
echo '</div>';
echo '</div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/scripts.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/ajax.php');

echo '</body></html>';
?>
