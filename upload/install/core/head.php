<?
ob_start();

require_once ($_SERVER['DOCUMENT_ROOT'].'/LFcore/src/function.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/LFcore/src/info.php');

$LF_fico = "".homeLink()."/favicon.ico";

echo '<head>
<meta name="robots" content="noindex">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" type="text/css" href="'.homeLink().'/install/css/install.css?d="'.time().'">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">

<link rel="shortcut icon" href="'.$LF_fico.'" type="image/x-icon">
<link rel="icon" href="'.$LF_fico.'" type="image/x-icon">
<script src="'.homeLink().'/LFcore/js/jquery-3.7.1.min.js"></script>

<title>Установка LetsFor</title>
</head>';

echo '<body>';
?>