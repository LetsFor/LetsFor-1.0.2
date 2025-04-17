<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');


echo '<title>Ключевые слова</title>';
echo '<div class="title">Ключевые слова</div>';
$tooltips = dbquery("SELECT * FROM `forum_tooltips` ORDER BY `id` ASC");
echo '<table class="dataTable">
<tbody>';

echo '<tr class="dataRow top">
<th>Понятие</th>
<th>Описание</th>
<th>Ключевые слова</th>		
</tr>';

while ($tt = mfa($tooltips)) {
    echo '<tr class="dataRow">
<th>' . $tt['definition'] . '</th>
<th>' . $tt['tooltip'] . '</th>
<th>' . $tt['word'] . '</th>';
    echo '</tr>';
}

echo '</tbody>
</table>';

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
