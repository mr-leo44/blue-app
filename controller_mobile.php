<?php
session_start();

require_once 'loader/init.php';
Autoloader::Load('classes');
/*
require_once("include/database_pdo.php"); 
include_once 'classes/class.login.mobile.php';
include_once 'classes/class.identification.php';
include_once 'classes/class.installation.php';
include_once 'classes/class.utils.php';
include_once 'classes/class.utilisateur.php';*/
$view = "";
//SELECT datesys,STR_TO_DATE(datesys,'%d/%m/%Y') as dt_fr,DATE_FORMAT(datesys,'%d/%m/%Y') as dt_fr_ FROM `t_inscription_stagiaire`
$result_array = array();
// get database connection
$database = new Database();
$db = $database->getConnection(); 
$admin_group_id = "3";
/*
$utilisateur = new Utilisateur($db);
$utilisateur->code_utilisateur=$_SESSION['uSession'];
$utilisateur->readOne();*/			
			
if(isset($_REQUEST["view"]))
	$view = $_REQUEST["view"];

switch($view){
	case "Login":
		$data = file_get_contents("php://input");			
		$ticket = new LoginMobile($db);
		$ticket->Login($data);
		break;
	case "getList_identifs":
		//$data = file_get_contents("php://input");			
		$lst = new Identification($db);
		$reponse=$lst->GetListeIdentifs();
		Utils::responseJson($reponse);
		break;
	case "getList_installs":
		//$data = file_get_contents("php://input");			
		$lst = new Installation($db);
		$reponse=$lst->GetListeInstalls();
		Utils::responseJson($reponse);
		break;
	
	case "CvsSpinner"://Mobile
		$data = file_get_contents("php://input");			
		$lst = new Identification($db);
		$lst->CvsSpinner($data);
		break;
	
	case "CommuneSpinner":			
		$lst = new Identification($db);
		$lst->CommuneSpinner();
		break;
		
	case "SaveIdentification":
		//$data = file_get_contents("php://input");
		$save_path = 'pictures/';
		$response  = array();
		 
		
	 
		// getting server ip address
		$server_ip       = gethostbyname(gethostname());
		  // final file url that is being uploaded
		$file_upload_url = 'http://' . $server_ip . '/' . 'm-stock-snel/images/product' . '/' . $save_path;
		// $new_file_name=isset($_GET["id"])?$_GET["id"]:basename($_FILES['file']['name']);
		  	$lst = new Identification($db);
		$reponse=$lst->Create($_POST["json"]);
		if($reponse["error"] == false){
			if(isset($_FILES['file']['name'])){
				//$save_path = $save_path . basename($_FILES['file']['name']);
				$save_path = $save_path . $reponse["id"] .'.jpeg';			 
				//$response['file_name'] = basename($_FILES['file']['name']);
				try
				{
					// Throws exception incase file is not being moved
					if(!move_uploaded_file($_FILES['file']['tmp_name'], $save_path))
					{
						// set status flag to - 1
						// $response['status'] = - 1;
						//$response['message'] = 'Could not upload the file!';
					}
			 
					// File successfully uploaded. set status flag to 0
					// $response['message'] = 'File uploaded successfully!';
					// $response['status'] = 0;
					// $response['file_path'] = $file_upload_url . basename($_FILES['file']['name']);
				} catch(Exception $e)
				{
					// Exception occurred. set status flag to - 2
					// $response['status'] = - 2;
					// $response['message'] = $e->getMessage();
				}
			}
			else
			{
				// File parameter is missing
				// $response['status'] = - 3;
				// $response['message'] = 'File is missing';
			}			
		}
		Utils::responseJson($reponse); 
		
		break;
	case "SaveInstallation":
		//$data = file_get_contents("php://input");
		$save_path = 'pictures/';
		$response  = array();
		 
		
		$lst = new Installation($db);
		$reponse=$lst->Create($_POST["json"]);
		Utils::responseJson($reponse); 
		 
		// getting server ip address
		$server_ip       = gethostbyname(gethostname());
		  // final file url that is being uploaded
		$file_upload_url = 'http://' . $server_ip . '/' . 'm-stock-snel/images/product' . '/' . $save_path;
		// $new_file_name=isset($_GET["id"])?$_GET["id"]:basename($_FILES['file']['name']);
		  
		if(isset($_FILES['file']['name'])){
			$save_path = $save_path . basename($_FILES['file']['name']);
		 
			$response['file_name'] = basename($_FILES['file']['name']);
			try
			{
				// Throws exception incase file is not being moved
				if(!move_uploaded_file($_FILES['file']['tmp_name'], $save_path))
				{
					// set status flag to - 1
					$response['status'] = - 1;
					$response['message'] = 'Could not upload the file!';
				}
		 
				// File successfully uploaded. set status flag to 0
				$response['message'] = 'File uploaded successfully!';
				$response['status'] = 0;
				$response['file_path'] = $file_upload_url . basename($_FILES['file']['name']);
			} catch(Exception $e)
			{
				// Exception occurred. set status flag to - 2
				$response['status'] = - 2;
				$response['message'] = $e->getMessage();
			}
		}
		else
		{
			// File parameter is missing
			$response['status'] = - 3;
			$response['message'] = 'File is missing';
		}
		
		break;
}



//public function uniqUid($len = 13) {  
    function uniqUid($table, $key_fld) {
        //uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
        $bytes = md5(mt_rand());
        //Phase 2 verification existance avant retour code
       /* if (VerifierExistance($key_fld, $bytes, $table)) {
            $bytes = uniqUid($table, $key_fld);
        }*/
        return $bytes;
        //return substr(bin2hex($bytes),0,$len);
    }

    function VerifierExistance($pKey, $NoGenerated, $table) {
        global $cnx;	
        $retour = false;
        $sql = "select $pKey from $table where $pKey=:NoGenerated";
        $stmt = $this->connection->prepare($sql);
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


	function ClentToDbDateFormat($c_date){	
		//$dt="17/07/2012";
		$n_date=str_ireplace('/','-',$c_date);
		$f_dt=date('Y-m-d',strtotime($n_date));
		return $f_dt;
	}
    function GetServerDateOrTime($p = "D") {
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
?>
