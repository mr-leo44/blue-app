<?php

class PARAM_Assign
{

	public function __construct($db)
	{
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
	public $chef_equipe_control;
	public $id_controleur_quality = "";

	private $table_name = 't_param_assignation';
	private $connection;


	/* function GetUserFilter($user_context){
	  $user_filtre =  '';
  if($user_context->id_service_group ==  '3'||$user_context->HasGlobalAccess()){
			$user_filtre="";
		}else if($user_context->is_chief == '1'){
			$lst_user_chief= '';	 
			$row_chief = $user_context->GenerateUserTree($user_context->code_utilisateur); 
			if(count($row_chief)>0){
				foreach ($row_chief as $item) {
						$lst_user_chief .= "'" . $item . "',";
					}
			}
			$clean = rtrim($lst_user_chief,",");		
			$user_filtre=" and (t_param_assignation.id_technicien in (" . $clean . ") OR t_param_assignation.id_chef_operation  in (" . $clean . ")) ";
		}else{
			$user_filtre=" and (t_param_assignation.id_technicien='" . $user_context->code_utilisateur  . "' OR t_param_assignation.id_chef_operation='" . $user_context->code_utilisateur  . "'";
		}
  return $user_filtre;
  }*/


	function GetOrganeControlAssigned($user_context)
	{

		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.reference_appartement,DATE_FORMAT(t_param_assignation.date_update,'%d/%m/%Y %H:%i:%S')  as date_assign_technicien,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,' ')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle,t_param_assignation.id_chef_operation,t_param_assignation.id_technicien,t_param_assignation.is_sceller_required,t_param_assignation.date_rendez_vous,DATE_FORMAT(t_param_assignation.date_rendez_vous,'%d/%m/%Y')  as date_rendez_vous_fr,t_param_assignation.comment_rendez_vous,t_param_assignation.accesibility,t_param_assignation.date_accessibility  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		// $stmt->bindValue(":id_organe", $user_context->id_organisme);
		$stmt->bindValue(":type_assignation", $this->type_assignation);


		$stmt->execute();
		$adress_item = new  AdresseEntity($this->connection);
		$result = array();
		$item = array();

		$result["items"] = array();
		$row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($row_) > 0) {
			foreach ($row_ as $vl) {
				$item['data'] = $vl;
				$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
				$item["technicien"] = $user_context->GetUserDetailName($vl["id_technicien"]);
				$item["chef"] = $user_context->GetUserDetailName($vl["id_chef_operation"]);
				$result['items'][] = $item;
			}
		}
		$result["error"] = 0;
		return $result;
	}




	function GetOrganeControlAssignedSearch($user_context, $search, $from_record_num, $records_per_page)
	{
		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "";
		$total_rows =  0;

		$search = trim($search);
		if ($search != '') {
			$query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.reference_appartement,DATE_FORMAT(t_param_assignation.date_update,'%d/%m/%Y %H:%i:%S')  as date_assign_technicien,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle,t_param_assignation.id_chef_operation,t_param_assignation.id_technicien,t_param_assignation.is_sceller_required,t_param_assignation.date_rendez_vous,DATE_FORMAT(t_param_assignation.date_rendez_vous,'%d/%m/%Y')  as date_rendez_vous_fr,t_param_assignation.comment_rendez_vous,t_param_assignation.accesibility,t_param_assignation.date_accessibility  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` where (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term) and t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC  LIMIT :from, :offset";

			$query_total = "SELECT COUNT(*) as total_rows FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` where (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term) and t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys";
		} else {
			$query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.reference_appartement,DATE_FORMAT(t_param_assignation.date_update,'%d/%m/%Y %H:%i:%S')  as date_assign_technicien,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle,t_param_assignation.id_chef_operation,t_param_assignation.id_technicien,t_param_assignation.is_sceller_required,t_param_assignation.date_rendez_vous,DATE_FORMAT(t_param_assignation.date_rendez_vous,'%d/%m/%Y')  as date_rendez_vous_fr,t_param_assignation.comment_rendez_vous,t_param_assignation.accesibility,t_param_assignation.date_accessibility  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC  LIMIT :from, :offset";


			$query_total = "SELECT COUNT(*) as total_rows FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys";
		}

		$stmt = $this->connection->prepare($query);
		$stmt_total = $this->connection->prepare($query_total);
		if ($search != '') {
			$search_term = "%{$search}%";
			$stmt->bindParam(':search_term', $search_term);
		}


		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		// $stmt->bindValue(":id_organe", $user_context->id_organisme);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->execute();

		$adress_item = new  AdresseEntity($this->connection);
		$result = array();
		$item = array();

		$result["items"] = array();
		$row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($row_) > 0) {
			foreach ($row_ as $vl) {
				$item['data'] = $vl;
				$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
				$item["technicien"] = $user_context->GetUserDetailName($vl["id_technicien"]);
				$item["chef"] = $user_context->GetUserDetailName($vl["id_chef_operation"]);
				$result['items'][] = $item;
			}
		}
		$result["error"] = 0;
		return $result;
	}

	function GetOrganeControlAssignedSearchAll($user_context, $search)
	{
		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "";
		$total_rows =  0;

		$search = trim($search);
		if ($search != '') {

			$query_total = "SELECT COUNT(*) as total_rows FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` where (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or coalesce(identite_client.phone_number,'') Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term) and t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys";
		} else {
			$query_total = "SELECT COUNT(*) as total_rows FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and t_param_assignation.type_assignation=:type_assignation " . $user_filtre . " ORDER BY t_param_assignation.datesys";
		}

		$stmt = $this->connection->prepare($query_total);
		if ($search != '') {
			$search_term = "%{$search}%";
			$stmt->bindParam(':search_term', $search_term);
		}


		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		// $stmt->bindValue(":id_organe", $user_context->id_organisme);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->execute();
		$row = $stmt->fetch();
		// $result["error"] = 0;			
		// $result["nbre"] = $row['total_rows'];			
		return $row['total_rows'];
	}

	function GetOrganeInstReplaceAssigned($user_context)
	{
		$user_filtre = $user_context->GetUserFilterAssignation();

		// /var_dump($user_filtre);
		// exit;

		$query = "SELECT t_main_data.id_,t_main_data.date_identification,DATE_FORMAT(t_param_assignation.date_update,'%d/%m/%Y %H:%i:%S')  as date_assign_technicien,t_main_data.p_a,t_main_data.reference_appartement,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle,t_param_assignation.id_chef_operation,t_param_assignation.id_technicien,t_param_assignation.is_sceller_required,t_param_assignation.date_rendez_vous,DATE_FORMAT(t_param_assignation.date_rendez_vous,'%d/%m/%Y')  as date_rendez_vous_fr,t_param_assignation.comment_rendez_vous,t_param_assignation.accesibility,t_param_assignation.date_accessibility FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and (t_param_assignation.type_assignation=:type_assignation Or t_param_assignation.type_assignation='3') " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC";
		$stmt = $this->connection->prepare($query);

		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		// $stmt->bindValue(":id_organe", $user_context->id_organisme);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->execute();
		$adress_item = new  AdresseEntity($this->connection);
		$result = array();
		$item = array();

		$result["items"] = array();
		$row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($row_) > 0) {
			foreach ($row_ as $vl) {
				$item['data'] = $vl;
				$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
				$item["technicien"] = $user_context->GetUserDetailName($vl["id_technicien"]);
				$item["chef"] = $user_context->GetUserDetailName($vl["id_chef_operation"]);
				$result['items'][] = $item;
			}
		}
		$result["error"] = 0;
		return $result;
	}

	function GetOrganeInstReplaceAssignedSearch($user_context, $search)
	{
		$user_filtre = $user_context->GetUserFilterAssignation();

		// /var_dump($user_filtre);
		// exit;
		$query = "";
		$search = trim($search);
		if ($search != '') {
			$query = "SELECT t_main_data.id_,t_main_data.date_identification,DATE_FORMAT(t_param_assignation.date_update,'%d/%m/%Y %H:%i:%S')  as date_assign_technicien,t_main_data.p_a,t_main_data.reference_appartement,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle,t_param_assignation.id_chef_operation,t_param_assignation.id_technicien,t_param_assignation.is_sceller_required,t_param_assignation.date_rendez_vous,DATE_FORMAT(t_param_assignation.date_rendez_vous,'%d/%m/%Y')  as date_rendez_vous_fr,t_param_assignation.comment_rendez_vous,t_param_assignation.accesibility,t_param_assignation.date_accessibility FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` where (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or coalesce(identite_client.phone_number,'') Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term) and t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and (t_param_assignation.type_assignation=:type_assignation Or t_param_assignation.type_assignation='3') " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC";
		} else {
			$query = "SELECT t_main_data.id_,t_main_data.date_identification,DATE_FORMAT(t_param_assignation.date_update,'%d/%m/%Y %H:%i:%S')  as date_assign_technicien,t_main_data.p_a,t_main_data.reference_appartement,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_main_data.est_installer,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_cvs.libelle,t_param_assignation.id_chef_operation,t_param_assignation.id_technicien,t_param_assignation.is_sceller_required,t_param_assignation.date_rendez_vous,DATE_FORMAT(t_param_assignation.date_rendez_vous,'%d/%m/%Y')  as date_rendez_vous_fr,t_param_assignation.comment_rendez_vous,t_param_assignation.accesibility,t_param_assignation.date_accessibility FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and  t_param_assignation.is_valid=1 and (t_param_assignation.type_assignation=:type_assignation Or t_param_assignation.type_assignation='3') " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC";
		}
		$stmt = $this->connection->prepare($query);
		if ($search != '') {
			$search_term = "%{$search}%";
			$stmt->bindParam(':search_term', $search_term);
		}
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		// $stmt->bindValue(":id_organe", $user_context->id_organisme);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->execute();
		$adress_item = new  AdresseEntity($this->connection);
		$result = array();
		$item = array();

		$result["items"] = array();
		$row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($row_) > 0) {
			foreach ($row_ as $vl) {
				$item['data'] = $vl;
				$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
				$item["technicien"] = $user_context->GetUserDetailName($vl["id_technicien"]);
				$item["chef"] = $user_context->GetUserDetailName($vl["id_chef_operation"]);
				$result['items'][] = $item;
			}
		}
		$result["error"] = 0;
		return $result;
	}


	function Supprimer()
	{

		//recuperation ref_fiche
		$ref_fiche = "";
		$is_valid = "0";
		$stmt = $this->connection->prepare("SELECT id_fiche_identif,is_valid FROM " . $this->table_name . " where id_assign=?");
		$stmt->bindParam(1, $this->id_);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$ref_fiche = $row["id_fiche_identif"];
			$is_valid = $row["is_valid"];
		}

		//suppression effective
		$query = "DELETE FROM " . $this->table_name . " WHERE id_assign=:id_assign";
		$stmt = $this->connection->prepare($query);
		$this->id_ = (strip_tags($this->id_));
		$stmt->bindParam(":id_assign", $this->id_);
		if ($stmt->execute()) {
			//CHANGER ETAT MAINDATA EN NON ASSIGNE APRES EXECUTION
			if ($is_valid == '1') {
				$query = "update t_main_data set deja_assigner=0  where id_=:id_";
				$stmt = $this->connection->prepare($query);
				$stmt->bindValue(":id_", $ref_fiche);
				$stmt->execute();
			}
			return true;
		} else {
			return false;
		}
	}


	function uniqUid($table, $key_fld)
	{
		//uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
		$bytes = md5(mt_rand());
		//Phase 2 verification existance avant retour code
		if ($this->VerifierExistance($key_fld, $bytes, $table)) {
			$bytes = $this->uniqUid($table, $key_fld);
		}
		return $bytes;
		//return substr(bin2hex($bytes),0,$len);
	}

	function VerifierExistance($pKey, $NoGenerated, $table)
	{

		$retour = false;
		$sql = "select $pKey from $table where $pKey='" . $NoGenerated . "'";
		$stmt = $this->connection->prepare($sql);
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

	function CreateAssignControl($POST)
	{
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
			if (!$data_row) {
				$id_assign = $this->uniqUid("t_param_assignation", "id_assign");
				$stmt->bindValue(':id_assign', $id_assign);
				$stmt->bindValue(':id_organe', $this->id_organe);
				$stmt->bindValue(':id_fiche_identif', $value);
				$stmt->bindValue(':datesys', $datesys);
				$stmt->bindValue(':n_user_create', $this->n_user_create);
				$stmt->bindValue(':type_assignation', $this->type_assignation); //categorie service = control (1)
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


	function DispatchingAssignInstall($POST, $technicien)
	{
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

	/*
  function read(){ 
  // $query = "SELECT code,libelle,is_sync FROM " . $this->table_name . " ORDER BY libelle";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  */
	function readAll($from_record_num, $records_per_page, $user_context, $filtre = "")
	{
		$user_filtre = $user_context->GetUserFilterAssignation();
		$user_filtre .= " " . $filtre;

		$query = "SELECT t_main_data.id_,
			t_chef_equipe.nom_complet as nom_chef,
			t_identificateur.nom_complet as noms_identificateur,
			t_main_data.identificateur,
			t_main_data.date_identification,
			t_main_data.p_a,
			t_main_data.gps_longitude,
			t_main_data.gps_latitude,
			Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,
			coalesce(identite_client.phone_number,'-') as phone_client_blue,
			t_main_data.reference_appartement,
			t_main_data.adresse_id,
			t_main_data.cvs_id,
			t_main_data.num_compteur_actuel,
			t_param_assignation.id_assign,
			t_param_assignation.id_organe,
			t_param_assignation.datesys,
			DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,
			t_param_assignation.statut_,
			t_param_assignation.type_assignation,
			t_param_assignation.is_valid,
			t_param_assignation.annule,
			t_param_assignation.id_chef_operation 
		FROM t_main_data  
		INNER JOIN t_param_identite AS identite_client 
			ON t_main_data.client_id = identite_client.id 
		INNER JOIN t_param_assignation 
			ON t_main_data.id_ = t_param_assignation.id_fiche_identif 
		INNER JOIN t_utilisateurs as t_identificateur 
			ON t_main_data.identificateur = t_identificateur.code_utilisateur 
		left JOIN t_utilisateurs as t_chef_equipe 
			ON t_param_assignation.id_chef_operation = t_chef_equipe.code_utilisateur 
		where t_main_data.ref_site_identif=:ref_site_identif 
			and t_param_assignation.annule=0 
			and t_param_assignation.type_assignation=:type_assignation   " . $user_filtre .
			" ORDER BY t_param_assignation.datesys  DESC LIMIT {$from_record_num}, {$records_per_page}";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->execute();
		return $stmt;
	}
	public function search($du, $au, $search_term, $from_record_num, $records_per_page, $user_context, $filtre = "")
	{
		// $query = "SELECT code,libelle,is_sync,annule  FROM " . $this->table_name  . " WHERE libelle LIKE :search_term  ORDER BY libelle ASC LIMIT :from, :offset";
		$user_filtre = $user_context->GetUserFilterAssignation();
		$user_filtre .= " " . $filtre;

		$query = "SELECT t_main_data.id_,
				t_chef_equipe.nom_complet as nom_chef,
				t_identificateur.nom_complet as noms_identificateur,
				t_main_data.identificateur,
				t_main_data.date_identification,
				t_main_data.p_a,
				t_main_data.reference_appartement,
				t_main_data.gps_longitude,
				t_main_data.gps_latitude,
				Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,
				coalesce(identite_client.phone_number,'-') as phone_client_blue,
				t_main_data.adresse_id,
				t_main_data.cvs_id,
				t_main_data.num_compteur_actuel,
				t_param_assignation.id_assign,
				t_param_assignation.id_organe,
				t_param_assignation.datesys,
				DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,
				t_param_assignation.statut_,
				t_param_assignation.type_assignation,
				t_param_assignation.is_valid,
				t_param_assignation.annule,
				t_param_assignation.id_chef_operation 
			FROM t_main_data  
				INNER JOIN t_param_identite AS identite_client 
					ON t_main_data.client_id = identite_client.id 
				INNER JOIN t_param_assignation 
					ON t_main_data.id_ = t_param_assignation.id_fiche_identif 
				INNER JOIN t_log_adresses 
					ON t_main_data.adresse_id = t_log_adresses.id 
				INNER JOIN t_param_adresse_entity AS e_quartier 
					ON t_log_adresses.quartier_id = e_quartier.`code` 
				INNER JOIN t_param_adresse_entity AS e_commune  
					ON t_log_adresses.commune_id = e_commune.`code`  
				INNER JOIN t_param_adresse_entity AS e_avenue 
					ON t_log_adresses.avenue = e_avenue.`code` 
				INNER JOIN t_utilisateurs as t_identificateur 
					ON t_main_data.identificateur = t_identificateur.code_utilisateur 
				LEFT JOIN t_utilisateurs as t_chef_equipe 
					ON t_param_assignation.id_chef_operation = t_chef_equipe.code_utilisateur 
				WHERE (
						t_main_data.ref_site_identif=:ref_site_identif 
						and t_param_assignation.annule=0 
						and t_param_assignation.type_assignation=:type_assignation
					) 
					and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)  
					and (num_compteur_actuel LIKE :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) 
					LIKE :search_term OR identite_client.phone_number 
					LIKE :search_term or  t_chef_equipe.nom_complet 
					LIKE :search_term or  t_identificateur.nom_complet 
					LIKE :search_term or e_avenue.libelle 
					Like :search_term or e_quartier.libelle 
					Like :search_term or e_commune.libelle 
					Like :search_term)   " . $user_filtre .
			" ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";

		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		return $stmt;
	}


	public function countAll_BySearch($du, $au, $search_term, $user_context, $filtre = "")
	{
		$user_filtre = $user_context->GetUserFilterAssignation();
		$user_filtre .= " " . $filtre;
		$query = "SELECT COUNT(*) as total_rows   
			FROM t_main_data  
				INNER JOIN t_param_identite AS identite_client 
					ON t_main_data.client_id = identite_client.id 
				INNER JOIN t_param_assignation 
					ON t_main_data.id_ = t_param_assignation.id_fiche_identif 
				INNER JOIN t_log_adresses 
					ON t_main_data.adresse_id = t_log_adresses.id 
				INNER JOIN t_param_adresse_entity AS e_quartier 
					ON t_log_adresses.quartier_id = e_quartier.`code` 
				INNER JOIN t_param_adresse_entity AS e_commune  
					ON t_log_adresses.commune_id = e_commune.`code`  
				INNER JOIN t_param_adresse_entity AS e_avenue 
					ON t_log_adresses.avenue = e_avenue.`code` 
				INNER JOIN t_utilisateurs as t_identificateur 
					ON t_main_data.identificateur = t_identificateur.code_utilisateur 
				left JOIN t_utilisateurs as t_chef_equipe 
					ON t_param_assignation.id_chef_operation = t_chef_equipe.code_utilisateur 
				where (t_main_data.ref_site_identif=:ref_site_identif 
					and t_param_assignation.annule=0 
					and t_param_assignation.type_assignation=:type_assignation) 
					and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)  
					and (t_main_data.num_compteur_actuel 
					LIKE :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) 
					LIKE :search_term or identite_client.phone_number 
					LIKE :search_term or  t_chef_equipe.nom_complet 
					LIKE :search_term or  t_identificateur.nom_complet 
					LIKE :search_term or e_avenue.libelle 
					Like :search_term or e_quartier.libelle 
					Like :search_term or e_commune.libelle 
					Like :search_term)   " . $user_filtre . " ";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(":search_term", $search_term);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row["total_rows"];
	}


	public function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $user_context)
	{

		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "SELECT t_main_data.id_,
			t_utilisateurs.nom_complet,
			t_main_data.date_identification,
			t_main_data.p_a,
			t_main_data.gps_longitude,
			t_main_data.gps_latitude,
			Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,
			coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,
			t_main_data.cvs_id,
			t_main_data.num_compteur_actuel,
			t_param_assignation.id_assign,
			t_param_assignation.id_organe,
			t_param_assignation.datesys,
			DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,
			t_param_assignation.statut_,
			t_param_assignation.type_assignation,
			t_param_assignation.is_valid,
			t_param_assignation.annule,
			t_param_assignation.id_chef_operation 
		FROM t_main_data  
			INNER JOIN t_param_identite AS identite_client 
				ON t_main_data.client_id = identite_client.id 
			INNER JOIN t_param_assignation 
				ON t_main_data.id_ = t_param_assignation.id_fiche_identif  
			where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=:type_assignation) 
			and (num_compteur_actuel LIKE :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) 
			LIKE :search_term or identite_client.phone_number 
			LIKE :search_term) and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)   " . $user_filtre
			. " ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";


		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		return $stmt;
	}
	public function search_advanced_DateOnly($du, $au,  $from_record_num, $records_per_page, $user_context)
	{

		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_assignation.id_chef_operation FROM t_main_data  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=:type_assignation) and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)   " . $user_filtre . " ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";

		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		return $stmt;
	}

	public function countAll($user_context, $filtre = "")
	{

		$user_filtre = $user_context->GetUserFilterAssignation();
		$user_filtre .= " " . $filtre;
		$query = "SELECT t_main_data.id_  FROM t_main_data  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif INNER JOIN t_utilisateurs as t_identificateur ON t_main_data.identificateur = t_identificateur.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.identificateur = t_chef_equipe.code_utilisateur where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.type_assignation=:type_assignation   " . $user_filtre . "";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}
	public function countAll_BySearch_advanced($du, $au, $search_term, $user_context)
	{
		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "SELECT COUNT(*) as total_rows  FROM t_main_data  
			INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
			INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  
			where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=:type_assignation) and (num_compteur_actuel LIKE :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) LIKE :search_term or identite_client.phone_number LIKE :search_term)  and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)  " . $user_filtre . " ";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(":search_term", $search_term);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["total_rows"];
	}
	public function countAll_BySearch_advanced_DateOnly($du, $au, $user_context)
	{
		$user_filtre = $user_context->GetUserFilterAssignation();
		$query = "SELECT COUNT(*) as total_rows  FROM t_main_data  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=:type_assignation)  and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)  " . $user_filtre . " ";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_identif", $user_context->site_id);
		$stmt->bindValue(":type_assignation", $this->type_assignation);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["total_rows"];
	}
}
