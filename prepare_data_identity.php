<?php
$mnu_title = "IDENTIFICATION";
$page_title = "Liste des identifications effectuées";
$home_page = "dashboard.php";
$active = "abonnes";
$parambase = "";
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();

/*
//Prepare IDENTITE

$query = "SELECT t_main_data.id_,
t_main_data.client_id,
t_main_data.occupant_id,
t_main_data.numero_piece_identity,
t_main_data.nom_proprietaire_facture_snel,
t_main_data.phone_proprietaire_facture_snel,
t_main_data.nom_remplacant,
t_main_data.phone_remplacant,
t_main_data.nom_client_blue,
t_main_data.phone_client_blue,
t_main_data.nom_occupant_trouver,
t_main_data.phone_occupant_trouver,
t_main_data.statut_client,
t_main_data.adresse_id,
t_main_data.titre_responsable,
t_main_data.statut_occupant,
t_main_data.titre_remplacant 
FROM t_main_data  where coalesce(t_main_data.client_id,'')='' or  coalesce(t_main_data.client_id,'')=''";
$stmt = $db->prepare($query);
$stmt->execute();
$items= $stmt->fetchAll(PDO::FETCH_ASSOC);
$datesys = date("Y-m-d H:i:s");;
$n_user_create =3;
   
	$stmt_insert = $db->prepare('INSERT INTO t_param_identite SET  id=:id,id_adress=:id_adress,nom=:nom,phone_number=:phone_number,num_piece_identity=:num_piece_identity,statut_identity=:statut_identity,user_create=:n_user_create,date_create=:date_create,site_id=:site_id');	
	$stmt_main = $db->prepare('Update t_main_data SET  client_id=:client_id,occupant_id=:occupant_id WHERE id_=:id_');	

$site_id=1;	
$ctr=0;	
echo count($items);
foreach($items as $row){
   $client_id="";
   $occupant_id="";
	$ctr=$ctr+1;	
   
   //CREATION CLIENT
   
   $identite_piece=""; 
   $id_=$row['id_']; 
   $identite_adress_id=$row['adresse_id']; 
   $identite_nom_client=$row['nom_client_blue']; 
   $identite_phone_client=$row['phone_client_blue']; 
   $identite_statut_client=$row['statut_client']; 
   
   
   $client_id =  uniqUid("t_param_identite", "id"); 
           $stmt_insert->bindValue(":id",$client_id);
			$stmt_insert->bindValue(":id_adress",$identite_adress_id);
			$stmt_insert->bindValue(":nom", $identite_nom_client);
			$stmt_insert->bindValue(":phone_number",$identite_phone_client);
			$stmt_insert->bindValue(":num_piece_identity",$identite_piece);
			$stmt_insert->bindValue(":statut_identity",$identite_statut_client);
			$stmt_insert->bindValue(":n_user_create",$n_user_create);
			$stmt_insert->bindValue(":date_create",$datesys);
			$stmt_insert->bindValue(":site_id",$site_id);
			
		$stmt_insert->execute();
			
   //CREATION OCCUPANT
   
   $identite_piece=$row['numero_piece_identity']; 
   // $identite_adress_id=$row['adresse_id']; 
   $identite_nom_client=$row['nom_occupant_trouver']; 
   $identite_phone_client=$row['phone_occupant_trouver']; 
   $identite_statut_client=$row['statut_occupant']; 
   
   
   $occupant_id =  uniqUid("t_param_identite", "id"); 
           $stmt_insert->bindValue(":id",$occupant_id);
			$stmt_insert->bindValue(":id_adress",$identite_adress_id);
			$stmt_insert->bindValue(":nom", $identite_nom_client);
			$stmt_insert->bindValue(":phone_number",$identite_phone_client);
			$stmt_insert->bindValue(":num_piece_identity",$identite_piece);
			$stmt_insert->bindValue(":statut_identity",$identite_statut_client);
			$stmt_insert->bindValue(":n_user_create",$n_user_create);
			$stmt_insert->bindValue(":date_create",$datesys);
			$stmt_insert->bindValue(":site_id",$site_id);
			$stmt_insert->execute();	



			$stmt_main->bindValue(":occupant_id",$occupant_id);
			$stmt_main->bindValue(":client_id",$client_id);
			$stmt_main->bindValue(":id_",$id_);
			$stmt_main->execute();				
 
}


echo "Proceddd " . $ctr;
*/

$query = "SELECT * FROM t_param_adresse_entity  where CODE IN ('PN18','PN1')";
$stmt = $db->prepare($query);
$stmt->execute();



$items= $stmt->fetchAll(PDO::FETCH_ASSOC);
// $lst_user_chief ="";
// echo count($items);

$query = "SELECT code FROM t_param_adresse_entity_prepare  where code=:code";
$stmt_select = $db->prepare($query);

 $query = "INSERT INTO t_param_adresse_entity_prepare  SET code=:code,n_user_create=:n_user_create,category_id=:category_id,parent_id=:parent_id,libelle=:libelle,datesys=:datesys";
			   $stmt_insert = $db->prepare($query); 
			   
			   
			   		 $query = "UPDATE t_param_adresse_entity_prepare  SET n_user_update=:n_user_update,category_id=:category_id,parent_id=:parent_id,libelle=:libelle where code=:code";
			   $stmt_update = $db->prepare($query); 
			   
