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


		/*   START CODE   */


	case "create_code":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		//$generer= new Generateur($db,FALSE);
		// $generer= new Generateur($db,TRUE);						 
		/* $generer->has_signature=TRUE;
						 $generer->Signature_fld='signature_id';
						 $generer->Signature_Value='02';*/
		//$group_user->id_group = uniqUid("ts_group_user", "id_group"); 
		$item = new PARAM_TypeObservation($db);
		// $item->code = $generer->getUID('generateur_main','num_type_fraude','N','t_param_type_observation', 'code'); 
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->code_label = isset($_POST["code_label"]) ? $_POST["code_label"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Create();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;
	case "edit_code":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new PARAM_TypeObservation($db);
		$item->code = isset($_POST["code"]) ? $_POST["code"] : "";
		$item->libelle = isset($_POST["libelle"]) ? $_POST["libelle"] : "";
		$item->code_label = isset($_POST["code_label"]) ? $_POST["code_label"] : "";
		$item->n_user_create = $utilisateur->code_utilisateur;
		$result_array = $item->Modifier();
		//$result_array['readOnly']=0;
		Utils::responseJson($result_array);

		//	}
		/*}else{
					DroitsNotGranted();					
				}*/
		break;

	case "delete_code":
		/*if($utilisateur->HasDroits("10_180"))
			{	*/
		//	if($_GET){	
		$item = new PARAM_TypeObservation($db);
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
		/*   END CODE   */

	default:
		$result_array["error"] = 1;
		$result_array["message"] = "Requête non prise en charge";
		echo json_encode($result_array);
		break;
}


$db = null;
