<?php
/*var_dump($_SERVER['HTTP_HOST']);
exit;*/
/* if ($_SERVER['HTTP_HOST'] != '127.0.0.1:8080' and (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')))
{
   $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}*/
if(!defined("FS_PATH")){
include_once 'config.php';
//include_once 'classes/class.utils.php';
}
require_once 'classes/Autoloader.php';
