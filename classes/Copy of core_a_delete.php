<?php
 
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
	
$USER_SITENAME=$site_classe->intitule_site;
$USER_SITE_PROVINCE=$site_classe->province_id;
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
 
// set number of records per page
$records_per_page = 10;
 
// range of links to show
$range = 4; 
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>