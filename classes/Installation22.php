<?php
class Installation
{

	// database connection and table name
	private $connection;
	private $table_name = "t_log_installation";

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

	public $id_install;
	public $ref_identific;
	public $cabine;
	public $num_depart;
	public $num_poteau;
	public $type_raccordement;
	public $type_cpteur_raccord;
	public $nbre_alimentation;
	public $section_cable_alimentation;
	public $section_cable_sortie;
	public $presence_inverseur;
	public $marque_cpteur_post_paie;
	public $date_retrait_cpteur_post_paie;
	public $num_serie_cpteur_post_paie;
	public $index_credit_restant_cpteur_post_paie;
	public $marque_cpteur_replaced;
	public $num_serie_cpteur_replaced;
	public $index_credit_restant_cpteur_replaced;
	public $type_defaut;
	public $marque_compteur;
	public $numero_compteur;
	public $type_new_cpteur;
	public $disjoncteur;
	public $replace_client_disjonct;
	public $client_disjonct_amperage;
	public $scelle_un_cpteur;
	public $scelle_deux_coffret;
	public $code_tarif;
	public $commentaire_installateur;
	public $commenteur_controle_blue;
	public $installateur;
	public $chef_equipe;
	public $controleur_blue;
	public $client;
	public $agent_cvs;
	public $n_user_create;
	public $datesys;
	public $date_synchro;
	public $is_sync;
	public $n_user_update;
	public $date_update;
	public $date_pose_scelle;
	public $date_installation;
	public $type_installation;
	public $usage_electricity;
	public $etat_poc;
	public $photo_compteur;
	public $date_debut_installation;
	public $date_fin_installation;
	public $gps_longitude;
	public $gps_latitude;
	public $nom_installateur;
	public $id_equipe;
	public $nom_equipe;
	public $code_installateur;
	public $ref_site_install;
	public $annule;
	public $n_user_annule;
	public $motif_annulation;
	public $statut_installation;
	public $is_autocollant_posed;
	public $id_assign;
	public $post_paie_trouver;
	public $lst_installateurs_secondaire;
	public $index_par_defaut;

	public $approbation_installation;
	public $is_draft_install;

	public function __construct($db)
	{
		$this->connection = $db;
	}




	function Approuver()
	{
		$query = "UPDATE  " . $this->table_name . " SET n_user_approuver=:n_user_approuver ,date_approbation=:date_approbation,approbation_installation=:approbation_installation,commenteur_controle_blue=:commenteur_controle_blue WHERE id_install=:id_install";
		$stmt = $this->connection->prepare($query);
		$this->id_install = (strip_tags($this->id_install));
		$stmt->bindValue(":id_install", $this->id_install);
		$stmt->bindValue(":n_user_approuver", $this->n_user_create);
		$stmt->bindValue(":commenteur_controle_blue", $this->commenteur_controle_blue);
		$stmt->bindValue(":date_approbation", date('Y-m-d H:i:s'));
		$stmt->bindValue(":approbation_installation", '1');
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function Cloturer()
	{
		$query = "UPDATE  " . $this->table_name . " SET n_user_cloture=:n_user_cloture ,date_fin_installation=:date_fin_installation,statut_installation=:statut_installation,comment_cloture=:comment_cloture WHERE id_install=:id_install";
		$stmt = $this->connection->prepare($query);
		$this->id_install = (strip_tags($this->id_install));
		$stmt->bindValue(":id_install", $this->id_install);
		$stmt->bindValue(":statut_installation", '1'); //Cloturer
		$stmt->bindValue(":n_user_cloture", $this->n_user_create);
		$stmt->bindValue(":comment_cloture", $this->commenteur_controle_blue);
		$stmt->bindValue(":date_fin_installation",  $this->datesys);
		if ($stmt->execute()) {
			if (is_array($this->lst_installateurs_secondaire)) {
				//Suppression de tous les Organismes liés au CVS pour actualiser les Organismes selectionnés
				$query_ven = "delete from t_log_installation_users  where ref_inst_=:ref_inst_";
				$stmt_ven = $this->connection->prepare($query_ven);
				$stmt_ven->bindValue(':ref_inst_', $this->id_install);
				$stmt_ven->execute();


				$query = "INSERT INTO t_log_installation_users (ref_sec_user,ref_inst_,ref_user,n_user_create,datesys) values (:ref_sec_user,:ref_inst_,:ref_user,:n_user_create,:datesys);";
				$stmt = $this->connection->prepare($query);
				//$k => $v
				// var_dump($this->n_user_create);
				foreach ($this->lst_installateurs_secondaire as $value) {
					$ref_link = Utils::uniqUid("t_log_installation_users", "ref_sec_user", $this->connection);
					$stmt->bindValue(':ref_sec_user', $ref_link);
					$stmt->bindValue(':ref_inst_', $this->id_install);
					$stmt->bindValue(':ref_user', $value);
					$stmt->bindValue(':n_user_create', $this->n_user_create);
					$stmt->bindValue(':datesys', $this->datesys);
					$stmt->execute();
				}
			}
			return true;
		} else {
			return false;
		}
	}

	function Supprimer()
	{
		$query = "update " . $this->table_name . " set annule=1 WHERE id_install=:id_install";
		$stmt = $this->connection->prepare($query);
		$this->id_install = (strip_tags($this->id_install));
		$stmt->bindParam(":id_install", $this->id_install);
		if ($stmt->execute()) {
			$query = "update t_main_data set  num_compteur_actuel='',est_installer=0 where ref_installation_actuel=:ref_installation_actuel";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":ref_installation_actuel", $this->id_install);
			$stmt->execute();

			return true;
		} else {
			return false;
		}
	}
	/*  function Create($data){
		$value = json_decode($data);        		
         
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET id_install=:id_install,ref_identific=:ref_identific,date_debut_installation=:date_debut_installation,date_fin_installation=:date_fin_installation,p_a=:p_a,nom_installateur=:nom_installateur,nom_equipe=:nom_equipe,numero_compteur=:numero_compteur,photo_compteur=:photo_compteur,marque_compteur=:marque_compteur,datesys=:datesys";
 
        $stmt = $this->connection->prepare($query);
		$value->id_install = $this->uniqUid($this->table_name, "id_install");  
		$value->ref_identific=(strip_tags($value->ref_identific));
		$value->date_debut_installation=(strip_tags($value->date_debut_installation));
		$value->date_fin_installation=(strip_tags($value->date_fin_installation));
		$value->p_a=(strip_tags($value->p_a));
		$value->nom_installateur=(strip_tags($value->nom_installateur));
		$value->id_equipe=(strip_tags($value->id_equipe));
		$value->nom_equipe=(strip_tags($value->nom_equipe));
		$value->numero_compteur=(strip_tags($value->numero_compteur));
		$value->photo_compteur=(strip_tags($value->photo_compteur));
		$value->marque_compteur=(strip_tags($value->marque_compteur));
		//$value->commentaires=(strip_tags($value->commentaires));
		$value->datesys=(strip_tags($value->datesys)); 

		//$this->n_user_create=(strip_tags($this->n_user_create));    
        //$this->datesys = date('Y-m-d H:i:s');
 
        $stmt->bindValue(":id_install", $value->id_install); 
		$stmt->bindValue(":ref_identific", $value->ref_identific);
		$stmt->bindValue(":date_debut_installation", $value->date_debut_installation);
		$stmt->bindValue(":p_a", $value->p_a);
		$stmt->bindValue(":nom_installateur", $value->nom_installateur);
		$stmt->bindValue(":nom_equipe", $value->nom_equipe);
		$stmt->bindValue(":numero_compteur", $value->numero_compteur);
		$stmt->bindValue(":photo_compteur", $value->photo_compteur);
		$stmt->bindValue(":marque_compteur", $value->marque_compteur);
		//$stmt->bindValue(":commentaires", $value->commentaires);
		$stmt->bindValue(":date_fin_installation", date('Y-m-d H:i:s'));
		$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
 
		//$stmt->bindParam(":datesys", $this->datesys);		
         if($stmt->execute()){
			 //Mettre num_compteur abonné
			  $query = "update t_main_data set num_compteur_initial=:num_compteur,num_compteur_actuel=:num_compteur,date_installation_initial=:datesys,date_installation_actuel=:datesys,ref_installation_actuel=:ref_installation_actuel,est_installer=1 where id_=:id_";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
			$stmt->bindValue(":num_compteur", $value->numero_compteur);
			$stmt->bindValue(":ref_installation_actuel", $value->id_install);
			$stmt->bindValue(":id_", $value->ref_identific);
			$stmt->execute();
			 $this->SaveMateriels($value->id_install, $value->lst_materiels);
            $result["error"] = false;
            $result["message"] = 'Création effectuée avec succès';
        }else{
			$result["error"] = true;
            $result["message"] = "L'opératon de la création a échoué.";
        }
		return $result;
    }*/

