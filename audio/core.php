<?php
// ini_set('session.gc_maxlifetime', 3600);
// session_set_cookie_params(3600);
// ini_set('session.use_cookies', true);
// error_reporting(E_ALL ^ E_NOTICE);
session_start(); 
/*
include_once 'include/database_pdo.php';
include_once 'classes/class.utilisateur.php';  
include_once 'classes/class.site.php'; */
$APP_NAME='Blue-TEST';
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


// Include visitor log script 
include_once 'log.php'; 
?>