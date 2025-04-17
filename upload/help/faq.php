<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:
        echo '<title>F.A.Q</title>';
        echo '<div class="title">F.A.Q</div>';
        $question = dbquery("SELECT * FROM `faq` ORDER BY `id` ASC");
        while ($faq = mfa($question)) {
            echo '<a class="link" href="/faq/answer-' . intval($faq['id']) . '">' . $faq['name'] . '</a>';
        }
        break;

    case 'answer':
        $id = abs(intval($_GET['id']));
        $answer = mfa(dbquery("SELECT * FROM `faq` WHERE `id` = '" . $id . "'"));
        echo '<title>' . $answer['name'] . '</title>';
        echo '<div class="title">' . $answer['name'] . '</div>';
        echo '<div class="menu_nb">' . nl2br(smile(bb($answer['text_col']))) . '</div>';
        break;
}

//-----Подключаем низ-----//
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
