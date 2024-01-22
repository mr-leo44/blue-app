<?php
class CLS_Controle
{
	//Lorqu'on fait le controle recuperer le dernier ref_install et stocker pour le control for reporting 
	// database connection and table name
	private $connection;
	private $table_name = "t_log_controle";

	private $is_valid = 0;
	/*
	public $id_install;
	public $ref_identific;
	public $date_debut_installation;
	public $date_fin_installation;
	public $p_a;
	public $nom_installateur;
	public $id_equipe;
	public $nom_equipe;
	public $numero_compteur;
	public $photo_compteur;
	public $marque_compteur;
	public $commentaires;
	public $datesys;
	public $date_update;
	public $site_id;
*/
	public $ref_fiche_controle;
	public $ref_fiche_identification;
	public $gps_longitude_control;
	public $gps_latitude_control;
	public $refus_access;
	public $par_wifi_cpl;
	public $cpteur_present;
	public $photo_compteur;
	public $photo_pa;
	public $numero_serie_cpteur;
	public $marque_compteur;
	public $marque_autre;
	public $type_cpteur;
	public $clavier_deporter;
	public $type_raccordement;
	public $nbre_arrived;
	public $section_cable_arrived;
	public $num_photo_cpteur;
	public $num_photo_raccord;
	public $possibility_fraud_expliquer;
	public $etat_interrupteur;
	public $credit_restant;
	public $indicateur_led;
	public $cas_de_fraude;
	public $client_reconnait_pas;
	public $autocollant_trouver;
	public $diagnostics_general;
	public $avis_client;
	public $noms_equipe_blue;
	public $noms_equipe_snel;
	public $date_controle;
	public $etat_fraude;
	public $lst_fraudes;
	public $lst_observations;
	public $scelle_compteur_poser;
	public $scelle_coffret_poser;
	public $scelle_cpt_existant;
	public $scelle_coffret_existant;
	public $compteur_arrache;
	public $raison_fraude;
	public $consommation_journaliere;
	public $categorie_de_vente;
	public $penalite_brise_scelle;
	public $penalite_anti_fraude;
	public $etat_du_compteur;
	public $date_de_dernier_ticket_rentre;
	public $qte_derniers_kwh_rentre;
	public $credit_restant1;
	public $tarif_controle;
	public $autocollant_place_controleur;
	public $controleur;
	public $nom_deuxième_controleur;
	public $nbre_de_jour_fraude;
	public $energie_recuperer_kwh;
	public $montant_fraude_a_recuperer;
	public $montant_total_penalite_en_franc;
	public $montant_total_penalite_en_dollar;
	public $remarque;
	public $compteur_disparu_apres_installation;
	public $n_user_create;
	public $datesys;
	public $n_user_update;
	public $date_update;
	public $n_user_annule;
	public $annule;
	public $date_annule;
	public $motif_annule;
	public $ref_site_controle;
	public $presence_inverseur;
	public $id_organisme_control;
	public $observation;
	public $cvs_id;
	public $id_assign;
	public $typ_conclusion;

	public $consommation_de_30jours_actuels;
	public $consommation_de_30jours_precedents;
	public $valeur_du_dernier_ticket;
	public $index_de_tarif_du_compteur;
	public $is_draft_control;



	public $sceller_identique;
	public $dernier_sceller_compteur;
	public $dernier_sceller_coffret;


	public function __construct($db)
	{
		$this->connection = $db;
	}

	function Supprimer()
	{
		$query = "UPDATE " . $this->table_name . " SET annule='1',motif_annule=:motif_annule,n_user_annule=:n_user_annule  WHERE ref_fiche_controle=:ref_fiche_controle";
		$stmt = $this->connection->prepare($query);
		$this->ref_fiche_controle = (strip_tags($this->ref_fiche_controle));
		$stmt->bindParam(":ref_fiche_controle", $this->ref_fiche_controle);
		$stmt->bindParam(":n_user_annule", $this->n_user_update);
		$stmt->bindParam(":motif_annule", $this->motif_annule);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}



	function CreateTemporaire($user_context, $detail)
	{
		$result = array();
		$item_site = new SiteProduction($this->connection);
		$item_site->code_site = $user_context->site_id;
		$_jiko_item = $item_site->GetDetail();
		$generer = new Generateur($this->connection, TRUE);
		$generer->has_signature = TRUE;
		$generer->Signature_fld = 'signature_id';
		$generer->Signature_Value = $_jiko_item["site_short_code"];
		$uuid = $generer->getUID('generateur_main', 'num_control', 'Y', 't_log_controle', 'ref_fiche_controle');

		// is_draft
		//Creation d'un Identifiant lors de l'aperçu du formulaire
		$query = " INSERT INTO t_log_controle SET ref_fiche_controle=:ref_fiche_controle,n_user_create=:n_user_create,datesys=now(),ref_site_controle=:ref_site_controle";
		$stmt = $this->connection->prepare($query);

		$stmt->bindParam(":ref_fiche_controle", $uuid);
		$stmt->bindParam(":n_user_create", $this->n_user_create);
		$stmt->bindParam(":ref_site_controle", $user_context->site_id);
		if ($stmt->execute()) {
			$result["error"] = 0;
			$result["uid"] = $uuid;
			$result["detail"] = $detail;
		} else {
			$result["error"] = 1;
			$result["message"] = 'Echec de la préparation du Formulaire';
		}
		return $result;
	}

	function CreateWeb()
	{
		$ref_last_log_install = '';
		$ref_preview_control_found = '';
		$datesys = date('Y-m-d H:i:s');
		$adress_Ent = new AdresseEntity($this->connection);
		//EVITER DUPLICATE COMPTEUR



		//EVITER DUPLICATE SCELLE 






		//RECUPERATION REF_LAST_INSTALL LOG AND REF_LAST_LOG_CONTROL
		$query = "SELECT id_,ref_installation_actuel,cvs_id,client_id,occupant_id,adresse_id,ref_dernier_log_controle FROM t_main_data where id_=:id_";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":id_", $this->ref_fiche_identification);
		$stmt->execute();
		$row_log = $stmt->fetch(PDO::FETCH_ASSOC);
		$ref_last_log_install = $row_log['ref_installation_actuel'];
		$ref_preview_control_found = $row_log['ref_dernier_log_controle'];
		$cvs_controler = $row_log['cvs_id'];


		$client_info = $adress_Ent->GetMenageDetail($row_log['client_id']);
		$client_controler = $client_info['noms'];
		$adresse_controler = $row_log['adresse_id'];
		///////

		$query = "INSERT INTO " . $this->table_name . " SET ref_fiche_controle=:ref_fiche_controle,ref_fiche_identification=:ref_fiche_identification,presence_inverseur=:presence_inverseur,numero_serie_cpteur=:numero_serie_cpteur,marque_compteur=:marque_compteur,type_cpteur=:type_cpteur,clavier_deporter=:clavier_deporter,scelle_cpt_existant=:scelle_cpt_existant,scelle_coffret_existant=:scelle_coffret_existant,scelle_compteur_poser=:scelle_compteur_poser,scelle_coffret_poser=:scelle_coffret_poser,type_raccordement=:type_raccordement,nbre_arrived=:nbre_arrived,section_cable_arrived=:section_cable_arrived,par_wifi_cpl=:par_wifi_cpl,num_photo_cpteur=:num_photo_cpteur,num_photo_raccord=:num_photo_raccord,possibility_fraud_expliquer=:possibility_fraud_expliquer,gps_latitude_control=:gps_latitude_control,gps_longitude_control=:gps_longitude_control,etat_interrupteur=:etat_interrupteur,credit_restant=:credit_restant,indicateur_led=:indicateur_led,cas_de_fraude=:cas_de_fraude,client_reconnait_pas=:client_reconnait_pas,type_fraude=:type_fraude,autocollant_place_controleur=:autocollant_place_controleur,autocollant_trouver=:autocollant_trouver,diagnostics_general=:diagnostics_general,avis_client=:avis_client,refus_client_de_signer=:refus_client_de_signer,refus_access=:refus_access,id_organisme_control=:id_organisme_control,chef_equipe_control=:chef_equipe_control,observation=:observation,controleur=:controleur,photo_compteur=:photo_compteur,n_user_create=:n_user_create,datesys=:datesys,date_controle=:date_controle,ref_site_controle=:ref_site_controle,typ_conclusion=:typ_conclusion,cvs_id=:cvs_id,ref_preview_control_found=:ref_preview_control_found,ref_last_install_found=:ref_last_install_found,is_draft_control=:is_draft_control,consommation_journaliere=:consommation_journaliere,consommation_de_30jours_actuels=:consommation_de_30jours_actuels,consommation_de_30jours_precedents=:consommation_de_30jours_precedents,valeur_du_dernier_ticket=:valeur_du_dernier_ticket,index_de_tarif_du_compteur=:index_de_tarif_du_compteur,date_de_dernier_ticket_rentre=:date_de_dernier_ticket_rentre";
		$stmt = $this->connection->prepare($query);
		$this->ref_fiche_controle = $this->uniqUid($this->table_name, "ref_fiche_controle");

