<?php

class CLS_Reporting
{

	private $connection;

	public function __construct($db)
	{
		$this->connection = $db;
	}


	function GetAll_AccessibleUSerSite($id_user)
	{
		$liste_site = array();
		$query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
		$code_user = (strip_tags($id_user));
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":code_user", $id_user);
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$liste_site[] = $row['code_site'];
		}
		return $liste_site;
	}


	function GetAll_Site_CVSList($site)
	{
		$site = (strip_tags($site));
		$query = "SELECT t_param_cvs.code,t_param_cvs.libelle,t_param_cvs.annule,t_param_cvs.code_province,t_param_cvs.id_site,t_param_cvs.activated FROM t_param_cvs WHERE t_param_cvs.id_site=:site  ORDER BY t_param_cvs.libelle";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(":site", $site);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}



	public function getCVS_CompteursReplaceDefectueux($cvs, $du, $au)
	{
		$query = "SELECT t_log_installation.id_install,DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y') AS date_fin_installation_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,t_main_data.est_installer,t_log_installation.marque_cpteur_post_paie,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d'),'%d/%m/%Y') as date_retrait_cpteur_post_paie_fr,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.num_serie_cpteur_replaced,t_log_installation.marque_compteur,t_log_installation.numero_compteur,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_log_installation.type_installation,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_pose_scelle,'%Y-%m-%d'),'%d/%m/%Y') as date_pose_scelle_fr,t_log_installation.statut_installation,t_log_installation.date_pose_scelle,t_log_installation.marque_cpteur_replaced,t_log_installation.installateur,t_log_installation.id_equipe FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_log_installation.type_installation=1 and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and coalesce(t_log_installation.num_serie_cpteur_replaced,'')!=''";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}

	public function getCVS_CompteursInstall($cvs, $du, $au)
	{
		$query = "SELECT t_log_installation.id_install,DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y') AS date_fin_installation_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,t_main_data.est_installer,t_log_installation.marque_cpteur_post_paie,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d'),'%d/%m/%Y') as date_retrait_cpteur_post_paie_fr,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.num_serie_cpteur_replaced,t_log_installation.marque_compteur,t_log_installation.numero_compteur,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_log_installation.type_installation,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_pose_scelle,'%Y-%m-%d'),'%d/%m/%Y') as date_pose_scelle_fr,t_log_installation.statut_installation,t_log_installation.date_pose_scelle,t_log_installation.marque_cpteur_replaced,t_log_installation.installateur,t_log_installation.id_equipe FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation in ('0','1')";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}



	public function getChiefTechnician($chief_id)
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.chef_equipe_id FROM t_utilisateurs where  t_utilisateurs.chef_equipe_id=:chef";

		$st = $this->connection->prepare($query);
		$st->bindValue(":chef", $chief_id);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}


	public function getSite_CompteursControlUser($user, $site_id, $du, $au)
	{
		$query = "SELECT DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y') AS date_controle_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_main_data.est_installer,t_main_data.gps_longitude,t_main_data.gps_latitude,t_log_controle.ref_fiche_controle,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.type_cpteur,t_log_controle.credit_restant,t_log_controle.cas_de_fraude,t_log_controle.client_reconnait_pas,t_log_controle.autocollant_trouver,t_log_controle.etat_fraude,t_log_controle.scelle_compteur_poser,t_log_controle.scelle_coffret_poser,t_log_controle.scelle_cpt_existant,t_log_controle.scelle_coffret_existant,t_log_controle.raison_fraude,t_log_controle.categorie_de_vente,t_log_controle.etat_du_compteur,t_log_controle.date_de_dernier_ticket_rentre,t_log_controle.qte_derniers_kwh_rentre,t_log_controle.tarif_controle,t_log_controle.autocollant_place_controleur,t_log_controle.type_fraude,t_log_controle.observation,t_log_controle.date_controle,t_log_controle.tarif_controle,t_log_controle.ref_last_install_found FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification INNER JOIN t_param_identite AS identite_client ON t_main_data.occupant_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.controleur = :user and t_main_data.ref_site_identif = :site_id and t_main_data.annule = :annule and t_log_controle.annule = :annule";

		$st = $this->connection->prepare($query);
		$st->bindValue(":user", $user);
		$st->bindValue(":site_id", $site_id);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}


	public function getSite_CompteursControlPeriodeCountUser($user, $site_id, $du, $au)
	{
		$query = "SELECT  Count(*) AS nbre FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification INNER JOIN t_param_identite AS identite_client ON t_main_data.occupant_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.controleur = :user  and t_main_data.ref_site_identif= :site_id and t_main_data.annule = :annule and t_log_controle.annule = :annule";
		$st = $this->connection->prepare($query);
		$st->bindValue(":user", $user);
		$st->bindValue(":site_id", $site_id);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}



	public function getSite_CompteursFraudePeriodeCountUser($user, $site_id, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.controleur = :user  and t_main_data.ref_site_identif= :site_id and t_main_data.annule = :annule and t_log_controle.annule = :annule and  t_log_controle.cas_de_fraude='Oui'";
		// and coalesce(t_log_controle.type_fraude,'') !=''";		
		$st = $this->connection->prepare($query);
		$st->bindValue(":user", $user);
		$st->bindValue(":site_id", $site_id);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}


	public function getSite_CompteursInstallUser($user, $site_id, $du, $au)
	{
		$query = "SELECT t_log_installation.id_install,DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y') AS date_fin_installation_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,t_main_data.est_installer,t_log_installation.marque_cpteur_post_paie,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d'),'%d/%m/%Y') as date_retrait_cpteur_post_paie_fr,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.num_serie_cpteur_replaced,t_log_installation.marque_compteur,t_log_installation.numero_compteur,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_log_installation.type_installation,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_pose_scelle,'%Y-%m-%d'),'%d/%m/%Y') as date_pose_scelle_fr,t_log_installation.statut_installation,t_log_installation.date_pose_scelle,t_log_installation.marque_cpteur_replaced,t_log_installation.installateur,t_log_installation.id_equipe FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_log_installation.installateur = :user and t_main_data.ref_site_identif= :site_id and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation in ('0','1')";

		$st = $this->connection->prepare($query);
		$st->bindValue(":user", $user);
		$st->bindValue(":site_id", $site_id);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}

