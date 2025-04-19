<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = abs(intval($_POST['id']));
    $mess = mfa(dbquery("SELECT * FROM `users` WHERE `id` = ?", [$id]));

    if (isset($mess['id']) && $user['id'] == $id) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка!']);
        exit;
    }

    $di = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = ? AND `kogo` = ? LIMIT 1", [$user['id'], $mess['id']]));

    if ($di['del'] == 1) {
        echo json_encode(['status' => 'error', 'message' => 'Диалог был удален!']);
        exit;
    }

    $ignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = ? AND `kogo` = ?", [$mess['id'], $user['id']]));
    $youignor = mfa(dbquery("SELECT * FROM `message_c` WHERE `kto` = ? AND `kogo` = ?", [$user['id'], $mess['id']]));

    if (isset($_POST['msg'])) {
        $text = LFS($_POST['msg']);

        if ($ignor['ignor'] == 1) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь добавил Вас в игнор лист!']);
            exit;
        }

        if (empty($text) || mb_strlen($text) < 3) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка ввода, минимум 3 символа!']);
            exit;
        }

        $tim = dbquery("SELECT * FROM `message` WHERE `kto` = ? ORDER BY `time_up` DESC", [$user['id']]);

        while ($ncm2 = mfa($tim)) {
            $news_antispam = mfa(dbquery("SELECT * FROM `antispam` WHERE `mes`"));
            $ncm_timeout = $ncm2['time_up'];

            if ((time() - $ncm_timeout) < $news_antispam['mes']) {
                echo json_encode(['status' => 'error', 'message' => 'Пишите не чаще чем раз в ' . $news_antispam['mes'] . ' секунд!']);
                exit;
            }
        }

        // Обработка файла
        if ($user['form_file'] == 1 && isset($_FILES['filename'])) {
            $maxsize = 25; // Максимальный размер файла, в мегабайтах
            $size = $_FILES['filename']['size']; // Вес файла

            if (@file_exists($_FILES['filename']['tmp_name'])) {
                if ($size > (1048576 * $maxsize)) {
                    echo json_encode(['status' => 'error', 'message' => 'Максимальный размер файла ' . $maxsize . 'мб!']);
                    exit;
                }
                // Здесь можно добавить код для сохранения файла
            }
        }

        // Здесь можно добавить код для сохранения сообщения
        echo json_encode(['status' => 'success', 'message' => 'Сообщение отправлено!']);
        exit;
    }
}
