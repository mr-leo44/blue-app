<?php

date_default_timezone_set('Africa/Kinshasa');
// ini_set('session.gc_maxlifetime', 3600);
// each client should remember their session id for EXACTLY 1 hour
// session_set_cookie_params(3600);
/////////////////////////////////////////////////////////////:
// ini_set('session.use_trans_sid', false);
// ini_set('session.use_cookies', true);
// ini_set('session.use_only_cookies', true);
// $https = false;
// if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') $https = true;
// $dirname = rtrim(dirname($_SERVER['PHP_SELF']), '/').'/';
// session_name('t_session_key');
// session_set_cookie_params(0, $dirname, $_SERVER['HTTP_HOST'], $https, true);
// session_start(); 
require_once './vendor/autoload.php';
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once "core.php";
/*
require_once("include/database_pdo.php"); 
include_once 'classes/class.utilisateur.php';
include_once 'classes/class.installation.php';
include_once 'classes/class.site.php'; 
include_once 'classes/class.group_user.php';
include_once 'classes/class.identification.php';
include_once 'classes/class.utils.php';




include_once "classes/class.avistechnique.php";
include_once "classes/class.province.php";
include_once "classes/class.cvs.php";
include_once "classes/class.organisme.php";
include_once "classes/class.commune.php";
include_once "classes/class.tarif.php";*/



$view = "";
//SELECT datesys,STR_TO_DATE(datesys,'%d/%m/%Y') as dt_fr,DATE_FORMAT(datesys,'%d/%m/%Y') as dt_fr_ FROM `t_inscription_stagiaire`
$result_array = array();
// get database connection
$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
/*if(!isset($_SESSION['uSession'])){
$utilisateur->redirect('login.php');	
	exit;
}
$utilisateur->code_utilisateur=$_SESSION['uSession'];
*/

if (isset($_REQUEST["view"]))
	$view = $_REQUEST["view"];

if ($view == "reconnect") {
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$email = addslashes($_POST['username']);
		$upass = addslashes($_POST['password']);
		//if(filter_var($email,FILTER_VALIDATE_EMAIL)) { 
		$response = $utilisateur->login($email, $upass);
		if (isset($response['login']) && $response['login'] == true) {
			//$utilisateur->readOne();
			$result_array["error"] = 0;
			$result_array["message"] = "Reconnexion effectuée avec succès";
			echo json_encode($result_array);
		} else if (isset($response['login']) && $response['login'] == false) {

			$result_array["error"] = 1;
			$result_array["message"] = $response['message'];
			echo json_encode($result_array);
		}
	} else {
		$result_array["error"] = 1;
		$result_array["message"] = "Veuillez fournir les informations d'accès";
		echo json_encode($result_array);
	}
	exit;
}





if ($utilisateur->is_logged_in() == false) {
	// XMLHttpRequest
	// return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	// $headers = apache_request_headers();
	// $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		/* This is one ajax call */
		$result_array["error"] = 1;
		$result_array["reconnect"] = true;
		$result_array["message"] = "Vous devriez vous reconnecter";
		echo json_encode($result_array);
		exit;
	} else {
		$utilisateur->redirect('login.php');
	}
}
$utilisateur->readOne();

$admin_group_id = "3";




