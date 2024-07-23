<?php
require_once './vendor/autoload.php';
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once "core.php";

$view = "";
$result_array = array();
$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
$accessibiliy = new Param_Accessibility($db);
$cvs_ = new CVS($db);
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
	case "search_view_log_visit":

		$search_item_value = "";
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

		$item = new LogVisitIdentification($db);
		// $item = new PARAM_VisitLog($db);

		$filtre = '';
		if (isset($_GET['filtre']) && strlen($_GET['filtre']) > 0) {
			//$est_installer = array();
			$e_commune = array();
			$param_cvs = array();
			$param_log_visite_ = array();
			$motif_visites = array();
			$arr_sites = [];
			$filtres = explode(',', $_GET['filtre']);


			foreach ($filtres as $k_ => $v_) {
				$filter_item = explode('=', $v_);
				if ($filter_item[0] == 't_param_log_visite_pa.statut_accessibilite') {
					$param_log_visite_[] = $v_;
				} else if ($filter_item[0] == 'e_commune.code') {
					$e_commune[] = $v_;
				} else if ($filter_item[0] == 't_param_cvs.code') {
					$param_cvs[] = $v_;
				} else if ($filter_item[0] == 't_param_log_visite_pa.type_motif_visite') {
					$motif_visites[] = $v_;
				} else if ($filter_item[0] == 't_log_installation.ref_site_install') {
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
			if (count($param_log_visite_) > 0) {
				$filtre .= " and (";
				$len_ = count($param_log_visite_);
				$contexte_ctr = 0;
				foreach ($param_log_visite_ as $est_item) {
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
			if (count($motif_visites) > 0) {
				$filtre .= " and (";
				$len_ = count($motif_visites);
				$contexte_ctr = 0;
				foreach ($motif_visites as $est_item) {
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

		$cacher->setPrefix("journal-visite");
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
		$date_identif = "";
		$date_titre = "Date visite";


		if ($utilisateur->HasDroits("10_40")) {
			$num_line = 0;
			foreach ($stmt as  $row_) {

				$date_identif = "";
				$date_titre = "Date visite";
				$motifs = '';
				/*
				
	$item->type_motif_visite = "0";// identification
	$item->type_motif_visite = "1";// Controle
	$item->type_motif_visite = "2";// Installation
				*/
				if ($row_["type_motif_visite"] == "0") {
					$motifs = 'IDENTIFICATION';
				} else if ($row_["type_motif_visite"] == "1") {
					$motifs = 'CONTROLE';
				} else if ($row_["type_motif_visite"] == "2") {
					$motifs = 'INSTALLATION';
				}

				$accessibiliy->code = $row_["statut_accessibilite"];
				$accessibiliy->GetDetailIN();

				$cvs_->code = $row_["cvs_id"];
				$cvs_->GetDetailIN();

				$accessibilitys = "";
				if ($accessibiliy->code == "3") {
					$accessibilitys = '<h4 class="mb-0 badge badge-warning">' . $accessibiliy->libelle . '</h4>';
				} else if ($accessibiliy->code == "1") {
					$accessibilitys = '<h4 class="mb-0 badge badge-danger">' . $accessibiliy->libelle . '</h4>';
				} else if ($accessibiliy->code == "4") {
					$accessibilitys = '<h4 class="mb-0 badge badge-info">' . $accessibiliy->libelle . '</h4>';
				}
				$num_line++;

				$date_rdvs = "";

				$technician = $utilisateur->GetUserDetailINFO($row_['n_user_create']);
				// var_dump($utilisateur);
				// exit;
				$chef_equipe = $utilisateur->GetUserDetailName($technician['chef_equipe_id']);
				$date_rdvs = trim($row_["date_rendez_vous_prev"]);
				if ($date_rdvs != '') {
					$date_rdvs = ' <span class="badge badge-info">' . $row_["date_rendez_vous_prev"] . '</span>';
				}
				$result .= '<div class="control-row card bg-white border-top">
			<div class="card-header d-flex">
			<div>	<div class="text-dark">' . $motifs . '</div> ' . $accessibilitys . ' ' .  $date_rdvs . '</div>';
				$result .= '
                                    </div><div class="card-body">
				<div class="row">
				<div class="col-sm-4">
						<div class="text-dark">
							' . $date_titre . '
						</div>
						<div class="font-medium text-primary control-date">' . $row_['date_visite'] . '</div>';

				/*if(	$row_["is_draft"] == '1'){
							  $result .= ' <span class="badge badge-info">Brouillon</span>';
							}*/

				$result .= '</div>	
					<div class="col-sm-4">
						<div class="text-dark">
							Technicien
						</div>
						<div class="font-medium text-primary control-staff">' . $technician['nom_complet'] . '</div>
					</div>				
					<div class="col-sm-4 text-left">
						<div class="text-dark">
							Chef equipe
						</div>
						<div class="font-medium text-primary control-staff">' . $chef_equipe . '</div>
					</div>
				</div>
				<div class="row">
					 
					<div class="col-sm-4">
						<div class="text-dark">
							Adresse
						</div>
						<div class="font-medium text-primary client-device">' . $commune->GetAdressInfoTexte($row_["ref_adresse"]) . '</div>
					</div>
					<div class="col-sm-4">
						<div class="text-dark">
							CVS
						</div>
						<div class="font-medium text-primary control-cvs">' . $cvs_->libelle . '</div>
					</div>
					<div class="col-sm-4">
						<div class="text-dark">
							Commentaire
						</div>
						<div class="font-medium text-primary control-cvs">' . $row_["commentaire"] . '</div>
					</div>';
				$result .= '
				</div>
			</div>
		</div>		';
			}
		}

		$result .=  $paginate_now->Paginate($view_mode);
		$result_array['data'] = $result;
		$result_array['count'] = $total_rows;
		echo json_encode($result_array);
		// paging buttons
		//  include_once 'layout_paging.php';

		/*}else{
				DroitsNotGranted();
			}*/
		break;

	default:
		$result_array["error"] = 1;
		$result_array["message"] = "Requête non prise en charge";
		echo json_encode($result_array);
		break;
}
// }
$db = null;
