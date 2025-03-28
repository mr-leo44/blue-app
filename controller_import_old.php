<?php
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


function uniqUid($table, $key_fld)
{
	//uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
	//$bytes = md5(mt_rand());
	$init = true;
	//Phase 2 verification existance avant retour code
	while ($init == true) {
		$bytes =  uniqUidX($table, $key_fld);
		$init =  VerifierExistance($key_fld, $bytes, $table);
	}
	return $bytes;
	//return substr(bin2hex($bytes),0,$len);
}

function uniqUidX($table, $key_fld)
{
	//uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
	$bytes = md5(mt_rand());
	return $bytes;
	//return substr(bin2hex($bytes),0,$len);
}

function VerifierExistance($pKey, $NoGenerated, $table)
{
	global $db;
	$retour = false;
	$sql = "select $pKey from $table where $pKey='" . $NoGenerated . "'";
	$stmt = $db->prepare($sql);
	//$stmt->bindParam(':$pKey', $genNB, PDO::PARAM_STR);
	//$stmt->bindValue(":pKey", $pKey);			
	// $stmt->bindValue(":NoGenerated", $NoGenerated);
	//$stmt->bindValue(":table", $table);
	$stmt->execute();
	if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$retour = true;
	} else {
		$retour = false;
	}
	return $retour;
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
	case "import-ctr-ctl":
		extractionCompteurControlXLS($utilisateur);

		break;

	default:
		$result_array["error"] = 1;
		$result_array["message"] = "Requête non prise en charge";
		echo json_encode($result_array);
		break;
}

/*
	public function SaveMateriels($identif, $materiels) {
		if(!is_array($materiels)){
			return;
		}
		
        $stmt = $this->connection->prepare("DELETE FROM t_log_installation_materiels WHERE ref_identification=:ref_identification");
		$stmt->bindValue(':ref_identification', $identif);
		$stmt->execute();
		
		$datesys = date("Y-m-d H:i:s");
		$query = "INSERT INTO t_log_installation_materiels (id_mat,ref_article,ref_identification,qte_identification,datesys) values (:id_mat,:ref_article,:ref_identification,:qte_identification,:datesys)";
        $stmt = $this->connection->prepare($query);
        foreach ($materiels as $value) {
            $id_mat = $this->uniqUid("t_log_installation_materiels", "id_mat");
            $stmt->bindValue(':id_mat', $id_mat);
			$stmt->bindValue(':ref_article', $value->libelle);
			$stmt->bindValue(':ref_identification', $identif);
			$stmt->bindValue(':qte_identification', $value->qte);
			$stmt->bindValue(':datesys', $datesys); 
            $stmt->execute();
        }
        return true;
    }
	
	
  function DispatchingAssignInstall($POST, $technicien){ 		
        $date_update = date("Y-m-d H:i:s");
        $query = "Update t_param_assignation    set n_user_update=:n_user_update,date_update=:date_update,id_technicien=:id_technicien where id_assign=:id_assign";
        $stmt = $this->connection->prepare($query);
		//$k => $v
        foreach ($POST as $value) {
           // $id_assign = Utils::uniqUid("t_param_assignation", "id_assign",$this->connection);
            
            $stmt->bindValue(':id_assign', $value);
            $stmt->bindValue(':id_technicien', $technicien); 
            $stmt->bindValue(':date_update', $date_update);
            $stmt->bindValue(':n_user_update', $this->n_user_create);
            $stmt->execute(); 			
        }
        $result["error"] = 0;
        $result["message"] = "Opération effectuée avec succès";
        $result["data"] = null;
        return $result;
	}
	
	case "create_control_assign":
				
				 if ($_POST) {
					 if(empty($_POST['tbl-checkbox'])){
						$result["error"] = 1;
						$result["message"] = "Veuillez sélectionner les compteurs à contrôler";
						$result["data"] = null;
						echo json_encode($result);
					 }else{
						 $item = new PARAM_Assign($db);
						 $item->type_assignation='1';	//Control					 
						 $item->id_organe = isset($_POST["id_equipe_identification"]) ? $_POST["id_equipe_identification"] : "";
						 $item->chef_equipe_control = isset($_POST["chef_equipe_control"]) ? $_POST["chef_equipe_control"] : "";
						 $item->id_controleur_quality =isset($_POST["controleur_quality"]) ? $_POST["controleur_quality"] : "";
						 $item->n_user_create = $utilisateur->code_utilisateur;
						 $result_array =$item->CreateAssignControl($_POST['tbl-checkbox']); 
						 echo json_encode($result_array);
					 }
				}
				break;
	
	
		function CreateAssignControl($POST){ 		
        $datesys = date("Y-m-d H:i:s");
       
			 $query = "INSERT INTO t_param_assignation (id_assign,id_organe,id_fiche_identif,datesys,n_user_create,type_assignation,id_chef_operation,id_controleur_quality) values (:id_assign,:id_organe,:id_fiche_identif,:datesys,:n_user_create,:type_assignation,:id_chef_operation,:id_controleur_quality);";
        $stmt = $this->connection->prepare($query);
		
		
	$stmt_avoid_duplicate = $this->connection->prepare('SELECT id_assign FROM t_param_assignation where id_fiche_identif=:id_fiche_identif and is_valid=1');
		//$k => $v
        foreach ($POST as $value) {
           // $id_assign = Utils::uniqUid("t_param_assignation", "id_assign",$this->connection);
		   $stmt_avoid_duplicate->bindValue(':id_fiche_identif', $value);
			$stmt_avoid_duplicate->execute();
			$data_row = $stmt_avoid_duplicate->fetch(PDO::FETCH_ASSOC);
			if(!$data_row)
			{
			   $id_assign = $this->uniqUid("t_param_assignation", "id_assign");
				$stmt->bindValue(':id_assign', $id_assign);
				$stmt->bindValue(':id_organe', $this->id_organe);
				$stmt->bindValue(':id_fiche_identif', $value);
				$stmt->bindValue(':datesys', $datesys);
				$stmt->bindValue(':n_user_create', $this->n_user_create);
				$stmt->bindValue(':type_assignation',$this->type_assignation);//categorie service = control (1)
				$stmt->bindValue(':id_chef_operation', $this->chef_equipe_control);
				$stmt->bindValue(':id_controleur_quality', $this->id_controleur_quality);
				$stmt->execute();
			//CHANGER ETAT MAINDATA EN ASSIGNE POUR EVITER MULTI ASSIGNATION
			$query = "update t_main_data set deja_assigner=1  where id_=:id_";
				$stmtx = $this->connection->prepare($query);
				//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
				$stmtx->bindValue(":id_", $value);
				$stmtx->execute();
			}
			
        }
        $result["error"] = 0;
        $result["message"] = "Opération effectuée avec succès";
        $result["data"] = null;
        return $result;
	}
  

	
	*/


