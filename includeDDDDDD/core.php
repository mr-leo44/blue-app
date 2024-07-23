<?php
// ini_set('session.gc_maxlifetime', 3600);
// session_set_cookie_params(3600);
// ini_set('session.use_cookies', true);
// error_reporting(E_ALL ^ E_NOTICE);
session_start(); 


/*
preg_match_all("/\/([^\/]+)\//i", $_SERVER['REQUEST_URI'], $match);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('BASE_DIR', $match[1][0] . '/');
define ('FS_PATH', str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . BASE_DIR));

include_once 'include/database_pdo.php';
include_once 'classes/class.utilisateur.php';  
include_once 'classes/class.site.php'; */
$APP_NAME='Blue-App-Dev';
$USER_SITENAME='NON DEFINI';
$MULTI_ACCESS_SITE_CODE= 100;
$MULTI_ACCESS_SITE_LABEL= "TOUS LES SITES";

$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db); 
$site_classe = new Site($db); 
$utilisateur->is_logged_in();
$utilisateur->readOne();
$site_classe->code_site=$utilisateur->site_id;
$site_classe->GetDetailIN();
	
$USER_SITE_ID=$site_classe->code_site;
$USER_SITENAME=$site_classe->intitule_site;
$USER_SITE_PROVINCE=$site_classe->province_id;
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
 
// set number of records per page
$records_per_page = 10;
 
// range of links to show
$range = 2; 

// echo FS_PATH;
// Include visitor log script 
// var_dump(FS_PATH);
// exit;
// include_once FS_PATH.'log.php'; 
?>