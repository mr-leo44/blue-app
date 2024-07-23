<?php
preg_match_all("/\/([^\/]+)\//i", $_SERVER['REQUEST_URI'], $match);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);

// var_dump($match);
$match=isset($match[1][0])?$match[1][0]:"/";
define('BASE_DIR', '/' . $match .  '/');
/*exit;*/
// define('BASE_DIR', $match[1][0] . '/');
// define ('FS_PATH', str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . BASE_DIR));
define ('FS_PATH', str_replace('//', '/', $_SERVER['DOCUMENT_ROOT']  . BASE_DIR));

// var_dump(FS_PATH);
?>