	function CreateWeb($type_inst)
	{
		$adress_Ent = new AdresseEntity($this->connection);
		$this->numero_compteur = strip_tags($this->numero_compteur);


		//EVITER DUPLICATE COMPTEUR
		$stmt = $this->connection->prepare('SELECT id_,num_compteur_actuel,ref_installation_actuel,ref_dernier_log_controle FROM t_main_data where num_compteur_actuel=?');
		$stmt->bindParam(1, $this->numero_compteur);
		//$stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			//A AJOUTER VERIFIER SI LE NUMERO DU COMPTEUR EXISTE DANS LA TABLE DES COMPTEURS
			//echo 'nothing found';
		} else {
			$result["error"] = true;
			$result["message"] = "Le numéro de série du compteur (" . $this->numero_compteur . ") est déjà assigné à une autre installation.\n Veuillez bien vérifier le numéro saisi. ";
			return $result;
		}

		//EVITER DUPLICATE SCELLE





		//

		//RECUPERATION REF_LAST_INSTALL LOG AND REF_LAST_LOG_CONTROL
		$query = "SELECT id_,ref_installation_actuel,cvs_id,client_id,occupant_id,adresse_id,ref_dernier_log_controle FROM t_main_data where id_=:id_";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":id_", $this->ref_identific);
		$stmt->execute();
		$row_log = $stmt->fetch(PDO::FETCH_ASSOC);
		$ref_last_log_install = $row_log['ref_installation_actuel'];
		$ref_preview_control_found = $row_log['ref_dernier_log_controle'];
		$cvs_controler = $row_log['cvs_id'];
		$client_info = $adress_Ent->GetMenageDetail($row_log['client_id']);
		$client_controler = $client_info['noms'];
		$adresse_controler = $row_log['adresse_id'];
		///////





		$query = "INSERT INTO " . $this->table_name . " SET id_install=:id_install,code_tarif=:code_tarif,index_par_defaut=:index_par_defaut,ref_identific=:ref_identific,cabine=:cabine,num_depart=:num_depart,num_poteau=:num_poteau,type_raccordement=:type_raccordement,type_cpteur_raccord=:type_cpteur_raccord,nbre_alimentation=:nbre_alimentation,section_cable_alimentation=:section_cable_alimentation,section_cable_sortie=:section_cable_sortie,presence_inverseur=:presence_inverseur,marque_cpteur_post_paie=:marque_cpteur_post_paie,date_retrait_cpteur_post_paie=now(),num_serie_cpteur_post_paie=:num_serie_cpteur_post_paie,index_credit_restant_cpteur_post_paie=:index_credit_restant_cpteur_post_paie,marque_cpteur_replaced=:marque_cpteur_replaced,num_serie_cpteur_replaced=:num_serie_cpteur_replaced,index_credit_restant_cpteur_replaced=:index_credit_restant_cpteur_replaced,type_defaut=:type_defaut,marque_compteur=:marque_compteur,numero_compteur=:numero_compteur,type_new_cpteur=:type_new_cpteur,disjoncteur=:disjoncteur,replace_client_disjonct=:replace_client_disjonct,client_disjonct_amperage=:client_disjonct_amperage,scelle_un_cpteur=:scelle_un_cpteur,scelle_deux_coffret=:scelle_deux_coffret,commentaire_installateur=:commentaire_installateur,commenteur_controle_blue=:commenteur_controle_blue,installateur=:installateur,chef_equipe=:chef_equipe,controleur_blue=:controleur_blue,agent_cvs=:agent_cvs,n_user_create=:n_user_create,datesys=:datesys,is_sync=:is_sync,date_pose_scelle=now(),type_installation=:type_installation,usage_electricity=:usage_electricity,etat_poc=:etat_poc,photo_compteur=:photo_compteur,date_debut_installation=:date_debut_installation,date_fin_installation=:date_fin_installation,gps_longitude=:gps_longitude,gps_latitude=:gps_latitude,code_installateur=:code_installateur,id_equipe=:id_equipe,nom_equipe=:nom_equipe,ref_site_install=:ref_site_install,is_autocollant_posed=:is_autocollant_posed,post_paie_trouver=:post_paie_trouver,is_draft_install=:is_draft_install";

		$stmt = $this->connection->prepare($query);
		$this->id_install = $this->uniqUid($this->table_name, "id_install");
		$this->id_install = strip_tags($this->id_install);
		$this->is_draft_install = strip_tags($this->is_draft_install);
		$this->ref_identific = strip_tags($this->ref_identific);
		$this->post_paie_trouver = strip_tags($this->post_paie_trouver);
		$this->cabine = strip_tags($this->cabine);
		$this->num_depart = strip_tags($this->num_depart);
		$this->num_poteau = strip_tags($this->num_poteau);
		$this->type_raccordement = strip_tags($this->type_raccordement);
		$this->type_cpteur_raccord = strip_tags($this->type_cpteur_raccord);
		$this->nbre_alimentation = strip_tags($this->nbre_alimentation);
		$this->section_cable_alimentation = strip_tags($this->section_cable_alimentation);
		$this->section_cable_sortie = strip_tags($this->section_cable_sortie);
		$this->presence_inverseur = strip_tags($this->presence_inverseur);
		$this->marque_cpteur_post_paie = strip_tags($this->marque_cpteur_post_paie);
		//$this->date_retrait_cpteur_post_paie=strip_tags($this->date_retrait_cpteur_post_paie);
		$this->num_serie_cpteur_post_paie = strip_tags($this->num_serie_cpteur_post_paie);
		$this->index_credit_restant_cpteur_post_paie = strip_tags($this->index_credit_restant_cpteur_post_paie);
		$this->marque_cpteur_replaced = strip_tags($this->marque_cpteur_replaced);
		$this->num_serie_cpteur_replaced = strip_tags($this->num_serie_cpteur_replaced);
		$this->index_credit_restant_cpteur_replaced = strip_tags($this->index_credit_restant_cpteur_replaced);
		$this->type_defaut = strip_tags($this->type_defaut);
		$this->marque_compteur = strip_tags($this->marque_compteur);

		$this->type_new_cpteur = strip_tags($this->type_new_cpteur);
		$this->disjoncteur = strip_tags($this->disjoncteur);
		$this->replace_client_disjonct = strip_tags($this->replace_client_disjonct);
		$this->client_disjonct_amperage = strip_tags($this->client_disjonct_amperage);
		$this->scelle_un_cpteur = strip_tags($this->scelle_un_cpteur);
		$this->scelle_deux_coffret = strip_tags($this->scelle_deux_coffret);
		$this->commentaire_installateur = strip_tags($this->commentaire_installateur);
		$this->commenteur_controle_blue = strip_tags($this->commenteur_controle_blue);
		$this->installateur = strip_tags($this->installateur);
		$this->chef_equipe = strip_tags($this->chef_equipe);
		$this->controleur_blue = strip_tags($this->controleur_blue);
		$this->agent_cvs = strip_tags($this->agent_cvs);
		$this->n_user_create = strip_tags($this->n_user_create);
		$this->is_sync = 0; //strip_tags($this->is_sync);
		//$this->date_pose_scelle=strip_tags($this->date_pose_scelle);
		$this->type_installation = $type_inst; //0 installation - 1 remplacement;//strip_tags($this->type_installation);
		$this->usage_electricity = strip_tags($this->usage_electricity);
		$this->etat_poc = strip_tags($this->etat_poc);
		$this->gps_longitude = strip_tags($this->gps_longitude);
		$this->gps_latitude = strip_tags($this->gps_latitude);
		$this->code_installateur = strip_tags($this->code_installateur);
		$this->id_equipe = strip_tags($this->id_equipe);
		$this->nom_equipe = strip_tags($this->nom_equipe);
		//$this->code_installateur=strip_tags($this->code_installateur);
		$this->index_par_defaut = strip_tags($this->index_par_defaut);
		$this->ref_site_install = strip_tags($this->ref_site_install);
		$this->code_tarif = strip_tags($this->code_tarif);
		$this->is_autocollant_posed = strip_tags($this->is_autocollant_posed);
		$this->photo_compteur = $this->id_install . '_inst.png';


		$this->date_debut_installation = date('Y-m-d H:i:s');
		$this->date_fin_installation = $this->date_debut_installation;
		//strip_tags($this->date_debut_installation);
		/*if($this->statut_installation == '1'){  //terminé
		  $this->date_fin_installation = date('Y-m-d H:i:s');//strip_tags($this->date_fin_installation);
		}*/

		$stmt->bindValue(":id_install", $this->id_install);
		$stmt->bindValue(":ref_identific", $this->ref_identific);
		$stmt->bindValue(":post_paie_trouver", $this->post_paie_trouver);
		$stmt->bindValue(":is_draft_install", $this->is_draft_install);
		$stmt->bindValue(":cabine", $this->cabine);
		$stmt->bindValue(":num_depart", $this->num_depart);
		$stmt->bindValue(":num_poteau", $this->num_poteau);
		$stmt->bindValue(":type_raccordement", $this->type_raccordement);
		$stmt->bindValue(":type_cpteur_raccord", $this->type_cpteur_raccord);
		$stmt->bindValue(":nbre_alimentation", $this->nbre_alimentation);
		$stmt->bindValue(":section_cable_alimentation", $this->section_cable_alimentation);
		$stmt->bindValue(":section_cable_sortie", $this->section_cable_sortie);
		$stmt->bindValue(":presence_inverseur", $this->presence_inverseur);
		$stmt->bindValue(":marque_cpteur_post_paie", $this->marque_cpteur_post_paie);
		//$stmt->bindValue(":date_retrait_cpteur_post_paie",$this->date_retrait_cpteur_post_paie);
		$stmt->bindValue(":num_serie_cpteur_post_paie", $this->num_serie_cpteur_post_paie);
		$stmt->bindValue(":index_credit_restant_cpteur_post_paie", $this->index_credit_restant_cpteur_post_paie);
		$stmt->bindValue(":marque_cpteur_replaced", $this->marque_cpteur_replaced);
		$stmt->bindValue(":num_serie_cpteur_replaced", $this->num_serie_cpteur_replaced);
		$stmt->bindValue(":index_credit_restant_cpteur_replaced", $this->index_credit_restant_cpteur_replaced);
		$stmt->bindValue(":type_defaut", $this->type_defaut);
		$stmt->bindValue(":marque_compteur", $this->marque_compteur);
		$stmt->bindValue(":numero_compteur", $this->numero_compteur);
		$stmt->bindValue(":type_new_cpteur", $this->type_new_cpteur);
		$stmt->bindValue(":disjoncteur", $this->disjoncteur);
		$stmt->bindValue(":replace_client_disjonct", $this->replace_client_disjonct);
		$stmt->bindValue(":client_disjonct_amperage", $this->client_disjonct_amperage);
		$stmt->bindValue(":scelle_un_cpteur", $this->scelle_un_cpteur);
		$stmt->bindValue(":scelle_deux_coffret", $this->scelle_deux_coffret);
		$stmt->bindValue(":commentaire_installateur", $this->commentaire_installateur);
		$stmt->bindValue(":commenteur_controle_blue", $this->commenteur_controle_blue);
		$stmt->bindValue(":installateur", $this->installateur);
		$stmt->bindValue(":chef_equipe", $this->chef_equipe);
		$stmt->bindValue(":controleur_blue", $this->controleur_blue);
		$stmt->bindValue(":agent_cvs", $this->agent_cvs);
		$stmt->bindValue(":n_user_create", $this->n_user_create);
		$stmt->bindValue(":is_sync", $this->is_sync);
		//$stmt->bindValue(":date_pose_scelle",$this->date_pose_scelle);
		$stmt->bindValue(":type_installation", $type_inst); //$this->type_installation);
		$stmt->bindValue(":usage_electricity", $this->usage_electricity);
		$stmt->bindValue(":etat_poc", $this->etat_poc);
		$stmt->bindValue(":photo_compteur", $this->photo_compteur);
		$stmt->bindValue(":date_debut_installation", $this->date_debut_installation);
		$stmt->bindValue(":date_fin_installation", $this->date_fin_installation);
		$stmt->bindValue(":gps_longitude", $this->gps_longitude);
		$stmt->bindValue(":gps_latitude", $this->gps_latitude);
		$stmt->bindValue(":code_installateur", $this->code_installateur);
		$stmt->bindValue(":id_equipe", $this->id_equipe);
		$stmt->bindValue(":nom_equipe", $this->nom_equipe);
		//$stmt->bindValue(":code_installateur",$this->code_installateur);
		$stmt->bindValue(":ref_site_install", $this->ref_site_install);
		//$stmt->bindValue(":statut_installation",$this->statut_installation);
		$stmt->bindValue(":is_autocollant_posed", $this->is_autocollant_posed);
		$stmt->bindValue(":index_par_defaut", $this->index_par_defaut);
		$stmt->bindValue(":code_tarif", $this->code_tarif);

		$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));

		//$stmt->bindParam(":datesys", $this->datesys);		
		if ($stmt->execute()) {
			//Mettre num_compteur abonné
			$query = "update t_main_data set num_compteur_initial=:num_compteur,num_compteur_actuel=:num_compteur,date_installation_initial=:datesys,date_installation_actuel=:datesys,ref_installation_actuel=:ref_installation_actuel,est_installer=1 where id_=:id_";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
			$stmt->bindValue(":num_compteur", $this->numero_compteur);
			$stmt->bindValue(":ref_installation_actuel", $this->id_install);
			$stmt->bindValue(":id_", $this->ref_identific);
			$stmt->execute();

			//Modification validité assignation
			$query = "update t_param_assignation set ref_execution=:ref_execution,date_execution=:date_execution,statut_=1,is_valid=:is_valid where id_assign=:id_";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_execution", $this->id_install);
			$stmt->bindValue(":is_valid", 0); //Invalidation
			$stmt->bindValue(":id_", $this->id_assign);
			$stmt->execute();

			//CHANGER ETAT MAINDATA EN NON ASSIGNE APRES EXECUTION
			$query = "update t_main_data set deja_assigner=0  where id_=:id_";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":id_", $this->ref_identific);
			$stmt->execute();


			//Generation Demande Ticket
			$ref_log = $this->uniqUid('t_param_notification_log', "ref_log");
			$query = "INSERT INTO t_param_notification_log SET ref_log=:ref_log,ref_identif=:ref_identif,statuts_notification=:statuts_notification,type_notification=:type_notification,id_site=:id_site,n_user_create=:n_user_create,num_compteur=:num_compteur,ref_transaction=:ref_transaction,datesys=:datesys,cvs_id=:cvs_id,nom_client=:nom_client,adresse_id=:adresse";
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




			//CHANGER STATUT COMPTEUR NEW
			$E_item_cpteur = new Compteurs($this->connection);
			$E_stmt = $E_item_cpteur->GetCompteurInfo($this->numero_compteur);
			$ref_produit_series = $E_stmt['ref_produit_series'];
			//(1)Non installé - (2)Installé - (3)Accepté - (4)Déclassé
			$query = "update t_param_liste_compteurs set log_type=:log_type,ref_fiche_ident_actuel=:ref_fiche_ident_actuel,date_actuelle_affectation=:date_actuelle_affectation   where ref_produit_series=:ref_produit_series";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_produit_series", $ref_produit_series);
			$stmt->bindValue(":date_actuelle_affectation", $this->date_debut_installation);
			$stmt->bindValue(":ref_fiche_ident_actuel", $this->ref_identific);
			$stmt->bindValue(":log_type", '2'); //Installation
			$stmt->execute();

			///HISTORISATION COMPTEUR LIFE	
			$ref_log_compteur_life = $this->uniqUid('t_log_life_compteur', "ref_log_compteur_life");
			$query = "INSERT INTO t_log_life_compteur set ref_log_compteur_life=:ref_log_compteur_life,ref_id_compteur=:ref_id_compteur,type_log=:type_log,ref_adresse=:ref_adresse,status_compteur=:status_compteur,ref_organisme=:ref_organisme,client=:client,ref_fiche_ident_actuel=:ref_fiche_ident_actuel,datesys=:datesys,n_user_create=:n_user_create";
			$stmt = $this->connection->prepare($query);
			//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
			$stmt->bindValue(":ref_log_compteur_life", $ref_log_compteur_life);
			$stmt->bindValue(":status_compteur", '0'); //SVC - Hors SVC
			$stmt->bindValue(":ref_organisme", $this->id_equipe);
			$stmt->bindValue(":client", $client_controler);
			$stmt->bindValue(":ref_id_compteur", $ref_produit_series);
			$stmt->bindValue(":type_log", '2'); //Installation 
			$stmt->bindValue(":datesys", $this->date_debut_installation);
			$stmt->bindValue(":ref_fiche_ident_actuel", $this->ref_identific);
			$stmt->bindValue(":ref_adresse", $adresse_controler);
			$stmt->bindValue(":n_user_create", $this->n_user_create);
			$stmt->execute();

			///LOG COMPTEUR A DECLASSER
			if ($type_inst == 1) { //REMPLACEMENT
				//CHANGER STATUT COMPTEUR NEW 
				$E_stmt = $E_item_cpteur->GetCompteurInfo($this->num_serie_cpteur_replaced);
				$ref_produit_series = $E_stmt['ref_produit_series'];
				//(1)Non installé - (2)Installé - (3)Accepté - (4)Déclassé
				$query = "update t_param_liste_compteurs set log_type=:log_type,ref_fiche_ident_actuel=:ref_fiche_ident_actuel,date_actuelle_affectation=:date_actuelle_affectation   where ref_produit_series=:ref_produit_series";
				$stmt = $this->connection->prepare($query);
				//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
				$stmt->bindValue(":ref_produit_series", $ref_produit_series);
				$stmt->bindValue(":date_actuelle_affectation", $this->date_debut_installation);
				$stmt->bindValue(":ref_fiche_ident_actuel", $this->ref_identific);
				$stmt->bindValue(":log_type", '3'); //REMPLACEMENT
				$stmt->execute();

				///HISTORISATION COMPTEUR LIFE	
				$ref_log_compteur_life = $this->uniqUid('t_log_life_compteur', "ref_log_compteur_life");
				$query = "INSERT INTO t_log_life_compteur set ref_log_compteur_life=:ref_log_compteur_life,ref_id_compteur=:ref_id_compteur,type_log=:type_log,ref_adresse=:ref_adresse,status_compteur=:status_compteur,ref_organisme=:ref_organisme,client=:client,ref_fiche_ident_actuel=:ref_fiche_ident_actuel,serie_cpteur_replaced=:serie_cpteur_replaced,datesys=:datesys,n_user_create=:n_user_create";
				$stmt = $this->connection->prepare($query);
				//$stmt->bindValue(":date_execution", date('Y-m-d H:i:s'));
				$stmt->bindValue(":ref_log_compteur_life", $ref_log_compteur_life);
				$stmt->bindValue(":status_compteur", '0'); //SVC - Hors SVC
				$stmt->bindValue(":ref_organisme", $this->id_equipe);
				$stmt->bindValue(":client", $client_controler);
				$stmt->bindValue(":ref_id_compteur", $ref_produit_series);
				$stmt->bindValue(":type_log", '3'); //REMPLACEMENT

				$stmt->bindValue(":serie_cpteur_replaced", $this->numero_compteur);
				$stmt->bindValue(":datesys", $this->date_debut_installation);
				$stmt->bindValue(":ref_fiche_ident_actuel", $this->ref_identific);
				$stmt->bindValue(":ref_adresse", $adresse_controler);
				$stmt->bindValue(":n_user_create", $this->n_user_create);
				$stmt->execute();
			}





			$this->SaveMateriels($this->id_install, $this->lst_materiels);
			$result["error"] = false;
			$result["message"] = 'Pré-Validation effectuée avec succès';
			$result["id"] = $this->id_install;
		} else {
			$result["error"] = true;
			$result["message"] = "L'opératon de la création a échoué.";
		}
		return $result;
	}


	function Modifier()
	{
		//EVITER DUPLICATE COMPTEUR
		$stmt = $this->connection->prepare('SELECT id_,num_compteur_actuel,ref_installation_actuel,ref_dernier_log_controle FROM t_main_data where num_compteur_actuel=?');
		$stmt->bindParam(1, $this->numero_compteur);
		//$stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			//A AJOUTER VERIFIER SI LE NUMERO DU COMPTEUR EXISTE DANS LA TABLE DES COMPTEURS
			// 
		} else {
			//Verifier si numero compteur affecter à un abonné autre que celui en cours 
			if ($row["id_"] != $this->ref_identific) {
				$result["error"] = true;
				$result["message"] = "Le numéro de série du compteur (" . $this->numero_compteur . ") est déjà assigné à une autre installation.\n Veuillez bien vérifier le numéro saisi. ";
				return $result;
			}
		}

		//EVITER DUPLICATE SCELLE


		//EVITER DUPLICATE SCELLE

		$query = "UPDATE " . $this->table_name . " SET  ref_identific=:ref_identific,code_tarif=:code_tarif,index_par_defaut=:index_par_defaut,cabine=:cabine,num_depart=:num_depart,num_poteau=:num_poteau,type_raccordement=:type_raccordement,type_cpteur_raccord=:type_cpteur_raccord,nbre_alimentation=:nbre_alimentation,section_cable_alimentation=:section_cable_alimentation,section_cable_sortie=:section_cable_sortie,presence_inverseur=:presence_inverseur,marque_cpteur_post_paie=:marque_cpteur_post_paie,num_serie_cpteur_post_paie=:num_serie_cpteur_post_paie,index_credit_restant_cpteur_post_paie=:index_credit_restant_cpteur_post_paie,marque_cpteur_replaced=:marque_cpteur_replaced,num_serie_cpteur_replaced=:num_serie_cpteur_replaced,index_credit_restant_cpteur_replaced=:index_credit_restant_cpteur_replaced,marque_compteur=:marque_compteur,numero_compteur=:numero_compteur,type_new_cpteur=:type_new_cpteur,disjoncteur=:disjoncteur,replace_client_disjonct=:replace_client_disjonct,client_disjonct_amperage=:client_disjonct_amperage,scelle_un_cpteur=:scelle_un_cpteur,scelle_deux_coffret=:scelle_deux_coffret,commentaire_installateur=:commentaire_installateur,commenteur_controle_blue=:commenteur_controle_blue,installateur=:installateur,chef_equipe=:chef_equipe,controleur_blue=:controleur_blue,agent_cvs=:agent_cvs,n_user_update=:n_user_update,date_update=:date_update,is_sync=:is_sync,usage_electricity=:usage_electricity,etat_poc=:etat_poc,photo_compteur=:photo_compteur,gps_longitude=:gps_longitude,gps_latitude=:gps_latitude,code_installateur=:code_installateur,id_equipe=:id_equipe,nom_equipe=:nom_equipe,is_autocollant_posed=:is_autocollant_posed ,is_draft_install=:is_draft_install  WHERE id_install=:id_install";
		//  date_fin_installation=(case when coalesce(date_fin_installation,'') != '' then date_fin_installation else (case when :statut_installation = '1' then  :date_fin else '' end) end),
		$stmt = $this->connection->prepare($query);
		//$this->id_install = $this->uniqUid($this->table_name, "id_install"); 
		$this->id_install = strip_tags($this->id_install);
		$this->ref_identific = strip_tags($this->ref_identific);
		$this->cabine = strip_tags($this->cabine);
		$this->is_draft_install = strip_tags($this->is_draft_install);
		$this->num_depart = strip_tags($this->num_depart);
		$this->num_poteau = strip_tags($this->num_poteau);
		$this->type_raccordement = strip_tags($this->type_raccordement);
		$this->type_cpteur_raccord = strip_tags($this->type_cpteur_raccord);
		$this->nbre_alimentation = strip_tags($this->nbre_alimentation);
		$this->section_cable_alimentation = strip_tags($this->section_cable_alimentation);
		$this->section_cable_sortie = strip_tags($this->section_cable_sortie);
		$this->presence_inverseur = strip_tags($this->presence_inverseur);
		$this->marque_cpteur_post_paie = strip_tags($this->marque_cpteur_post_paie);
		//$this->date_retrait_cpteur_post_paie=strip_tags($this->date_retrait_cpteur_post_paie);
		$this->num_serie_cpteur_post_paie = strip_tags($this->num_serie_cpteur_post_paie);
		$this->index_credit_restant_cpteur_post_paie = strip_tags($this->index_credit_restant_cpteur_post_paie);
		$this->marque_cpteur_replaced = strip_tags($this->marque_cpteur_replaced);
		$this->num_serie_cpteur_replaced = strip_tags($this->num_serie_cpteur_replaced);
		$this->index_credit_restant_cpteur_replaced = strip_tags($this->index_credit_restant_cpteur_replaced);
		$this->marque_compteur = strip_tags($this->marque_compteur);
		$this->numero_compteur = strip_tags($this->numero_compteur);
		$this->type_new_cpteur = strip_tags($this->type_new_cpteur);
		$this->disjoncteur = strip_tags($this->disjoncteur);
		$this->replace_client_disjonct = strip_tags($this->replace_client_disjonct);
		$this->client_disjonct_amperage = strip_tags($this->client_disjonct_amperage);
		$this->index_par_defaut = strip_tags($this->index_par_defaut);
		$this->scelle_un_cpteur = strip_tags($this->scelle_un_cpteur);
		$this->scelle_deux_coffret = strip_tags($this->scelle_deux_coffret);
		$this->commentaire_installateur = strip_tags($this->commentaire_installateur);
		$this->commenteur_controle_blue = strip_tags($this->commenteur_controle_blue);
		$this->installateur = strip_tags($this->installateur);
		$this->chef_equipe = strip_tags($this->chef_equipe);
		$this->controleur_blue = strip_tags($this->controleur_blue);
		$this->agent_cvs = strip_tags($this->agent_cvs);
		$this->n_user_update = strip_tags($this->n_user_update);
		$this->is_sync = 0; //strip_tags($this->is_sync);
		//$this->date_pose_scelle=strip_tags($this->date_pose_scelle);
		// $this->type_installation= 0;//strip_tags($this->type_installation);
		$this->usage_electricity = strip_tags($this->usage_electricity);
		$this->etat_poc = strip_tags($this->etat_poc);
		$this->gps_longitude = strip_tags($this->gps_longitude);
		$this->gps_latitude = strip_tags($this->gps_latitude);
		$this->code_installateur = strip_tags($this->code_installateur);
		$this->id_equipe = strip_tags($this->id_equipe);
		$this->nom_equipe = strip_tags($this->nom_equipe);
		//$this->code_installateur=strip_tags($this->code_installateur);
		//$this->ref_site_install=strip_tags($this->ref_site_install);
		//$this->statut_installation=strip_tags($this->statut_installation);
		$this->is_autocollant_posed = strip_tags($this->is_autocollant_posed);
		$this->code_tarif = strip_tags($this->code_tarif);
		$this->photo_compteur = $this->id_install . '_CTR.jpeg';


		$this->date_debut_installation = date('Y-m-d H:i:s'); //strip_tags($this->date_debut_installation);
		/*if($this->statut_installation == '1'){  //terminé
		  $this->date_fin_installation = date('Y-m-d H:i:s'); 
		}
*/
		$stmt->bindValue(":id_install", $this->id_install);
		$stmt->bindValue(":ref_identific", $this->ref_identific);
		$stmt->bindValue(":cabine", $this->cabine);
		$stmt->bindValue(":is_draft_install", $this->is_draft_install);
		$stmt->bindValue(":num_depart", $this->num_depart);
		$stmt->bindValue(":num_poteau", $this->num_poteau);
		$stmt->bindValue(":type_raccordement", $this->type_raccordement);
		$stmt->bindValue(":type_cpteur_raccord", $this->type_cpteur_raccord);
		$stmt->bindValue(":nbre_alimentation", $this->nbre_alimentation);
		$stmt->bindValue(":section_cable_alimentation", $this->section_cable_alimentation);
		$stmt->bindValue(":section_cable_sortie", $this->section_cable_sortie);
		$stmt->bindValue(":presence_inverseur", $this->presence_inverseur);
		$stmt->bindValue(":marque_cpteur_post_paie", $this->marque_cpteur_post_paie);
		//$stmt->bindValue(":date_retrait_cpteur_post_paie",$this->date_retrait_cpteur_post_paie);
		$stmt->bindValue(":num_serie_cpteur_post_paie", $this->num_serie_cpteur_post_paie);
		$stmt->bindValue(":index_credit_restant_cpteur_post_paie", $this->index_credit_restant_cpteur_post_paie);
		$stmt->bindValue(":marque_cpteur_replaced", $this->marque_cpteur_replaced);
		$stmt->bindValue(":num_serie_cpteur_replaced", $this->num_serie_cpteur_replaced);
		$stmt->bindValue(":index_credit_restant_cpteur_replaced", $this->index_credit_restant_cpteur_replaced);
		$stmt->bindValue(":marque_compteur", $this->marque_compteur);
		$stmt->bindValue(":numero_compteur", $this->numero_compteur);
		$stmt->bindValue(":type_new_cpteur", $this->type_new_cpteur);
		$stmt->bindValue(":disjoncteur", $this->disjoncteur);
		$stmt->bindValue(":replace_client_disjonct", $this->replace_client_disjonct);
		$stmt->bindValue(":client_disjonct_amperage", $this->client_disjonct_amperage);
		$stmt->bindValue(":scelle_un_cpteur", $this->scelle_un_cpteur);
		$stmt->bindValue(":scelle_deux_coffret", $this->scelle_deux_coffret);
		$stmt->bindValue(":commentaire_installateur", $this->commentaire_installateur);
		$stmt->bindValue(":commenteur_controle_blue", $this->commenteur_controle_blue);
		$stmt->bindValue(":installateur", $this->installateur);
		$stmt->bindValue(":chef_equipe", $this->chef_equipe);
		$stmt->bindValue(":index_par_defaut", $this->index_par_defaut);
		$stmt->bindValue(":controleur_blue", $this->controleur_blue);
		$stmt->bindValue(":agent_cvs", $this->agent_cvs);
		$stmt->bindValue(":n_user_update", $this->n_user_update);
		$stmt->bindValue(":is_sync", $this->is_sync);
		//$stmt->bindValue(":date_pose_scelle",$this->date_pose_scelle);
		// $stmt->bindValue(":type_installation",$this->type_installation);
		$stmt->bindValue(":usage_electricity", $this->usage_electricity);
		$stmt->bindValue(":etat_poc", $this->etat_poc);
		$stmt->bindValue(":photo_compteur", $this->photo_compteur);
		//$stmt->bindValue(":date_debut_installation",$this->date_debut_installation);
		//$stmt->bindValue(":date_fin",$this->date_fin_installation);
		$stmt->bindValue(":gps_longitude", $this->gps_longitude);
		$stmt->bindValue(":gps_latitude", $this->gps_latitude);
		$stmt->bindValue(":code_installateur", $this->code_installateur);
		$stmt->bindValue(":id_equipe", $this->id_equipe);
		$stmt->bindValue(":nom_equipe", $this->nom_equipe);
		//$stmt->bindValue(":code_installateur",$this->code_installateur);
		//$stmt->bindValue(":ref_site_install",$this->ref_site_install);
		//$stmt->bindValue(":statut_installation",$this->statut_installation);
		$stmt->bindValue(":is_autocollant_posed", $this->is_autocollant_posed);
		$stmt->bindValue(":code_tarif", $this->code_tarif);

		$stmt->bindValue(":date_update", date('Y-m-d H:i:s'));

		//$stmt->bindParam(":datesys", $this->datesys);		
		if ($stmt->execute()) {
			//Mettre num_compteur abonné
			$query = "update t_main_data set num_compteur_actuel=:num_compteur,date_update=:date_update,ref_installation_actuel=:ref_installation_actuel where id_=:id_";
			$stmt = $this->connection->prepare($query);
			$stmt->bindValue(":date_update", date('Y-m-d H:i:s'));
			$stmt->bindValue(":num_compteur", $this->numero_compteur);
			$stmt->bindValue(":ref_installation_actuel", $this->id_install);
			$stmt->bindValue(":id_", $this->ref_identific);
			$stmt->execute();
			$this->SaveMateriels($this->id_install, $this->lst_materiels);
			$result["error"] = false;
			$result["message"] = 'Modification effectuée avec succès';
		} else {
			$result["error"] = true;
			$result["message"] = "L'opératon de la création a échoué.";
		}
		return $result;
	}


	public function SaveMateriels($identif, $materiels)
	{
		if (!is_array($materiels)) {
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


	function GetListeInstalls()
	{
		$query = "SELECT t_log_installation.id_install,t_log_installation.id_equipe, t_log_installation.ref_identific, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_log_installation.p_a, t_log_installation.code_installateur, t_log_installation.nom_installateur, t_log_installation.nom_equipe, t_log_installation.numero_compteur, t_log_installation.photo_compteur, t_log_installation.marque_compteur, t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur, t_main_data.client_id, t_main_data.phone_client_blue, t_main_data.adresse_id, t_main_data.photo_pa_avant, t_main_data.cvs_id,  t_main_data.section_cable, t_main_data.nbre_branchement FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ order by t_log_installation.datesys desc";
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		$result = array();
		$data = array();
		while ($row_spin = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row_spin;
		}
		if (isset($data)) {
			$result["error"] = false;
			$result["message"] = "Opération effectuée avec succès";
			$result["data"] = $data;
		} else {
			$result["error"] = true;
			$result["message"] = "Il n'y a pas de données";
			$result["data"] = null;
		}
		return $result;
	}



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
			$user_filtre = " and code_installateur in (" . $clean . ")";
		} else {
			$user_filtre = " and code_installateur='" . $user_context->code_utilisateur  . "'";
		}
		return $user_filtre;
	}


	function GetUserFilterSearch($user_context)
	{
		$user_filtre = "";
		if ($user_context->id_service_group ==  '3' || $user_context->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($user_context->is_chief == '1') {
			$lst_user_chief = '';
			$row_chief = $user_context->GenerateUserTree($user_context->code_utilisateur);
			if (count($row_chief) > 0) {
				foreach ($row_chief as $item) {
					$lst_user_chief .= "'" . $item . "',";
				}
			}
			$lst_user_chief .= "'non',"; //Compteur migré
			$clean = rtrim($lst_user_chief, ",");
			$user_filtre = " and code_installateur in (" . $clean . ")";
		} else {
			$user_filtre = " and code_installateur='" . $user_context->code_utilisateur  . "'";
		}
		return $user_filtre;
	}

	function GetDetail($id_service_group)
	{
		$photos = array();
		$installateurs_suppl = array();
		$query = "SELECT t_log_installation.id_install, t_log_installation.id_equipe,t_log_installation.code_tarif,t_log_installation.index_par_defaut,t_log_installation.ref_identific, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_main_data.p_a, t_log_installation.nom_installateur, t_log_installation.is_draft_install, t_log_installation.nom_equipe, t_log_installation.numero_compteur,t_main_data.num_compteur_actuel, t_log_installation.photo_compteur, t_log_installation.marque_compteur,  t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur, Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,Concat(coalesce(identite_occupant.nom,''),' ',coalesce(identite_occupant.postnom,''),' ',coalesce(identite_occupant.prenom,'')) as nom_occupant, coalesce(identite_client.phone_number,'-') as phone_client_blue, t_main_data.adresse_id,DATE_FORMAT(t_main_data.date_identification,'%d/%m/%Y %H:%i:%s')  as date_identification_fr, t_main_data.photo_pa_avant, t_main_data.cvs_id, t_main_data.section_cable,t_main_data.nbre_branchement,t_log_installation.cabine,t_log_installation.num_depart,t_log_installation.num_poteau,t_log_installation.type_raccordement,t_log_installation.type_cpteur_raccord,t_log_installation.nbre_alimentation,t_log_installation.section_cable_alimentation,t_log_installation.section_cable_sortie,t_log_installation.presence_inverseur,t_log_installation.marque_cpteur_post_paie,t_log_installation.date_retrait_cpteur_post_paie,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.marque_cpteur_replaced,t_log_installation.num_serie_cpteur_replaced,t_log_installation.index_credit_restant_cpteur_replaced,type_defaut,t_log_installation.type_new_cpteur,t_log_installation.disjoncteur,t_log_installation.replace_client_disjonct,t_log_installation.client_disjonct_amperage,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_main_data.accessibility_client,t_main_data.tarif_identif,t_log_installation.commentaire_installateur,t_log_installation.commenteur_controle_blue,t_log_installation.installateur_id,t_log_installation.installateur,t_log_installation.id_organisme,t_log_installation.organisme,t_log_installation.chef_equipe,t_log_installation.controleur_id,t_log_installation.controleur_blue,t_log_installation.date_pose_scelle,t_log_installation.date_installation,t_log_installation.type_installation,t_log_installation.usage_electricity,t_log_installation.etat_poc,t_main_data.gps_longitude,t_main_data.gps_latitude,t_log_installation.statut_installation,t_log_installation.is_autocollant_posed,t_log_installation.post_paie_trouver,t_log_installation.approbation_installation FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  INNER JOIN t_param_identite AS identite_occupant ON t_main_data.occupant_id = identite_occupant.id WHERE t_log_installation.id_install = ?
			LIMIT 0,1";
		$result = array();
		$items = array();
		$stmt = $this->connection->prepare($query);
		$this->id_install = (strip_tags($this->id_install));
		$stmt->bindParam(1, $this->id_install);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$result["error"] = 0;
		$result["data"] = $row;
		// $result_array["readOnly"]= 0;		
		$result["count"] = $stmt->rowCount();

		// $ref_inst_ =  $row['id_install'];
		$fiche_identif_id =  $row['ref_identific'];
		$adresse_id =  $row['adresse_id'];
		if ($row["statut_installation"]  == 1) { // terminé
			if ($id_service_group ==  '3') {  //Administration			
				$result_array["readOnly"] = 0;
			} else {
				$result_array["readOnly"] = 1;
			}
		}
		$query = "select  t_log_installation_materiels.id_mat,t_log_installation_materiels.ref_article, t_log_installation_materiels.qte_identification,t_param_liste_materiels.designation  FROM t_log_installation_materiels INNER JOIN t_param_liste_materiels ON t_log_installation_materiels.ref_article = t_param_liste_materiels.ref_produit where t_log_installation_materiels.ref_identification=:ref_identification";
		/*$query = "select  id_mat,ref_article, qte_identification  FROM t_installation_materiels where ref_identification=:ref_identification"; */
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_identification", $this->id_install);
		$stmt->execute();
		if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$result["error"] = false;
			$result["message"] = "";
			$items[] = $ro;
			while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$items[] = $rw;
			}
		}

		$adress_item = new  AdresseEntity($this->connection);
		//$result["adresse"] = $adress_item->GetAdressInfo($this->adresse_id);
		$result["adresseTexte"] = $adress_item->GetAdressInfoTexte($adresse_id);
		$result["items"] = $items;


		//RECUPERATION PHOTOS GALLERY PA
		$query = "select  ref_photo FROM t_main_data_gallery where ref_fiche=:ref_identification";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_identification", $fiche_identif_id);
		$stmt->execute();
		if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$result["error"] = false;
			$result["message"] = "";
			$photos[] = $ro;
			while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$photos[] = $rw;
			}
		}
		$result["photos"] = $photos;

		//RECUPERATION INSTALLATEURS SUPPLEMENTAIRES
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_log_installation_users.ref_inst_ FROM t_log_installation_users INNER JOIN t_utilisateurs ON t_log_installation_users.ref_user = t_utilisateurs.code_utilisateur where t_log_installation_users.ref_inst_=:ref_inst_";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_inst_", $this->id_install);
		$stmt->execute();
		if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$result["error"] = false;
			$result["message"] = "";
			$photos[] = $ro;
			while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$installateurs_suppl[] = $rw;
			}
		}
		$result["installateurs"] = $installateurs_suppl;



		return $result;
	}
	function GetDetail_Light($ref_install)
	{
		$query = "SELECT DATE_FORMAT(t_log_installation.date_fin_installation,'%d/%m/%Y') AS date_fin_installation_fr,t_log_installation.marque_cpteur_post_paie,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_retrait_cpteur_post_paie,'%Y-%m-%d'),'%d/%m/%Y') as date_retrait_cpteur_post_paie_fr,t_log_installation.num_serie_cpteur_post_paie,t_log_installation.index_credit_restant_cpteur_post_paie,t_log_installation.num_serie_cpteur_replaced,t_log_installation.marque_compteur,t_log_installation.numero_compteur,t_log_installation.scelle_un_cpteur,t_log_installation.scelle_deux_coffret,t_log_installation.type_installation,DATE_FORMAT(STR_TO_DATE(t_log_installation.date_pose_scelle,'%Y-%m-%d'),'%d/%m/%Y') as date_pose_scelle_fr,t_log_installation.statut_installation,t_log_installation.date_pose_scelle,t_log_installation.marque_cpteur_replaced,t_log_installation.is_draft_install,t_log_installation.approbation_installation FROM t_log_installation  WHERE t_log_installation.id_install = ? LIMIT 0,1";
		$stmt = $this->connection->prepare($query);
		$ref_install = (strip_tags($ref_install));
		$stmt->bindParam(1, $ref_install);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
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
		$query = "SELECT t_log_installation.id_install,t_log_installation.is_draft_install,t_log_installation.type_installation,t_log_installation.id_equipe,t_log_installation.type_new_cpteur, t_log_installation.ref_identific,DATE_FORMAT( t_log_installation.date_debut_installation,'%d/%m/%Y %H:%i:%S')  as date_debut_installation_fr, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_log_installation.p_a, t_log_installation.nom_installateur, t_log_installation.nom_equipe, t_log_installation.numero_compteur,t_log_installation.num_serie_cpteur_post_paie,t_main_data.num_compteur_actuel,t_main_data.identificateur, t_log_installation.photo_compteur, t_log_installation.marque_compteur,  t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue, t_main_data.adresse_id, t_main_data.photo_pa_avant, t_main_data.cvs_id, t_main_data.section_cable, t_main_data.nbre_branchement,t_log_installation.statut_installation,t_log_installation.approbation_installation,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  where t_log_installation.ref_site_install=:ref_site_install  and  t_log_installation.annule=0 "  . $filtre  . $user_filtre . " ORDER BY t_log_installation.date_fin_installation DESC LIMIT {$from_record_num}, {$records_per_page}";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		return $stmt;
	}
	public function countAll($user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilter($user_context);
		$query = "SELECT t_log_installation.id_install FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  where t_log_installation.ref_site_install=:ref_site_install   and  t_log_installation.annule=0 "  . $filtre  . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}


	public function search($search_term, $from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilterSearch($user_context);
		$query = "SELECT t_log_installation.id_install,t_log_installation.is_draft_install,t_log_installation.type_installation,t_log_installation.id_equipe,t_log_installation.type_new_cpteur, t_log_installation.ref_identific,DATE_FORMAT( t_log_installation.date_debut_installation,'%d/%m/%Y %H:%i:%S')  as date_debut_installation_fr, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_log_installation.p_a, t_log_installation.nom_installateur, t_log_installation.nom_equipe, t_log_installation.numero_compteur,t_log_installation.num_serie_cpteur_post_paie,t_main_data.num_compteur_actuel, t_log_installation.photo_compteur, t_log_installation.marque_compteur,  t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue, t_main_data.adresse_id, t_main_data.photo_pa_avant, t_main_data.cvs_id, t_main_data.section_cable, t_main_data.nbre_branchement,t_log_installation.statut_installation,t_log_installation.approbation_installation,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur  INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE (Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or t_main_data.id_ Like :search_term OR t_log_installation.id_install Like :search_term OR t_utilisateurs.nom_complet  Like :search_term  OR t_chef_equipe.nom_complet  Like :search_term OR t_log_installation.numero_compteur Like :search_term OR identite_client.phone_number Like :search_term OR DATE_FORMAT(date_fin_installation,'%d/%m/%Y') Like :search_term OR e_avenue.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term)  and  t_log_installation.ref_site_install=:ref_site_install   and  t_log_installation.annule=0 "  . $filtre  . $user_filtre . "	ORDER BY t_log_installation.date_fin_installation DESC LIMIT :from, :offset";
		// or t_main_data.adresse Like :search_term or	
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		return $stmt;
	}

	public function countAll_BySearch($search_term, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilterSearch($user_context);

		// select query or t_main_data.adresse Like :search_term 
		$query = "SELECT COUNT(*) as total_rows   FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme  INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  WHERE (Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or t_main_data.id_ Like :search_term OR t_log_installation.id_install Like :search_term OR t_utilisateurs.nom_complet  Like :search_term  OR t_chef_equipe.nom_complet  Like :search_term OR t_log_installation.numero_compteur Like :search_term OR identite_client.phone_number Like :search_term OR DATE_FORMAT(date_fin_installation,'%d/%m/%Y') Like :search_term OR e_avenue.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term)  and  t_log_installation.ref_site_install=:ref_site_install  and  t_log_installation.annule=0 "  . $filtre  . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}


	public function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilterSearch($user_context);
		$query = "SELECT t_log_installation.id_install,t_log_installation.is_draft_install,t_log_installation.type_installation,t_log_installation.id_equipe,t_log_installation.type_new_cpteur, t_log_installation.ref_identific,DATE_FORMAT( t_log_installation.date_debut_installation,'%d/%m/%Y %H:%i:%S')  as date_debut_installation_fr, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_log_installation.p_a, t_log_installation.nom_installateur, t_log_installation.nom_equipe, t_log_installation.numero_compteur,t_log_installation.num_serie_cpteur_post_paie,t_main_data.num_compteur_actuel, t_log_installation.photo_compteur, t_log_installation.marque_compteur,  t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue, t_main_data.adresse_id, t_main_data.photo_pa_avant, t_main_data.cvs_id, t_main_data.section_cable, t_main_data.nbre_branchement,t_log_installation.statut_installation,t_log_installation.approbation_installation,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur  INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id   WHERE (Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or t_main_data.id_ Like :search_term OR t_log_installation.id_install Like :search_term OR t_utilisateurs.nom_complet  Like :search_term  OR t_chef_equipe.nom_complet  Like :search_term OR t_log_installation.numero_compteur Like :search_term OR identite_client.phone_number Like :search_term OR DATE_FORMAT(date_fin_installation,'%d/%m/%Y') Like :search_term OR e_avenue.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term)  and (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au)  and  t_log_installation.ref_site_install=:ref_site_install  and  t_log_installation.annule=0 "  . $filtre  . $user_filtre . " ORDER BY t_log_installation.date_fin_installation DESC  LIMIT :from, :offset";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->execute();
		return $stmt;
	}


	public function countAll_BySearch_advanced($du, $au, $search_term, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilterSearch($user_context);
		$query = "SELECT COUNT(*) as total_rows  FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur  INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id   WHERE (Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or t_main_data.id_ Like :search_term OR t_log_installation.id_install Like :search_term OR t_utilisateurs.nom_complet  Like :search_term OR t_chef_equipe.nom_complet  Like :search_term OR t_log_installation.numero_compteur Like :search_term OR identite_client.phone_number Like :search_term OR DATE_FORMAT(date_fin_installation,'%d/%m/%Y') Like :search_term OR e_avenue.libelle Like :search_term OR e_commune.libelle Like :search_term OR e_quartier.libelle Like :search_term)  and (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au)  and  t_log_installation.ref_site_install=:ref_site_install  and  t_log_installation.annule=0 "  . $filtre  . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}



	public function search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilterSearch($user_context);

		$query = "SELECT t_log_installation.id_install,t_log_installation.is_draft_install,t_log_installation.type_installation,t_log_installation.id_equipe,t_log_installation.type_new_cpteur, t_log_installation.ref_identific,DATE_FORMAT( t_log_installation.date_debut_installation,'%d/%m/%Y %H:%i:%S')  as date_debut_installation_fr, t_log_installation.date_debut_installation, t_log_installation.date_fin_installation,DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, t_log_installation.p_a, t_log_installation.nom_installateur, t_log_installation.nom_equipe, t_log_installation.numero_compteur,t_log_installation.num_serie_cpteur_post_paie,t_main_data.num_compteur_actuel, t_log_installation.photo_compteur, t_log_installation.marque_compteur,  t_log_installation.datesys, t_log_installation.date_update, t_log_installation.code_installateur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue, t_main_data.adresse_id, t_main_data.photo_pa_avant, t_main_data.cvs_id, t_main_data.section_cable, t_main_data.nbre_branchement,t_log_installation.statut_installation,t_log_installation.approbation_installation,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur  INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au)  and  t_log_installation.ref_site_install=:ref_site_install  and  t_log_installation.annule=0 "  . $filtre  . $user_filtre . " ORDER BY t_log_installation.date_fin_installation DESC LIMIT :from, :offset";

		// echo ($query);
		// exit;
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		return $stmt;
	}



	public function countAll_BySearch_advanced_DateOnly($du, $au, $user_context, $filtre)
	{
		$user_filtre = $this->GetUserFilterSearch($user_context);
		$query = "SELECT COUNT(*) as total_rows FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_  INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur  INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  WHERE (DATE_FORMAT(t_log_installation.date_fin_installation,'%Y-%m-%d')  between :du and :au) and  t_log_installation.ref_site_install=:ref_site_install and  t_log_installation.annule=0 "  . $filtre  . $user_filtre;
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(':du', $du);
		$stmt->bindParam(':au', $au);
		$stmt->bindValue(":ref_site_install", $user_context->site_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}
}
