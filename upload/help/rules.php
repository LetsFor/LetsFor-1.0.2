<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

echo '<title>Правила форума - ' . $LF_name . '</title>';
echo '<div class="title">Правила форума</div>';

// Получаем категории правил
$rule_kat = dbquery("SELECT * FROM `rules` ORDER BY `id` ASC");
while ($rule = mfa($rule_kat)) {
    echo '<div class="menu_nb"><h2 style="margin: 5px 0;">' . $rule['kat'] . '</h2>';
    echo '<div id="text">' . nl2br(bb($rule['text_col'])) . '</div>';
    echo '</div>';
}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
