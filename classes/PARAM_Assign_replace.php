<?php
 
class PARAM_Assign_replace{
   
  public function __construct($db){
   $this->connection = $db;
  }
  public $id_;
  public $id_organe;
  public $id_fiche_identif;
  public $datesys;
  public $datelastupdate;
  public $stateupdate;
  public $n_user_create;
  public $code_user_create;
  public $statut_;
  public $type_assignation;
  public $n_user_update;
  public $date_update;
  public $is_valid;
  public $annule;
  public $motif_annule;
  public $n_user_annule;
  
  private $table_name='t_param_assignation';
  private $param_type='3';// Remplacement
  private $connection;
  
  public $chef_equipe_install;
  public $id_controleur_quality;
  
  
  
    function GetOrganeControlAssigned($site, $id_organe){
		$query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code`
 where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.id_organe=:id_organe and t_param_assignation.is_valid=1 and t_param_assignation.type_assignation={$this->param_type} ORDER BY t_param_assignation.datesys  DESC";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_identif", $site);
		$stmt->bindValue(":id_organe", $id_organe);
		$stmt->execute();
		return $stmt;
    }
  
  
	 function Supprimer(){  
        //recuperation ref_fiche
		$ref_fiche="";
		$is_valid="0";
		$stmt = $this->connection->prepare("SELECT id_fiche_identif,is_valid FROM " . $this->table_name . " where id_assign=?");
		$stmt->bindParam(1,$this->id_);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row)
		{
			$ref_fiche=$row["id_fiche_identif"];
			$is_valid=$row["is_valid"];
		}	
		
		//suppression effective
        $query = "DELETE FROM " . $this->table_name . " WHERE id_assign=:id_assign"; 
        $stmt = $this->connection->prepare($query);
        $this->id_=(strip_tags($this->id_));
		$stmt->bindParam(":id_assign", $this->id_);
		 if($stmt->execute()){
			 //CHANGER ETAT MAINDATA EN NON ASSIGNE APRES EXECUTION
			 if($is_valid == '1'){
				$query = "update t_main_data set deja_assigner=0  where id_=:id_";
				$stmt = $this->connection->prepare($query);
				$stmt->bindValue(":id_", $ref_fiche);
				$stmt->execute();
			 }
            return true;
        }else{
			return false;
        }	 
	 } 
	 
	function CreateAssignControl($POST){ 		
        $datesys = date("Y-m-d H:i:s");
        $query = "INSERT INTO t_param_assignation (id_assign,id_organe,id_fiche_identif,datesys,n_user_create,type_assignation,id_chef_operation,id_controleur_quality) values (:id_assign,:id_organe,:id_fiche_identif,:datesys,:n_user_create,:type_assignation,:id_chef_operation,:id_controleur_quality);";
        $stmt = $this->connection->prepare($query);
		//$k => $v
        foreach ($POST as $value) {
            $id_assign = Utils::uniqUid("t_param_assignation", "id_assign",$this->connection);
            $stmt->bindValue(':id_assign', $id_assign);
            $stmt->bindValue(':id_organe', $this->id_organe);
            $stmt->bindValue(':id_fiche_identif', $value);
            $stmt->bindValue(':id_chef_operation', $this->chef_equipe_install);
            $stmt->bindValue(':id_controleur_quality', $this->id_controleur_quality);
            $stmt->bindValue(':type_assignation', $this->param_type);//categorie service = control (1)
            $stmt->bindValue(':n_user_create', $this->n_user_create);
            $stmt->bindValue(':datesys', $datesys);
            $stmt->execute();
			
			//CHANGER ETAT MAINDATA EN ASSIGNE POUR EVITER MULTI ASSIGNATION
			$query = "update t_main_data set deja_assigner=1  where id_=:id_";
				$stmt = $this->connection->prepare($query);
				//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
				$stmt->bindValue(":id_", $value);
				$stmt->execute();
        }
        $result["error"] = 0;
        $result["message"] = "Opération effectuée avec succès";
        $result["data"] = null;
        return $result;
	}
  
  
  function read(){ 
  // $query = "SELECT code,libelle,is_sync FROM " . $this->table_name . " ORDER BY libelle";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  function readAll($from_record_num, $records_per_page, $site){ 
   $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type} ORDER BY t_param_assignation.datesys  DESC LIMIT {$from_record_num}, {$records_per_page}";
   $stmt = $this->connection->prepare($query);
   $stmt->bindValue(":ref_site_identif", $site);
   $stmt->execute();
   return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page, $site){
   $query = "SELECT code,libelle,is_sync,annule  FROM " . $this->table_name  . " WHERE libelle LIKE :search_term  ORDER BY libelle ASC LIMIT :from, :offset";
   
   
    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type}) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term) ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";
 
   $stmt = $this->connection->prepare( $query );
   $search_term = "%{$search_term}%";
   $stmt->bindParam(':search_term', $search_term);
   $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
   $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
   $stmt->bindValue(":ref_site_identif", $site);
   $stmt->execute(); 
   return $stmt;
  }
  public function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $site) {
    
    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type}) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term) and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au) ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";
 
	$stmt = $this->connection->prepare( $query );
	$search_term = "%{$search_term}%";
	$stmt->bindParam(':search_term', $search_term);
	$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
	$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
	$stmt->bindValue(":ref_site_identif", $site);
	$stmt->bindParam(':du', $du);
	$stmt->bindParam(':au', $au);
   $stmt->execute(); 
   return $stmt;
  }
  public function search_advanced_DateOnly($du, $au,  $from_record_num, $records_per_page, $site) {
      
    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type}) and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au) ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";
 
	$stmt = $this->connection->prepare( $query );
	$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
	$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
	$stmt->bindValue(":ref_site_identif", $site);
	$stmt->bindParam(':du', $du);
	$stmt->bindParam(':au', $au);
   $stmt->execute(); 
   return $stmt;
  }
  public function countAll($site){ 
   $query = "SELECT t_main_data.id_ FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.type_assignation={$this->param_type} ";
   $stmt = $this->connection->prepare($query);
   $stmt->bindValue(":ref_site_identif", $site);
   $stmt->execute();
   $num = $stmt->rowCount();
   return $num;
  }
  public function countAll_BySearch( $search_term, $site){
   $query = "SELECT COUNT(*) as total_rows  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type}) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term)";
   $stmt = $this->connection->prepare( $query ); 
   $search_term = "%{$search_term}%";
   $stmt->bindParam(":search_term", $search_term);
   $stmt->bindValue(":ref_site_identif", $site);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   return $row["total_rows"];
  }
  public function countAll_BySearch_advanced($du, $au, $search_term, $site){
   $query = "SELECT COUNT(*) as total_rows  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type}) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term)  and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)";
   $stmt = $this->connection->prepare( $query ); 
   $search_term = "%{$search_term}%";
   $stmt->bindParam(":search_term", $search_term);
   $stmt->bindValue(":ref_site_identif", $site);
	$stmt->bindParam(':du', $du);
	$stmt->bindParam(':au', $au);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   return $row["total_rows"];
  }
  public function countAll_BySearch_advanced_DateOnly($du, $au, $site){
   $query = "SELECT COUNT(*) as total_rows  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation={$this->param_type})  and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)";
   $stmt = $this->connection->prepare( $query ); 
   $stmt->bindValue(":ref_site_identif", $site);
	$stmt->bindParam(':du', $du);
	$stmt->bindParam(':au', $au);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   return $row["total_rows"];
  }
}
?>