public function getBatchCVSScelleCounts($cvs_list)
{
    // Collecter tous les codes cvs
    $cvs_codes = array_column($cvs_list, 'code');
    
    // Préparer la requête pour le traitement par lots
    $query = "
        SELECT t_main_data.cvs_id, 
               SUM(CASE 
                    WHEN (LENGTH(COALESCE(scelle_un_cpteur, '')) > 0 AND LENGTH(COALESCE(scelle_deux_coffret, '')) > 0) THEN 2
                    WHEN (LENGTH(COALESCE(scelle_un_cpteur, '')) > 0 OR LENGTH(COALESCE(scelle_deux_coffret, '')) > 0) THEN 1
                    ELSE 0
               END) AS nbre_scelle
        FROM t_log_installation
        INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_
        INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id
        WHERE t_main_data.cvs_id IN (" . implode(",", array_fill(0, count($cvs_codes), "?")) . ")
        AND t_main_data.annule = ?
        AND t_log_installation.statut_installation = 1 
        AND t_log_installation.is_draft_install = 0 
        AND t_log_installation.type_installation IN ('0', '1')
        GROUP BY t_main_data.cvs_id";
    
    // Préparer la requête
    $st = $this->connection->prepare($query);
    
    // Lier les valeurs pour les codes cvs
    foreach ($cvs_codes as $index => $cvs_code) {
        $st->bindValue($index + 1, $cvs_code);
    }
    
    // Lier la valeur pour le paramètre annule
    $st->bindValue(count($cvs_codes) + 1, Utils::$Valid);
    
    $st->execute();
    
    // Récupérer les résultats
    $result = $st->fetchAll(PDO::FETCH_ASSOC);
    
    // Retourner les résultats sous forme d'un tableau associatif par cvs_id
    return array_column($result, 'nbre_scelle', 'cvs_id');
}


