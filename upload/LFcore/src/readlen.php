<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/function.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/db-connect.php');
$user = checkCookie();

if (isset($user)) {
	$len = msres(dbquery("SELECT COUNT(id) FROM lenta WHERE komy = '" . $user['id'] . "' and readlen = '0'"), 0);
	$mes = msres(dbquery("SELECT COUNT(id) FROM message WHERE komy = '" . $user['id'] . "' and readlen = '0'"), 0);
} else {
	$len = 0;
	$mes = 0;
}

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
	case 'all':
	header('Content-Type: application/json');
	
	$data = [
    "mes" => $mes,
    "len" => $len
	];

	echo json_encode($data);
	break;
}
?>