/**
 * Query the database for a login_id and output JSON for use in JavaScript.
 *
 * @param int $loginID the login_id to look up
 */
function extractionCompteurControlXLS($user)
{
	global $db, $utilisateur;
	$result_array = [];
	$filePath = 'temp_data/';
	if (is_dir($filePath) === false) {
		mkdir($filePath);
	}
	//	["DeclarationCalcul","StatDeclarationCalcul","VentillationPaiement","StatVentillationPaiement","AttestationPaiement","StatAttestationPaiement","RepartitionProvince","StatRepartitionProvince","CDDCDIONEM","StatCDDCDIONEM","DeclarationAnnulée","StatDeclarationAnnulée"]

	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) . '';
	$path_info_detail = pathinfo($_FILES['file']['name']);
	$nom_fichier = $path_info_detail['filename'];
	$file_name = $user->code_utilisateur . '_' . md5(uniqid(rand(), true)) . '.' . $ext;
	// $group->n_user_create =$utilisateur->code_utilisateur; 

	// $file_name =  md5(uniqid(rand(), true)). '.' . $ext;
	$filePath .= $file_name; //$_FILES['file']['name'];
	if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
		$result_array["error"] = 0;
		$result_array["message"] = "Importation effectuée avec succès";
	} else {
		$result_array["error"] = 1;
		$result_array["message"] = "Echec d'importation du fichier";
		return $result_array;
	}

	$item = new Identification($db);

	// DECLARATION
	//DateCreation	NIF	CodeCategorie	NDepot	NAFONEM	NAFINSS	NAFINPP	RaisonSociale	Sigle	Exercice	Mois	LibelleCategorie	Nombre	Brutes	Nettes	Taux	MontantIPR	MontantIERE	DroitTotalDu	DateDepot	NbreNat	NbreExp	BrutesNat	BrutesExp

	//$cls_ese_inpp = new XLS_DATA_DGI($this->pdo, $ext);
	$message = array();
	$messages = array();
	if (isset($_FILES['file'])) {
		// if ($xlsx = XLSXReader::parse($_FILES['file']['tmp_name'])) {
		try {
			$Spreadsheet = new SpreadsheetReader($filePath);
			$sheetNames = [];

			$Sheets = $Spreadsheet->Sheets();

			// $sheets_a_traiter = ["DeclarationCalcul","AttestationPaiement","VentillationPaiement"];
			// var_dump($Sheets);
			// exit;
			/*
foreach ($Sheets as $Index => $Name)
		{
			$sheetNames[$Name]=$Index;
		}*/
			// echo '<h2>Parsing Result</h2>';
			// echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';

			//	$dim = $xlsx->dimension();
			//	$cols = $dim[0];
			$ctr_duplicate = 0;
			$success_insert = 0;
			$error_count = 0;
			$lst_duplicate = array();




			//$this->pdo->beginTransaction();

			//DeclarationCalcul  
			// $query = "SELECT ndepot FROM t_declaration WHERE ndepot=:ndepot";
			// $Spreadsheet -> ChangeSheet($sheetNames['DeclarationCalcul']);
			$Spreadsheet->ChangeSheet(0);

			// var_dump($sheetNames);
			// exit;
			//PREPARATION LISTES DES CHEFS CONTROLEURS BASES AU CONTEXTE UTILISATEUR



			//PREPARATION LISTES DES CONTROLEURS BASES AU CONTEXTE UTILISATEUR


			$query_user_detail = "SELECT code_utilisateur,nom_utilisateur,nom_complet,id_group as gp,activated as et,site_id as site,phone_user,email_user,chef_equipe_id,id_organisme,is_chief FROM t_utilisateurs	WHERE nom_complet = ?
			LIMIT 0,1";


			$stmt_avoid_duplicate = $db->prepare('SELECT id_assign FROM t_param_assignation where id_fiche_identif=:id_fiche_identif and is_valid=1');


			$query = "INSERT INTO t_param_assignation (id_assign,id_organe,id_fiche_identif,datesys,n_user_create,type_assignation,id_chef_operation,id_controleur_quality,id_technicien) values (:id_assign,:id_organe,:id_fiche_identif,:datesys,:n_user_create,:type_assignation,:id_chef_operation,:id_controleur_quality,:id_technicien);";
			$stmt = $db->prepare($query);


			$stmt_user_detail = $db->prepare($query_user_detail);
			$datesys = date("Y-m-d H:i:s");
			$id_controleur_quality = "";
			$type_assignation  = '1';	//Control		
			foreach ($Spreadsheet as $k => $r) {


				if (strtolower($ext) == "xls") {
					if ($k == 1) continue; // skip first row
				} else if (strtolower($ext) == "xlsx") {
					if ($k == 0) continue; // skip first row
				}


				$Numero_compteur = isset($r[0]) ? trim($r[0]) : ''; // [3, 4, 5] CodeCategorie autorisés	
				$Nom_complet_Controleur = isset($r[1]) ? trim($r[1]) : ''; // [3, 4, 5] CodeCategorie autorisés	
				// echo $categorie . "$ext<br>";
				//
				$resultat = $item->GetCompteurAdresseForControl($Numero_compteur, $utilisateur);
				// var_dump($resultat);
				if (isset($resultat) && isset($resultat["error"])) {
					if ($resultat["error"] == 1) { //DEJA ASSIGNE
						//GENERER LOG ADRESSE CONTIENT DEJA UNE ASSIGNATION
						$error_count++;
						//Error detail 
						$message["compteur"] = $Numero_compteur;
						// $message["erro_type"] = "warning";
						$message["error_type"] = $resultat["error_type"];
						$message["error_message"] = $resultat["message"]; //"Compteur a déja une assignation valide";
						$messages["error_list"][] = $message;
					} else if ($resultat["error"] == 0) { //NON ASSIGNE
						//var_dump($resultat);
						//exit;
						$compteur_item = $resultat["items"][0];
						$data = $compteur_item["data"]; //"id_" ["jour_passer_dernier_controle"] ["date_dernier_controle_fr"]["gps_longitude"]["gps_latitude"]["p_a"] ["reference_appartement"] ["nom_client_blue"]["phone_client_blue"] ["num_compteur_actuel"]["adresse_id"] ["cvs_id"]
						$adresseTexte = $compteur_item["adresseTexte"];
						$value = $data["id_"];

						//VERIFICATION INFOS CONTROLEURS
						$Nom_complet_Controleur = trim(strip_tags($Nom_complet_Controleur));
						$stmt_user_detail->bindParam(1, $Nom_complet_Controleur);
						$stmt_user_detail->execute();
						$row_controleur = $stmt_user_detail->fetch(PDO::FETCH_ASSOC);
						if ($row_controleur != false) {
							// echo ($row_controleur["code_utilisateur"]);
							// code_utilisateur,nom_utilisateur,nom_complet,id_group as gp,activated as et,site_id as site,phone_user,email_user,chef_equipe_id,id_organisme,is_chief 
							$id_technicien = $row_controleur["code_utilisateur"];
							$id_organe = $row_controleur["id_organisme"];
							$chef_equipe_control = $row_controleur["chef_equipe_id"];
							// var_dump($row_controleur);
							//EVITER MULTIPLE ASSIGNATION
							$stmt_avoid_duplicate->bindValue(':id_fiche_identif', $value);
							$stmt_avoid_duplicate->execute();
							$data_row = $stmt_avoid_duplicate->fetch(PDO::FETCH_ASSOC);
							if (!$data_row) {
								$id_assign = uniqUid("t_param_assignation", "id_assign");
								$stmt->bindValue(':id_assign', $id_assign);
								$stmt->bindValue(':id_organe', $id_organe);
								$stmt->bindValue(':id_fiche_identif', $value);
								$stmt->bindValue(':datesys', $datesys);
								$stmt->bindValue(':n_user_create', $user->code_utilisateur);
								$stmt->bindValue(':type_assignation', $type_assignation); //categorie service = control (1)
								$stmt->bindValue(':id_technicien', $id_technicien); //categorie service = control (1)
								$stmt->bindValue(':id_chef_operation', $chef_equipe_control);
								$stmt->bindValue(':id_controleur_quality', $id_controleur_quality);
								$stmt->execute();
								//CHANGER ETAT MAINDATA EN ASSIGNE POUR EVITER MULTI ASSIGNATION
								$query = "update t_main_data set deja_assigner=1  where id_=:id_";
								$stmtx = $db->prepare($query);
								//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
								$stmtx->bindValue(":id_", $value);
								$stmtx->execute();
								$success_insert++;
							} else {

								$error_count++;
								//Error detail 
								$message["compteur"] = $Numero_compteur;
								$message["erro_type"] = "warning";
								$message["erro_message"] = "Compteur a déja une assignation valide";
								$messages["error_list"][] = $message;
							}

							//A AJOUTER DETAIL TECHNICIEN ET GENERER DIRECTEMENT L'ASSIGNATION POUR CONTROLEURS
							//A LA FIN GENERER UN RAPPORT D'IMPORTATION


						} else {
							//GENERER LOG ERREUR CONTROLEUR NON RECONNU

							$error_count++;
							//Error detail 
							$message["compteur"] = $Numero_compteur;
							$message["controleur"] = $Nom_complet_Controleur;
							$message["error_type"] = "error";
							$message["error_message"] = "Contrôleur non reconnu par le système";
							$messages["error_list"][] = $message;
						}
					}
				}


				// if(in_array($categorie, [3, 4, 5])){
				// // echo $cls_ese_inpp->toUnixTimeStamp($r[19]) . '<br>';
				///////////////////////////////////////////////
				/*	$id_declaration= "";
							$datecreation = $cls_ese_inpp->formatDateFromShort($r[0]);// ? ($cls_ese_inpp->checkIsAValidDate(trim($r[0]))?trim($r[0]):null) : null;
							$ndepot= isset($r[3]) ? trim($r[3]) : '';
							$datedepot= $cls_ese_inpp->formatDateFromShort($r[19]);//isset($r[19]) ? ($cls_ese_inpp->checkIsAValidDate(trim($r[19]))?trim($r[19]):null) : null;	
							$exercice= $cls_ese_inpp->getOrCreateExercice($r[9]); 
							$mois =  isset($r[10]) ? trim($r[10]) : '1'; //$cls_ese_inpp->getMois($r[10]); 
							$ref_entreprise= $cls_ese_inpp->getOrCreateEntreprise($r[7]); 
							$code_banque= "0";//$cls_ese_inpp->getOrCreateBanque($this->pdo, $r[7]); */
				//}
			}

			if (file_exists($filePath)) {
				unlink($filePath);
			}
			$messages["nbre_error"] = $error_count;
			$messages["nbre_success"] = $success_insert;
			die(json_encode($messages));
		} catch (\Exception $e) {
			if (file_exists($filePath)) {
				unlink($filePath);
			}
			/*   if ($this->pdo->inTransaction()) {
							$this->pdo->rollback();
							$result["error"] = true;
							$result["message"] = "Echec opération";
							$result["data"] = $e->getMessage();
							die(json_encode($message));	
					
						}*/
		}
	}
}
// }
$db = null;