foreach($items as $row){
	 set_time_limit(0);
// $stmt_insert->bindParam(":code", $row['code']);
// $stmt_insert->bindParam(":datesys", $row['datesys']);
// $stmt_insert->bindParam(":n_user_create", $row['n_user_create']); 
// $stmt_insert->bindParam(":category_id", $row['category_id']); 
// $stmt_insert->bindParam(":parent_id", $row['parent_id']); 
// $stmt_insert->bindParam(":libelle", $row['libelle']); 
// $stmt_insert->execute();
	ProcessData($row);
	$row_chief = GenerateUserTree($row['code']); 
			if(count($row_chief)>0){
				//$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
					ProcessData($item);
									
				// $stmt_insert->bindParam(":code", $item['code']);
				// $stmt_insert->bindParam(":datesys", $item['datesys']);
				// $stmt_insert->bindParam(":n_user_create", $item['n_user_create']); 
				// $stmt_insert->bindParam(":category_id", $item['category_id']); 
				// $stmt_insert->bindParam(":parent_id", $item['parent_id']); 
				// $stmt_insert->bindParam(":libelle", $item['libelle']); 
				// $stmt_insert->execute();
						//$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
						//$lst_user_chief .= "'" . $item . "',";
					}
			}
	
	
}
echo 'finish';


    function ProcessData($DataSet) {		
        global $db;	
        global $stmt_select;	
        global $stmt_insert;	
		set_time_limit(0);
		$stmt_select->bindParam(':code', $DataSet['code']); 
			$stmt_select->execute();			
			$row_ = $stmt_select->fetch(PDO::FETCH_ASSOC);
			if(!$row_){						 
				$stmt_insert->bindParam(":code", $DataSet['code']);
				$stmt_insert->bindParam(":datesys", $DataSet['datesys']);
				$stmt_insert->bindParam(":n_user_create", $DataSet['n_user_create']); 
				$stmt_insert->bindParam(":category_id", $DataSet['category_id']); 
				$stmt_insert->bindParam(":parent_id", $DataSet['parent_id']); 
				$stmt_insert->bindParam(":libelle", $DataSet['libelle']); 
				$stmt_insert->execute();
			}			
	}
	
    function GenerateUserTree($user_code) {
		
        global $db;	
        global $stmt_insert;	
		 set_time_limit(0);
		$context_tree=array();
		 $query = "SELECT code,category_id,parent_id,libelle,n_user_create,datesys,n_user_update,date_update 
FROM t_param_adresse_entity WHERE (code = :id_u)";	 
				$stmt = $db->prepare( $query );
				$stmt->bindParam(':id_u', $user_code); 
				$stmt->execute(); 
			$row_chief = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(count($row_chief)>0){
				foreach ($row_chief as $item) {
						 // $context_tree[]=$item['code'];
						 
	ProcessData($item);
				// $stmt_insert->bindParam(":code", $item['code']);
				// $stmt_insert->bindParam(":datesys", $item['datesys']);
				// $stmt_insert->bindParam(":n_user_create", $item['n_user_create']); 
				// $stmt_insert->bindParam(":category_id", $item['category_id']); 
				// $stmt_insert->bindParam(":parent_id", $item['parent_id']); 
				// $stmt_insert->bindParam(":libelle", $item['libelle']); 
				// $stmt_insert->execute();
					GetParentUserAllChild($item,$context_tree);
					}
			}
			 
		return $context_tree;
	}
	
	
	
    function GetParentUserAllChild($user_context,&$context_tree){
		
        global $db;	
        global $stmt_insert;	
		 set_time_limit(0);
					$query = "SELECT code,category_id,parent_id,libelle,n_user_create,datesys,n_user_update,date_update 
FROM t_param_adresse_entity	WHERE (parent_id = :id_u)";	 
				$stmt = $db->prepare( $query );
				// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
				$stmt->bindParam(':id_u', $user_context['code']); 
				$stmt->execute(); 
				$rows= $stmt->fetchAll(PDO::FETCH_ASSOC);	
				foreach($rows as $item){
					// $context_tree[]=$item['code'];
					// $stmt_insert->bindParam(":code", $item['code']);
				// $stmt_insert->bindParam(":datesys", $item['datesys']);
				// $stmt_insert->bindParam(":n_user_create", $item['n_user_create']); 
				// $stmt_insert->bindParam(":category_id", $item['category_id']); 
				// $stmt_insert->bindParam(":parent_id", $item['parent_id']); 
				// $stmt_insert->bindParam(":libelle", $item['libelle']); 
				// $stmt_insert->execute();
				
	ProcessData($item);
					GetParentUserAllChild($item,$context_tree);
				}		
				 
			
	}

    function uniqUid($table, $key_fld) {
        //uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
        $bytes = md5(mt_rand());
        //Phase 2 verification existance avant retour code
       if (VerifierExistance($key_fld, $bytes, $table)) {
            $bytes = uniqUid($table, $key_fld);
        }
        return $bytes;
        //return substr(bin2hex($bytes),0,$len);
    }

    function VerifierExistance($pKey, $NoGenerated, $table) {
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
?>