		$this->ref_fiche_identification = strip_tags($this->ref_fiche_identification);
		$this->presence_inverseur = strip_tags($this->presence_inverseur);
		$this->numero_serie_cpteur = strip_tags($this->numero_serie_cpteur);
		$this->marque_compteur = strip_tags($this->marque_compteur);
		$this->type_cpteur = strip_tags($this->type_cpteur);
		$this->clavier_deporter = strip_tags($this->clavier_deporter);
		$this->scelle_cpt_existant = strip_tags($this->scelle_cpt_existant);
		$this->scelle_coffret_existant = strip_tags($this->scelle_coffret_existant);
		$this->scelle_compteur_poser = strip_tags($this->scelle_compteur_poser);
		$this->scelle_coffret_poser = strip_tags($this->scelle_coffret_poser);
		$this->type_raccordement = strip_tags($this->type_raccordement);
		$this->nbre_arrived = strip_tags($this->nbre_arrived);
		$this->section_cable_arrived = strip_tags($this->section_cable_arrived);
		$this->par_wifi_cpl = strip_tags($this->par_wifi_cpl);
		$this->num_photo_cpteur = strip_tags($this->num_photo_cpteur);
		$this->num_photo_raccord = strip_tags($this->num_photo_raccord);
		$this->possibility_fraud_expliquer = strip_tags($this->possibility_fraud_expliquer);
		$this->gps_latitude_control = strip_tags($this->gps_latitude_control);
		$this->gps_longitude_control = strip_tags($this->gps_longitude_control);
		$this->etat_interrupteur = strip_tags($this->etat_interrupteur);
		$this->credit_restant = strip_tags($this->credit_restant);
		$this->indicateur_led = strip_tags($this->indicateur_led);
		$this->cas_de_fraude = strip_tags($this->cas_de_fraude);
		$this->client_reconnait_pas = strip_tags($this->client_reconnait_pas);
		$this->type_fraude = strip_tags($this->type_fraude);
		$this->autocollant_place_controleur = strip_tags($this->autocollant_place_controleur);
		$this->autocollant_trouver = strip_tags($this->autocollant_trouver);
		$this->diagnostics_general = strip_tags($this->diagnostics_general);
		$this->avis_client = strip_tags($this->avis_client);
		$this->refus_client_de_signer = strip_tags($this->refus_client_de_signer);
		$this->id_organisme_control = strip_tags($this->id_organisme_control);
		$this->chef_equipe_control = strip_tags($this->chef_equipe_control);
		$this->controleur = strip_tags($this->controleur);
		$this->n_user_create = strip_tags($this->n_user_create);
		$this->ref_site_controle = strip_tags($this->ref_site_controle);
		$this->cvs_id = strip_tags($this->cvs_id);
		$this->refus_access = strip_tags($this->refus_access);
		$this->observation = strip_tags($this->observation);
		$this->typ_conclusion = strip_tags($this->typ_conclusion);
		$this->datesys = date('Y-m-d H:i:s'); //strip_tags($this->date_controle);
		$this->date_controle = date('Y-m-d H:i:s'); //strip_tags($this->date_controle);
		$this->photo_compteur = $this->ref_fiche_controle . '_ctl.png';




		$this->consommation_journaliere = strip_tags($this->consommation_journaliere);
		$this->date_de_dernier_ticket_rentre = strip_tags($this->date_de_dernier_ticket_rentre);
		$this->consommation_de_30jours_actuels = strip_tags($this->consommation_de_30jours_actuels);
		$this->consommation_de_30jours_precedents = strip_tags($this->consommation_de_30jours_precedents);
		$this->valeur_du_dernier_ticket = strip_tags($this->valeur_du_dernier_ticket);
		$this->index_de_tarif_du_compteur = strip_tags($this->index_de_tarif_du_compteur);
		$this->is_draft_control = strip_tags($this->is_draft_control);


		$stmt->bindValue(":ref_fiche_controle", $this->ref_fiche_controle);
		$stmt->bindValue(":ref_fiche_identification", $this->ref_fiche_identification);
		$stmt->bindValue(":presence_inverseur", $this->presence_inverseur);
		$stmt->bindValue(":numero_serie_cpteur", $this->numero_serie_cpteur);
		$stmt->bindValue(":marque_compteur", $this->marque_compteur);
		$stmt->bindValue(":type_cpteur", $this->type_cpteur);
		$stmt->bindValue(":clavier_deporter", $this->clavier_deporter);
		$stmt->bindValue(":scelle_cpt_existant", $this->scelle_cpt_existant);
		$stmt->bindValue(":scelle_coffret_existant", $this->scelle_coffret_existant);
		$stmt->bindValue(":scelle_compteur_poser", $this->scelle_compteur_poser);
		$stmt->bindValue(":scelle_coffret_poser", $this->scelle_coffret_poser);
		$stmt->bindValue(":type_raccordement", $this->type_raccordement);
		$stmt->bindValue(":nbre_arrived", $this->nbre_arrived);
		$stmt->bindValue(":section_cable_arrived", $this->section_cable_arrived);
		$stmt->bindValue(":par_wifi_cpl", $this->par_wifi_cpl);
		$stmt->bindValue(":num_photo_cpteur", $this->num_photo_cpteur);
		$stmt->bindValue(":num_photo_raccord", $this->num_photo_raccord);
		$stmt->bindValue(":possibility_fraud_expliquer", $this->possibility_fraud_expliquer);
		$stmt->bindValue(":gps_latitude_control", $this->gps_latitude_control);
		$stmt->bindValue(":gps_longitude_control", $this->gps_longitude_control);
		$stmt->bindValue(":etat_interrupteur", $this->etat_interrupteur);
		$stmt->bindValue(":credit_restant", $this->credit_restant);
		$stmt->bindValue(":indicateur_led", $this->indicateur_led);
		$stmt->bindValue(":cas_de_fraude", $this->cas_de_fraude);
		$stmt->bindValue(":client_reconnait_pas", $this->client_reconnait_pas);
		$stmt->bindValue(":type_fraude", $this->type_fraude);
		$stmt->bindValue(":autocollant_place_controleur", $this->autocollant_place_controleur);
		$stmt->bindValue(":autocollant_trouver", $this->autocollant_trouver);
		$stmt->bindValue(":diagnostics_general", $this->diagnostics_general);
		$stmt->bindValue(":avis_client", $this->avis_client);
		$stmt->bindValue(":refus_client_de_signer", $this->refus_client_de_signer);
		$stmt->bindValue(":id_organisme_control", $this->id_organisme_control);
		$stmt->bindValue(":chef_equipe_control", $this->chef_equipe_control);
		$stmt->bindValue(":controleur", $this->controleur);
		$stmt->bindValue(":n_user_create", $this->n_user_create);
		$stmt->bindValue(":ref_site_controle", $this->ref_site_controle);
		$stmt->bindValue(":cvs_id", '');
		$stmt->bindValue(":datesys", $this->datesys);
		$stmt->bindValue(":date_controle", $this->date_controle);
		$stmt->bindValue(":photo_compteur", $this->photo_compteur);
		$stmt->bindValue(":refus_access", $this->refus_access);
		$stmt->bindValue(":observation", $this->observation);
		$stmt->bindValue(":ref_preview_control_found", $ref_preview_control_found);
		$stmt->bindValue(":ref_last_install_found", $ref_last_log_install);
		$stmt->bindValue(":typ_conclusion", $this->typ_conclusion);


		$stmt->bindValue(":consommation_journaliere", $this->consommation_journaliere);
		$stmt->bindValue(":date_de_dernier_ticket_rentre", $this->date_de_dernier_ticket_rentre);
		$stmt->bindValue(":consommation_de_30jours_actuels", $this->consommation_de_30jours_actuels);
		$stmt->bindValue(":consommation_de_30jours_precedents", $this->consommation_de_30jours_precedents);
		$stmt->bindValue(":valeur_du_dernier_ticket", $this->valeur_du_dernier_ticket);
		$stmt->bindValue(":index_de_tarif_du_compteur", $this->index_de_tarif_du_compteur);
		$stmt->bindValue(":is_draft_control", $this->is_draft_control);

		//$stmt->bindParam(":datesys", $this->datesys);		
		if ($stmt->execute()) {

			//CREATION LISTE DES FRAUDES
			//if($this->lst_fraudes !=null){
			$this->SaveFraude($this->ref_fiche_controle, $this->lst_fraudes);
			// }

			//CREATION LISTE DES OBSERVATIONS
			//if($this->lst_observations !=null){
			$this->SaveObservation($this->ref_fiche_controle, $this->lst_observations);
			// }





			//Mettre num_compteur abonné
			$query = "update t_main_data set ref_dernier_log_controle=:ref_dernier_log_controle,date_dernier_controle=:date_dernier_controle,statut_conclusion_dernier_controle=:statut_conclusion_dernier_controle where id_=:id_";
			// $query = "update t_main_data set num_compteur_initial=:num_compteur,num_compteur_actuel=:num_compteur,date_installation_initial=:datesys,date_installation_actuel=:datesys,ref_installation_actuel=:ref_installation_actuel,est_installer=1 where id_=:id_";
			$stmt = $this->connection->prepare($query);
			//	$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_dernier_log_controle", $this->ref_fiche_controle);
			$stmt->bindValue(":date_dernier_controle", $this->datesys);
			$stmt->bindValue(":statut_conclusion_dernier_controle", $this->typ_conclusion);
			$stmt->bindValue(":id_", $this->ref_fiche_identification);
			$stmt->execute();

			//Modification validité assignation
			$query = "update t_param_assignation set ref_execution=:ref_execution,date_execution=:date_execution,statut_=1,is_valid=:is_valid where id_assign=:id_";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_execution", $this->ref_fiche_controle);
			$stmt->bindValue(":is_valid", 0); //Invalidation
			$stmt->bindValue(":id_", $this->id_assign);
			$stmt->execute();

			//CHANGER ETAT MAINDATA EN NON ASSIGNE APRES EXECUTION
			$query = "update t_main_data set deja_assigner=0  where id_=:id_";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":id_", $this->ref_fiche_identification);
			$stmt->execute();

