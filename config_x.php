<?php
//https://www.google.com/maps/place/4%C2%B020'32.3%22S+15%C2%B018'08.5%22E/@-4.3423561,15.3023081,19.04z/data=!4m5!3m4!1s0x0:0x0!8m2!3d-4.3423157!4d15.302361
preg_match_all("/\/([^\/]+)\//i", $_SERVER['REQUEST_URI'], $match);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('BASE_DIR', $match[1][0] . '/');
define ('FS_PATH', str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . BASE_DIR));



//require_once FS_PATH.'/core/init.php';

//loading Classes filess
//Autoloader::Load(FS_PATH.'/classes');
//Olivier traitement si port non standard
//define ('BASE_URL', 'http://' . str_replace('//', '/', $_SERVER['SERVER_NAME'] . '/' . BASE_DIR));
//devient

$olivPORT = ( ($_SERVER['SERVER_PORT'] != '80') && ($_SERVER['SERVER_PORT'] != '443') ? ":".$_SERVER['SERVER_PORT'] : '');
$protocole = isset($_SERVER['HTTPS'])?$_SERVER['HTTPS']:"off";
$olivHTTP = ( $protocole == 'on' ? 'https://' : 'http://');
define ('BASE_URL', $olivHTTP . str_replace('//', '/', $_SERVER['SERVER_NAME']. $olivPORT . '/' . BASE_DIR));

?>
