<?php
echo '<html id="LF" lang="ru" class="LetsFor">';

echo '<head>
<meta charset="UTF-8">
<meta property="og:site_name" content="' . $LF_name . ' - ' . $LF_info . '">
<meta property="og:url" content="' . homeLink() . '">
<meta property="og:type" content="website">

<meta name="author" content="LetsFor">
<meta name="generator" content="LetsFor ' . $ver . '">
<meta name="Keywords" content="' . $LF_keyw . '"/>
<meta name="description" content="' . $seo->get_description() . '">
<meta name="viewport" content="width=device-width,initial-scale = 1.0, maximum-scale = 1.0">
<meta name="referrer" content="origin-when-cross-origin">
<meta name="twitter:card" content="summary_large_image">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="HandheldFriendly" content="true"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,650;1,400;1,600;1,650&display=swap" rel="stylesheet">

<link rel="shortcut icon" href="' . $LF_fico . '" type="image/x-icon">
<link rel="icon" href="' . $LF_fico . '" type="image/x-icon">

<script src="' . homeLink() . '/LFcore/js/bootstrap.js"></script>
<script src="' . homeLink() . '/LFcore/js/simplebar.min.js"></script>
<script src="' . homeLink() . '/LFcore/js/jquery-1.12.4.min.js"></script>
<script src="' . homeLink() . '/LFcore/js/jquery-2.2.4.min.js"></script>
<script src="' . homeLink() . '/LFcore/js/jquery-3.7.1.min.js"></script>
<script src="' . homeLink() . '/LFcore/js/scripts.js"></script>
<script src="' . homeLink() . '/LFcore/js/LikeBookmark.js"></script>
<script src="' . homeLink() . '/LFcore/js/readlen.js"></script>
<script src="' . homeLink() . '/LFcore/js/choices.js"></script>
<script src="' . homeLink() . '/LFcore/js/prism.js"></script>
<script src="' . homeLink() . '/LFcore/js/fancybox.umd.js"></script>
<script src="' . homeLink() . '/LFcore/js/TweenMax.min.js"></script>
<script src="' . homeLink() . '/LFcore/js/Flow.js"></script>
</head>';

echo '<body>
<div id="loading-content">
<div id="modal-container"></div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/modal.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/headbar.php');

echo '<div id="loading" style="display:none;">';
require_once($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/loader.php');
echo '</div>';

echo '<div id="alertContainer"></div>';

echo '<div class="Home-GT" id="Home-GT">';
echo '<div class="breadcrumbs">' . $seo->get_breadcrumbs() . '</div>';
echo '<div id="overlay-onew"></div>';
echo '<div id="overlay"></div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/non-info.php');
echo '<div class="home-table">';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/set-side.php');

echo '<div class="add_set">';
echo '<div class="content" id="view">';

echo '<div class="pc">';
echo '<a id="back-top" href="#"></br></br></br><i style="font-size: 20px;" class="fas fa-angle-up"></i><br />На верх</a>';
echo '</div>';

echo '<main>';