			//GENERATION NOTIFICATION SELON LA CONCLUSION
			if ($this->typ_conclusion == '2' || $this->typ_conclusion == '3') {
				$ref_log = $this->uniqUid('t_param_notification_log', "ref_log");
				$query = "INSERT INTO t_param_notification_log SET ref_log=:ref_log,ref_identif=:ref_identif,statuts_notification=:statuts_notification,type_notification=:type_notification,id_site=:id_site,n_user_create=:n_user_create,num_compteur=:num_compteur,ref_transaction=:ref_transaction,datesys=:datesys,cvs_id=:cvs_id,nom_client=:nom_client,adresse_id=:adresse";
				$stmt = $this->connection->prepare($query);
				$stmt->bindValue(":datesys", $datesys);
				$stmt->bindValue(":ref_log", $ref_log);
				$stmt->bindValue(":ref_identif", $this->ref_fiche_identification);
				$stmt->bindValue(":statuts_notification", '0'); //(0)Non vu, (1) Vu		
				$stmt->bindValue(":type_notification", $this->typ_conclusion); // (2) REMPLACEMENT COMPTEUR - (3)DEMANDE DE RE-LEGALISATION - (4) DEMANDE TICKET	
				$stmt->bindValue(":id_site", $this->ref_site_controle);
				$stmt->bindValue(":n_user_create", $this->n_user_create);
				$stmt->bindValue(":num_compteur", $this->numero_serie_cpteur);
				$stmt->bindValue(":ref_transaction", $this->ref_fiche_controle);
				$stmt->bindValue(":cvs_id", $cvs_controler);
				$stmt->bindValue(":nom_client", $client_controler);
				$stmt->bindValue(":adresse", $adresse_controler);
				$stmt->execute();
			}




			//CHANGER STATUT COMPTEUR NEW 

			$E_item_cpteur = new Compteurs($this->connection);
			$E_stmt = $E_item_cpteur->GetCompteurInfo($this->numero_serie_cpteur);
			$ref_produit_series = $E_stmt['ref_produit_series'];
			//(1)Non installé - (2)Installé - (3)Accepté - (4)Déclassé
			$query = "update t_param_liste_compteurs set is_controled=:is_controled,date_controle=:date_controle   where ref_produit_series=:ref_produit_series";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_produit_series", $ref_produit_series);
			$stmt->bindValue(":date_controle", $datesys);
			$stmt->bindValue(":is_controled", '1'); //Controle
			$stmt->execute();

			///HISTORISATION COMPTEUR LIFE	
			$ref_log_compteur_life = $this->uniqUid('t_log_life_compteur', "ref_log_compteur_life");
			$query = "INSERT INTO t_log_life_compteur set ref_log_compteur_life=:ref_log_compteur_life,ref_id_compteur=:ref_id_compteur,type_log=:type_log,ref_adresse=:ref_adresse,status_compteur=:status_compteur,ref_organisme=:ref_organisme,client=:client,ref_fiche_ident_actuel=:ref_fiche_ident_actuel,datesys=:datesys,n_user_create=:n_user_create";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_log_compteur_life", $ref_log_compteur_life);
			$stmt->bindValue(":status_compteur", '0'); //SVC - Hors SVC
			$stmt->bindValue(":ref_organisme", $this->id_organisme_control);
			$stmt->bindValue(":client", $client_controler);
			$stmt->bindValue(":ref_id_compteur", $ref_produit_series);
			$stmt->bindValue(":type_log", '1');

			$stmt->bindValue(":datesys", $datesys);
			$stmt->bindValue(":ref_fiche_ident_actuel", $this->ref_fiche_identification);
			$stmt->bindValue(":ref_adresse", $adresse_controler);
			$stmt->bindValue(":n_user_create", $this->n_user_create);
			$stmt->execute();