public function getCVS_CompteursInstallAndReplacePeriodeCount($cvs, $du, $au)
{
    $query = "SELECT 
                 SUM(CASE WHEN t_log_installation.type_installation = 0 THEN 1 ELSE 0 END) AS nbre_install,
                 SUM(CASE WHEN t_log_installation.type_installation = 1 THEN 1 ELSE 0 END) AS nbre_replace
              FROM t_log_installation
              INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
              WHERE t_log_installation.date_fin_installation BETWEEN :du AND :au
                AND t_main_data.cvs_id = :id_cvs
                AND t_main_data.annule = :annule
                AND t_log_installation.statut_installation = 1
                AND t_log_installation.is_draft_install = 0";

    $st = $this->connection->prepare($query);
    $st->bindValue(":id_cvs", $cvs);
    $st->bindValue(":du", $du);
    $st->bindValue(":au", $au);
    $st->bindValue(":annule", Utils::$Valid);
    $st->execute();

    return $st->fetch(PDO::FETCH_ASSOC);
}



	public function getCVS_CompteursInstallALLEnPlaceCount($cvs)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where  t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_main_data.num_compteur_actuel = t_log_installation.numero_compteur";
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}

	public function getCVS_AssignationControllesCount($cvs, $du, $au)
	{
		$query =
			"SELECT COUNT(*) as nombre  
			FROM t_main_data  
			INNER JOIN t_param_identite AS identite_client 
				ON t_main_data.client_id = identite_client.id 
			INNER JOIN t_param_assignation 
				ON t_main_data.id_ = t_param_assignation.id_fiche_identif  
			where ( t_param_assignation.annule=0  and t_param_assignation.statut_=1
				and t_param_assignation.type_assignation=1)  
				and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  
			between :du and :au)   AND 
			t_main_data.cvs_id = :id_cvs  ";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nombre"];
	}

	public function getCVS_CompteursInstallAndScelleCount($cvs)
{
    $query = "SELECT 
                 COUNT(*) AS nbre_install,
                 SUM(
                     CASE 
                         WHEN (IFNULL(LENGTH(scelle_un_cpteur), 0) > 0 AND IFNULL(LENGTH(scelle_deux_coffret), 0) > 0) THEN 2
                         WHEN (IFNULL(LENGTH(scelle_un_cpteur), 0) > 0 OR IFNULL(LENGTH(scelle_deux_coffret), 0) > 0) THEN 1
                         ELSE 0
                     END
                 ) AS nbre_scelle
             FROM t_log_installation
             INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
             INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id
             WHERE t_main_data.cvs_id = :id_cvs
               AND t_main_data.annule = :annule
               AND t_log_installation.statut_installation = 1
               AND t_log_installation.is_draft_install = 0
               AND t_main_data.num_compteur_actuel = t_log_installation.numero_compteur
               AND t_log_installation.type_installation IN ('0', '1')";

    $st = $this->connection->prepare($query);
    $st->bindValue(":id_cvs", $cvs);
    $st->bindValue(":annule", Utils::$Valid);
    $st->execute();

    return $st->fetch(PDO::FETCH_ASSOC);
}
	public function getCVS_AssignationNonControllesCount($cvs, $du, $au)
	{
		$query =
			"SELECT COUNT(*) as nombre  
			FROM t_main_data  
			INNER JOIN t_param_identite AS identite_client 
				ON t_main_data.client_id = identite_client.id 
			INNER JOIN t_param_assignation 
				ON t_main_data.id_ = t_param_assignation.id_fiche_identif  
			where ( t_param_assignation.annule=0 and 
				(t_param_assignation.statut_=0) 
				and t_param_assignation.type_assignation=1)  
				and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  
			between :du and :au)   AND 
			t_main_data.cvs_id = :id_cvs  ";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		// $st->bindValue(":annule", Utils::$Valid);
		$st->execute();

		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nombre"];
	}
	public function getCVS_IdentificationCount($cvs, $du, $au)
	{
		$query = "SELECT Count(*) as nombre FROM  t_main_data 
			INNER JOIN t_param_cvs 
				ON t_param_cvs.`code` = t_main_data.cvs_id 
			INNER JOIN t_log_adresses 
				ON t_main_data.adresse_id = t_log_adresses.id 
			INNER JOIN t_param_adresse_entity AS e_quartier 
				ON t_log_adresses.quartier_id = e_quartier.`code` 
			INNER JOIN t_param_adresse_entity AS e_commune  
				ON t_log_adresses.commune_id = e_commune.`code` 
			INNER JOIN t_param_adresse_entity AS e_ville 
				ON t_log_adresses.ville_id = e_ville.`code` 
			INNER JOIN t_utilisateurs 
				ON t_main_data.identificateur = t_utilisateurs.code_utilisateur 
			INNER JOIN t_utilisateurs as t_chef_equipe 
				ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  
			INNER JOIN t_param_organisme 
				ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
			INNER JOIN t_param_adresse_entity AS e_avenue 
				ON t_log_adresses.avenue = e_avenue.`code` 
			INNER JOIN t_param_identite AS identite_client 
				ON t_main_data.client_id = identite_client.id  
			WHERE 
			(DATE_FORMAT(date_fin_identification,'%Y-%m-%d')  
		between :du and :au) AND 
			t_main_data.cvs_id = :id_cvs  
				AND t_main_data.annule= :annule";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nombre"];
	}

	public function getCVS_CompteursInstallPeriodeCount($cvs, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
		where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  
		between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule 
		and t_log_installation.statut_installation=1 
		and t_log_installation.is_draft_install=0 
		and t_log_installation.type_installation=0"; //Install	
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}

	public function getSite_CompteursInstallPeriodeCountUser($user, $site, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_main_data.ref_site_identif= :site_id and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=0 and t_log_installation.installateur=:user"; //Install	
		$st = $this->connection->prepare($query);
		$st->bindValue(":site_id", $site);
		$st->bindValue(":user", $user);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}

	public function getSite_CompteursReplacePeriodeCountUser($user, $site, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_main_data.ref_site_identif= :site_id and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=1 and t_log_installation.installateur=:user"; //Install	
		$st = $this->connection->prepare($query);
		$st->bindValue(":site_id", $site);
		$st->bindValue(":user", $user);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}

	public function getCVS_CompteursPostPeriodeCount($cvs, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=0 and LENGTH(trim(coalesce(t_log_installation.num_serie_cpteur_post_paie,'')))>0 and t_log_installation.post_paie_trouver='Oui'"; //Install	

		//(DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and 
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}

	public function getCVS_CompteursScelleALLPeriodeCount($cvs)
	{
		$query = "SELECT Sum(case when (LENGTH(COALESCE(scelle_un_cpteur,''))>0 and LENGTH(COALESCE(scelle_deux_coffret,''))>0 ) then 2
else (case when (LENGTH(COALESCE(scelle_un_cpteur,''))>0 Or LENGTH(COALESCE(scelle_deux_coffret,''))>0 ) then 1
else 0 end) end) as nbre_scelle FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation in ('0','1')";


		/*	$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=0 and LENGTH(trim(coalesce(t_log_installation.num_serie_cpteur_post_paie,'')))>0 and t_log_installation.post_paie_trouver='Oui'";//Install	
		*/

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre_scelle"];
	}


	public function getCVS_CompteursScellePeriodeCount($cvs, $du, $au)
	{
		$query = "SELECT Sum(case when (LENGTH(COALESCE(scelle_un_cpteur,''))>0 and LENGTH(COALESCE(scelle_deux_coffret,''))>0 ) then 2
else (case when (LENGTH(COALESCE(scelle_un_cpteur,''))>0 Or LENGTH(COALESCE(scelle_deux_coffret,''))>0 ) then 1
else 0 end) end) as nbre_scelle FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_pose_scelle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation in ('0','1')";


		/*	$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=0 and LENGTH(trim(coalesce(t_log_installation.num_serie_cpteur_post_paie,'')))>0 and t_log_installation.post_paie_trouver='Oui'";//Install	
		*/

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre_scelle"];
	}

	public function getCVS_CompteursReplaceDefectueuxPeriodeCount($cvs, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=1 and coalesce(t_log_installation.num_serie_cpteur_replaced,'')!=''"; //Replace	
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}

	public function getCVS_CompteursReplacePeriodeCount($cvs, $du, $au)
	{
		$query = "SELECT Count(*) AS nbre FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
		INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and t_log_installation.is_draft_install=0 and t_log_installation.type_installation=1"; //Replace	
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}




	public function getCVS_CompteursControlPeriodeCount($cvs, $du, $au)
	{
		$query = "SELECT  Count(*) AS nbre FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification INNER JOIN t_param_identite AS identite_client ON t_main_data.occupant_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_controle.annule = :annule";
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}



	public function getCVS_CompteursFraudePeriodeCount($cvs, $du, $au)
	{
		// $query = "SELECT Count(*) AS nbre FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_controle.annule = :annule and coalesce(t_log_controle.type_fraude,'') !=''";		
		$query = "SELECT Count(*) AS nbre FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_controle.annule = :annule and  t_log_controle.cas_de_fraude='Oui'";
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetch(PDO::FETCH_ASSOC);
		return	$result["nbre"];
	}



	public function getCVS_CompteursScellerALL($cvs, $du, $au)
	{
		$query = "SELECT DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y') AS date_fin_installation_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,t_main_data.est_installer,t_log_installation.marque_cpteur_post_paie,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d'),'%d/%m/%Y') as date_retrait_cpteur_post_paie_fr,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.num_serie_cpteur_replaced,t_log_installation.marque_compteur,t_log_installation.numero_compteur,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_log_installation.type_installation,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_pose_scelle,'%Y-%m-%d'),'%d/%m/%Y') as date_pose_scelle_fr,t_log_installation.statut_installation,t_log_installation.date_pose_scelle,t_log_installation.marque_cpteur_replaced FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id
 		where (DATE_FORMAT(t_log_installation.date_pose_scelle,'%Y-%m-%d')  <= :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and (length(trim(t_log_installation.scelle_un_cpteur))>0 or length(trim(t_log_installation.scelle_deux_coffret))>0) ";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		// $st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}

	public function getCVS_CompteursSceller($cvs, $du, $au)
	{
		$query = "SELECT DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y') AS date_fin_installation_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,t_main_data.est_installer,t_log_installation.marque_cpteur_post_paie,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d'),'%d/%m/%Y') as date_retrait_cpteur_post_paie_fr,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.num_serie_cpteur_replaced,t_log_installation.marque_compteur,t_log_installation.numero_compteur,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_log_installation.type_installation,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_pose_scelle,'%Y-%m-%d'),'%d/%m/%Y') as date_pose_scelle_fr,t_log_installation.statut_installation,t_log_installation.date_pose_scelle,t_log_installation.marque_cpteur_replaced FROM t_log_installation
		INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ 
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id
 		where (DATE_FORMAT(t_log_installation.date_pose_scelle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_installation.statut_installation=1 and (length(trim(t_log_installation.scelle_un_cpteur))>0 or length(trim(t_log_installation.scelle_deux_coffret))>0) ";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}


	public function getCVS_CompteursControl($cvs, $du, $au)
	{
		$query = "SELECT t_log_controle.controleur,t_log_controle.diagnostics_general,t_log_controle.consommation_de_30jours_actuels,t_log_controle.valeur_du_dernier_ticket,DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y') AS date_controle_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_main_data.est_installer,t_main_data.gps_longitude,t_main_data.gps_latitude,t_log_controle.ref_fiche_controle,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.type_cpteur,t_log_controle.credit_restant,t_log_controle.cas_de_fraude,t_log_controle.client_reconnait_pas,t_log_controle.autocollant_trouver,t_log_controle.etat_fraude,t_log_controle.scelle_compteur_poser,t_log_controle.scelle_coffret_poser,t_log_controle.scelle_cpt_existant,t_log_controle.scelle_coffret_existant,t_log_controle.raison_fraude,t_log_controle.categorie_de_vente,t_log_controle.etat_du_compteur,t_log_controle.date_de_dernier_ticket_rentre,t_log_controle.qte_derniers_kwh_rentre,t_log_controle.tarif_controle,t_log_controle.autocollant_place_controleur,t_log_controle.type_fraude,t_log_controle.observation,t_log_controle.date_controle,t_log_controle.tarif_controle,t_log_controle.ref_last_install_found FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification INNER JOIN t_param_identite AS identite_client ON t_main_data.occupant_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_controle.annule = :annule Order By t_log_controle.date_controle ASC";

		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}

	public function getCVS_CompteursControlALL_Client($ref_fiche_identif)
	{
		$query = "SELECT t_log_controle.controleur,DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y') AS date_controle_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_main_data.est_installer,t_main_data.gps_longitude,t_main_data.gps_latitude,t_log_controle.ref_fiche_controle,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.type_cpteur,t_log_controle.credit_restant,t_log_controle.cas_de_fraude,t_log_controle.client_reconnait_pas,t_log_controle.autocollant_trouver,t_log_controle.etat_fraude,t_log_controle.scelle_compteur_poser,t_log_controle.scelle_coffret_poser,t_log_controle.scelle_cpt_existant,t_log_controle.scelle_coffret_existant,t_log_controle.raison_fraude,t_log_controle.categorie_de_vente,t_log_controle.etat_du_compteur,t_log_controle.date_de_dernier_ticket_rentre,t_log_controle.qte_derniers_kwh_rentre,t_log_controle.tarif_controle,t_log_controle.autocollant_place_controleur,t_log_controle.type_fraude,t_log_controle.observation,t_log_controle.date_controle,t_log_controle.tarif_controle,t_log_controle.ref_last_install_found FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification INNER JOIN t_param_identite AS identite_client ON t_main_data.occupant_id = identite_client.id where t_main_data.id_ = :ref_fiche_identif";

		$st = $this->connection->prepare($query);
		$st->bindValue(":ref_fiche_identif", $ref_fiche_identif);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}


	public function getCVS_CompteursFraude($cvs, $du, $au)
	{
		$query = "SELECT DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y') AS date_controle_fr,t_main_data.id_,t_main_data.p_a,t_main_data.client_id,t_main_data.occupant_id,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,t_main_data.cvs_id,t_main_data.ref_site_identif,t_main_data.tarif_identif,t_main_data.adresse_id,t_main_data.est_installer,t_main_data.gps_longitude,t_main_data.gps_latitude,t_log_controle.ref_fiche_controle,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.type_cpteur,t_log_controle.credit_restant,t_log_controle.cas_de_fraude,t_log_controle.client_reconnait_pas,t_log_controle.autocollant_trouver,t_log_controle.etat_fraude,t_log_controle.scelle_compteur_poser,t_log_controle.scelle_coffret_poser,t_log_controle.scelle_cpt_existant,t_log_controle.scelle_coffret_existant,t_log_controle.raison_fraude,t_log_controle.categorie_de_vente,t_log_controle.etat_du_compteur,t_log_controle.date_de_dernier_ticket_rentre,t_log_controle.qte_derniers_kwh_rentre,t_log_controle.tarif_controle,t_log_controle.autocollant_place_controleur,t_log_controle.type_fraude,t_log_controle.observation,t_log_controle.date_controle,t_log_controle.tarif_controle,t_log_controle.ref_last_install_found FROM t_main_data INNER JOIN t_log_controle ON t_main_data.id_ = t_log_controle.ref_fiche_identification  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_main_data.cvs_id = :id_cvs and t_main_data.annule = :annule and t_log_controle.annule = :annule and t_log_controle.cas_de_fraude='Oui'";

		// coalesce(t_log_controle.type_fraude,'') !=''";		
		$st = $this->connection->prepare($query);
		$st->bindValue(":id_cvs", $cvs);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", Utils::$Valid);
		$st->execute();
		$result = $st->fetchAll(PDO::FETCH_ASSOC);
		return	$result;
	}
}