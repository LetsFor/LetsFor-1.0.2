<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');

$id = abs(intval($_GET['id'])); // Проверяем ID поста

if (isset($_GET['getState']) && $_GET['getState'] === 'true') {
    $forum_p = mfa(dbquery("SELECT * FROM forum_post WHERE id = '$id'"));

    if ($forum_p) {
        $forum_like = mfa(dbquery("SELECT * FROM forum_like WHERE post = '" . $forum_p['id'] . "' AND us = '" . $user['id'] . "'"));
        $likeCount = mfa(dbquery("SELECT COUNT(*) as total FROM forum_like WHERE post = '" . $forum_p['id'] . "'"));

        echo $likeCount['total'] . '|' . ($forum_like ? '1' : '0'); // Возвращаем число лайков и состояние
    } else {
        echo '0|0';
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $forum_p = mfa(dbquery("SELECT * FROM forum_post WHERE id = '" . $id . "'"));
	$forum_nl = mfa(dbquery("SELECT * FROM forum_post WHERE us = '" . $forum_like['us'] . "'"));

    if ($forum_p) {
        $forum_like = mfa(dbquery("SELECT * FROM forum_like WHERE post = '" . $forum_p['id'] . "' AND us = '" . $user['id'] . "'"));

        if ($_POST['action'] === 'like' && !$forum_like) {
            dbquery("INSERT INTO forum_like SET 
                post = '" . $forum_p['id'] . "',
                us = '" . $user['id'] . "',
                tema = '" . $forum_p['tema'] . "',
                themus = '" . $forum_p['us'] . "'");
        } elseif ($_POST['action'] === 'unlike' && $forum_like) {
            dbquery("DELETE FROM forum_like WHERE id = '" . $forum_like['id'] . "'");
        }
		
		if ($forum_p['us'] == $user['id']) {
			dbquery("DELETE FROM forum_like WHERE post = '" . $forum_p['id'] . "' and us = '" . $user['id'] . "'");
		}
		dbquery("DELETE FROM forum_like WHERE us IS NULL OR us = ''");
    }

    // Возвращаем обновленное количество лайков
    $result = dbquery("SELECT COUNT(*) as total FROM forum_like WHERE post = '" . $forum_p['id'] . "'");
    $row = mfa($result);
    $hasLiked = mfa(dbquery("SELECT * FROM forum_like WHERE post = '" . $forum_p['id'] . "' AND us = '" . $user['id'] . "'"));

    echo $row['total'] . '|' . ($hasLiked ? '1' : '0');
    exit();
}
?>