			$result["error"] = false;
			$result["message"] = 'Création effectuée avec succès';
		} else {
			$result["error"] = true;
			$result["message"] = "L'opératon de la création a échoué.";
		}
		return $result;
	}



	public function SaveFraude($ref_fiche_ctrl, $lst_fraudes)
	{

		$datesys = date("Y-m-d H:i:s");
		$query = "INSERT INTO t_log_controle_fraudes (ref_control_fraude,ref_code_fraude,ref_fiche_control,n_user_create ,datesys) values (:ref_control_fraude,:ref_code_fraude,:ref_fiche_control,:n_user_create,:datesys)";
		$stmt  = $this->connection->prepare($query);

		$query_avoid_duplicate = "select ref_code_fraude,ref_fiche_control from t_log_controle_fraudes where ref_code_fraude=:ref_code_fraude and ref_fiche_control=:ref_fiche_control";
		$stmt_duplicate = $this->connection->prepare($query_avoid_duplicate);

		if ($lst_fraudes != null) {
			$lst_fraudes_selected = "";
			foreach ($lst_fraudes as $value_) {
				$lst_fraudes_selected .= "'" . $value_ . "',";

				$stmt_duplicate->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
				$stmt_duplicate->bindValue(':ref_code_fraude', $value_);
				$stmt_duplicate->execute();
				$row = $stmt_duplicate->fetch(PDO::FETCH_ASSOC);
				if ($row == false) {
					$ref_control_fraude = $this->uniqUid("t_log_controle_fraudes", "ref_control_fraude");
					$stmt->bindValue(':ref_control_fraude', $ref_control_fraude);
					$stmt->bindValue(':ref_code_fraude', $value_);
					$stmt->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
					$stmt->bindValue(':n_user_create', $this->n_user_create);
					$stmt->bindValue(':datesys', $datesys);
					$stmt->execute();
				}
			}
			//SUPPRESSION DIFFERENTIELLE FRAUDES
			$clean_list = rtrim($lst_fraudes_selected, ",");
			$stmt_delete_batch = $this->connection->prepare("DELETE FROM t_log_controle_fraudes WHERE ref_fiche_control=:ref_fiche_control and ref_code_fraude not in (" . $clean_list  . ")");
			$stmt_delete_batch->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
			$stmt_delete_batch->execute();
		} else {
			//desassigner tous les FRAUDES
			$stmt_delete_batch = $this->connection->prepare("DELETE FROM t_log_controle_fraudes WHERE ref_fiche_control=:ref_fiche_control");
			$stmt_delete_batch->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
			$stmt_delete_batch->execute();
		}
		return true;
	}




	public function SaveObservation($ref_fiche_ctrl, $lst_observations)
	{

		$datesys = date("Y-m-d H:i:s");
		$query = "INSERT INTO t_log_controle_observations (ref_control_obs,ref_code_obs,ref_fiche_control,n_user_create ,datesys) values (:ref_control_obs,:ref_code_obs,:ref_fiche_control,:n_user_create,:datesys)";
		$stmt  = $this->connection->prepare($query);

		$query_avoid_duplicate = "select ref_code_obs,ref_fiche_control from t_log_controle_observations where ref_code_obs=:ref_code_obs and ref_fiche_control=:ref_fiche_control";
		$stmt_duplicate = $this->connection->prepare($query_avoid_duplicate);

		if ($lst_observations != null) {
			$lst_obs_selected = "";
			foreach ($lst_observations as $value_) {
				$lst_obs_selected .= "'" . $value_ . "',";

				$stmt_duplicate->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
				$stmt_duplicate->bindValue(':ref_code_obs', $value_);
				$stmt_duplicate->execute();
				$row = $stmt_duplicate->fetch(PDO::FETCH_ASSOC);
				if ($row == false) {
					$ref_control_obs = $this->uniqUid("t_log_controle_observations", "ref_control_obs");
					$stmt->bindValue(':ref_control_obs', $ref_control_obs);
					$stmt->bindValue(':ref_code_obs', $value_);
					$stmt->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
					$stmt->bindValue(':n_user_create', $this->n_user_create);
					$stmt->bindValue(':datesys', $datesys);
					$stmt->execute();
				}
			}
			//SUPPRESSION DIFFERENTIELLE OBSERVATIONS
			$clean_list = rtrim($lst_obs_selected, ",");
			$stmt_delete_batch = $this->connection->prepare("DELETE FROM t_log_controle_observations WHERE ref_fiche_control=:ref_fiche_control and ref_code_obs not in (" . $clean_list  . ")");
			$stmt_delete_batch->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
			$stmt_delete_batch->execute();
		} else {
			//desassigner tous les OBSERVATIONS
			$stmt_delete_batch = $this->connection->prepare("DELETE FROM t_log_controle_observations WHERE ref_fiche_control=:ref_fiche_control");
			$stmt_delete_batch->bindValue(':ref_fiche_control', $ref_fiche_ctrl);
			$stmt_delete_batch->execute();
		}
		return true;
	}



	function CreateWebOne()
	{
		$ref_last_log_install = '';
		$ref_preview_control_found = '';
		$datesys = date('Y-m-d H:i:s');
		$adress_Ent = new AdresseEntity($this->connection);
		//EVITER DUPLICATE COMPTEUR



		//EVITER DUPLICATE SCELLE 






		//RECUPERATION REF_LAST_INSTALL LOG AND REF_LAST_LOG_CONTROL
		$query = "SELECT id_,ref_installation_actuel,cvs_id,client_id,occupant_id,adresse_id,ref_dernier_log_controle FROM t_main_data where id_=:id_";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":id_", $this->ref_fiche_identification);
		$stmt->execute();
		$row_log = $stmt->fetch(PDO::FETCH_ASSOC);
		$ref_last_log_install = $row_log['ref_installation_actuel'];
		$ref_preview_control_found = $row_log['ref_dernier_log_controle'];
		$cvs_controler = $row_log['cvs_id'];


		$client_info = $adress_Ent->GetMenageDetail($row_log['client_id']);
		$client_controler = $client_info['noms'];
		$adresse_controler = $row_log['adresse_id'];
		///////

		$query = "UPDATE  " . $this->table_name . " SET  ref_fiche_identification=:ref_fiche_identification,presence_inverseur=:presence_inverseur,numero_serie_cpteur=:numero_serie_cpteur,marque_compteur=:marque_compteur,type_cpteur=:type_cpteur,clavier_deporter=:clavier_deporter,scelle_cpt_existant=:scelle_cpt_existant,scelle_coffret_existant=:scelle_coffret_existant,scelle_compteur_poser=:scelle_compteur_poser,scelle_coffret_poser=:scelle_coffret_poser,type_raccordement=:type_raccordement,nbre_arrived=:nbre_arrived,section_cable_arrived=:section_cable_arrived,par_wifi_cpl=:par_wifi_cpl,num_photo_cpteur=:num_photo_cpteur,num_photo_raccord=:num_photo_raccord,possibility_fraud_expliquer=:possibility_fraud_expliquer,gps_latitude_control=:gps_latitude_control,gps_longitude_control=:gps_longitude_control,etat_interrupteur=:etat_interrupteur,credit_restant=:credit_restant,indicateur_led=:indicateur_led,cas_de_fraude=:cas_de_fraude,client_reconnait_pas=:client_reconnait_pas,type_fraude=:type_fraude,autocollant_place_controleur=:autocollant_place_controleur,autocollant_trouver=:autocollant_trouver,diagnostics_general=:diagnostics_general,avis_client=:avis_client,refus_client_de_signer=:refus_client_de_signer,refus_access=:refus_access,id_organisme_control=:id_organisme_control,chef_equipe_control=:chef_equipe_control,observation=:observation,controleur=:controleur,photo_compteur=:photo_compteur,n_user_create=:n_user_create,datesys=:datesys,date_controle=:date_controle,ref_site_controle=:ref_site_controle,typ_conclusion=:typ_conclusion,cvs_id=:cvs_id,ref_preview_control_found=:ref_preview_control_found,ref_last_install_found=:ref_last_install_found,consommation_journaliere=:consommation_journaliere,consommation_de_30jours_actuels=:consommation_de_30jours_actuels,consommation_de_30jours_precedents=:consommation_de_30jours_precedents,valeur_du_dernier_ticket=:valeur_du_dernier_ticket,index_de_tarif_du_compteur=:index_de_tarif_du_compteur,date_de_dernier_ticket_rentre=:date_de_dernier_ticket_rentre,is_draft_control=:is_draft_control,sceller_identique=:sceller_identique,dernier_sceller_compteur=:dernier_sceller_compteur,dernier_sceller_coffret=:dernier_sceller_coffret WHERE ref_fiche_controle=:ref_fiche_controle";
		$stmt = $this->connection->prepare($query);
		//$this->ref_fiche_controle = $this->uniqUid($this->table_name, "ref_fiche_controle"); 

		$this->ref_fiche_identification = strip_tags($this->ref_fiche_identification);
		$this->presence_inverseur = strip_tags($this->presence_inverseur);
		$this->numero_serie_cpteur = strip_tags($this->numero_serie_cpteur);
		$this->marque_compteur = strip_tags($this->marque_compteur);
		$this->type_cpteur = strip_tags($this->type_cpteur);
		$this->clavier_deporter = strip_tags($this->clavier_deporter);
		$this->scelle_cpt_existant = strip_tags($this->scelle_cpt_existant);
		$this->scelle_coffret_existant = strip_tags($this->scelle_coffret_existant);
		$this->scelle_compteur_poser = strip_tags($this->scelle_compteur_poser);
		$this->scelle_coffret_poser = strip_tags($this->scelle_coffret_poser);
		$this->type_raccordement = strip_tags($this->type_raccordement);
		$this->nbre_arrived = strip_tags($this->nbre_arrived);
		$this->section_cable_arrived = strip_tags($this->section_cable_arrived);
		$this->par_wifi_cpl = strip_tags($this->par_wifi_cpl);
		$this->num_photo_cpteur = strip_tags($this->num_photo_cpteur);
		$this->num_photo_raccord = strip_tags($this->num_photo_raccord);
		$this->possibility_fraud_expliquer = strip_tags($this->possibility_fraud_expliquer);
		$this->gps_latitude_control = strip_tags($this->gps_latitude_control);
		$this->gps_longitude_control = strip_tags($this->gps_longitude_control);
		$this->etat_interrupteur = strip_tags($this->etat_interrupteur);
		$this->credit_restant = strip_tags($this->credit_restant);
		$this->indicateur_led = strip_tags($this->indicateur_led);
		$this->cas_de_fraude = strip_tags($this->cas_de_fraude);
		$this->client_reconnait_pas = strip_tags($this->client_reconnait_pas);
		$this->type_fraude = strip_tags($this->type_fraude);
		$this->autocollant_place_controleur = strip_tags($this->autocollant_place_controleur);
		$this->autocollant_trouver = strip_tags($this->autocollant_trouver);
		$this->diagnostics_general = strip_tags($this->diagnostics_general);
		$this->avis_client = strip_tags($this->avis_client);
		$this->refus_client_de_signer = strip_tags($this->refus_client_de_signer);
		$this->id_organisme_control = strip_tags($this->id_organisme_control);
		$this->chef_equipe_control = strip_tags($this->chef_equipe_control);
		$this->controleur = strip_tags($this->controleur);
		$this->n_user_create = strip_tags($this->n_user_create);
		$this->ref_site_controle = strip_tags($this->ref_site_controle);
		$this->cvs_id = strip_tags($this->cvs_id);
		$this->refus_access = strip_tags($this->refus_access);
		$this->observation = strip_tags($this->observation);
		$this->typ_conclusion = strip_tags($this->typ_conclusion);
		$this->datesys = date('Y-m-d H:i:s'); //strip_tags($this->date_controle);
		$this->date_controle = date('Y-m-d H:i:s'); //strip_tags($this->date_controle);
		$this->photo_compteur = $this->ref_fiche_controle . '_ctl.png';


		$this->consommation_journaliere = strip_tags($this->consommation_journaliere);
		$this->date_de_dernier_ticket_rentre = strip_tags($this->date_de_dernier_ticket_rentre);
		$this->consommation_de_30jours_actuels = strip_tags($this->consommation_de_30jours_actuels);
		$this->consommation_de_30jours_precedents = strip_tags($this->consommation_de_30jours_precedents);
		$this->valeur_du_dernier_ticket = strip_tags($this->valeur_du_dernier_ticket);
		$this->index_de_tarif_du_compteur = strip_tags($this->index_de_tarif_du_compteur);
		$this->is_draft_control = strip_tags($this->is_draft_control);



		$stmt->bindValue(":ref_fiche_controle", $this->ref_fiche_controle);
		$stmt->bindValue(":ref_fiche_identification", $this->ref_fiche_identification);
		$stmt->bindValue(":presence_inverseur", $this->presence_inverseur);
		$stmt->bindValue(":numero_serie_cpteur", $this->numero_serie_cpteur);
		$stmt->bindValue(":marque_compteur", $this->marque_compteur);
		$stmt->bindValue(":type_cpteur", $this->type_cpteur);
		$stmt->bindValue(":clavier_deporter", $this->clavier_deporter);
		$stmt->bindValue(":scelle_cpt_existant", $this->scelle_cpt_existant);
		$stmt->bindValue(":scelle_coffret_existant", $this->scelle_coffret_existant);
		$stmt->bindValue(":scelle_compteur_poser", $this->scelle_compteur_poser);
		$stmt->bindValue(":scelle_coffret_poser", $this->scelle_coffret_poser);
		$stmt->bindValue(":type_raccordement", $this->type_raccordement);
		$stmt->bindValue(":nbre_arrived", $this->nbre_arrived);
		$stmt->bindValue(":section_cable_arrived", $this->section_cable_arrived);
		$stmt->bindValue(":par_wifi_cpl", $this->par_wifi_cpl);
		$stmt->bindValue(":num_photo_cpteur", $this->num_photo_cpteur);
		$stmt->bindValue(":num_photo_raccord", $this->num_photo_raccord);
		$stmt->bindValue(":possibility_fraud_expliquer", $this->possibility_fraud_expliquer);
		$stmt->bindValue(":gps_latitude_control", $this->gps_latitude_control);
		$stmt->bindValue(":gps_longitude_control", $this->gps_longitude_control);
		$stmt->bindValue(":etat_interrupteur", $this->etat_interrupteur);
		$stmt->bindValue(":credit_restant", $this->credit_restant);
		$stmt->bindValue(":indicateur_led", $this->indicateur_led);
		$stmt->bindValue(":cas_de_fraude", $this->cas_de_fraude);
		$stmt->bindValue(":client_reconnait_pas", $this->client_reconnait_pas);
		$stmt->bindValue(":type_fraude", $this->type_fraude);
		$stmt->bindValue(":autocollant_place_controleur", $this->autocollant_place_controleur);
		$stmt->bindValue(":autocollant_trouver", $this->autocollant_trouver);
		$stmt->bindValue(":diagnostics_general", $this->diagnostics_general);
		$stmt->bindValue(":avis_client", $this->avis_client);
		$stmt->bindValue(":refus_client_de_signer", $this->refus_client_de_signer);
		$stmt->bindValue(":id_organisme_control", $this->id_organisme_control);
		$stmt->bindValue(":chef_equipe_control", $this->chef_equipe_control);
		$stmt->bindValue(":controleur", $this->controleur);
		$stmt->bindValue(":n_user_create", $this->n_user_create);
		$stmt->bindValue(":ref_site_controle", $this->ref_site_controle);
		$stmt->bindValue(":cvs_id", '');
		$stmt->bindValue(":datesys", $this->datesys);
		$stmt->bindValue(":date_controle", $this->date_controle);
		$stmt->bindValue(":photo_compteur", $this->photo_compteur);
		$stmt->bindValue(":refus_access", $this->refus_access);
		$stmt->bindValue(":observation", $this->observation);
		$stmt->bindValue(":ref_preview_control_found", $ref_preview_control_found);
		$stmt->bindValue(":ref_last_install_found", $ref_last_log_install);
		$stmt->bindValue(":typ_conclusion", $this->typ_conclusion);

		$stmt->bindValue(":consommation_journaliere", $this->consommation_journaliere);
		$stmt->bindValue(":date_de_dernier_ticket_rentre", $this->date_de_dernier_ticket_rentre);
		$stmt->bindValue(":consommation_de_30jours_actuels", $this->consommation_de_30jours_actuels);
		$stmt->bindValue(":consommation_de_30jours_precedents", $this->consommation_de_30jours_precedents);
		$stmt->bindValue(":valeur_du_dernier_ticket", $this->valeur_du_dernier_ticket);
		$stmt->bindValue(":index_de_tarif_du_compteur", $this->index_de_tarif_du_compteur);
		$stmt->bindValue(":is_draft_control", $this->is_draft_control);
		$stmt->bindValue(":sceller_identique", $this->sceller_identique);
		$stmt->bindValue(":dernier_sceller_compteur", $this->dernier_sceller_compteur);
		$stmt->bindValue(":dernier_sceller_coffret", $this->dernier_sceller_coffret);

		//$stmt->bindParam(":datesys", $this->datesys);		
		if ($stmt->execute()) {

			//CREATION LISTE DES FRAUDES
			//if($this->lst_fraudes !=null){
			$this->SaveFraude($this->ref_fiche_controle, $this->lst_fraudes);

			$this->SaveObservation($this->ref_fiche_controle, $this->lst_observations);
			// }

			$param_scelle_compteur_poser = "";
			$param_scelle_coffret_poser = "";
			$param_type_pose_scelle_actuel = "";
			if (!empty($this->scelle_compteur_poser) || !empty($this->scelle_coffret_poser)) {

				$param_type_pose_scelle_actuel = ",type_pose_scelle_actuel=:type_pose_scelle_actuel,id_fiche_pose_scelle_actuel=:id_fiche_pose_scelle_actuel";
				if (!empty($this->scelle_compteur_poser)) {
					$param_scelle_compteur_poser = ",scelle_actuel_compteur=:scelle_actuel_compteur";
				}
				if (!empty($this->scelle_coffret_poser)) {
					$param_scelle_coffret_poser = ",scelle_actuel_coffret=:scelle_actuel_coffret";
				}
			}
			// echo $param_scelle_coffret_poser .  $param_type_pose_scelle_actuel.  $param_scelle_compteur_poser;
			// exit;
			//Mettre num_compteur abonné
			$query = "update t_main_data set ref_dernier_log_controle=:ref_dernier_log_controle,date_dernier_controle=:date_dernier_controle,statut_conclusion_dernier_controle=:statut_conclusion_dernier_controle" .  $param_scelle_coffret_poser .  $param_type_pose_scelle_actuel .  $param_scelle_compteur_poser . " where id_=:id_";
			// $query = "update t_main_data set num_compteur_initial=:num_compteur,num_compteur_actuel=:num_compteur,date_installation_initial=:datesys,date_installation_actuel=:datesys,ref_installation_actuel=:ref_installation_actuel,est_installer=1 where id_=:id_";
			$stmt = $this->connection->prepare($query);
			//	$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_dernier_log_controle", $this->ref_fiche_controle);
			$stmt->bindValue(":date_dernier_controle", $this->datesys);
			$stmt->bindValue(":statut_conclusion_dernier_controle", $this->typ_conclusion);
			$stmt->bindValue(":id_", $this->ref_fiche_identification);


			if (!empty($this->scelle_compteur_poser) || !empty($this->scelle_coffret_poser)) {

				$stmt->bindValue(":type_pose_scelle_actuel", 1); //CONTROLE
				$stmt->bindValue(":id_fiche_pose_scelle_actuel", $this->ref_fiche_controle);

				if (!empty($this->scelle_compteur_poser)) {
					$stmt->bindValue(":scelle_actuel_compteur", $this->scelle_compteur_poser);
				}

				if (!empty($this->scelle_coffret_poser)) {
					$stmt->bindValue(":scelle_actuel_coffret", $this->scelle_coffret_poser);
				}
			}
			$stmt->execute();

			//Modification validité assignation
			$query = "update t_param_assignation set ref_execution=:ref_execution,date_execution=:date_execution,statut_=1,is_valid=:is_valid where id_assign=:id_";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_execution", $this->ref_fiche_controle);
			$stmt->bindValue(":is_valid", 0); //Invalidation
			$stmt->bindValue(":id_", $this->id_assign);
			$stmt->execute();

			//CHANGER ETAT MAINDATA EN NON ASSIGNE APRES EXECUTION
			$query = "update t_main_data set deja_assigner=0  where id_=:id_";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":id_", $this->ref_fiche_identification);
			$stmt->execute();

			//GENERATION NOTIFICATION SELON LA CONCLUSION
			if ($this->typ_conclusion == '2' || $this->typ_conclusion == '3') {
				$ref_log = $this->uniqUid('t_param_notification_log', "ref_log");
				$query = "INSERT INTO t_param_notification_log SET ref_log=:ref_log,ref_identif=:ref_identif,statuts_notification=:statuts_notification,type_notification=:type_notification,id_site=:id_site,n_user_create=:n_user_create,num_compteur=:num_compteur,ref_transaction=:ref_transaction,datesys=:datesys,cvs_id=:cvs_id,nom_client=:nom_client,adresse_id=:adresse";
				$stmt = $this->connection->prepare($query);
				$stmt->bindValue(":datesys", $datesys);
				$stmt->bindValue(":ref_log", $ref_log);
				$stmt->bindValue(":ref_identif", $this->ref_fiche_identification);
				$stmt->bindValue(":statuts_notification", '0'); //(0)Non vu, (1) Vu		
				$stmt->bindValue(":type_notification", $this->typ_conclusion); // (2) REMPLACEMENT COMPTEUR - (3)DEMANDE DE RE-LEGALISATION - (4) DEMANDE TICKET	
				$stmt->bindValue(":id_site", $this->ref_site_controle);
				$stmt->bindValue(":n_user_create", $this->n_user_create);
				$stmt->bindValue(":num_compteur", $this->numero_serie_cpteur);
				$stmt->bindValue(":ref_transaction", $this->ref_fiche_controle);
				$stmt->bindValue(":cvs_id", $cvs_controler);
				$stmt->bindValue(":nom_client", $client_controler);
				$stmt->bindValue(":adresse", $adresse_controler);
				$stmt->execute();
			}




			//CHANGER STATUT COMPTEUR NEW 

			$E_item_cpteur = new Compteurs($this->connection);
			$E_stmt = $E_item_cpteur->GetCompteurInfo($this->numero_serie_cpteur);
			$ref_produit_series = $E_stmt['ref_produit_series'];
			//(1)Non installé - (2)Installé - (3)Accepté - (4)Déclassé
			$query = "update t_param_liste_compteurs set is_controled=:is_controled,date_controle=:date_controle   where ref_produit_series=:ref_produit_series";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_produit_series", $ref_produit_series);
			$stmt->bindValue(":date_controle", $datesys);
			$stmt->bindValue(":is_controled", '1'); //Controle
			$stmt->execute();

			///HISTORISATION COMPTEUR LIFE	
			// $ticker = "SELECT id_install,code_tarif,index_par_defaut,ref_identific,cabine,num_depart,num_poteau,type_raccordement,type_cpteur_raccord,nbre_alimentation,section_cable_alimentation_deux,section_cable_alimentation,section_cable_sortie,presence_inverseur,marque_cpteur_post_paie,date_retrait_cpteur_post_paie=now(),num_serie_cpteur_post_paie=:num_serie_cpteur_post_paie,index_credit_restant_cpteur_post_paie=:index_credit_restant_cpteur_post_paie,marque_cpteur_replaced=:marque_cpteur_replaced,num_serie_cpteur_replaced=:num_serie_cpteur_replaced,index_credit_restant_cpteur_replaced=:index_credit_restant_cpteur_replaced,type_defaut=:type_defaut,marque_compteur=:marque_compteur,numero_compteur=:numero_compteur,type_new_cpteur=:type_new_cpteur,disjoncteur=:disjoncteur,replace_client_disjonct=:replace_client_disjonct,client_disjonct_amperage=:client_disjonct_amperage,scelle_un_cpteur=:scelle_un_cpteur,scelle_deux_coffret=:scelle_deux_coffret,commentaire_installateur=:commentaire_installateur,commenteur_controle_blue=:commenteur_controle_blue,installateur=:installateur,chef_equipe=:chef_equipe,controleur_blue=:controleur_blue,agent_cvs=:agent_cvs,n_user_create=:n_user_create,datesys=:datesys,is_sync=:is_sync,date_pose_scelle=now(),type_installation=:type_installation,usage_electricity=:usage_electricity,etat_poc=:etat_poc,photo_compteur=:photo_compteur,date_debut_installation=:date_debut_installation,date_fin_installation=:date_fin_installation,gps_longitude=:gps_longitude,gps_latitude=:gps_latitude,code_installateur=:code_installateur,id_equipe=:id_equipe,nom_equipe=:nom_equipe,ref_site_install=:ref_site_install,is_autocollant_posed=:is_autocollant_posed,post_paie_trouver=:post_paie_trouver,is_draft_install=:is_draft_install,etat_compteur_reaffected=:etat_compteur_reaffected""
			$ref_log_compteur_life = $this->uniqUid('t_log_life_compteur', "ref_log_compteur_life");
			$query = "INSERT INTO t_log_life_compteur set ref_log_compteur_life=:ref_log_compteur_life,ref_id_compteur=:ref_id_compteur,type_log=:type_log,ref_adresse=:ref_adresse,status_compteur=:status_compteur,ref_organisme=:ref_organisme,client=:client,ref_fiche_ident_actuel=:ref_fiche_ident_actuel,datesys=:datesys,n_user_create=:n_user_create";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_log_compteur_life", $ref_log_compteur_life);
			$stmt->bindValue(":status_compteur", '0'); //SVC - Hors SVC
			$stmt->bindValue(":ref_organisme", $this->id_organisme_control);
			$stmt->bindValue(":client", $client_controler);
			$stmt->bindValue(":ref_id_compteur", $ref_produit_series);
			$stmt->bindValue(":type_log", '1');

			$stmt->bindValue(":datesys", $datesys);
			$stmt->bindValue(":ref_fiche_ident_actuel", $this->ref_fiche_identification);
			$stmt->bindValue(":ref_adresse", $adresse_controler);
			$stmt->bindValue(":n_user_create", $this->n_user_create);
			$stmt->execute();




			$result["error"] = false;
			$result["message"] = 'Opération effectuée avec succès';
		} else {
			$result["error"] = true;
			$result["message"] = "L'opératon de la création a échoué.";
		}
		return $result;
	}

	function SendTicketDemand($id_contro)
	{
		$installation = "";
		$query = "SELECT id_install,code_tarif,index_par_defaut,ref_identific,cabine,num_depart,
			num_poteau,type_raccordement,type_cpteur_raccord,nbre_alimentation,section_cable_alimentation_deux,
			section_cable_alimentation,section_cable_sortie,presence_inverseur,marque_cpteur_post_paie,date_retrait_cpteur_post_paie,
			num_serie_cpteur_post_paie,index_credit_restant_cpteur_post_paie,marque_cpteur_replaced,num_serie_cpteur_replaced,
			index_credit_restant_cpteur_replaced,type_defaut,marque_compteur,numero_compteur,type_new_cpteur,
			disjoncteur,replace_client_disjonct,client_disjonct_amperage,scelle_un_cpteur,scelle_deux_coffret,
			commentaire_installateur,commenteur_controle_blue,installateur,chef_equipe,controleur_blue,
			agent_cvs,n_user_create,datesys,is_sync,date_pose_scelle,type_installation,usage_electricity,
			etat_poc,photo_compteur,date_debut_installation,date_fin_installation,gps_longitude,gps_latitude,code_installateur,id_equipe,
			nom_equipe,ref_site_install,is_autocollant_posed,post_paie_trouver,is_draft_install,etat_compteur_reaffected FROM t_log_installation WHERE numero_compteur=:numero_compteur AND id_install=:id_install";

		$stmt = $this->connection->prepare($query);


		//Generation Demande Ticket
		$ref_log = $this->uniqUid('t_param_notification_log', "ref_log");
		$query = "INSERT INTO t_param_notification_log 
			SET ref_log=:ref_log, 
				ref_identif=:ref_identif,
				statuts_notification=:statuts_notification,
				type_notification=:type_notification,
				id_site=:id_site,
				n_user_create=:n_user_create,
				num_compteur=:num_compteur,
				ref_transaction=:ref_transaction,
				datesys=:datesys,
				cvs_id=:cvs_id,
				nom_client=:nom_client,
				adresse_id=:adresse";

		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
		$stmt->bindValue(":ref_log", $ref_log);
		$stmt->bindValue(":ref_identif", $this->ref_identific);
		$stmt->bindValue(":statuts_notification", '0'); //(0)Non vu, (1) Vu		
		$stmt->bindValue(":type_notification", "4"); // (2) REMPLACEMENT COMPTEUR - (3)DEMANDE DE RE-LEGALISATION - (4) DEMANDE TICKET	
		$stmt->bindValue(":id_site", $this->ref_site_install);
		$stmt->bindValue(":n_user_create", $this->n_user_create);
		$stmt->bindValue(":num_compteur", $this->numero_compteur);
		$stmt->bindValue(":ref_transaction", $this->id_install);
		$stmt->bindValue(":cvs_id", $cvs_controler);
		$stmt->bindValue(":nom_client", $client_controler);
		$stmt->bindValue(":adresse", $adresse_controler);
		//$stmt->bindValue(":tarif", $this->tarif);	
		$stmt->execute();
	}

	function Modifier()
	{
		//EVITER DUPLICATE COMPTEUR



		//EVITER DUPLICATE SCELLE 
		$query = "UPDATE " . $this->table_name . " SET presence_inverseur=:presence_inverseur,numero_serie_cpteur=:numero_serie_cpteur,marque_compteur=:marque_compteur,type_cpteur=:type_cpteur,clavier_deporter=:clavier_deporter,scelle_cpt_existant=:scelle_cpt_existant,scelle_coffret_existant=:scelle_coffret_existant,scelle_compteur_poser=:scelle_compteur_poser,scelle_coffret_poser=:scelle_coffret_poser,type_raccordement=:type_raccordement,nbre_arrived=:nbre_arrived,section_cable_arrived=:section_cable_arrived,par_wifi_cpl=:par_wifi_cpl,num_photo_cpteur=:num_photo_cpteur,num_photo_raccord=:num_photo_raccord,possibility_fraud_expliquer=:possibility_fraud_expliquer,etat_interrupteur=:etat_interrupteur,credit_restant=:credit_restant,indicateur_led=:indicateur_led,cas_de_fraude=:cas_de_fraude,client_reconnait_pas=:client_reconnait_pas,type_fraude=:type_fraude,autocollant_place_controleur=:autocollant_place_controleur,autocollant_trouver=:autocollant_trouver,diagnostics_general=:diagnostics_general,avis_client=:avis_client,refus_client_de_signer=:refus_client_de_signer,id_organisme_control=:id_organisme_control,chef_equipe_control=:chef_equipe_control,observation=:observation,controleur=:controleur,n_user_update=:n_user_update,ref_fiche_identification=:ref_fiche_identification,date_update=:date_update,consommation_journaliere=:consommation_journaliere,is_draft_control=:is_draft_control,consommation_de_30jours_actuels=:consommation_de_30jours_actuels,consommation_de_30jours_precedents=:consommation_de_30jours_precedents,valeur_du_dernier_ticket=:valeur_du_dernier_ticket,index_de_tarif_du_compteur=:index_de_tarif_du_compteur,date_de_dernier_ticket_rentre=:date_de_dernier_ticket_rentre,typ_conclusion=:typ_conclusion,sceller_identique=:sceller_identique WHERE ref_fiche_controle=:ref_fiche_controle";
		$stmt = $this->connection->prepare($query);


		$this->ref_fiche_controle = strip_tags($this->ref_fiche_controle);
		$this->presence_inverseur = strip_tags($this->presence_inverseur);
		$this->numero_serie_cpteur = strip_tags($this->numero_serie_cpteur);
		$this->marque_compteur = strip_tags($this->marque_compteur);
		$this->type_cpteur = strip_tags($this->type_cpteur);
		$this->clavier_deporter = strip_tags($this->clavier_deporter);
		$this->scelle_cpt_existant = strip_tags($this->scelle_cpt_existant);
		$this->scelle_coffret_existant = strip_tags($this->scelle_coffret_existant);
		$this->scelle_compteur_poser = strip_tags($this->scelle_compteur_poser);
		$this->scelle_coffret_poser = strip_tags($this->scelle_coffret_poser);
		$this->type_raccordement = strip_tags($this->type_raccordement);
		$this->nbre_arrived = strip_tags($this->nbre_arrived);
		$this->section_cable_arrived = strip_tags($this->section_cable_arrived);
		$this->par_wifi_cpl = strip_tags($this->par_wifi_cpl);
		$this->num_photo_cpteur = strip_tags($this->num_photo_cpteur);
		$this->num_photo_raccord = strip_tags($this->num_photo_raccord);
		$this->possibility_fraud_expliquer = strip_tags($this->possibility_fraud_expliquer);
		$this->etat_interrupteur = strip_tags($this->etat_interrupteur);
		$this->credit_restant = strip_tags($this->credit_restant);
		$this->indicateur_led = strip_tags($this->indicateur_led);
		$this->cas_de_fraude = strip_tags($this->cas_de_fraude);
		$this->client_reconnait_pas = strip_tags($this->client_reconnait_pas);
		$this->type_fraude = strip_tags($this->type_fraude);
		$this->autocollant_place_controleur = strip_tags($this->autocollant_place_controleur);
		$this->autocollant_trouver = strip_tags($this->autocollant_trouver);
		$this->diagnostics_general = strip_tags($this->diagnostics_general);
		$this->avis_client = strip_tags($this->avis_client);
		$this->refus_client_de_signer = strip_tags($this->refus_client_de_signer);
		$this->id_organisme_control = strip_tags($this->id_organisme_control);
		$this->chef_equipe_control = strip_tags($this->chef_equipe_control);
		$this->controleur = strip_tags($this->controleur);
		$this->n_user_update = strip_tags($this->n_user_update);
		$this->cvs_id = strip_tags($this->cvs_id);
		//$this->refus_access = strip_tags($this->refus_access);
		$this->observation = strip_tags($this->observation);
		$this->date_update = date('Y-m-d H:i:s'); //strip_tags($this->date_controle); 
		$this->photo_compteur = $this->ref_fiche_controle . '_ctr.png';

		$this->consommation_journaliere = strip_tags($this->consommation_journaliere);
		$this->date_de_dernier_ticket_rentre = strip_tags($this->date_de_dernier_ticket_rentre);
		$this->consommation_de_30jours_actuels = strip_tags($this->consommation_de_30jours_actuels);
		$this->consommation_de_30jours_precedents = strip_tags($this->consommation_de_30jours_precedents);
		$this->valeur_du_dernier_ticket = strip_tags($this->valeur_du_dernier_ticket);
		$this->index_de_tarif_du_compteur = strip_tags($this->index_de_tarif_du_compteur);
		$this->is_draft_control = strip_tags($this->is_draft_control);
		$this->typ_conclusion = strip_tags($this->typ_conclusion);




		$stmt->bindValue(":ref_fiche_controle", $this->ref_fiche_controle);
		$stmt->bindValue(":ref_fiche_identification", $this->ref_fiche_identification);
		$stmt->bindValue(":presence_inverseur", $this->presence_inverseur);
		$stmt->bindValue(":numero_serie_cpteur", $this->numero_serie_cpteur);
		$stmt->bindValue(":marque_compteur", $this->marque_compteur);
		$stmt->bindValue(":type_cpteur", $this->type_cpteur);
		$stmt->bindValue(":clavier_deporter", $this->clavier_deporter);
		$stmt->bindValue(":scelle_cpt_existant", $this->scelle_cpt_existant);
		$stmt->bindValue(":scelle_coffret_existant", $this->scelle_coffret_existant);
		$stmt->bindValue(":scelle_compteur_poser", $this->scelle_compteur_poser);
		$stmt->bindValue(":scelle_coffret_poser", $this->scelle_coffret_poser);
		$stmt->bindValue(":type_raccordement", $this->type_raccordement);
		$stmt->bindValue(":nbre_arrived", $this->nbre_arrived);
		$stmt->bindValue(":section_cable_arrived", $this->section_cable_arrived);
		$stmt->bindValue(":par_wifi_cpl", $this->par_wifi_cpl);
		$stmt->bindValue(":num_photo_cpteur", $this->num_photo_cpteur);
		$stmt->bindValue(":num_photo_raccord", $this->num_photo_raccord);
		$stmt->bindValue(":possibility_fraud_expliquer", $this->possibility_fraud_expliquer);
		$stmt->bindValue(":etat_interrupteur", $this->etat_interrupteur);
		$stmt->bindValue(":credit_restant", $this->credit_restant);
		$stmt->bindValue(":indicateur_led", $this->indicateur_led);
		$stmt->bindValue(":cas_de_fraude", $this->cas_de_fraude);
		$stmt->bindValue(":client_reconnait_pas", $this->client_reconnait_pas);
		$stmt->bindValue(":type_fraude", $this->type_fraude);
		$stmt->bindValue(":autocollant_place_controleur", $this->autocollant_place_controleur);
		$stmt->bindValue(":autocollant_trouver", $this->autocollant_trouver);
		$stmt->bindValue(":diagnostics_general", $this->diagnostics_general);
		$stmt->bindValue(":avis_client", $this->avis_client);
		$stmt->bindValue(":refus_client_de_signer", $this->refus_client_de_signer);
		$stmt->bindValue(":id_organisme_control", $this->id_organisme_control);
		$stmt->bindValue(":chef_equipe_control", $this->chef_equipe_control);
		$stmt->bindValue(":controleur", $this->controleur);
		$stmt->bindValue(":n_user_update", $this->n_user_update);
		$stmt->bindValue(":date_update", $this->date_update);
		//$stmt->bindValue(":refus_access",$this->refus_access); 
		$stmt->bindValue(":observation", $this->observation);

		$stmt->bindValue(":consommation_journaliere", $this->consommation_journaliere);
		$stmt->bindValue(":date_de_dernier_ticket_rentre", $this->date_de_dernier_ticket_rentre);
		$stmt->bindValue(":consommation_de_30jours_actuels", $this->consommation_de_30jours_actuels);
		$stmt->bindValue(":consommation_de_30jours_precedents", $this->consommation_de_30jours_precedents);
		$stmt->bindValue(":valeur_du_dernier_ticket", $this->valeur_du_dernier_ticket);
		$stmt->bindValue(":index_de_tarif_du_compteur", $this->index_de_tarif_du_compteur);
		$stmt->bindValue(":is_draft_control", $this->is_draft_control);
		$stmt->bindValue(":typ_conclusion", $this->typ_conclusion);
		$stmt->bindValue(":sceller_identique", $this->sceller_identique);

		//$stmt->bindParam(":datesys", $this->datesys);		
		if ($stmt->execute()) {


			$this->SaveFraude($this->ref_fiche_controle, $this->lst_fraudes);
			$this->SaveObservation($this->ref_fiche_controle, $this->lst_observations);


			//Mettre num_compteur abonné
			$query = "update t_main_data set ref_dernier_log_controle=:ref_dernier_log_controle,date_dernier_controle=:date_dernier_controle where id_=:id_";
			// $query = "update t_main_data set num_compteur_initial=:num_compteur,num_compteur_actuel=:num_compteur,date_installation_initial=:datesys,date_installation_actuel=:datesys,ref_installation_actuel=:ref_installation_actuel,est_installer=1 where id_=:id_";
			$stmt = $this->connection->prepare($query);
			//	$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_dernier_log_controle", $this->ref_fiche_controle);
			$stmt->bindValue(":date_dernier_controle", $this->datesys);
			$stmt->bindValue(":id_", $this->ref_fiche_identification);
			$stmt->execute();
			// $this->SaveMateriels($this->ref_fiche_controle, $this->lst_materiels);
			$result["error"] = false;
			$result["message"] = 'Modification effectuée avec succès';
		} else {
			$result["error"] = true;
			$result["message"] = "L'opératon de la modification a échoué.";
		}
		return $result;
	}

	/*  
	public function SaveMateriels($ref_controle, $materiels) {
		if(!is_array($materiels)){
			return;
		}
		
        $stmt = $this->connection->prepare("DELETE FROM t_log_controle_materiels WHERE ref_identification=:ref_identification");
		$stmt->bindValue(':ref_identification', $identif);
		$stmt->execute();
		
		$datesys = date("Y-m-d H:i:s");
		$query = "INSERT INTO t_log_controle_materiels (id_mat,ref_article,ref_controle,qte,datesys) values (:id_mat,:ref_article,:ref_controle,:qte,:datesys)";
        $stmt = $this->connection->prepare($query);
        foreach ($materiels as $value) {
            $id_mat = $this->uniqUid("t_log_controle_materiels", "id_mat");
            $stmt->bindValue(':id_mat', $id_mat);
			$stmt->bindValue(':ref_article', $value->libelle);
			$stmt->bindValue(':ref_controle', $ref_controle);
			$stmt->bindValue(':qte', $value->qte);
			$stmt->bindValue(':datesys', $datesys); 
            $stmt->execute();
        }
        return true;
    }*/


	/*function GetListeControle(){
	 $query = "SELECT t_log_installation.id_install,t_log_installation.id_equipe, t_log_installation.ref_identific, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_log_installation.p_a, t_log_installation.nom_installateur, t_log_installation.nom_equipe, t_log_installation.numero_compteur, t_log_installation.photo_compteur, t_log_installation.marque_compteur, t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur, t_main_data.nom_client_blue, t_main_data.phone_client_blue, t_main_data.adresse, t_main_data.photo_pa_avant, t_main_data.cvs_id, t_main_data.commune_id, t_main_data.section_cable, t_main_data.nbre_branchement FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ order by t_log_installation.datesys desc";	 
	   $stmt = $this->connection->prepare( $query );
        $stmt->execute();
		 $result = array();
		 $data = array();
        while ($row_spin = $stmt->fetch(PDO::FETCH_ASSOC)) {  
			$data[] =$row_spin;
		}
		if (isset($data)) {
            $result["error"] = false;
            $result["message"] = "Opération effectuée avec succès";
            $result["data"] =$data;  
        } else {
            $result["error"] = true;
            $result["message"] = "Il n'y a pas de données";
            $result["data"] = null;
        }
		return $result;
	 
 }*/

	function GetUserFilter($user_context)
	{
		$user_filtre = "";
		if ($user_context->id_service_group ==  '3' || $user_context->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($user_context->is_chief == '1') {
			$lst_user_chief = '';
			/*$lst_user_chief= "'" . $user_context->code_utilisateur . "'";
			$stmt_chief = $user_context->GetCurrentUserListIdentificateurs($user_context->code_utilisateur,$user_context->id_organisme,$user_context->is_chief);
			$row_chief = $stmt_chief->fetchAll(PDO::FETCH_ASSOC);
			if(count($row_chief)>0){
				$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
						$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
					}
			}*/

			$row_chief = $user_context->GenerateUserTree($user_context->code_utilisateur);
			if (count($row_chief) > 0) {
				foreach ($row_chief as $item) {
					$lst_user_chief .= "'" . $item . "',";
				}
			}
			$clean = rtrim($lst_user_chief, ",");
			$user_filtre = " and controleur in (" . $clean . ")";
		} else {
			$user_filtre = " and controleur='" . $user_context->code_utilisateur  . "'";
		}
		return $user_filtre;
	}

	function GetDetail($id_service_group)
	{

		$e_adresse = new AdresseEntity($this->connection);
		$query = "SELECT t_log_controle.gps_latitude_control,t_log_controle.gps_longitude_control,t_log_controle.ref_fiche_controle,t_log_controle.ref_last_install_found,t_log_controle.consommation_journaliere,t_log_controle.consommation_de_30jours_actuels,t_log_controle.consommation_de_30jours_precedents,t_log_controle.valeur_du_dernier_ticket,t_log_controle.index_de_tarif_du_compteur,t_log_controle.is_draft_control,DATE_FORMAT(t_log_controle.date_de_dernier_ticket_rentre,'%d/%m/%Y') as date_de_dernier_ticket_rentre_fr,t_log_controle.date_de_dernier_ticket_rentre,t_log_controle.ref_fiche_identification,t_log_controle.presence_inverseur,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.type_cpteur,t_log_controle.clavier_deporter,t_log_controle.scelle_cpt_existant,t_log_controle.scelle_coffret_existant,t_log_controle.scelle_compteur_poser,t_log_controle.scelle_coffret_poser,t_log_controle.type_raccordement,t_log_controle.nbre_arrived,t_log_controle.section_cable_arrived,t_log_controle.par_wifi_cpl,t_log_controle.num_photo_cpteur,t_log_controle.num_photo_raccord,t_log_controle.possibility_fraud_expliquer,t_main_data.gps_latitude,t_main_data.gps_longitude,t_main_data.client_id,t_main_data.occupant_id,t_log_controle.etat_interrupteur,t_log_controle.credit_restant,t_log_controle.indicateur_led,t_log_controle.cas_de_fraude,t_log_controle.client_reconnait_pas,t_log_controle.type_fraude,t_log_controle.autocollant_place_controleur,t_log_controle.autocollant_trouver,t_log_controle.diagnostics_general,t_log_controle.avis_client,t_log_controle.refus_client_de_signer,t_log_controle.refus_access,t_log_controle.id_organisme_control,t_log_controle.chef_equipe_control,t_log_controle.controleur,t_log_controle.gps_latitude_control,t_log_controle.gps_longitude_control,t_log_controle.photo_compteur,t_log_controle.observation,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%s')  as date_identification_fr,t_main_data.date_identification,t_main_data.id_,t_main_data.p_a,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.num_compteur_actuel,t_main_data.adresse_id,t_main_data.cvs_id,t_main_data.tarif_identif,t_log_controle.typ_conclusion,t_log_controle.sceller_identique,t_log_controle.dernier_sceller_compteur,t_log_controle.dernier_sceller_coffret FROM t_log_controle INNER JOIN t_main_data ON t_main_data.id_ = t_log_controle.ref_fiche_identification  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE ref_fiche_controle = ?
			LIMIT 0,1";
		$result = array();
		$items = array();
		$stmt = $this->connection->prepare($query);
		$this->ref_fiche_controle = (strip_tags($this->ref_fiche_controle));
		$stmt->bindParam(1, $this->ref_fiche_controle);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$client_id = $row['client_id'];
		$occupant_id = $row['occupant_id'];
		$adresse_id =  $row['adresse_id'];
		$adress_item = new  AdresseEntity($this->connection);
		$result["adresseTexte"] = $adress_item->GetAdressInfoTexte($adresse_id);
		$result["error"] = 0;
		$result["data"] = $row;
		$result["client"] =  $e_adresse->GetMenageDetail($client_id);
		$result["occupant"] =  $e_adresse->GetMenageDetail($occupant_id);

		//INFOS INSTALLATION
		$result["infos_installation"] = array();
		$query_avoid = "select id_install,DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S') as date_fin_installation_fr from t_log_installation where id_install=:id_install";
		$stmt_avoid = $this->connection->prepare($query_avoid);
		$stmt_avoid->bindValue(":id_install", $row['ref_last_install_found']);
		$stmt_avoid->execute();
		$row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
		if ($row_avoid) {
			$result["infos_installation"] =	$row_avoid;
		}
		//END INFOS INSTALLATION

		//INFOS FRAUDES
		$result["fraudes"] = array();
		$query_avoid = "select  ref_code_fraude from t_log_controle_fraudes where ref_fiche_control=:ref_fiche_control";
		$stmt_avoid = $this->connection->prepare($query_avoid);
		$stmt_avoid->bindValue(":ref_fiche_control", $this->ref_fiche_controle);
		$stmt_avoid->execute();
		$row_chief = $stmt_avoid->fetchAll(PDO::FETCH_ASSOC);
		$result["fraudes"] = $row_chief;
		//END FRAUDES

		//INFOS OBSERVATIONS
		$result["codes_observations"] = array();
		$query_avoid = "select  ref_code_obs from t_log_controle_observations where ref_fiche_control=:ref_fiche_control";
		$stmt_avoid = $this->connection->prepare($query_avoid);
		$stmt_avoid->bindValue(":ref_fiche_control", $this->ref_fiche_controle);
		$stmt_avoid->execute();
		$row_chief = $stmt_avoid->fetchAll(PDO::FETCH_ASSOC);
		$result["codes_observations"] = $row_chief;
		//END OBSERVATIONS

		return $result;
	}

	//public function uniqUid($len = 13) {  
	public function uniqUid($table, $key_fld)
	{
		//uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
		$bytes = md5(mt_rand());
		if ($this->VerifierExistance($key_fld, $bytes, $table)) {
			$bytes = $this->uniqUid($table, $key_fld);
		}
		return $bytes;
	}

	function VerifierExistance($pKey, $NoGenerated, $table)
	{
		$retour = false;
		$sql = "select $pKey from $table where $pKey=:NoGenerated";
		$stmt = $this->connection->prepare($sql);
		$stmt->bindValue(":NoGenerated", $NoGenerated);
		$stmt->execute();
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$retour = true;
		} else {
			$retour = false;
		}
		return $retour;
	}

	function readAll($from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT t_log_controle.gps_latitude_control,t_log_controle.gps_longitude_control,t_log_controle.ref_fiche_controle,t_log_controle.chef_equipe_control,t_log_controle.is_draft_control,t_log_controle.id_organisme_control,t_log_controle.ref_fiche_identification,t_log_controle.ref_site_controle,t_log_controle.date_controle,t_log_controle.controleur,t_main_data.cvs_id ,t_log_controle.numero_serie_cpteur,DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y %H:%i:%S')  as date_controle_fr,t_log_controle.etat_fraude,t_log_controle.type_fraude,t_log_controle.cas_de_fraude,t_log_controle.photo_compteur,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.marque_autre,t_log_controle.type_cpteur, Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_controle.controleur = t_utilisateurs.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`   INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id   where t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre . "  ORDER BY t_log_controle.date_controle  DESC LIMIT {$from_record_num}, {$records_per_page}";
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	public function countAll($user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT t_log_controle.ref_fiche_controle FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_controle.controleur = t_utilisateurs.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`   INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id   where  t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}


	public function search($search_term, $from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT t_log_controle.gps_latitude_control,t_log_controle.gps_longitude_control,t_log_controle.ref_fiche_controle,t_log_controle.chef_equipe_control,t_log_controle.is_draft_control,t_log_controle.id_organisme_control,t_log_controle.ref_fiche_identification,t_log_controle.ref_site_controle,t_log_controle.date_controle,t_log_controle.controleur,t_main_data.cvs_id ,t_log_controle.numero_serie_cpteur,DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y %H:%i:%S')  as date_controle_fr,t_log_controle.etat_fraude,t_log_controle.type_fraude,t_log_controle.cas_de_fraude,t_log_controle.photo_compteur,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.marque_autre,t_log_controle.type_cpteur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_controleur.nom_utilisateur,t_controleur.nom_complet,t_controleur.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs as t_controleur ON t_log_controle.controleur = t_controleur.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE ((t_log_controle.ref_fiche_controle LIKE :begin_search_term or t_log_controle.ref_fiche_controle LIKE :search_term ) or t_log_controle.numero_serie_cpteur Like :search_term or Concat(identite_client.nom,' ',identite_client.postnom,' ',identite_client.prenom) Like :search_term  OR e_ville.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term OR e_avenue.libelle Like :search_term OR t_controleur.nom_complet  Like :search_term OR identite_client.phone_number Like :search_term)  and   t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre . " ORDER BY t_log_controle.ref_fiche_controle ASC ,t_log_controle.date_controle desc LIMIT :from, :offset";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$begin_search_term = "{$search_term}%";
		$stmt->bindParam(':begin_search_term', $begin_search_term);
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}

	public function countAll_BySearch($search_term, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT COUNT(*) as total_rows FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs as t_controleur ON t_log_controle.controleur = t_controleur.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE (t_log_controle.ref_fiche_controle Like :search_term or t_log_controle.numero_serie_cpteur Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term  OR e_ville.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term OR e_avenue.libelle Like :search_term OR t_controleur.nom_complet  Like :search_term OR identite_client.phone_number Like :search_term)    and t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}




	public function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT t_log_controle.gps_latitude_control,t_log_controle.gps_longitude_control,t_log_controle.ref_fiche_controle,t_log_controle.chef_equipe_control,t_log_controle.is_draft_control,t_log_controle.id_organisme_control,t_log_controle.ref_fiche_identification,t_log_controle.ref_site_controle,t_log_controle.date_controle,t_log_controle.controleur,t_main_data.cvs_id ,t_log_controle.numero_serie_cpteur,DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y %H:%i:%S')  as date_controle_fr,t_log_controle.etat_fraude,t_log_controle.type_fraude,t_log_controle.cas_de_fraude,t_log_controle.photo_compteur,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.marque_autre,t_log_controle.type_cpteur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_controleur.nom_utilisateur,t_controleur.nom_complet,t_controleur.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs as t_controleur ON t_log_controle.controleur = t_controleur.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE ((t_log_controle.ref_fiche_controle LIKE :begin_search_term OR t_log_controle.ref_fiche_controle LIKE :search_term) or t_log_controle.numero_serie_cpteur Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term  OR e_ville.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term OR e_avenue.libelle Like :search_term OR t_controleur.nom_complet  Like :search_term OR identite_client.phone_number Like :search_term)  and (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au)  and t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre . " ORDER BY t_log_controle.ref_fiche_controle ASC ,t_log_controle.date_controle desc LIMIT :from, :offset";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$begin_search_term = "{$search_term}%";
		$stmt->bindParam(':begin_search_term', $begin_search_term);
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		return $stmt;
	}


	public function countAll_BySearch_advanced($du, $au, $search_term, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT COUNT(*) as total_rows  FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs as t_controleur ON t_log_controle.controleur = t_controleur.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE (t_log_controle.numero_serie_cpteur Like :search_term or  Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term   OR e_ville.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term OR e_avenue.libelle Like :search_term OR t_controleur.nom_complet  Like :search_term OR identite_client.phone_number Like :search_term)  and (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au)  and t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}



	public function search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT t_log_controle.gps_latitude_control,t_log_controle.gps_longitude_control,t_log_controle.ref_fiche_controle,t_log_controle.chef_equipe_control,t_log_controle.is_draft_control,t_log_controle.id_organisme_control,t_log_controle.ref_fiche_identification,t_log_controle.ref_site_controle,t_log_controle.date_controle,t_log_controle.controleur,t_main_data.cvs_id ,t_log_controle.numero_serie_cpteur,DATE_FORMAT(t_log_controle.date_controle,'%d/%m/%Y %H:%i:%S')  as date_controle_fr,t_log_controle.etat_fraude,t_log_controle.type_fraude,t_log_controle.cas_de_fraude,t_log_controle.photo_compteur,t_log_controle.numero_serie_cpteur,t_log_controle.marque_compteur,t_log_controle.marque_autre,t_log_controle.type_cpteur, Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,t_main_data.adresse_id,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_controleur.nom_utilisateur,t_controleur.nom_complet,t_controleur.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs as t_controleur ON t_log_controle.controleur = t_controleur.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.annule=" . $this->is_valid  . $filtre . $user_filtre . "  ORDER BY t_log_controle.date_controle desc LIMIT :from, :offset";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}



	public function countAll_BySearch_advanced_DateOnly($du, $au, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT COUNT(*) as total_rows FROM t_log_controle INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs as t_controleur ON t_log_controle.controleur = t_controleur.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.annule=" . $this->is_valid . $filtre . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}
}
