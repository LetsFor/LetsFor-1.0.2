<?
if (!file_exists(''.$_SERVER['DOCUMENT_ROOT'].'/LFcore/config.php'))
{
	header('Location: ' . homeLink() . '/install');
	exit();
}
include ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/config.php');
DataADP::init($config['host'], $config['base'], $config['user'], $config['pass'], [PDO::ATTR_EMULATE_PREPARES => false]);
?>