function user_agent_matches_session()
{
	if (!isset($_SESSION['user_agent'])) {
		return false;
	}
	if (!isset($_SERVER['HTTP_USER_AGENT'])) {
		return false;
	}
	return ($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']);
}

function last_login_is_recent()
{
	$recent_limit = 60 * 60 * 24 * 1; // 1 day
	if (!isset($_SESSION['last_login'])) {
		return false;
	}
	return (($_SESSION['last_login'] + $recent_limit) >= time());
}
/*
if(!isset($_POST['session_ID']))
{

    $json[] = array(
        'return' => $errors_authentification,
        'error_msg' => "User not authenticated"
    );

    echo json_encode($json);
    return;
}

session_id($_POST['session_ID']);
session_start();*/



// if($utilisateur->is_logged_in()=="")
// {
/*if(!isset($_SESSION['user_agent'])) { 
		if(!isset($_SERVER['HTTP_USER_AGENT'])) { 
			return false; 
		}
		if($_SERVER['HTTP_USER_AGENT']
		if(($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']){
			if(isset($_REQUEST["view"]))
	$view = $_REQUEST["view"];
	if($view== "Login"){//Mobile
		$data = file_get_contents("php://input");			
		$ticket = new Ticket($db);
		$ticket->Login($data);
		
		}

		}
		if(isset($_POST['sess_id'])){
				session_id($_POST['sess_id']); //starts session with given session id
				session_start();
				$_SESSION['count']++;
		}
		else {
			session_start(); //starts a new session
			$_SESSION['count']=0;
		}	
	else{	*/
// $utilisateur->redirect('login.php');
//}
// }else			
// {

switch ($view) {
	case "reconnect":
		if (isset($_POST['username']) && isset($_POST['password'])) {
			$email = addslashes($_POST['username']);
			$upass = addslashes($_POST['password']);
			//if(filter_var($email,FILTER_VALIDATE_EMAIL)) { 
			$response = $utilisateur->login($email, $upass);
			if (isset($response['login']) && $response['login'] == true) {
				//$utilisateur->readOne();
				$result_array["error"] = 0;
				$result_array["message"] = "Reconnexion effectuée avec succès";
				echo json_encode($result_array);
			} else if (isset($response['login']) && $response['login'] == false) {
				$error = $response['error'];
			}
		} else {
			$result_array["error"] = 1;
			$result_array["message"] = "Veuillez fournir les informations d'accès";
			echo json_encode($result_array);
		}
		break;

	case "grant_law":
		if ($utilisateur->HasDroits("12_54")) {
			if ($_POST) {
				$group = new GroupUtilisateur($db);
				$group->id_group = $_POST['group_id'];
				$group->n_user_create = $utilisateur->code_utilisateur;

				$group->GrantPrivileges($_POST['tbl-checkbox']);
				header("Location: user_group.php");
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "grant_site":
		/*if($utilisateur->HasDroits("12_56"))
			{*/
		if ($_POST) {
			$site = new Site($db);
			$site->n_user_create = $utilisateur->nom_utilisateur;
			$site->GrantSiteAccess($_POST['tbl-checkbox'], $_POST['k_m']);
			header("Location: users.php");
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;


	case "grant_article":
		if ($utilisateur->HasDroits("12_52")) {

			if ($_POST) {
				$site = new Site($db);
				$site->n_user_create = $utilisateur->nom_utilisateur;
				$site->GrantArticleAccess($_POST['tbl-checkbox'], $_POST['k_m']);
				header("Location: site_production.php");
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "get_group_user_law":
		if ($utilisateur->HasDroits("12_53")) {
			if ($_GET) {
				$group = new GroupUtilisateur($db);
				$group_id = isset($_GET["group_id"]) ? $_GET["group_id"] : "";
				$result_array["error"] = 0;
				$result_array["data"] = $group->GetDroits($group_id);
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "get_adress_menage":
		// if($utilisateur->HasDroits("12_53"))
		// {		
		// if($_GET){	

		$statut_personne = new Param_Statut_Personne($db);
		$item = new AdresseEntity($db);
		//ADRESSE
		$quartier_id = isset($_POST["quartier"]) ? $_POST["quartier"] : "";
		$commune_id = isset($_POST["commune_id"]) ? $_POST["commune_id"] : "";
		$ville_id = isset($_POST["ville_id"]) ? $_POST["ville_id"] : "";
		$numero = isset($_POST["numero_avenue"]) ? $_POST["numero_avenue"] : "";
		$avenue = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
		//ADRESSE
		$id_adresse = $item->GetOrCreateAdressId($ville_id, $commune_id, $quartier_id, $avenue, $numero);
		$result_array = $item->GetAdressMenage($id_adresse);
		$r_content = "";
		if ($result_array['count'] != 0) {
			$items = $result_array['data'];
			foreach ($items as $r_item) {
				$statut_personne->code = $r_item['statut_identity'];
				$statut_personne->GetDetailIN();

				$button_s = '<a class="btn btn-xs select-item-identite"><i class="fas fa-check"></i></a>';


				if ($utilisateur->HasDroits("10_750")) {
					$button_s .= '<a class="btn btn-xs edit-item-identite"><i class="fas fa-pencil-alt"></i></a>';
				}

				if ($utilisateur->HasDroits("10_760")) {
					$button_s .= '<a class="btn btn-xs delete-item-identite"><i class="fas fa-trash"></i></a>';
				}
				$nom_complet = $r_item['nom'] . ' ' . $r_item['postnom'] . ' ' . $r_item['prenom'];
				$r_content .= '<tr class="item-row-identite" data-id="' . $r_item['id'] . '" data-phone="' . $r_item['phone_number'] . '" data-statut="' . $r_item['statut_identity'] . '"  data-nom="' . $r_item['nom'] . '"  data-prenom="' . $r_item['prenom'] . '"  data-postnom="' . $r_item['postnom'] . '" data-lieu="' . $r_item['lieu_naissance'] . '"  data-sexe="' . $r_item['sexe'] . '"  data-name="' . trim($nom_complet) . '" data-piece="' . $r_item['num_piece_identity'] . '"><td style="width:95%"><h6 class="menage-nom">' . trim($nom_complet) . '</h6> 
												<p class="text-muted mb-0 menage-statut">' . $statut_personne->libelle . '</p></td><td>' . $button_s . '</td></tr>';
			}
		}
		$result_['count'] = $result_array['count'];
		$result_['data'] = $r_content;
		$result_['adress_id'] = $id_adresse;
		echo json_encode($result_);
		// }
		// }else{
		// DroitsNotGranted();
		// }
		break;


	case "create_menage": //"create_identite":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/
		// if($_GET){		 			
		$item = new AdresseEntity($db);
		$identite_id =  isset($_POST["identite_id"]) ? trim($_POST["identite_id"]) : "";
		$identite_adress_id =  isset($_POST["identite_adress_id"]) ? trim($_POST["identite_adress_id"]) : "";
		$identite_nom =  isset($_POST["identite_nom"]) ? trim($_POST["identite_nom"]) : "";
		$identite_postnom =  isset($_POST["identite_postnom"]) ? trim($_POST["identite_postnom"]) : "";
		$identite_prenom =  isset($_POST["identite_prenom"]) ? trim($_POST["identite_prenom"]) : "";
		$identite_sexe =  isset($_POST["identite_sexe"]) ? trim($_POST["identite_sexe"]) : "";
		$identite_lieu =  isset($_POST["identite_lieu"]) ? trim($_POST["identite_lieu"]) : "";
		$identite_piece =  isset($_POST["identite_piece"]) ? trim($_POST["identite_piece"]) : "";
		$identite_phone =  isset($_POST["identite_phone"]) ? trim($_POST["identite_phone"]) : "";
		$identite_statut =  isset($_POST["identite_statut"]) ? trim($_POST["identite_statut"]) : "";

		$site_id = $utilisateur->site_id;
		$item->n_user_create =  $utilisateur->code_utilisateur;

		$result_array = $item->CreateOrUpdateIdentite($identite_adress_id, $identite_nom, $identite_postnom, $identite_prenom, $identite_sexe, $identite_lieu, $identite_piece, $identite_phone, $identite_statut, $site_id);
		echo json_encode($result_array);
		// }
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "edit_menage": //"create_identite":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/
		// if($_GET){		 			
		$item = new AdresseEntity($db);
		$identite_id =  isset($_POST["identite_id"]) ? trim($_POST["identite_id"]) : "";
		$identite_adress_id =  isset($_POST["identite_adress_id"]) ? trim($_POST["identite_adress_id"]) : "";
		$identite_nom =  isset($_POST["identite_nom"]) ? trim($_POST["identite_nom"]) : "";
		$identite_postnom =  isset($_POST["identite_postnom"]) ? trim($_POST["identite_postnom"]) : "";
		$identite_prenom =  isset($_POST["identite_prenom"]) ? trim($_POST["identite_prenom"]) : "";
		$identite_sexe =  isset($_POST["identite_sexe"]) ? trim($_POST["identite_sexe"]) : "";
		$identite_lieu =  isset($_POST["identite_lieu"]) ? trim($_POST["identite_lieu"]) : "";
		$identite_piece =  isset($_POST["identite_piece"]) ? trim($_POST["identite_piece"]) : "";
		$identite_phone =  isset($_POST["identite_phone"]) ? trim($_POST["identite_phone"]) : "";
		$identite_statut =  isset($_POST["identite_statut"]) ? trim($_POST["identite_statut"]) : "";

		$site_id = $utilisateur->site_id;
		$item->n_user_create =  $utilisateur->code_utilisateur;

		$result_array = $item->UpdateIdentite($identite_id, $identite_nom, $identite_postnom, $identite_prenom, $identite_sexe, $identite_lieu, $identite_piece, $identite_phone, $identite_statut, $site_id);
		echo json_encode($result_array);
		// }
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "delete_menage": //"create_identite":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/
		// if($_GET){		 			
		$item = new AdresseEntity($db);
		$identite_id =  isset($_POST["id_"]) ? trim($_POST["id_"]) : "";
		$invalidation_motif =  isset($_POST["invalidation_motif"]) ? trim($_POST["invalidation_motif"]) : "";
		$item->n_user_create =  $utilisateur->code_utilisateur;

		$result_array = $item->DeleteIdentite($identite_id, $invalidation_motif);
		echo json_encode($result_array);
		// }
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "get_user_site":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/
		if ($_GET) {
			$site = new Site($db);
			$user = isset($_GET["q"]) ? $_GET["q"] : "";
			$result_array["error"] = 0;
			$result_array["data"] = $site->SiteAccessibleForUser($user);
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;



	case "visualiser_fiche_controle":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_GET) {
			$item = new CLS_Controle($db);
			$item->ref_fiche_controle = isset($_GET["q"]) ? $_GET["q"] : "";
			$data = $item->GetDetail($utilisateur->id_service_group);
			// $result_array['readOnly']=0;
			// Utils::responseJson($data);
			// EXIT;		

			// var_dump($data['fraudes']);
			// exit;								
			$e_adresse = new AdresseEntity($db);
			$etat_poc = new PARAM_EtatPOC($db);
			$pTypeDefaut = new Param_TypeDefaut($db);
			$statut_installation = new PARAM_StatutInstallation($db);
			$materiel = new Materiels($db);
			$organisme = new Organisme($db);
			$type_client = new TypeClient($db);
			$yes_no = new PARAM_YesNo($db);
			$conformity_install = new PARAM_ConformityInstall($db);
			$tarif = new Tarif($db);
			$cvs = new CVS($db);
			$accessib = new Param_Accessibility($db);
			$raccordement = new Param_Raccordement($db);
			$statut_personne = new Param_Statut_Personne($db);
			$type_compteur = new Param_TypeCompteur($db);
			$section_cable = new PARAM_Section_Cable($db);
			$type_usage = new Param_TypeUsage($db);
			$marquecompteur = new MarqueCompteur($db);

			$presence = new PARAM_Presence($db);
			$indicateur_led = new PARAM_Indicateur_led($db);
			$typeFraude = new PARAM_TypeFraude($db);
			$typeObservations = new PARAM_TypeObservation($db);
			$etat_interrupteur = new PARAM_Etat_Interrupteur($db);
			$type_conclusion = new Param_Conclusion($db);
			$p_wifi = new PARAM_WIFI_CPL($db);
			$date_installation_date = "";
			if (isset($data['infos_installation']) && isset($data['infos_installation']['date_fin_installation_fr'])) {
				$date_installation_date = $data['infos_installation']['date_fin_installation_fr'];
			}
			// $data['data']['date_identification_fr']
			$result = '<div class="row"  >  
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">INFORMATIONS GENERALES</h5>
								 <div class="row">
										
							   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">
										  
											<div class="form-group"> 
												<label>N° COMPTEUR</label>
												<div class="font-medium text-primary control-date">' . $data['data']['num_compteur_actuel'] .  '</div>              
											</div>  
											 
													
											 
											<div class="form-group"> 
												<label>NOM DU PROPRIETAIRE (Identité sur la facture SNEL)</label>
												<div class="font-medium text-primary control-date">' . $data['client']['noms'] .  '</div>                 
											</div>
											<div class="form-group"> 
												<label>NOM DU LOCATAIRE (ou Ménage à connecté)</label>
												<div class="font-medium text-primary control-date">' . $data['occupant']['noms'] .  '</div>                 
											</div>
										</div>
									</div>
								</div>
										
							   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
										  <div class="form-group">
												<label>DATE INSTALLATION</label>
												<div class="font-medium text-primary control-date">' . $date_installation_date .  '</div>                 
											</div> 
											<div class="form-group"> 
												<label>ADRESSE</label>
												<div class="font-medium text-primary control-date">' . $data['adresseTexte'] .  '</div>               
											</div> 
											
											<div class="form-group">
												<label>ACCESSIBILITE CLIENT</label>';

			$accessib->code = $data['data']['refus_access'];
			$accessib->GetDetailIN();

			$result .= '<div class="font-medium text-primary control-date">' . $accessib->libelle .  '</div> 
											</div>
											 
											
											<div class="form-group"> 
												<a class="btn btn-outline-light float-right" id="btn_map_viewer" data-toggle="modal" data-target="#myModalLeaflet" data-lat="' . $data['data']['gps_latitude'] .  '" data-lng="' . $data['data']['gps_longitude'] .  '"><i class="fas fa-map"></i> Visualiser Carte</a> 				
											</div> 	
										</div>
									</div>
								</div>
                        </div>
						 <div class="row">';

			$cvs->code = $data['data']['cvs_id'];
			$cvs->GetDetailIN();
			$result .= '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>CVS</label>
												<div class="font-medium text-primary control-date">' . $cvs->libelle .  '</div>                
											</div> 
										</div>
									</div>
								</div>
							   <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>P.A</label>
												<div class="font-medium text-primary control-date">' . $data['data']['p_a'] .  '</div>               
											</div> 
										</div>
									</div>
								</div>';

			$tarif->code = $data['data']['tarif_identif'];
			$tarif->GetDetailIN();
			$result .= '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>TARIF</label>
												<div class="font-medium text-primary control-date">' . $tarif->libelle .  '</div>                
											</div> 
										</div>
									</div>
								</div>
						
                            </div>
							
                            </div>
                        </div>
                        
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">INFORMATIONS SUR LE COMPTEUR</h5>
								 <div class="row">
                                
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                          <div class="card">
							<div class="card-body">
								<div class="form-group">';
			$yes_no->code = $data['data']['presence_inverseur'];
			$yes_no->GetDetailIN();

			$result .= '<label>PRESENCE COMPTEUR</label>
                                    <div class="font-medium text-primary control-date">' . $yes_no->libelle .  '</div>                
                                </div>';

			$presence->code = $data['data']['clavier_deporter'];
			$presence->GetDetailIN();

			$result .= '<div class="form-group">
                                    <label>CLAVIER DEPORTE</label>			
                                    <div class="font-medium text-primary control-date">' . $presence->libelle .  '</div>
                                </div>
								<div class="form-group">
                                    <label>NUMERO DE SERIE</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['numero_serie_cpteur'] .  '</div>                
                                </div>
								<div class="form-group">';


			$marquecompteur->code = $data['data']['marque_compteur'];
			$marquecompteur->GetDetailIN();
			$result .= '<label>MARQUE DU COMPTEUR</label>
                                    <div class="font-medium text-primary control-date">' . $marquecompteur->libelle .  '</div>               
                                </div>
								<div class="form-group">';

			$type_compteur->code = $data['data']['type_cpteur'];
			$type_compteur->GetDetailIN();


			$result .= '<label>TYPE COMPTEUR</label>			
                                    <div class="font-medium text-primary control-date">' . $type_compteur->libelle .  '</div>
                                </div>
								
								
								</div>
                            </div>
                        </div>
						       
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								 <div class="form-group">
                                    <label>PHOTO COMPTEUR</label>
                                    <div class="input-group" style="width: 100%;"> 
                                        <img style="height:300px;" class="form-control pull-right" src="pictures/' . $data['data']['ref_fiche_controle'] .  '_CTL_CTR.png">
                                    </div>                
                                </div>
								</div>
                            </div>
                        </div>
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group">
                                    <label>PHOTO AVANT CONTROLE</label>
									<div class="input-group" style="width: 100%;"> 
                                        <img style="height:300px;" class="form-control pull-right"  src="pictures/' . $data['data']['ref_fiche_controle'] .  '_CTL_BFR.png">
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_sceller_un">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group">
                                    <label>PHOTO APRES CONTROLE</label>

                                    <div class="input-group" style="width: 100%;"> 
                                        <img style="height:300px;" class="form-control pull-right"  src="pictures/' . $data['data']['ref_fiche_controle'] .  '_CTL_AFT.png">
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
					  
					  
					  <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_sceller_un">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group">
                                    <label>PHOTO SCELLE 1</label>


                                    <div class="input-group" style="width: 100%;"> 
                                        <img style="height:300px;" class="form-control pull-right" id="photo_sceller_un" src="pictures/' . $data['data']['ref_fiche_controle'] .  '_CTL_SC1.png">
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
					  
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_scelle_deux">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group">
                                    <label>PHOTO SCELLE 2</label>									
                                    <div class="input-group" style="width: 100%;"> 
                                        <img style="height:300px;" class="form-control pull-right" id="photo_sceller_deux" src="pictures/' . $data['data']['ref_fiche_controle'] .  '_CTL_SC2.png">
                                    </div>                
                                </div>
								</div>
                            </div>
                        </div>
					  
					  	<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">
										
										<div class="form-group">
											<label>DERNIER SCELLE COFFRET</label>
											<div class="font-medium text-primary control-date">' . $data['data']['dernier_sceller_coffret'] .  '</div>          
										</div>
										</div>
									</div>
								</div> 
								<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">
										<div class="form-group">
											<label>DERNIER SCELLE COMPTEUR</label>
											 <div class="font-medium text-primary control-date">' . $data['data']['dernier_sceller_compteur'] .  '</div>                
										</div>
										</div>
									</div>
								</div>';

			$chk_identique = $data['data']['sceller_identique'] == '1' ? 'ckecked' : '';

			$result .= ' <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">SCELLES TROUVES</h5>
								<div class="card-body">
								
								<div class="form-group">
									<label class="custom-control custom-checkbox"> 
										<input type="checkbox" checked="' . $chk_identique .  '" class="custom-control-input" disabled /><span class="custom-control-label">SCELLES IDENTIQUES AUX DERNIERS</span>
									</label>              
								</div>
								
								<div class="form-group">
                                    <label>SCELLE COFFRET</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['scelle_coffret_existant'] .  '</div>                
                                </div>
								<div class="form-group">
                                    <label>SCELLE COMPTEUR</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['scelle_cpt_existant'] .  '</div>                
                                </div>
								</div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">SCELLES POSES PAR LE CONTROLEUR</h5>
								<div class="card-body">
								<div class="form-group">
                                    <label>SCELLE COFFRET</label>
                                    <div class="font-medium text-primary">' . $data['data']['scelle_coffret_poser'] .  '</div>               
                                </div>
								<div class="form-group">
                                    <label>SCELLE COMPTEUR</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['scelle_compteur_poser'] .  '</div>                
                                </div>
								</div>
                            </div>
                        </div>                       
                        </div>
                            </div>
                        </div>
                         			
						 
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">EXAMEN DU RACCORDEMENT </h5>
								 <div class="row">                                
								   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">';
			$raccordement->code = $data['data']['type_raccordement'];
			$raccordement->GetDetailIN();


			$result .= '<div class="form-group">
													<label>TYPE RACCORDEMENT</label>
													<div class="font-medium text-primary control-date">' . $raccordement->libelle .  '</div>                
												</div>
											
								<div class="form-group">
									<label>NOMBRE D\'ARRIVEES</label>
										<div class="font-medium text-primary control-date">' . $data['data']['nbre_arrived'] .  '</div>              
												</div>';

			$section_cable->code = $data['data']['section_cable_arrived'];
			$section_cable->GetDetailIN();



			$result .= '<div class="form-group">
					<label>SECTION CABLE ARRIVEE</label>
					<div class="font-medium text-primary control-date">' . $section_cable->libelle .  '</div>                
				</div></div>
										</div>
									</div>';

			$p_wifi->code = $data['data']['par_wifi_cpl'];
			$p_wifi->GetDetailIN();
			$result .= '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
											
												<div class="form-group">
													<label>PAR WIFI/CPL</label>
													<div class="font-medium text-primary control-date">' . $p_wifi->libelle .  '</div>               
												</div>		

							<div class="form-group">
                                    <label>POSSIBILITES DE FRAUDE (EXPLIQUER)</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['possibility_fraud_expliquer'] .  '</div>                
                                </div>
											</div>
										</div>
									</div>
									</div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">EXAMEN APPROFONDI DU COMPTEUR A PREPAIEMENT</h5>
								 <div class="row">
                                
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								<div class="form-group">';

			$etat_interrupteur->code = $data['data']['etat_interrupteur'];
			$etat_interrupteur->GetDetailIN();

			$result .= '<label>INTERRUPTEUR</label>
                                    <div class="font-medium text-primary ">' . $etat_interrupteur->libelle .  '</div>                
                                </div>
								<div class="form-group">
                                    <label>CREDIT RESTANT</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['credit_restant'] .  '</div>                
                                </div>';
			$indicateur_led->code = $data['data']['indicateur_led'];
			$indicateur_led->GetDetailIN();




			$result .= '<div class="form-group">
                                    <label>INDICATEUR LED</label>
                                    <div class="font-medium text-primary control-date">' . $indicateur_led->libelle .  '</div>                
                                </div>
								<div class="form-group">
                                    <label>CONSOMMATION JOURNALIERE</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['consommation_journaliere'] .  '</div>                
                                </div>
								<div class="form-group">
                                    <label>CONSOMMATION DE 30 JOURS ACTUELS</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['consommation_de_30jours_actuels'] .  '</div>                
                                </div>
								<div class="form-group">
                                    <label>CONSOMMATION DE 30 JOURS PRECEDENTS</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['consommation_de_30jours_precedents'] .  '</div>                
                                </div>
								
								</div>
                            </div>
                        </div>
						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								<div class="form-group">
									<label class="custom-control custom-checkbox">';
			$cas_de_fraude_chk = $data['data']['cas_de_fraude'] == 'Oui' ? "checked" : "";

			$result .= '<input type="hidden" name="cas_de_fraude"  > 
										<input type="checkbox"  disabled  class="custom-control-input" ' . $cas_de_fraude_chk . '><span class="custom-control-label">CAS DE FRAUDE</span>
									</label>              
								</div> ';
			if ($data['data']['cas_de_fraude'] == 'Oui') {
				$result .= ' 
									<div class="form-group">
										<label class="custom-control custom-checkbox">';
				$client_reconnait_pas_chk = $data['data']['client_reconnait_pas'] == 'Oui' ? "checked" : "";

				$result .= '<input type="checkbox" disabled class="custom-control-input"' . $client_reconnait_pas_chk . '><span class="custom-control-label">CLIENT RECONNAIT</span>
										</label>              
									</div> 
									<div class="form-group">';

				$result .= '<label>TYPE DE FRAUDE</label>';
				// $typeFraude->code=$data['data']['type_fraude'];
				$fiche_list_fraudes = $data['fraudes'];
				$lst_fraudes_selected = "";
				if (!empty($data['fraudes'])) {
					foreach ($fiche_list_fraudes as $value_) {
						$lst_fraudes_selected .= "'" . $value_['ref_code_fraude'] . "',";
					}
					$clean_list = " where code in (" . rtrim($lst_fraudes_selected, ",") . ")";
					// $typeFraude->GetDetailIN();		

					$stmt_lst_fraudes = $typeFraude->readinList($clean_list);
					$result .= '<div class="card shadow border-0 mb-5" style="height:250px;overflow-y:scroll">
										<div class="card-body">  
											<ul class="list-group table table-hover"   >';

					while ($row_gp = $stmt_lst_fraudes->fetch(PDO::FETCH_ASSOC)) {

						$result .= '<li class="list-group-item rounded-0 lstr-fraude-item">
													<div class="custom-control custom-checkbox">
														<input class="custom-control-input" type="checkbox"   checked="checked">
														<label class="cursor-pointer font-italic d-block custom-control-label lst-fraude-item-label" >' .   $row_gp["libelle"] . '</label>
													</div>
												</li>';
					}
					$result .= '	</ul>
										</div>
										</div>';
				}



				$result .= '</div>
									<div class="card">
											<div class="card-body">			
											 <div class="form-group">
												<label>PHOTO SIGNATURE CLIENT</label>
												<div class="input-group" style="width: 100%;"> 
													<img style="height:300px;" class="form-control pull-right"  src="pictures/' . $data['data']['ref_fiche_controle'] . '_CTL_SGN.png">
												</div>                
											</div>
											</div> 
									</div>';
				$refus_client_de_signer = $data['data']['refus_client_de_signer'] == 'on' ? "checked" : "";



				$result .= '<div class="form-group">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input"  disabled  ' . $refus_client_de_signer . '><span class="custom-control-label">Client Refuse de signer</span>
												</label>              
											</div>';
			}


			$result .= '<div class="form-group">
                                    <label>AUTOCOLLANT PLACE</label>
                                    <div class="font-medium text-primary control-date">' . $data['adresseTexte'] .  '</div>               
                                </div>
								<div class="form-group">';
			$yes_no->code = $data['data']['autocollant_place_controleur'];
			$yes_no->GetDetailIN();

			$result .= '<label>AUTOCOLLANT TROUVE</label>
                                    <div class="font-medium text-primary control-date">' . $yes_no->libelle .  '</div>               
                                </div>
								<div class="form-group">
                                    <label>DATE DERNIER TICKET</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['date_de_dernier_ticket_rentre_fr'] .  '</div>               
                                </div>
								<div class="form-group">
                                    <label>VALEUR DU DERNIER TICKET</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['valeur_du_dernier_ticket'] .  '</div>               
                                </div>
								<div class="form-group">
                                    <label>INDEX DE TARIF DU COMPTEUR</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['index_de_tarif_du_compteur'] .  '</div>               
                                </div>
								
								
								</div>
                            </div>
                        </div></div>
                            </div>
                        </div>
                               
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                    <label>OBSERVATIONS</label>';


			$fiche_list_observations = $data['codes_observations'];
			$lst_observs_selected = "";
			if (!empty($data['codes_observations'])) {
				foreach ($fiche_list_observations as $value_) {
					$lst_observs_selected .= "'" . $value_['ref_code_obs'] . "',";
				}
				$clean_list = " where code in (" . rtrim($lst_observs_selected, ",") . ")";

				$stmt_lst_obs = $typeObservations->readinList($clean_list);
				$result .= '<div class="card shadow border-0 mb-5" style="height:250px;overflow-y:scroll">
										<div class="card-body">  
											<ul class="list-group table table-hover"   >';

				while ($row_gp = $stmt_lst_obs->fetch(PDO::FETCH_ASSOC)) {

					$result .= '<li class="list-group-item rounded-0 lstr-fraude-item">
													<div class="custom-control custom-checkbox">
														<input class="custom-control-input" type="checkbox"   checked="checked">
														<label class="cursor-pointer font-italic d-block custom-control-label lst-fraude-item-label" >' .   $row_gp["libelle"] . '</label>
													</div>
												</li>';
				}
				$result .= '	</ul>
										</div>
										</div>';
			}

			$result .= '</div>
                        </div>
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                    <label>DIAGNOSTIC GENERAL (AVIS EQUIPE TECHNIQUE)</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['diagnostics_general'] .  '</div>                
                                </div>
                        </div>
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                    <label>AVIS DU CLIENT</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['avis_client'] .  '</div>                
                                </div>
                        </div>
			 
	
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card">
								<div class="card-body">
								<div class="form-group">';

			$type_conclusion->code = $data['data']['typ_conclusion'];
			$type_conclusion->GetDetailIN();
			$result .= '<label>Conclusion contrôle</label>
											<div class="font-medium text-primary control-date">' .  $type_conclusion->libelle .  '</div>               
											</div>                
											</div>                
										</div>
                                </div>
 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card">
								<div class="card-body">
								<div class="form-group">';
			$organisme->ref_organisme = $data['data']['id_organisme_control'];
			$organisme->GetDetailIN();

			$result .= '<label>SOCIETE EN CHARGE DU CONTROLE</label>
											<div class="font-medium text-primary">' .  $organisme->denomination .  '</div>                
											</div>                
											</div>                
										</div>
                                </div> ';
			$controleur = $utilisateur->GetUserDetailName($data['data']['controleur']);
			$chef_equipe = $utilisateur->GetUserDetailName($data['data']['chef_equipe_control']);
			$result .= '  <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="card">
								<div class="card-body">
								<div class="form-group">
											<label>CHEF D\'EQUIPE CONTROLE<span class="ml-1 text-danger">*</span></label>
											<div class="font-medium text-primary control-date">' . $chef_equipe .  '</div>               
											</div>                
											</div>                
										</div>
                                </div>';
			$result .= ' <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
								<div class="card-body">	
										<div class="form-group">
											<label>CONTROLEUR <span class="ml-1 text-danger">*</span></label>
											<div class="font-medium text-primary control-date">' . $controleur .  '</div>               
											</div>                
											</div>                
										</div>
                                </div>';


			// $result_array["error"]=$data['error']; 		
			$result_array["data"] = $result;
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;


	case "visualiser_fiche_installation":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/

		$organisme = new Organisme($db);
		$marquecompteur = new MarqueCompteur($db);
		$cvs = new CVS($db);
		$materiel = new Materiels($db);
		$pTypeDefaut = new Param_TypeDefaut($db);
		$section_cable = new PARAM_Section_Cable($db);
		$commune = new AdresseEntity($db);
		$accessib = new Param_Accessibility($db);
		$raccordement = new Param_Raccordement($db);
		$type_compteur = new Param_TypeCompteur($db);
		$type_usage = new Param_TypeUsage($db);
		$etat_poc = new PARAM_EtatPOC($db);
		$statut_installation = new PARAM_StatutInstallation($db);
		$type_client = new TypeClient($db);
		$yes_no = new PARAM_YesNo($db);
		$conformity_install = new PARAM_ConformityInstall($db);
		$tarif = new Tarif($db);
		//$statut_personne = new Param_Statut_Personne($db); 
		$type_usage = new Param_TypeUsage($db);

		$fiche = isset($_GET["q"]) ? $_GET["q"] : "";

		$result = "";
		$brouillon = "";
		//if($_GET){	
		$item = new Installation($db);
		$item->id_install = isset($_GET["q"]) ? $_GET["q"] : "";
		$data = $item->GetDetail("");
		//var_dump($tmp_array); 						 
		//Utils::responseJson($result_array);

		if ($data['count'] != 0) {

			$tarif->code = $data['data']['code_tarif'];
			$tarif->GetDetailIN();
			$result = '<div class="row"> 
					
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">INFORMATIONS DU CLIENT</h5>
								 <div class="row">
										
							   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">
											<div class="form-group"> 
												<label>NOM DU PROPRIETAIRE (Identité sur la facture SNEL)</label>
												 <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['nom_client_blue'] .  '</div>
										</div>                
											</div>
											<div class="form-group"> 
												<label>NOM DU LOCATAIRE (ou Ménage à connecté)</label>
												<div class="input-group"  style="width: 100%;" > 
													<div class="font-medium text-primary ">' . $data['data']['nom_occupant'] .  '</div>
												</div>                
											</div>
											
                                <div class="form-group">
                                    <label>TARIF INSTALLATION</label>			
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $tarif->libelle .  '</div>
										</div> 
                                </div>
										</div>
									</div>
								</div>
										
							   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
										  <div class="form-group">
												<label>DATE IDENTIFICATION</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['date_identification_fr'] .  '</div>
										</div>                
											</div> 
											<div class="form-group"> 
												<label>ADRESSE</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['adresseTexte'] .  '</div>
										</div>                
											</div>';
			$accessib->code = $data['data']['accessibility_client'];
			$accessib->GetDetailIN();

			$result .= '<div class="form-group">
												<label>ACCESSIBILITE CLIENT</label>			
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $accessib->libelle .  '</div>
										</div> 
											</div>';

			if (isset($data['data']['gps_longitude']) && isset($data['data']['gps_latitude'])) {
				$result .= '<div class="form-group"> 
<a class="btn btn-outline-light float-right" data-lng="' . $data['data']['gps_longitude'] .  '"  data-lat="' . $data['data']['gps_latitude'] .  '" data-toggle="modal" data-target="#myModalLeaflet" ><i class="fas fa-map"></i> Visualiser Carte</a>	';
				//<!-- <a class="btn btn-outline-light float-right" id="btn_map_viewer" data-toggle="modal" data-target="#myModalLeaflet" data-lat='-4.34176' data-lng='15.299379199999999'><i class="fas fa-map"></i> Visualiser Carte</a>	-->					
				$result .= '</div>';
			}
			$result .= '</div>
									</div>
								</div>
                        </div>
						 <div class="row">';

			$cvs->code = $data['data']['cvs_id'];
			$cvs->GetDetailIN();

			$result .= '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>CVS</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $cvs->libelle .  '</div>
										</div>                
											</div> 
										</div>
									</div>
								</div>
							   <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>P.A</label>
												<div class="input-group"  style="width: 100%;" > 
													<div class="font-medium text-primary ">' . $data['data']['accessibility_client'] .  '</div>
												</div>                
											</div> 
										</div>
									</div>
								</div>';


			$tarif->code = $data['data']['tarif_identif'];
			$tarif->GetDetailIN();
			$result .= '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>TARIF</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $tarif->libelle .  '</div>
										</div>                
											</div> 
										</div>
									</div>
								</div>
                            </div>
							
                            </div>
                        </div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                         
						<div class="card">
                                    <div class="card-header d-flex">
                                        <h4 class="mb-0">PHOTO PA</h4>
                                    </div>
                                    <div class="card-body">
									  <div class="row">';
			$photos = $data['photos'];
			foreach ($photos as $photo) {
				$result .= '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" >
													<div class="card">
														<div class="card-body">												   
															<label> </label>  <a href="pictures/'  . $photo['ref_photo'] . '.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a><div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right"  src="pictures/' . $photo['ref_photo'] . '.png"> ' .
					'</div>                
														</div>
														</div> 
												</div>';
			}

			$result .= '</div>
                                         
                                    </div>
                                </div>
                           </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">INFORMATIONS SUR  LE RACCORDEMENT </h5>
								 <div class="row">
                                
								   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>CABINE</label>
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary "> - </div>
										</div>                 
												</div>
												<div class="form-group">
													<label>N° DEPART</label>
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['num_depart'] .  '</div>
										</div>                
												</div>
												<div class="form-group">
													<label>N° POTEAU</label>
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['num_poteau'] .  '</div>
										</div>';

			$raccordement->code = $data['data']['type_raccordement'];
			$raccordement->GetDetailIN();

			$section_cable->code = $data['data']['section_cable_alimentation'];
			$section_cable->GetDetailIN();
			$result .= '</div>
												<div class="form-group">
													<label>TYPE RACCORDEMENT</label>
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $raccordement->libelle .  '</div>
										</div>                
												</div>
											</div>
										</div>
									</div>
								   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
								<div class="form-group">
									<label>NOMBRE D\'ALIMENTATION</label>
										<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['nbre_alimentation'] .  '</div>
										</div>                
												</div>
								
												<div class="form-group">
													<label>SECTION CABLE D\'ALIMENTATION</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $section_cable->libelle .  '</div>
										</div>               
												</div>';

			$section_cable->code = $data['data']['section_cable_alimentation_deux'];
			$section_cable->GetDetailIN();
			$result .= '<div class="form-group">
													<label>SECTION CABLE D\'ALIMENTATION 2</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $section_cable->libelle .  '</div>
										</div>               
												</div>';

			$section_cable->code = $data['data']['section_cable_sortie'];
			$section_cable->GetDetailIN();

			$result .= '<div class="form-group">
													<label>SECTION CABLE DE SORTIE</label>
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $section_cable->libelle .  '</div>
										</div>                 
												</div>';

			$yes_no->code = $data['data']['presence_inverseur'];
			$yes_no->GetDetailIN();

			$result .= '<div class="form-group">
													<label>PRESENCE INVERSEUR</label>
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $yes_no->libelle .  '</div>
										</div> 
												</div>
												
											</div>
										</div>
									</div>		
                        </div>
                            </div>
                        </div>';

			if ($data['data']['type_installation'] == '0') {
				$result .= '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="block_post_paie">
                            <div class="card">
                                <h5 class="card-header">INFORMATIONS COMPTEUR POST-PAIE</h5>
								 <div class="row">';

				$yes_no->code = $data['data']['post_paie_trouver'];
				$yes_no->GetDetailIN();

				$result .= '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								<div class="form-group">
                                    <label>COMPTEUR POST-PAIE EXISTE</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $yes_no->libelle .  '</div>
										</div>                
                                </div>
								<div class="form-group">
                                    <label>MARQUE COMPTEUR POST-PAIE</label>
									<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['marque_cpteur_post_paie'] .  '</div>
										</div> 									
                                </div>
								<div class="form-group">
                                    <label>NUMERO DE SERIE</label><div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['num_serie_cpteur_post_paie'] .  '</div>
										</div> 									
                                </div>
								</div>
                            </div>
                        </div>
						 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">

								<div class="form-group">
                                    <label>INDEX OU CREDIT RESTANT</label>
									<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['index_credit_restant_cpteur_post_paie'] .  '</div>
										</div> 									
                                </div>	
								<div class="form-group">
									<label>PHOTO COMPTEUR POST-PAIE</label>  <a href="pictures/' . $data['data']['id_install'] . '_INST_POST.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a>
									<div class="input-group"  style="width: 100%;" > 
										<img style="height:300px;" class="form-control pull-right" src="pictures/' . $data['data']['id_install'] . '_INST_POST.png" />
									</div> 								
								</div>
                            
                        </div>
                        </div>
                            </div>
                        </div>
                        </div>
                        </div>';
			} else {
				$result  .= '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  id="block_remplacement">
                            <div class="card">
                                <h5 class="card-header">INFORMATIONS DU COMPTEUR DEFECTUEUX</h5>
								 <div class="row">';
				$marquecompteur->code = $data['data']['marque_cpteur_replaced'];
				$marquecompteur->GetDetailIN();

				$result .= '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								<div class="form-group">
                                    <label>MARQUE</label>
									<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $marquecompteur->libelle .  '</div>
										</div>                
                                </div>
								<div class="form-group">
                                    <label>NUMERO DE SERIE</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['num_serie_cpteur_replaced'] .  '</div>
										</div> 
                                </div>
								 <div class="form-group" id="bloc_photo_cpteur_defectueux">
                                    <label>PHOTO COMPTEUR DEFECTUEUX</label>  <a href="pictures/' . $data['data']['id_install'] . '_INST_DFT.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a> 
                                    <div class="input-group"  style="width: 100%;" > 
                                        <img style="height:300px;" class="form-control pull-right" src="pictures/' . $data['data']['id_install'] . '_INST_DFT.png"/>
                                    </div>                
                                </div>
								</div>
                            </div>
                        </div>
						 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">

								<div class="form-group">
                                    <label>INDEX OU CREDIT RESTANT</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['index_credit_restant_cpteur_replaced'] .  '</div>
										</div>                 
                                </div>';
				$pTypeDefaut->code = $data['data']['type_defaut'];
				$pTypeDefaut->GetDetailIN();
				$result .= '<div class="form-group">
                                    <label>TYPE DEFAUT</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $pTypeDefaut->libelle .  '</div>
										</div>      
                                </div>
								</div>
                            </div>
                        </div>
                        </div>
                            </div>
                        </div>';
			}
			$result .= '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">NOUVEAU COMPTEUR</h5>
								 <div class="row">';


			$marquecompteur->code = $data['data']['marque_compteur'];
			$marquecompteur->GetDetailIN();

			$result .= '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                          <div class="card">
							<div class="card-body">
								<div class="form-group">
                                    <label>MARQUE NOUVEAU COMPTEUR</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $marquecompteur->libelle .  '</div>
										</div>                
                                </div>
								<div class="form-group">
                                    <label>NUMERO DE SERIE</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['numero_compteur'] .  '</div>
										</div>               
                                </div>
								<div class="form-group">
                                    <label>INDEX PAR DEFAUT</label>
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['index_par_defaut'] .  '</div>
										</div>               
                                </div>';
			$type_compteur->code = $data['data']['type_new_cpteur'];
			$type_compteur->GetDetailIN();
			$result .= '<div class="form-group">
                                    <label>TYPE COMPTEUR</label>			
                                    <div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $type_compteur->libelle .  '</div>
										</div> 
                                </div>';
			$replace_client_disjonct = $data['data']['replace_client_disjonct'] == 'on' ? "checked" : "";
			$result .= '<div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"  disabled ' . $replace_client_disjonct . '/><span class="custom-control-label">Disjoncteur Remplacé par celui du client?</span>
                            </label>              
                        </div> 
						
								<div class="form-group">
                                    <label>AMPERAGE DISJONCTEUR</label>
									<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['client_disjonct_amperage'] .  '</div>
										</div> 									
                                </div>
								
								<div class="form-group">
                                    <label>AUTOCOLLANT POSE</label>
									<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['is_autocollant_posed'] .  '</div>
										</div> 
                                </div>
								
								
								</div>
                            </div>
                        </div>
						       
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_nouveau_cpteur">
                            <div class="card">
								<div class="card-body">
								 <div class="form-group">
                                    <label>PHOTO NOUVEAU COMPTEUR  </label>  <a href="pictures/' . $data['data']['id_install'] . '_INST_CTR.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <img style="height:300px;" class="form-control pull-right" src="pictures/' . $data['data']['id_install'] . '_INST_CTR.png" />
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
						  
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_avant_install">
                            <div class="card">
								<div class="card-body">		
								 <div class="form-group">
                                    <label>PHOTO AVANT INSTALLATION</label>   <a href="pictures/' . $data['data']['id_install'] . '_INST_BFR.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <img style="height:300px;" class="form-control pull-right" src="pictures/' . $data['data']['id_install'] . '_INST_BFR.png" />
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
					  
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_apres_install">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group">
                                    <label>PHOTO APRES INSTALLATION</label>  <a href="pictures/' . $data['data']['id_install'] . '_INST_AFT.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <img style="height:300px;" class="form-control pull-right" src="pictures/' . $data['data']['id_install'] . '_INST_AFT.png" />
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
					  
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_sceller_un">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group">
                                    <label>PHOTO SCELLE 1</label>  <a href="pictures/' . $data['data']['id_install'] . '_INST_SC1.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <img style="height:300px;" class="form-control pull-right"  src="pictures/' . $data['data']['id_install'] . '_INST_SC1.png" />
                                    </div>                
                                </div>
                                </div>
                                </div>
                                </div>
					  
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_scelle_deux">
                            <div class="card">
								<div class="card-body">			
								 <div class="form-group" >
                                    <label>PHOTO SCELLE 2</label>  <a href="pictures/' . $data['data']['id_install'] . '_INST_SC2.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <img style="height:300px;" class="form-control pull-right"  src="pictures/' . $data['data']['id_install'] . '_INST_SC2.png"/>
                                    </div>                
                                </div>
								</div>
                            </div>
                        </div>
						        
                       <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">   
											<div class="form-group"> 
												<label>SCELLE COMPTEUR</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['scelle_un_cpteur'] .  '</div>
										</div>                
											</div>  
								</div>
                            </div>
                        </div> 
						        
                       <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                            <div class="card"> 
										<div class="card-body">  
											<div class="form-group"> 
												<label>SCELLE COFFRET</label>
												<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['scelle_deux_coffret'] .  '</div>
										</div>                 
											</div>  
								</div>
                            </div>
                        </div>        
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
                                <div class="form-group">
                                    <div class="table-responsive table-bordered table-hover" style="height:250px;">
                                        <table class="table no-wrap p-table  ui-sortable"><thead><tr><th style="width:5%">N°</th><th style="width:90%">Matériel</th><th>Qté</th></tr>
                                            </thead>											
                                            <tbody>';


			$matos = $data['items'];
			foreach ($matos as $mat_item) {
				$result .= '<tr class="item-row"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' . $mat_item['designation'] . '</span></td><td><span class="qte">' . $mat_item['qte_identification'] . '</span></td></tr>';
			}


			$result .= '</tbody>
                                        </table>
                                    </div>         
                                </div>
								</div>
                            </div>
                        </div>
                        </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">COMMENTAIRE DE L\'INSTALLATEUR</h5>
								 <div class="row">
                                
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
					<div class="form-group">';
			$type_usage->code = $data['data']['usage_electricity'];
			$type_usage->GetDetailIN();
			$result .= '<label>TYPE DE CLIENT</label>			
							<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $type_usage->libelle .  '</div>
										</div> 
													</div>
								</div>
                            </div>
                        </div>
						      
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">

					<div class="form-group">';
			$etat_poc->code = $data['data']['etat_poc'];
			$etat_poc->GetDetailIN();

			$organisme->ref_organisme = $data['data']['id_equipe'];
			$organisme->GetDetailIN();
			$result .= '<label>ETAT POC</label>			
														<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $etat_poc->libelle .  '</div>
										</div> 
													</div>
								</div>
                            </div>
                        </div>   
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
					<div class="form-group">
							<label>SOCIETE EN CHARGE DE L\'INSTALLATION</label>			
													<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $organisme->denomination .  '</div>
										</div> 
													</div>
								</div>
                            </div>
                        </div>
                        </div>
                            </div>
                        </div>
						
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                    <label>COMMETAIRE INSTALLATEUR</label>
                                    <div class="input-group" style="width: 100%;"> 
										<div class="font-medium text-primary ">' . $data['data']['commentaire_installateur'] .  '</div>
									</div>                
                            </div>
                        </div>
								 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
								<div class="card-body">
								<div class="form-group">';
			$installateur = $utilisateur->GetUserDetailName($data['data']['installateur']);
			$chef_equipe = $utilisateur->GetUserDetailName($data['data']['chef_equipe']);
			$result .= '<label>CHEF D\'EQUIPE INSTALLATION*</label>
											<div class="input-group"  style="width: 100%;" > 
					<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $chef_equipe .  '</div>
										</div> 										
											</div>                
											</div>                
											</div>                
										</div>
                                </div>
								 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<div class="card">
								<div class="card-body">	
										<div class="form-group">
											<label>INSTALLATEUR *</label>
											<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $installateur .  '</div>
										</div> 
											</div>                
											</div>                
											</div>
                                </div>';

			$installateurs_suppl = "";

			$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_log_installation_users.ref_inst_ FROM t_log_installation_users INNER JOIN t_utilisateurs ON t_log_installation_users.ref_user = t_utilisateurs.code_utilisateur where t_log_installation_users.ref_inst_=:ref_inst_";
			$stmt = $db->prepare($query);
			$stmt->bindValue(":ref_inst_", $fiche);
			$stmt->execute();
			$ro = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($ro) > 0) {
				$result .= '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="card">
								<div class="card-body">	
										<div class="form-group">
											<label>INSTALLATEURS SUPPLEMENTAIRES *</label>
											<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">';

				foreach ($ro as $ins_suppItem) {
					$installateurs_suppl .= "  "  . $ins_suppItem["nom_complet"] . ",";
				}
				$clean = rtrim($installateurs_suppl, ",");
				$result .= $clean .  '</div>
										</div> 
											</div>                
											</div>                
											</div>
                                </div>';
			}

			if ($utilisateur->HasDroits("10_80")) {
				if (strtolower($data['data']['compteur_desaffecte']) == "0") {
					$temp_btn =  '<a id="desaffect-compteur" href="#" class="btn btn-outline-dark float-right ml-2" data-name-install="' . $data["data"]["nom_client_blue"] . '" data-id-install="' . $data["data"]["id_install"] . '">Désaffecter</a>';

					$desaffect_container = '
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 "> 
							<label>DESAFFECTER UN COMPTEUR</label> 
							<form class="form-inline" id="desaffection-form">
								<div class="form-group " style="width: 80%;">
									<select required class="form-control w-100" id="raisonsDesaffectation" name="raisonsDesaffectation">
										<option value="" selected  >Raison de la désaffectation...</option>
										<option value="Installation désactivée">Installation désactivée</option>
										<option value="Compteur défectueux">Compteur défectueux</option>
										<option value="Changement de propriétaire">Changement de propriétaire</option>
										<option value="Maintenance planifiée">Maintenance planifiée</option>
										<option value="Déménagement de l\'utilisateur">Déménagement de l\'utilisateur</option>
										<option value="Fin de contrat">Fin de contrat</option>
										<option value="Réaménagement de l\'espace">Réaménagement de l\'espace</option>
										<option value="Modernisation de l\'équipement">Modernisation de l\'équipement</option>
										<option value="Problème d\'alimentation électrique">Problème d\'alimentation électrique</option> 
									</select>
								</div>
								' . $temp_btn . '
							</form>  
						</div>
					';

					$result .= $desaffect_container;
				}
			}

			$result .= ' <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="card">
								<div class="card-body">	
										<div class="form-group">';

			if ($data["data"]["is_draft_install"] != '1') {
				if ($utilisateur->HasDroits("10_555")) {
					if ($data["data"]["statut_installation"] == '0') {
						// <button  class="dropdown-item "    >Clôturer</button>';


						$result .=  '<a class="btn btn-outline-light float-right cloture-install"  data-id-install="' . $data["data"]["id_install"] . '" data-compteur-install="' . $data["data"]["numero_compteur"] . '" data-name-install="' . $data["data"]["nom_client_blue"] . '"> Clôturer</a>';
					}
				}
			}


			if ($utilisateur->HasDroits("10_70") || $utilisateur->HasDroits("10_550")) {
				if ($utilisateur->HasDroits("10_70")) {
					$result .=  '<a href="#"  class="btn btn-outline-light float-right  edit-install"  data-id-install="' . $data["data"]["id_install"] . '">Modifier</a>';
				} else if ($utilisateur->HasDroits("10_550")) {
					if ($data["data"]["statut_installation"] == '0') { //Non clôturé
						// if($data["data"]["is_draft_install"] == '1'){ //Brouillon
						$result .=  '<a href="#"  class="btn btn-outline-light float-right  edit-install"  data-id-install="' . $data["data"]["id_install"] . '">Ajouter infos</a>';
					}
				}
			}

			if ($utilisateur->HasDroits("10_80")) {
				if (strtolower($data['data']['compteur_desaffecte']) == "1") {
					$result .=  '<a id="reaffect-compteur" href="#" class="btn btn-outline-dark float-right ml-2" data-name-install="' . $data["data"]["nom_client_blue"] . '" data-id-install="' . $data["data"]["id_install"] . '">Réaffecter le compteur</a>';
				}
				$result .=  '<a href="#" class="btn btn-outline-danger float-right delete-install" data-name-install="' . $data["data"]["nom_client_blue"] . '" data-id-install="' . $data["data"]["id_install"] . '">Supprimer</a>';
			}


			if ($utilisateur->HasDroits("10_545")) {
				if ($data["data"]["statut_installation"] == '1') { //Clôturé
					if ($data["data"]["approbation_installation"] == '0') {
						$result .= '<a href="#"  class="btn btn-outline-light float-right approve-install"  data-id-install="' . $data["data"]["id_install"] . '" data-compteur-install="' . $data["data"]["numero_compteur"] . '" data-name-install="' . $data["data"]["nom_client_blue"] . '" >Approuver</a>';
					}
				}
			}

			$result .= '<a href="#" class="btn btn-outline-light float-right fermer" data-dismiss="modal"> Fermer</a>';


			$result .= '</div> 
											</div>                
											</div>                
											</div>                
										</div>
                                </div>';
			$result_array["error"] = $data['error'];
			$result_array["data"] = $result;
			echo json_encode($result_array);
		} else {
			$result_array["error"] = $data['error'];
			$result_array["data"] = "No data";
			echo json_encode($result_array);
		}

		//	if($_GET){				
		/*}else{
				DroitsNotGranted();
			}*/
		break;
	case "visualiser_fiche_identification":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/
		//	if($_GET){		 		 
		$fiche = isset($_GET["q"]) ? $_GET["q"] : "";

		$result = "";
		$brouillon = "";
		//if($_GET){	
		$item = new Identification($db);
		$item->id_ = isset($_GET["q"]) ? $_GET["q"] : "";
		$data = $item->GetDetail();
		//var_dump($tmp_array); 						 
		//Utils::responseJson($result_array);

		if ($data['count'] != 0) {


			$e_adresse = new AdresseEntity($db);
			$etat_poc = new PARAM_EtatPOC($db);
			$pTypeDefaut = new Param_TypeDefaut($db);
			$statut_installation = new PARAM_StatutInstallation($db);
			$materiel = new Materiels($db);
			$organisme = new Organisme($db);
			$type_client = new TypeClient($db);
			$yes_no = new PARAM_YesNo($db);
			$conformity_install = new PARAM_ConformityInstall($db);
			$tarif = new Tarif($db);
			$cvs = new CVS($db);
			$accessib = new Param_Accessibility($db);
			$raccordement = new Param_Raccordement($db);
			$statut_personne = new Param_Statut_Personne($db);
			$type_compteur = new Param_TypeCompteur($db);
			$section_cable = new PARAM_Section_Cable($db);
			$type_usage = new Param_TypeUsage($db);
			$marquecompteur = new MarqueCompteur($db);
			//}
			//$data=$tmp_array;
			// if ( $data['error'] == 0) {
			if ($data['data']['is_draft'] == '1') {
				$brouillon = "Brouillon";
			}
			// var_dump($data);
			// exit;	
			$organisme->ref_organisme = $data['data']['id_equipe_identification'];
			$organisme->GetDetailIN();


			$cvs->code = $data['data']['cvs_id'];
			$cvs->GetDetailIN();

			$conformity_install->code = $data['data']['conformites_installation'];
			$conformity_inst = $conformity_install->GetDetail();

			//$type_activites = $type_usage->


			$identificateur = $utilisateur->GetUserDetailName($data['data']['identificateur']);
			$chef_equipe = $utilisateur->GetUserDetailName($data['data']['chef_equipe']);



			$raccordement->code = $data['data']['type_raccordement_identif'];
			$raccordement->GetDetailIN();



			$result = '<div class="row">   
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">									
									<div class="row">									   
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">					               			
									<div class="form-group">
										<label>N° COMPTEUR </label>
										<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary fiche-compteur">' . $data['data']['num_compteur_actuel'] .  '</div>
										</div>                
									</div>
									</div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">  					               	 
									<div class="form-group">
										<label>DATE IDENTIFICATION</label>
										<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">' . $data['data']['date_identification_fr'] .  '</div>
										</div>                
									</div> 
									</div>				
									</div>			
								</div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Localisation</h5>
								 <div class="row">
                                 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
							<div class="card-body">
                                <div class="form-group">
                                    <label>VILLE</label>			
                                    <div class="font-medium text-primary control-date">' . $e_adresse->GetLabel($data['adresse']['ville_id']) .  '</div>
                                </div>
                                <div class="form-group">
                                    <label>COMMUNE</label>			
                                   <div class="font-medium text-primary control-date">' . $e_adresse->GetLabel($data['adresse']['commune_id']) .  '</div>
                                </div>
                                <div class="form-group">
                                    <label>QUARTIER</label>			
                                    <div class="font-medium text-primary control-date">' . $e_adresse->GetLabel($data['adresse']['quartier_id']) .  '</div>
                                </div>
                                <div class="form-group">
                                    <label>CVS</label>			
                                    <div class="font-medium text-primary control-date">' . $cvs->libelle .  '</div>
                                </div> 								
                                <div class="form-group">
                                    <label>AVENUE</label>
                                   <div class="font-medium text-primary control-date">' . $e_adresse->GetLabel($data['adresse']['avenue']) .  '</div>            
                                </div>
                                </div>
								
                            </div>
                        </div>
                         <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
                            <div class="card-body">
								
                                <div class="form-group">
                                    <label>NUMERO</label>
                                   <div class="font-medium text-primary control-date">' . $data['adresse']['numero'] .  '</div>              
                                </div>
								
                                <div class="form-group">
                                    <label>TYPE RACCORDEMENT</label>			
                                   <div class="font-medium text-primary control-date">' . $raccordement->libelle .  '</div>
                                </div>
								<div class="form-group">
                                    <label>N° P.A</label>
                                   <div class="font-medium text-primary control-date">' . $data['data']['p_a'] .  '</div>         
                                </div>';

			$accessib->code = $data['data']['accessibility_client'];
			$accessib->GetDetailIN();

			$result .= '<div class="form-group">
                                    <label>ACCESSIBILITE CLIENT</label>			
                                 <div class="font-medium text-primary control-date">' . $accessib->libelle .  '</div>
                                </div>	
                                </div>
                            </div>
                        </div>
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">';
			if (isset($data['data']['gps_longitude']) && isset($data['data']['gps_latitude'])) {
				$result .= '<div class="form-group"> 
                                    <a class="btn btn-outline-light float-right" data-lat="' . $data['data']['gps_latitude'] .  '"   data-lng="' . $data['data']['gps_longitude'] .  '"  data-toggle="modal" data-target="#myModalLeaflet" ><i class="fas fa-map-marker-alt"></i> Visualiser Carte</a>    
                                </div>';
			}
			$result .= '</div>
                            </div>
                        </div>
                        </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Information Client</h5>
								 <div class="row">
                                 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">                                
                            <div class="card-body"> 
                                <div class="form-group">';

			$type_client->code = $data['data']['type_client'];
			$type_client->GetDetailIN();

			$tarif->code = $data['data']['tarif_identif'];
			$tarif->GetDetailIN();

			$result .= '<label>TYPE DE CLIENT</label>			
                                    <div class="font-medium text-primary control-date">' . $type_client->libelle .  '</div>
                                </div>								
                                <div class="form-group">
                                    <label>TARIF</label>			
                                   <div class="font-medium text-primary control-date">' . $tarif->libelle .  '</div>
                                </div>
								<div class="form-group">
                                    <label>NOMS CLIENT (PROPRIETAIRE)</label>
                                  <div class="font-medium text-primary control-date">' . $data['client']['noms'] .  '</div>        
                                </div>';


			$statut_personne->code = $data['client']['statut_identity'];
			$statut_personne->GetDetailIN();


			$result .= '<div class="form-group">
                                    <label>STATUT CLIENT</label>			
                                    <div class="font-medium text-primary control-date">' . $statut_personne->libelle .  '</div>
                                </div>
                               
                                <div class="form-group">
                                    <label>PHONE CLIENT</label>
                                   <div class="font-medium text-primary control-date">' . $data['client']['phone_number'] .  '</div>              
                                </div>
                                </div>								
                            </div>
                        </div>
                         <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
                            <div class="card-body">							
								<div class="form-group">
                                    <label>NOM OCCUPANT TROUVE *</label>
                                   <div class="font-medium text-primary control-date">' . $data['occupant']['noms'] .  '</div>         
                                </div>	
								<div class="form-group">
                                    <label>N° PIECE D\'IDENTITE</label>
                                   <div class="font-medium text-primary control-date">' . $data['occupant']['num_piece_identity'] .  '</div>           
                                </div>
                                <div class="form-group">
                                    <label>PHONE OCCUPANT</label>
                                    <div class="font-medium text-primary control-date">' . $data['occupant']['phone_number'] .  '</div>             
                                </div>';


			$statut_personne->code = $data['occupant']['statut_identity'];
			$statut_personne->GetDetailIN();


			$yes_no->code = $data['data']['consommateur_gerer'];
			$yes_no->GetDetailIN();

			$result .= '<div class="form-group">
                                    <label>STATUT OCCUPANT</label>			
                                  <div class="font-medium text-primary control-date">' . $statut_personne->libelle .  '</div>
                                </div>
								<div class="form-group">
                                    <label>CONSOMMATEUR GERE</label>			
                                 <div class="font-medium text-primary control-date">' .  $yes_no->libelle .  '</div>
                                </div>	
                                </div>
                            </div>
                        </div> 
                        </div>
                            </div>
                        </div>
                       
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Information raccordement</h5>
								 <div class="row">
                                
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								<div class="form-group">
                                    <label>CABINE</label>
                                   <div class="font-medium text-primary control-date">' . $data['data']['cabine_id'] .  '</div>          
                                </div>

							<div class="form-group">
                                    <label>NUMERO DEPART</label>
                                   <div class="font-medium text-primary control-date">' . $data['data']['numero_depart'] .  '</div>         
                            </div>	 
                            <div class="form-group">
                                    <label>NUMERO POTEAU</label>
                                 <div class="font-medium text-primary control-date">' . $data['data']['numero_poteau_identif'] .  '</div> 
                            </div>
                        </div>
                        </div>
                            </div>
							 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">																
								<div class="form-group">';

			$section_cable->code = $data['data']['section_cable'];
			$section_cable->GetDetailIN();

			$type_compteur->code = $data['data']['type_compteur'];
			$type_compteur->GetDetailIN();

			$result .= '<label>SECTION CABLE</label>
								<div class="font-medium text-primary control-date">' . $section_cable->libelle .  '</div>								
							</div>
								<div class="form-group">
                                    <label>NBRE BRANCH.</label>
                                    <div class="font-medium text-primary control-date">' . $data['data']['nbre_branchement'] .  '</div>           
                                </div>
                                <div class="form-group">
                                    <label>TYPE RACCORDEMENT PROPOSE</label>			
                                    <div class="font-medium text-primary control-date">' . $type_compteur->libelle .  '</div>
                                </div>';
			$yes_no->code = $data['data']['presence_inversor'];
			$yes_no->GetDetailIN();

			$result .= '<div class="form-group">
													<label>PRESENCE INVERSEUR</label>			
													<div class="font-medium text-primary control-date">' . $yes_no->libelle .  '</div>
												</div>
								
								</div>
                            </div>
                        </div>
						 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
                                    <div class="card-header d-flex">
                                        <h4 class="mb-0">GALERIE PHOTO PA</h4>
                                    </div>
                                    <div class="card-body">
									  <div class="row">';
			$photos = $data['photos'];
			foreach ($photos as $photo) {
				$result .= '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" >
													<div class="card">
														<div class="card-body">												   
															<label> </label>   <a href="pictures/'  . $photo['ref_photo'] . '.png" class="btn btn-primary" download> <i class="fa fa-download"></i> </a><div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right"  src="pictures/' . $photo['ref_photo'] . '.png"> ' .
					'</div>                
														</div>
														</div> 
												</div>';
			}

			$result .= '</div>
                                </div>
                           </div>
						 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								    <div class="form-group">
                                    <div class="table-responsive table-bordered table-hover" style="height:250px;">
                                        <table class="table no-wrap p-table lignes ui-sortable" ><thead><tr><th style="width:5%">N°</th><th style="width:90%">Matériel</th><th>Qté</th></tr>
                                            </thead>											
                                            <tbody>';

			$matos = $data['items'];
			foreach ($matos as $mat_item) {
				$result .= '<tr class="item-row"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' . $mat_item['designation'] . '</span></td><td><span class="qte">' . $mat_item['qte_identification'] . '</span></td></tr>';
			}


			$result .= '</tbody>
                                        </table>
                                    </div>         
                                </div>

								</div>
                            </div>
                        </div>
							
                        </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Informations sur l\'immeuble</h5>
								 <div class="row">
                                
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body"> 
                                <div class="form-group">
                                    <label>NOMBRE D\'APPARTEMENT </label>
                                   <div class="font-medium text-primary control-date">' . $data['data']['nbre_appartement'] .  '</div>            
                                </div>

								</div>
                            </div>
                        </div>                             
                       <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">';


			$type_usage->code = $data['data']['type_activites'];
			$type_usage->GetDetailIN();

			$result .= '<div class="form-group">
                                    <label>TYPE D\'ACTIVITES *</label>			
                                   <div class="font-medium text-primary control-date">' . $type_usage->libelle  . '</div>
                                </div>
                                <div class="form-group">
                                    <label>CONFORMITE D\'INSTALLATION</label>			
                                  <div class="font-medium text-primary control-date">' . $conformity_inst['libelle'] .  '</div>
                                </div> 
								</div>
                            </div>
                        </div>
                        </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Informations supplémentaires</h5>
								 <div class="row">                                
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
                                <div class="form-group">
                                    <label>AVIS TECHNIQUE BLUE ENERGY</label>
                                   <div class="font-medium text-primary control-date">' . $data['data']['avis_technique_blue'] .  '</div>             
                                </div>
                                <div class="form-group">
                                    <label>AVIS OCCUPANT</label>
                                  <div class="font-medium text-primary control-date">' . $data['data']['avis_occupant'] .  '</div>            
                                </div>
                                <div class="row"> 
								 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
					<div class="form-group">
							<label>SOCIETE EN CHARGE DE L\'IDENTIFICATION</label>			
							<div class="font-medium text-primary control-date">' .  $organisme->denomination .  '</div>
													</div>
								</div>
                            </div>
                        </div>
								 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label>CHEF D\'EQUIPE *</label>
											<div class="font-medium text-primary control-date">' . $chef_equipe .  '</div>               
										</div>
                                </div>
								 <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label>IDENTIFICATEUR *</label>
										<div class="font-medium text-primary control-date">' .  $identificateur .  '</div>                
										</div>
                                </div>
                                </div>
								</div>
                            </div>
                        </div>
                        </div>
                            </div>
                        </div>						
                    </div>';
			$result_array["error"] = $data['error'];
			$result_array["data"] = $result;
			echo json_encode($result_array);
		} else {
			$result_array["error"] = $data['error'];
			$result_array["data"] = "No data";
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "search_view_identification":
		/*	if($utilisateur->HasDroits("12_55"))
			{*/


		//	
		/* if($utilisateur->is_logged_in()=="")
  {
  $utilisateur->redirect('login.php');
  } */
		$search_item_value = "";
		/*var_dump($utilisateur);
exit();*/
		$search_term = isset($_GET['s']) ? $_GET['s'] : '';
		$stmt = null;
		$page_url = 'lst_identifs.php?';

		$paginate_now = new CLS_Paginate();
		$commune = new AdresseEntity($db);
		$cvs = new CVS($db);
		$organisme = new Organisme($db);
		$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : "";
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//$records_per_page = 10;
		$range = 2;
		//Number of results displayed per page 	by default its 10.
		$records_per_page =  ($_GET["show"] <> "" && is_numeric($_GET["show"])) ? intval($_GET["show"]) : 10;
		$from_record_num = ($records_per_page * $page) - $records_per_page;

		$item = new Identification($db);
		//$stmt = $item->readAll($from_record_num, $records_per_page, $utilisateur);
		//$total_rows = $item->countAll($utilisateur);

		//$expanded = "true";

		$filtre = '';
		if (isset($_GET['filtre']) && strlen($_GET['filtre']) > 0) {
			$est_installer = array();
			$e_commune = array();
			$param_cvs = array();
			$brouillon_ = array();
			$equipe_ident_ = array();
			$chef_equipe_ident_ = array();
			$identificateurs_arr = array();
			$arr_sites =  [];

			$filtres = explode(',', $_GET['filtre']);
			foreach ($filtres as $k_ => $v_) {
				$filter_item = explode('=', $v_);
				if ($filter_item[0] == 'est_installer') {
					$est_installer[] = $v_;
				} else if ($filter_item[0] == 'e_commune.code') {
					$e_commune[] = $v_;
				} else if ($filter_item[0] == 't_param_cvs.code') {
					$param_cvs[] = $v_;
				} else if ($filter_item[0] == 'id_equipe_identification') {
					$equipe_ident_[] = $v_;
				} else if ($filter_item[0] == 't_main_data.chef_equipe') {
					$chef_equipe_ident_[] = $v_;
				} else if ($filter_item[0] == 't_main_data.identificateur') {
					$identificateurs_arr[] = $v_;
				} else if ($filter_item[0] == 'is_draft') {
					$brouillon_[] = $v_;
				} else if ($filter_item[0] == 't_main_data.ref_site_identif') {
					$arr_sites[] = $v_;
				}
			}


			if (count($arr_sites) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_sites);
				$contexte_ctr = 0;
				foreach ($arr_sites as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($brouillon_) > 0) {
				$filtre .= " and (";
				$len_ = count($brouillon_);
				$contexte_ctr = 0;
				foreach ($brouillon_ as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}

			if (count($chef_equipe_ident_) > 0) {
				$filtre .= " and (";
				$len_ = count($chef_equipe_ident_);
				$contexte_ctr = 0;
				foreach ($chef_equipe_ident_ as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($identificateurs_arr) > 0) {
				$filtre .= " and (";
				$len_ = count($identificateurs_arr);
				$contexte_ctr = 0;
				foreach ($identificateurs_arr as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($equipe_ident_) > 0) {
				$filtre .= " and (";
				$len_ = count($equipe_ident_);
				$contexte_ctr = 0;
				foreach ($equipe_ident_ as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($est_installer) > 0) {
				$filtre .= " and (";
				$len_ = count($est_installer);
				$contexte_ctr = 0;
				foreach ($est_installer as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($e_commune) > 0) {
				$filtre .= " and (";
				$len_ = count($e_commune);
				$contexte_ctr = 0;
				foreach ($e_commune as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($param_cvs) > 0) {
				$filtre .= " and (";
				$len_ = count($param_cvs);
				$contexte_ctr = 0;
				foreach ($param_cvs as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
		}
		// var_dump($filtre);

		$search_item = isset($_GET['s']) ? $_GET['s'] : '';
		$du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
		$au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";

		$cacher = new Cacher();

		$cacher->setPrefix("identification");
		$cacheMetaData = [$search_item, $search_term, $du, $au, $from_record_num, $records_per_page, $utilisateur->site_id, $filtre];

		if ($view_mode == "date_only") {
			$cacheKey = ["search-advanced-date-only", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$du,
				$au,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre
			) {
				$stmt = $item->search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch_advanced_DateOnly($du, $au, $utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		} else if ($view_mode == "advanced_search") {
			$cacheKey = ["search-advanced", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$du,
				$au,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre,
				$search_item
			) {
				$stmt = $item->search_advanced($du, $au, $search_item, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch_advanced($du, $au, $search_item, $utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		} else if ($view_mode == "search") {
			$cacheKey = ["search", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre,
				$search_item
			) {
				$stmt = $item->search($search_item, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch($search_item, $utilisateur, $filtre);
				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				// dd($stmt, count($stmt));
				return [$stmt, count($stmt)];
			});
		} else {
			$cacheKey = ["read-all", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre,
			) {
				$stmt = $item->readAll($from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll($utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		}

		// dd((int)$page, $total_rows, $records_per_page, $range, $page_url );
		$paginate_now->page = (int)$page;
		$paginate_now->total_rows = $total_rows;
		$paginate_now->records_per_page = $records_per_page;
		$paginate_now->range_ = $range;
		$paginate_now->page_url = $page_url;

		$result = "";
		$date_identif = "";
		$date_titre = "Date identification";

		if ($utilisateur->HasDroits("10_40")) {
			$num_line = 0;
			foreach ($stmt as  $row_) {
				$date_identif = "";
				$date_titre = "Date identification";
				$num_line++;
				$result .= '<div class="control-row card bg-white border-top">
					<div class="card-header d-flex">
						<div>	<div class="text-dark">Compteur</div>  <h4 class="mb-0 text-primary">' . $row_["num_compteur_actuel"] . '</h4></div>
                                        <div class="dropdown ml-auto">
                                            <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i>  </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">';


				if ($row_["is_draft"] == '1') {
					$date_identif = $row_["date_deb_fr"];
					$date_titre = "Date debut identification";
				} else {
					$date_identif = $row_["date_fr"];
				}

				if ($utilisateur->HasDroits("10_20") || $utilisateur->HasDroits("10_650")) {
					if ($utilisateur->HasDroits("10_20")) {
						$result .= '<a href="#" class="dropdown-item edit"  data-id="' . $row_["id_"] . '">Modifier</a>';
					} else if ($utilisateur->HasDroits("10_650")) {

						if ($row_["is_draft"] == '1') {
							$result .= '<a href="#" class="dropdown-item edit"  data-id="' . $row_["id_"] . '">Ajouter Infos</a>';
						}
					}
				}

				// if($utilisateur->HasDroits("10_930")){
				$result .= '<a href="#" class="dropdown-item add"  data-id="' . $row_["id_"] . '">Nouvelle fiche même Adresse</a>';
				// }
				if ($utilisateur->HasDroits("10_930")) {
					$result .= '<a href="#" class="dropdown-item change-numero"  data-id="' . $row_["adresse_id"] . '">Changer Numéro Adresse</a>';
				}
				/* if ($utilisateur->HasDroits("10_730")) {
                                                           $result .= '<a  href="#" class="dropdown-item view-fiche-identification" data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["id_"] . '">Voir Fiche</a>';
                                                        }*/
				if ($utilisateur->HasDroits("10_30")) {
					$result .= '<a  href="#" class="dropdown-item delete" data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["id_"] . '">Supprimer</a>';
				}
				$organisme->ref_organisme = $row_["id_equipe_identification"];
				$organisme->GetDetailIN();
				$cvs->code = $row_["cvs_id"];
				$cvs->GetDetailIN();
				$ctl_rw = $utilisateur->readDetail($row_["identificateur"]);
				/*$statut_installation->code =  $row_["est_installer"];
														$row_statut = $statut_installation->GetDetail();*/
				$result .= '</div> 
                                        </div>
                                    </div><div class="card-body">
				<div class="row">
				<div class="col-sm-3">
						<div class="text-dark">
							' . $date_titre . '
						</div>
						<div class="font-medium text-primary control-date">' . $date_identif . '</div><span class="badge ' .  Utils::getAssign_Control_Badge($row_["est_installer"]) . '">' .  Utils::getStatut_IDentification($row_["est_installer"]) . '</span>';

				if ($row_["is_draft"] == '1') {
					$result .= ' <span class="badge badge-info">Brouillon</span>';
				}

				$result .= '</div><div class="col-sm-3">
						<div class="text-dark">
							Equipe identification
						</div>
						<div class="font-medium text-primary control-organe">' . $organisme->denomination . '</div>
					</div>					
					<div class="col-sm-3">
						<div class="text-dark">
							Identificateur
						</div>
						<div class="font-medium text-primary control-staff">' . $ctl_rw['nom_complet'] . '</div>
					</div>				
					<div class="col-sm-3 text-left">
						<div class="text-dark">
							Chef equipe
						</div>
						<div class="font-medium text-primary control-staff">' . $row_['nom_chef_equipe'] . '</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<div class="text-dark">
							Client
						</div>
						<div class="font-medium text-primary control-customer">' .  $row_["nom_client_blue"] . '</div>
					</div>
					<div class="col-sm-4">
						<div class="text-dark">
							Téléphone
						</div>
						<div class="font-medium text-primary control-phone">' . $row_["phone_client_blue"] . '</div>
					</div>
					<div class="col-sm-4">
						<div class="text-dark">
							Adresse
						</div>
						<div class="font-medium text-primary client-device">' . $commune->GetAdressInfoTexte($row_["adresse_id"]) . '</div>
					</div>
					<div class="col-sm-4">
						<div class="text-dark">
							CVS
						</div>
						<div class="font-medium text-primary control-cvs">' . $cvs->libelle . '</div>
					</div>
					<div class="col-sm-2">
						<div class="text-dark">
							PA
						</div>
						<div class="font-medium text-primary control-cvs">' . $row_["p_a"] . '</div>
					</div>
					<div class="col-sm-2">
						<div class="text-dark">
							NBRE APPT.
						</div>
						<div class="font-medium text-primary control-cvs">' . $row_["nbre_appartement"] . '</div>
					</div>
					<div class="col-sm-2">
						<div class="text-dark">
							REF. APPARTEMENT
						</div>
						<div class="font-medium text-primary control-cvs">' . $row_["reference_appartement"] . '</div>
					</div>
					<div class="col-sm-2">';
				//<a class="btn btn-outline-light float-right ml-1 view-all" href="#">Voir Fiche</a>';

				if ($utilisateur->HasDroits("10_730")) {
					$result .= '<a  href="#" class="btn btn-outline-light float-right ml-1 view-fiche-identification" data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["id_"] . '">Voir Fiche</a>';
				}
				$result .= '</div>
				</div>
			</div>
		</div>		';
			}
		}

		$result .=  $paginate_now->Paginate($view_mode);
		$result_array['data'] = $result;
		$result_array['count'] = $total_rows;
		echo json_encode($result_array);

		break;
	case "desaffect_compteur_in_installation":

		$id_install = isset($_GET['q']) ? $_GET['q'] : "";
		$raison = isset($_GET['raison']) ? $_GET['raison'] : "";

		$query = "UPDATE t_log_installation SET compteur_desaffecte = :compteur_desaffecte, motif_desaffectation = :motif_desaffectation WHERE id_install = :id_install";
		$stmt = $db->prepare($query);
		$stmt->bindValue(":id_install", $id_install);
		$stmt->bindValue(":motif_desaffectation", $raison);
		$stmt->bindValue(":compteur_desaffecte", 1);

		$res = $stmt->execute();

		if ($res) {
			$result["error"] = 0;
			$result["message"] = "Le compteur a été désaffecté";
		} else {
			$result['error'] = 1;
			$result['message'] = "Impossible de désaffecter ce compteur de cette installation ! ";
		}

		echo json_encode($result);

		break;
	case "reaffect_compteur_in_installation":

		$id_install = isset($_GET['q']) ? $_GET['q'] : "";

		$query = "UPDATE t_log_installation SET compteur_desaffecte = 0, motif_desaffectation = null WHERE id_install = :id_install";
		$stmt = $db->prepare($query);
		$stmt->bindValue(":id_install", $id_install);

		$res = $stmt->execute();

		if ($res) {
			$result["error"] = 0;
			$result["message"] = "Le compteur a été réaffecté";
		} else {
			$result['error'] = 1;
			$result['message'] = "Impossible de réaffecter le compteur de cette installation ! ";
		}

		echo json_encode($result);

		break;
	case "search_view_installation":

		$search_item_value = "";
		$search_term = isset($_GET['s']) ? $_GET['s'] : '';
		$stmt = null;
		$page_url = 'lst_install.php?';

		$statut_installation = new PARAM_StatutInstallation($db);
		$paginate_now = new CLS_Paginate();
		$adressEt = new AdresseEntity($db);
		$cvs = new CVS($db);
		$organisme = new Organisme($db);
		$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : "";
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//$records_per_page = 10;
		$range = 2;
		//Number of results displayed per page 	by default its 10.
		$records_per_page =  ($_GET["show"] <> "" && is_numeric($_GET["show"])) ? intval($_GET["show"]) : 10;
		$from_record_num = ($records_per_page * $page) - $records_per_page;
		$item = new Installation($db);
		$filtre = '';
		if (isset($_GET['filtre']) && strlen($_GET['filtre']) > 0) {
			$est_installer = array();
			$arr_type_installation = array();
			$e_commune = array();
			$param_cvs = array();
			$arr_id_equipe = array();
			$arr_installateur = array();
			$arr_chef_equipe_install = array();
			$arr_type_cpteur = array();
			$arr_provinces = [];
			$arr_sites = [];
			$compteur_desaffecte = [];
			$filtres = explode(',', $_GET['filtre']);

			foreach ($filtres as $k_ => $v_) {
				$filter_item = explode('=', $v_);
				if ($filter_item[0] == 't_log_installation.statut_installation') {
					$est_installer[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.installateur') {
					$arr_installateur[] = $v_;
				} else if ($filter_item[0] == 'e_commune.code') {
					$e_commune[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.chef_equipe') {
					$arr_chef_equipe_install[] = $v_;
				} else if ($filter_item[0] == 'id_equipe') {
					$arr_id_equipe[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.type_new_cpteur') {
					$arr_type_cpteur[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.type_installation') {
					$arr_type_installation[] = $v_;
				} else if ($filter_item[0] == 't_param_cvs.code') {
					$param_cvs[] = $v_;
				} else if ($filter_item[0] == 't_param_adresse_entity.code') {
					$arr_provinces[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.ref_site_install') {
					$arr_sites[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.compteur_desaffecte') {
					$compteur_desaffecte[] = $v_;
				}
			}


			if (count($compteur_desaffecte) > 0) {
				$filtre .= " AND (";
				$len_ = count($compteur_desaffecte);
				$contexte_ctr = 0;
				foreach ($compteur_desaffecte as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " AND " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}

			if (count($arr_provinces) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_provinces);
				$contexte_ctr = 0;
				foreach ($arr_provinces as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}

			if (count($arr_sites) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_sites);
				$contexte_ctr = 0;
				foreach ($arr_sites as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}

			if (count($arr_installateur) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_installateur);
				$contexte_ctr = 0;
				foreach ($arr_installateur as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}

			if (count($arr_chef_equipe_install) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_chef_equipe_install);
				$contexte_ctr = 0;
				foreach ($arr_chef_equipe_install as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($arr_id_equipe) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_id_equipe);
				$contexte_ctr = 0;
				foreach ($arr_id_equipe as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($arr_type_cpteur) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_type_cpteur);
				$contexte_ctr = 0;
				foreach ($arr_type_cpteur as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($arr_type_installation) > 0) {
				$filtre .= " and (";
				$len_ = count($arr_type_installation);
				$contexte_ctr = 0;
				foreach ($arr_type_installation as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($est_installer) > 0) {
				$filtre .= " and (";
				$len_ = count($est_installer);
				$contexte_ctr = 0;
				foreach ($est_installer as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($e_commune) > 0) {
				$filtre .= " and (";
				$len_ = count($e_commune);
				$contexte_ctr = 0;
				foreach ($e_commune as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
			if (count($param_cvs) > 0) {
				$filtre .= " and (";
				$len_ = count($param_cvs);
				$contexte_ctr = 0;
				foreach ($param_cvs as $est_item) {
					//$len_moins = $len_ - 1;
					if ($contexte_ctr == 0) {
						$filtre .=  $est_item . "";
					} else {
						$filtre .= " Or " . $est_item . "";
					}

					$contexte_ctr++;
				}
				$filtre .= ")";
			}
		}
		$search_item = isset($_GET['s']) ? $_GET['s'] : '';
		$du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
		$au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";

		$cacher = new Cacher();

		$cacher->setPrefix("installation");
		$cacheMetaData = [$search_item, $search_term, $du, $au, $from_record_num, $records_per_page, $utilisateur->site_id, $filtre];

		if ($view_mode == "date_only") {
			$cacheKey = ["search-advanced-date-only", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$du,
				$au,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre
			) {
				$stmt = $item->search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch_advanced_DateOnly($du, $au, $utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		} else if ($view_mode == "advanced_search") {
			$cacheKey = ["search-advanced", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$du,
				$au,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre,
				$search_item
			) {
				$stmt = $item->search_advanced($du, $au, $search_item, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch_advanced($du, $au, $search_item, $utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		} else if ($view_mode == "search") {
			$cacheKey = ["search", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre,
				$search_term
			) {
				$stmt = $item->search($search_term, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch($search_term, $utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		} else {
			$cacheKey = ["read-all", ...$cacheMetaData];

			[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
				$item,
				$from_record_num,
				$records_per_page,
				$utilisateur,
				$filtre,
			) {
				$stmt = $item->readAll($from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll($utilisateur, $filtre);

				$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return [$stmt, $total_rows];
			});
		}
		// dump($stmt);
		$paginate_now->page = $page;
		$paginate_now->total_rows = $total_rows;
		$paginate_now->records_per_page = $records_per_page;
		$paginate_now->range_ = $range;
		$paginate_now->page_url = $page_url;

		$result = "";
		if ($utilisateur->HasDroits("10_90")) {
			$num_line = 0;
			// while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
			foreach ($stmt as $row_) {
				$num_line++;

				$desaffecte = "";
				$motif_desaffecte = "";
				if (isset($row_['compteur_desaffecte']) && $row_["compteur_desaffecte"] == '1') {
					$desaffecte .= ' <span class="badge badge-dark">Compteur désaffecté</span>';

					$motif = "Aucun";
					if (isset($row_['motif_desaffectation']) && $row_['motif_desaffectation']) {
						$motif = $row_['motif_desaffectation'];
					}

					$motif_desaffecte .= ' <span class="badge badge-warning"> <strong>MOTIF : </strong> ' . $motif . '</>';
				}
				$result .= '<div class="control-row card bg-white">
								<div class="card-header d-flex">
                                    <div>	
									   <div class="text-dark">Compteur ' . Utils::getCompteurTypeSpan($row_['type_new_cpteur']) . '</div>  
									   <h4 class="mb-0 text-primary">' .  $row_["numero_compteur"] .
					' <span class="badge ' . Utils::getAssign_Control_Badge($row_["approbation_installation"]) . '">' .
					Utils::getApproved_Label($row_['approbation_installation']) . '</span> ' .
					Utils::getInstallationEnplaceSPAN(trim($row_["num_compteur_actuel"]), trim($row_["numero_compteur"]))
					.
					$desaffecte . $motif_desaffecte
					. '</h4></div>';
				// <div class="dropdown ml-auto">
				// <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i>  </a>
				// <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">';

				// if ($utilisateur->HasDroits("10_545")) {
				// if($row_["approbation_installation"] == '0'){   
				// $result .= '<a href="#"  class="dropdown-item approve-install"  data-id-install="'.$row_["id_install"].'" data-compteur-install="'.$row_["numero_compteur"].'" data-name-install="'.$row_["nom_client_blue"].'" >Approuver</a>';
				// }
				// }
				// if ($utilisateur->HasDroits("10_555")) {
				// if($row_["statut_installation"] == '0'){   
				// $result .=  '<a href="#"  class="dropdown-item cloture-install"  data-id-install="'.$row_["id_install"].'" data-compteur-install="'.$row_["numero_compteur"].'" data-name-install="'.$row_["nom_client_blue"].'" >Clôturer</a>';
				// }
				// }
				/* if ($utilisateur->HasDroits("10_70")||$utilisateur->HasDroits("10_550")) {
													 if($utilisateur->HasDroits("10_70")){
                                                           $result .=  '<a href="#"  class="dropdown-item edit-install"  data-id-install="'.$row_["id_install"].'">Modifier</a>';
													 }else if($utilisateur->HasDroits("10_550")){
														 if($row_["statut_installation"] == '0'){ //Non clôturé
                                                            $result .=  '<a href="#"  class="dropdown-item edit-install"  data-id-install="'.$row_["id_install"].'">Ajouter infos</a>';
														 }
													 }
                                                 }
												 
												 if ($utilisateur->HasDroits("10_80")) {
                                                            $result .=  '<a href="#" class="dropdown-item delete-install" data-name-install="'.$row_["nom_client_blue"].'" data-id-install="'.$row_["id_install"].'">Supprimer</a>';
                                                        }*/
				$organisme->ref_organisme = $row_["id_equipe"];
				$organisme->GetDetailIN();

				$cvs->code = $row_["cvs_id"];
				$cvs->GetDetailIN();
				$ctl_rw = $utilisateur->readDetail($row_["code_installateur"]);
				$statut_installation->code =  $row_["statut_installation"];
				$row_statut = $statut_installation->GetDetail();
				// $result .= '</div>
				$result .= '  
                                    </div><div class="card-body">
				<div class="row">
				<div class="col-sm-3">
						<div class="text-dark">';
				$result .=  Utils::getDateInstall_Label($row_["statut_installation"]);

				if ($row_["is_draft_install"] == '1') {
					$result .= ' <span class="badge badge-info">Brouillon</span>';
				}

				$result .= '</div>
						<div class="font-medium text-primary control-date">';
				$result .= Utils::getDateInstall_Value($row_["statut_installation"], $row_["date_fin_installation_fr"], $row_["date_debut_installation_fr"]);
				$result .= '</div><span class="badge ' .  Utils::getAssign_Control_Badge($row_["statut_installation"]) . '">' .  $row_statut['libelle'] . '</span></div>
					<div class="col-sm-3">
						<div class="text-dark">
							Equipe installation
						</div>
						<div class="font-medium text-primary control-organe">' . $organisme->denomination  . '</div>
					</div>
					
					<div class="col-sm-3">
						<div class="text-dark">
							Installateur
						</div>
						<div class="font-medium text-primary control-staff">' . $ctl_rw['nom_complet'] . '</div>
					</div>
					<div class="col-sm-3 text-left">
						<div class="text-dark">
							Chef équipe
						</div>
						<div class="font-medium text-primary control-staff">' . $row_['nom_chef_equipe'] . '</div>
					</div>';
				$installateurs_suppl = "";

				$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_log_installation_users.ref_inst_ FROM t_log_installation_users INNER JOIN t_utilisateurs ON t_log_installation_users.ref_user = t_utilisateurs.code_utilisateur where t_log_installation_users.ref_inst_=:ref_inst_";
				$stmt_inst_suppl = $db->prepare($query);
				$stmt_inst_suppl->bindValue(":ref_inst_", $row_["id_install"]);

				// $cacher = new Cacher();
				$ro = $cacher->get(['installateurs-supplementaires', $row_["id_install"]], function () use ($stmt_inst_suppl) {
					$stmt_inst_suppl->execute();
					return  $stmt_inst_suppl->fetchAll(PDO::FETCH_ASSOC);
				});

				if (count($ro) > 0) {
					$result .= '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="card">
								<div class="card-body">	
										<div class="form-group">
											<label>INSTALLATEURS SUPPLEMENTAIRES *</label>
											<div class="input-group" style="width: 100%;"> 
											<div class="font-medium text-primary ">';

					foreach ($ro as $ins_suppItem) {
						$installateurs_suppl .= "  "  . $ins_suppItem["nom_complet"] . ",";
					}
					$clean = rtrim($installateurs_suppl, ",");
					$result .= $clean .  '</div>
										</div> 
											</div>                
											</div>                
											</div>
                                </div>';
				}

				$result .= '</div>';
				$result .= '<div class="row">
					<div class="col-sm-3">
						<div class="text-dark">
							Client
						</div>
						<div class="font-medium text-primary control-customer">' . $row_["nom_client_blue"] . '</div>
					</div>
					<div class="col-sm-3">
						<div class="text-dark">
							Téléphone
						</div>
						<div class="font-medium text-primary control-phone">' .  $row_["phone_client_blue"]  . '</div>
					</div>
					<div class="col-sm-3">
						<div class="text-dark">
							CVS
						</div>
						<div class="font-medium text-primary control-cvs">' . $cvs->libelle .
					' <span class="badge ' . Utils::getInstallType_Badge($row_["type_installation"]) . '">' . Utils::getInstallType_Label($row_["type_installation"]);
				$result .= '</span>
						</div>
					</div>  <div class="col-sm-3">
						<div class="text-dark">
							Adresse
						</div>
						<div class="font-medium text-primary client-device">' . $adressEt->GetAdressInfoTexte($row_["adresse_id"]) . '</div>
					</div>';
				if ($utilisateur->HasDroits("10_800")) {
					$result .= '<a  href="#" class="btn btn-outline-light float-right ml-1 view-fiche" data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["id_install"] . '">Voir Fiche</a>';
				}

				$result .= '</div>
			</div>
		</div>	';
			}
		}
		$result .=  $paginate_now->Paginate($view_mode);
		$result_array['data'] = $result;
		$result_array['count'] = $total_rows;
		echo json_encode($result_array);
		break;
	case "get_control_list":

		$search_item_value = "";
		$search_term = isset($_GET['s']) ? $_GET['s'] : '';
		$stmt = null;
		$page_url = 'rapport_compteur_maps.php?';
		$statut_installation = new PARAM_StatutInstallation($db);
		$paginate_now = new CLS_Paginate();
		$commune = new AdresseEntity($db);
		$cvs = new CVS($db);
		$organisme = new Organisme($db);

		$typeFraude = new PARAM_TypeFraude($db);
		$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : "";
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//$records_per_page = 10;
		$range = 2;
		//Number of results displayed per page 	by default its 10.
		$records_per_page =  ($_GET["show"] <> "" && is_numeric($_GET["show"])) ? intval($_GET["show"]) : 100;
		$from_record_num = ($records_per_page * $page) - $records_per_page;
		$item = new CLS_Controle($db);
		$result = '';
		$filtre = '';

		if ($utilisateur->HasDroits("10_140")) {
			if (isset($_GET['filtre']) && strlen($_GET['filtre']) > 0) {
				$est_installer = array();
				$arr_type_installation = array();
				$e_commune = array();
				$param_cvs = array();
				$equipe_control_ = array();
				$arr_chef_equipe_control = array();
				$arr_controleur = array();
				$arr_cas_fraude = array();
				$filtres = explode(',', $_GET['filtre']);
				foreach ($filtres as $k_ => $v_) {
					$filter_item = explode('=', $v_);
					if ($filter_item[0] == 'e_commune.code') {
						$e_commune[] = $v_;
					} else if ($filter_item[0] == 't_param_cvs.code') {
						$param_cvs[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.cas_de_fraude') {
						$arr_cas_fraude[] = $v_;
					} else if ($filter_item[0] == 'id_organisme_control') {
						$equipe_control_[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.chef_equipe_control') {
						$arr_chef_equipe_control[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.controleur') {
						$arr_controleur[] = $v_;
					}
				}


				if (count($arr_cas_fraude) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_cas_fraude);
					$contexte_ctr = 0;
					foreach ($arr_cas_fraude as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($equipe_control_) > 0) {
					$filtre .= " and (";
					$len_ = count($equipe_control_);
					$contexte_ctr = 0;
					foreach ($equipe_control_ as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($arr_chef_equipe_control) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_chef_equipe_control);
					$contexte_ctr = 0;
					foreach ($arr_chef_equipe_control as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($arr_controleur) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_controleur);
					$contexte_ctr = 0;
					foreach ($arr_controleur as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($e_commune) > 0) {
					$filtre .= " and (";
					$len_ = count($e_commune);
					$contexte_ctr = 0;
					foreach ($e_commune as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($param_cvs) > 0) {
					$filtre .= " and (";
					$len_ = count($param_cvs);
					$contexte_ctr = 0;
					foreach ($param_cvs as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
			}
			//var_dump($filtre);
			$filtre .= " AND (t_log_controle.gps_latitude_control IS NOT NULL and t_log_controle.gps_longitude_control IS NOT NULL) ";
			$search_item = isset($_GET['s']) ? $_GET['s'] : '';
			$du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
			$au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";
			if ($view_mode == "date_only") {
				$stmt = $item->search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch_advanced_DateOnly($du, $au, $utilisateur, $filtre);
			} else if ($view_mode == "advanced_search") {
				$stmt = $item->search_advanced($du, $au, $search_item, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch_advanced($du, $au, $search_item, $utilisateur, $filtre);
			} else if ($view_mode == "search") {
				$stmt = $item->search($search_term, $from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll_BySearch($search_term, $utilisateur, $filtre);
			} else {
				$stmt = $item->readAll($from_record_num, $records_per_page, $utilisateur, $filtre);
				$total_rows = $item->countAll($utilisateur, $filtre);
			}

			$paginate_now->page = $page;
			$paginate_now->total_rows = $total_rows;
			$paginate_now->records_per_page = $records_per_page;
			$paginate_now->range_ = $range;
			$paginate_now->page_url = $page_url;

			$result = "";
			$num_line = 0;
			$results = [];
			while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$num_line++;

				$result = [
					// "row" => $row_,
					"gps_longitude_control" => $row_['gps_longitude_control'],
					"gps_latitude_control" => $row_['gps_latitude_control'],
					"cas_de_fraude" => $row_['cas_de_fraude'],
					"numero_serie_cpteur" => $row_['numero_serie_cpteur'],
					"cas_de_fraude" => $row_['cas_de_fraude'],
					"cas_de_fraude" => $row_['cas_de_fraude'],

				];

				if ($utilisateur->HasDroits("10_120")) {
					$result['nom_client_blue'] = $row_['nom_client_blue'];
					$result['ref_fiche_controle'] = $row_['ref_fiche_controle'];
				} else {
					$result['is_draft_control'] = $row_['is_draft_control'];
				}

				$organisme->ref_organisme = $row_["id_organisme_control"];
				$organisme->GetDetailIN();

				$cvs->code = $row_["cvs_id"];
				$cvs->GetDetailIN();
				$ctl_rw = $utilisateur->readDetail($row_["controleur"]);

				$result['date_controle_fr'] = $row_['date_controle_fr'];


				$chef_equipe = $utilisateur->GetUserDetailName($row_['chef_equipe_control']);
				$result['organisme_denomination'] = $organisme->denomination;
				$result['nom_complet'] = $ctl_rw['nom_complet'];
				$result['chef_equipe'] = $chef_equip;
				$result['nom_client_blue'] = $row_["nom_client_blue"];
				$result['phone_client_blue'] = $row_["phone_client_blue"];
				$result['cvs_libelle'] =  $cvs->libelle;
				$result['adresse'] = $commune->GetAdressInfoTexte($row_["adresse_id"]);
				$result['nom_client_blue'] = $row_["nom_client_blue"];
				$result['ref_fiche_controle'] =  $row_["ref_fiche_controle"];
				$result['cvs_libelle'] =  $cvs->libelle;
				$results[] = $result;
			}
		}

		// $result .=  $paginate_now->Paginate($view_mode);
		$result_array['data'] = $results;
		$result_array['paginate'] = $paginate_now->Paginate($view_mode);
		$result_array['count'] = count($results);
		echo json_encode($result_array);
		break;
	case "search_view_control":

		$search_item_value = "";
		$search_term = isset($_GET['s']) ? $_GET['s'] : '';
		$stmt = null;
		$page_url = 'lst_control.php?';
		$statut_installation = new PARAM_StatutInstallation($db);
		$paginate_now = new CLS_Paginate();
		$commune = new AdresseEntity($db);
		$cvs = new CVS($db);
		$organisme = new Organisme($db);

		$typeFraude = new PARAM_TypeFraude($db);
		$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : "";
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//$records_per_page = 10;
		$range = 2;
		//Number of results displayed per page 	by default its 10.
		$records_per_page =  ($_GET["show"] <> "" && is_numeric($_GET["show"])) ? intval($_GET["show"]) : 10;
		$from_record_num = ($records_per_page * $page) - $records_per_page;
		$item = new CLS_Controle($db);
		$result = '';
		$filtre = '';

		if ($utilisateur->HasDroits("10_140")) {
			if (isset($_GET['filtre']) && strlen($_GET['filtre']) > 0) {
				$est_installer = array();
				$arr_type_installation = array();
				$e_commune = array();
				$param_cvs = array();
				$equipe_control_ = array();
				$arr_chef_equipe_control = array();
				$arr_controleur = array();
				$arr_cas_fraude = array();
				$arr_sites = [];

				$filtres = explode(',', $_GET['filtre']);
				foreach ($filtres as $k_ => $v_) {
					$filter_item = explode('=', $v_);
					if ($filter_item[0] == 'e_commune.code') {
						$e_commune[] = $v_;
					} else if ($filter_item[0] == 't_param_cvs.code') {
						$param_cvs[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.cas_de_fraude') {
						$arr_cas_fraude[] = $v_;
					} else if ($filter_item[0] == 'id_organisme_control') {
						$equipe_control_[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.chef_equipe_control') {
						$arr_chef_equipe_control[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.controleur') {
						$arr_controleur[] = $v_;
					} else if ($filter_item[0] == 't_log_controle.ref_site_controle') {
						$arr_sites[] = $v_;
					}
				}

				if (count($arr_sites) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_sites);
					$contexte_ctr = 0;
					foreach ($arr_sites as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}

				if (count($arr_cas_fraude) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_cas_fraude);
					$contexte_ctr = 0;
					foreach ($arr_cas_fraude as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($equipe_control_) > 0) {
					$filtre .= " and (";
					$len_ = count($equipe_control_);
					$contexte_ctr = 0;
					foreach ($equipe_control_ as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($arr_chef_equipe_control) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_chef_equipe_control);
					$contexte_ctr = 0;
					foreach ($arr_chef_equipe_control as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($arr_controleur) > 0) {
					$filtre .= " and (";
					$len_ = count($arr_controleur);
					$contexte_ctr = 0;
					foreach ($arr_controleur as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($e_commune) > 0) {
					$filtre .= " and (";
					$len_ = count($e_commune);
					$contexte_ctr = 0;
					foreach ($e_commune as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
				if (count($param_cvs) > 0) {
					$filtre .= " and (";
					$len_ = count($param_cvs);
					$contexte_ctr = 0;
					foreach ($param_cvs as $est_item) {
						//$len_moins = $len_ - 1;
						if ($contexte_ctr == 0) {
							$filtre .=  $est_item . "";
						} else {
							$filtre .= " Or " . $est_item . "";
						}

						$contexte_ctr++;
					}
					$filtre .= ")";
				}
			}
			//var_dump($filtre);

			$search_item = isset($_GET['s']) ? $_GET['s'] : '';
			$du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
			$au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";

			$cacher = new Cacher();

			$cacher->setPrefix("control");
			$cacheMetaData = [$search_item, $search_term, $du, $au, $from_record_num, $records_per_page, $utilisateur->site_id, $filtre];

			if ($view_mode == "date_only") {
				$cacheKey = ["search-advanced-date-only", ...$cacheMetaData];

				[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
					$item,
					$du,
					$au,
					$from_record_num,
					$records_per_page,
					$utilisateur,
					$filtre
				) {
					$stmt = $item->search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $utilisateur, $filtre);
					$total_rows = $item->countAll_BySearch_advanced_DateOnly($du, $au, $utilisateur, $filtre);

					$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
					return [$stmt, $total_rows];
				});
			} else if ($view_mode == "advanced_search") {
				$cacheKey = ["search-advanced", ...$cacheMetaData];

				[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
					$item,
					$du,
					$au,
					$from_record_num,
					$records_per_page,
					$utilisateur,
					$filtre,
					$search_item
				) {
					$stmt = $item->search_advanced($du, $au, $search_item, $from_record_num, $records_per_page, $utilisateur, $filtre);
					$total_rows = $item->countAll_BySearch_advanced($du, $au, $search_item, $utilisateur, $filtre);

					$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
					return [$stmt, $total_rows];
				});
			} else if ($view_mode == "search") {
				$cacheKey = ["search", ...$cacheMetaData];

				[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
					$item,
					$from_record_num,
					$records_per_page,
					$utilisateur,
					$filtre,
					$search_term
				) {
					$stmt = $item->search($search_term, $from_record_num, $records_per_page, $utilisateur, $filtre);
					$total_rows = $item->countAll_BySearch($search_term, $utilisateur, $filtre);

					$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
					return [$stmt, $total_rows];
				});
			} else {
				$cacheKey = ["read-all", ...$cacheMetaData];

				[$stmt, $total_rows] = $cacher->get($cacheKey, function () use (
					$item,
					$from_record_num,
					$records_per_page,
					$utilisateur,
					$filtre,
				) {
					$stmt = $item->readAll($from_record_num, $records_per_page, $utilisateur, $filtre);
					$total_rows = $item->countAll($utilisateur, $filtre);

					$stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
					return [$stmt, $total_rows];
				});
			}

			$paginate_now->page = $page;
			$paginate_now->total_rows = $total_rows;
			$paginate_now->records_per_page = $records_per_page;
			$paginate_now->range_ = $range;
			$paginate_now->page_url = $page_url;

			$result = "";
			$num_line = 0;
			foreach ($stmt as $row_) {
				// while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$num_line++;


				$result .= '<div class="control-row card bg-white">
			<div class="card-header d-flex">
											<div>	<div class="text-dark">Compteur</div>  <h4 class="mb-0 text-primary">' . $row_["numero_serie_cpteur"];

				if ($row_['cas_de_fraude'] == 'Oui' || $row_['cas_de_fraude'] == 'on') {
					// $typeFraude->code=$row_['type_fraude'];
					// $typeFraude->GetDetailIN(); 
					// $result .='<span class="badge badge-danger">' . $typeFraude->libelle . '</span>';
					$result .= '<span class="badge badge-danger">Fraude</span>';
				}

				$result .= '</h4></div>
											
                                        <div class="dropdown ml-auto">
                                            <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i>  </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">';

				if ($utilisateur->HasDroits("10_120")) {
					$result .=  '<a class="dropdown-item edit-control" href="#"  data-name-control="' . $row_["nom_client_blue"] . '" data-id-control="' . $row_["ref_fiche_controle"] . '">Modifier</a>';
				} else {
					if ($row_["is_draft_control"] == '1') { //Brouillon
						$result .=  '<a href="#"  class="dropdown-item  edit-control"  data-id-control="' . $row_["ref_fiche_controle"] . '">Ajouter infos</a>';
					}
				}
				if ($utilisateur->HasDroits("10_130")) {
					$result .=  '<a class="dropdown-item delete-control" href="#" data-name-control="' . $row_["nom_client_blue"] . '" data-id-control="' . $row_["ref_fiche_controle"] . '">Supprimer</a>';
				}




				$organisme->ref_organisme = $row_["id_organisme_control"];
				$organisme->GetDetailIN();

				$cvs->code = $row_["cvs_id"];
				$cvs->GetDetailIN();
				$ctl_rw = $utilisateur->readDetail($row_["controleur"]);

				$result .= ' </div>
                                        </div>
                                    </div><div class="card-body">
				<div class="row">
				<div class="col-sm-3">
						<div class="text-dark">
							Date contrôle';
				if ($row_["is_draft_control"] == '1') {
					$result .= ' <span class="badge badge-info">Brouillon</span>';
				}

				$chef_equipe = $utilisateur->GetUserDetailName($row_['chef_equipe_control']);
				$result .= '</div>
						<div class="font-medium text-primary control-date">' .  $row_["date_controle_fr"] . '</div>
					</div>
					<div class="col-sm-3">
						<div class="text-dark">
							Equipe contrôle
						</div>
						<div class="font-medium text-primary control-organe">' .  $organisme->denomination . '</div>
					</div>
					
					<div class="col-sm-3 text-left">
						<div class="text-dark">
							Contrôleur
						</div>
						<div class="font-medium text-primary control-staff">' .  $ctl_rw['nom_complet'] . '</div>
					</div>
					
					<div class="col-sm-3 text-left">
						<div class="text-dark">
							Chef-Equipe
						</div>
						<div class="font-medium text-primary control-staff">' .  $chef_equipe . '</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="text-dark">
							Client
						</div>
						<div class="font-medium text-primary control-customer">' .  $row_["nom_client_blue"] . '</div>
					</div>
					<div class="col-sm-3">
						<div class="text-dark">
							Téléphone
						</div>
						<div class="font-medium text-primary control-phone">' . $row_["phone_client_blue"] . '</div>
					</div>
					<div class="col-sm-3">
						<div class="text-dark">
							CVS
						</div>
						<div class="font-medium text-primary control-cvs">' .   $cvs->libelle . '</div>
					</div>
					<div class="col-sm-3">
						<div class="text-dark">
							Adresse
						</div>
						<div class="font-medium text-primary client-device">' . $commune->GetAdressInfoTexte($row_["adresse_id"]) . '</div>
					</div>';

				// if ($utilisateur->HasDroits("10_930")) {
				$result .= '<a  href="#" class="btn btn-outline-light float-right ml-1 view-fiche" data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["ref_fiche_controle"] . '">Voir Fiche</a>';
				//                     }


				$result .= '</div>
			</div>
		</div>';
			}
		}
		$result .=  $paginate_now->Paginate($view_mode);
		$result_array['data'] = $result;
		$result_array['count'] = $total_rows;
		echo json_encode($result_array);
		break;

	case "get_site_article":
		if ($utilisateur->HasDroits("12_51")) {
			if ($_GET) {
				$site = new Site($db);
				$site->code_site = isset($_GET["q"]) ? $_GET["q"] : "";
				$result_array["error"] = 0;
				$result_array["data"] = $site->ArticleAccessibleForSite();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;

	case "get_province_site":
		if ($_GET) {
			$site = new Site($db);
			$province_id = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $site->GetSiteAccessibleForProvince($utilisateur->code_utilisateur, $province_id);
			$options = '<option selected="selected" disabled="true">Choisissez le site</option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;
		/*case "get_user_site":
			if($_GET){		 			
				$site = new Site($db); 
				$province_id= isset($_GET["id_"])?$_GET["id_"]:"";  
				 $result_array["error"]=0;
				 $site_array=$site->GetSiteAllAccessibleForUser($utilisateur->code_utilisateur); 
				 $options='<option selected="selected" disabled="true">Choisissez le site</option>';
				 while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)){
					 $options.= "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
				 }
				  $result_array["data"]=$options;
				echo json_encode($result_array);			
			}
			break;*/

	case "get_commune_cvs":
		if ($_GET) {
			$item = new CVS($db);
			$id_commune = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $item->GetCommuneCVS($id_commune);
			$options = '<option selected="selected" disabled="true">Choisissez CVS</option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;

	case "get_province_ville":
		if ($_GET) {
			$item = new AdresseEntity($db);
			$id_province = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $item->GetProvinceVille($id_province);
			$options = '<option selected="selected" value=""> </option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;
	case "get_ville_commune":
		if ($_GET) {
			$item = new AdresseEntity($db);
			$id_ = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $item->GetVilleCommuneTerritoire($id_);
			$options = '<option selected="selected" value=""> </option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;
	case "get_commune_quartier":
		if ($_GET) {
			$item = new AdresseEntity($db);
			$id_ = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $item->GetCommuneQuartier($id_);
			$options = '<option selected="selected" value=""> </option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;
	case "get_adress_json":
		if ($_GET) {
			$item = new AdresseEntity($db);
			$id_ = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$stmt = $item->FetAllChild($id_);
			$c_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$result_array["data"] = $c_array;
			$result_array["count"] = count($c_array);
			echo json_encode($result_array);
		}
		break;

	case "get_organisme_chief":
		if ($_GET) {
			$item = new Utilisateur($db);
			$id_ = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $item->GetOrganismeChief($id_);
			$options = '<option selected="selected" >Veuillez préciser</option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code_utilisateur"]}'>{$row_["nom_complet"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;

	case "get_organisme_chief_control":
		if ($_GET) {
			// $item = new Utilisateur($db); 
			$id_ = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $utilisateur->GetOrganismeChiefControl($id_);
			$options = '<option selected="selected" disabled>Veuillez préciser</option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code_utilisateur"]}'>{$row_["nom_complet"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;

	case "get_organisme_chief_install":
		if ($_GET) {
			//$item = new Utilisateur($db); 
			$id_ = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$result_array["error"] = 0;
			$site_array = $utilisateur->GetOrganismeChiefInstall($id_);
			$options = '<option selected="selected" disabled>Veuillez préciser</option>';
			while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
				$options .= "<option value='{$row_["code_utilisateur"]}'>{$row_["nom_complet"]}</option>";
			}
			$result_array["data"] = $options;
			echo json_encode($result_array);
		}
		break;
		/** START REFUS  */
	case "create_refus":
		/*if($utilisateur->HasDroits("10_230"))
			{	*/
		if ($_POST) {
			$item = new PARAM_VisitLog($db);
			$item->ref_log_visite =  uniqUid("t_param_log_visite_pa", "ref_log_visite");
			$item->statut_accessibilite =  isset($_POST["refus_accessibility"]) ? $_POST["refus_accessibility"] : "";
			$item->num_pa =  isset($_POST["id_group"]) ? $_POST["id_group"] : "";
			$item->type_motif_visite = "0"; // identification
			$item->site_id = $utilisateur->site_id;
			$item->cvs_id =  isset($_POST["refus_cvs"]) ? $_POST["refus_cvs"] : "";
			$item->commentaire =  isset($_POST["refus_comment"]) ? $_POST["refus_comment"] : "";
			$item->date_rendez_vous =  isset($_POST["dat_rendez_vous"]) ? $_POST["dat_rendez_vous"] : "";

			//ADRESSE
			$item->quartier_id = isset($_POST["refus_quartier"]) ? $_POST["refus_quartier"] : "";
			$item->commune_id = isset($_POST["refus_commune"]) ? $_POST["refus_commune"] : "";
			$item->ville_id = isset($_POST["refus_ville"]) ? $_POST["refus_ville"] : "";
			//$item->province_id = isset($_POST["refus_quartier"])?$_POST["id_group"]:"";		 
			$item->numero = isset($_POST["refus_numero"]) ? $_POST["refus_numero"] : "";
			$item->avenue = isset($_POST["refus_avenue"]) ? $_POST["refus_avenue"] : "";
			//ADRESSE


			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "create_refus_control":
		/*if($utilisateur->HasDroits("10_230"))
			{	*/
		if ($_POST) {
			$item = new PARAM_VisitLog($db);
			$item->ref_log_visite =  uniqUid("t_param_log_visite_pa", "ref_log_visite");
			$item->statut_accessibilite =  isset($_POST["refus_accessibility"]) ? $_POST["refus_accessibility"] : "";
			//$item->num_pa =  isset($_POST["id_group"])?$_POST["id_group"]:"";
			$item->type_motif_visite = "1"; // Controle
			$item->site_id = $utilisateur->site_id;
			$item->cvs_id =  isset($_POST["refus_cvs"]) ? $_POST["refus_cvs"] : "";
			$item->assign_id =  isset($_POST["refus_assign_id"]) ? $_POST["refus_assign_id"] : "";
			$item->commentaire =  isset($_POST["refus_comment"]) ? $_POST["refus_comment"] : "";
			$item->date_rendez_vous =  isset($_POST["dat_rendez_vous"]) ? $_POST["dat_rendez_vous"] : "";

			//ADRESSE
			$item->adress_id = isset($_POST["refus_adress_id"]) ? $_POST["refus_adress_id"] : "";
			//ADRESSE				   
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "create_refus_install":
		/*if($utilisateur->HasDroits("10_230"))
			{	*/
		if ($_POST) {
			$item = new PARAM_VisitLog($db);
			$item->ref_log_visite =  uniqUid("t_param_log_visite_pa", "ref_log_visite");
			$item->statut_accessibilite =  isset($_POST["refus_accessibility"]) ? $_POST["refus_accessibility"] : "";
			//$item->num_pa =  isset($_POST["id_group"])?$_POST["id_group"]:"";
			$item->type_motif_visite = "2"; // Installation
			$item->site_id = $utilisateur->site_id;
			$item->cvs_id =  isset($_POST["refus_cvs"]) ? $_POST["refus_cvs"] : "";
			$item->assign_id =  isset($_POST["refus_assign_id"]) ? $_POST["refus_assign_id"] : "";
			$item->commentaire =  isset($_POST["refus_comment"]) ? $_POST["refus_comment"] : "";
			$item->date_rendez_vous =  isset($_POST["dat_rendez_vous"]) ? $_POST["dat_rendez_vous"] : "";

			//ADRESSE
			$item->adress_id = isset($_POST["refus_adress_id"]) ? $_POST["refus_adress_id"] : "";
			//ADRESSE				   
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;
		/* END REFUS */
	case "edit_group_user":
		if ($utilisateur->HasDroits("10_230")) {
			if ($_POST) {
				$groupe = new GroupUtilisateur($db);
				$groupe->id_group =  isset($_POST["id_group"]) ? $_POST["id_group"] : "";
				$groupe->intitule = isset($_POST["intitule"]) ? $_POST["intitule"] : "";
				$groupe->id_service = isset($_POST["id_service"]) ? $_POST["id_service"] : "";
				$groupe->n_user_create = $utilisateur->code_utilisateur;
				$result_array = $groupe->Modifier();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "delete_group_user":
		if ($utilisateur->HasDroits("10_220")) {
			if ($_POST) {

				$group = new GroupUtilisateur($db);
				$group->id_group = isset($_POST["id_group"]) ? $_POST["id_group"] : "";
				if ($group->Supprimer()) {
					$result_array["error"] = 0;
					$result_array["message"] = "Suppression effectuée avec succès";
				} else {
					$result_array["error"] = 1;
					$result_array["message"] = "L'opération n'a pas pu être effectuée";
				}
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;


	case "delete_site":
		if ($utilisateur->HasDroits("12_32")) {
			if ($_POST) {

				$site = new Site($db);
				$site->code_site = isset($_POST["k"]) ? $_POST["k"] : "";
				if ($site->Supprimer()) {
					$result_array["error"] = 0;
					$result_array["message"] = "Suppression effectuée avec succès";
				} else {
					$result_array["error"] = 1;
					$result_array["message"] = "L'opération n'a pas pu être effectuée";
				}
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;


	case "ticket_require":
		/*	if($utilisateur->HasDroits("12_32"))
			{	*/
		if ($_POST) {
			$item = new PARAM_Notification($db);
			$item->n_user_create = $utilisateur->code_utilisateur;
			$fiche = isset($_POST["fiche"]) ? $_POST["fiche"] : "";
			$compteur = isset($_POST["compteur"]) ? $_POST["compteur"] : "";
			$marque = isset($_POST["marque"]) ? $_POST["marque"] : "";
			$type_cpteur = isset($_POST["type_cpteur"]) ? $_POST["type_cpteur"] : "";
			$site_id = $utilisateur->site_id;
			$result_array = $item->CreateTicketDemande($fiche, $compteur, $marque, $type_cpteur, $site_id);

			echo json_encode($result_array);
		}
		/*	}else{
				DroitsNotGranted();
			}*/
		break;
		/*	case "assign_ticket":
			if($utilisateur->HasDroits("12_32"))
			{	 			
				if($_POST){								
						$item = new PARAM_Notification($db); 
						$ref_log = isset($_POST["ref_demande"])?$_POST["ref_demande"]:"";
						$item->n_user_create = $utilisateur->code_utilisateur;	
						$numero_ticket = isset($_POST["numero_ticket"])?$_POST["numero_ticket"]:""; 
						$result_array = $item->AssignerTicket($ref_log,$numero_ticket);						 
						echo json_encode($result_array);			
				}
		 	}else{
				DroitsNotGranted();
			
			break;
		}*/


	case "assign_ticket":
		/*	if($utilisateur->HasDroits("12_32"))
			{	*/

		if (isset($_FILES['photo_pa_avant']) == FALSE) {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre une photo du Ticket";
			echo json_encode($result_array);
			exit;
		}
		if ($_POST) {
			$item = new PARAM_Notification($db);
			$ref_log = isset($_POST["ref_demande"]) ? $_POST["ref_demande"] : "";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$numero_ticket = isset($_POST["numero_ticket"]) ? $_POST["numero_ticket"] : "";
			$result_array = $item->AssignerTicket($ref_log, $numero_ticket);

			if ($result_array["error"] == 0) {
				//UPLOAD FAIL
				//$filename=$result_array["id"].'.jpeg';
				/*$filename=$item->id_.'.png';
									if(isset($_FILES['photo_pa_avant'])){
										if( move_uploaded_file($_FILES['photo_pa_avant']['tmp_name'],'pictures/'.$filename) ){
										 $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
										// echo $url;
										}			
									}*/
				$nbre = isset($_FILES['photo_pa_avant']) ? count($_FILES['photo_pa_avant']['name']) : 0;
				if ($nbre > 0) {
					$item->datesys = date("Y-m-d H:i:s");
					$item->n_user_create = $utilisateur->code_utilisateur;
					for ($i = 0; $i < $nbre; $i++) {
						$code_  = uniqUid("t_param_notification_photo", "ref_photo");
						$label = $_POST["photo_pa_avant_label"][$i];
						//echo $label;

						$can_upload = $item->CreatePhoto($ref_log, $code_, $label);
						if ($can_upload) {

							Utils::F_Exist('tickets');
							Utils::F_Exist('tickets_temp');
							$filename = $code_ . '.png';

							$source_image = 'tickets_temp/' . $filename;
							$image_destination = 'tickets/' . $filename;
							if (move_uploaded_file($_FILES['photo_pa_avant']['tmp_name'][$i], $source_image)) {
								Utils::compress2($source_image, $image_destination, 50);
								unlink($source_image);
								//$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
							}
						}
					}
				}
			}


			//	exit;								
			echo json_encode($result_array);
		}
		/*	}else{
				DroitsNotGranted();
			}*/
		break;

	case "delete_customer":
		/*if($utilisateur->HasDroits("12_24"))
			{	*/
		if ($_POST) {

			$item = new Identification($db);
			$item->id_ = isset($_POST["k"]) ? $_POST["k"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->n_user_update = $utilisateur->code_utilisateur;
			if ($item->Supprimer()) {
				$result_array["error"] = 0;
				$result_array["message"] = "Suppression effectuée avec succès";
			} else {
				$result_array["error"] = 1;
				$result_array["message"] = "L'opération n'a pas pu être effectuée";
			}
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "delete_control":
		/*if($utilisateur->HasDroits("12_24"))
			{	*/
		if ($_POST) {

			$item = new CLS_Controle($db);
			$item->ref_fiche_controle = isset($_POST["k"]) ? $_POST["k"] : "";
			$item->motif_annule = isset($_POST["motif_annule"]) ? $_POST["motif_annule"] : "";
			$item->n_user_update = $utilisateur->code_utilisateur;
			if ($item->Supprimer()) {
				$result_array["error"] = 0;
				$result_array["message"] = "Suppression effectuée avec succès";
			} else {
				$result_array["error"] = 1;
				$result_array["message"] = "L'opération n'a pas pu être effectuée";
			}
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;
	case "delete_install":
		/*if($utilisateur->HasDroits("12_24"))
			{	*/
		if ($_POST) {

			$item = new Installation($db);
			$item->id_install = isset($_POST["k"]) ? $_POST["k"] : "";
			if ($item->Supprimer()) {
				$result_array["error"] = 0;
				$result_array["message"] = "Suppression effectuée avec succès";
			} else {
				$result_array["error"] = 1;
				$result_array["message"] = "L'opération n'a pas pu être effectuée";
			}
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "delete_user":
		if ($utilisateur->HasDroits("10_170")) {
			if ($_POST) {
				$utilisateur->code_utilisateur = isset($_POST["k"]) ? $_POST["k"] : "";
				if ($utilisateur->Supprimer()) {
					$result_array["error"] = 0;
					$result_array["message"] = "Suppression effectuée avec succès";
				} else {
					$result_array["error"] = 1;
					$result_array["message"] = "L'opération n'a pas pu être effectuée";
				}
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;

	case "reset_user":
		if ($utilisateur->HasDroits("12_57")) {
			if ($_POST) {
				$utilisateur->code_utilisateur = isset($_POST["k"]) ? $_POST["k"] : "";
				if ($utilisateur->ResetPwd()) {
					$result_array["error"] = 0;
					$result_array["message"] = "Ré-initialisation effectuée avec succès";
				} else {
					$result_array["error"] = 1;
					$result_array["message"] = "L'opération n'a pas pu être effectuée";
				}
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "create_install":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		if ($_POST) {
			$item = new Installation($db);
			//$item->id_install = isset($_POST["id_install"])?$_POST["id_install"]:"";    
			/*$item->ref_identific= isset($_POST["ref_identific"])?$_POST["ref_identific"]:"";  						
						$item->date_debut_installation= isset($_POST["date_debut_installation"])?$_POST["date_debut_installation"]:"";
						$item->p_a= isset($_POST["p_a"])?$_POST["p_a"]:"";  
						$item->id_equipe= isset($_POST["nom_equipe"])?$_POST["nom_equipe"]:"";  
						$item->nom_equipe= isset($_POST["nom_equipe"])?$_POST["nom_equipe"]:"";  
						$item->numero_compteur= isset($_POST["numero_compteur"])?$_POST["numero_compteur"]:"";  
						$item->marque_compteur= isset($_POST["marque_compteur"])?$_POST["marque_compteur"]:"";  
						$item->commentaires= isset($_POST["commentaires"])?$_POST["commentaires"]:"";  */
			$item->is_draft_install = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "1";

			if (isset($_FILES['photo_compteur']) == FALSE && $item->is_draft_install == '0') {
				$result_array["error"] = true;
				$result_array["message"] = "Veuillez prendre la photo du compteur";
				echo json_encode($result_array);
				exit;
			}
			//$item->id_install=isset($_POST["id_install"])?$_POST["id_install"]:"";  
			$item->ref_identific = isset($_POST["ref_identific"]) ? $_POST["ref_identific"] : "";
			$item->cabine = isset($_POST["cabine"]) ? $_POST["cabine"] : "";
			$item->num_depart = isset($_POST["num_depart"]) ? $_POST["num_depart"] : "";
			$item->num_poteau = isset($_POST["num_poteau"]) ? $_POST["num_poteau"] : "";
			$item->type_raccordement = isset($_POST["type_raccordement"]) ? $_POST["type_raccordement"] : "";
			$item->type_cpteur_raccord = isset($_POST["type_cpteur_raccord"]) ? $_POST["type_cpteur_raccord"] : "";
			$item->nbre_alimentation = isset($_POST["nbre_alimentation"]) ? $_POST["nbre_alimentation"] : "";
			$item->section_cable_alimentation = isset($_POST["section_cable_alimentation"]) ? $_POST["section_cable_alimentation"] : "";
			$item->section_cable_alimentation_deux = isset($_POST["section_cable_alimentation_deux"]) ? $_POST["section_cable_alimentation_deux"] : "";
			$item->section_cable_sortie = isset($_POST["section_cable_sortie"]) ? $_POST["section_cable_sortie"] : "";
			$item->presence_inverseur = isset($_POST["presence_inverseur"]) ? $_POST["presence_inverseur"] : "";
			$item->marque_cpteur_post_paie = isset($_POST["marque_cpteur_post_paie"]) ? $_POST["marque_cpteur_post_paie"] : "";
			$item->code_tarif = isset($_POST["code_tarif"]) ? $_POST["code_tarif"] : "";
			$item->date_retrait_cpteur_post_paie = isset($_POST["date_retrait_cpteur_post_paie"]) ? $_POST["date_retrait_cpteur_post_paie"] : "";
			$item->num_serie_cpteur_post_paie = isset($_POST["num_serie_cpteur_post_paie"]) ? $_POST["num_serie_cpteur_post_paie"] : "";
			$item->index_par_defaut = isset($_POST["index_par_defaut"]) ? $_POST["index_par_defaut"] : "";
			$item->index_credit_restant_cpteur_post_paie = isset($_POST["index_credit_restant_cpteur_post_paie"]) ? $_POST["index_credit_restant_cpteur_post_paie"] : "";
			$item->marque_cpteur_replaced = isset($_POST["marque_cpteur_replaced"]) ? $_POST["marque_cpteur_replaced"] : "";
			$item->num_serie_cpteur_replaced = isset($_POST["num_serie_cpteur_replaced"]) ? $_POST["num_serie_cpteur_replaced"] : "";
			$item->index_credit_restant_cpteur_replaced = isset($_POST["index_credit_restant_cpteur_replaced"]) ? $_POST["index_credit_restant_cpteur_replaced"] : "";
			$item->marque_compteur = isset($_POST["marque_compteur"]) ? $_POST["marque_compteur"] : "";
			$item->numero_compteur = isset($_POST["numero_compteur"]) ? $_POST["numero_compteur"] : "";
			$item->type_new_cpteur = isset($_POST["type_new_cpteur"]) ? $_POST["type_new_cpteur"] : "";
			$item->disjoncteur = isset($_POST["disjoncteur"]) ? $_POST["disjoncteur"] : "";
			$item->replace_client_disjonct = isset($_POST["replace_client_disjonct"]) ? $_POST["replace_client_disjonct"] : "";
			$item->client_disjonct_amperage = isset($_POST["client_disjonct_amperage"]) ? $_POST["client_disjonct_amperage"] : "";
			$item->scelle_un_cpteur = isset($_POST["scelle_un_cpteur"]) ? $_POST["scelle_un_cpteur"] : "";
			$item->scelle_deux_coffret = isset($_POST["scelle_deux_coffret"]) ? $_POST["scelle_deux_coffret"] : "";
			$item->commentaire_installateur = isset($_POST["commentaire_installateur"]) ? $_POST["commentaire_installateur"] : "";
			//$item->commenteur_controle_blue=isset($_POST["commenteur_controle_blue"])?$_POST["commenteur_controle_blue"]:"";
			$item->installateur = isset($_POST["installateur"]) ? $_POST["installateur"] : "";
			$item->chef_equipe = isset($_POST["chef_equipe_install"]) ? $_POST["chef_equipe_install"] : "";
			$item->controleur_blue = isset($_POST["controleur_blue"]) ? $_POST["controleur_blue"] : "";
			$item->agent_cvs = isset($_POST["agent_cvs"]) ? $_POST["agent_cvs"] : "";
			//$item->date_pose_scelle=isset($_POST["date_pose_scelle"])?Utils::ClientToDbDateFormat($_POST["date_pose_scelle"]):"";
			$item->type_installation = isset($_POST["type_installation"]) ? $_POST["type_installation"] : "";
			$item->usage_electricity = isset($_POST["usage_electricity"]) ? $_POST["usage_electricity"] : "";
			$item->etat_poc = isset($_POST["etat_poc"]) ? $_POST["etat_poc"] : "";
			$item->photo_compteur = isset($_POST["photo_compteur"]) ? $_POST["photo_compteur"] : "";
			$item->post_paie_trouver = isset($_POST["post_paie_trouver"]) ? $_POST["post_paie_trouver"] : "";
			//$item->date_debut_installation=isset($_POST["date_debut_installation"])?$_POST["date_debut_installation"]:"";
			//$item->date_fin_installation=isset($_POST["date_fin_installation"])?$_POST["date_fin_installation"]:"";
			$item->gps_longitude = isset($_POST["gps_longitude_install"]) ? $_POST["gps_longitude_install"] : "";
			$item->gps_latitude = isset($_POST["gps_latitude_install"]) ? $_POST["gps_latitude_install"] : "";
			$item->nom_installateur = isset($_POST["nom_installateur"]) ? $_POST["nom_installateur"] : "";
			$item->id_equipe = isset($_POST["id_equipe"]) ? $_POST["id_equipe"] : "";
			$item->nom_equipe = isset($_POST["nom_equipe"]) ? $_POST["nom_equipe"] : "";
			$item->code_installateur = isset($_POST["installateur"]) ? $_POST["installateur"] : "";
			$item->statut_installation = isset($_POST["statut_installation"]) ? $_POST["statut_installation"] : "";
			$item->is_autocollant_posed = isset($_POST["is_autocollant_posed"]) ? $_POST["is_autocollant_posed"] : "";
			$item->id_assign = isset($_POST["id_assign"]) ? $_POST["id_assign"] : "";
			$item->etat_compteur_reaffected = isset($_POST["etat_compteur_reaffected"]) ? $_POST["etat_compteur_reaffected"] : "";


			$item->lst_materiels = isset($_POST["lst_materiels"]) ? json_decode($_POST["lst_materiels"]) : "[]";
			$item->n_user_create = $utilisateur->code_utilisateur;
			//$item->code_installateur =$utilisateur->code_utilisateur;  
			$item->nom_installateur = $utilisateur->nom_utilisateur;
			$item->ref_site_install = $utilisateur->site_id;
			$result_array = $item->CreateWeb(0);
			if ($result_array["error"] == 0) {
				//UPLOAD FAIL
				//$filename=$result_array["id"].'.jpeg';
				processCaptureInstall($_FILES, $item->id_install);
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;

	case "create_install_rpl":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		if ($_POST) {
			$item = new Installation($db);
			//$item->id_install = isset($_POST["id_install"])?$_POST["id_install"]:"";    
			/*$item->ref_identific= isset($_POST["ref_identific"])?$_POST["ref_identific"]:"";  						
						$item->date_debut_installation= isset($_POST["date_debut_installation"])?$_POST["date_debut_installation"]:"";
						$item->p_a= isset($_POST["p_a"])?$_POST["p_a"]:"";  
						$item->id_equipe= isset($_POST["nom_equipe"])?$_POST["nom_equipe"]:"";  
						$item->nom_equipe= isset($_POST["nom_equipe"])?$_POST["nom_equipe"]:"";  
						$item->numero_compteur= isset($_POST["numero_compteur"])?$_POST["numero_compteur"]:"";  
						$item->marque_compteur= isset($_POST["marque_compteur"])?$_POST["marque_compteur"]:"";  
						$item->commentaires= isset($_POST["commentaires"])?$_POST["commentaires"]:"";  */

			// if(isset($_FILES['photo_compteur']) == FALSE ){
			$item->is_draft_install = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "1";
			$item->etat_compteur_reaffected = isset($_POST["etat_compteur_reaffected"]) ? $_POST["etat_compteur_reaffected"] : "";

			if (isset($_FILES['photo_compteur']) == FALSE && $item->is_draft_install == '0') {
				$result_array["error"] = true;
				$result_array["message"] = "Veuillez prendre la photo du compteur";
				echo json_encode($result_array);
				exit;
			}
			//$item->id_install=isset($_POST["id_install"])?$_POST["id_install"]:"";  
			$item->ref_identific = isset($_POST["ref_identific"]) ? $_POST["ref_identific"] : "";
			$item->cabine = isset($_POST["cabine"]) ? $_POST["cabine"] : "";
			$item->num_depart = isset($_POST["num_depart"]) ? $_POST["num_depart"] : "";
			$item->num_poteau = isset($_POST["num_poteau"]) ? $_POST["num_poteau"] : "";
			$item->type_raccordement = isset($_POST["type_raccordement"]) ? $_POST["type_raccordement"] : "";
			$item->type_cpteur_raccord = isset($_POST["type_cpteur_raccord"]) ? $_POST["type_cpteur_raccord"] : "";
			$item->nbre_alimentation = isset($_POST["nbre_alimentation"]) ? $_POST["nbre_alimentation"] : "";
			$item->section_cable_alimentation = isset($_POST["section_cable_alimentation"]) ? $_POST["section_cable_alimentation"] : "";
			$item->section_cable_alimentation_deux = isset($_POST["section_cable_alimentation_deux"]) ? $_POST["section_cable_alimentation_deux"] : "";
			$item->section_cable_sortie = isset($_POST["section_cable_sortie"]) ? $_POST["section_cable_sortie"] : "";
			$item->presence_inverseur = isset($_POST["presence_inverseur"]) ? $_POST["presence_inverseur"] : "";
			$item->marque_cpteur_post_paie = isset($_POST["marque_cpteur_post_paie"]) ? $_POST["marque_cpteur_post_paie"] : "";
			$item->date_retrait_cpteur_post_paie = isset($_POST["date_retrait_cpteur_post_paie"]) ? $_POST["date_retrait_cpteur_post_paie"] : "";
			$item->num_serie_cpteur_post_paie = isset($_POST["num_serie_cpteur_post_paie"]) ? $_POST["num_serie_cpteur_post_paie"] : "";
			$item->index_credit_restant_cpteur_post_paie = isset($_POST["index_credit_restant_cpteur_post_paie"]) ? $_POST["index_credit_restant_cpteur_post_paie"] : "";
			$item->marque_cpteur_replaced = isset($_POST["marque_cpteur_replaced"]) ? $_POST["marque_cpteur_replaced"] : "";
			$item->num_serie_cpteur_replaced = isset($_POST["num_serie_cpteur_replaced"]) ? $_POST["num_serie_cpteur_replaced"] : "";
			$item->index_credit_restant_cpteur_replaced = isset($_POST["index_credit_restant_cpteur_replaced"]) ? $_POST["index_credit_restant_cpteur_replaced"] : "";
			$item->type_defaut = isset($_POST["type_defaut"]) ? $_POST["type_defaut"] : "";
			$item->marque_compteur = isset($_POST["marque_compteur"]) ? $_POST["marque_compteur"] : "";
			$item->numero_compteur = isset($_POST["numero_compteur"]) ? $_POST["numero_compteur"] : "";
			$item->type_new_cpteur = isset($_POST["type_new_cpteur"]) ? $_POST["type_new_cpteur"] : "";
			$item->disjoncteur = isset($_POST["disjoncteur"]) ? $_POST["disjoncteur"] : "";
			$item->replace_client_disjonct = isset($_POST["replace_client_disjonct"]) ? $_POST["replace_client_disjonct"] : "";
			$item->client_disjonct_amperage = isset($_POST["client_disjonct_amperage"]) ? $_POST["client_disjonct_amperage"] : "";
			$item->scelle_un_cpteur = isset($_POST["scelle_un_cpteur"]) ? $_POST["scelle_un_cpteur"] : "";
			$item->scelle_deux_coffret = isset($_POST["scelle_deux_coffret"]) ? $_POST["scelle_deux_coffret"] : "";
			$item->commentaire_installateur = isset($_POST["commentaire_installateur"]) ? $_POST["commentaire_installateur"] : "";
			//$item->commenteur_controle_blue=isset($_POST["commenteur_controle_blue"])?$_POST["commenteur_controle_blue"]:"";
			$item->installateur = isset($_POST["installateur"]) ? $_POST["installateur"] : "";
			$item->chef_equipe = isset($_POST["chef_equipe_install"]) ? $_POST["chef_equipe_install"] : "";
			$item->controleur_blue = isset($_POST["controleur_blue"]) ? $_POST["controleur_blue"] : "";
			$item->agent_cvs = isset($_POST["agent_cvs"]) ? $_POST["agent_cvs"] : "";
			//$item->date_pose_scelle=isset($_POST["date_pose_scelle"])?Utils::ClientToDbDateFormat($_POST["date_pose_scelle"]):"";
			$item->type_installation = isset($_POST["type_installation"]) ? $_POST["type_installation"] : "";
			$item->usage_electricity = isset($_POST["usage_electricity"]) ? $_POST["usage_electricity"] : "";
			$item->etat_poc = isset($_POST["etat_poc"]) ? $_POST["etat_poc"] : "";
			$item->photo_compteur = isset($_POST["photo_compteur"]) ? $_POST["photo_compteur"] : "";
			//$item->date_debut_installation=isset($_POST["date_debut_installation"])?$_POST["date_debut_installation"]:"";
			//$item->date_fin_installation=isset($_POST["date_fin_installation"])?$_POST["date_fin_installation"]:"";
			$item->gps_longitude = isset($_POST["gps_longitude_install"]) ? $_POST["gps_longitude_install"] : "";
			$item->gps_latitude = isset($_POST["gps_latitude_install"]) ? $_POST["gps_latitude_install"] : "";
			$item->nom_installateur = isset($_POST["nom_installateur"]) ? $_POST["nom_installateur"] : "";
			$item->id_equipe = isset($_POST["id_equipe"]) ? $_POST["id_equipe"] : "";
			$item->nom_equipe = isset($_POST["nom_equipe"]) ? $_POST["nom_equipe"] : "";
			$item->code_installateur = isset($_POST["code_installateur"]) ? $_POST["code_installateur"] : "";
			$item->statut_installation = isset($_POST["statut_installation"]) ? $_POST["statut_installation"] : "";
			$item->is_autocollant_posed = isset($_POST["is_autocollant_posed"]) ? $_POST["is_autocollant_posed"] : "";
			$item->id_assign = isset($_POST["id_assign"]) ? $_POST["id_assign"] : "";


			$item->lst_materiels = isset($_POST["lst_materiels"]) ? json_decode($_POST["lst_materiels"]) : "[]";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$item->code_installateur = $utilisateur->code_utilisateur;
			$item->nom_installateur = $utilisateur->nom_utilisateur;
			$item->ref_site_install = $utilisateur->site_id;
			$result_array = $item->CreateWeb(1); //REMPLACEMENT
			if ($result_array["error"] == 0) {
				processCaptureInstall($_FILES, $item->id_install);
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;

	case "edit_install":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		if ($_POST) {
			$item = new Installation($db);
			$item->id_install = isset($_POST["id_install"]) ? $_POST["id_install"] : "";
			//$item->ref_identific= isset($_POST["ref_identific"])?$_POST["ref_identific"]:"";  						


			/*$item->date_debut_installation= isset($_POST["date_debut_installation"])?$_POST["date_debut_installation"]:"";
						$item->p_a= isset($_POST["p_a"])?$_POST["p_a"]:"";  
						$item->id_equipe= isset($_POST["nom_equipe"])?$_POST["nom_equipe"]:"";  
						$item->nom_equipe= isset($_POST["nom_equipe"])?$_POST["nom_equipe"]:"";  
						$item->numero_compteur= isset($_POST["numero_compteur"])?$_POST["numero_compteur"]:"";  
						$item->photo_compteur= $item->id_install.'_CTR.jpeg';  
						$item->marque_compteur= isset($_POST["marque_compteur"])?$_POST["marque_compteur"]:"";  
						$item->commentaires= isset($_POST["commentaires"])?$_POST["commentaires"]:"";  
						$item->lst_materiels= isset($_POST["lst_materiels"])?json_decode($_POST["lst_materiels"]):"[]";
						$item->code_installateur =$utilisateur->code_utilisateur;  
						$item->nom_installateur =$utilisateur->nom_utilisateur;  
						 */




			$item->is_draft_install = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "1";
			$item->etat_compteur_reaffected = isset($_POST["etat_compteur_reaffected"]) ? $_POST["etat_compteur_reaffected"] : "";



			$item->ref_identific = isset($_POST["ref_identific"]) ? $_POST["ref_identific"] : "";
			$item->cabine = isset($_POST["cabine"]) ? $_POST["cabine"] : "";
			$item->num_depart = isset($_POST["num_depart"]) ? $_POST["num_depart"] : "";
			$item->num_poteau = isset($_POST["num_poteau"]) ? $_POST["num_poteau"] : "";
			$item->type_raccordement = isset($_POST["type_raccordement"]) ? $_POST["type_raccordement"] : "";
			$item->type_cpteur_raccord = isset($_POST["type_cpteur_raccord"]) ? $_POST["type_cpteur_raccord"] : "";
			$item->nbre_alimentation = isset($_POST["nbre_alimentation"]) ? $_POST["nbre_alimentation"] : "";
			$item->section_cable_alimentation = isset($_POST["section_cable_alimentation"]) ? $_POST["section_cable_alimentation"] : "";
			$item->section_cable_alimentation_deux = isset($_POST["section_cable_alimentation_deux"]) ? $_POST["section_cable_alimentation_deux"] : "";
			$item->section_cable_sortie = isset($_POST["section_cable_sortie"]) ? $_POST["section_cable_sortie"] : "";
			$item->presence_inverseur = isset($_POST["presence_inverseur"]) ? $_POST["presence_inverseur"] : "";
			$item->marque_cpteur_post_paie = isset($_POST["marque_cpteur_post_paie"]) ? $_POST["marque_cpteur_post_paie"] : "";
			$item->code_tarif = isset($_POST["code_tarif"]) ? $_POST["code_tarif"] : "";
			$item->date_retrait_cpteur_post_paie = isset($_POST["date_retrait_cpteur_post_paie"]) ? $_POST["date_retrait_cpteur_post_paie"] : "";
			$item->index_par_defaut = isset($_POST["index_par_defaut"]) ? $_POST["index_par_defaut"] : "";
			$item->num_serie_cpteur_post_paie = isset($_POST["num_serie_cpteur_post_paie"]) ? $_POST["num_serie_cpteur_post_paie"] : "";
			$item->index_credit_restant_cpteur_post_paie = isset($_POST["index_credit_restant_cpteur_post_paie"]) ? $_POST["index_credit_restant_cpteur_post_paie"] : "";
			$item->marque_cpteur_replaced = isset($_POST["marque_cpteur_replaced"]) ? $_POST["marque_cpteur_replaced"] : "";
			$item->num_serie_cpteur_replaced = isset($_POST["num_serie_cpteur_replaced"]) ? $_POST["num_serie_cpteur_replaced"] : "";
			$item->index_credit_restant_cpteur_replaced = isset($_POST["index_credit_restant_cpteur_replaced"]) ? $_POST["index_credit_restant_cpteur_replaced"] : "";
			$item->marque_compteur = isset($_POST["marque_compteur"]) ? $_POST["marque_compteur"] : "";
			$item->numero_compteur = isset($_POST["numero_compteur"]) ? $_POST["numero_compteur"] : "";
			$item->type_new_cpteur = isset($_POST["type_new_cpteur"]) ? $_POST["type_new_cpteur"] : "";
			$item->disjoncteur = isset($_POST["disjoncteur"]) ? $_POST["disjoncteur"] : "";
			$item->replace_client_disjonct = isset($_POST["replace_client_disjonct"]) ? $_POST["replace_client_disjonct"] : "";
			$item->client_disjonct_amperage = isset($_POST["client_disjonct_amperage"]) ? $_POST["client_disjonct_amperage"] : "";
			$item->scelle_un_cpteur = isset($_POST["scelle_un_cpteur"]) ? $_POST["scelle_un_cpteur"] : "";
			$item->scelle_deux_coffret = isset($_POST["scelle_deux_coffret"]) ? $_POST["scelle_deux_coffret"] : "";
			$item->commentaire_installateur = isset($_POST["commentaire_installateur"]) ? $_POST["commentaire_installateur"] : "";
			//$item->commenteur_controle_blue=isset($_POST["commenteur_controle_blue"])?$_POST["commenteur_controle_blue"]:"";
			$item->installateur = isset($_POST["installateur"]) ? $_POST["installateur"] : "";
			$item->chef_equipe = isset($_POST["chef_equipe_install"]) ? $_POST["chef_equipe_install"] : "";
			$item->controleur_blue = isset($_POST["controleur_blue"]) ? $_POST["controleur_blue"] : "";
			$item->agent_cvs = isset($_POST["agent_cvs"]) ? $_POST["agent_cvs"] : "";
			$item->date_pose_scelle = isset($_POST["date_pose_scelle"]) ? Utils::ClientToDbDateFormat($_POST["date_pose_scelle"]) : "";
			$item->type_installation = isset($_POST["type_installation"]) ? $_POST["type_installation"] : "";
			$item->usage_electricity = isset($_POST["usage_electricity"]) ? $_POST["usage_electricity"] : "";
			$item->etat_poc = isset($_POST["etat_poc"]) ? $_POST["etat_poc"] : "";
			$item->photo_compteur = isset($_POST["photo_compteur"]) ? $_POST["photo_compteur"] : "";
			//$item->date_debut_installation=isset($_POST["date_debut_installation"])?$_POST["date_debut_installation"]:"";
			//$item->date_fin_installation=isset($_POST["date_fin_installation"])?$_POST["date_fin_installation"]:"";
			$item->gps_longitude = isset($_POST["gps_longitude_install"]) ? $_POST["gps_longitude_install"] : "";
			$item->gps_latitude = isset($_POST["gps_latitude_install"]) ? $_POST["gps_latitude_install"] : "";
			$item->nom_installateur = isset($_POST["nom_installateur"]) ? $_POST["nom_installateur"] : "";
			$item->id_equipe = isset($_POST["id_equipe"]) ? $_POST["id_equipe"] : "";
			$item->nom_equipe = isset($_POST["nom_equipe"]) ? $_POST["nom_equipe"] : "";
			$item->code_installateur = isset($_POST["installateur"]) ? $_POST["installateur"] : "";
			$item->statut_installation = isset($_POST["statut_installation"]) ? $_POST["statut_installation"] : "";
			$item->is_autocollant_posed = isset($_POST["is_autocollant_posed"]) ? $_POST["is_autocollant_posed"] : "";


			$item->lst_materiels = isset($_POST["lst_materiels"]) ? json_decode($_POST["lst_materiels"]) : "[]";
			$item->n_user_create = $utilisateur->code_utilisateur;
			//$item->code_installateur =$utilisateur->code_utilisateur;  
			$item->nom_installateur = $utilisateur->nom_utilisateur;

			$result_array = $item->Modifier();
			if ($result_array["error"] == 0) {
				processCaptureInstall($_FILES, $item->id_install);
				//UPLOAD FAIL
				//$filename=$result_array["id"].'.jpeg';

			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;
	case "create_customer_deactive":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		if ($_POST) {

			/*	if(isset($_FILES['photo_pa_avant']) == FALSE ){
							$result_array["error"] = true;
							$result_array["message"] = "Veuillez prendre une photo du P.A";
							echo json_encode($result_array);
							EXIT;
						}	*/

			$item = new Identification($db);
			$item->id_ = uniqUid("t_main_data", "id_");

			//$item->numero_avenue= isset($_POST["numero_avenue"])?$_POST["numero_avenue"]:"";
			//$item->commune_id= isset($_POST["commune_id"])?$_POST["commune_id"]:"";  
			//$item->adresse= isset($_POST["adresse"])?$_POST["adresse"]:""; 
			//$item->quartier= isset($_POST["quartier"])?$_POST["quartier"]:"";

			//ADRESSE
			$item->quartier_id = isset($_POST["quartier"]) ? $_POST["quartier"] : "";
			$item->commune_id = isset($_POST["commune_id"]) ? $_POST["commune_id"] : "";
			$item->ville_id = isset($_POST["ville_id"]) ? $_POST["ville_id"] : "";
			//$item->province_id = isset($_POST["refus_quartier"])?$_POST["id_group"]:"";		 
			$item->numero = isset($_POST["numero_avenue"]) ? $_POST["numero_avenue"] : "";
			$item->avenue = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
			//ADRESSE



			$item->p_a = isset($_POST["p_a"]) ? $_POST["p_a"] : "";

			$item->gps_longitude = isset($_POST["gps_longitude"]) ? $_POST["gps_longitude"] : "";
			$item->gps_latitude = isset($_POST["gps_latitude"]) ? $_POST["gps_latitude"] : "";
			$item->num_compteur_actuel = isset($_POST["num_compteur_actuel"]) ? $_POST["num_compteur_actuel"] : "";

			$item->cvs_id = isset($_POST["cvs_id"]) ? $_POST["cvs_id"] : "";
			$item->nbre_branchement = isset($_POST["nbre_branchement"]) ? $_POST["nbre_branchement"] : "";
			$item->section_cable = isset($_POST["section_cable"]) ? $_POST["section_cable"] : "";
			$item->lst_materiels = isset($_POST["lst_materiels"]) ? json_decode($_POST["lst_materiels"]) : "[]";





			$item->numero_piece_identity = isset($_POST["numero_piece_identity"]) ? $_POST["numero_piece_identity"] : "";

			$item->accessibility_client = isset($_POST["accessibility_client"]) ? $_POST["accessibility_client"] : "";
			$item->tarif_identif = isset($_POST["tarif_identif"]) ? $_POST["tarif_identif"] : "";
			$item->infos_supplementaires = isset($_POST["infos_supplementaires"]) ? $_POST["infos_supplementaires"] : "";
			$item->nbre_menage_a_connecter = isset($_POST["nbre_menage_a_connecter"]) ? $_POST["nbre_menage_a_connecter"] : "";
			$item->reference_appartement = isset($_POST["reference_appartement"]) ? $_POST["reference_appartement"] : "";
			$item->noms_equipe_blue_energy = isset($_POST["noms_equipe_blue_energy"]) ? $_POST["noms_equipe_blue_energy"] : "";
			$item->numero_depart = isset($_POST["numero_depart"]) ? $_POST["numero_depart"] : "";
			$item->numero_poteau_identif = isset($_POST["numero_poteau_identif"]) ? $_POST["numero_poteau_identif"] : "";
			$item->type_raccordement_identif = isset($_POST["type_raccordement_identif"]) ? $_POST["type_raccordement_identif"] : "";
			$item->type_compteur = isset($_POST["type_compteur"]) ? $_POST["type_compteur"] : "";
			$item->type_construction = isset($_POST["type_construction"]) ? $_POST["type_construction"] : "";
			$item->nbre_appartement = isset($_POST["nbre_appartement"]) ? $_POST["nbre_appartement"] : "";
			$item->nbre_habitant = isset($_POST["nbre_habitant"]) ? $_POST["nbre_habitant"] : "";
			$item->type_activites = isset($_POST["type_activites"]) ? $_POST["type_activites"] : "";
			$item->conformites_installation = isset($_POST["conformites_installation"]) ? $_POST["conformites_installation"] : "";
			$item->avis_technique_blue = isset($_POST["avis_technique_blue"]) ? $_POST["avis_technique_blue"] : "";
			$item->avis_occupant = isset($_POST["avis_occupant"]) ? $_POST["avis_occupant"] : "";
			$item->chef_equipe = isset($_POST["chef_equipe"]) ? $_POST["chef_equipe"] : "";
			// $item->statut_client= isset($_POST["statut_client"])?$_POST["statut_client"]:"";
			$item->titre_responsable = isset($_POST["titre_responsable"]) ? $_POST["titre_responsable"] : "";
			$item->titre_remplacant = isset($_POST["titre_remplacant"]) ? $_POST["titre_remplacant"] : "";


			//////////////////////
			$item->client_id = isset($_POST["client_id"]) ? $_POST["client_id"] : "";
			$item->occupant_id = isset($_POST["occupant_id"]) ? $_POST["occupant_id"] : "";

			// $item->statut_occupant= isset($_POST["statut_occupant"])?$_POST["statut_occupant"]:"";
			// $item->nom_occupant_trouver= isset($_POST["nom_occupant_trouver"])?$_POST["nom_occupant_trouver"]:"";
			// $item->phone_occupant_trouver= isset($_POST["phone_occupant_trouver"])?$_POST["phone_occupant_trouver"]:"";

			/////////////////////
			$item->type_raccordement_propose = isset($_POST["type_raccordement_propose"]) ? $_POST["type_raccordement_propose"] : "";
			$item->nature_activity = isset($_POST["nature_activity"]) ? $_POST["nature_activity"] : "";
			$item->type_client = isset($_POST["type_client"]) ? $_POST["type_client"] : "";
			$item->consommateur_gerer = isset($_POST["consommateur_gerer"]) ? $_POST["consommateur_gerer"] : "";
			$item->cabine_id = isset($_POST["cabine_id"]) ? $_POST["cabine_id"] : "";
			$item->index_consommation = isset($_POST["index_consommation"]) ? $_POST["index_consommation"] : "";
			$item->identificateur = isset($_POST["identificateur"]) ? $_POST["identificateur"] : "";
			$item->id_equipe_identification = isset($_POST["id_equipe_identification"]) ? $_POST["id_equipe_identification"] : "";
			$item->is_draft = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "1";
			$item->presence_inversor = isset($_POST["presence_inversor"]) ? $_POST["presence_inversor"] : "Non";


			//$item->code_identificateur =$utilisateur->code_utilisateur;  
			$item->n_user_create = strip_tags($utilisateur->code_utilisateur);
			$item->site_id = $utilisateur->site_id;
			//var_dump($item->site_id);
			//exit;
			$result_array = $item->CreateWeb();
			if ($result_array["error"] == 0) {
				//UPLOAD FAIL
				//$filename=$result_array["id"].'.jpeg';
				/*$filename=$item->id_.'.png';
							if(isset($_FILES['photo_pa_avant'])){
								if( move_uploaded_file($_FILES['photo_pa_avant']['tmp_name'],'pictures/'.$filename) ){
								 $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
								// echo $url;
								}			
							}*/
				$nbre = isset($_FILES['photo_pa_avant']) ? count($_FILES['photo_pa_avant']['name']) : 0;
				if ($nbre > 0) {
					$item->datesys = date("Y-m-d H:i:s");
					$item->n_user_create = $utilisateur->code_utilisateur;
					for ($i = 0; $i < $nbre; $i++) {
						$code_  = uniqUid("t_main_data_gallery", "ref_photo") . '_PA';
						$can_upload = $item->CreatePhoto($code_);
						if ($can_upload) {
							$filename = $code_ . '.png';
							$source_image = 'pictures_temp/' . $filename;
							$image_destination = 'pictures/' . $filename;

							Utils::F_Exist('pictures');
							Utils::F_Exist('pictures_temp');
							if (move_uploaded_file($_FILES['photo_pa_avant']['tmp_name'][$i], $source_image)) {

								// Utils::compressImage($source_image, $image_destination);
								Utils::compress2($source_image, $image_destination, 50);

								$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
							}
						}
					}
				}
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;

	case "prepare_identification":
		/*if($utilisateur->HasDroits("12_54"))
		{*/
		// if($_POST){	
		$item = new Identification($db);
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->CreateTemporaire($utilisateur);
		echo json_encode($result_array);
		// }
		/*	
		}else{
			DroitsNotGranted();
		}*/
		break;
	case "prepare_controle":
		/*if($utilisateur->HasDroits("12_54"))
		{*/
		// if($_POST){	

		$item = new Identification($db);
		$item->id_ = isset($_GET["k"]) ? $_GET["k"] : "";
		$detail_customer = $item->GetDetail();

		$item_ctl = new CLS_Controle($db);
		$item_ctl->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item_ctl->CreateTemporaire($utilisateur, $detail_customer);
		echo json_encode($result_array);
		// }
		/*	
		}else{
			DroitsNotGranted();
		}*/
		break;


	case "add_customer":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_GET) {

			$item = new Identification($db);

			$item->id_ = isset($_GET["k"]) ? $_GET["k"] : "";
			$detail = $item->GetDetail();

			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->CreateTemporaire($utilisateur);
			if ($result_array["error"] == 0) {
				// $result_array["detail"]=$detail;	 
				$detail["uid"] = $result_array["uid"];
			}
			$detail["error"] = $result_array["error"];
			Utils::responseJson($detail);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_customer":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		//var_dump($_POST);
		if ($_POST) {
			$item = new Identification($db);
			$item->id_ = isset($_POST["UID"]) ? $_POST["UID"] : "";
			if (empty(trim($item->id_))) {
				$result["error"] = 1;
				$result["message"] = "Référence fiche invalide";
				echo json_encode($result);
			} else {

				$item->p_a = isset($_POST["p_a"]) ? $_POST["p_a"] : "";
				$item->adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : "";

				//ADRESSE
				$item->quartier_id = isset($_POST["quartier"]) ? $_POST["quartier"] : "";
				$item->commune_id = isset($_POST["commune_id"]) ? $_POST["commune_id"] : "";
				$item->ville_id = isset($_POST["ville_id"]) ? $_POST["ville_id"] : "";
				//$item->province_id = isset($_POST["refus_quartier"])?$_POST["id_group"]:"";		 
				$item->numero = isset($_POST["numero_avenue"]) ? $_POST["numero_avenue"] : "";
				$item->avenue = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
				//ADRESSE

				$item->gps_longitude = isset($_POST["gps_longitude"]) ? $_POST["gps_longitude"] : "";
				$item->gps_latitude = isset($_POST["gps_latitude"]) ? $_POST["gps_latitude"] : "";
				$item->num_compteur_actuel = isset($_POST["num_compteur_actuel"]) ? $_POST["num_compteur_actuel"] : "";
				$item->commune_id = isset($_POST["commune_id"]) ? $_POST["commune_id"] : "";
				$item->cvs_id = isset($_POST["cvs_id"]) ? $_POST["cvs_id"] : "";
				$item->nom_proprietaire_facture_snel = isset($_POST["nom_responsable"]) ? $_POST["nom_responsable"] : "";
				$item->phone_proprietaire_facture_snel = isset($_POST["phone_responsable"]) ? $_POST["phone_responsable"] : "";
				$item->nom_remplacant = isset($_POST["nom_remplacant"]) ? $_POST["nom_remplacant"] : "";
				$item->phone_remplacant = isset($_POST["phone_remplacant"]) ? $_POST["phone_remplacant"] : "";
				$item->client_id = isset($_POST["client_id"]) ? $_POST["client_id"] : "";
				$item->occupant_id = isset($_POST["occupant_id"]) ? $_POST["occupant_id"] : "";
				$item->nbre_branchement = isset($_POST["nbre_branchement"]) ? $_POST["nbre_branchement"] : "";
				$item->section_cable = isset($_POST["section_cable"]) ? $_POST["section_cable"] : "";
				$item->lst_materiels = isset($_POST["lst_materiels"]) ? json_decode($_POST["lst_materiels"]) : "[]";

				$item->numero_piece_identity = isset($_POST["numero_piece_identity"]) ? $_POST["numero_piece_identity"] : "";
				//$item->quartier= isset($_POST["quartier"])?$_POST["quartier"]:"";
				//$item->numero_avenue= isset($_POST["numero_avenue"])?$_POST["numero_avenue"]:"";
				$item->accessibility_client = isset($_POST["accessibility_client"]) ? $_POST["accessibility_client"] : "";
				$item->tarif_identif = isset($_POST["tarif_identif"]) ? $_POST["tarif_identif"] : "";
				$item->infos_supplementaires = isset($_POST["infos_supplementaires"]) ? $_POST["infos_supplementaires"] : "";
				$item->nbre_menage_a_connecter = isset($_POST["nbre_menage_a_connecter"]) ? $_POST["nbre_menage_a_connecter"] : "";
				$item->noms_equipe_blue_energy = isset($_POST["noms_equipe_blue_energy"]) ? $_POST["noms_equipe_blue_energy"] : "";
				$item->numero_depart = isset($_POST["numero_depart"]) ? $_POST["numero_depart"] : "";
				$item->numero_poteau_identif = isset($_POST["numero_poteau_identif"]) ? $_POST["numero_poteau_identif"] : "";
				$item->type_raccordement_identif = isset($_POST["type_raccordement_identif"]) ? $_POST["type_raccordement_identif"] : "";
				$item->type_compteur = isset($_POST["type_compteur"]) ? $_POST["type_compteur"] : "";
				$item->type_construction = isset($_POST["type_construction"]) ? $_POST["type_construction"] : "";
				$item->nbre_appartement = isset($_POST["nbre_appartement"]) ? $_POST["nbre_appartement"] : "";
				$item->nbre_habitant = isset($_POST["nbre_habitant"]) ? $_POST["nbre_habitant"] : "";
				$item->type_activites = isset($_POST["type_activites"]) ? $_POST["type_activites"] : "";
				$item->conformites_installation = isset($_POST["conformites_installation"]) ? $_POST["conformites_installation"] : "";
				$item->avis_technique_blue = isset($_POST["avis_technique_blue"]) ? $_POST["avis_technique_blue"] : "";
				$item->avis_occupant = isset($_POST["avis_occupant"]) ? $_POST["avis_occupant"] : "";
				$item->chef_equipe = isset($_POST["chef_equipe"]) ? $_POST["chef_equipe"] : "";
				$item->reference_appartement = isset($_POST["reference_appartement"]) ? $_POST["reference_appartement"] : "";
				// $item->statut_client= isset($_POST["statut_client"])?$_POST["statut_client"]:"";
				$item->titre_responsable = isset($_POST["titre_responsable"]) ? $_POST["titre_responsable"] : "";
				$item->titre_remplacant = isset($_POST["titre_remplacant"]) ? $_POST["titre_remplacant"] : "";

				//$item->statut_occupant= isset($_POST["statut_occupant"])?$_POST["statut_occupant"]:"";
				//$item->nom_occupant_trouver= isset($_POST["nom_occupant_trouver"])?$_POST["nom_occupant_trouver"]:"";
				// $item->phone_occupant_trouver= isset($_POST["phone_occupant_trouver"])?$_POST["phone_occupant_trouver"]:"";
				$item->type_raccordement_propose = isset($_POST["type_raccordement_propose"]) ? $_POST["type_raccordement_propose"] : "";
				$item->nature_activity = isset($_POST["nature_activity"]) ? $_POST["nature_activity"] : "";
				$item->type_client = isset($_POST["type_client"]) ? $_POST["type_client"] : "";
				$item->consommateur_gerer = isset($_POST["consommateur_gerer"]) ? $_POST["consommateur_gerer"] : "";
				$item->cabine_id = isset($_POST["cabine_id"]) ? $_POST["cabine_id"] : "";
				$item->index_consommation = isset($_POST["index_consommation"]) ? $_POST["index_consommation"] : "";
				$item->identificateur = isset($_POST["identificateur"]) ? $_POST["identificateur"] : "";
				$item->id_equipe_identification = isset($_POST["id_equipe_identification"]) ? $_POST["id_equipe_identification"] : "";
				$item->is_draft = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "";
				$item->presence_inversor = isset($_POST["presence_inversor"]) ? $_POST["presence_inversor"] : "Non";

				$item->n_user_update = $utilisateur->code_utilisateur;
				$result_array = $item->Modifier();
				if ($result_array["error"] == 0) {
					//UPLOAD FAIL
					//$filename=$result_array["id"].'.jpeg';
					//var_dump($_FILES['photo_pa_avant']);
					$nbre = isset($_FILES['photo_pa_avant']) ? count($_FILES['photo_pa_avant']['name']) : 0;
					if ($nbre > 0) {
						$item->datesys = date("Y-m-d H:i:s");
						$item->n_user_create = $utilisateur->code_utilisateur;

						for ($i = 0; $i < $nbre; $i++) {
							$code_  = uniqUid("t_main_data_gallery", "ref_photo");
							$can_upload = $item->CreatePhoto($code_);
							if ($can_upload) {
								$filename = $code_ . '.png';
								$source_image = 'pictures_temp/' . $filename;
								$image_destination = 'pictures/' . $filename;

								Utils::F_Exist('pictures');
								Utils::F_Exist('pictures_temp');
								if (move_uploaded_file($_FILES['photo_pa_avant']['tmp_name'][$i], $source_image)) {
									// $compress_images = Utils::compressImage($source_image, $image_destination);
									Utils::compress2($source_image, $image_destination, 50);

									unlink($source_image);
									$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
								}
							}
						}
					}
				}
				echo json_encode($result_array);
			}
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;
	case "create_group_user":
		if ($utilisateur->HasDroits("10_240")) {
			if ($_POST) {
				$generer = new Generateur($db, FALSE);
				/* $generer= new Generateur($db,TRUE);
						 
						 $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
				$group_user = new GroupUtilisateur($db);
				//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
				$group_user->id_group = $generer->getUID('generateur_main', 'num_group_users', 'Y', 'ts_group_user', 'id_group');
				//   var_dump($group_user->id_group);
				//exit;
				$group_user->intitule = isset($_POST["intitule"]) ? $_POST["intitule"] : "";
				$group_user->id_service = isset($_POST["id_service"]) ? $_POST["id_service"] : "";
				$group_user->n_user_create = $utilisateur->nom_utilisateur;
				$result_array = $group_user->Create();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;

	case "create_site":
		if ($utilisateur->HasDroits("12_30")) {
			if ($_POST) {
				$site = new Site($db);
				$site->n_user_create = $utilisateur->nom_utilisateur;
				$site->code_site = uniqUid("t_site_perception", "code_site");
				$site->intitule_site = isset($_POST["d"]) ? $_POST["d"] : "";
				$site->adresse_site = isset($_POST["a"]) ? $_POST["a"] : "";
				$site->commune_id = isset($_POST["c"]) ? $_POST["c"] : "";
				$site->province_id = isset($_POST["p"]) ? $_POST["p"] : "";
				$reponse = $site->Create();
				echo json_encode($reponse);
			}
		} else {
			DroitsNotGranted();
		}
		break;


	case "create_article":
		if ($utilisateur->HasDroits("12_22")) {
			if ($_POST) {
				$article = new Article($db);
				$article->n_user_create = $utilisateur->nom_utilisateur;
				$article->ref_produit = uniqUid("t_produit", "ref_produit");
				$article->designation = isset($_POST["d"]) ? $_POST["d"] : "";
				$article->prix_vente = isset($_POST["a"]) ? $_POST["a"] : "";
				$article->monnaie_vente = isset($_POST["p"]) ? $_POST["p"] : "";
				$article->unite_de_mesure = isset($_POST["c"]) ? $_POST["c"] : "";
				$reponse = $article->Create();
				echo json_encode($reponse);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "edit_article":
		if ($utilisateur->HasDroits("12_23")) {
			if ($_POST) {
				$article = new Article($db);
				$article->n_user_update = $utilisateur->code_utilisateur;
				$article->ref_produit = isset($_POST["z_"]) ? $_POST["z_"] : "";
				$article->designation = isset($_POST["d"]) ? $_POST["d"] : "";
				$article->prix_vente = isset($_POST["a"]) ? $_POST["a"] : "";
				$article->monnaie_vente = isset($_POST["p"]) ? $_POST["p"] : "";
				$article->unite_de_mesure = isset($_POST["c"]) ? $_POST["c"] : "";
				$reponse = $article->Modifier();
				echo json_encode($reponse);
			}
		} else {
			DroitsNotGranted();
		}
		break;

	case "edit_site":
		if ($utilisateur->HasDroits("12_31")) {
			if ($_POST) {
				$site = new Site($db);
				$site->n_user_update = $utilisateur->nom_utilisateur;
				$site->code_site = isset($_POST["z_"]) ? $_POST["z_"] : "";
				$site->intitule_site = isset($_POST["d"]) ? $_POST["d"] : "";
				$site->adresse_site = isset($_POST["a"]) ? $_POST["a"] : "";
				$site->commune_id = isset($_POST["c"]) ? $_POST["c"] : "";
				$site->province_id = isset($_POST["p"]) ? $_POST["p"] : "";
				$reponse = $site->Modifier();
				echo json_encode($reponse);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "create_user":
		if ($utilisateur->HasDroits("10_190")) {
			if ($_POST) {
				$utilisateur->n_user_create = $utilisateur->code_utilisateur;
				$utilisateur->code_utilisateur = uniqUid("t_utilisateurs", "code_utilisateur");
				$utilisateur->nom_utilisateur = isset($_POST["k"]) ? $_POST["k"] : "";
				$utilisateur->nom_complet = isset($_POST["nk"]) ? $_POST["nk"] : "";
				$utilisateur->id_group = isset($_POST["gp"]) ? $_POST["gp"] : "";
				$utilisateur->activated = isset($_POST["etat"]) ? $_POST["etat"] : "";
				$utilisateur->site_id = isset($_POST["site"]) ? $_POST["site"] : "";
				$utilisateur->phone_user = isset($_POST["phone_user"]) ? $_POST["phone_user"] : "";
				$utilisateur->email_user = isset($_POST["email_user"]) ? $_POST["email_user"] : "";
				$utilisateur->chef_equipe_id = isset($_POST["chef_equipe_id"]) ? $_POST["chef_equipe_id"] : "";
				$utilisateur->id_organisme = isset($_POST["id_organisme"]) ? $_POST["id_organisme"] : "";
				$utilisateur->id_organisme_chief = isset($_POST["id_organisme_chief"]) ? $_POST["id_organisme_chief"] : "";
				$utilisateur->is_chief = isset($_POST["is_chief"]) ? $_POST["is_chief"] : "";
				$utilisateur->access_au_module_deux = isset($_POST["access_au_module_deux"]) ? $_POST["access_au_module_deux"] : "0";
				$reponse = $utilisateur->Create();
				echo json_encode($reponse);
			}
		} else {
			DroitsNotGranted();
		}
		break;

	case "edit_user":
		if ($utilisateur->HasDroits("10_180")) {
			if ($_POST) {
				$utilisateur->n_user_update = $utilisateur->code_utilisateur;
				$utilisateur->code_utilisateur = isset($_POST["z_"]) ? $_POST["z_"] : "";
				$utilisateur->nom_utilisateur = isset($_POST["k"]) ? $_POST["k"] : "";
				$utilisateur->nom_complet = isset($_POST["nk"]) ? $_POST["nk"] : "";
				$utilisateur->id_group = isset($_POST["gp"]) ? $_POST["gp"] : "";
				$utilisateur->activated = isset($_POST["etat"]) ? $_POST["etat"] : "";
				$utilisateur->site_id = isset($_POST["site"]) ? $_POST["site"] : "";
				$utilisateur->phone_user = isset($_POST["phone_user"]) ? $_POST["phone_user"] : "";
				$utilisateur->email_user = isset($_POST["email_user"]) ? $_POST["email_user"] : "";
				$utilisateur->chef_equipe_id = isset($_POST["chef_equipe_id"]) ? $_POST["chef_equipe_id"] : "";
				$utilisateur->id_organisme = isset($_POST["id_organisme"]) ? $_POST["id_organisme"] : "";
				$utilisateur->id_organisme_chief = isset($_POST["id_organisme_chief"]) ? $_POST["id_organisme_chief"] : "";
				$utilisateur->is_chief = isset($_POST["is_chief"]) ? $_POST["is_chief"] : "";
				$utilisateur->access_au_module_deux = isset($_POST["access_au_module_deux"]) ? $_POST["access_au_module_deux"] : "0";
				$reponse = $utilisateur->Modifier();
				echo json_encode($reponse);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "view_ticket_pic":
		/*if($utilisateur->HasDroits("10_230"))
			{*/
		if ($_GET) {
			$item = new PARAM_Notification($db);
			$ref_log = isset($_GET["k"]) ? $_GET["k"] : "";
			$result_array = $item->GetPhoto($ref_log);
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "detail_group_user":
		if ($utilisateur->HasDroits("10_230")) {
			if ($_GET) {
				$group = new GroupUtilisateur($db);
				$group->id_group = isset($_GET["id_group"]) ? $_GET["id_group"] : "";
				$result_array["error"] = 0;
				$result_array["data"] = $group->GetDetail();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;


	case "detail_site":
		if ($utilisateur->HasDroits("12_31")) {
			if ($_GET) {
				$site = new Site($db);
				$site->code_site = isset($_GET["k"]) ? $_GET["k"] : "";
				$result_array["error"] = 0;
				$result_array["data"] = $site->GetDetail();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "detail_article":
		if ($utilisateur->HasDroits("12_23")) {
			if ($_GET) {
				$article = new Article($db);
				$article->ref_produit = isset($_GET["k"]) ? $_GET["k"] : "";
				$result_array["error"] = 0;
				$result_array["data"] = $article->GetDetail();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;

	case "detail_customer":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_GET) {
			$item = new Identification($db);
			$item->id_ = isset($_GET["k"]) ? $_GET["k"] : "";
			$result_array = $item->GetDetail();
			Utils::responseJson($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "detail_install":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_GET) {
			$item = new Installation($db);
			$item->id_install = isset($_GET["k"]) ? $_GET["k"] : "";
			$result_array = $item->GetDetail($utilisateur->id_service_group);
			Utils::responseJson($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "create_install_approve":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_POST) {
			$item = new Installation($db);
			$item->id_install = isset($_POST["id_"]) ? $_POST["id_"] : "";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$item->commenteur_controle_blue = isset($_POST["comment_"]) ? $_POST["comment_"] : "";
			if ($item->Approuver()) {
				$result_array["error"] = 0;
				$result_array["message"] = "Approbation effectuée avec succès";
			} else {
				$result_array["error"] = 1;
				$result_array["message"] = "L'opération n'a pas pu être effectuée";
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;


	case "create_install_cloture":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_POST) {
			$item = new Installation($db);
			$item->id_install = isset($_POST["id_"]) ? $_POST["id_"] : "";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$item->datesys = date('Y-m-d H:i:s');
			$item->commenteur_controle_blue = isset($_POST["comment_"]) ? $_POST["comment_"] : "";
			$item->lst_installateurs_secondaire = isset($_POST["list_installateurs_secondaire"]) ? $_POST["list_installateurs_secondaire"] : "";
			if ($item->Cloturer()) {
				$result_array["error"] = 0;
				$result_array["message"] = "Clôture effectuée avec succès";
			} else {
				$result_array["error"] = 1;
				$result_array["message"] = "L'opération n'a pas pu être effectuée";
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "detail_user":
		if ($utilisateur->HasDroits("10_180")) {
			if ($_GET) {
				$utilisateur->code_utilisateur = isset($_GET["k"]) ? $_GET["k"] : "";
				$result_array["error"] = 0;
				$result_array["data"] = $utilisateur->GetDetail();
				echo json_encode($result_array);
			}
		} else {
			DroitsNotGranted();
		}
		break;
	case "logout":
		$utilisateur->logout();
		break;
	case "SyncDocBatch": //Mobile		
		$data = file_get_contents("php://input");
		$ticket = new Ticket($db);
		$ticket->SyncTicketBatch($data);
		break;

		/*	case "SyncLogAnnuation":		
		$data = file_get_contents("php://input");			
		$log = new Ticket($db);
		$log->SyncLog_Annuation($data);
		break;
	*/
	case "SyncLogReimpression":	//Mobile	
		$data = file_get_contents("php://input");
		$log = new Ticket($db);
		$log->SyncLog_Re_impression($data);
		break;
	case "UpdatePwd": //Mobile
		$data = file_get_contents("php://input");
		$ticket = new Ticket($db);
		$ticket->UpdatePwd($data);
		break;
	case "DeviceIdentification": //Mobile
		$data = file_get_contents("php://input");
		$ticket = new Ticket($db);
		$ticket->DeviceIdentification($data);
		break;
	case "ResetDevice": //Mobile
		$data = file_get_contents("php://input");
		$ticket = new Ticket($db);
		$ticket->ResetDevice($data);
		break;


	case "RefreshBasicParam": //Mobile
		$data = file_get_contents("php://input");
		$ticket = new Ticket($db);
		$ticket->RefreshBasicParam($data);
		break;
	case "AppUpdate": //Mobile
		$filepath = "appupdate/app-debug.apk";
		// Process download
		if (file_exists($filepath)) {
			header('Content-type: application/octet-stream');
			header("Content-Type: " . mime_content_type($filepath));
			header("Content-Disposition: attachment; filename=" . basename($filepath));
			while (ob_get_level()) {
				ob_end_clean();
			}
			readfile($filepath);
		}
		break;


	case "update_pwd": //Web
		if ($_POST) {

			$user = $utilisateur->code_utilisateur;
			$cp = isset($_POST["cp"]) ? $_POST["cp"] : "";
			$np = isset($_POST["np"]) ? $_POST["np"] : "";
			$rp = isset($_POST["rp"]) ? $_POST["rp"] : "";

			$result_array = $utilisateur->UpdatePwd($user, $cp, $np);
		}
		break;
		/*   START AVISTECHNIQUE   */
	case "create_avistechnique":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Avistechnique($db);
			$item->code = uniqUid("t_param_avis_technique", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->date_synchro = isset($_POST["date_synchro"]) ? $_POST["date_synchro"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_avistechnique":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Avistechnique($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->date_synchro = isset($_POST["date_synchro"]) ? $_POST["date_synchro"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_avistechnique":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_GET) {
			$item = new Avistechnique($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "delete_avistechnique":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Avistechnique($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
			   DroitsNotGranted();
		 } */
		break;
		/*   END AVISTECHNIQUE   */


		/*   START PROVINCE   */
	case "create_province":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = uniqUid("t_param_adresse_entity", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->category_id = "3";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_province":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_province":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_GET) {
			$item = new AdresseEntity($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			// $item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "delete_province":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
			   DroitsNotGranted();
		 } */
		break;
		/*   END PROVINCE   */

		/*   START SITEPRODUCTION   */
	case "create_siteproduction":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Site($db);
			//$item->code_site = uniqUid("t_param_site_production", "code_site");
			$generer = new Generateur($db, FALSE);
			/* $generer= new Generateur($db,TRUE);
						 
						 $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
			$item->code_site = $generer->getUID('generateur_sys_base_entity', 'num_site', 'N', 't_param_site_production', 'code_site');

			$item->intitule_site = isset($_POST["intitule_site"]) ? $_POST["intitule_site"] : "";
			$item->adresse_site = isset($_POST["adresse_site"]) ? $_POST["adresse_site"] : "";
			$item->contact_site = isset($_POST["contact_site"]) ? $_POST["contact_site"] : "";
			$item->province_id = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_siteproduction":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Site($db);
			$item->code_site = isset($_POST["code_site"]) ? $_POST["code_site"] : "";
			$item->intitule_site = isset($_POST["intitule_site"]) ? $_POST["intitule_site"] : "";
			$item->adresse_site = isset($_POST["adresse_site"]) ? $_POST["adresse_site"] : "";
			$item->contact_site = isset($_POST["contact_site"]) ? $_POST["contact_site"] : "";
			$item->province_id = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_siteproduction":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_GET) {
			$item = new Site($db);
			$item->code_site = isset($_GET["code_site"]) ? $_GET["code_site"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "delete_siteproduction":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Site($db);
			$item->code_site = isset($_POST["code_site"]) ? $_POST["code_site"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
			   DroitsNotGranted();
		 } */
		break;
		/*   END SITEPRODUCTION   */
		/*   START ORGANISME   */
	case "create_organisme":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Organisme($db);
			$item->ref_organisme = uniqUid("t_param_organisme", "ref_organisme");
			$item->denomination = isset($_POST["denomination"]) ? $_POST["denomination"] : "";
			$item->adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
			$item->contact_organisme = isset($_POST["contact_organisme"]) ? $_POST["contact_organisme"] : "";
			$item->ref_org_principal = isset($_POST["ref_org_principal"]) ? $_POST["ref_org_principal"] : "";
			$item->phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->is_blue_energy = isset($_POST["is_blue_energy"]) ? $_POST["is_blue_energy"] : "";
			$item->id_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_ville = isset($_POST["id_ville"]) ? $_POST["id_ville"] : "";
			$item->id_commune = isset($_POST["id_commune"]) ? $_POST["id_commune"] : "";
			$item->id_quartier = isset($_POST["id_quartier"]) ? $_POST["id_quartier"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_organisme":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Organisme($db);
			$item->ref_organisme = isset($_POST["ref_organisme"]) ? $_POST["ref_organisme"] : "";
			$item->denomination = isset($_POST["denomination"]) ? $_POST["denomination"] : "";
			$item->adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
			$item->contact_organisme = isset($_POST["contact_organisme"]) ? $_POST["contact_organisme"] : "";
			$item->ref_org_principal = isset($_POST["ref_org_principal"]) ? $_POST["ref_org_principal"] : "";
			$item->phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
			$item->annule_ = isset($_POST["annule_"]) ? $_POST["annule_"] : "";
			$item->type_ = isset($_POST["type_"]) ? $_POST["type_"] : "";
			$item->signature_id = isset($_POST["signature_id"]) ? $_POST["signature_id"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$item->is_blue_energy = isset($_POST["is_blue_energy"]) ? $_POST["is_blue_energy"] : "";
			$item->id_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_commune = isset($_POST["id_commune"]) ? $_POST["id_commune"] : "";
			$item->id_ville = isset($_POST["id_ville"]) ? $_POST["id_ville"] : "";
			$item->id_quartier = isset($_POST["id_quartier"]) ? $_POST["id_quartier"] : "";
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_organisme":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_GET) {
			$item = new Organisme($db);
			$item->ref_organisme = isset($_GET["ref_organisme"]) ? $_GET["ref_organisme"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "delete_organisme":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new Organisme($db);
			$item->ref_organisme = isset($_POST["ref_organisme"]) ? $_POST["ref_organisme"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
			   DroitsNotGranted();
		 } */
		break;
		/*   END ORGANISME   */

		/*   START CVS   */
	case "create_cvs":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_POST) {
			$item = new CVS($db);
			$item->code = uniqUid("t_param_cvs", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->id_organisme = isset($_POST["id_organisme"]) ? $_POST["id_organisme"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->id_commune = isset($_POST["id_commune"]) ? $_POST["id_commune"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
				  DroitsNotGranted();
			} */
		break;
	case "edit_cvs":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_POST) {
			$item = new CVS($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			//$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->id_organisme = isset($_POST["id_organisme"]) ? $_POST["id_organisme"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->date_synchro = isset($_POST["date_synchro"]) ? $_POST["date_synchro"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->id_commune = isset($_POST["id_commune"]) ? $_POST["id_commune"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
				  DroitsNotGranted();
			} */
		break;
	case "detail_cvs":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_GET) {
			$item = new CVS($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			//$result_array["error"] = 0;
			$result_array = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
				  DroitsNotGranted();
			} */
		break;
	case "delete_cvs":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_POST) {
			$item = new CVS($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
				   DroitsNotGranted();
			 } */
		break;



	case "get_cvs_compteur_install":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$id_cvs = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$search_param = isset($_GET["search_param"]) ? $_GET["search_param"] : "";
			$item = new Identification($db);
			//$result_array["error"]=0;
			$result_array = $item->GetCvsCompteurForInstallSearch($id_cvs, $utilisateur, $search_param);
			//$compteur_array=$stmt->fetchAll(PDO::FETCH_ASSOC);				
			//$result_array["data"]=$compteur_array;
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;

	case "get_cvs_compteur_replace":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$id_cvs = isset($_GET["id_"]) ? trim($_GET["id_"]) : "";
			$search_param = isset($_GET["search_param"]) ? $_GET["search_param"] : "";
			$item = new Identification($db);
			//$result_array["error"]=0;
			//$stmt= 
			// $compteur_array=$stmt->fetchAll(PDO::FETCH_ASSOC);				
			$result_array = $item->GetCvsCompteurForReplaceSearch($id_cvs, $search_param, $utilisateur); //$compteur_array;
			echo json_encode($result_array);
		}
		/*}else{
DroitsNotGranted();
} */
		break;

	case "get_cvs_compteur_controle":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		/*
var_dump($_GET);
exit;*/
		if ($_GET) {
			$id_cvs = isset($_GET["id_"]) ? $_GET["id_"] : "";
			$filtre = isset($_GET["filtre"]) ? $_GET["filtre"] : "";
			$jour = isset($_GET["jour"]) ? $_GET["jour"] : "";
			$search_term = isset($_GET["search_param"]) ? $_GET["search_param"] : "";
			$item = new Identification($db);
			//$result_array["error"]=0;
			//$stmt= 
			// $csv_id, $search_term,$filtre,$jour
			// $compteur_array=$stmt->fetchAll(PDO::FETCH_ASSOC);				
			$result_array = $item->GetCvsCompteurForControlSearch($id_cvs, $search_term, $filtre, $jour, $utilisateur); //$compteur_array;
			echo json_encode($result_array);
		}
		/*}else{
DroitsNotGranted();
} */
		break;

		/*   END CVS   */

		/*  START CONTROL ASSIGN    */

	case "get_control_assign":

		if ($_GET) {
			$cacher = new Cacher();
			$cacher->setPrefix("get-control-assign");

			$data = $cacher->get([$utilisateur->site_id], function () use (
				$db,
				$utilisateur,
			) {
				$item = new PARAM_Assign($db);
				$item->type_assignation = '1';
				$result_array = $item->GetOrganeControlAssigned($utilisateur);
				return json_encode($result_array);
			});

			echo $data;
		}

		break;



	case "delete_assign_control":
		/*if($utilisateur->HasDroits("12_24"))
		
		{	*/
		$utilisateur = new Utilisateur($db);
		$Abonne = new PARAM_Assign($db);
		$Abonne->type_assignation = '1';
		$ids_ =  [];

		if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['query']) and !empty($_POST['query'])) {
			$query_data = json_decode($_POST['query']);

			$method = $query_data->method;
			$params = $query_data->params;

			if ($method == 'readAll') {
				$code_utilisateur = $query_data->params[2]->code_utilisateur;
				$utilisateur->code_utilisateur = $code_utilisateur;
				$utilisateur->readOne();

				$stmt = $Abonne->readAll(
					$params[0],
					$params[1],
					$utilisateur,
					$params[3]
				);
			} else if ($method == 'searchWithoutDate') {
				$code_utilisateur = $query_data->params[3]->code_utilisateur;
				$utilisateur->code_utilisateur = $code_utilisateur;
				$utilisateur->readOne();

				$stmt = $Abonne->searchWithoutDate(
					$params[0],
					$params[1],
					$params[2],
					$utilisateur,
					$params[4]
				);
			} else if ($method == 'search') {
				$code_utilisateur = $query_data->params[5]->code_utilisateur;
				$utilisateur->code_utilisateur = $code_utilisateur;
				$utilisateur->readOne();

				$stmt = $Abonne->search(
					$params[0],
					$params[1],
					$params[2],
					$params[3],
					$params[4],
					$utilisateur,
					$params[6],
				);
			}
			$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($items as $item) {
				$ids_[] = $item['id_assign'];
			}
		} else if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['k'])) {
			$k_value =  $_POST["k"];
			$ids_ = json_decode($k_value);
		}

		try {
			foreach ($ids_ as $id_) {
				$item = new PARAM_Assign($db);
				$item->type_assignation = '1';
				$item->id_ = $id_;

				if ($item->Supprimer()) {
					$result_array["message_$id_"] = "Annulation effectuée avec succès";
				} else {
					$result_array["message_$id_"] = "L'opération n'a pas pu être effectuée";
				}
			}
			$result_array["error"] = 0;
		} catch (Exception $e) {
			$result_array["error"] = 1;
			$result_array["message"] = "L'opération n'a pas pu être effectuée";
		}

		$result_array["message"] = "Annulation effectuée avec succès";
		echo json_encode($result_array);
		break;

		/*  END CONTROL ASSIGN   */

		/*  START CONTROL ASSIGN INSTALL    */

	case "get_install_assign":
		/*if($utilisateur->HasDroits("12_34"))
	{ */
		if ($_GET) {
			$item = new PARAM_Assign($db);
			$item->type_assignation = "2";
			//$result_array["error"]=0;
			$site_array = $item->GetOrganeInstReplaceAssigned($utilisateur);
			echo json_encode($site_array);
		}
		/*}else{
		  DroitsNotGranted();
	} */
		break;



	case "delete_assign_install":
		/*if($utilisateur->HasDroits("12_24"))
			{	*/
		if ($_POST) {

			$item = new PARAM_Assign($db);
			$item->type_assignation = "2";
			$item->id_ = isset($_POST["k"]) ? $_POST["k"] : "";
			if ($item->Supprimer()) {
				$result_array["error"] = 0;
				$result_array["message"] = "Annulation effectuée avec succès";
			} else {
				$result_array["error"] = 1;
				$result_array["message"] = "L'opération n'a pas pu être effectuée";
			}
			echo json_encode($result_array);
		}
		/*}else{
				DroitsNotGranted();
			}*/
		break;

	case "create_control_assign":
		/*if($utilisateur->HasDroits("12_34"))
				{ */
		if ($_POST) {
			if (empty($_POST['tbl-checkbox'])) {
				$result["error"] = 1;
				$result["message"] = "Veuillez sélectionner les compteurs à contrôler";
				$result["data"] = null;
				echo json_encode($result);
			} else {
				$item = new PARAM_Assign($db);
				$item->type_assignation = '1';	//Control					 
				$item->id_organe = isset($_POST["id_equipe_identification"]) ? $_POST["id_equipe_identification"] : "";
				$item->chef_equipe_control = isset($_POST["chef_equipe_control"]) ? $_POST["chef_equipe_control"] : "";
				$item->id_controleur_quality = isset($_POST["controleur_quality"]) ? $_POST["controleur_quality"] : "";
				$item->n_user_create = $utilisateur->code_utilisateur;
				$result_array = $item->CreateAssignControl($_POST['tbl-checkbox']);
				echo json_encode($result_array);
			}
		}
		/*}else{
					  DroitsNotGranted();
				} */
		break;

	case "create_install_assign":
		/*if($utilisateur->HasDroits("12_34"))
				{ */
		if ($_POST) {
			if (empty($_POST['tbl-checkbox'])) {
				$result["error"] = 1;
				$result["message"] = "Veuillez sélectionner les clients à installer";
				$result["data"] = null;
				echo json_encode($result);
			} else {
				$item = new PARAM_Assign($db);
				$item->type_assignation = "2";	//INSTALL
				$item->id_organe = isset($_POST["id_equipe_identification"]) ? $_POST["id_equipe_identification"] : "";
				$item->chef_equipe_control = isset($_POST["chef_equipe_install"]) ? $_POST["chef_equipe_install"] : "";
				$item->id_controleur_quality = isset($_POST["controleur_quality"]) ? $_POST["controleur_quality"] : "";
				$item->n_user_create = $utilisateur->code_utilisateur;
				$result_array = $item->CreateAssignControl($_POST['tbl-checkbox']);
				echo json_encode($result_array);
			}
		}
		/*}else{
					  DroitsNotGranted();
				} */
		break;

	case "create_replace_assign":
		/*if($utilisateur->HasDroits("12_34"))
				{ */
		if ($_POST) {
			if (empty($_POST['tbl-checkbox'])) {
				$result["error"] = 1;
				$result["message"] = "Veuillez sélectionner les compteurs à remplacer";
				$result["data"] = null;
				echo json_encode($result);
			} else {
				$item = new PARAM_Assign($db);
				$item->type_assignation = '3'; // Remplacement
				$item->id_organe = isset($_POST["id_equipe_identification"]) ? $_POST["id_equipe_identification"] : "";
				$item->chef_equipe_control = isset($_POST["chef_equipe_install"]) ? $_POST["chef_equipe_install"] : "";
				$item->id_controleur_quality = isset($_POST["controleur_quality"]) ? $_POST["controleur_quality"] : "";
				$item->n_user_create = $utilisateur->code_utilisateur;
				$result_array = $item->CreateAssignControl($_POST['tbl-checkbox']);
				echo json_encode($result_array);
			}
		}
		/*}else{
					  DroitsNotGranted();
				} */
		break;

		/*  END CONTROL ASSIGN INSTALL  */
		/*  START DISPATCHING  */
	case "dispatch_install_set_user":
		/*if($utilisateur->HasDroits("12_34"))
				{ */
		if ($_POST) {
			if (empty($_POST['tbl-checkbox'])) {
				$result["error"] = 1;
				$result["message"] = "Veuillez sélectionner les adresses à assigner";
				$result["data"] = null;
				echo json_encode($result);
			} else {
				$item = new PARAM_Assign($db);
				$technicien = isset($_POST["technicien"]) ? $_POST["technicien"] : "";
				$item->n_user_create = $utilisateur->code_utilisateur;
				$result_array = $item->DispatchingAssignInstall($_POST['tbl-checkbox'], $technicien);
				echo json_encode($result_array);
			}
		}
		/*}else{
					  DroitsNotGranted();
				} */
		break;

	case "dispatch_control_set_user":
		/*if($utilisateur->HasDroits("12_34"))
				{ */
		if ($_POST) {
			if (empty($_POST['tbl-checkbox'])) {
				$result["error"] = 1;
				$result["message"] = "Veuillez sélectionner les adresses à assigner";
				$result["data"] = null;
				echo json_encode($result);
			} else {
				$item = new PARAM_Assign($db);
				$technicien = isset($_POST["technicien"]) ? $_POST["technicien"] : "";
				$item->n_user_create = $utilisateur->code_utilisateur;
				$result_array = $item->DispatchingAssignInstall($_POST['tbl-checkbox'], $technicien);
				echo json_encode($result_array);
			}
		}
		/*}else{
					  DroitsNotGranted();
				} */
		break;

		/*  END DISPATCHING */


		/*   START COMMUNE   */
	case "create_commune":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = uniqUid("t_param_adresse_entity", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->parent_id = isset($_POST["id_province"]) ? $_POST["id_province"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->category_id = "8";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_commune":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			//$item->category_id = "8";
			$item->n_user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_commune":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_GET) {
			$item = new AdresseEntity($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
				  DroitsNotGranted();
			} */
		break;
	case "delete_commune":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			//$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
				   DroitsNotGranted();
			 } */
		break;
		/*   END COMMUNE   */
		/*   START QUARTIER   */
	case "create_quartier":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = uniqUid("t_param_adresse_entity", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->parent_id = isset($_POST["parent_id"]) ? $_POST["parent_id"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->category_id = "10";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_quartier":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->parent_id = isset($_POST["parent_id"]) ? $_POST["parent_id"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			//$item->category_id = "8";
			$item->n_user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_quartier":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_GET) {
			$item = new AdresseEntity($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
				  DroitsNotGranted();
			} */
		break;
	case "delete_quartier":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			//$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
				   DroitsNotGranted();
			 } */
		break;
		/*   END QUARTIER   */


		/*   START VILLE   */
	case "create_ville":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = uniqUid("t_param_adresse_entity", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->parent_id = isset($_POST["parent_id"]) ? $_POST["parent_id"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->category_id = "4";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "edit_ville":
		/*if($utilisateur->HasDroits("12_34"))
		{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->parent_id = isset($_POST["parent_id"]) ? $_POST["parent_id"] : "";
			//$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			//$item->category_id = "8";
			$item->n_user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
			  DroitsNotGranted();
		} */
		break;
	case "detail_ville":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_GET) {
			$item = new AdresseEntity($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
				  DroitsNotGranted();
			} */
		break;
	case "delete_ville":
		/*if($utilisateur->HasDroits("12_34"))
			{ */
		if ($_POST) {
			$item = new AdresseEntity($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			//$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
				   DroitsNotGranted();
			 } */
		break;
		/*   END VILLE   */


		/*   START TARIF   */
	case "create_tarif":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {

			if (empty(trim($_POST["code"]))) {
				$result["error"] = 1;
				$result["message"] = "Veuillez saisir le code";
				echo json_encode($result);
			} else {
				$item = new Tarif($db);
				$item->code = isset($_POST["code"]) ? $_POST["code"] : ""; //uniqUid("t_param_tarif", "code");
				$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
				$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
				$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
				$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
				$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
				$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
				$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
				$item->user_create = $utilisateur->code_utilisateur;
				$result_array = $item->Create();
				echo json_encode($result_array);
			}
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "edit_tarif":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Tarif($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->date_synchro = isset($_POST["date_synchro"]) ? $_POST["date_synchro"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "detail_tarif":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$item = new Tarif($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "delete_tarif":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Tarif($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
       DroitsNotGranted();
 } */
		break;
		/*   END TARIF   */

		/*   START UNITE_DE_MESURE   */
	case "create_unite_de_mesure":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Unite_de_Mesure($db);
			$item->code_unite = uniqUid("t_param_unite_de_mesure", "code_unite");
			$item->libelle_unite = isset($_POST["libelle_unite"]) ? $_POST["libelle_unite"] : "";
			$item->symbole_unite = isset($_POST["symbole_unite"]) ? $_POST["symbole_unite"] : "";
			$item->stateUpdate = isset($_POST["stateUpdate"]) ? $_POST["stateUpdate"] : "";
			$item->date_sync = isset($_POST["date_sync"]) ? $_POST["date_sync"] : "";
			$item->dateUpdate = isset($_POST["dateUpdate"]) ? $_POST["dateUpdate"] : "";
			$item->id_boutique = isset($_POST["id_boutique"]) ? $_POST["id_boutique"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "edit_unite_de_mesure":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Unite_de_Mesure($db);
			$item->code_unite = isset($_POST["code_unite"]) ? $_POST["code_unite"] : "";
			$item->libelle_unite = isset($_POST["libelle_unite"]) ? $_POST["libelle_unite"] : "";
			$item->symbole_unite = isset($_POST["symbole_unite"]) ? $_POST["symbole_unite"] : "";
			$item->stateUpdate = isset($_POST["stateUpdate"]) ? $_POST["stateUpdate"] : "";
			$item->date_sync = isset($_POST["date_sync"]) ? $_POST["date_sync"] : "";
			$item->dateUpdate = isset($_POST["dateUpdate"]) ? $_POST["dateUpdate"] : "";
			$item->id_boutique = isset($_POST["id_boutique"]) ? $_POST["id_boutique"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "detail_unite_de_mesure":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$item = new Unite_de_Mesure($db);
			$item->code_unite = isset($_GET["code_unite"]) ? $_GET["code_unite"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "delete_unite_de_mesure":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Unite_de_Mesure($db);
			$item->code_unite = isset($_POST["code_unite"]) ? $_POST["code_unite"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
       DroitsNotGranted();
 } */
		break;
		/*   END UNITE_DE_MESURE   */

		/*   START CONTROL   */
	case "create_control":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		if ($_POST) {

			if (isset($_FILES['photo_compteur']) == FALSE) {
				$result_array["error"] = true;
				$result_array["message"] = "Veuillez prendre la photo du Compteur";
				echo json_encode($result_array);
				exit;
			}

			$item = new CLS_Controle($db);

			$item->ref_fiche_controle = isset($_POST["id_control"]) ? $_POST["id_control"] : "";
			//$item->id_ =uniqUid("t_log_controle", "id_"); ;    
			$item->ref_fiche_identification = isset($_POST["ref_identific"]) ? $_POST["ref_identific"] : "";
			$item->observation = isset($_POST["observation"]) ? $_POST["observation"] : "";
			$item->presence_inverseur = isset($_POST["presence_inverseur"]) ? $_POST["presence_inverseur"] : "";
			$item->numero_serie_cpteur = isset($_POST["numero_serie_cpteur"]) ? $_POST["numero_serie_cpteur"] : "";
			$item->marque_compteur = isset($_POST["marque_compteur"]) ? $_POST["marque_compteur"] : "";
			$item->type_cpteur = isset($_POST["type_cpteur"]) ? $_POST["type_cpteur"] : "";
			$item->clavier_deporter = isset($_POST["clavier_deporter"]) ? $_POST["clavier_deporter"] : "";


			$item->scelle_cpt_existant = isset($_POST["scelle_cpt_existant"]) ? $_POST["scelle_cpt_existant"] : "";
			if (strlen($item->scelle_cpt_existant) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Compteur Existant doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}

			$item->scelle_coffret_existant = isset($_POST["scelle_coffret_existant"]) ? $_POST["scelle_coffret_existant"] : "";

			if (strlen($item->scelle_coffret_existant) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Coffret Existant doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}

			$item->scelle_compteur_poser = isset($_POST["scelle_compteur_poser"]) ? trim($_POST["scelle_compteur_poser"]) : "";

			if (strlen($item->scelle_compteur_poser) > 0 &&  strlen($item->scelle_compteur_poser) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Compteur Posé doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}



			$item->scelle_coffret_poser = isset($_POST["scelle_coffret_poser"]) ? trim($_POST["scelle_coffret_poser"]) : "";

			if (strlen($item->scelle_coffret_poser) > 0 &&  strlen($item->scelle_coffret_poser) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Coffret Posé doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}

			//VERIFICATION SCELLE 1 & 2 IDENTIQUES
			if (strlen($item->scelle_compteur_poser) > 0 &&  strlen($item->scelle_coffret_poser) > 0) {
				if (trim($item->scelle_compteur_poser) == trim($item->scelle_coffret_poser)) {
					$result_array["error"] = true;
					$result_array["message"] = "Le N° Scellé Compteur Posé doit être différent du scellé coffret posé ";
					echo json_encode($result_array);
					exit;
				}
			}
			if (strlen($item->scelle_coffret_existant) > 0 &&  strlen($item->scelle_cpt_existant) > 0) {
				if (trim($item->scelle_coffret_existant) == trim($item->scelle_cpt_existant)) {
					$result_array["error"] = true;
					$result_array["message"] = "Le N° Scellé Compteur doit être différent du scellé coffret  ";
					echo json_encode($result_array);
					exit;
				}
			}
			//FIN VERIFICATION SCELLE 1 & 2 IDENTIQUES
			$item->type_raccordement = isset($_POST["type_raccordement"]) ? $_POST["type_raccordement"] : "";
			$item->nbre_arrived = isset($_POST["nbre_arrived"]) ? $_POST["nbre_arrived"] : "";
			$item->section_cable_arrived = isset($_POST["section_cable_arrived"]) ? $_POST["section_cable_arrived"] : "";
			$item->par_wifi_cpl = isset($_POST["par_wifi_cpl"]) ? $_POST["par_wifi_cpl"] : "";
			$item->num_photo_cpteur = isset($_POST["num_photo_cpteur"]) ? $_POST["num_photo_cpteur"] : "";
			$item->num_photo_raccord = isset($_POST["num_photo_raccord"]) ? $_POST["num_photo_raccord"] : "";
			$item->possibility_fraud_expliquer = isset($_POST["possibility_fraud_expliquer"]) ? $_POST["possibility_fraud_expliquer"] : "";
			$item->gps_latitude_control = isset($_POST["gps_latitude_control"]) ? $_POST["gps_latitude_control"] : "";
			$item->gps_longitude_control = isset($_POST["gps_longitude_control"]) ? $_POST["gps_longitude_control"] : "";
			$item->etat_interrupteur = isset($_POST["etat_interrupteur"]) ? $_POST["etat_interrupteur"] : "";
			$item->credit_restant = isset($_POST["credit_restant"]) ? $_POST["credit_restant"] : "";
			$item->indicateur_led = isset($_POST["indicateur_led"]) ? $_POST["indicateur_led"] : "";
			$item->cas_de_fraude = isset($_POST["cas_de_fraude"]) ? $_POST["cas_de_fraude"] : "";
			$item->client_reconnait_pas = isset($_POST["client_reconnait_pas"]) ? $_POST["client_reconnait_pas"] : "";
			$item->type_fraude = isset($_POST["type_fraude"]) ? $_POST["type_fraude"] : "";
			$item->autocollant_place_controleur = isset($_POST["autocollant_place_controleur"]) ? $_POST["autocollant_place_controleur"] : "";
			$item->autocollant_trouver = isset($_POST["autocollant_trouver"]) ? $_POST["autocollant_trouver"] : "";

			$item->diagnostics_general = isset($_POST["diagnostics_general"]) ? $_POST["diagnostics_general"] : "";
			$item->avis_client = isset($_POST["avis_client"]) ? $_POST["avis_client"] : "";
			$item->refus_access = isset($_POST["accessibility_client"]) ? $_POST["accessibility_client"] : "";
			$item->refus_client_de_signer = isset($_POST["refus_client_de_signer"]) ? $_POST["refus_client_de_signer"] : "";
			$item->id_organisme_control = isset($_POST["id_organisme_control"]) ? $_POST["id_organisme_control"] : "";
			$item->chef_equipe_control = isset($_POST["chef_equipe_control"]) ? $_POST["chef_equipe_control"] : "";
			$item->controleur = isset($_POST["controleur"]) ? $_POST["controleur"] : "";
			$item->id_assign = isset($_POST["id_assign"]) ? $_POST["id_assign"] : "";
			$item->typ_conclusion = isset($_POST["typ_conclusion"]) ? $_POST["typ_conclusion"] : "";


			$item->consommation_journaliere = isset($_POST["consommation_journaliere"]) ? $_POST["consommation_journaliere"] : "";
			$item->consommation_de_30jours_actuels = isset($_POST["consommation_de_30jours_actuels"]) ? $_POST["consommation_de_30jours_actuels"] : "";
			$item->consommation_de_30jours_precedents = isset($_POST["consommation_de_30jours_precedents"]) ? $_POST["consommation_de_30jours_precedents"] : "";
			$item->valeur_du_dernier_ticket = isset($_POST["valeur_du_dernier_ticket"]) ? $_POST["valeur_du_dernier_ticket"] : "";
			$item->index_de_tarif_du_compteur = isset($_POST["index_de_tarif_du_compteur"]) ? $_POST["index_de_tarif_du_compteur"] : "";
			$item->date_de_dernier_ticket_rentre = isset($_POST["date_de_dernier_ticket_rentre"]) ? Utils::ClientToDbDateFormat($_POST["date_de_dernier_ticket_rentre"]) : "";
			$item->is_draft_control = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "";
			$item->sceller_identique = isset($_POST["sceller_identique"]) ? 1 : 0;
			$item->dernier_sceller_compteur = isset($_POST["dernier_sceller_compteur"]) ? $_POST["dernier_sceller_compteur"] : "";
			$item->dernier_sceller_coffret = isset($_POST["dernier_sceller_coffret"]) ? $_POST["dernier_sceller_coffret"] : "";

			if ($item->refus_client_de_signer == '') {
				if (isset($_FILES['photo_signature_client']) == FALSE) {
					$v = $utilisateur->GetSettingValue('24');
					if ($v == '1') {
						$result_array["error"] = true;
						$result_array["message"] = "Veuillez prendre la photo signature du client";
						echo json_encode($result_array);
						exit;
					}
				}
			}

			//$item->code_identificateur =$utilisateur->code_utilisateur;  
			$item->n_user_create = strip_tags($utilisateur->code_utilisateur);
			$item->ref_site_controle = $utilisateur->site_id;


			//RECUPERATION LISTE DES FRAUDES
			$lst_s = isset($_POST["frd_checkbox"]) ? $_POST["frd_checkbox"] : null;
			$item->lst_fraudes = $lst_s;


			//RECUPERATION LISTE DES OBSERVATIONS
			$lst_obs = isset($_POST["obser_checkbox"]) ? $_POST["obser_checkbox"] : null;
			$item->lst_observations = $lst_obs;


			//var_dump($item->site_id);
			//exit;
			$result_array = $item->CreateWebOne();
			if ($result_array["error"] == 0) {
				//UPLOAD FAIL
				//$filename=$result_array["id"].'.jpeg'; 
				processCaptureControl($_FILES, $item->ref_fiche_controle, $item->photo_compteur);
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;


	case "edit_control":
		/*	if($utilisateur->HasDroits("10_240"))
				{*/
		if ($_POST) {

			$item = new CLS_Controle($db);
			$item->ref_fiche_controle = isset($_POST["id_control"]) ? $_POST["id_control"] : "";
			$item->ref_fiche_identification = isset($_POST["ref_identific"]) ? $_POST["ref_identific"] : "";
			$item->observation = isset($_POST["observation"]) ? $_POST["observation"] : "";
			$item->presence_inverseur = isset($_POST["presence_inverseur"]) ? $_POST["presence_inverseur"] : "";
			$item->numero_serie_cpteur = isset($_POST["numero_serie_cpteur"]) ? $_POST["numero_serie_cpteur"] : "";
			$item->marque_compteur = isset($_POST["marque_compteur"]) ? $_POST["marque_compteur"] : "";
			$item->type_cpteur = isset($_POST["type_cpteur"]) ? $_POST["type_cpteur"] : "";
			$item->clavier_deporter = isset($_POST["clavier_deporter"]) ? $_POST["clavier_deporter"] : "";

			/*
						$item->scelle_cpt_existant= isset($_POST["scelle_cpt_existant"])?$_POST["scelle_cpt_existant"]:""; 
						$item->scelle_coffret_existant= isset($_POST["scelle_coffret_existant"])?$_POST["scelle_coffret_existant"]:"";
						$item->scelle_compteur_poser= isset($_POST["scelle_compteur_poser"])?$_POST["scelle_compteur_poser"]:"";
						$item->scelle_coffret_poser= isset($_POST["scelle_coffret_poser"])?$_POST["scelle_coffret_poser"]:"";
						*/


			$item->scelle_cpt_existant = isset($_POST["scelle_cpt_existant"]) ? $_POST["scelle_cpt_existant"] : "";
			if (strlen($item->scelle_cpt_existant) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Compteur Existant doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}

			$item->scelle_coffret_existant = isset($_POST["scelle_coffret_existant"]) ? $_POST["scelle_coffret_existant"] : "";

			if (strlen($item->scelle_coffret_existant) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Coffret Existant doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}

			$item->scelle_compteur_poser = isset($_POST["scelle_compteur_poser"]) ? trim($_POST["scelle_compteur_poser"]) : "";

			if (strlen($item->scelle_compteur_poser) > 0 && strlen($item->scelle_compteur_poser) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Compteur Posé doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}

			$item->scelle_coffret_poser = isset($_POST["scelle_coffret_poser"]) ? trim($_POST["scelle_coffret_poser"]) : "";
			if (strlen($item->scelle_coffret_poser) > 0 && strlen($item->scelle_coffret_poser) != 7) {
				$result_array["error"] = true;
				$result_array["message"] = "Le N° Scellé Coffret Posé doit contenir 7 chiffres ";
				echo json_encode($result_array);
				exit;
			}




			//VERIFICATION SCELLE 1 & 2 IDENTIQUES
			if (strlen($item->scelle_compteur_poser) > 0 &&  strlen($item->scelle_coffret_poser) > 0) {
				if (trim($item->scelle_compteur_poser) == trim($item->scelle_coffret_poser)) {
					$result_array["error"] = true;
					$result_array["message"] = "Le N° Scellé Compteur Posé doit être différent du scellé coffret posé ";
					echo json_encode($result_array);
					exit;
				}
			}
			if (strlen($item->scelle_coffret_existant) > 0 &&  strlen($item->scelle_cpt_existant) > 0) {
				if (trim($item->scelle_coffret_existant) == trim($item->scelle_cpt_existant)) {
					$result_array["error"] = true;
					$result_array["message"] = "Le N° Scellé Compteur doit être différent du scellé coffret  ";
					echo json_encode($result_array);
					exit;
				}
			}
			//FIN VERIFICATION SCELLE 1 & 2 IDENTIQUES

			$item->type_raccordement = isset($_POST["type_raccordement"]) ? $_POST["type_raccordement"] : "";
			$item->nbre_arrived = isset($_POST["nbre_arrived"]) ? $_POST["nbre_arrived"] : "";
			$item->section_cable_arrived = isset($_POST["section_cable_arrived"]) ? $_POST["section_cable_arrived"] : "";
			$item->par_wifi_cpl = isset($_POST["par_wifi_cpl"]) ? $_POST["par_wifi_cpl"] : "";
			$item->num_photo_cpteur = isset($_POST["num_photo_cpteur"]) ? $_POST["num_photo_cpteur"] : "";
			$item->num_photo_raccord = isset($_POST["num_photo_raccord"]) ? $_POST["num_photo_raccord"] : "";
			$item->possibility_fraud_expliquer = isset($_POST["possibility_fraud_expliquer"]) ? $_POST["possibility_fraud_expliquer"] : "";
			$item->gps_latitude_control = isset($_POST["gps_latitude_control"]) ? $_POST["gps_latitude_control"] : "";
			$item->gps_longitude_control = isset($_POST["gps_longitude_control"]) ? $_POST["gps_longitude_control"] : "";
			$item->etat_interrupteur = isset($_POST["etat_interrupteur"]) ? $_POST["etat_interrupteur"] : "";
			$item->credit_restant = isset($_POST["credit_restant"]) ? $_POST["credit_restant"] : "";
			$item->indicateur_led = isset($_POST["indicateur_led"]) ? $_POST["indicateur_led"] : "";
			$item->cas_de_fraude = isset($_POST["cas_de_fraude"]) ? $_POST["cas_de_fraude"] : "";
			$item->client_reconnait_pas = isset($_POST["client_reconnait_pas"]) ? $_POST["client_reconnait_pas"] : "";
			$item->type_fraude = isset($_POST["type_fraude"]) ? $_POST["type_fraude"] : "";
			$item->autocollant_place_controleur = isset($_POST["autocollant_place_controleur"]) ? $_POST["autocollant_place_controleur"] : "";
			$item->autocollant_trouver = isset($_POST["autocollant_trouver"]) ? $_POST["autocollant_trouver"] : "";

			$item->diagnostics_general = isset($_POST["diagnostics_general"]) ? $_POST["diagnostics_general"] : "";
			$item->avis_client = isset($_POST["avis_client"]) ? $_POST["avis_client"] : "";
			//$item->refus_access= isset($_POST["refus_access"])?$_POST["refus_access"]:"";
			$item->refus_client_de_signer = isset($_POST["refus_client_de_signer"]) ? $_POST["refus_client_de_signer"] : "";
			$item->id_organisme_control = isset($_POST["id_organisme_control"]) ? $_POST["id_organisme_control"] : "";
			$item->chef_equipe_control = isset($_POST["chef_equipe_control"]) ? $_POST["chef_equipe_control"] : "";
			$item->controleur = isset($_POST["controleur"]) ? $_POST["controleur"] : "";


			$item->id_assign = isset($_POST["id_assign"]) ? $_POST["id_assign"] : "";
			$item->typ_conclusion = isset($_POST["typ_conclusion"]) ? $_POST["typ_conclusion"] : "";


			$item->consommation_journaliere = isset($_POST["consommation_journaliere"]) ? $_POST["consommation_journaliere"] : "";
			$item->consommation_de_30jours_actuels = isset($_POST["consommation_de_30jours_actuels"]) ? $_POST["consommation_de_30jours_actuels"] : "";
			$item->consommation_de_30jours_precedents = isset($_POST["consommation_de_30jours_precedents"]) ? $_POST["consommation_de_30jours_precedents"] : "";
			$item->valeur_du_dernier_ticket = isset($_POST["valeur_du_dernier_ticket"]) ? $_POST["valeur_du_dernier_ticket"] : "";
			$item->date_de_dernier_ticket_rentre = isset($_POST["date_de_dernier_ticket_rentre"]) ? Utils::ClientToDbDateFormat($_POST["date_de_dernier_ticket_rentre"]) : "";
			$item->index_de_tarif_du_compteur = isset($_POST["index_de_tarif_du_compteur"]) ? $_POST["index_de_tarif_du_compteur"] : "";
			$item->is_draft_control = isset($_POST["doc_save_mode"]) ? $_POST["doc_save_mode"] : "";

			//$item->code_identificateur =$utilisateur->code_utilisateur;  
			$item->n_user_update = strip_tags($utilisateur->code_utilisateur);
			//$item->ref_site_controle = $utilisateur->site_id;  
			//var_dump($item->site_id);
			//exit;

			//RECUPERATION LISTE DES FRAUDES
			$lst_s = isset($_POST["frd_checkbox"]) ? $_POST["frd_checkbox"] : null;
			$item->lst_fraudes = $lst_s;

			//RECUPERATION LISTE DES OBSERVATIONS
			$lst_obs = isset($_POST["obser_checkbox"]) ? $_POST["obser_checkbox"] : null;
			$item->lst_observations = $lst_obs;

			$item->sceller_identique = isset($_POST["sceller_identique"]) ? 1 : 0;


			$result_array = $item->Modifier();
			if ($result_array["error"] == 0) {
				//UPLOAD FAIL
				//$filename=$result_array["id"].'.jpeg';
				/*$filename=$item->photo_compteur;
							if(isset($_FILES['photo_compteur'])){
								if( move_uploaded_file($_FILES['photo_compteur']['tmp_name'],'pictures/'.$filename) ){
								// $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
								// echo $url;
								}			
							}*/

				//$filename=$result_array["id"].'.jpeg'; 
				processCaptureControl($_FILES, $item->ref_fiche_controle, $item->photo_compteur);
			}
			echo json_encode($result_array);
		}
		/*}else{
					DroitsNotGranted();
				}*/
		break;

	case "detail_control":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		if ($_GET) {
			$item = new CLS_Controle($db);
			$item->ref_fiche_controle = isset($_GET["k"]) ? $_GET["k"] : "";
			$result_array = $item->GetDetail($utilisateur->id_service_group);
			$result_array['readOnly'] = 0;
			Utils::responseJson($result_array);
		}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;



		/*   END CONTROL   */
		/*   START TYPE CLIENT   */


	case "create_type_client":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);
		$generer = new Generateur($db, TRUE);
		/* $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
		//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
		$item = new TypeClient($db);
		$item->code = $generer->getUID('generateur_main', 'num_type_client', 'N', 't_param_type_client', 'code');
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_type_client":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new TypeClient($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_type_client":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new TypeClient($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Supprimer();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
		/*   END TYPE CLIENT   */
		/*   START TYPE FRAUDE   */


	case "create_type_fraude":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);
		$generer = new Generateur($db, TRUE);
		/* $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
		//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
		$item = new PARAM_TypeFraude($db);
		$item->code = $generer->getUID('generateur_main', 'num_type_fraude', 'N', 't_param_type_fraude', 'code');
		$item->libelle = isset($_POST["libelle"]) ? trim($_POST["libelle"]) : "";
		$item->code_label = isset($_POST["code_label"]) ? trim($_POST["code_label"]) : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_type_fraude":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new PARAM_TypeFraude($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->libelle = isset($_POST["libelle"]) ? trim($_POST["libelle"]) : "";
		$item->code_label = isset($_POST["code_label"]) ? trim($_POST["code_label"]) : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_type_fraude":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new PARAM_TypeFraude($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Supprimer();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
		/*   END TYPE FRAUDE   */


		/*   START SECTION CABLE   */


	case "create_section_cable":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);
		$generer = new Generateur($db, TRUE);
		/* $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
		//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
		$item = new PARAM_Section_Cable($db);
		$item->code = $generer->getUID('generateur_main', 'num_type_fraude', 'N', 't_param_section_cable', 'code');
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_section_cable":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new PARAM_Section_Cable($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_section_cable":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new PARAM_Section_Cable($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Supprimer();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
		/*   END SECTION CABLE   */


		/*   START MATERIEL   */
	case "create_materiel":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);

		$item = new Materiels($db);
		$item->designation = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->unite_de_mesure = isset($_POST["code_unite"]) ? $_POST["code_unite"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_materiel":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new Materiels($db);
		$item->ref_produit = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->designation = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->unite_de_mesure = isset($_POST["code_unite"]) ? $_POST["code_unite"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_materiel":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new Materiels($db);
		$item->ref_produit = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Supprimer();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
		/*   END MATERIEL   */

		/*   START TYPE USAGE   */

	case "create_type_usage":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);
		$generer = new Generateur($db, TRUE);
		/* $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
		//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
		$item = new Param_TypeUsage($db);
		$item->code = $generer->getUID('generateur_main', 'num_type_usage', 'N', 't_param_type_usage', 'code');
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);
		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_type_usage":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new Param_TypeUsage($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);
		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_type_usage":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new Param_TypeUsage($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Supprimer();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);
		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
		/*   END TYPE USAGE   */


		/*   START TYPE DEFAUT   */

	case "create_type_defaut":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);
		$generer = new Generateur($db, TRUE);
		/* $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
		//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
		$item = new Param_TypeDefaut($db);
		$item->code = $generer->getUID('generateur_main', 'num_type_defaut', 'N', 't_param_type_defauts', 'code');
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);
		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_type_defaut":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new Param_TypeDefaut($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);
		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_type_defaut":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new Param_TypeDefaut($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Supprimer();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);
		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
		/*   END TYPE DEFAUT   */

		/*   START Compteurs   */
	case "create_compteurs":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Compteurs($db);
			$item->ref_produit_series = uniqUid("t_param_liste_compteurs", "ref_produit_series");
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->code_user_create = isset($_POST["code_user_create"]) ? $_POST["code_user_create"] : "";
			$item->serial_number = isset($_POST["serial_number"]) ? $_POST["serial_number"] : "";
			$item->sts_serial_number = isset($_POST["sts_serial_number"]) ? $_POST["sts_serial_number"] : "";
			$item->order_number = isset($_POST["order_number"]) ? $_POST["order_number"] : "";
			$item->manufacturer_ref = isset($_POST["manufacturer_ref"]) ? $_POST["manufacturer_ref"] : "";
			$item->site_id_affectation = isset($_POST["site_id_affectation"]) ? $_POST["site_id_affectation"] : "";
			$item->ref_sous_traitant = isset($_POST["ref_sous_traitant"]) ? $_POST["ref_sous_traitant"] : "";
			$item->nom_sous_traitant = isset($_POST["nom_sous_traitant"]) ? $_POST["nom_sous_traitant"] : "";
			$item->deja_affected = isset($_POST["deja_affected"]) ? $_POST["deja_affected"] : "";
			$item->date_affectation_first_afectation = isset($_POST["date_affectation_first_afectation"]) ? $_POST["date_affectation_first_afectation"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->statut_compteur = isset($_POST["statut_compteur"]) ? $_POST["statut_compteur"] : "";
			$item->date_desaffectation = isset($_POST["date_desaffectation"]) ? $_POST["date_desaffectation"] : "";
			$item->motif_desaffectation = isset($_POST["motif_desaffectation"]) ? $_POST["motif_desaffectation"] : "";
			$item->annee_fabrication = isset($_POST["annee_fabrication"]) ? $_POST["annee_fabrication"] : "";
			$item->date_actuelle_affectation = isset($_POST["date_actuelle_affectation"]) ? $_POST["date_actuelle_affectation"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "edit_compteurs":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Compteurs($db);
			$item->ref_produit_series = isset($_POST["ref_produit_series"]) ? $_POST["ref_produit_series"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			$item->code_user_create = isset($_POST["code_user_create"]) ? $_POST["code_user_create"] : "";
			$item->serial_number = isset($_POST["serial_number"]) ? $_POST["serial_number"] : "";
			$item->sts_serial_number = isset($_POST["sts_serial_number"]) ? $_POST["sts_serial_number"] : "";
			$item->order_number = isset($_POST["order_number"]) ? $_POST["order_number"] : "";
			$item->manufacturer_ref = isset($_POST["manufacturer_ref"]) ? $_POST["manufacturer_ref"] : "";
			$item->site_id_affectation = isset($_POST["site_id_affectation"]) ? $_POST["site_id_affectation"] : "";
			$item->ref_sous_traitant = isset($_POST["ref_sous_traitant"]) ? $_POST["ref_sous_traitant"] : "";
			$item->nom_sous_traitant = isset($_POST["nom_sous_traitant"]) ? $_POST["nom_sous_traitant"] : "";
			$item->deja_affected = isset($_POST["deja_affected"]) ? $_POST["deja_affected"] : "";
			$item->date_affectation_first_afectation = isset($_POST["date_affectation_first_afectation"]) ? $_POST["date_affectation_first_afectation"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->statut_compteur = isset($_POST["statut_compteur"]) ? $_POST["statut_compteur"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->date_desaffectation = isset($_POST["date_desaffectation"]) ? $_POST["date_desaffectation"] : "";
			$item->motif_desaffectation = isset($_POST["motif_desaffectation"]) ? $_POST["motif_desaffectation"] : "";
			$item->annee_fabrication = isset($_POST["annee_fabrication"]) ? $_POST["annee_fabrication"] : "";
			$item->date_actuelle_affectation = isset($_POST["date_actuelle_affectation"]) ? $_POST["date_actuelle_affectation"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "detail_compteurs":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$item = new Compteurs($db);
			$item->ref_produit_series = isset($_GET["ref_produit_series"]) ? $_GET["ref_produit_series"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "delete_compteurs":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Compteurs($db);
			$item->ref_produit_series = isset($_POST["ref_produit_series"]) ? $_POST["ref_produit_series"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
       DroitsNotGranted();
 } */
		break;

	case "verify_compteur_and_send_ticket_demand":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Compteurs($db);
			$numero_serie = isset($_POST["serial_number_verify"]) ? $_POST["serial_number_verify"] : "";
			$verify_fiche_identif = isset($_POST["verify_fiche_identif"]) ? $_POST["verify_fiche_identif"] : "";
			$control_types = isset($_POST["control_type"]) ? $_POST["control_type"] : null;

			$control_type = implode(",", $control_types);
			$result_array = $item->VerifyCompteurInfo($numero_serie, $utilisateur, true, $control_type);

			echo json_encode($result_array);
		}
		break;
	case "verify_compteur":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new Compteurs($db);
			$numero_serie = isset($_POST["serial_number_verify"]) ? $_POST["serial_number_verify"] : "";
			$verify_fiche_identif = isset($_POST["verify_fiche_identif"]) ? $_POST["verify_fiche_identif"] : "";
			$result_array = $item->VerifyCompteurInfo($numero_serie, $utilisateur);
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
		/*   END Compteurs   */
		/*   START CLS_PA   */
	case "create_cls_pa":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new CLS_PA($db);
			$item->code = uniqUid("t_param_pa", "code");
			$item->pa_num = isset($_POST["pa_num"]) ? $_POST["pa_num"] : "";
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->cvs_id = isset($_POST["cvs_id"]) ? $_POST["cvs_id"] : "";
			$item->statut_accessibility = isset($_POST["statut_accessibility"]) ? $_POST["statut_accessibility"] : "";
			$item->ref_last_visit_log_id = isset($_POST["ref_last_visit_log_id"]) ? $_POST["ref_last_visit_log_id"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "edit_cls_pa":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new CLS_PA($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->pa_num = isset($_POST["pa_num"]) ? $_POST["pa_num"] : "";
			$item->adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->date_synchro = isset($_POST["date_synchro"]) ? $_POST["date_synchro"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->cvs_id = isset($_POST["cvs_id"]) ? $_POST["cvs_id"] : "";
			$item->statut_accessibility = isset($_POST["statut_accessibility"]) ? $_POST["statut_accessibility"] : "";
			$item->ref_last_visit_log_id = isset($_POST["ref_last_visit_log_id"]) ? $_POST["ref_last_visit_log_id"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "detail_cls_pa":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$item = new CLS_PA($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "delete_cls_pa":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new CLS_PA($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
       DroitsNotGranted();
 } */
		break;
		/*   END CLS_PA   */

		/*   START PARAM_Notification   */
	case "create_param_notification":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new PARAM_Notification($db);
			$item->ref_log = uniqUid("t_param_notification_log", "ref_log");
			$item->ref_identif = isset($_POST["ref_identif"]) ? $_POST["ref_identif"] : "";
			$item->num_compteur = isset($_POST["num_compteur"]) ? $_POST["num_compteur"] : "";
			$item->commentaire = isset($_POST["commentaire"]) ? $_POST["commentaire"] : "";
			$item->statuts_notification = isset($_POST["statuts_notification"]) ? $_POST["statuts_notification"] : "";
			$item->n_user_vu = isset($_POST["n_user_vu"]) ? $_POST["n_user_vu"] : "";
			$item->n_user_create = isset($_POST["n_user_create"]) ? $_POST["n_user_create"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->id_commune = isset($_POST["id_commune"]) ? $_POST["id_commune"] : "";
			$item->date_vu = isset($_POST["date_vu"]) ? $_POST["date_vu"] : "";
			$item->type_notification = isset($_POST["type_notification"]) ? $_POST["type_notification"] : "";
			$item->numero_ticket = isset($_POST["numero_ticket"]) ? $_POST["numero_ticket"] : "";
			$item->date_creation_ticket = isset($_POST["date_creation_ticket"]) ? $_POST["date_creation_ticket"] : "";
			$item->n_user_create_ticket = isset($_POST["n_user_create_ticket"]) ? $_POST["n_user_create_ticket"] : "";
			$item->tarif = isset($_POST["tarif"]) ? $_POST["tarif"] : "";
			$item->ref_transaction = isset($_POST["ref_transaction"]) ? $_POST["ref_transaction"] : "";
			$item->user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "edit_param_notification":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new PARAM_Notification($db);
			$item->ref_log = isset($_POST["ref_log"]) ? $_POST["ref_log"] : "";
			$item->ref_identif = isset($_POST["ref_identif"]) ? $_POST["ref_identif"] : "";
			$item->num_compteur = isset($_POST["num_compteur"]) ? $_POST["num_compteur"] : "";
			$item->commentaire = isset($_POST["commentaire"]) ? $_POST["commentaire"] : "";
			$item->statuts_notification = isset($_POST["statuts_notification"]) ? $_POST["statuts_notification"] : "";
			$item->n_user_vu = isset($_POST["n_user_vu"]) ? $_POST["n_user_vu"] : "";
			$item->annule = isset($_POST["annule"]) ? $_POST["annule"] : "";
			$item->n_user_annule = isset($_POST["n_user_annule"]) ? $_POST["n_user_annule"] : "";
			$item->motif_annulation = isset($_POST["motif_annulation"]) ? $_POST["motif_annulation"] : "";
			$item->date_synchro = isset($_POST["date_synchro"]) ? $_POST["date_synchro"] : "";
			$item->is_sync = isset($_POST["is_sync"]) ? $_POST["is_sync"] : "";
			$item->code_province = isset($_POST["code_province"]) ? $_POST["code_province"] : "";
			$item->id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : "";
			$item->date_annule = isset($_POST["date_annule"]) ? $_POST["date_annule"] : "";
			$item->activated = isset($_POST["activated"]) ? $_POST["activated"] : "";
			$item->id_commune = isset($_POST["id_commune"]) ? $_POST["id_commune"] : "";
			$item->date_vu = isset($_POST["date_vu"]) ? $_POST["date_vu"] : "";
			$item->type_notification = isset($_POST["type_notification"]) ? $_POST["type_notification"] : "";
			$item->numero_ticket = isset($_POST["numero_ticket"]) ? $_POST["numero_ticket"] : "";
			$item->date_creation_ticket = isset($_POST["date_creation_ticket"]) ? $_POST["date_creation_ticket"] : "";
			$item->n_user_create_ticket = isset($_POST["n_user_create_ticket"]) ? $_POST["n_user_create_ticket"] : "";
			$item->tarif = isset($_POST["tarif"]) ? $_POST["tarif"] : "";
			$item->ref_transaction = isset($_POST["ref_transaction"]) ? $_POST["ref_transaction"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "detail_param_notification":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$item = new PARAM_Notification($db);
			$item->ref_log = isset($_GET["ref_log"]) ? $_GET["ref_log"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "delete_param_notification":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new PARAM_Notification($db);
			$item->ref_log = isset($_POST["ref_log"]) ? $_POST["ref_log"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
       DroitsNotGranted();
 } */
		break;
		/*   END PARAM_Notification   */
		/*   START IMPORT   */


	case "import_csv_compteur":
		/* 	if($utilisateur->HasDroits("12_36"))
              { */
		$item = new Compteurs($db);
		$item->n_user_create = $utilisateur->code_utilisateur;
		$site_import = isset($_POST["site"]) ? $_POST["site"] : "";
		$marque_compteur = isset($_POST["marque_compteur"]) ? $_POST["marque_compteur"] : "";
		$result_array = $item->import($site_import, $_FILES, $utilisateur, $marque_compteur);
		echo json_encode($result_array);
		/* }else{
              DroitsNotGranted();
              } */
		break;

		/*   END IMPORT   */
		/*   START IMPORT   */


	case "import_adresse_entity":
		/* 	if($utilisateur->HasDroits("12_36"))
              { */
		$item = new AdresseEntity($db);
		$item->n_user_create = $utilisateur->code_utilisateur;
		$item->parent_id  = isset($_POST["_id"]) ? $_POST["_id"] : "";
		$item->category_id =  isset($_POST["category_id"]) ? $_POST["category_id"] : "";
		$result_array = $item->import($_FILES, $utilisateur);
		echo json_encode($result_array);
		/* }else{
              DroitsNotGranted();
              } */
		break;

		/*   END IMPORT   */
		/*   START MARQUE_COMPTEUR   */
	case "create_marque_compteur":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new MarqueCompteur($db);
			$item->code = uniqUid("t_param_marque_compteur", "code");
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->n_user_create = $utilisateur->code_utilisateur;
			$result_array = $item->Create();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "edit_marque_compteur":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new MarqueCompteur($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
			$item->n_user_update = $utilisateur->code_utilisateur;
			$result_array = $item->Modifier();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "detail_marque_compteur":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_GET) {
			$item = new MarqueCompteur($db);
			$item->code = isset($_GET["code"]) ? $_GET["code"] : "";
			$item->user_update = $utilisateur->code_utilisateur;
			$result_array["error"] = 0;
			$result_array["data"] = $item->GetDetail();
			echo json_encode($result_array);
		}
		/*}else{
      DroitsNotGranted();
} */
		break;
	case "delete_marque_compteur":
		/*if($utilisateur->HasDroits("12_34"))
{ */
		if ($_POST) {
			$item = new MarqueCompteur($db);
			$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
			$item->n_user_update = $utilisateur->code_utilisateur;
			$result_array  = $item->Supprimer();
			echo json_encode($result_array);
		}
		/*}else{
       DroitsNotGranted();
 } */
		break;
		/*   END MARQUE_COMPTEUR   */

	default:
		$result_array["error"] = 1;
		$result_array["message"] = "Requête non prise en charge";
		echo json_encode($result_array);
		break;
}
// }
$db = null;

//public function uniqUid($len = 13) {  
function uniqUid($table, $key_fld)
{
	//uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
	$bytes = md5(mt_rand());
	//Phase 2 verification existance avant retour code
	if (VerifierExistance($key_fld, $bytes, $table)) {
		$bytes = uniqUid($table, $key_fld);
	}
	return $bytes;
	//return substr(bin2hex($bytes),0,$len);
}

function VerifierExistance($pKey, $NoGenerated, $table)
{
	global $db;
	$retour = false;
	$sql = "select $pKey from $table where $pKey=:NoGenerated";
	$stmt = $db->prepare($sql);
	//$stmt->bindParam(':$pKey', $genNB, PDO::PARAM_STR);
	//$stmt->bindValue(":pKey", $pKey);			
	$stmt->bindValue(":NoGenerated", $NoGenerated);
	//$stmt->bindValue(":table", $table);
	$stmt->execute();
	if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$retour = true;
	} else {
		$retour = false;
	}
	return $retour;
}


function DroitsNotGranted()
{
	$result_array["error"] = 1;
	$result_array["message"] = "Droit non attribué";
	echo json_encode($result_array);
}

function DroitsNotGrantedDialogSwal()
{
	$result_array["error"] = 1;
	$result_array["message"] = "Droit non attribué";
	echo json_encode($result_array);
}

function ClientToDbDateFormat($c_date)
{
	//$dt="17/07/2012";
	$n_date = str_ireplace('/', '-', $c_date);
	$f_dt = date('Y-m-d', strtotime($n_date));
	return $f_dt;
}
function processRequiredCaptureReplace($FILES)
{
	if (isset($_FILES['photo_compteur']) == FALSE) {
		$v = $utilisateur->GetSettingValue('13');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo du compteur";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_avant_install']) == FALSE) {
		$v = $utilisateur->GetSettingValue('14');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo avant ";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_apres_install']) == FALSE) {
		$v = $utilisateur->GetSettingValue('15');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo après";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_sceller_un']) == FALSE) {
		$v = $utilisateur->GetSettingValue('16');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo scellé 1";
			echo json_encode($result_array);
			exit;
		}
	}


	if (isset($_FILES['photo_sceller_deux']) == FALSE) {
		$v = $utilisateur->GetSettingValue('17');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo scellé 2";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_compteur_defectueux']) == FALSE) {
		$v = $utilisateur->GetSettingValue('18');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo du Compteur défectueux";
			echo json_encode($result_array);
			exit;
		}
	}
}
function processRequiredCaptureInstall($FILES)
{

	if (isset($_FILES['photo_compteur']) == FALSE) {
		$v = $utilisateur->GetSettingValue('8');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo du compteur";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_avant_install']) == FALSE) {
		$v = $utilisateur->GetSettingValue('9');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo avant installation";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_apres_install']) == FALSE) {
		$v = $utilisateur->GetSettingValue('10');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo après installation";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_sceller_un']) == FALSE) {
		$v = $utilisateur->GetSettingValue('11');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo scellé 1";
			echo json_encode($result_array);
			exit;
		}
	}


	if (isset($_FILES['photo_sceller_deux']) == FALSE) {
		$v = $utilisateur->GetSettingValue('12');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo scellé 2";
			echo json_encode($result_array);
			exit;
		}
	}
}


function processRequiredCaptureControl($FILES)
{
	if (isset($_FILES['photo_compteur']) == FALSE) {
		$v = $utilisateur->GetSettingValue('19');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo du compteur";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_avant_control']) == FALSE) {
		$v = $utilisateur->GetSettingValue('20');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo avant contrôle";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_apres_control']) == FALSE) {
		$v = $utilisateur->GetSettingValue('21');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo après contrôle";
			echo json_encode($result_array);
			exit;
		}
	}

	if (isset($_FILES['photo_sceller_un']) == FALSE) {
		$v = $utilisateur->GetSettingValue('22');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo scellé 1";
			echo json_encode($result_array);
			exit;
		}
	}


	if (isset($_FILES['photo_sceller_deux']) == FALSE) {
		$v = $utilisateur->GetSettingValue('23');
		if ($v == '1') {
			$result_array["error"] = true;
			$result_array["message"] = "Veuillez prendre la photo scellé 2";
			echo json_encode($result_array);
			exit;
		}
	}
}
function processCaptureInstall($FILES, $id_install)
{
	$filename = $id_install . '_INST_CTR.png';


	$source_image = 'pictures_temp/' . $filename;
	$image_destination = 'pictures/' . $filename;

	Utils::F_Exist('pictures');
	Utils::F_Exist('pictures_temp');

	if (isset($_FILES['photo_compteur'])) {
		if (move_uploaded_file($_FILES['photo_compteur']['tmp_name'], $source_image)) {

			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}
	$filename = $id_install . '_INST_POST.png';
	if (isset($_FILES['photo_compteur_post_paie'])) {

		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;


		if (move_uploaded_file($_FILES['photo_compteur_post_paie']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);

			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}


	$filename = $id_install . '_INST_BFR.png';
	if (isset($_FILES['photo_avant_install'])) {
		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;


		if (move_uploaded_file($_FILES['photo_avant_install']['tmp_name'], $source_image)) {

			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $id_install . '_INST_AFT.png';
	if (isset($_FILES['photo_apres_install'])) {

		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;

		if (move_uploaded_file($_FILES['photo_apres_install']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			// $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $id_install . '_INST_SC1.png';
	if (isset($_FILES['photo_sceller_un'])) {


		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;

		if (move_uploaded_file($_FILES['photo_sceller_un']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);

			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $id_install . '_INST_SC2.png';
	if (isset($_FILES['photo_sceller_deux'])) {

		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;


		if (move_uploaded_file($_FILES['photo_sceller_deux']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}



	if (isset($_FILES['photo_compteur_defectueux'])) {

		$filename = $id_install . '_INST_DFT.png';
		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($_FILES['photo_compteur_defectueux']['tmp_name'], $source_image)) {

			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}
}

function processCaptureControl($FILES, $ref_fiche_controle, $photo_compteur)
{


	Utils::F_Exist('pictures');
	Utils::F_Exist('pictures_temp');
	$filename = $ref_fiche_controle . '_CTL_CTR.png';
	if (isset($FILES['photo_compteur'])) {

		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($FILES['photo_compteur']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);

			// $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
			// echo $url;
		}
	}


	$filename = $ref_fiche_controle . '_CTL_BFR.png';
	if (isset($FILES['photo_avant_control'])) {

		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($FILES['photo_avant_control']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $ref_fiche_controle . '_CTL_AFT.png';
	if (isset($FILES['photo_apres_control'])) {

		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($FILES['photo_apres_control']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $ref_fiche_controle . '_CTL_SC1.png';
	if (isset($FILES['photo_sceller_un'])) {
		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($FILES['photo_sceller_un']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			// $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $ref_fiche_controle . '_CTL_SC2.png';
	if (isset($FILES['photo_sceller_deux'])) {
		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($FILES['photo_sceller_deux']['tmp_name'], $source_image)) {
			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}

	$filename = $ref_fiche_controle . '_CTL_SGN.png';
	if (isset($FILES['photo_signature_client'])) {
		$source_image = 'pictures_temp/' . $filename;
		$image_destination = 'pictures/' . $filename;
		if (move_uploaded_file($FILES['photo_signature_client']['tmp_name'], $source_image)) {

			Utils::compress2($source_image, $image_destination, 50);
			unlink($source_image);
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/pictures/' . $filename;
		}
	}
}
function GetServerDateOrTime($p = "D")
{
	$retour = "";
	if ($p == "Y") {
		$retour = date('Y');
	} else if ($p == "DT") {
		$retour = date('Y-m-d H:i:s');
	} else if ($p == "T") {
		$retour = date('H:i:s');
	} else if ($p == "D") {
		$retour = date('Y-m-d');
	}
	return $retour; //date('Y');//date('Y-m-d H:i:s');
}
	
	
	/*
	if(isset($_FILES['file']['name'])){

   / * Getting file name * /
   $filename = $_FILES['file']['name'];

   / * Location * /
   $location = "upload/".$filename;
   $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
   $imageFileType = strtolower($imageFileType);

   /* Valid extensions * /
   $valid_extensions = array("jpg","jpeg","png");

   $response = 0;
   / * Check file extension * /
   if(in_array(strtolower($imageFileType), $valid_extensions)) {
       
      if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
         $response = $location;
      }
   }

   echo $response;
   exit;
}
$('#upload').on('click', function() {
    var file_data = $('#sortpicture').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    alert(form_data);                             
    $.ajax({
        url: 'upload.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            alert(php_script_response); // display response from the PHP script, if any
        }
     });
});
	*/
