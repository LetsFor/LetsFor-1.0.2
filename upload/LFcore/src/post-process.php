<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = abs(intval($_GET['id']));
    $text = LFS($_POST['msg']);

    // Проверка текста
    if (empty($text)) {
        echo '<div class="err">Введите текст сообщения</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    if (mb_strlen($text, 'UTF-8') < 3) {
        echo '<div class="err">Минимум для ввода 3 символа</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    // Проверка авторизации
    if (empty($user['id'])) {
        echo '<div class="err">Авторизуйтесь чтобы ответить</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    // Получение данных из базы данных
    $forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = ?", [$id]));
    if (!$forum_t['id']) {
        echo '<div class="err">Тема не найдена</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    $forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = ?", [$forum_t['kat']]));
    if (!$forum_k['id']) {
        echo '<div class="err">Категория не найдена</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    $forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = ?", [$forum_k['razdel']]));
    if (!$forum_r['id']) {
        echo '<div class="err">Раздел не найден</div>';
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
        exit();
    }

    // Антиспам
    $time = mfa(dbquery("SELECT `time_up` FROM `forum_post` WHERE `us` = ? ORDER BY `time_up` DESC LIMIT 1", [$user['id']]));

    if ($time) {
        $forum_antispam = mfa(dbquery("SELECT `forum_post` FROM `antispam` WHERE `forum_post`"));
        if ($forum_antispam && isset($forum_antispam['forum_post']) && (time() - $time['time_up']) < $forum_antispam['forum_post']) {
            echo '<div class="err">Пишите не чаще чем раз в ' . $forum_antispam['forum_post'] . ' секунд</div>';
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
            exit();
        }
    }

    // Транзакция для обеспечения целостности данных
    dbquery("START TRANSACTION");

    // Обновление темы
    if (!isset($forum_t['usup'])) {
        dbquery("UPDATE `forum_tema` SET `usup` = ? WHERE `id` = ?", [$forum_t['us'], $id]);
    }

    dbquery("UPDATE `forum_tema` SET `usup` = ?, `up` = ? WHERE `id` = ?", [$user['id'], time(), $id]);

    // Добавление поста
    dbquery("INSERT INTO `forum_post` SET `kat` = ?, `text_col` = ?, `us` = ?, `time_up` = ?, `tema` = ?, `razdel` = ?", [$forum_k['id'], $text, $user['id'], time(), $id, $forum_r['id']]);

    // Обновление денег пользователя
    $settings = mfa(dbquery("SELECT `forum_tem_m` FROM `settings` WHERE id = '1'"));
    if ($settings) {
        dbquery("UPDATE `users` SET `money_col` = `money_col` + ? WHERE `id` = ?", [$settings['forum_tem_m'], $user['id']]);
    }

    // Оповещение
    if ($user['id'] != $forum_t['us']) {
        dbquery("INSERT INTO `lenta` SET `readlen` = '0', `time_up` = ?, `komy` = ?, `kto` = ?, `text_col` = ?", [time(), $forum_t['us'], $user['id'], 'написал в вашей [url=' . homeLink() . '/tema' . $id . ']теме[/url]']);
    }

    dbquery("COMMIT");
}
?>